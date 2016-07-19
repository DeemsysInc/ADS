package com.seemoreinteractive.virtualshot.helper;

import java.net.URL;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.util.Log;

import com.gmail.yuyang226.flickr.Flickr;
import com.gmail.yuyang226.flickr.auth.Permission;
import com.gmail.yuyang226.flickr.oauth.OAuthToken;
import com.seemoreinteractive.virtualshot.FlickrActivity;
import com.seemoreinteractive.virtualshot.utils.Constants;

public class GetFlickrAuth extends AsyncTask<Void, Integer, String> {
	private static final Uri OAUTH_CALLBACK_URI = Uri.parse(Constants.FLICKR_CALLBACK_SCHEME + "://oauth");
	private Activity mContext;
	public GetFlickrAuth(Activity context) {
		super();
		this.mContext = context;
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
	}

	@Override
	protected String doInBackground(Void... params) {
		try {
			Flickr f = FlickrHelper.getInstance().getFlickr();
			OAuthToken oauthToken = f.getOAuthInterface().getRequestToken(
					OAUTH_CALLBACK_URI.toString());
			saveTokenSecrent(oauthToken.getOauthTokenSecret());
			URL oauthUrl = f.getOAuthInterface().buildAuthenticationUrl(
					Permission.WRITE, oauthToken);
			return oauthUrl.toString();
		} catch (Exception e) {
			return "error:" + e.getMessage();
		}
	}

	private void saveTokenSecrent(String tokenSecret) {
		FlickrActivity act = (FlickrActivity) mContext;
		act.saveOAuthToken(null, null, null, tokenSecret);
	}

	@Override
	protected void onPostExecute(String result) {
		Log.i("Trends", "OAuthTask onPost Executed.");
		if (result != null && !result.startsWith("error")) {
			Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(result));
			mContext.startActivity(intent);
		}
	}
}
