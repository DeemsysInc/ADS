package com.seemoreinteractive.seemoreinteractive;


import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.LayerDrawable;
import android.location.Location;
import android.location.LocationManager;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TabHost;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.TabHost.TabSpec;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.LatLngBounds;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.Polyline;
import com.google.android.gms.maps.model.PolylineOptions;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.helper.GMapV2Direction;
import com.seemoreinteractive.seemoreinteractive.helper.GetDirectionsAsyncTask;

public class FindAStoreItemDetails extends Activity {

	String storeTitle = "", storeAddress = "", storeReference = "", storeDistance = ""/*, storeRating = "", 
			storeWebsite = ""*/, placeDetails = "";
	AQuery aq;
	TextView txtAddress, txtPhone, txtDistance, txtWebsite;
	ImageView imgCallbtn;
	RatingBar ratingBar;
	Intent getIntentRes; 
	Double fromLat,fromLng,toLat,toLng;
	
	 //List<Overlay> mapOverlays;
   //  GeoPoint point1, point2;
     LocationManager locManager;
     Drawable drawable;
     LatLng fromPosition;
     LatLng toPosition;
     GoogleMap mGoogleMap;
     Location location;
     String name;
 	private LatLngBounds latlngBounds;
 	private Polyline newPolyline;
 	String className = this.getClass().getSimpleName();

