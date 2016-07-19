package com.seemoreinteractive.virtualshot.helper;

import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import com.seemoreinteractive.virtualshot.MainActivity;
import com.seemoreinteractive.virtualshot.model.Marker;
import com.seemoreinteractive.virtualshot.model.Visual;
import com.seemoreinteractive.virtualshot.model.Visuals;
import com.seemoreinteractive.virtualshot.utils.Constants;
import com.seemoreinteractive.virtualshot.utils.FileTransaction;
import com.seemoreinteractive.virtualshot.utils.LegalPosition;

public class LoadMarkers extends AsyncTask<Void, Void, Void> {
	MainActivity context;
	SharedPreferences mPrefs;

	public LoadMarkers(MainActivity context, SharedPreferences mPrefs) {
		this.context = context;
		this.mPrefs = mPrefs;
	}

	FileTransaction file = new FileTransaction();

	@Override
	protected Void doInBackground(Void... params) {
		try{
		String json = retriveMarkers();
		//Log.e("json", ""+json);
		if (json != null) {
			Visuals newVisuals = populateVisuals(json);
			if (newVisuals != null) {
				Visuals existingVisuals = file.getVisuals();
				existingVisuals.mergeWith(newVisuals);
				if (existingVisuals.getEmptyImageFileVisuals().size() != 0) {
					saveVisualAndMarkerImages(existingVisuals.getEmptyImageFileVisuals());
				}
				file.createXML(existingVisuals);
				file.setVisuals(existingVisuals);
				// file.getListOfFiles();
			}
		}
		}catch(final Exception e){
			context.runOnUiThread(new Runnable() {
				@Override
				public void run() {
					Toast.makeText(context, "Error: LoadMarker doinbackground - while downloading images.", Toast.LENGTH_LONG).show();
					e.printStackTrace();
				}
			});
		}
		return null;
	}

	@Override
	protected void onPostExecute(Void result) {
		if (context != null) {
			context.markersLoadingComplete();
		}
	}

	public String retriveMarkers() {
		try {
			DefaultHttpClient httpClient = new DefaultHttpClient();
			HttpGet httpget = new HttpGet(Constants.TRENDS_CMS_URL);
			HttpResponse httpResponse = httpClient.execute(httpget);
			HttpEntity httpEntity = httpResponse.getEntity();
			return EntityUtils.toString(httpEntity);
		} catch (Exception e) {
			Log.i(Constants.TAG, "Exception in retriveMarkers: " + e);
			Toast.makeText(context, "Error: LoadMarker retriveMarkers - while retriving markers.", Toast.LENGTH_LONG).show();
		}
		return null;
	}

	public Visuals populateVisuals(String json) {
		try {
			Visuals visuals = new Visuals();
			JSONArray array = new JSONArray(json);
			for (int i = 0; i < array.length(); i++) {
				JSONObject imageVisual = (JSONObject) array.get(i);
				JSONObject imageMarker = imageVisual.getJSONObject("marker");
				if (imageMarker.getBoolean("active")) {

					Marker marker = new Marker(imageMarker.getLong("id"), imageMarker.getString("image"), imageMarker.getString("title"),
							imageMarker.getInt("width"), imageMarker.getInt("height"), 0);

					// if (imageMarker.getString("imageFile") != null &&
					// imageMarker.getString("imageFile").length() != 0
					// && imageMarker.getString("imageFile") != "null" && false)
					// {
					// Log.i(Constants.TAG,"Marker Image File: "+imageMarker.getString("imageFile"));
					// marker.setImageFile(imageMarker.getString("imageFile"));
					// }
					Visual visual = new Visual(imageVisual.getLong("id"));
					visual.setImage(imageVisual.getString("image"));
					if (imageVisual.getString("imageFile") != null && imageVisual.getString("imageFile").length() > 0
							&& imageVisual.getString("imageFile") != "null") {
						visual.setImageFile(imageVisual.getString("imageFile"));
					}
					visual.setTitle(imageVisual.getString("title"));
					visual.setX(imageVisual.getInt("x"));
					visual.setY(imageVisual.getInt("y"));
					visual.setLegalSwitch(imageVisual.getBoolean("legalSwitch"));
					// visual.setLegalSwitch(true);
					visual.setLegalImage(imageVisual.getString("legalImage"));
					// visual.setLegalImage("https://seemore-cms.s3.amazonaws.com/cms/Trends/TempLegalImage.png");
					if(!imageVisual.getString("legalPosition").equals("null")){
						visual.setLegalPosition(LegalPosition.fromValue(imageVisual.getJSONObject("legalPosition").getString("name")));
					}

					/*Log.e("marker", ""+ marker.getIndex()+" "+visual.getMarker().getIndex());
					Log.e("imageMarker", ""+imageMarker.getString("title"));
					if(imageMarker.getString("title").equals("DemoMarker")){
						demoMarkerId = (int) marker.getIndex();
						Log.e("marker", ""+ marker.getIndex()+" "+visual.getMarker().getIndex());
						Log.e("demoMarkerId", ""+demoMarkerId);
					}*/
					
					visual.setMarker(marker);
					visuals.add(visual);
				}
			}
			return visuals;
		} catch (final Exception e) {
			context.runOnUiThread(new Runnable() {
				@Override
				public void run() {
					Log.i(Constants.TAG, "Exception in populateVisuals: " + e.getMessage());
					Toast.makeText(context, "Error: LoadMarker populateVisuals - while populating values.", Toast.LENGTH_LONG).show();
					e.printStackTrace();
				}
			});
		}
		return null;
	}

	public void saveVisualAndMarkerImages(List<Visual> visuals) {
		try{
		for (Visual visual : visuals) {
			visual.setImageFile(file.downloadAndSaveImage(visual.getImage(), "v" + visual.getId()));
			visual.getMarker().setImageFile(file.downloadAndSaveImage(visual.getMarker().getImage(), "m" + visual.getMarker().getId()));
			visual.setLegalImageFile(file.downloadAndSaveImage(visual.getLegalImage(), "l" + visual.getId()));
			//Log.i(Constants.TAG, ">>>: " + visual.getMarker().getImageFile());
			if (!mPrefs.getBoolean("is_marker_loaded", false)) {
				if (visual.getImageFile() != null && visual.getMarker().getImageFile() != null) {
					Editor editor = mPrefs.edit();
					editor.putBoolean("is_marker_loaded", true);
					editor.commit();
				}
			}
		}
		}catch(Exception e){
			Toast.makeText(context,"Error:LoadMarker saveVisualAndMarkerImages - while saving values & markers", Toast.LENGTH_LONG).show();
		}
	}

}
