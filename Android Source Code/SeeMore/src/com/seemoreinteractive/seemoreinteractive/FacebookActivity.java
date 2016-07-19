package com.seemoreinteractive.seemoreinteractive;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.net.MalformedURLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.facebook.android.AsyncFacebookRunner;
import com.facebook.android.AsyncFacebookRunner.RequestListener;
import com.facebook.android.DialogError;
import com.facebook.android.Facebook;
import com.facebook.android.Facebook.DialogListener;
import com.facebook.android.FacebookError;
import com.seemoreinteractive.seemoreinteractive.Model.ProfileModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserProfile;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FacebookActivity extends Activity {

	Facebook fb = new Facebook(Constants.FACEBOOK_APP_ID);
	private SharedPreferences mPrefs;
	String checkLoginFlag, stringArrayList2;
	ArrayList<String> offerStringArrayListValues;
	String first_name = "",last_name ="",username="",email_id="",fb_id="",gender="";
	String className ="FacebookActivity";
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.loading);
		try{
			offerStringArrayListValues = new ArrayList<String>();
			Intent intent = getIntent();
			if(intent.getExtras()!=null){
				checkLoginFlag = intent.getStringExtra("checkLoginFlag");
				stringArrayList2 = intent.getStringExtra("stringArrayList2");
				offerStringArrayListValues = intent.getStringArrayListExtra("offerStringArrayListValues");
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
				 AsyncFacebookRunner myAsyncRunner = new AsyncFacebookRunner(fb);
				 myAsyncRunner.request("me", new meRequestListener());
			}
			if (expires != 0) {
				fb.setAccessExpires(expires);
			}
			if (!fb.isSessionValid()) {				  
		            fb.authorize(this, new String[] { 
							"email", 
							"publish_stream",
							"publish_actions",
							"public_profile",
					        },
						new DialogListener() {
							@Override
							public void onComplete(Bundle values) {
								try{							
									SharedPreferences.Editor editor = mPrefs.edit();
						            editor.putString("access_token", fb.getAccessToken());
						            editor.putLong("access_expires", fb.getAccessExpires());
						            editor.commit();
									 AsyncFacebookRunner myAsyncRunner = new AsyncFacebookRunner(fb);
									 myAsyncRunner.request("me", new meRequestListener());
									
								} catch (Exception e) {
									e.printStackTrace();
									Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onComplete.", Toast.LENGTH_LONG).show();
									String errorMsg = className+" | authorize onComplete  |   " +e.getMessage();
						       	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
								}
							}
							  @Override
				               public void onCancel() {
								  FacebookActivity.this.finish();
							  }
							@Override
							public void onFacebookError(FacebookError ex) {
								// TODO Auto-generated method stub
								FacebookActivity.this.finish();
								ex.printStackTrace();
			                    try {
			                        fb.logout(FacebookActivity.this);
			                    } catch (MalformedURLException e) {
			                        e.printStackTrace();
			                    } catch (IOException e) {
			                        e.printStackTrace();
			                    }
							}
							@Override
							public void onError(DialogError e) {
								FacebookActivity.this.finish();
								e.printStackTrace();								
							}
				});
			}			
		} catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onCreate  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
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
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onActivityResult.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
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
			String errorMsg = className+" | onResume  |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
		}
	}
	Intent intent;
	SessionManager session;
	ArrayList<String> userArrayList;
	String[] userArray;
	public class meRequestListener implements RequestListener {
	    @Override
	    public void onComplete(String response, Object state) {
	    	try {
	           	session = new SessionManager(FacebookActivity.this);
				final JSONObject jsonObject = new JSONObject(response);				 
				 	if(jsonObject.has("first_name") && jsonObject.getString("first_name").toString() != null){
						first_name = jsonObject.getString("first_name").toString();
					}
					if(jsonObject.has("last_name") && jsonObject.getString("last_name").toString() != null){
						last_name = jsonObject.getString("last_name").toString();
					}				
					if(jsonObject.has("email") && jsonObject.getString("email").toString() != null){
						email_id = jsonObject.getString("email").toString();
					}
					if(jsonObject.has("id") && jsonObject.getString("id").toString() != null){
						fb_id = jsonObject.getString("id").toString();
					}
					 Log.i("email",email_id);
					 Log.i("first_name",first_name);
					 Log.i("last_name",last_name);
					 Log.i("fb_id",fb_id);
					final Map<String, String> regParams = new HashMap<String, String>();
					regParams.put("first_name",first_name);
					regParams.put("last_name", last_name);
					regParams.put("username", email_id);
					regParams.put("email_id", email_id);	
					regParams.put("user_details_fbid", fb_id);
					regParams.put("user_details_gender", gender);
					regParams.put("register_through", "1");				
					
					Log.i("regurl",Constants.registerURL);
					Log.i("regParams",""+regParams);
			        final AQuery aq = new AQuery(FacebookActivity.this);
			        aq.ajax(Constants.registerURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
			        	@Override
						public void callback(String url, XmlDom xml, AjaxStatus status) {
			        		try{
			        			if(xml!=null){			        				
			        				List<XmlDom> xmlMsg = xml.tags("resultXml");
			        				for(XmlDom xmlMsg1 : xmlMsg){
			        					if(xmlMsg1.text("msg").toString().equals("already")){
				        					//Toast.makeText(getApplicationContext(), "Already registered.", Toast.LENGTH_LONG).show();

											final Map<String, String> loginParams = new HashMap<String, String>();
											loginParams.put("username", "");
											loginParams.put("password", "");
											loginParams.put("register_through", "1");
											loginParams.put("email_id", email_id);
											loginParams.put("user_details_fbid", fb_id);
											loginParams.put("first_name",first_name);
											loginParams.put("last_name", last_name);
											Log.i("loginParams fb_id",fb_id);
									       
									        aq.ajax(Constants.loginURL, loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
									        	@Override
												public void callback(String url, XmlDom xml, AjaxStatus status) {
									        		try{
									        			if(xml!=null){
									        				List<XmlDom> xmlMsg = xml.tags("resultXml");
									        				final ProfileModel profile = new ProfileModel();
									        				for(XmlDom xmlMsg1 : xmlMsg){
										        				if(xmlMsg1.text("msg").toString().equals("success")){
										        					userArrayList = new ArrayList<String>();
										        					userArrayList.add(xmlMsg1.text("id").toString());
										        					userArrayList.add(xmlMsg1.text("username").toString());
										        					userArrayList.add(xmlMsg1.text("email_id").toString());
										        					userArrayList.add(xmlMsg1.text("user_firstname").toString());
										        					userArrayList.add(xmlMsg1.text("user_lastname").toString());		
										        					userArrayList.add(xmlMsg1.text("user_group_id").toString());		
										        					userArrayList.add("");						        					
																	userArray = userArrayList.toArray(new String[userArrayList.size()]);
																	
										        					session.createLoginSession(userArray);
										        					
										        					UserProfile userProfile = new UserProfile();
				        				        					userProfile.setId(Long.parseLong(xmlMsg1.text("user_details_id").toString()));
				        				        					userProfile.setFirstName(xmlMsg1.text("user_firstname").toString());
				        				        					userProfile.setLastname(xmlMsg1.text("user_lastname").toString());
				        				        					userProfile.setGender(xmlMsg1.text("user_details_gender").toString());
				        				        					userProfile.setDateofBirth(xmlMsg1.text("user_details_dob").toString());
				        				        					userProfile.setPhone(xmlMsg1.text("user_details_phone").toString());
				        				        					userProfile.setEmail(xmlMsg1.text("email_id").toString());
				        				        					userProfile.setAddress1(xmlMsg1.text("user_details_address1").toString());
				        				        					userProfile.setAddress2(xmlMsg1.text("user_details_address2").toString());
				        				        					userProfile.setCity(xmlMsg1.text("user_details_city").toString());
				        				        					userProfile.setState(xmlMsg1.text("user_details_state").toString());
				        				        					userProfile.setCountry(xmlMsg1.text("user_details_country").toString());
				        				        					userProfile.setZip(xmlMsg1.text("user_details_zip").toString());
				        				        					profile.add(userProfile);        	

										        					boolean resultForUser = new Common().getStoredUserSessionDetails(FacebookActivity.this, checkLoginFlag, stringArrayList2);
										        					new Constants();
										        					//new LoginActivity().getUserOffersDetails(xmlMsg1.text("id").toString(),checkLoginFlag,stringArrayList2,FacebookActivity.this);
										        					//FacebookActivity.this.finish();
										        					FileTransaction file = new FileTransaction();
										        					file.setProfile(profile);
										        				} else {
																	Toast.makeText(FacebookActivity.this, "Login failed. Please enter valid username and password.",
																			Toast.LENGTH_LONG).show();
										        				}
									        				}
									        				//Log.i("xml", xml.tag("msg").toString()+" "+xml+" "+url+" "+loginParams);
									        			}
									        		} catch(Exception e){
									        			e.printStackTrace();
									        			String errorMsg = className+" | meRequestListener  | onComplete | registerURL | loginURL | success   " +e.getMessage();
									               	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
									        		}
									        	}
									        });
										
				        				} else if(xmlMsg1.text("msg").toString().equals("success")){
				        					final Map<String, String> loginParams = new HashMap<String, String>();
											loginParams.put("username", "");
											loginParams.put("password", "");
											loginParams.put("register_through", "1");
											loginParams.put("email_id", email_id);
											loginParams.put("user_details_fbid", fb_id);
									       
									        aq.ajax(Constants.loginURL, loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
									        	@Override
												public void callback(String url, XmlDom xml, AjaxStatus status) {
									        		try{
									        			if(xml!=null){
									        				List<XmlDom> xmlMsg = xml.tags("resultXml");
									        				for(XmlDom xmlMsg1 : xmlMsg){
										        				if(xmlMsg1.text("msg").toString().equals("success")){
										        					userArrayList = new ArrayList<String>();
										        					userArrayList.add(xmlMsg1.text("id").toString());
										        					userArrayList.add(xmlMsg1.text("username").toString());
										        					userArrayList.add(xmlMsg1.text("email_id").toString());
										        					userArrayList.add(xmlMsg1.text("user_firstname").toString());
										        					userArrayList.add(xmlMsg1.text("user_lastname").toString());		
										        					userArrayList.add(xmlMsg1.text("user_group_id").toString());		
										        					userArrayList.add("");						        					
																	userArray = userArrayList.toArray(new String[userArrayList.size()]);
																	session.createLoginSession(userArray);
																	boolean resultForUser = new Common().getStoredUserSessionDetails(FacebookActivity.this, checkLoginFlag, stringArrayList2);
																	new Constants();
										        					//new LoginActivity().getUserOffersDetails(xmlMsg1.text("id").toString(),checkLoginFlag,stringArrayList2,FacebookActivity.this);
										        					//FacebookActivity.this.finish();
																				
																} else {
																	Toast.makeText(FacebookActivity.this, "Login failed. Please enter valid username and password.",
																			Toast.LENGTH_LONG).show();
										        				}
									        				}
									        				//Log.i("xml", xml.tag("msg").toString()+" "+xml+" "+url+" "+loginParams);
									        			}
									        		} catch(Exception e){
									        			e.printStackTrace();
									        			String errorMsg = className+" | meRequestListener  | onComplete | loginURL  | success | " +e.getMessage();
									               	 	Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
									        		}
									        	}
									        });
				        				} else {
											Toast.makeText(getApplicationContext(), "Registration failed. Please try agian!", Toast.LENGTH_LONG).show();
											FacebookActivity.this.finish();
										}
			        				}
			        			}
			        		} catch(Exception e){
			        			e.printStackTrace();
			        		}
			        	}
			        });				
			
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	        
	    }
		@Override
		public void onIOException(IOException e, Object state) {
			// TODO Auto-generated method stub
			
		}
		@Override
		public void onFileNotFoundException(FileNotFoundException e, Object state) {
			// TODO Auto-generated method stub
			
		}
		@Override
		public void onMalformedURLException(MalformedURLException e, Object state) {
			// TODO Auto-generated method stub
			
		}
		@Override
		public void onFacebookError(FacebookError e, Object state) {
			// TODO Auto-generated method stub
			
		}

	    //You also have to override all the other methods
	}
	 @Override
	public void onBackPressed(){
		 try{
			 new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, ARDisplayActivity.class, "0");
		 }catch(Exception  e){
			 e.printStackTrace();
			 String errorMsg = className+" | onBackPressed | " +e.getMessage();
        	 Common.sendCrashWithAQuery(FacebookActivity.this,errorMsg);
		 }
	 }
}

