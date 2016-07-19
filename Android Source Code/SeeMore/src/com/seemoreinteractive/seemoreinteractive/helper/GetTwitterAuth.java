package com.seemoreinteractive.seemoreinteractive.helper;

import oauth.signpost.OAuthConsumer;
import oauth.signpost.OAuthProvider;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

public class GetTwitterAuth extends AsyncTask<Void, Void, Void> {
	private Context context;
	private OAuthProvider provider;
	private OAuthConsumer consumer;

	public static final String TWITTER_CALLBACK_SCHEME = "seemore-twitter";
	public static final String TWITTER_CALLBACK_HOST = "callback";
	public static final String TWITTER_CALLBACK_URL = TWITTER_CALLBACK_SCHEME + "://" + TWITTER_CALLBACK_HOST;
	
	public GetTwitterAuth(Context context, OAuthConsumer consumer, OAuthProvider provider) {
		this.context = context;
		this.consumer = consumer;
		this.provider = provider;
	}

	@Override
	protected Void doInBackground(Void... params) {
		try {
			final String url = provider.retrieveRequestToken(consumer,TWITTER_CALLBACK_URL);
			Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
			context.startActivity(intent);
		} catch (Exception e) {
			Log.i("error", "Error during OAUth retrieve request token", e);
			Toast.makeText(context, "Error:GetTwitterAuth doInBackground - during OAUth retrieve request token", Toast.LENGTH_LONG).show();
		}
		return null;
	}
}
