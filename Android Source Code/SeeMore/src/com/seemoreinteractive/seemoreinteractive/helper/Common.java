package com.seemoreinteractive.seemoreinteractive.helper;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Currency;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Random;
import java.util.regex.Pattern;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ActivityManager;
import android.app.ActivityManager.RunningTaskInfo;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.PorterDuff.Mode;
import android.graphics.PorterDuffXfermode;
import android.graphics.Rect;
import android.graphics.RectF;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.GradientDrawable;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.ImageOptions;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.AccountLaunchActivity;
import com.seemoreinteractive.seemoreinteractive.Closet;
import com.seemoreinteractive.seemoreinteractive.LoginActivity;
import com.seemoreinteractive.seemoreinteractive.MenuOptions;
import com.seemoreinteractive.seemoreinteractive.MyOffers;
import com.seemoreinteractive.seemoreinteractive.OfferViewActivity;
import com.seemoreinteractive.seemoreinteractive.OrderConfirmation;
import com.seemoreinteractive.seemoreinteractive.ProductDetails;
import com.seemoreinteractive.seemoreinteractive.Products;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.ShareActivity;
import com.seemoreinteractive.seemoreinteractive.TryOn;
import com.seemoreinteractive.seemoreinteractive.VideoActivity;
import com.seemoreinteractive.seemoreinteractive.Model.ChangeLogModel;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserChangeLog;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserProduct;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.Utils.NetworkUtil;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
public class Common {
	
	public Bitmap LoadImage(String URL, BitmapFactory.Options options) {
		Bitmap bitmap = null;
		InputStream in = null;
		try {
			in = OpenHttpConnection(URL);
			bitmap = BitmapFactory.decodeStream(in, null, options);
			in.close();
		} catch (IOException e1) {
		}
		return bitmap;
	}

	public InputStream OpenHttpConnection(String strURL) throws IOException {
		InputStream inputStream = null;
		URL url = new URL(strURL);
		URLConnection conn = url.openConnection();

		try {
			HttpURLConnection httpConn = (HttpURLConnection) conn;
			httpConn.setRequestMethod("GET");
			httpConn.connect();

			if (httpConn.getResponseCode() == HttpURLConnection.HTTP_OK) {
				inputStream = httpConn.getInputStream();
			}
		} catch (Exception ex) {
		}
		return inputStream;
	}
	
	public  boolean deleteFiles(String location) {
		File dir = new File(location);	    
	    if( dir.exists() ) {
	      File[] files = dir.listFiles();
	      if (files == null) {
	          return true;
	      }
	      for(int i=0; i<files.length; i++) {	         
	           files[i].delete();
	         
	      }
	    }
	    return( dir.delete() );
	  }
	
	public void downloadFile(String imageUrl,String Path){
		int count;
        try {
			File dir = new File(Path);
		    if(!dir.exists()){
		    	dir.mkdirs();
		    }
        	
	        String imageName = imageUrl.substring(imageUrl.lastIndexOf('/') + 1, imageUrl.length());
	        File fileToCheck = new File(Path, imageName);
	        if(!fileToCheck.exists())
	        {
	            URL url = new URL(imageUrl.replaceAll(" ", "%20"));
	            URLConnection conection = url.openConnection();
	            conection.connect();
	            conection.getContentLength();

	            // download the file
	            InputStream input = new BufferedInputStream(url.openStream(), 8192);

	            // Output stream
	            OutputStream output = new FileOutputStream(Path+imageName);

	            byte data[] = new byte[1024];
	            while ((count = input.read(data)) != -1) {
	                // writing data to file
	                output.write(data, 0, count);
	            }
	            // flushing output
	            output.flush();
	            // closing streams
	            output.close();
	            input.close();        	
	        }
	    } catch (Exception e) {
            Log.i("Download File Error: ", e.getMessage());
            e.printStackTrace();
        }
	}

   public Bitmap getRoundedCornerBitmap(Bitmap bitmap) {
	    Bitmap output = Bitmap.createBitmap(bitmap.getWidth(),
	         bitmap.getHeight(), Config.ARGB_8888);
	    Canvas canvas = new Canvas(output);

	    final int color = 0xff424242;
	    final Paint paint = new Paint();
	    final Rect rect = new Rect(0, 0, bitmap.getWidth(), bitmap.getHeight());
	    final RectF rectF = new RectF(rect);
	    //Log.i("BMap WxH", ""+bitmap.getWidth()+", "+bitmap.getHeight());
	    //final float roundPx = (int) Math.floor(bitmap.getWidth()*0.20);
	    //Log.i("roundPx", ""+roundPx);
	    final float roundPx = 20;

	    paint.setAntiAlias(true);
	    canvas.drawARGB(0, 0, 0, 0);
	    paint.setColor(color);

	    canvas.drawRoundRect(rectF, roundPx, roundPx, paint);

	    paint.setXfermode(new PorterDuffXfermode(Mode.SRC_IN));
	    canvas.drawBitmap(bitmap, rect, rect, paint);

	    return output;
   }
   
