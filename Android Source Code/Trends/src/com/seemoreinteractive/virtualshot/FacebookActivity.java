package com.seemoreinteractive.virtualshot;

import java.io.ByteArrayOutputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.net.MalformedURLException;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.text.method.BaseKeyListener;
import android.util.Log;
import android.widget.Toast;

import com.facebook.android.AsyncFacebookRunner;
import com.facebook.android.AsyncFacebookRunner.RequestListener;
import com.facebook.android.DialogError;
import com.facebook.android.Facebook;
import com.facebook.android.Facebook.DialogListener;
import com.facebook.android.FacebookError;
import com.seemoreinteractive.virtualshot.model.Image;
import com.seemoreinteractive.virtualshot.utils.Constants;

public class FacebookActivity extends Activity {

	Facebook fb = new Facebook(Constants.FACEBOOK_APP_ID);
	private SharedPreferences mPrefs;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.loading);
		try{
			mPrefs = getSharedPreferences(Constants.FB_PREF, Context.MODE_PRIVATE);
			String access_token = mPrefs.getString("access_token", null);
			long expires = mPrefs.getLong("access_expires", 0);
			if (access_token != null) {
				fb.setAccessToken(access_token);
				postImageOnWall();
			}
			if (expires != 0) {
				fb.setAccessExpires(expires);
			}
	
			if (!fb.isSessionValid()) {
				fb.authorize(this, new String[] { "email", "publish_stream" },
						new DialogListener() {
							@Override
							public void onComplete(Bundle values) {
								try{
									SharedPreferences.Editor editor = mPrefs.edit();
									editor.putString("access_token",fb.getAccessToken());
									editor.putLong("access_expires",fb.getAccessExpires());
									editor.commit();
									postImageOnWall();
								} catch (Exception e) {
									// TODO: handle exception
									Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onComplete.", Toast.LENGTH_LONG).show();
								}
							}
	
							@Override
							public void onFacebookError(FacebookError error) {
								Log.i(Constants.TAG, "FBError: " + error);
								FacebookActivity.this.finish();
							}
	
							@Override
							public void onError(DialogError e) {
								Log.i(Constants.TAG, "FBDialogError: " + e);
								FacebookActivity.this.finish();
							}
	
							@Override
							public void onCancel() {
								Log.i(Constants.TAG, "onCancel called.");
								FacebookActivity.this.finish();
							}
						});
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onCreate.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			super.onActivityResult(requestCode, resultCode, data);
			fb.authorizeCallback(requestCode, resultCode, data);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity onActivityResult.", Toast.LENGTH_LONG).show();
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
		}
	}

	public void postImageOnWall() {
		try{
			ByteArrayOutputStream bytes = new ByteArrayOutputStream();
			Image.getCombinedBitmap().compress(Bitmap.CompressFormat.JPEG, 100,bytes);
			Bundle params = new Bundle();
			params.putString(Facebook.TOKEN, fb.getAccessToken());
			params.putString("method","photos.upload");
			params.putString("caption",mPrefs.getString("fb_photo_description", ""));
			params.putByteArray("picture", bytes.toByteArray());
			AsyncFacebookRunner mAsyncRunner = new AsyncFacebookRunner(fb);
			mAsyncRunner.request(null, params, "POST", new ImageUploadListener(),null);
			Toast.makeText(FacebookActivity.this, "Photo is being uploaded",Toast.LENGTH_SHORT).show();
			FacebookActivity.this.finish();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: FaceBookActivity postImageOnWall.", Toast.LENGTH_LONG).show();
		}
	}

	public class ImageUploadListener extends BaseKeyListener implements RequestListener {

		@Override
		public void onComplete(final String response, final Object state) {
		}

		@Override
		public void onFacebookError(FacebookError e, Object state) {
		}

		@Override
		public int getInputType() {
			return 0;
		}

		@Override
		public void onIOException(IOException e, Object state) {
		}

		@Override
		public void onFileNotFoundException(FileNotFoundException e, Object state) {
		}

		@Override
		public void onMalformedURLException(MalformedURLException e, Object state) {
		}
	}
}
