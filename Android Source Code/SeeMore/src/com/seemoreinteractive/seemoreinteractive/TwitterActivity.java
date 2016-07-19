package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayInputStream;

import oauth.signpost.OAuth;
import oauth.signpost.OAuthConsumer;
import oauth.signpost.OAuthProvider;
import oauth.signpost.commonshttp.CommonsHttpOAuthConsumer;
import oauth.signpost.commonshttp.CommonsHttpOAuthProvider;
import twitter4j.StatusUpdate;
import twitter4j.Twitter;
import twitter4j.TwitterFactory;
import twitter4j.auth.AccessToken;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.helper.GetTwitterAuth;


public class TwitterActivity extends Activity {
	final String TAG = "Seemore Showroom";
	private OAuthConsumer consumer;
	private OAuthProvider provider;
	private SharedPreferences mPrefs;
	byte[] image;
	String statusVal;
	String className ="TwitterActivity";
	String token = "1543021686-o1Q5sU3u4zBda0abmbzEHsnolChW5xqm8iJK37n";
	String secret = "mV2JEwElgzwqwSRQ7Bgg4VyprfiAHCL8ndzDU20A";
	@Override
	public void onCreate(Bundle savedInstanceState) {
		try{
			super.onCreate(savedInstanceState);
			setContentView(R.layout.loading);
			Intent getIntVals = getIntent();
			image= getIntVals.getByteArrayExtra("image");
			statusVal= getIntVals.getStringExtra("status");
			try {
				this.consumer = new CommonsHttpOAuthConsumer("9NB7zSyD7StayKf3Elvog","bhCTioIhxTnhJedQL7Ukq3jVjTryFrSo52aADkDg5ek");
				this.provider = new CommonsHttpOAuthProvider("https://api.twitter.com/oauth/request_token","https://api.twitter.com/oauth/access_token", "https://api.twitter.com/oauth/authorize");
				
				if (token != "" && secret != "") {
					sendTweet();
				} else {
					new GetTwitterAuth(this, consumer, provider).execute();
				}
			} catch (Exception e) {
				Log.i(TAG, "Error creating consumer / provider", e);
				Toast.makeText(getApplicationContext(), "Error: TwitterActivity onCreate - creating consumer / provider.", Toast.LENGTH_LONG).show();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: TwitterActivity onCreate.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | OnCreate    |   " +e.getMessage();
			Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
		}
	}

	@Override
	public void onNewIntent(Intent intent) {
		try{
			super.onNewIntent(intent);
			final Uri uri = intent.getData();
			if (uri != null && uri.getScheme().equals("seemore-twitter")) {
				new RetrieveAccessTokenTask().execute(uri);
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: TwitterActivity onNewIntent.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onNewIntent    |   " +e.getMessage();
			Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
		}
	}

	public class RetrieveAccessTokenTask extends AsyncTask<Uri, Void, Void> {
		@Override
		protected Void doInBackground(Uri... params) {
			try{
				final Uri uri = params[0];
				final String oauth_verifier = uri.getQueryParameter(OAuth.OAUTH_VERIFIER);
				try {
					provider.retrieveAccessToken(consumer, oauth_verifier);
					final Editor edit = mPrefs.edit();
					edit.putString("twitter_token", consumer.getToken());
					edit.putString("twitter_token_secret", consumer.getTokenSecret());
					edit.commit();
					
					consumer.setTokenWithSecret(token, secret);
					sendTweet();
				} catch (Exception e) {
					Log.i(TAG, "OAuth - Access Token Retrieval Error", e);
					Toast.makeText(getApplicationContext(), "Error: TwitterActivity doInBackground OAuth - Access Token Retrieval Error.", Toast.LENGTH_LONG).show();
				}
			} catch (Exception e) {
				// TODO: handle exception
				Toast.makeText(getApplicationContext(), "Error: TwitterActivity doInBackground.", Toast.LENGTH_LONG).show();
				String errorMsg = className+" | RetrieveAccessTokenTask  doInBackground    |   " +e.getMessage();
				Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
			}
			return null;
		}
	}

	public void sendTweet() {
		try{
			Thread thread = new Thread() {
				@Override
				public void run() {
					try {
						
						AccessToken accessToken = new AccessToken(token, secret);
						Twitter twitter = new TwitterFactory().getInstance();
						twitter.setOAuthConsumer("9NB7zSyD7StayKf3Elvog","bhCTioIhxTnhJedQL7Ukq3jVjTryFrSo52aADkDg5ek");
						twitter.setOAuthAccessToken(accessToken);
						StatusUpdate status = new StatusUpdate(statusVal);
						status.setMedia("seemoreshowroom",new ByteArrayInputStream(image));
						twitter.updateStatus(status);
						Log.i(TAG, "Tweet has been sent successfully.");
					} catch (Exception ex) {
						ex.printStackTrace();
						Log.i(TAG, "Exception : " + ex);
						runOnUiThread(new Runnable() {
							@Override
							public void run() {
								Toast.makeText(TwitterActivity.this, "Unable to send tweet. Please authorize app",Toast.LENGTH_SHORT).show();
								new GetTwitterAuth(TwitterActivity.this, consumer, provider).execute();
							}
						});
					}
				}
			};
			thread.start();
			runOnUiThread(new Runnable() {
				@Override
				public void run() {
					Toast.makeText(TwitterActivity.this, "Tweet is being sent",Toast.LENGTH_SHORT).show();
				}
			});
			TwitterActivity.this.finish();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: TwitterActivity sendTweet.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | sendTweet      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
		}
	}
	 @Override
	public void onStart() {
		 try{
	    super.onStart();
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStart      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop      |   " +e.getMessage();
			Common.sendCrashWithAQuery(TwitterActivity.this,errorMsg);
		 }
	}
	
}
