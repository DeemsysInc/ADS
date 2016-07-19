package com.seemoreinteractive.seemoreinteractive.calendar;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.util.Log;

import com.seemoreinteractive.seemoreinteractive.MainActivity;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.Model.ClientStores;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.StoreTriggers;
import com.seemoreinteractive.seemoreinteractive.Model.Stores;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserStoreTriggers;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;

public class NotifyService extends Service {

	private Long counter = 0L; 
	private NotificationManager nm;
	private Timer timer = new Timer();
	private final Calendar time = Calendar.getInstance();
	public static LocationManager locManager;
	private LocationListener locListener = new myLocationListener();
	private Handler handler = new Handler();
	Thread t;


	private boolean gps_enabled = false;
	private boolean network_enabled = false;
	
	
	
	@Override
	public IBinder onBind(Intent intent) {
		// TODO Auto-generated method stub
		return null;
	}
	@Override
	public void onCreate() {
		super.onCreate();

		//nm = (NotificationManager)getSystemService(NOTIFICATION_SERVICE);
		//Toast.makeText(this,"Service created at " + time.getTime(), Toast.LENGTH_LONG).show();
		//incrementCounter();

		Log.i("service","service on create");
	}
	
    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
    
		/* final Runnable r = new Runnable() {
		     public void run() {
		         location();
		         handler.postDelayed(this, 10000);
		     }
		 };
		 handler.postDelayed(r, 10000);
    	*/
    	
