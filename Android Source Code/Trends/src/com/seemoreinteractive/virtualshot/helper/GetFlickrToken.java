package com.seemoreinteractive.virtualshot.helper;

import android.os.AsyncTask;
import android.widget.Toast;

import com.gmail.yuyang226.flickr.Flickr;
import com.gmail.yuyang226.flickr.oauth.OAuth;
import com.gmail.yuyang226.flickr.oauth.OAuthInterface;
import com.seemoreinteractive.virtualshot.FlickrActivity;

public class GetFlickrToken extends AsyncTask<String, Integer, OAuth>{
	private FlickrActivity activity;

	public GetFlickrToken(FlickrActivity context) {
		this.activity = context;
	}

	@Override
	protected OAuth doInBackground(String... params) {
		String oauthToken = params[0];
		String oauthTokenSecret = params[1];
		String verifier = params[2];
		Flickr f = FlickrHelper.getInstance().getFlickr();
		OAuthInterface oauthApi = f.getOAuthInterface();
		try {
			return oauthApi.getAccessToken(oauthToken, oauthTokenSecret,verifier);
		} catch (Exception e) {
			Toast.makeText(activity, "Error:GetFlickrToken doInBackground - during OAUth retrieve request token", Toast.LENGTH_LONG).show();
			return null;
		}
	}

	@Override
	protected void onPostExecute(OAuth result) {
		if (activity != null) {
			activity.onOAuthDone(result);
		}
	}

}
