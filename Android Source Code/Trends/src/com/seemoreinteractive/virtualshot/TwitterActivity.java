package com.seemoreinteractive.virtualshot;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;

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
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.graphics.Bitmap.CompressFormat;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.seemoreinteractive.virtualshot.helper.GetTwitterAuth;
import com.seemoreinteractive.virtualshot.model.Image;
import com.seemoreinteractive.virtualshot.utils.Constants;

public class TwitterActivity extends Activity {
	final String TAG = "Trends";
	private OAuthConsumer consumer;
	private OAuthProvider provider;
	private SharedPreferences mPrefs;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		try{
			super.onCreate(savedInstanceState);
			setContentView(R.layout.loading);
			mPrefs = getSharedPreferences(Constants.TWITTER_PREF,Context.MODE_PRIVATE);
			try {
				this.consumer = new CommonsHttpOAuthConsumer(Constants.TWITTER_CONSUMER_KEY,Constants.TWITTER_CONSUMER_SECRET);
				this.provider = new CommonsHttpOAuthProvider(Constants.TWITTER_REQUEST_URL,Constants.TWITTER_ACCESS_URL, Constants.TWITTER_AUTHORIZE_URL);
				String token = mPrefs.getString(Constants.TWITTER_TOKEN, "");
				String secret = mPrefs.getString(Constants.TWITTER_TOKEN_SECRET, "");
				if (token != "" && secret != "") {
					sendTweet();
				} else {
					new GetTwitterAuth(this, consumer, provider).execute();
				}
			} catch (Exception e) {
				Log.e(TAG, "Error creating consumer / provider", e);
				Toast.makeText(getApplicationContext(), "Error: TwitterActivity onCreate - creating consumer / provider.", Toast.LENGTH_LONG).show();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: TwitterActivity onCreate.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onNewIntent(Intent intent) {
		try{
			super.onNewIntent(intent);
			final Uri uri = intent.getData();
			if (uri != null && uri.getScheme().equals(Constants.TWITTER_CALLBACK_SCHEME)) {
				new RetrieveAccessTokenTask().execute(uri);
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: TwitterActivity onNewIntent.", Toast.LENGTH_LONG).show();
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
					edit.putString(Constants.TWITTER_TOKEN, consumer.getToken());
					edit.putString(Constants.TWITTER_TOKEN_SECRET, consumer.getTokenSecret());
					edit.commit();
					String token = mPrefs.getString(Constants.TWITTER_TOKEN, "");
					String secret = mPrefs.getString(Constants.TWITTER_TOKEN_SECRET, "");
					consumer.setTokenWithSecret(token, secret);
					sendTweet();
				} catch (Exception e) {
					Log.e(TAG, "OAuth - Access Token Retrieval Error", e);
					Toast.makeText(getApplicationContext(), "Error: TwitterActivity doInBackground OAuth - Access Token Retrieval Error.", Toast.LENGTH_LONG).show();
				}
			} catch (Exception e) {
				// TODO: handle exception
				Toast.makeText(getApplicationContext(), "Error: TwitterActivity doInBackground.", Toast.LENGTH_LONG).show();
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
						String token = mPrefs.getString(Constants.TWITTER_TOKEN, "");
						String secret = mPrefs.getString(Constants.TWITTER_TOKEN_SECRET, "");
						AccessToken accessToken = new AccessToken(token, secret);
						Twitter twitter = new TwitterFactory().getInstance();
						twitter.setOAuthConsumer(Constants.TWITTER_CONSUMER_KEY,Constants.TWITTER_CONSUMER_SECRET);
						twitter.setOAuthAccessToken(accessToken);
						StatusUpdate status = new StatusUpdate(mPrefs.getString("tweet", ""));
						ByteArrayOutputStream stream = new ByteArrayOutputStream();
						Image.getCombinedBitmap().compress(CompressFormat.JPEG,100, stream);
						status.setMedia("trends",new ByteArrayInputStream(stream.toByteArray()));
						twitter.updateStatus(status);
						Log.i(TAG, "Tweet has been sent successfully.");
					} catch (Exception ex) {
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
		}
	}
}