	public boolean isBackPressed = false;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_find_a_store_item_details);
		
		try{
			 getIntentRes = getIntent();
	
			TextView txtLblAddress = (TextView) findViewById(R.id.txtLblAddress);
			TextView txtLblRating = (TextView) findViewById(R.id.txtLblRating);
			TextView txtLblPhone = (TextView) findViewById(R.id.txtLblPhone);
			TextView txtLblDistance = (TextView) findViewById(R.id.txtLblDistance);
			final TextView txtLblWebsite = (TextView) findViewById(R.id.txtLblWebsite);
			
			txtLblAddress.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtLblRating.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtLblPhone.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtLblDistance.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtLblWebsite.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	
			RelativeLayout.LayoutParams rlpForLblAddress = (RelativeLayout.LayoutParams) txtLblAddress.getLayoutParams();
			rlpForLblAddress.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			rlpForLblAddress.topMargin = (int) (0.0625 * Common.sessionDeviceHeight);
			txtLblAddress.setLayoutParams(rlpForLblAddress);
	
			RelativeLayout.LayoutParams rlpForLblRating = (RelativeLayout.LayoutParams) txtLblRating.getLayoutParams();
			//rlpForLblRating.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			rlpForLblRating.topMargin = (int) (0.094 * Common.sessionDeviceHeight);
			txtLblRating.setLayoutParams(rlpForLblRating);
	
			RelativeLayout.LayoutParams rlpForLblPhone = (RelativeLayout.LayoutParams) txtLblPhone.getLayoutParams();
			//rlpForLblPhone.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			rlpForLblPhone.topMargin = (int) (0.0625 * Common.sessionDeviceHeight);
			txtLblPhone.setLayoutParams(rlpForLblPhone);
	
			RelativeLayout.LayoutParams rlpForLblDistance = (RelativeLayout.LayoutParams) txtLblDistance.getLayoutParams();
			//rlpForLblDistance.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			rlpForLblDistance.topMargin = (int) (0.0625 * Common.sessionDeviceHeight);
			txtLblDistance.setLayoutParams(rlpForLblDistance);
	
			RelativeLayout.LayoutParams rlpForLblWebsite = (RelativeLayout.LayoutParams) txtLblWebsite.getLayoutParams();
			//rlpForLblWebsite.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
			rlpForLblWebsite.topMargin = (int) (0.0625 * Common.sessionDeviceHeight);
			txtLblWebsite.setLayoutParams(rlpForLblWebsite);
			
			imgCallbtn = (ImageView) findViewById(R.id.callbtn);
			RelativeLayout.LayoutParams rlpForimgCallbtn = (RelativeLayout.LayoutParams) imgCallbtn.getLayoutParams();
			rlpForimgCallbtn.rightMargin = (int) (0.0912 * Common.sessionDeviceWidth);		
			rlpForimgCallbtn.width =  (int) (0.0834 * Common.sessionDeviceWidth);
			rlpForimgCallbtn.height =  (int) (0.053 * Common.sessionDeviceHeight);
			txtLblWebsite.setLayoutParams(rlpForLblWebsite);
			 
			//txtLbWebsite  = (TextView) findViewById(R.id.txtAddress);
			txtAddress = (TextView) findViewById(R.id.txtAddress);
			
			txtPhone = (TextView) findViewById(R.id.txtPhone);
			txtDistance = (TextView) findViewById(R.id.txtDistance);
			txtWebsite = (TextView) findViewById(R.id.txtWebsite);
			ratingBar = (RatingBar) findViewById(R.id.ratingBar1);
			LayerDrawable stars = (LayerDrawable) ratingBar.getProgressDrawable();
			stars.getDrawable(2).setColorFilter(Color.parseColor("#FF9900"), PorterDuff.Mode.SRC_ATOP);
			
			txtAddress.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			//txtRating.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtPhone.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtDistance.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			txtWebsite.setTextSize((float)(0.042 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
			RelativeLayout.LayoutParams rlpForAddress = (RelativeLayout.LayoutParams) txtAddress.getLayoutParams();
			rlpForAddress.leftMargin = (int) (0.15 * Common.sessionDeviceWidth);
			//rlpForAddress.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
			txtAddress.setLayoutParams(rlpForAddress);
			
			//Log.e("storeDistance", getIntentRes.getStringExtra("storeDistance"));
						
			if(getIntentRes.getStringExtra("storeTitle")!=null){
				storeTitle = getIntentRes.getStringExtra("storeTitle");
			}
			if(getIntentRes.getStringExtra("storeAddress")!=null){
				storeAddress = getIntentRes.getStringExtra("storeAddress");
			}
			if(getIntentRes.getStringExtra("storeReference")!=null){
				storeReference = getIntentRes.getStringExtra("storeReference");
			}
			if(getIntentRes.getStringExtra("storeDistance")!=null){
				storeDistance = getIntentRes.getStringExtra("storeDistance");
			}
			if(getIntentRes.getStringExtra("lat")!=null){
				fromLat = Double.parseDouble(getIntentRes.getStringExtra("lat"));
			}
			if(getIntentRes.getStringExtra("lng")!=null){
				fromLng = Double.parseDouble(getIntentRes.getStringExtra("lng"));
			}
			/*if(getIntentRes.getStringExtra("storeDistance")!=null){
				storeDistance = getIntentRes.getStringExtra("storeDistance");
			}
			if(getIntentRes.getStringExtra("storeWebsite")!=null){
				storeWebsite = getIntentRes.getStringExtra("storeWebsite");
			}*/
	
			aq = new AQuery(this);
			placeDetails = "https://maps.googleapis.com/maps/api/place/details/xml?reference="+storeReference+"&sensor=true&key="+Constants.GOOGLE_API_KEY;
			Log.i("places url", ""+placeDetails);
			aq.ajax(placeDetails, XmlDom.class, new AjaxCallback<XmlDom>(){			
				@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
					try{
						Log.i("XmlDom", ""+xml);
	    				if(xml!=null){
	    					List<XmlDom> xmlLv = xml.tags("result");
	    					if(xmlLv.size()>0){
	    						for(XmlDom placeDetailsInXml: xmlLv){
	    							if(placeDetailsInXml.tag("name")!=null){
	    							 name =placeDetailsInXml.text("name").toString();
	    							}
	    							if(placeDetailsInXml.tag("formatted_address")!=null){
	    								txtAddress.setText(placeDetailsInXml.text("formatted_address").toString());
	    							}else if(placeDetailsInXml.tag("vicinity")!=null){
	    								txtAddress.setText(placeDetailsInXml.text("vicinity").toString());
	    							}else{
	    								txtAddress.setText("      --     ");
	    							}
	    							if(placeDetailsInXml.tag("user_ratings_total")!=null){
	    								//txtRating.setText(placeDetailsInXml.text("user_ratings_total").toString());
	    								ratingBar.setRating(Float.parseFloat(placeDetailsInXml.text("user_ratings_total").toString()));
	    								//Log.e("placeDetailsInXml.tag",placeDetailsInXml.tag("user_ratings_total").toString());
		    							
	    							}
	    							if(placeDetailsInXml.tag("international_phone_number")!=null){
	    								txtPhone.setText(placeDetailsInXml.text("international_phone_number").toString());
	    								
	    								imgCallbtn.setVisibility(View.VISIBLE);
	    								imgCallbtn.setOnClickListener(new OnClickListener() {
											
											@Override
											public void onClick(View v) {
												Intent callIntent = new Intent(Intent.ACTION_CALL);
												callIntent.setData(Uri.parse("tel://"+txtPhone.getText().toString()));
							                   	startActivity(callIntent);
											}
										});
	    							}else{
	    								txtPhone.setText("      --     ");
	    							}
	    							/*if(placeDetailsInXml.tag("storeDistance")!=null){
	    								txtDistance.setText(placeDetailsInXml.text("storeDistance").toString());
	    							}*/
	    							if(getIntentRes.getStringExtra("storeDistance")!=null){
	    								txtDistance.setText(storeDistance);
	    							}else{
	    								txtDistance.setText("      --     ");
	    							}
	    							if(placeDetailsInXml.tag("website")!=null){
	    								txtWebsite.setText(placeDetailsInXml.text("website").toString());
	    							}else{
	    								txtWebsite.setText("      --     ");
	    							}
	    							
	    							if(placeDetailsInXml.tag("geometry").tag("location").text("lat").toString() !=null){	    								
	    								toLat = Double.parseDouble(placeDetailsInXml.tag("geometry").tag("location").text("lat").toString());
	    							}
	    							
	    							if(placeDetailsInXml.tag("geometry").tag("location").text("lng").toString() !=null){	    								
	    								toLng = Double.parseDouble(placeDetailsInXml.tag("geometry").tag("location").text("lng").toString());
	    							}
	    						}
	    						        
	    				         mGoogleMap = ((MapFragment) getFragmentManager().findFragmentById(R.id.map)).getMap();	    				        
	    				         mGoogleMap.setMyLocationEnabled(true);	        
	    			           
	    			            fromPosition = new LatLng(fromLat, fromLng);
	    			            toPosition = new LatLng(toLat, toLng);
	    			            
	    			            
	    			            mGoogleMap.addMarker(new MarkerOptions()
	    		                .position(fromPosition)
	    		                .title("You are here")
	    		                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_VIOLET)));
	    			            
	    			            mGoogleMap.addMarker(new MarkerOptions()
	    		                .position(toPosition)
	    		                .title(name)
	    		                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_RED)));
	    			            
	    			            if(toPosition != null){
	    							latlngBounds = createLatLngBoundsObject(fromPosition, toPosition);
	    							mGoogleMap.moveCamera(CameraUpdateFactory.newLatLngBounds(latlngBounds, Common.sessionDeviceWidth, Common.sessionDeviceHeight, 10));
	    						}
	    			            findDirections( fromPosition.latitude, fromPosition.longitude,toPosition.latitude, toPosition.longitude, GMapV2Direction.MODE_DRIVING );
	    			            
	    					}
	    				}
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate  ajax call|   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
					}
				}
			});
	
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					"", storeTitle, "");
			
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
	    	imgBtnCart.setImageBitmap(null);
	    	
	    	ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
	        imgBackButton.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					try{						
			        	finish();
			        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();
						Toast.makeText(getApplicationContext(), "Error: Find A Store Details imgBackButton.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" |imgBackButton click|   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
					}
				}
			});
	    	
			
			
			final TabHost tab_host = (TabHost) findViewById(R.id.tabhost);
	        // don't forget this setup before adding tabs from a tabhost using a xml view or you'll get an nullpointer exception
	        tab_host.setup(); 
	
	        TabSpec ts1 = tab_host.newTabSpec("TAB_INFO");
	        ts1.setIndicator("Information");
	        ts1.setContent(R.id.tab1);
	        tab_host.addTab(ts1);
	
	        TabSpec ts2 = tab_host.newTabSpec("TAB_DIRECTION");
	        ts2.setIndicator("Directions");
	        ts2.setContent(R.id.tab2);
	        tab_host.addTab(ts2);
	        tab_host.setCurrentTab(0);
	        	        
	       for (int i = 0; i < tab_host.getTabWidget().getChildCount(); i++) {
	        	tab_host.getTabWidget().getChildAt(1).setBackgroundColor(Color.WHITE); 
	        	TextView tv = (TextView) tab_host.getTabWidget().getChildAt(i).findViewById(android.R.id.title); //Unselected Tabs
	            tv.setTextColor(Color.parseColor("#000000"));
	            tv.setTextSize((float) (0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
        	} 
	        
	       tab_host.setOnTabChangedListener(new OnTabChangeListener() {

	    	    @Override
	    	    public void onTabChanged(String tabId) {
	    	    	try{
		    	        for (int i = 0; i < tab_host.getTabWidget().getChildCount(); i++) {
		    	        	tab_host.getTabWidget().getChildAt(i).setBackgroundColor(Color.WHITE); // unselected
		    	            TextView tv = (TextView) tab_host.getTabWidget().getChildAt(i).findViewById(android.R.id.title); //Unselected Tabs
		    	            tv.setTextColor(Color.parseColor("#000000"));
		    	            tv.setTextSize((float) (0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		    	        }	
		    	        tab_host.getTabWidget().getChildAt(tab_host.getCurrentTab()).setBackgroundColor(Color.LTGRAY); // selected
		    	        TextView tv = (TextView) tab_host.getCurrentTabView().findViewById(android.R.id.title); //for Selected Tab
		    	        tv.setTextColor(Color.parseColor("#000000"));
			            tv.setTextSize((float) (0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	    	    	}catch(Exception e){
	    	    		e.printStackTrace();
	    	    		String errorMsg = className+" |setOnTabChangedListener click|   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
	    	    	}
	    	    }
	    	});
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | OnCreate |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
		}
	}
	
	
	@Override
	public void onStart() {
	 try{
		    super.onStart();
		    Tracker easyTracker = EasyTracker.getInstance(this);
			easyTracker.set(Fields.SCREEN_NAME, "/findstoredirections");
			easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
			 String[] segments = new String[1];
			 segments[0] = "Find a store directions"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
	 try{
		 super.onStop();
		 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	
		 QuantcastClient.activityStop();
	 }catch(Exception e){
		 e.printStackTrace();
		 String errorMsg = className+" | onStop  |   " +e.getMessage();
       	 Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
	 }
	}	
	 
	 	@Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(FindAStoreItemDetails.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
			}
			
		}
	
	 @Override
		protected void onResume() {			
			super.onResume();
			try{
				if(toPosition != null){
					latlngBounds = createLatLngBoundsObject(fromPosition, toPosition);
					mGoogleMap.moveCamera(CameraUpdateFactory.newLatLngBounds(latlngBounds, Common.sessionDeviceWidth, Common.sessionDeviceHeight,50));
				}
				
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(FindAStoreItemDetails.this);			
					Common.isAppBackgrnd = false;
				}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
			}

		}

		public void handleGetDirectionsResult(ArrayList<LatLng> directionPoints) {
			try{
				PolylineOptions rectLine = new PolylineOptions().width(5).color(Color.RED);
				for(int i = 0 ; i < directionPoints.size() ; i++) 
				{          
					rectLine.add(directionPoints.get(i));
				}
				if (newPolyline != null)
				{
					newPolyline.remove();
				}
				newPolyline = mGoogleMap.addPolyline(rectLine);
				latlngBounds = createLatLngBoundsObject(fromPosition, toPosition);
				mGoogleMap.animateCamera(CameraUpdateFactory.newLatLngBounds(latlngBounds, Common.sessionDeviceWidth, Common.sessionDeviceHeight, 50));
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | handleGetDirectionsResult |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
			}
		}
		
		
		private LatLngBounds createLatLngBoundsObject(LatLng firstLocation, LatLng secondLocation)
		{
			try{
				if (firstLocation != null && secondLocation != null)
				{
					LatLngBounds.Builder builder = new LatLngBounds.Builder();    
					builder.include(firstLocation).include(secondLocation);					
					return builder.build();
				}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | createLatLngBoundsObject |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
			}
			return null;
		}
		
		public void findDirections(double fromPositionDoubleLat, double fromPositionDoubleLong, double toPositionDoubleLat, double toPositionDoubleLong, String mode)
		{
			try{
				Map<String, String> map = new HashMap<String, String>();
				map.put(GetDirectionsAsyncTask.USER_CURRENT_LAT, String.valueOf(fromPositionDoubleLat));
				map.put(GetDirectionsAsyncTask.USER_CURRENT_LONG, String.valueOf(fromPositionDoubleLong));
				map.put(GetDirectionsAsyncTask.DESTINATION_LAT, String.valueOf(toPositionDoubleLat));
				map.put(GetDirectionsAsyncTask.DESTINATION_LONG, String.valueOf(toPositionDoubleLong));
				map.put(GetDirectionsAsyncTask.DIRECTIONS_MODE, mode);
				
				GetDirectionsAsyncTask asyncTask = new GetDirectionsAsyncTask(this);
				asyncTask.execute(map);
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | findDirections |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
				
			}
		}
		@Override
	    public boolean onKeyDown(int keyCode, KeyEvent event) {
	    	try{
		        if (keyCode == KeyEvent.KEYCODE_BACK) {
		            //Log.i("Press Back", "BACK PRESSED EVENT");
		            onBackPressed();
		            isBackPressed = true;
		        }
		
		        // Call super code so we dont limit default interaction
		        return super.onKeyDown(keyCode, event);
	    	} catch (Exception e) {
				Toast.makeText(getApplicationContext(), "Error: onKeyDown.", Toast.LENGTH_LONG).show();
				String errorMsg = className+" | onKeyDown |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);

				return false;
			}
	    }
		
	 @Override
		public void onBackPressed() {
	    	try{					
	        	finish();
	        	overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		        return;
	    	} catch (Exception e) {
				Toast.makeText(getApplicationContext(), "Error: By Brands onBackPressed.", Toast.LENGTH_LONG).show();
				String errorMsg = className+" | onBackPressed |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStoreItemDetails.this,errorMsg);
			}
	    }
}