    	 location();
        return START_STICKY;
    }
	
	@Override
	public void onDestroy() {
		super.onDestroy();
        // Cancel the persistent notification.
		//shutdownCounter();
        //nm.cancel(R.string.service_started);
	//	Toast.makeText(this, "Service destroyed at " + time.getTime() + "; counter is at: " + counter, Toast.LENGTH_LONG).show();
		//counter=null;
		//locManager.removeUpdates(locListener);
		//handler.removeCallbacksAndMessages(null);
		Log.i("Service destroyed ","notify service destroyed");
	}
	
	public void location() {
	    locManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

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
	}
	private class myLocationListener implements LocationListener {
	    double lat_old = 0.0;
	    double lon_old = 0.0;
	    double lat_new;
	    double lon_new;

	    @Override
	    public void onLocationChanged(Location location) {
	    	try{	
		        if (location != null) {
		            locManager.removeUpdates(locListener);
		            lon_new = location.getLongitude();
		            lat_new = location.getLatitude();
		            String longitude = "Longitude: " + location.getLongitude();
		            String latitude = "Latitude: " + location.getLatitude();
		            Log.v("Debug",  "Latt: " + latitude + " Long: " + longitude);
		          //  Toast.makeText(getApplicationContext(),longitude + "\n" + latitude, Toast.LENGTH_SHORT).show();
		            lat_old = lat_new;
		            lon_old = lon_new;
		            
		            triggerPushNotification(lat_new,lon_new);
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
			// TODO Auto-generated method stub
			
		}
	}
	
	
	
	   public void triggerPushNotification(double lat, double lng){
		   try{
	    	UserStoreTriggers userStoreTrigger = new UserStoreTriggers();
	    	Log.i("triggerPushNotification","start");
		    FileTransaction file = new FileTransaction();
	    	Offers offers = file.getOffers();
	    	ArrayList<String> offerClientIds = new ArrayList<String>();
	    	int i=0;
	    	if(offers.size() > 0){
	    		List<UserOffers> userOffers = offers.getAllOffers();
	    	for(UserOffers useroffer : userOffers){
	    		
	    		String expiry_date = useroffer.getOfferValidDate();	  
	    		Log.i("expiry_date",expiry_date);
	    		SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");
	    		//SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	    		Date strDate;
	    		Date currentDate;
				try {
					strDate = sdf.parse(expiry_date);
					currentDate = sdf.parse(sdf.format(new Date()));
					
				   
					if (strDate.after(currentDate)) {
		    		    if(!offerClientIds.contains(useroffer.getOfferClientId())){
		    		    offerClientIds.add(useroffer.getOfferClientId());
		    		    Stores store = file.getStores();
		    		     List<ClientStores> clientstores = store.getAllClientStores(useroffer.getOfferClientId());
		    		     Log.i("clientstores",""+clientstores.size());
		    		     final UserStoreTriggers stores = file.getStoresTriggers();
		    		    for(ClientStores clientstore :  clientstores){
		    		    	i++;
		    		    	double dis = distFrom(lat,lng,clientstore.getLatitude(),clientstore.getLongitude());
		    		    	Log.i("dis",""+dis);
		    		    	Log.i("threshoul",""+Float.parseFloat(clientstore.getStoreTriggerThreshold()));
		    		    	float threshold = Float.parseFloat(clientstore.getStoreTriggerThreshold());
		    		    	if(threshold >0.0){
		    		    		threshold = threshold;
		    		    	}else{
		    		    		threshold = 1;
		    		    	}
		    		    	Log.i("threshold",""+threshold);
		    		    	if(dis <  threshold){
		    		    	Log.i("dis",""+dis);
		    		    	Log.i("clientstore ",""+clientstore.getStoreName());
		    		    	Log.i("clientstore.getLatitude()",""+clientstore.getLatitude());
		    		    	Log.i("clientstore.getLongitude()",""+clientstore.getLongitude());
		    		    	
		    		    	if(stores.size() > 0){
		    		    		 StoreTriggers userStore = stores.getClientStoreList(clientstore.getStoreCode());
		    		    		if(userStore != null){
		    		    			
		    		    			//int diff =(int)( (new Date().getTime() - userStoreList.getCreatedDate().getTime()) / (1000 * 60 * 60 * 24));
		    		    			long diff = new Date().getTime() - userStore.getUpdatedDate().getTime();
		    		    			Log.i("thresholdUpdate date",""+userStore.getUpdatedDate()+"time"+userStore.getUpdatedDate().getTime());
		    		    			Log.i("diff",""+diff);
		    		    			//if(currentDate.after(sdf.parse(sdf.format(userStoreList.getCreatedDate())))){
		    		    			Log.i("new Date().compareTo(userStoreList.getCreatedDate())>0)",""+new Date().compareTo(userStore.getUpdatedDate()));
		    		    			//if(diff>0){
		    		    			long diffSec = diff / (1000) % 60;
		    		    			long diffMinutes = diff / (60 * 1000) % 60;
		    		    			long diffHours = diff / (60 * 60 * 1000) % 24;
		    		    			
		    		    			Log.i("diffHours",""+diffHours);
		    		    			
		    		    			int thresholdUpdate = (int) clientstore.getStoreTriggerUpdate();
		    		    			Log.i("thresholdUpdate",""+thresholdUpdate);
		    		    			if(thresholdUpdate > 0){
		    		    				thresholdUpdate = (thresholdUpdate % 3600) / 60;
		    		    			}else{
		    		    				thresholdUpdate = (24* 60);
		    		    			}
		    		    			Log.i("thresholdUpdate",""+thresholdUpdate);
		    		    			Log.i("thresholdUpdate diffMinutes",""+diffMinutes);
		    		    			if(diffMinutes > thresholdUpdate){
		    		    				int seconds = thresholdUpdate * 60;
		    		    				Log.i("thresholdUpdate seconds",""+thresholdUpdate);
			    		    			Log.i("thresholdUpdate getStoreTriggerUpdate()",""+clientstore.getStoreTriggerUpdate());
							    		if(seconds == clientstore.getStoreTriggerUpdate())
							    		{
								    		String address = clientstore.getAddress1()+", "+clientstore.getCity()+", "+clientstore.getState();
											ArrayList<String> stringArrayList = new ArrayList<String>();
							    			stringArrayList.add(""+i);
							    			stringArrayList.add(clientstore.getStoreName());
							    			stringArrayList.add(clientstore.getStoreSearchType());
							    			stringArrayList.add(""+clientstore.getLatitude());
							    			stringArrayList.add(""+clientstore.getLongitude());
							    			stringArrayList.add(""+clientstore.getStoreNotifyMsg());
							    			stringArrayList.add(""+clientstore.getStoreTriggerThreshold());
							    			stringArrayList.add(address);
							    			
							    			
							    			Log.i("stringArrayList",""+stringArrayList);
											String[] stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
								    		showNotification(stringArray);
								    		
								    		
								    		userStore.setUpdatedDate(new Date());
			    		    				Log.i("thresholdUpdate getVisits()",""+userStore.getVisits());
			    		    				userStore.setVisits(userStore.getVisits()+1);
			    		    				Log.i("userStoreList.getVisits()",""+userStore.getVisits());
			    		    				int visits = userStore.getVisits();
			    		    				List userStoreList = stores.getAllUserStoresList(clientstore.getStoreCode());
			    		    				List<StoreTriggers> allStores = stores.list();
			    		    				allStores.remove(userStoreList);
			    		    				
			    		    				
			    		    				StoreTriggers storeTriggers = new StoreTriggers(i);
						    		    	storeTriggers.setStoreCode(clientstore.getStoreCode());
						    		    	storeTriggers.setStoreName(clientstore.getStoreName());
						    		    	storeTriggers.setStoreSearchType(clientstore.getStoreSearchType());
						    		    	storeTriggers.setClientId(clientstore.getClientId());
						    		    	storeTriggers.setCreatedDate(new Date());
						    		    	storeTriggers.setUpdatedDate(new Date());
								    		storeTriggers.setVisits(visits+1);											    		
								    		userStoreTrigger.add(storeTriggers);				    		
								    		
								    		
								    		stores.mergeWithStores(userStoreTrigger);
								    		file.setStoresTriggers(stores);
							    		}
		    		    			}
		    		    				
		    		    			
					    			
		    		    		}
		    		    		
		    		    	}else{	    		    	
			    		    	StoreTriggers storeTriggers = new StoreTriggers(i);
			    		    	storeTriggers.setStoreCode(clientstore.getStoreCode());
			    		    	storeTriggers.setStoreName(clientstore.getStoreName());
			    		    	storeTriggers.setStoreSearchType(clientstore.getStoreSearchType());
			    		    	storeTriggers.setClientId(clientstore.getClientId());
			    		    	storeTriggers.setCreatedDate(new Date());
			    		    	storeTriggers.setUpdatedDate(new Date());
					    		storeTriggers.setVisits(1);											    		
					    		userStoreTrigger.add(storeTriggers);					    		
							   	file.setStoresTriggers(userStoreTrigger); 
							   
							   	String address = clientstore.getAddress1()+", "+clientstore.getCity()+", "+clientstore.getState();
								ArrayList<String> stringArrayList = new ArrayList<String>();
				    			stringArrayList.add(""+i);
				    			stringArrayList.add(clientstore.getStoreName());
				    			stringArrayList.add(clientstore.getStoreSearchType());
				    			stringArrayList.add(""+clientstore.getLatitude());
				    			stringArrayList.add(""+clientstore.getLongitude());
				    			stringArrayList.add(""+clientstore.getStoreNotifyMsg());
				    			stringArrayList.add(""+clientstore.getStoreTriggerThreshold());
				    			stringArrayList.add(""+clientstore.getStoreClientName());
				    			stringArrayList.add(address);
				    			Log.i("stringArrayList",""+stringArrayList);
								String[] stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
					    		showNotification(stringArray);
							  
		    		    	
				    		
		    		    	}
		    		    	}
		    		    }
		    		    }
		    		}
				} catch (ParseException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	    		
	    	}
	    	

	    
			
	    	}
		   }catch(Exception e){
			   e.printStackTrace();
		   }
	    	
	    }
	
	   public static double distFrom(double lat, double lng, double d, double e) {
	        double earthRadius = 3958.75;
	        double dLat = Math.toRadians(d-lat);
	        double dLng = Math.toRadians(e-lng);
	        double a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	                   Math.cos(Math.toRadians(lat)) * Math.cos(Math.toRadians(d)) *
	                   Math.sin(dLng/2) * Math.sin(dLng/2);
	        double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	        double dist = Math.ceil(earthRadius * c);

	        int meterConversion = 1609;

	        //return  (dist * meterConversion);
	        return dist;
	        }
	
	
	
	
	
    /**
     * Show a notification while this service is running.
     * @param stringArray 
     */
	private void showNotification(String[] stringArray) {

		try{
		
		// define sound URI, the sound to be played when there's a notification
		Uri soundUri  = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
		String latVal = stringArray[3];
		String lngVal = stringArray[4];
		// intent triggered, you can add other intent for other actions
		Intent intent = new Intent(NotifyService.this,MainActivity.class);    		
		/*intent.setAction(Intent.ACTION_MAIN);
		intent.addCategory(Intent.CATEGORY_LAUNCHER);
		*/

		intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP); 
		intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		intent.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);		    		
		intent.putExtra("notify", "true");
		intent.putExtra("lat",latVal);
		intent.putExtra("long",lngVal);
		intent.putExtra("search_type", stringArray[2]);
		intent.putExtra("search_name", stringArray[1]);
		
		//PendingIntent pIntent = PendingIntent.getActivity(NotificationService.this, 0, intent, 0);		    			
		//PendingIntent i=PendingIntent.getActivity(this, 0,new Intent(NotificationService.this, NotificationReceiver.class),0);
    	intent.setFlags(
		        PendingIntent.FLAG_UPDATE_CURRENT | 
		        PendingIntent.FLAG_ONE_SHOT);
		
		
			    PendingIntent pIntent = 
			        PendingIntent.getActivity(NotifyService.this, Integer.parseInt(stringArray[0]), 
			        intent, PendingIntent.FLAG_UPDATE_CURRENT | 
			        PendingIntent.FLAG_ONE_SHOT);

		
		
		//String contentMsg =stringArray[1] +" is near to your location. You can avail discount at this store, which is 0.1 miles away!";
				
		//String subContentMsg = ";	
	

		//Notification noti = new Notification.Builder(this).
		
		// this is it, we'll build the notification!
		// in the addAction method, if you don't want any icon, just set the first param to 0
	/*	Notification mNotification = new Notification.Builder(NotifyService.this)
			.setContentTitle("Seemore")
			//.setContentText(contentMsg)
			//.setSubText(subContentMsg)
			.setSmallIcon(R.drawable.seemore_icon)
			.setContentIntent(pIntent)
			.setSound(soundUri)
			//.setStyle(new Notification.BigTextStyle().bigText(longText)) 
			.addAction(R.drawable.seemore_icon, "View", pIntent)
			.addAction(0, "Remind", pIntent)
			.setAutoCancel(true)
			.build();
		
		*/
		
		
		
		String longText = stringArray[5];
		//longText = longText.replace("{offername}", "");
		 
		longText = longText.replace("{clientname}", stringArray[7]);
		longText = longText.replace("{storename}", stringArray[1]);
		longText = longText.replace("{distance}", stringArray[6]);
		
	   
	    android.app.Notification.Builder builder = new Notification.Builder(this);
	    builder.setContentTitle("Seemore")
		.setContentText(longText)
		//.setSubText(subContentMsg)
		.setSmallIcon(R.drawable.seemore_icon)
		.setContentIntent(pIntent)
		.setSound(soundUri)
		//.addAction(R.drawable.seemore_icon, "View", pIntent)
		//.addAction(0, "Remind", pIntent)
		.setAutoCancel(true);
	    
	    Notification mNotification = new Notification.BigTextStyle(builder)
        .bigText(longText).build();

	  /*  builder.setContentTitle("Big text Notofication")
	            .setContentText("Big text Notification")
	            .setSmallIcon(R.drawable.ic_launcher).setAutoCancel(true)
	            .setPriority(Notification.PRIORITY_HIGH)
	            .addAction(R.drawable.ic_launcher, "show activity", pi);
	    Notification notification = new Notification.BigTextStyle(builder)
	            .bigText(msgText).build();

	    notificationManager.notify(0, notification);*/
	
		
		NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);

		// If you want to hide the notification after it was selected, do the code below
		// myNotification.flags |= Notification.FLAG_AUTO_CANCEL;
		int x = (int) Math.random();
		notificationManager.notify(Integer.parseInt(stringArray[0]), mNotification);
		
 	//	mNotification.number=++count;
 		mNotification.flags = Notification.DEFAULT_LIGHTS | Notification.FLAG_AUTO_CANCEL;
		//mNotification.vibrate=new long[] {500L, 200L, 200L, 500L};
		//mNotification.flags|=Notification.FLAG_AUTO_CANCEL;
 	/*	mNotification.flags = Notification.FLAG_SHOW_LIGHTS | 
 		        Notification.FLAG_ONGOING_EVENT | 
 		        Notification.FLAG_ONLY_ALERT_ONCE | 
 		        Notification.FLAG_AUTO_CANCEL;*/
		}catch(Exception e){
			e.printStackTrace();
		}
    }
    
    private void incrementCounter() {
    	timer.scheduleAtFixedRate(new TimerTask(){ @Override
		public void run() {counter++;}}, 0, 1000);
    }
    
    private void shutdownCounter() {
    	if (timer != null) {
    		timer.cancel();
    	}
    }
}
