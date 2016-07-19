package com.seemoreinteractive.virtualshot;

import java.io.ByteArrayOutputStream;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.gmail.yuyang226.flickr.Flickr;
import com.gmail.yuyang226.flickr.oauth.OAuth;
import com.gmail.yuyang226.flickr.oauth.OAuthToken;
import com.gmail.yuyang226.flickr.people.User;
import com.gmail.yuyang226.flickr.uploader.UploadMetaData;
import com.gmail.yuyang226.flickr.uploader.Uploader;
import com.seemoreinteractive.virtualshot.helper.FlickrHelper;
import com.seemoreinteractive.virtualshot.helper.GetFlickrAuth;
import com.seemoreinteractive.virtualshot.helper.GetFlickrToken;
import com.seemoreinteractive.virtualshot.model.Image;
import com.seemoreinteractive.virtualshot.utils.Constants;

public class FlickrActivity extends Activity {
	private SharedPreferences mPrefs;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.loading);
		try{
			mPrefs = getSharedPreferences(Constants.FLICKR_PREF,Context.MODE_PRIVATE);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity onCreate.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onDestroy() {
		try{
			super.onDestroy();
			Log.i(Constants.TAG, "flickr activity destroyed");
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity onDestroy.", Toast.LENGTH_LONG).show();
		}
	}
	
	@Override
	protected void onNewIntent(Intent intent) {
		try{
			Log.i(Constants.TAG,"New Intent called.");
			setIntent(intent);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity onNewIntent.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onResume() {
		try{
			super.onResume();
			Intent intent = getIntent();
			String scheme = intent.getScheme();
			OAuth savedToken = getOAuthToken();
			if (Constants.FLICKR_CALLBACK_SCHEME.equals(scheme) && (savedToken == null || savedToken.getUser() == null)) {
				Uri uri = intent.getData();
				String query = uri.getQuery();
				String[] data = query.split("&");
				if (data != null && data.length == 2) {
					String oauthToken = data[0].substring(data[0].indexOf("=") + 1);
					String oauthVerifier = data[1].substring(data[1].indexOf("=") + 1);
					Editor editor = mPrefs.edit();
					editor.putString(Constants.FLICKR_TOKEN, oauthToken);
					editor.commit();
					OAuth oauth = getOAuthToken();
					if (oauth != null && oauth.getToken() != null && oauth.getToken().getOauthTokenSecret() != null) {
						GetFlickrToken task = new GetFlickrToken(this);
						task.execute(oauthToken, oauth.getToken().getOauthTokenSecret(), oauthVerifier);
					}
				}
			} else {
				OAuth oauth = getOAuthToken();
				if (oauth == null) {
					GetFlickrAuth task = new GetFlickrAuth(FlickrActivity.this);
					task.execute();
				} else {
					uploadImage(oauth);
				}
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity onResume.", Toast.LENGTH_LONG).show();
		}
	}

	public OAuth getOAuthToken() {
		try{
			String oauthTokenString = mPrefs.getString(Constants.FLICKR_TOKEN, null);
			String tokenSecret = mPrefs.getString(Constants.FLICKR_TOKEN_SECRET,null);
			if (oauthTokenString == null || tokenSecret == null) {
				return null;
			}
			OAuth oauth = new OAuth();
			OAuthToken oauthToken = new OAuthToken();
			oauth.setToken(oauthToken);
			oauthToken.setOauthToken(oauthTokenString);
			oauthToken.setOauthTokenSecret(tokenSecret);
			return oauth;
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity getOAuthToken.", Toast.LENGTH_LONG).show();
			return null;
		}
	}

	public void onOAuthDone(OAuth result) {
		try{
			if (result == null) {
				Toast.makeText(this,"Authorization failed",Toast.LENGTH_LONG).show();
			} else {
				User user = result.getUser();
				OAuthToken token = result.getToken();
				if (user == null || user.getId() == null || token == null || token.getOauthToken() == null || token.getOauthTokenSecret() == null) {
					Toast.makeText(this, "Authorization failed",Toast.LENGTH_SHORT).show();
					return;
				}
				saveOAuthToken(user.getUsername(),user.getId(),token.getOauthToken(), token.getOauthTokenSecret());
				uploadImage(result);
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity onOAuthDone.", Toast.LENGTH_LONG).show();
		}
	}

	public void saveOAuthToken(String userName, String userId, String token, String tokenSecret) {
		try{
			Editor editor = mPrefs.edit();
			editor.putString(Constants.FLICKR_TOKEN, token);
			editor.putString(Constants.FLICKR_TOKEN_SECRET, tokenSecret);
			editor.commit();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity saveOAuthToken.", Toast.LENGTH_LONG).show();
		}
	}

	private void uploadImage(final OAuth oauth){
		try{
			if (oauth != null) {
				new Thread() {
					@Override
					public void run() {
						try {
							ByteArrayOutputStream bytes = new ByteArrayOutputStream();
							Bitmap bitmap = Image.getCombinedBitmap(); 
							bitmap.compress(Bitmap.CompressFormat.JPEG, 100, bytes);
							OAuthToken token = oauth.getToken();
							Flickr flickr = FlickrHelper.getInstance().getFlickrAuthed(token.getOauthToken(),token.getOauthTokenSecret());
							Uploader uploader = flickr.getUploader();
							final UploadMetaData uploadMetaData = new UploadMetaData();
							uploadMetaData.setTitle(mPrefs.getString("flickr_title", ""));
							uploadMetaData.setDescription(mPrefs.getString("flickr_description", ""));
							uploader.upload(" ", bytes.toByteArray(),uploadMetaData);
						} catch (Exception e) {
							Log.i(Constants.TAG, "Upload Exception " + e);
							e.printStackTrace();
							saveOAuthToken("","","","");
							Toast.makeText(getApplicationContext(), "Error: FlickrActivity uploadImage Thread run.", Toast.LENGTH_LONG).show();
						}
					}
				}.start();
				Toast.makeText(this, "Photo is being uploaded.",Toast.LENGTH_SHORT).show();
				FlickrActivity.this.finish();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FlickrActivity uploadImage.", Toast.LENGTH_LONG).show();
		}
	}
}