   public <T> void clickingOnBackButton(final Activity activityThis, final Class<T> redirectedActivity){
		ImageView imgBackButton = (ImageView) activityThis.findViewById(R.id.imgvBtnBack);
		imgBackButton.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
				 Intent returnIntent = new Intent(activityThis, redirectedActivity);
				 activityThis.setResult(1, returnIntent);
				 activityThis.finish();
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg =  activityThis.getClass().getSimpleName()+" | clickingOnBackButton click      |   " +e.getMessage();
					sendCrashWithAQuery(activityThis,errorMsg);
				}
			}
		});
   }

   public <T> void clickingOnBackButtonWithAnimation(final Activity activityThis, final Class<T> redirectedActivity,final String string){
		ImageView imgBackButton = (ImageView) activityThis.findViewById(R.id.imgvBtnBack);
		imgBackButton.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				try{
				 Intent returnIntent = new Intent(activityThis, redirectedActivity);
				 returnIntent.putExtra("instruction_type",string);
				// Closing all the Activities
				 //returnIntent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
				// Add new Flag to start new Activity
				 //returnIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
				 activityThis.setResult(1, returnIntent);
				 activityThis.finish();
				 activityThis.overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				}catch(Exception e){
					String errorMsg =  activityThis.getClass().getSimpleName()+" | clickingOnBackButtonWithAnimation click      |   " +e.getMessage();
					sendCrashWithAQuery(activityThis,errorMsg);
				}
			}
		});
   }

   public <T> void clickingOnBackButtonWithAnimationWithBackPressed(final Activity activityThis, final Class<T> redirectedActivity,final String strFlag){

		 /*Intent returnIntent = new Intent(activityThis, redirectedActivity);
		 returnIntent.putExtra("instruction_type",strFlag);
		 activityThis.setResult(1, returnIntent);
		 activityThis.finish();
		 activityThis.overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);*/
	   try{
	    Intent intent = new Intent(activityThis, redirectedActivity);
	    intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
	   // intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	    activityThis.finish();
	    activityThis.startActivity(intent);
	    activityThis.overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
	   }catch(Exception e){
		   e.printStackTrace();
			String errorMsg =  activityThis.getClass().getSimpleName()+" | clickingOnBackButtonWithAnimationWithBackPressed click      |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
	   }
   }
   
   public void clickingOnBackButtonGoPreviousWithAnimation(final Activity activityThis){
	   activityThis.finish();
   }

   public boolean checkEmail(String email) {
       return EMAIL_ADDRESS_PATTERN.matcher(email).matches();
   }

	public final Pattern EMAIL_ADDRESS_PATTERN = Pattern.compile(
	          "[a-zA-Z0-9+._%-+]{1,256}" +
	      "@" +
	      "[a-zA-Z0-9][a-zA-Z0-9-]{0,64}" +
	      "(" +
	      "." +
	      "[a-zA-Z0-9][a-zA-Z0-9-]{0,25}" +
	      ")+"
	);
	/**
     * Checks if the device has Internet connection.
     * 
     * @return <code>true</code> if the phone is connected to the Internet.
     */
  /*  public static boolean isNetworkAvailable(Context context)
    {
	      ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
	
	      //This for Wifi.
	      NetworkInfo wifiNetwork = cm.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
	      if (wifiNetwork != null && wifiNetwork.isConnected()) 
	      {
	        return true;
	      }
	
	      //This for Mobile Network.
	      NetworkInfo mobileNetwork = cm.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
	      if (mobileNetwork != null && mobileNetwork.isConnected()) 
	      {
	        return true;
	      }
	
	      //This for Return true else false for Current status.
	      NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
	      if (activeNetwork != null && activeNetwork.isConnected()) 
	      {
	        return true;
	      }
	      return false;
    }*/
    
	 public static boolean isNetworkAvailable(Context context)
	    {
		 Boolean status = false;
		 try{
		 	 status = NetworkUtil.getConnectivityStatusString(context);			
		 }catch(Exception e){
			 e.printStackTrace();
		 }
		 return status;
	    }
	
    AQuery aq;

	public void DownloadImageFromUrlInBackgroundDrawable(Activity activityThis, String selectedClientBackgroundPath,
			RelativeLayout bgRelativeLayout) {
		
		InputStream inputStream;
		try {
			if(Common.isNetworkAvailable(activityThis)){				
				inputStream = new URL(selectedClientBackgroundPath).openStream();
				Drawable drawable = Drawable.createFromStream(inputStream, null);
				inputStream.close();
			    bgRelativeLayout.setBackgroundDrawable(drawable);
			}else{
				AQuery aq = new AQuery(activityThis);
				Bitmap bitmap =aq.getCachedImage(selectedClientBackgroundPath);				
				Drawable drawable = new BitmapDrawable(bitmap);
				bgRelativeLayout.setBackgroundDrawable(drawable);
				
			}
		} catch (MalformedURLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
			 String errorMsg =  activityThis.getClass().getSimpleName()+" | DownloadImageFromUrlInBackgroundDrawable       |   " +e1.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		} catch (IOException e1) {			
			e1.printStackTrace();
			 String errorMsg =  activityThis.getClass().getSimpleName()+" | DownloadImageFromUrlInBackgroundDrawable       |   " +e1.getMessage();
				sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
    public void DownloadImageFromUrl(Activity activityThis, String imageUrl, int displayingImageId){
    	try{
    	aq = new AQuery(activityThis);		
		if(Common.isNetworkAvailable(activityThis)){			
			aq.id(displayingImageId).image(imageUrl, true, true);
			Bitmap bitmap = aq.getCachedImage(imageUrl);
			if(bitmap==null){
				aq.cache(imageUrl, 14400000);
			}
		}else{
			Bitmap bitmap1 = aq.getCachedImage(imageUrl);
			ImageView img = (ImageView) activityThis.findViewById(displayingImageId);
			img.setImageBitmap(null);
			if(bitmap1 != null){
				img.setImageBitmap(bitmap1);
			}
		}
    	}catch(Exception e){
    		e.printStackTrace();
    		String errorMsg =  activityThis.getClass().getSimpleName()+" | DownloadImageFromUrl       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
    	}
		
    }
    public void DownloadImageFromUrlBitmap(Activity activityThis, String imageUrl, Bitmap bitmap, int displayingImageId){
    	try{
		aq = new AQuery(activityThis);
		ImageOptions options = new ImageOptions();
		//options.round = 10;
		options.fileCache = true;
		options.memCache = true;
		options.targetWidth = 0;
		//options.fallback = R.drawable.app_icon;
		options.fallback = 0;
		options.preset = bitmap;
		options.ratio = 0;
		options.animation = com.androidquery.util.Constants.FADE_IN;
		aq.id(displayingImageId).image(imageUrl, options);
    	}catch(Exception e){
    		e.printStackTrace();
    		String errorMsg = activityThis+" | DownloadImageFromUrlBitmap       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
    	}
    }
    public void DownloadImageFromUrlWithLoader(Activity activityThis, String imageUrl, int displayingImageId, int loaderId){
		aq = new AQuery(activityThis);
		aq.id(displayingImageId).progress(loaderId).image(imageUrl, true, true);
    }
    
    public void showDrawableImageFromAquery(Activity activityThis, int drawableImg, int displayingImageId){
    	try{
			aq = new AQuery(activityThis);
			//aq.id(displayingImageId).image(imageUrl, true, true, LayoutParams.WRAP_CONTENT, 0);
			/*ImageOptions imgOptions = new ImageOptions();
			imgOptions.round = 15;*/
			aq.id(displayingImageId).image(drawableImg);
    	} catch(Exception e){
    		e.printStackTrace();
    		String errorMsg =  activityThis.getClass().getSimpleName()+" | showDrawableImageFromAquery       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
    	}
    }
    
    public void showProgressLoaderFromAQuery(Activity activityThis, int displayingImageId){
    	try{
			aq = new AQuery(activityThis);
	    	aq.progress(displayingImageId);
			activityThis.findViewById(displayingImageId).setVisibility(View.INVISIBLE);
    	} catch(Exception e){
    		e.printStackTrace();
    		String errorMsg =  activityThis.getClass().getSimpleName()+" | showProgressLoaderFromAQuery       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
    	}
    }
    
	SessionManager session;
	public boolean alertErrorType = true;
	//private ProgressDialog pDialog;
	ArrayList<String> stringArrayList;
	ArrayList<String> userArrayList;
	String[] userArray;
	public static String sessionClientId = "null",
			sessionClientBgImage = "null", sessionClientBgColor = "null",
			sessionClientLogo = "null", sessionClientName = "null",
			sessionClientUrl = "null", sessionProductId = "null",
			sessionProductName = "null", sessionProductPrice = "null",
			sessionProductShortDesc = "null", sessionTapDetailId = "null",
			sessionTapProdId = "null", sessionProductUrl = "null",
			sessionProductBgColor = "null", sessionProductHideImage = "null",
			sessionBuyButtonName = "null", sessionBuyButtonUrl = "null",
			sessionClientBackgroundLightColor = "null", sessionClientBackgroundDarkColor = "null", sessionIdForUserAnalytics = "",sessionForUserAppOpenDate = "";
	public static int sessionProdIsTryOn = 0;
	public static ArrayList<String> remTriggerIds = new ArrayList<String>();
	public static ArrayList<String> remTriggerURl = new ArrayList<String>();
	public void getStoredClientProductSessionValues(Activity activityThis){
		try{
		 
		stringArrayList = new ArrayList<String>();
        session = new SessionManager(activityThis);
        HashMap<String, String> storedSessVals = session.getClientProductValuesFromSession();
        // get user data from session
        sessionClientId = storedSessVals.get(SessionManager.KEY_SESSCLIENT_ID);
        sessionClientLogo = storedSessVals.get(SessionManager.KEY_SESSCLIENT_LOGO);
        sessionClientName = storedSessVals.get(SessionManager.KEY_SESSCLIENT_NAME);
		sessionClientBgImage = storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGIMAGE);
		sessionClientBgColor = storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGCOLOR);
		sessionClientUrl = storedSessVals.get(SessionManager.KEY_SESSCLIENT_URL);
		sessionProductId = storedSessVals.get(SessionManager.KEY_SESSPROD_ID);
		sessionProductName = storedSessVals.get(SessionManager.KEY_SESSPROD_NAME);
		sessionProductPrice = storedSessVals.get(SessionManager.KEY_SESSPROD_PRICE);
		sessionProductShortDesc = storedSessVals.get(SessionManager.KEY_SESSPROD_SHORTDESC);
		sessionTapDetailId = storedSessVals.get(SessionManager.KEY_SESSTAP_ID);
		sessionTapProdId = storedSessVals.get(SessionManager.KEY_SESSTAP_PRODID); 
		sessionProductUrl = storedSessVals.get(SessionManager.KEY_SESSPROD_URL);
		sessionProductBgColor = storedSessVals.get(SessionManager.KEY_SESSPROD_BGCOLOR);
		sessionProductHideImage = storedSessVals.get(SessionManager.KEY_SESSPROD_HideImage);
		sessionBuyButtonName = storedSessVals.get(SessionManager.KEY_SESSPROD_BuyButtonName);
		sessionBuyButtonUrl = storedSessVals.get(SessionManager.KEY_SESSPROD_BuyButtonUrl);
		sessionProdIsTryOn = Integer.parseInt(storedSessVals.get(SessionManager.KEY_SESSPROD_ISTRYON));
		sessionClientBackgroundLightColor = storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGLIGHTCOLOR);
		sessionClientBackgroundDarkColor = storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGDARKCOLOR);
		Log.i("KEY_SESSCLIENT_ID", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_ID));
        Log.i("KEY_SESSCLIENT_BGIMAGE", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGIMAGE));
        Log.i("KEY_SESSCLIENT_BGCOLOR", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGCOLOR));
        Log.i("KEY_SESSCLIENT_BGLIGHTCOLOR", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGLIGHTCOLOR));
        Log.i("KEY_SESSCLIENT_BGDARKCOLOR", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_BGDARKCOLOR));
        Log.i("KEY_SESSCLIENT_LOGO", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_LOGO));
        Log.i("KEY_SESSCLIENT_NAME", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_NAME));
        Log.i("KEY_SESSCLIENT_URL", ""+storedSessVals.get(SessionManager.KEY_SESSCLIENT_URL));
        Log.i("KEY_SESSPROD_ID", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_ID));
        Log.i("KEY_SESSPROD_NAME", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_NAME));
        Log.i("KEY_SESSPROD_PRICE", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_PRICE));
        Log.i("KEY_SESSPROD_SHORTDESC", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_SHORTDESC));
        Log.i("KEY_SESSTAP_ID", ""+storedSessVals.get(SessionManager.KEY_SESSTAP_ID));
        Log.i("KEY_SESSTAP_PRODID", ""+storedSessVals.get(SessionManager.KEY_SESSTAP_PRODID));
        Log.i("sessionBuyButtonName", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_BuyButtonName));
        Log.i("KEY_SESSPROD_BuyButtonUrl", ""+storedSessVals.get(SessionManager.KEY_SESSPROD_BuyButtonUrl));
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg =  activityThis.getClass().getSimpleName()+" | getStoredClientProductSessionValues       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}

    String checkStrArrayFlag = "";
    
    public <T> void getLoginDialog(final Activity activityThis, final Class<T> redirectedActivityClass, final String checkLoginFlag, final ArrayList<String> stringArrayList2)
    {
    	// Session class instance
    	try{
       	session = new SessionManager(activityThis);
        
        if(session.isLoggedIn())
        {	        
			if(checkLoginFlag.equals("OfferViewMyOffers")){
				checkStrArrayFlag = stringArrayList2.get(0);
				Log.i("checkStrArrayFlag", ""+checkStrArrayFlag);
				new OfferViewActivity().insertOfferToMyOffersDbUsingXml(Constants.MyOffers_Url+"insert_offers/"+Common.sessionIdForUserLoggedIn+"/"+checkStrArrayFlag+"/", activityThis,"");
				
			} else if(checkLoginFlag.equals("ClosetInsert")){
				checkStrArrayFlag = stringArrayList2.get(0);
				String insertClosetUrl = Constants.Closet_Url+"insert/"+Common.sessionIdForUserLoggedIn+"/"+checkStrArrayFlag+"/";
				Log.i("insertClosetUrl", ""+insertClosetUrl);
				new Closet().insertUpdateDeleteProductsToClosetDbUsingXml(insertClosetUrl, "insert");
			}
			
			if(!checkLoginFlag.equals("OfferViewMyOffers")){				
				Intent intent = new Intent(activityThis, redirectedActivityClass);	
				intent.putExtra("productId", sessionProductId);
				intent.putExtra("productName", sessionProductName);
				intent.putExtra("productPrice", sessionProductPrice);
				intent.putExtra("productShortDesc", sessionProductShortDesc);
				intent.putExtra("clientLogo", sessionClientLogo);
				intent.putExtra("clientId", sessionClientId);
				intent.putExtra("clientBackgroundImage", sessionClientBgImage);
				intent.putExtra("clientImageName", sessionClientName);
				intent.putExtra("clientBackgroundColor", sessionClientBgColor);
				intent.putExtra("clientBackgroundLightColor", sessionClientBackgroundLightColor);
				intent.putExtra("clientBackgroundDarkColor", sessionClientBackgroundDarkColor);
				if(stringArrayList2.size() >1){					
					intent.putExtra("pageRedirectFlag", stringArrayList2.get(1));					
				}else{
					intent.putExtra("pageRedirectFlag", "");
				}
				// Closing all the Activities
				//intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);			
				// Add new Flag to start new Activity
				//intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				activityThis.startActivityForResult(intent, 1);
				activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
				//pDialog.dismiss();
			
			}
		} else {
			if(checkLoginFlag.equals("OfferViewMyOffers")){		        	
        		checkStrArrayFlag = stringArrayList2.get(0);		        	
        	}
        	if(checkLoginFlag.equals("ClosetInsert")){
				checkStrArrayFlag = stringArrayList2.get(0);						
			} 	
			if(checkLoginFlag.equals("OfferCalendarMyOffers")){
				checkStrArrayFlag = stringArrayList2.get(0);						
			}
			Log.i("stringArrayList2", ""+stringArrayList2);
			
        	/*SharedPreferences mPrefs = activityThis.getSharedPreferences("fb_prefs", Context.MODE_PRIVATE);
			Editor editor = mPrefs.edit();
			editor.putString("fb_photo_description", "New VirtualShot Image.");
			editor.commit();
			
			Intent intent = new Intent(activityThis, FacebookActivity.class);*/
			
			Intent accountlaunch = new Intent(activityThis,AccountLaunchActivity.class);
			accountlaunch.putExtra("checkLoginFlag", checkLoginFlag);		
			Log.i("checkStrArrayFlag", ""+checkStrArrayFlag);
			accountlaunch.putExtra("stringArrayList2", checkStrArrayFlag);
			if(checkLoginFlag.equals("OfferCalendarMyOffers")){
				accountlaunch.putStringArrayListExtra("offerStringArrayListValues", stringArrayList2);
			}
			
			
			activityThis.startActivity(accountlaunch);
			
		}
    	}catch(Exception e){
    		e.printStackTrace();
    		String errorMsg =  activityThis.getClass().getSimpleName()+" | getLoginDialog       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
    	}
	
    }
    
    
    
	public void shareOptions(final Activity activityThis, final String productId){
		try{
	    	ImageView imgBtnShare = (ImageView) activityThis.findViewById(R.id.imgvBtnShare);
	    	imgBtnShare.setOnClickListener(new OnClickListener() {
				@Override
			public void onClick(View arg0) {
				try{
					ImageView bigImage = (ImageView) activityThis.findViewById(R.id.imgvBigImg);
					BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
					Bitmap bitmap = test.getBitmap();
					ByteArrayOutputStream baos = new ByteArrayOutputStream();
					bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
					byte[] b = baos.toByteArray();
					Intent intent = new Intent(activityThis, ShareActivity.class);
					intent.putExtra("tapOnImage", false);		
					intent.putExtra("image", b);		
					intent.putExtra("productId", productId);
					intent.putExtra("clientId", Common.sessionClientId);						
					activityThis.startActivity(intent);
					activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg =  activityThis.getClass().getSimpleName()+" | shareOptions  onclick      |   " +e.getMessage();
					sendCrashWithAQuery(activityThis,errorMsg);
					
				}
			}
		});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg =  activityThis.getClass().getSimpleName()+" | shareOptions       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
			
		}
    }

	public void getYourWishListTableWithXml(String wishlistUrl, Activity activityThis) {
		try{		
	        aq = new AQuery(activityThis);
	        aq.ajax(wishlistUrl, XmlDom.class, activityThis, "xmlResultsCallbackForWishList");	
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = activityThis+" | getYourWishListTableWithXml       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
	public void xmlResultsCallbackForWishList(String url, XmlDom xml, AjaxStatus status){
		try{
	        if(xml != null){                    
	        	//successful ajax call, show status code and json content			
				try {
					List<XmlDom> prodClosetFromWishList = xml.tags("prodClosetFromWishList");
					if(prodClosetFromWishList.size()>1){
						 for(final XmlDom pdXml : prodClosetFromWishList){
							String prodId = pdXml.text("prodId").toString();	
							Log.i("prodId", prodId);
							session.createClosetInSession(""+Common.sessionIdForUserLoggedIn, prodId);
						 }
					}
				} catch (Exception e) {				
					e.printStackTrace();				
				}
	        }else{     
	            Toast.makeText(aq.getContext(), "Error:" + status.getCode(), Toast.LENGTH_LONG).show();
	        }	
		}catch(Exception e){
			e.printStackTrace();
		}
	}
    
    public void getYourWishListTable(String jsonAjaxUrl, Activity activityThis) {
		try{
	        aq = new AQuery(activityThis);
	        aq.ajax(jsonAjaxUrl, JSONObject.class, this, "jsonResultsCallback");
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = activityThis+" | getYourWishListTable       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
	public void jsonResultsCallback(String url, JSONObject json, AjaxStatus status){
		try{
	        if(json != null){                    
	        	//successful ajax call, show status code and json content
				JSONArray jsonArr = null;
				try {
					jsonArr = json.getJSONArray("prodClosetFromWishList");
					if(jsonArr.length()>0){					
						for(int j=0; j<jsonArr.length(); j++)
						{
							JSONObject jsonObj = jsonArr.getJSONObject(j);   						
		    				String prodId = jsonObj.getString("prodId").toString();	
		    				Log.i("prodId",prodId);
		    				session.createClosetInSession(""+Common.sessionIdForUserLoggedIn, prodId);
		    				
						}
					} 
					
					//pDialog.dismiss();
				} catch (JSONException e) {				
					e.printStackTrace();
				}
	        }else{     
	            Toast.makeText(aq.getContext(), "Error:" + status.getCode(), Toast.LENGTH_LONG).show();
	        }	
		}catch(Exception e){
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
	    Log.i("FacebookApps.length",""+FacebookApps.length);
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

	/*public void dragToButtonCart(final Activity activityThis, final String productId){
		try{
			ImageView imgBtnCart = (ImageView) activityThis.findViewById(R.id.imgvBtnCart);
			imgBtnCart.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					//Toast.makeText(getApplicationContext(), "Please drag and drop the big image to this cart.", Toast.LENGTH_LONG).show();
					try{
						new Common().getLoginAlertDialog(activityThis, ProductInfo.class);
						
						ImageView bigImage = (ImageView) activityThis.findViewById(R.id.imgvBigImg);
						BitmapDrawable test = (BitmapDrawable) bigImage.getDrawable();
						Bitmap bitmap = test.getBitmap();
						ByteArrayOutputStream baos = new ByteArrayOutputStream();
						bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
						byte[] b = baos.toByteArray();

						Intent intent = new Intent(activityThis, ProductInfo.class);
						intent.putExtra("tapOnImage", false);		
						intent.putExtra("image", b);		
						intent.putExtra("productId", productId);
						intent.putExtra("clientId", Common.sessionClientId);
						activityThis.startActivity(intent);
						activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
						new ProductList().hideInstruction(view);
					} catch (Exception e) {
						
						Toast.makeText(activityThis, "Error: productlist button cart.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
					}
				}
			});
		} catch (Exception e) {			
			Log.i("dragToButtonCart error", ""+e.getMessage());
			e.printStackTrace();
		}
	}	*/
	
	@SuppressLint("NewApi")
	@TargetApi(Build.VERSION_CODES.GINGERBREAD)
	public long getFolderSize(File dir) {
		long size = 0;
		for (File file : dir.listFiles()) {
		    if (file.isFile()) {
		        Log.i("Folder File Size",file.getName() + " " + file.getTotalSpace());
		        size += file.getTotalSpace();
		    }
		    else
		        size += getFolderSize(file);
		}
		return size;
	}

	
	public float dpFromPx(Activity activityThis, float px)
    {
        return px / activityThis.getBaseContext().getResources().getDisplayMetrics().density;
    }

	public float pxFromDp(Activity activityThis, float dp)
    {
        return dp * activityThis.getBaseContext().getResources().getDisplayMetrics().density;
    }
	public int createNewWidthForImageCommon(int originalWidth, int originalHeight, int newHeight){
		//original width / original height x new height = new width
		double widthByHeight = (double) originalWidth/originalHeight;
		double newWidth = roundTwoDecimalsCommon(widthByHeight) * newHeight;
		return (int) newWidth;
	}
	
	public int createNewHeightForImageCommon(int originalWidth, int originalHeight, int newWidth){
		//original height / original width x new width = new height
		double heightByWidth = (double) originalHeight/originalWidth;
		double newHeight = roundTwoDecimalsCommon(heightByWidth) * newWidth;
		return (int) newHeight;
	}
	public double roundDecimalsCommon(double d)
	{
	    DecimalFormat twoDForm = new DecimalFormat("#");
	    return Double.valueOf(twoDForm.format(d));
	}
	public static double roundTwoDecimalsCommon(double d)
	{
	    DecimalFormat twoDForm = new DecimalFormat("0.00");
	    return Double.parseDouble(twoDForm.format(d));
	}
	public static double roundTwoDecimalsByStringCommon(String d)
	{
		double convertToDouble = Double.parseDouble(d);
	    DecimalFormat twoDForm = new DecimalFormat("0.00");
	    Double finalDoubleVal = Double.parseDouble(twoDForm.format(convertToDouble));
	    return finalDoubleVal;
	}
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	public void onGlobalLayoutForMyOffersButtons(Activity activityThis, double inputWidth, double inputHeight, 
			Button[] myButtons, RelativeLayout rlForMyOffers)
	{	
        int mLayoutInputWidth = (int) (inputWidth * Common.sessionDeviceWidth);
        Log.i("mLayoutInputWidth", ""+mLayoutInputWidth);
        int mLayoutInputHeight = (int) (inputHeight * Common.sessionDeviceHeight);
        Log.i("mLayoutInputHeight", ""+mLayoutInputHeight);

        int eachInputCount = Common.sessionDeviceWidth/myButtons.length;
        Log.i("eachInputCount", ""+eachInputCount);
        int eachBtnWidth = eachInputCount - (int)(inputWidth*Common.sessionDeviceWidth);
        Log.i("eachBtnWidth", ""+eachBtnWidth);
        int gapCount = (eachBtnWidth * myButtons.length)/(myButtons.length+1);
        Log.i("gapCount", ""+gapCount);
         
        for(int i=0; i<myButtons.length; i++){
    		RelativeLayout.LayoutParams rlParams = (RelativeLayout.LayoutParams) myButtons[i].getLayoutParams();
        	rlParams.width = mLayoutInputWidth;
        	rlParams.height = mLayoutInputHeight; 
            Log.i("buttons topMargin", ""+gapCount+" "+rlParams.topMargin);
            Log.i("buttons leftMargin", ""+gapCount+" "+rlParams.leftMargin);
        	rlParams.setMargins(gapCount, rlParams.topMargin, 0, 0);
            Log.i("buttons topMargin", ""+gapCount+" "+rlParams.topMargin);
            Log.i("buttons leftMargin", ""+gapCount+" "+rlParams.leftMargin);
	        myButtons[i].setTextSize((float)(0.03 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	        myButtons[i].setLayoutParams(rlParams);
        }
	}

	public static int sessionDeviceWidth = 0, sessionDeviceHeight=0, sessionDeviceDensity=0;	
	public void getStoredDisplayMetricsSessionValues(Activity activityThis){
		try{
	        session = new SessionManager(activityThis);
	        HashMap<String, String> storedSessValsDisplay = session.getDisplayMetricsDetails();
	        // get user data from session
	        sessionDeviceWidth = Integer.valueOf(storedSessValsDisplay.get(SessionManager.KEY_DEVICE_WIDTH));
	        sessionDeviceHeight = Integer.valueOf(storedSessValsDisplay.get(SessionManager.KEY_DEVICE_HEIGHT));
	        sessionDeviceDensity = Integer.valueOf(storedSessValsDisplay.get(SessionManager.KEY_DEVICE_DENSITY));
			sessionIdForUserAnalytics = storedSessValsDisplay.get(SessionManager.KEY_SESSID_USERANALYTICS);
			sessionForUserAppOpenDate = storedSessValsDisplay.get(SessionManager.KEY_DEVICE_OPEN_DATE);
	        Log.i("sessionIdForUserAnalytics", ""+storedSessValsDisplay.get(SessionManager.KEY_SESSID_USERANALYTICS));
	        Log.i("sessionDeviceWidth", ""+sessionDeviceWidth);
	        Log.i("sessionDeviceHeight", ""+sessionDeviceHeight);
	        Log.i("sessionDeviceDensity", ""+sessionDeviceDensity);
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg =  activityThis.getClass().getSimpleName()+" | getStoredDisplayMetricsSessionValues       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
	
	public void getStoredAppOpenDate(Activity activityThis){
		try{
	        session = new SessionManager(activityThis);
	        HashMap<String, String> storedSessValsDisplay = session.getAppOpenDate();
	        // get user data from session
	       	sessionForUserAppOpenDate = storedSessValsDisplay.get(SessionManager.KEY_DEVICE_OPEN_DATE);	       
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg =  activityThis.getClass().getSimpleName()+" | getStoredAppOpenDate       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
		
	public void clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
			Activity activityThis, String ColorCode, String lightColorCode,
			String darkColorCode, String getClientLogo, String staticHeaderTitle, String strBgImgFlag) {
		try{
			if(ColorCode.equals("null") || ColorCode.equals("")){
				Common.sessionClientBgColor = "ff2600";
			} else {
				Common.sessionClientBgColor = ColorCode;				
			}
			if(lightColorCode.equals("null") || lightColorCode.equals("")){
				Common.sessionClientBackgroundLightColor = "ff2600";
			} else {
				Common.sessionClientBackgroundLightColor = lightColorCode;				
			}
			if(darkColorCode.equals("null") || darkColorCode.equals("")){
				Common.sessionClientBackgroundDarkColor = "ff2600";
			} else {
				Common.sessionClientBackgroundDarkColor = darkColorCode;		
			}
			
			Log.i("getClientLogo", ""+getClientLogo+"staticHeaderTitle"+staticHeaderTitle);
			int bgColor = Color.parseColor("#"+Common.sessionClientBackgroundLightColor);
			int bgColor1 = Color.parseColor("#"+Common.sessionClientBackgroundDarkColor);
			int colors[] = { bgColor, bgColor1 };
			//float ratioColors[] = { 0, 0.4f, 0.6f };

			GradientDrawable gradientDrawable = new GradientDrawable(GradientDrawable.Orientation.TOP_BOTTOM, colors);
			if(staticHeaderTitle.equals("OfferView")){				
			} else {				
				if(staticHeaderTitle.equals("My Offers")){
					//Log.i("myoffers", ""+staticHeaderTitle);
					ImageView imgvMyOfferBtnBack = (ImageView) activityThis.findViewById(R.id.imgvBtnBack);		  
					RelativeLayout.LayoutParams rlpForImgvMyOfferBackButton = (RelativeLayout.LayoutParams) imgvMyOfferBtnBack.getLayoutParams();
					rlpForImgvMyOfferBackButton.width = (int) (0.1334 * Common.sessionDeviceWidth);
					rlpForImgvMyOfferBackButton.height = (int) (0.082 * Common.sessionDeviceHeight);
					imgvMyOfferBtnBack.setLayoutParams(rlpForImgvMyOfferBackButton);		
					imgvMyOfferBtnBack.setBackgroundDrawable(gradientDrawable);
					
					ImageView imgvBtnCameraIcon = (ImageView) activityThis.findViewById(R.id.imgvBtnCart);
					RelativeLayout.LayoutParams rlpForImgvBtnCameraIcon = (RelativeLayout.LayoutParams) imgvBtnCameraIcon.getLayoutParams();
					rlpForImgvBtnCameraIcon.width = (int) (0.1334 * Common.sessionDeviceWidth);
					rlpForImgvBtnCameraIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
					imgvBtnCameraIcon.setLayoutParams(rlpForImgvBtnCameraIcon);
					imgvBtnCameraIcon.setBackgroundDrawable(gradientDrawable);		
					
				}  
				else {
					ImageView imgvBtnBack = (ImageView) activityThis.findViewById(R.id.imgvBtnBack);   
					RelativeLayout.LayoutParams rlpForImgvBackButton = (RelativeLayout.LayoutParams) imgvBtnBack.getLayoutParams();
					rlpForImgvBackButton.width = (int) (0.1334 * Common.sessionDeviceWidth);
					rlpForImgvBackButton.height = (int) (0.082 * Common.sessionDeviceHeight);
					imgvBtnBack.setLayoutParams(rlpForImgvBackButton);
					imgvBtnBack.setBackgroundDrawable(gradientDrawable);
					if(staticHeaderTitle.equals("Order Confirmation") || staticHeaderTitle.equals("My Orders")){
						TextView imgvBtnCart = (TextView) activityThis.findViewById(R.id.txtvShop);  
						RelativeLayout.LayoutParams rlpForImgvBtnCart = (RelativeLayout.LayoutParams) imgvBtnCart.getLayoutParams();
						rlpForImgvBtnCart.width = (int) (0.1334 * Common.sessionDeviceWidth);
						rlpForImgvBtnCart.height = (int) (0.082 * Common.sessionDeviceHeight);
						imgvBtnCart.setLayoutParams(rlpForImgvBtnCart);
						imgvBtnCart.setBackgroundDrawable(gradientDrawable);
					}else{
						ImageView imgvBtnCart = (ImageView) activityThis.findViewById(R.id.imgvBtnCart);  
						RelativeLayout.LayoutParams rlpForImgvBtnCart = (RelativeLayout.LayoutParams) imgvBtnCart.getLayoutParams();
						rlpForImgvBtnCart.width = (int) (0.1334 * Common.sessionDeviceWidth);
						rlpForImgvBtnCart.height = (int) (0.082 * Common.sessionDeviceHeight);
						imgvBtnCart.setLayoutParams(rlpForImgvBtnCart);
						imgvBtnCart.setBackgroundDrawable(gradientDrawable);
					}	
					if(!strBgImgFlag.equals("")){
			            RelativeLayout bgRelativeLayout = (RelativeLayout) activityThis.findViewById(R.id.bgRelativeLayout); 
						if(Common.sessionClientBgImage.equals("null")){
							bgRelativeLayout.setBackgroundResource(R.drawable.bg_closet);
						} 
						else {
							String selectedClientBackgroundPath = (Constants.Client_Logo_Location
									+ Common.sessionClientId + "/background/" + Common.sessionClientBgImage)
									.toString().replaceAll(" ", "%20");	
							Log.i("selectedClientBackgroundPath", ""+selectedClientBackgroundPath);
							DownloadImageFromUrlInBackgroundDrawable(activityThis, selectedClientBackgroundPath, bgRelativeLayout);
							//Log.i("bgRelativeLayout", ""+bgRelativeLayout.getLayoutParams());
						}	
						
					}
				}
	            RelativeLayout rlForHeaderMiddle = (RelativeLayout) activityThis.findViewById(R.id.rlForHeaderMiddle);
	            RelativeLayout.LayoutParams rlpHeaderPanel = (RelativeLayout.LayoutParams) rlForHeaderMiddle.getLayoutParams();
	            rlpHeaderPanel.height = (int) (0.082 * Common.sessionDeviceHeight);
	            rlForHeaderMiddle.setLayoutParams(rlpHeaderPanel);
	            rlForHeaderMiddle.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));      
	            
	            RelativeLayout footerLinearLayout = (RelativeLayout) activityThis.findViewById(R.id.rlForFooterPanel);
				//Log.i("footerLinearLayout", ""+footerLinearLayout);
				if(footerLinearLayout!=null){				
					ImageView imgvBtnCloset = (ImageView) activityThis.findViewById(R.id.imgvBtnCloset);   
					RelativeLayout.LayoutParams rlpForImgvBtnCloset = (RelativeLayout.LayoutParams) imgvBtnCloset.getLayoutParams();
					rlpForImgvBtnCloset.width = (int) (0.1334 * Common.sessionDeviceWidth);
					rlpForImgvBtnCloset.height = (int) (0.082 * Common.sessionDeviceHeight);
					imgvBtnCloset.setLayoutParams(rlpForImgvBtnCloset);
					imgvBtnCloset.setBackgroundDrawable(gradientDrawable);
					
					ImageView imgvBtnShare = (ImageView) activityThis.findViewById(R.id.imgvBtnShare);   
					RelativeLayout.LayoutParams rlpForImgvBtnShare = (RelativeLayout.LayoutParams) imgvBtnShare.getLayoutParams();
					rlpForImgvBtnShare.width = (int) (0.1334 * Common.sessionDeviceWidth);
					rlpForImgvBtnShare.height = (int) (0.082 * Common.sessionDeviceHeight);
					imgvBtnShare.setLayoutParams(rlpForImgvBtnShare);
					imgvBtnShare.setBackgroundDrawable(gradientDrawable);

		            RelativeLayout rlForFooterMiddle = (RelativeLayout) activityThis.findViewById(R.id.rlForFooterMiddle);
		            RelativeLayout.LayoutParams rlpFooterPanel = (RelativeLayout.LayoutParams) rlForFooterMiddle.getLayoutParams();
		            rlpFooterPanel.height = (int) (0.082 * Common.sessionDeviceHeight);
		            rlForFooterMiddle.setLayoutParams(rlpFooterPanel);
		            rlForFooterMiddle.setBackgroundColor(Color.parseColor("#"+Common.sessionClientBgColor));				
				}

				if(staticHeaderTitle.equals("TryOn")){
					Log.i("TryOn", "Is TryOn");
				} else if(staticHeaderTitle.equals("")){
					
				    ImageView imgClientLogo = (ImageView) activityThis.findViewById(R.id.imgvHeadTitle);
					TextView txtHeadTitle = (TextView) activityThis.findViewById(R.id.txtvHeadTitle);
					if(!Common.sessionClientLogo.equals("null")&& !Common.sessionClientLogo.equals("")){
						txtHeadTitle.setVisibility(View.GONE);
				    	String selectedClientLogoPath;
				    	 if(Common.sessionClientLogo.indexOf(Constants.Client_Logo_Location) != -1){
				             selectedClientLogoPath = Common.sessionClientLogo.replaceAll(" ", "%20");
				         }else{
				             selectedClientLogoPath =  (Constants.Client_Logo_Location+Common.sessionClientId+"/logo/"+Common.sessionClientLogo).toString().replaceAll(" ", "%20");
				         }
				        
				    	Log.e("selectedClientLogoPath",selectedClientLogoPath);
						//String selectedClientLogoPath = Common.sessionClientLogo.toString().replaceAll(" ", "%20");
						
				    	DownloadImageFromUrl(activityThis, selectedClientLogoPath, R.id.imgvHeadTitle);
						RelativeLayout.LayoutParams rlpImgClientLogo = (RelativeLayout.LayoutParams) imgClientLogo.getLayoutParams();
						rlpImgClientLogo.height = (int) (0.056 * Common.sessionDeviceHeight);
						imgClientLogo.setLayoutParams(rlpImgClientLogo);
						imgClientLogo.setVisibility(View.VISIBLE);
					} else {
						if(Common.sessionClientName.equals("null") || Common.sessionClientName.equals("")){
							imgClientLogo.setVisibility(View.GONE);
							txtHeadTitle.setText("");
							txtHeadTitle.setVisibility(View.VISIBLE);
							txtHeadTitle.setTextSize((float)(0.084 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						} else {
							imgClientLogo.setVisibility(View.GONE);
							txtHeadTitle.setText(Common.sessionClientName);
							txtHeadTitle.setVisibility(View.VISIBLE);
							txtHeadTitle.setTextSize((float)(0.084 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						}
					}
				} else {
					TextView txtHeadTitle = (TextView) activityThis.findViewById(R.id.txtvHeadTitle);
					txtHeadTitle.setText(staticHeaderTitle);
					if(staticHeaderTitle.length()>20){
						txtHeadTitle.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
					} else {
						txtHeadTitle.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
					}
					txtHeadTitle.setVisibility(View.VISIBLE);	
				}
			}
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = activityThis.getClass().getSimpleName()+" | clientLogoOrTitleWithThemeColorAndBgImgByPassingColor       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}

	public void btnForSeeItLive(final Activity activityThis, Button btnSeeItLive, String strLayout, String strWidth, 
			String strTitle, final String strPdId, final String strClientId, final int flagForPdIsTryOn, final String bgColorCode){
		try{	
			if(strLayout.equals("linear")){
				LinearLayout.LayoutParams lpImgSeeItLive = (LinearLayout.LayoutParams) btnSeeItLive.getLayoutParams();		
				if(strTitle.equals("products") && strWidth.equals("width")){
					lpImgSeeItLive.width = (int) (0.265 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.082 * Common.sessionDeviceHeight);
				} else {
					lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
					lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;				
				}
				//Log.i("btnForSeeItLive lp", ""+Common.sessionClientBgColor+" "+bgColorCode);
				btnSeeItLive.setLayoutParams(lpImgSeeItLive);	
			} else { 
				RelativeLayout.LayoutParams lpImgSeeItLive = (RelativeLayout.LayoutParams) btnSeeItLive.getLayoutParams();
				if(strTitle.equals("products") && strWidth.equals("width")){
					
					lpImgSeeItLive.width = (int) (0.265 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.082 * Common.sessionDeviceHeight);
				} else if(strTitle.equals("product_info") && strWidth.equals("width")){
					
					lpImgSeeItLive.width = (int) (0.265 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.082 * Common.sessionDeviceHeight);
					lpImgSeeItLive.rightMargin = (int) (0.1 * Common.sessionDeviceWidth);
					lpImgSeeItLive.topMargin = (int) (0.081 * Common.sessionDeviceHeight);
				}  else if(strTitle.equals("products_curve")){					
					if(strWidth.equals("1")){
						lpImgSeeItLive.width = (int) (0.075 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.036 * Common.sessionDeviceHeight);
					} else if(strWidth.equals("2")){
						lpImgSeeItLive.width = (int) (0.0834 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0462 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("3")){
						lpImgSeeItLive.width = (int) (0.1084 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0564 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("4")){
						lpImgSeeItLive.width = (int) (0.0834 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0462 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("5")){
						lpImgSeeItLive.width = (int) (0.075 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.036 * Common.sessionDeviceHeight);						
					}
				} else {
					lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
					lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;				
				}				
				btnSeeItLive.setLayoutParams(lpImgSeeItLive);
			}
			if(strTitle.equals("products") || strTitle.equals("product_info")){
				btnSeeItLive.setTextSize((float) ((0.03 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			} else if(strWidth.equals("1") || strWidth.equals("5")){ 
				btnSeeItLive.setTextSize((float) ((0.0134 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			} else if(strWidth.equals("2") || strWidth.equals("4")){ 
				btnSeeItLive.setTextSize((float) ((0.015 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			} else {
				btnSeeItLive.setTextSize((float) ((0.0167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			}
			btnSeeItLive.setTag(strPdId);
			int iconColor = 0;
			if(flagForPdIsTryOn==1){
				// Load the icon as drawable object
				Drawable d = activityThis.getResources().getDrawable(R.drawable.see_it_live);				 
				// Get the color of the icon depending on system state		
				if(bgColorCode.equals("") || bgColorCode.equals("null")){
					iconColor = Color.parseColor("#ff2600");					
				} else {
					iconColor = Color.parseColor("#"+bgColorCode);					
				}
				// Set the correct new color
				d.setColorFilter( iconColor, Mode.MULTIPLY );	
				d = d.mutate();
				// Load the updated drawable to the image viewer
				btnSeeItLive.setBackground(d);
				btnSeeItLive.setVisibility(View.VISIBLE);
				btnSeeItLive.setOnClickListener(new OnClickListener() {						
					@Override
					public void onClick(View v) {						
						try{
							Common.sessionClientBgColor = bgColorCode;
							Intent intent = new Intent(activityThis, TryOn.class);
							intent.putExtra("clientId", strClientId);
							intent.putExtra("productId", strPdId);
							intent.putExtra("pdIsTryOn", flagForPdIsTryOn);
							activityThis.startActivity(intent);
							//activityThis.finish();
							activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						} catch(Exception e){
							e.printStackTrace();
						}
					}
				});
			} else {
				btnSeeItLive.setVisibility(View.INVISIBLE);
			}
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = activityThis+" | btnForSeeItLive       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
	public void btnForSeeItLiveWithAllColors(final Activity activityThis, Button btnSeeItLive, String strLayout, String strWidth, 
			String strTitle, final String strPdId, final String strClientId, final int flagForPdIsTryOn, 
			final String bgColorCode, final String bgLightColorCode, final String bgDarkColorCode){
		try{	
			if(strLayout.equals("linear")){
				LinearLayout.LayoutParams lpImgSeeItLive = (LinearLayout.LayoutParams) btnSeeItLive.getLayoutParams();		
				if(strTitle.equals("products") && strWidth.equals("width")){
					lpImgSeeItLive.width = (int) (0.265 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.082 * Common.sessionDeviceHeight);
				} else {
					lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
					lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;				
				}
				//Log.i("btnForSeeItLive lp", ""+Common.sessionClientBgColor+" "+bgColorCode);
				btnSeeItLive.setLayoutParams(lpImgSeeItLive);	
			} else { 
				RelativeLayout.LayoutParams lpImgSeeItLive = (RelativeLayout.LayoutParams) btnSeeItLive.getLayoutParams();
				if(strTitle.equals("products") && strWidth.equals("width")){
					
					lpImgSeeItLive.width = (int) (0.334 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
					btnSeeItLive.setPadding(0, 0, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);
				} else if(strTitle.equals("product_info") && strWidth.equals("width")){
					
					lpImgSeeItLive.width = (int) (0.334 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
					lpImgSeeItLive.rightMargin = (int) (0.1 * Common.sessionDeviceWidth);
					lpImgSeeItLive.topMargin = (int) (0.081 * Common.sessionDeviceHeight);
					btnSeeItLive.setPadding(0, 0, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);
				}  else if(strTitle.equals("products_curve")){
					
					btnSeeItLive.setPadding(0, 0, (int) (0.0034*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);
					if(strWidth.equals("1")){
						lpImgSeeItLive.width = (int) (0.075 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.036 * Common.sessionDeviceHeight);
					} else if(strWidth.equals("2")){
						lpImgSeeItLive.width = (int) (0.0834 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0462 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("3")){
						lpImgSeeItLive.width = (int) (0.1084 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0564 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("4")){
						lpImgSeeItLive.width = (int) (0.0834 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.0462 * Common.sessionDeviceHeight);						
					} else if(strWidth.equals("5")){
						lpImgSeeItLive.width = (int) (0.075 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.036 * Common.sessionDeviceHeight);						
					}
				}else if(strTitle.equals("financial") && strWidth.equals("width")){
					
					lpImgSeeItLive.width = (int) (0.1667 * Common.sessionDeviceWidth);
					lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
					btnSeeItLive.setPadding(0, 0, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);
				}  else {
					lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
					lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;	
					btnSeeItLive.setPadding(0, 0, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);			
				}
				
				btnSeeItLive.setLayoutParams(lpImgSeeItLive);
			}
			if(strTitle.equals("products") || strTitle.equals("product_info")){
				btnSeeItLive.setTextSize((float) ((0.04 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			}else if(strTitle.equals("financial")){
				btnSeeItLive.setTextSize((float) ((0.03 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			} else if(strWidth.equals("1") || strWidth.equals("5")){ 
				btnSeeItLive.setTextSize((float) ((0.0134 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			} else if(strWidth.equals("2") || strWidth.equals("4")){ 
				btnSeeItLive.setTextSize((float) ((0.015 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			} else {
				btnSeeItLive.setTextSize((float) ((0.0167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
			}
			btnSeeItLive.setTag(strPdId);
			int iconColor = 0;
			if(flagForPdIsTryOn==1){
				
				// Load the icon as drawable object
				Drawable d = activityThis.getResources().getDrawable(R.drawable.see_it_live);				 
				// Get the color of the icon depending on system state		
				Log.i("bgColorCode if", ""+bgColorCode);
				if(bgColorCode.equals("") || bgColorCode.equals("null")){
					Log.i("bgColorCode if", ""+bgColorCode);
					iconColor = Color.parseColor("#ff2600");					
				} else {
					Log.i("bgColorCode else", ""+bgColorCode+" "+bgLightColorCode+" "+bgDarkColorCode);
					iconColor = Color.parseColor("#"+bgColorCode);					
				}
				// Set the correct new color
				d.setColorFilter( iconColor, Mode.MULTIPLY );	
				d = d.mutate();
				// Load the updated drawable to the image viewer
				btnSeeItLive.setBackground(d);
				btnSeeItLive.setVisibility(View.VISIBLE);
				btnSeeItLive.setOnClickListener(new OnClickListener() {						
					@Override
					public void onClick(View v) {						
						try{
							Common.sessionClientBgColor = bgColorCode;
							Common.sessionClientBackgroundLightColor = bgLightColorCode;
							Common.sessionClientBackgroundDarkColor = bgDarkColorCode;
							Intent intent = new Intent(activityThis, TryOn.class);
							intent.putExtra("clientId", strClientId);
							intent.putExtra("productId", strPdId);
							intent.putExtra("pdIsTryOn", flagForPdIsTryOn);
							activityThis.startActivity(intent);
							//activityThis.finish();
							activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						} catch(Exception e){
							e.printStackTrace();
						}
					}
				});
			} else {
				btnSeeItLive.setVisibility(View.INVISIBLE);
			}
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = activityThis.getClass().getSimpleName()+" | btnForSeeItLiveWithAllColors       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
		}
	}
	
	public static HashMap<String, String> aMap = new HashMap<String, String>();
	public static ArrayList<String> arrPdIdsForUserAnalytics = new ArrayList<String>();
	public static ArrayList<String> arrOfferIdsForUserAnalytics = new ArrayList<String>();
    public static void sendJsonOld(String URL, String loggedInUserId, String screenName, String productIds, String offerIds) {
        HttpClient client = new DefaultHttpClient();
        HttpConnectionParams.setConnectionTimeout(client.getParams(), 10000); //Timeout Limit
        HttpResponse response;
        try{
            List<NameValuePair> params = new ArrayList<NameValuePair>();  
            //params.add(new BasicNameValuePair("sample", jsonObj.toString()));
            params.add(new BasicNameValuePair("user_id", loggedInUserId));
			params.add(new BasicNameValuePair("session_id", randomString(40)));
			params.add(new BasicNameValuePair("screen_path", screenName));
			params.add(new BasicNameValuePair("product_ids", productIds));
			params.add(new BasicNameValuePair("offer_ids", offerIds));
			params.add(new BasicNameValuePair("device_os",  "ANDROID"));
			params.add(new BasicNameValuePair("device_type", android.os.Build.MODEL));
			params.add(new BasicNameValuePair("device_brand", android.os.Build.BRAND));
			params.add(new BasicNameValuePair("device_os_version", android.os.Build.VERSION.RELEASE));
			params.add(new BasicNameValuePair("user_address", ""));
			params.add(new BasicNameValuePair("user_country", ""));
			params.add(new BasicNameValuePair("user_state", ""));
			params.add(new BasicNameValuePair("user_city", ""));
			params.add(new BasicNameValuePair("time_on_screen", ""+android.os.Build.TIME));
            HttpPost post = new HttpPost(URL);
            
            // Hand the NVP to the POST
            post.setEntity(new UrlEncodedFormEntity(params));
           
            // Collect the response
            response = client.execute(post);
            int code = response.getStatusLine().getStatusCode();            
            /*Checking response */
            if(response!=null){
                InputStream in = response.getEntity().getContent(); //Get the data in the entity               
            }
            Common.arrPdIdsForUserAnalytics.clear();
            Common.arrOfferIdsForUserAnalytics.clear();
            Common.aMap.clear();
        }
        catch(Exception e){
            e.printStackTrace();
            //createDialog("Error", "Cannot Establish Connection");
        }
    }

    static AQuery aqNew;
    public static void sendJsonWithAQuery(Activity activityThis, String loggedInUserId, String screenName, String productIds, String offerIds) {
    	aqNew = new AQuery(activityThis);
        try{        	
        	new Common().getLatLng(activityThis);
        	PackageManager manager = activityThis.getPackageManager();
			PackageInfo info = manager.getPackageInfo(activityThis.getPackageName(), 0);
			
            // Create a NameValuePair out of the JSONObject + a name
        	Map<String, String> params = new HashMap<String, String>();
            //params.put("sample", "manohar");
            params.put("user_id", loggedInUserId);
			params.put("session_id", Common.sessionIdForUserAnalytics);
			params.put("screen_path", screenName);
			params.put("prod_ids", productIds);
			params.put("offer_ids", offerIds);
			params.put("device_os",  "ANDROID");
			params.put("device_type", android.os.Build.MODEL);
			params.put("device_brand", android.os.Build.BRAND);
			params.put("device_os_version", android.os.Build.VERSION.RELEASE);
			params.put("version_name", info.versionName);
			params.put("user_address", "");
			params.put("user_country", "");
			params.put("user_state", "");
			params.put("user_city", "");
			params.put("time_on_screen", ""+android.os.Build.TIME);			
			params.put("lat_long", ""+lat+"|"+lng);
			params.put("device_bundle_version",info.versionName);
			//Log.e("params",""+params);
        	aqNew.ajax(Constants.Live_Url+"data_analytics/sample/", params, JSONObject.class, new AjaxCallback<JSONObject>(){
        		@Override
                public void callback(String url, JSONObject json, AjaxStatus status) {
        			try{
        				//Log.e("json",""+json);
        				if(json!=null){
	        				Log.i("status", url+" "+json);
	        				Log.i("status", status.getCode()+" "+status.getMessage());
        				}
        			} catch (Exception e){
        				e.printStackTrace();
        			}
                }
        	});
           
            Common.arrPdIdsForUserAnalytics.clear();
            Common.arrOfferIdsForUserAnalytics.clear();
            //Common.aMap.clear();
        }
        catch(Exception e){
            e.printStackTrace();
            String errorMsg = activityThis+" | sendJsonWithAQuery       |   " +e.getMessage();
			sendCrashWithAQuery(activityThis,errorMsg);
            //createDialog("Error", "Cannot Establish Connection");
        }
    }
    public static final String AB = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    public static Random rnd = new Random();

    public static String randomString( int len ) 
    {
       StringBuilder sb = new StringBuilder( len );
       for( int i = 0; i < len; i++ ) 
          sb.append( AB.charAt( rnd.nextInt(AB.length()) ) );
       return sb.toString();
    }

	public void gradientDrawableCorners(Activity activityThis, LinearLayout linearLayoutOnly, RelativeLayout relativeLayoutOnly, double radiusVal, double setPaddingVal) {
		try{
			int radiusCorner = (int) ((radiusVal * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			int setPadding = (int) ((setPaddingVal * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			GradientDrawable shape =  new GradientDrawable();
			//Log.e("activityThis", ""+activityThis+" "+activityThis.getClass().getSimpleName());
			if(activityThis.getClass().getSimpleName().equals("Products")){
				/*int[] colors = { Color.parseColor("#FFFFFF"), Color.parseColor("#FFFFFF") };
				float[] radii = { 2, 2, 2, 2, 2, 2, 2, 2 };
				shape = new GradientDrawable(GradientDrawable.Orientation.TL_BR, colors);
				shape.setCornerRadii(radii);*/
				shape.setColor(Color.WHITE);
				shape.setStroke(2, Color.parseColor("#FF2600"));
			}else{
				shape.setColor(Color.WHITE);
			}
			shape.setCornerRadius(radiusCorner);
			if(linearLayoutOnly != null){
				//linearLayoutOnly.setPadding(0, 0, setPadding, 0);
				linearLayoutOnly.setBackground(shape);			
			} else if(relativeLayoutOnly != null){
				//relativeLayoutOnly.setPadding(0, 0, setPadding, 0);
				relativeLayoutOnly.setBackground(shape);			
			}
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	private String strImage; //url of the image to download
    private Bitmap imagebmp; //To store the cached image

    public void setImageForCannotGenerateTextureBitmap(ImageView imgview, AQuery aq)
    {       
        if (imagebmp==null || imagebmp.isRecycled()) 
        {
            Bitmap bm = aq.getCachedImage(strImage);
            if (bm==null || bm.isRecycled())                     
                aq.id(imgview).image(strImage);
            else {
                imgview.setImageBitmap(bm);
                imagebmp = bm;
            }       
        } else {
            imgview.setImageBitmap(imagebmp);       
        }   
    }
    
    public Locale getLocale(String strCode) {  
        for (Locale locale : NumberFormat.getAvailableLocales()) {  
            String code = NumberFormat.getCurrencyInstance(locale).getCurrency().getCurrencyCode();  
            Log.i("code", ""+code);
            if (strCode.equals(code)) { 
                Log.i("locale", ""+locale); 
                return locale;  
            } else {
                return locale;  
            }
        }    
        return null;  
    } 
    
    public String getCurrencySymbol(String countryLanguage, String countryCode) {  
    	Locale locale=new Locale(countryLanguage, countryCode);
		Currency currency=Currency.getInstance(locale);
		String symbol = currency.getSymbol();
		return symbol;
    } 
    
	public static int sessionIdForUserLoggedIn = 0, sessionIdForUserGroupId = 0;
	public static String sessionIdForUserLoggedEmail = "", sessionIdForUserLoggedFname = "", sessionIdForUserLoggedLname = "", sessionIdForUserLoggedName = "";
    public boolean getStoredUserSessionDetails(final Activity activityThis, final String checkLoginFlag, final String stringArrayList2){
    	try{
	        session = new SessionManager(activityThis);
	        HashMap<String, String> storedUserSessVals = session.getUserDetails();
	        // get user data from session
	        sessionIdForUserLoggedIn = Integer.valueOf(storedUserSessVals.get(SessionManager.KEY_SESSUSERID));
	        sessionIdForUserGroupId = Integer.valueOf(storedUserSessVals.get(SessionManager.KEY_SESSUSERGROUPID));
	        sessionIdForUserLoggedFname = storedUserSessVals.get(SessionManager.KEY_FNAME);
	        sessionIdForUserLoggedLname = storedUserSessVals.get(SessionManager.KEY_LNAME);
	        sessionIdForUserLoggedName = storedUserSessVals.get(SessionManager.KEY_NAME);
	        sessionIdForUserLoggedEmail = storedUserSessVals.get(SessionManager.KEY_EMAIL);
			//Log.i("Change sessionIdForUserLoggedIn", sessionIdForUserLoggedIn+" "+sessionIdForUserGroupId);			
			//Log.i("Change url1", ""+Constants.Live_Url);		
			if(Common.sessionIdForUserGroupId==4){
				//Constants.Live_Url = Constants.Live_Url_Staging;
				
				Map<String, String> loginParams = new HashMap<String, String>();
				loginParams.put("user_id", ""+sessionIdForUserLoggedIn);
				loginParams.put("username", sessionIdForUserLoggedName);
				loginParams.put("password", storedUserSessVals.get(SessionManager.KEY_PASSWORD));
				loginParams.put("register_through", "0");
				loginParams.put("email_id", sessionIdForUserLoggedEmail);
		      /*  Log.i("loginParams", sessionIdForUserLoggedIn+" "+loginParams.get(0));
		        Log.i("loginParams", sessionIdForUserLoggedName+" "+loginParams.get(1));
		        Log.i("loginParams", storedUserSessVals.get(SessionManager.KEY_PASSWORD)+" "+loginParams.get(2));
		        Log.i("loginParams", ""+loginParams.get(3));
		        Log.i("loginParams", sessionIdForUserLoggedEmail+" "+loginParams.get(4));
		        Log.i("loginParams", ""+loginParams);*/
		        aq = new AQuery(activityThis);
		       // Log.i("loginParams",""+Constants.Live_Url+"mobileapps/"+Constants.Webservices_Folder+"/login_profiling/");
		        //Log.i("loginParams1",""+Constants.Live_Android_Url+"mobileapps/android/login/");
		        aq.progress(R.id.registerProgressBar).ajax(Constants.Live_Url+"mobileapps/"+Constants.Webservices_Folder+"/login_profiling/", loginParams, XmlDom.class, new AjaxCallback<XmlDom>(){
		        	@Override
					public void callback(String url, XmlDom xml, AjaxStatus status) {
		        		try{
					       // Log.i("loginParams xml", url+"   "+xml);
		        			if(xml!=null){
						      //  Log.i("loginParams url",""+url);
		        				List<XmlDom> xmlMsg = xml.tags("resultXml");
		        				for(XmlDom xmlMsg1 : xmlMsg){
							      //  Log.i("loginParams url",""+xmlMsg1);
			        				if(xmlMsg1.text("msg").toString().equals("success")){
								    //    Log.i("loginParams url",""+xmlMsg1);
				        				Common.sessionIdForUserLoggedIn = Integer.valueOf(xmlMsg1.text("id").toString());
				        				Common.sessionIdForUserLoggedFname = xmlMsg1.text("user_firstname").toString();
				        				Common.sessionIdForUserLoggedLname = xmlMsg1.text("user_lastname").toString();
				        				Common.sessionIdForUserLoggedName = xmlMsg1.text("username").toString();
				        				Common.sessionIdForUserLoggedEmail = xmlMsg1.text("email_id").toString();
				        			//	Log.i("Common.sessionIdForUserLoggedIn", ""+Common.sessionIdForUserLoggedIn);
				        				new LoginActivity().getUserOffersDetails(xmlMsg1.text("id").toString(), checkLoginFlag, stringArrayList2, activityThis);
			        				}
		        				}
		        			}
		        		}catch(Exception e){
			        		e.printStackTrace();
		        		}
		        	}
		        });
			} else {
				//Log.i("Change url8", ""+Constants.Live_Url);	
				Constants.Live_Url = Constants.Live_Url_Main;
				new LoginActivity().getUserOffersDetails(""+Common.sessionIdForUserLoggedIn, checkLoginFlag, stringArrayList2, activityThis);
				//Log.i("Change url9", ""+Constants.Live_Url);	
			}
			//Log.i("Change url2", ""+Constants.Live_Url);
		//	Log.i("Common.sessionIdForUserLoggedIn", ""+Common.sessionIdForUserLoggedIn);
			return true;
		} catch(Exception e){
			e.printStackTrace();
			  String errorMsg = activityThis+" | getStoredUserSessionDetails       |   " +e.getMessage();
			  sendCrashWithAQuery(activityThis,errorMsg);
			return false;
		}
	}
	public static String sessionForAndroidVersionName = "";	
	public void getCommonStoredAndroidVersionName(Activity activityThis){
		try{
	        session = new SessionManager(activityThis);
	        HashMap<String, String> storedSessValsDisplay = session.getStoredAndroidVersionName();
	        // get user data from session
	        sessionForAndroidVersionName = storedSessValsDisplay.get(SessionManager.KEY_ANDROID_VERSION_NAME);
		} catch(Exception e){
			e.printStackTrace();
			 String errorMsg = activityThis.getClass().getSimpleName()+" | getCommonStoredAndroidVersionName       |   " +e.getMessage();
			  sendCrashWithAQuery(activityThis,errorMsg);
		}
	
	}
	
	RelativeLayout rlInstruction;
    public void instructionBox(Activity activityThis, int instructiontitlenonwk, int instructionmsgnonwk){
   		try{	
   			rlInstruction = (RelativeLayout)activityThis.findViewById(R.id.rlInstruction);
		 	rlInstruction.setVisibility(View.VISIBLE);
			RelativeLayout.LayoutParams rlInstrLayout = (RelativeLayout.LayoutParams) rlInstruction.getLayoutParams();
			rlInstrLayout.height = (int) (0.291 * Common.sessionDeviceHeight);
			rlInstrLayout.topMargin = (int) (0.0718 * Common.sessionDeviceHeight);
			rlInstruction.setLayoutParams(rlInstrLayout);
			rlInstruction.setVisibility(View.VISIBLE);
			
			TextView instruction = (TextView)activityThis.findViewById(R.id.instruction);
			RelativeLayout.LayoutParams rlInstrMsg = (RelativeLayout.LayoutParams) instruction.getLayoutParams();
			rlInstrMsg.leftMargin = (int) (0.119 * Common.sessionDeviceWidth);
			rlInstrMsg.bottomMargin = (int) (0.061 * Common.sessionDeviceHeight);
			rlInstrMsg.width = (int) (0.67 * Common.sessionDeviceWidth);
			rlInstrMsg.height = (int) (0.082 * Common.sessionDeviceHeight);
			instruction.setLayoutParams(rlInstrMsg);
			instruction.setTextSize((float) ((0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			
   			

   			TextView label = (TextView)activityThis.findViewById(R.id.label);
   			label.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));

   			ImageView imgClose = (ImageView)activityThis.findViewById(R.id.closeinc);
   			RelativeLayout.LayoutParams rlInstrClose = (RelativeLayout.LayoutParams) imgClose.getLayoutParams();
   			rlInstrClose.width = (int) (0.084 * Common.sessionDeviceWidth);
   			rlInstrClose.height = (int) (0.052 * Common.sessionDeviceHeight);
   			rlInstrClose.bottomMargin = (int) (0.039 * Common.sessionDeviceHeight);
   			rlInstrClose.leftMargin = (int) (0.02 * Common.sessionDeviceWidth);
   			imgClose.setLayoutParams(rlInstrClose);

   			/*ImageView imgIcon= (ImageView)activityThis.findViewById(R.id.icon);
   			RelativeLayout.LayoutParams rlInstrIcon = (RelativeLayout.LayoutParams) imgIcon.getLayoutParams();
   			rlInstrIcon.width = (int) (0.134 * Common.sessionDeviceWidth);
   			rlInstrIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
   			rlInstrIcon.topMargin = (int) (0.0154 * Common.sessionDeviceWidth);
   			imgIcon.setLayoutParams(rlInstrIcon);
   			*/
   			
   			label.setText(instructiontitlenonwk);	   			
   	   		instruction.setText(instructionmsgnonwk);
   			rlInstruction.setVisibility(View.VISIBLE);
   		    Runnable mRunnable;
   			Handler mHandler=new Handler();
   			mRunnable=new Runnable() {
   	            @Override
   	            public void run() {   	                				            	
       				rlInstruction.setVisibility(View.INVISIBLE);
   	            }
   	        };
   			mHandler.postDelayed(mRunnable,5000);   			
	   		/*imgIcon.setOnClickListener(new OnClickListener() {	   			
	   			@Override
	   			public void onClick(View v) {
	   				rlInstruction.setVisibility(View.INVISIBLE);
	   			}
	   		});*/
	   		rlInstruction.setOnClickListener(new OnClickListener() {	   			
	   			@Override
	   			public void onClick(View v) {
	   				rlInstruction.setVisibility(View.INVISIBLE);
	   			}
	   		});
   			
   		} catch(Exception e){
   			e.printStackTrace();
   			String errorMsg = activityThis.getClass().getSimpleName()+" | instructionBox       |   " +e.getMessage();
			  sendCrashWithAQuery(activityThis,errorMsg);
   		}
   	}
    public void hideInstruction(View v){
		RelativeLayout rlInstruction = (RelativeLayout)v.findViewById(R.id.rlInstruction);
		rlInstruction.setVisibility(View.GONE);
	}
    
    public static void sendCrashWithAQuery(Activity activityThis,  String errorMsg) {
    	aqNew = new AQuery(activityThis);
        try{
        	//Log.i("sessionID", ""+Common.sessionIdForUserAnalytics);
        	PackageManager manager = activityThis.getPackageManager();
			PackageInfo info = manager.getPackageInfo(activityThis.getPackageName(), 0);
            // Create a NameValuePair out of the JSONObject + a name
        	Map<String, String> params = new HashMap<String, String>();
            //params.put("sample", "manohar");
            params.put("user_id", ""+Common.sessionIdForUserLoggedIn);
			params.put("session_id", Common.sessionIdForUserAnalytics);
			params.put("device_type", android.os.Build.MODEL);
			params.put("os_version", android.os.Build.VERSION.RELEASE);
			params.put("error_message", errorMsg);			
			params.put("crash_type", "1");
			params.put("build_version", info.versionName);			

			//Log.i("activityThis", ""+activityThis);
			//Log.i("params", ""+params);
			//Log.i("Constants.Live_Android_Url", Constants.crashLog);
			aqNew.ajax(Constants.crashLog, params, XmlDom.class, new AjaxCallback<XmlDom>(){
        	@Override
			public void callback(String url, XmlDom xml, AjaxStatus status) {
        		try{
        			if(xml!=null){
	        				Log.i("status", xml+" "+xml);	        				
        				}
        			} catch (Exception e){
        				e.printStackTrace();
        			}
                }
        	});
            //Common.aMap.clear();
        }
        catch(Exception e){
            e.printStackTrace();
            //createDialog("Error", "Cannot Establish Connection");
        }
    }
    
    public void resetTriggers(){
    	try{
    		deleteFiles(Constants.Trigger_Location);
    		deleteChangeLogFields("trigger",0);
    	}catch(Exception e){
    		e.printStackTrace();
    	}
 		
 	}
    
    public Boolean checkProductOfferExistindb(String type, String id) {
    	Boolean changeLog =  false;
		try{ 
			//function 1 to check whether product/offer exist in db	  	
			  FileTransaction file = new FileTransaction();			  
			  if(type.equals("product")){
				  	ProductModel  getProdDetail = file.getProduct();
				  	if(!id.equals("null")){
				  	UserProduct chkProdExist = getProdDetail.getUserProductById(Integer.parseInt(id));
				  	if(chkProdExist != null){
				  		changeLog = true;
				  	}
				  	}
 				}else if(type.equals("offer")){
				  Offers offers = file.getOffers();
					if(!id.equals("null")){
				  UserOffers offerExist = offers.getUserOffers(Integer.parseInt(id));
				  if(offerExist != null){
				  		changeLog = true;
				  	}
					}
			  }
	  		    			
 		}catch(Exception e){
 			e.printStackTrace();
 			
 		}
		return changeLog;   
		
	}
   
    
    public Boolean checkOfferExistinChangeLog(String type, String id) {
    	Boolean changeLog =  false;
		try{ 
			//function 2 to check whether product/offer exist in change log
	  			//Log.i("trigger checkProductExistinChangeLog","trigger checkProductExistinChangeLog");	
	  		    FileTransaction file = new FileTransaction();
				ChangeLogModel changeLogModelData = file.getChangeLog();		
	 			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
	 			if(userChangeLogList.size() > 0){
	 	  			    	 			
		    			for(UserChangeLog userChangeLog : userChangeLogList){
		    				if(type.equals("trigger")){		    					   	    				
		    					if(userChangeLog.getClientId() == Integer.parseInt(id) || userChangeLog.getTriggerId() == Integer.parseInt(id)){		    								    			
		    						changeLog = true;
			    				}   
		    				}
		    				else if(type.equals("visual")){
		    					if(userChangeLog.getTriggerVisualId() == Integer.parseInt(id)){
		    						changeLog = true;
		    					}
		    				}
		    				else if(type.equals("product")){		    					   	    				
		    					if(userChangeLog.getProdtId() == Integer.parseInt(id)){		    								    			
		    						changeLog = true;
			    				}    			 
			    			}	
		    				else if(type.equals("offer")){		    					   	    				
		    					if(userChangeLog.getOfferId() == Integer.parseInt(id)){		    								    			
		    						changeLog = true;
			    				}    			 
			    			}else if(type.equals("offerCalender")){
			    				String[] offerCalenderIds =  id.split(",");
			    				for(int i=0;i<offerCalenderIds.length;i++){
			    					if(userChangeLog.getOfferId() == Integer.parseInt(offerCalenderIds[i])){
			    						changeLog = true;		
			    					}
			    				}			    				
			    			}
			    			else if(type.equals("myoffers")){
			    				String[] offerCalenderIds =  id.split(",");
			    				String changeofferIds ="";
			    				int j=0;
			    				for(int i=0;i<offerCalenderIds.length;i++){			    					
			    					if(userChangeLog.getOfferId() == Integer.parseInt(offerCalenderIds[i])){
			    						if(changeofferIds.equals("")){
			    							changeofferIds = ""+userChangeLog.getOfferId();
			    						}else{
			    							if(j<offerCalenderIds.length -1){
			    								changeofferIds = changeofferIds+ ","+userChangeLog.getOfferId();
			    							}
			    						}
			    							
			    					}
			    					j++;
			    				}
			    				if(j==offerCalenderIds.length){
			    					 new MyOffers();
			    					 MyOffers.changeOfferIds =changeofferIds;
			    					changeLog = true;	
			    				}
			    			}
		    			}
		    		}	    			
 		}catch(Exception e){
 			e.printStackTrace();
 			
 		}
		return changeLog;   
		
	}
    public void deleteChangeLogFields(String type,int pdofid){ 
    	try{
    		//function 4 delete Visual/Products/Offers from change log
    		
    		List<UserChangeLog> list = new ArrayList<UserChangeLog>();
    		Date d = Calendar.getInstance().getTime(); // Current time
    		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); // Set your date format
			String date = sdf.format(d);
			Date currentDate;
			if(!Common.sessionForUserAppOpenDate.equals("null"))
				currentDate = sdf.parse(Common.sessionForUserAppOpenDate);
			else
				currentDate = sdf.parse(date);
		    FileTransaction file = new FileTransaction();
		    ChangeLogModel changeLogModelData = file.getChangeLog();		    				
			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
			for(UserChangeLog userChangeLog : userChangeLogList){
				if(type.equals("trigger")){
					if(userChangeLog.getClientId() !=0 || userChangeLog.getTriggerId() != 0 && (sdf.parse(userChangeLog.getCreatedDate()).after(currentDate) || sdf.parse(userChangeLog.getCreatedDate()).equals(currentDate))){
						userChangeLog.setClientId(0);
						userChangeLog.setTriggerId(0);
					}
				}
				else if(type.equals("visual")){
					if(userChangeLog.getVisulaId() != 0 && (sdf.parse(userChangeLog.getCreatedDate()).after(currentDate) || sdf.parse(userChangeLog.getCreatedDate()).equals(currentDate))){
						userChangeLog.setVisualId(0);
						userChangeLog.setTriggerVisualId(0);
					}
				}
				else if(type.equals("product")){
					if(userChangeLog.getProdtId() != 0 && userChangeLog.getProdtId() == pdofid && (sdf.parse(userChangeLog.getCreatedDate()).after(currentDate) || sdf.parse(userChangeLog.getCreatedDate()).equals(currentDate))){
						userChangeLog.setProdtId(0);
					}
				}
				else if(type.equals("offer")){
					if(userChangeLog.getOfferId() != 0 && userChangeLog.getOfferId() == pdofid && (sdf.parse(userChangeLog.getCreatedDate()).after(currentDate) || sdf.parse(userChangeLog.getCreatedDate()).equals(currentDate))){
						userChangeLog.setOfferId(0);
					}
				}
				list.add(userChangeLog);	 	 		
		 	}
			changeLogModelData.updateChangeLog(list);
			file.setChangeLog(changeLogModelData);
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
    	
    
    public static List<String> sessionTriggerScannedVals;
	public void getCommonStoredSessionForRecentlyScanned2(Activity activityThis) {		
		try{
			sessionTriggerScannedVals = new ArrayList<String>();
			session = new SessionManager(activityThis);		
	        HashSet<String> storedSessVals = session.getStoredSessionForRecentlyScanned();
	        HashSet<String> list = new HashSet<String>(storedSessVals);
            Iterator<String> itr = list.iterator();
	        while(itr.hasNext()) {
	        	String current = itr.next();
        		sessionTriggerScannedVals.add(current);
	        }
            Collections.sort(sessionTriggerScannedVals);
            Collections.reverse(sessionTriggerScannedVals);
		} catch(Exception e){
			e.printStackTrace();
		}
	}	
	
	public static Location location;
	public static Double lat =0.0;
	public static Double lng=0.0;
	public static LocationManager locManager;
	private static boolean gps_enabled = false;
	private static boolean network_enabled = false;
	public static LocationListener locListener = new myLocationListener();	
	public  void getLatLng(Activity activitythis){
		try{
			
		    	 locManager = (LocationManager) activitythis.getSystemService(Context.LOCATION_SERVICE);
		 	    try {
		 	        gps_enabled = locManager
		 	                .isProviderEnabled(LocationManager.GPS_PROVIDER);
		 	    } catch (Exception ex) {
		 	    }
		 	    try {
		 	        network_enabled = locManager
		 	                .isProviderEnabled(LocationManager.NETWORK_PROVIDER);
		 	    } catch (Exception ex) {
		 	    }
		 	    if (gps_enabled) {
		 	        locManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0,
		 	                0, locListener);
		 	    }
		 	    if (network_enabled) {
		 	        locManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,
		 	                0, 0, locListener);
		 	    }
		 	
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	
	private static class myLocationListener implements LocationListener {
	                                      
	    
	    @Override
	    public void onLocationChanged(Location location) {
	    	try{	
		        if (location != null) {
		            locManager.removeUpdates(locListener);
		            lng = location.getLongitude();
		            lat = location.getLatitude();
		            String longitude = "Longitude: " + location.getLongitude();
		            String latitude = "Latitude: " + location.getLatitude();
		          //  Log.v("Debug",  "Latt: " + latitude + " Long: " + longitude);		          
		            
		        }
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	    }

	    @Override
	    public void onProviderDisabled(String provider) {
	    }

	    
	    @Override
	    public void onProviderEnabled(String provider) {
	    }

	    

		@Override
		public void onStatusChanged(String provider, int status, Bundle extras) {
			
			
		}
	}
	public static Boolean isAppBackgrnd=false;
	public  boolean isApplicationBroughtToBackground(Activity activitythis) {
	    ActivityManager am = (ActivityManager) activitythis.getSystemService(Context.ACTIVITY_SERVICE);
	    List<RunningTaskInfo> tasks = am.getRunningTasks(1);
	    if (!tasks.isEmpty()) {
	        ComponentName topActivity = tasks.get(0).topActivity;	       
	        if (!topActivity.getPackageName().equals(activitythis.getPackageName())) {	        	
	            return true;
	        }
	    }
	    //return isAppBackgrnd;
	    return false;
	}
	public static Boolean isActive=false;
	public void storeChangeLogResultFromServer(final Activity activityThis){		 
	  	try { 
	  			aq = new AQuery(activityThis);
	  			aq.progress(R.id.progressBar).ajax(Constants.changeLog, XmlDom.class, new AjaxCallback<XmlDom>(){
		    	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status){
		    		try{
		    			if(xml!=null){
		    				if(xml.tags("resultXml") != null){
			    				FileTransaction file = new FileTransaction();
			    				final List<XmlDom> entries = xml.tags("resultXml");
			    				
			    				
			    				ChangeLogModel changeLogModel; 
			    				ChangeLogModel changeLogModelData = file.getChangeLog();
			    				if(changeLogModelData.size() == 0)
			    					 changeLogModel = new ChangeLogModel();
			    				else
			    					changeLogModel = file.getChangeLog();
			    				Date d = Calendar.getInstance().getTime(); // Current time
			    				SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); // Set your date format
			    				String date = sdf.format(d);
			    				//Date currentDate = sdf.parse(date);
			    				
			    				
			    				Date currentDate,newDate;
			    				if(!Common.sessionForUserAppOpenDate.equals("null"))
			    					currentDate = sdf.parse(Common.sessionForUserAppOpenDate);
			    				else
			    					currentDate = sdf.parse(date);
			    				
			    				
			    				//Date currentDate = sdf.parse(new Date().toString());
			    				//Log.e("currentDate",currentDate.toString());
			    				int dateFlag=0;		    				
			    				if(entries.size() > 0){
			    					List<String> idsList = changeLogModelData.getChangeLogIdsbyDate();
			    					
			    				 for(XmlDom entry: entries){
			    					 if(entry.text("change_log_id") != null){			    						
			    						 newDate = sdf.parse(entry.text("created_date"));			    							
			    					 if(!idsList.contains(""+entry.text("change_log_id")) &&  (newDate.getTime() > currentDate.getTime()  || newDate.equals(currentDate))){			    					 	
				    					 UserChangeLog userChangeLog = new UserChangeLog();
				    					 userChangeLog.setId(Integer.parseInt(entry.text("change_log_id")));
				    					 userChangeLog.setClientId(Integer.parseInt(entry.text("client_id")));
				    					 userChangeLog.setTriggerId(Integer.parseInt(entry.text("trigger_id")));	
				    					 userChangeLog.setVisualId(Integer.parseInt(entry.text("visual_id")));
				    					 userChangeLog.setTriggerVisualId(Integer.parseInt(entry.text("trigger_visual_id")));
				    					 userChangeLog.setProdtId(Integer.parseInt(entry.text("product_id")));
				    					 userChangeLog.setOfferId(Integer.parseInt(entry.text("offer_id")));
				    					 userChangeLog.setCreatedDate(entry.text("created_date"));
				    					 changeLogModel.add(userChangeLog);	 				    					 			    						    						 
						    			dateFlag ++;					    			    					 
			    					 }
			    					 }
			    				 }	
			    				 
			    				 if(dateFlag >0){			    					 
			    					 file.setChangeLog(changeLogModel);
			    				 }
			    				}
			    				isActive = true;
			    				session = new SessionManager(activityThis);
				    	        session.createAppOpenDate(); 
		    				}
		    			}
		    		}catch(Exception e){
		    			e.printStackTrace();
		    			String errorMsg = activityThis.getClass().getSimpleName()+" | storeChangeLogResultFromServer callback |   " +e.getMessage();
		           	 	Common.sendCrashWithAQuery(activityThis,errorMsg);
		    		}
		    	}
	  			});
	  		
	  			} catch (Exception e) {
	  				e.printStackTrace();
	  				String errorMsg = activityThis.getClass().getSimpleName()+" | storeChangeLogResultFromServer |   " +e.getMessage();
	  	       	 	Common.sendCrashWithAQuery(activityThis,errorMsg);
	  			}
	  	
	  	
	}

	public void headerAndFooterModules(final Activity activityThis) {		
		try{
			aq = new AQuery(activityThis);
			
			
			ImageView imgBackButton = (ImageView) activityThis.findViewById(R.id.imgvBtnBack);
			imgBackButton.setOnClickListener(new OnClickListener() {		
				@Override
				public void onClick(View v) {	
					try{
				    	 Intent intent;
				    	 if(activityThis.getClass().getSimpleName().equals("OrderConfirmation")){
				    		 intent = new Intent(activityThis, ProductDetails.class);
				    	 } else if(activityThis.getClass().getSimpleName().equals("ShippingListInfo")){
				    		 intent = new Intent(activityThis, OrderConfirmation.class);
				    	 } else {
				    		 intent = new Intent(activityThis, Closet.class);
				    	 }
				    	 activityThis.setResult(1, intent);
			    		 activityThis.finish();
				    	 /*if(activityThis.getClass().getSimpleName().equals("OrderConfirmation")){
				    		 intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
				    		 activityThis.startActivity(intent);
				    	 } else {
					    	 activityThis.setResult(1, intent);
				    		 activityThis.finish();
				    	 }*/
				    	 activityThis.overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					} catch (Exception e){
						e.printStackTrace();
						String errorMsg = activityThis.getClass().getSimpleName()+" | imgBackButton click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(activityThis,errorMsg);
					}	
				}
			});		
			if(activityThis.getClass().getSimpleName().equals("OrderConfirmation") || activityThis.getClass().getSimpleName().equals("SaveOrderInformation")){
				
				TextView txtvShop = (TextView)activityThis.findViewById(R.id.txtvShop);
				txtvShop.setTextSize((float) (0.0416* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
				txtvShop.setOnClickListener(new OnClickListener() {
					@Override
					public void onClick(View v) {
						Intent intent = null;
				    	 if(activityThis.getClass().getSimpleName().equals("OrderConfirmation")){
				    		 intent = new Intent(activityThis, Products.class);
				    		 intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);					
				    		 activityThis.startActivity(intent);	
				    		 activityThis.finish();
				    	 } 
				    	
					}
				});
			}else{

				ImageView imgBtnCart = (ImageView) activityThis.findViewById(R.id.imgvBtnCart);
				imgBtnCart.setImageAlpha(0);
			}
				
			ImageView imgvBtnShare = (ImageView) activityThis.findViewById(R.id.imgvBtnShare);
			imgvBtnShare.setImageAlpha(0);

			ImageView imgvBtnCloset = (ImageView) activityThis.findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);
			
	        ImageView imgFooterMiddle = (ImageView) activityThis.findViewById(R.id.imgvFooterMiddle); 
		    imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View view) {
	            	try{
						Intent intent = new Intent(activityThis, MenuOptions.class);
						int requestCode = 0;
						activityThis.startActivityForResult(intent, requestCode);
						activityThis.overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
	            	} catch (Exception e) {
						//Toast.makeText(getApplicationContext(), "Error: ProductInfo imgFooterMiddle.", Toast.LENGTH_LONG).show();
	            		e.printStackTrace();
	            		String errorMsg = activityThis.getClass().getSimpleName()+" | imgFooterMiddle  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(activityThis, errorMsg);
					}
	            }
		    });
				
		} catch (Exception e){
			e.printStackTrace();
			String errorMsg = activityThis.getClass().getSimpleName()+" | headerAndFooterModules |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(activityThis,errorMsg);
		}		
	}   
	 public static boolean ServerFileexists(String URLName)
	 {
        try {
		      HttpURLConnection.setFollowRedirects(false);
		      // note : you may also need
		      //HttpURLConnection.setInstanceFollowRedirects(false)

		      HttpURLConnection con =  (HttpURLConnection) new URL(URLName).openConnection();
		      con.setRequestMethod("HEAD");
		      return (con.getResponseCode() == HttpURLConnection.HTTP_OK);
        }catch (Exception e) {
		       e.printStackTrace();
		       return false;
		    }
	}
	 
	 public void btnForVideoWithAllColors(final Activity activityThis, Button btnSeeItLive, String strLayout, String strWidth, 
				String strTitle, final String strPdId, final String strClientId, final String videoUrl, 
				final String bgColorCode, final String bgLightColorCode, final String bgDarkColorCode, final String clientLogo){
			try{	
				if(strLayout.equals("linear")){
					LinearLayout.LayoutParams lpImgSeeItLive = (LinearLayout.LayoutParams) btnSeeItLive.getLayoutParams();		
					if(strTitle.equals("products") && strWidth.equals("width")){
						lpImgSeeItLive.width = (int) (0.265 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.082 * Common.sessionDeviceHeight);
					} else {
						lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
						lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;				
					}
					//Log.i("btnForSeeItLive lp", ""+Common.sessionClientBgColor+" "+bgColorCode);
					btnSeeItLive.setLayoutParams(lpImgSeeItLive);	
				} else { 
					RelativeLayout.LayoutParams lpImgSeeItLive = (RelativeLayout.LayoutParams) btnSeeItLive.getLayoutParams();
					if(strTitle.equals("products") && strWidth.equals("width")){						
						lpImgSeeItLive.width = (int) (0.334 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
						btnSeeItLive.setPadding(0, (int) (0.0258*Common.sessionDeviceHeight)/Common.sessionDeviceDensity, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);	
					} else if(strTitle.equals("product_info") && strWidth.equals("width")){
						
						lpImgSeeItLive.width = (int) (0.334 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
						lpImgSeeItLive.rightMargin = (int) (0.1 * Common.sessionDeviceWidth);
						lpImgSeeItLive.topMargin = (int) (0.081 * Common.sessionDeviceHeight);
						btnSeeItLive.setPadding(0, (int) (0.0258*Common.sessionDeviceHeight)/Common.sessionDeviceDensity, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);	
					}  else if(strTitle.equals("financial") && strWidth.equals("width")){
						
						lpImgSeeItLive.width = (int) (0.1667 * Common.sessionDeviceWidth);
						lpImgSeeItLive.height = (int) (0.103 * Common.sessionDeviceHeight);
						btnSeeItLive.setPadding(0,(int) (0.0258*Common.sessionDeviceHeight)/Common.sessionDeviceDensity, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);	
					}  else {
						lpImgSeeItLive.width = LayoutParams.WRAP_CONTENT;
						lpImgSeeItLive.height = LayoutParams.WRAP_CONTENT;	
						btnSeeItLive.setPadding(0, (int) (0.0258*Common.sessionDeviceHeight)/Common.sessionDeviceDensity, (int) (0.0167*Common.sessionDeviceWidth)/Common.sessionDeviceDensity, 0);			
					}
					
					btnSeeItLive.setLayoutParams(lpImgSeeItLive);
				}
				if(strTitle.equals("products") || strTitle.equals("product_info")){
					btnSeeItLive.setTextSize((float) ((0.04 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
				}else if(strTitle.equals("financial")){
					btnSeeItLive.setTextSize((float) ((0.03 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
				} else if(strWidth.equals("1") || strWidth.equals("5")){ 
					btnSeeItLive.setTextSize((float) ((0.0134 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
				} else if(strWidth.equals("2") || strWidth.equals("4")){ 
					btnSeeItLive.setTextSize((float) ((0.015 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
				} else {
					btnSeeItLive.setTextSize((float) ((0.0167 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));				
				}
				btnSeeItLive.setTag(strPdId);
				int iconColor = 0;
				if(!videoUrl.equals("null")){
					//Log.i("bgColorCode", ""+bgColorCode+" "+bgLightColorCode+" "+bgDarkColorCode);
					// Load the icon as drawable object
					Drawable d = activityThis.getResources().getDrawable(R.drawable.see_it_video);				 
					// Get the color of the icon depending on system state		
					if(bgColorCode.equals("") || bgColorCode.equals("null")){
						iconColor = Color.parseColor("#ff2600");					
					} else {
						iconColor = Color.parseColor("#"+bgColorCode);					
					}
					// Set the correct new color
					d.setColorFilter( iconColor, Mode.MULTIPLY );	
					d = d.mutate();
					// Load the updated drawable to the image viewer
					btnSeeItLive.setBackground(d);
					btnSeeItLive.setVisibility(View.VISIBLE);
					btnSeeItLive.setOnClickListener(new OnClickListener() {						
						@Override
						public void onClick(View v) {						
							try{
								if(Common.isNetworkAvailable(activityThis)){
								Common.sessionClientBgColor = bgColorCode;
								Common.sessionClientBackgroundLightColor = bgLightColorCode;
								Common.sessionClientBackgroundDarkColor = bgDarkColorCode;
								Common.sessionClientLogo = clientLogo;
								Common.sessionClientId = strClientId;
								Common.sessionProductId = strPdId;
								Intent intent = new Intent(activityThis, VideoActivity.class);
								intent.putExtra("videoUrl", videoUrl);								
								activityThis.startActivity(intent);
								//activityThis.finish();
								activityThis.overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
								}else{
									instructionBox(activityThis,R.string.title_case7,R.string.instruction_case7);
								}
							} catch(Exception e){
								e.printStackTrace();
							}
						}
					});
				} else {
					btnSeeItLive.setVisibility(View.INVISIBLE);
				}
				
			} catch(Exception e){
				e.printStackTrace();
				String errorMsg = activityThis.getClass().getSimpleName()+" | btnForSeeItLiveWithAllColors       |   " +e.getMessage();
				sendCrashWithAQuery(activityThis,errorMsg);
			}
		}
	 public void httpPostResponse(){
		 try{
			 
			 HttpClient httpclient=new DefaultHttpClient();  
             HttpPost httpPost=new HttpPost(Constants.Live_Url+"data_analytics/sample/");
             List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
             
            /* nameValuePairs.add(new BasicNameValuePair("user_id", loggedInUserId));
             nameValuePairs.add(new BasicNameValuePair("session_id", Common.sessionIdForUserAnalytics));
             nameValuePairs.add(new BasicNameValuePair("screen_path", screenName));
             nameValuePairs.add(new BasicNameValuePair("prod_ids", productIds));
             nameValuePairs.add(new BasicNameValuePair("offer_ids", offerIds));
             nameValuePairs.add(new BasicNameValuePair("device_os",  "ANDROID"));
             nameValuePairs.add(new BasicNameValuePair("device_type", android.os.Build.MODEL));
             nameValuePairs.add(new BasicNameValuePair("device_brand", android.os.Build.BRAND));
             nameValuePairs.add(new BasicNameValuePair("device_os_version", android.os.Build.VERSION.RELEASE));
             nameValuePairs.add(new BasicNameValuePair("version_name", info.versionName));
             nameValuePairs.add(new BasicNameValuePair("user_address", ""));
             nameValuePairs.add(new BasicNameValuePair("user_country", ""));
             nameValuePairs.add(new BasicNameValuePair("user_state", ""));
             nameValuePairs.add(new BasicNameValuePair("user_city", ""));
             nameValuePairs.add(new BasicNameValuePair("time_on_screen", ""+android.os.Build.TIME));			
             nameValuePairs.add(new BasicNameValuePair("lat_long", ""+lat+"|"+lng));
             nameValuePairs.add(new BasicNameValuePair("device_bundle_version",info.versionName));
             nameValuePairs.add(new BasicNameValuePair("id","manoj"));
             nameValuePairs.add(new BasicNameValuePair("password", " "));
             nameValuePairs.add(new BasicNameValuePair("action", "validate_password"));*/
                
                    httpPost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                
                    HttpResponse response = httpclient.execute(httpPost);
                    String t=response.getParams().toString();
                    String t1 = EntityUtils.toString(response.getEntity());
                    Log.e("response",t1);
                   
               
		 }catch(Exception e){
			 e.printStackTrace();
		 }
	 }
		
}
