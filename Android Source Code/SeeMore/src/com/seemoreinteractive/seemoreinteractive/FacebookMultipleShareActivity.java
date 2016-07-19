package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.util.ArrayList;
import java.util.List;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.text.Html;
import android.text.Spanned;
import android.util.Log;
import android.widget.Toast;

import com.facebook.android.DialogError;
import com.facebook.android.Facebook;
import com.facebook.android.Facebook.DialogListener;
import com.facebook.android.FacebookError;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FacebookMultipleShareActivity extends Activity {

	Facebook fb = new Facebook(Constants.FACEBOOK_APP_ID);
	private SharedPreferences mPrefs;
	String wishListName = "";
	ArrayList<Uri> arrProdImage = new ArrayList<Uri>();
	ArrayList<String> arrProdImageName = new ArrayList<String>();
	ArrayList<String> arrProdImageLink = new ArrayList<String>();
	ArrayList<String> arrProdImagePrice = new ArrayList<String>();
	ArrayList<String> arrProdImageDesc = new ArrayList<String>();
	 Bundle params = new Bundle();
	 String className ="FacebookMultipleShareActivity";
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.loading);
		try{
			Bundle extras = getIntent().getExtras();
			if(extras != null){
				wishListName = extras.getString("wishListName");
				arrProdImage = extras.getParcelableArrayList("arrProdImage");
				arrProdImageName = extras.getStringArrayList("arrProdImageName");
				arrProdImageLink = extras.getStringArrayList("arrProdImageLink");
				arrProdImagePrice = extras.getStringArrayList("arrProdImagePrice");
				arrProdImageDesc = extras.getStringArrayList("arrProdImageDesc");
			}
			
			mPrefs = getSharedPreferences("fb_prefs", Context.MODE_PRIVATE);
			String access_token = mPrefs.getString("access_token", null);
			long expires = mPrefs.getLong("access_expires", 0);
			
			  
			/*SharedPreferences.Editor editor = mPrefs.edit();
            editor.putString("access_token", fb.getAccessToken());
            editor.putLong("access_expires", fb.getAccessExpires());
            editor.commit();*/
            
         
			if (access_token != null) {
				fb.setAccessToken(access_token);					
				Log.i("access_token", ""+access_token);
				Log.i("fb.isSessionValid()", ""+fb.isSessionValid());
				Log.i("expires", ""+expires);	
				try {
		            for (int i = 0; i < arrProdImage.size(); i++) {
		                String prodInfoText = "";
		                prodInfoText += "<p>Shared a wish list <b>'"+wishListName+"'</b></p>";
		                prodInfoText += "<p>"+arrProdImageName.get(i);
		                if(arrProdImagePrice.get(i) != null || !arrProdImagePrice.get(i).equals("0"))
		                	prodInfoText +=  "&nbsp;"+arrProdImagePrice.get(i)+"</p>";
		                else
		                	prodInfoText += "</p>";
		                if(arrProdImageDesc.get(i) != null )
		                	prodInfoText += "<p>"+arrProdImageDesc.get(i).toString()+"</p>";
		                if(arrProdImageLink.get(i) != null && !arrProdImageLink.get(i).equals("null") || !arrProdImageLink.get(i).startsWith("tel"))
		                	prodInfoText += "<p>"+arrProdImageLink.get(i).toString()+"</p>";
		                
		                Spanned text = Html.fromHtml(new StringBuilder().append(prodInfoText).toString());
		                params.putString("message",""+text);
		                
		                InputStream iStream = getContentResolver().openInputStream(arrProdImage.get(i));
		                Bitmap bitmap = BitmapFactory.decodeStream(iStream);
		                ByteArrayOutputStream bos = new ByteArrayOutputStream();
		                bitmap.compress(CompressFormat.JPEG, 100, bos);
		                byte[] imgData = null;
		                imgData = bos.toByteArray();
		                params.putByteArray("picture" + (i + 1), imgData);							                
		                String response = fb.request("me/photos?access_token="+access_token,
	                            params, "POST");
		               
		                params.clear();
		                if((arrProdImage.size()-1) == i){	
							if(response !=null){
								finish();
							}									
						}
		            }
		        } catch (Exception e) {
		            e.printStackTrace();
		            String errorMsg = className+" | access_token   |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(FacebookMultipleShareActivity.this,errorMsg);
		        }									
			}
			if (expires != 0) {
				fb.setAccessExpires(expires);
			}
			if (!fb.isSessionValid()) {
				   Log.i("access_token", ""+access_token);
		            Log.i("fb.isSessionValid()", ""+fb.isSessionValid());
		            Log.i("expires", ""+expires);
				fb.authorize(this, new String[] {  
						 "publish_stream",
							"publish_actions",
						},
						new DialogListener() {
							@Override
							public void onComplete(Bundle values) {								
							        try {
							            for (int i = 0; i < arrProdImage.size(); i++) {
							                String prodInfoText = "";
							                prodInfoText += "<p>Shared a wish list <b>'"+wishListName+"'</b></p>";
							                prodInfoText += "<p>"+arrProdImageName.get(i);
							                if(arrProdImagePrice.get(i) != null || !arrProdImagePrice.get(i).equals("0"))
							                	prodInfoText +=  "&nbsp;"+arrProdImagePrice.get(i)+"</p>";
							                else
							                	prodInfoText += "</p>";
							                if(arrProdImageDesc.get(i) != null )
							                	prodInfoText += "<p>"+arrProdImageDesc.get(i).toString()+"</p>";
							                if(arrProdImageLink.get(i) != null || !arrProdImageLink.get(i).equals("null") || !arrProdImageLink.get(i).startsWith("tel"))
							                	prodInfoText += "<p>"+arrProdImageLink.get(i).toString()+"</p>";
							                
							                Spanned text = Html.fromHtml(new StringBuilder().append(prodInfoText).toString());
							                params.putString("message",""+text);
							                
							                InputStream iStream = getContentResolver().openInputStream(arrProdImage.get(i));
							                Bitmap bitmap = BitmapFactory.decodeStream(iStream);
							                ByteArrayOutputStream bos = new ByteArrayOutputStream();
							                bitmap.compress(CompressFormat.JPEG, 100, bos);
							                byte[] imgData = null;
							                imgData = bos.toByteArray();
							                params.putByteArray("picture" + (i + 1), imgData);							                
							                String response = fb.request("me/photos",
						                            params, "POST");
							                params.clear();
							                if((arrProdImage.size()-1) == i){	
												if(response !=null){
													finish();
												}									
											}
							            }
							        } catch (Exception e) {
							            e.printStackTrace();
							            String errorMsg = className+" |authorize | onComplete   |   " +e.getMessage();
							       	 	Common.sendCrashWithAQuery(FacebookMultipleShareActivity.this,errorMsg);
							        }
							        
							   
							}
							  @Override
				               public void onCancel() {
								  FacebookMultipleShareActivity.this.finish();
							  }
							@Override
							public void onFacebookError(FacebookError ex) {
								// TODO Auto-generated method stub
								FacebookMultipleShareActivity.this.finish();
								ex.printStackTrace();
			                    try {
			                        fb.logout(FacebookMultipleShareActivity.this);
			                    } catch (MalformedURLException e) {
			                        e.printStackTrace();
			                    } catch (IOException e) {
			                        e.printStackTrace();
			                    }
							}
							@Override
							public void onError(DialogError e) {
								// TODO Auto-generated method stub
								FacebookMultipleShareActivity.this.finish();
								e.printStackTrace();
								
							}
				});
			}	       
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	
	public static Intent findFacebookClient(Context con)
	{
	    final String[] FacebookApps = {"com.facebook.android", "com.facebook.katana"};
	    Intent facebookIntent = new Intent();
	    facebookIntent.setType("text/plain");
	    final PackageManager packageManager = con.getPackageManager();
	    List<ResolveInfo> list = packageManager.queryIntentActivities(facebookIntent, PackageManager.MATCH_DEFAULT_ONLY);

	    for (int i = 0; i < FacebookApps.length; i++)
	    {
	        for (ResolveInfo resolveInfo : list)
	        {
	            String p = resolveInfo.activityInfo.packageName;
	            Log.i("resolveinfo",p);
	            if (p != null && p.startsWith(FacebookApps[i]))
	            {
	                facebookIntent.setPackage(p);
	                return facebookIntent;
	            }
	        }
	    }
	    return null;
	}
	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			fb.authorizeCallback(requestCode, resultCode, data);
			Log.i("data",""+data);
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onActivityResult.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" |authorize | onActivityResult   |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(FacebookMultipleShareActivity.this,errorMsg);
		}
	}

	@Override
	public void onResume() {
		try{
			super.onResume();
			fb.extendAccessTokenIfNeeded(this, null);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onResume.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onResume   |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(FacebookMultipleShareActivity.this,errorMsg);
		}
	}
	 @Override
	public void onBackPressed(){
		 try{
			 new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, ARDisplayActivity.class, "0");
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onBackPressed   |   " +e.getMessage();
		     Common.sendCrashWithAQuery(FacebookMultipleShareActivity.this,errorMsg);
		 }
	 }
}

