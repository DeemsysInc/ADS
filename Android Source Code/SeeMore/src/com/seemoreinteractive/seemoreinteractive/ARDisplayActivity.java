// Copyright 2007-2013 metaio GmbH. All rights reserved.
package com.seemoreinteractive.seemoreinteractive;
 
import java.io.File;
import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.text.DateFormat;
import java.text.DecimalFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.NoSuchElementException;
import java.util.Set;
import java.util.Timer;
import java.util.TimerTask;

import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlarmManager;
import android.app.AlertDialog;
import android.app.PendingIntent;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.Configuration;
import android.graphics.PixelFormat;
import android.hardware.Camera.CameraInfo;
import android.location.LocationManager;
import android.media.MediaPlayer;
import android.media.MediaPlayer.OnCompletionListener;
import android.media.MediaPlayer.OnPreparedListener;
import android.media.MediaPlayer.OnVideoSizeChangedListener;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.PowerManager;
import android.os.PowerManager.WakeLock;
import android.os.SystemClock;
import android.support.v4.view.ViewPager;
import android.text.TextUtils;
import android.util.Log;
import android.view.GestureDetector;
import android.view.GestureDetector.SimpleOnGestureListener;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.Animation.AnimationListener;
import android.view.animation.AnimationUtils;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.MediaController;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.SlidingDrawer;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.VideoView;
import android.widget.ViewFlipper;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.metaio.sdk.MetaioDebug;
import com.metaio.sdk.MetaioSurfaceView;
import com.metaio.sdk.SensorsComponentAndroid;
import com.metaio.sdk.jni.ERENDER_SYSTEM;
import com.metaio.sdk.jni.ESCREEN_ROTATION;
import com.metaio.sdk.jni.IGeometry;
import com.metaio.sdk.jni.IGeometryVector;
import com.metaio.sdk.jni.IMetaioSDKAndroid;
import com.metaio.sdk.jni.IMetaioSDKCallback;
import com.metaio.sdk.jni.MetaioSDK;
import com.metaio.sdk.jni.Rotation;
import com.metaio.sdk.jni.StringVector;
import com.metaio.sdk.jni.TrackingValuesVector;
import com.metaio.sdk.jni.Vector2di;
import com.metaio.sdk.jni.Vector3d;
import com.metaio.tools.Memory;
import com.metaio.tools.Screen;
import com.metaio.tools.SystemInfo;
import com.metaio.tools.io.AssetsManager;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ChangeLogModel;
import com.seemoreinteractive.seemoreinteractive.Model.ClientTriggers;
import com.seemoreinteractive.seemoreinteractive.Model.Marker;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.Triggers;
import com.seemoreinteractive.seemoreinteractive.Model.UserChangeLog;
import com.seemoreinteractive.seemoreinteractive.Model.Visual;
import com.seemoreinteractive.seemoreinteractive.Model.Visuals;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.calendar.NotifyService;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

/**
 * This is base activity to use metaio SDK. It creates metaio GLSurfaceView and
 * handle all its callbacks and lifecycle. 
 * 
 */ 

@TargetApi(Build.VERSION_CODES.HONEYCOMB_MR2)
public class ARDisplayActivity extends Activity implements MetaioSurfaceView.Callback, OnTouchListener 
{    
	  private static boolean nativeLibsLoaded;
	  private VideoView mVideoView;
	  protected int mDetectedCosID = -1,mDetectedTriggerId=0;
	  protected String mDetectedCosName = "";
	  protected String mMarkerType = "image";
	  
	  public Boolean alertErrorType = true;
	  public Context context = this;
	  AQuery aq1 = new AQuery(ARDisplayActivity.this);
	  FileTransaction file;
	  
	  ImageView trigger = null;
	  Boolean flag =false, mdownload=false,result =false,resetTriggerFlag = false;
	  String modelName = null,textureName = null,masterClientId = "",strFlagVideo ="";
	  SharedPreferences mPrefs;	
	  ProgressBar progressBarVideoNew;
	  RelativeLayout rlayCameraView;
	  ArrayList<String> offerImage,modelArray;
	  public int productId = 0;
	  int modelArrayCount =0;
	  private MetaioSDKCallbackHandler mCallbackHandler;
	  MediaController md;    	
	  ImageView close; 	 
	  Timer timer;
	  ViewPager viewPager;
	  RelativeLayout rlayout; 
	  String className = this.getClass().getSimpleName();	
	  static 
	  {     
		nativeLibsLoaded = IMetaioSDKAndroid.loadNativeLibs();
	  } 
	  
	  SessionManager session;
	 
	 
	 public static int captureImageRotate = 90;
     public static ArrayList<String> arrayClientMarkerIds = new ArrayList<String>();
	 public static ArrayList<String> arrayTriggerImage = new ArrayList<String>();
	 public static ArrayList<String> arrayTriggerClientIds  = new ArrayList<String>();
	 public static ArrayList<String> arraySessionClientImages  = new ArrayList<String>();
	
	 boolean flagForLogin = false;
	 JSONObject json;
	
	 private AnimationListener mAnimationListener;
	 private ViewFlipper mViewFlipper;
	 private final GestureDetector detector = new GestureDetector(new SwipeGestureDetector());
	 Boolean detectorFlag = false;
	 private Context mContext;
	
	
	 protected View mGUIView;
	 protected View mOfferView;
	 protected View mIntroView;
	// public Boolean ARFlag = false;
	 public Boolean visualFlag = false;
	 public static long startnow,endnow;
	
	protected int getGUILayout() 
	{
		return R.layout.activity_multiple_new;
	}
	public int getGUIIntroLayout() 
	{
		// Attaching layout to the activity
		return R.layout.flipper_layout_tab;
	}

	
	/**
	 * Sensor manager
	 */
	protected SensorsComponentAndroid mSensors;
	
	/**
	 * metaio SurfaceView
	 */
	protected MetaioSurfaceView mSurfaceView;

	/**
	 * GUI overlay, only valid in onStart and if a resource is provided in getGUILayout.
	 */

	
	/**
	 * metaio SDK object
	 */
	protected IMetaioSDKAndroid metaioSDK;
	
	/** 
	 * flag for the renderer
	 */
	protected boolean mRendererInitialized;
	
	/**
	 * Camera image resolution
	 */
	protected Vector2di mCameraResolution;
	private WakeLock mWakeLock;
	
	/**
	 * Provide resource for GUI overlay if required.
	 * <p> The resource is inflated into mGUIView which is added in onStart
	 * @return Resource ID of the GUI view
	 */
	//protected abstract int getGUILayout();
	
	/**
	 * Provide metaio SDK callback handler if desired. 
	 * @see IMetaioSDKCallback
	 * 
	 * @return Return metaio SDK callback handler
	 */
	
	/**
	 * Load contents to unifeye in this method, e.g. tracking data,
	 * geometries etc.
	 */
	//protected abstract void loadContents();
	
	/**
	 * Called when a geometry is touched.
	 * 
	 * @param geometry Geometry that is touched
	 */
	
	/**
	 * Start camera. Override this to change camera index or resolution
	 */
	protected void startCamera()
	{
		 try{
				// Select the back facing camera by default
				final int cameraIndex = SystemInfo.getCameraIndex(CameraInfo.CAMERA_FACING_BACK);
				mCameraResolution = metaioSDK.startCamera(cameraIndex, 320, 240);				
			}catch(Exception e){
				e.printStackTrace();			
				String errorMsg = className+" | startCamera | "+e.getMessage();
				Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);	
		   }
	}
	
	
	
	@Override
	public void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		
		final PowerManager pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
		this.mWakeLock = pm.newWakeLock(PowerManager.SCREEN_DIM_WAKE_LOCK, "My Tag");
        this.mWakeLock.acquire();
		MetaioDebug.log("ARViewActivity.onCreate()");
		metaioSDK = null;
		mSurfaceView = null;
		mRendererInitialized = false;
		//mPrefs = getSharedPreferences("mPREF", Context.MODE_PRIVATE);
		//mCallbackHandler = new MetaioSDKCallbackHandler();
		mContext = this;
		//ARFlag = true;
		
		try
		{
	        session = new SessionManager(context);
			//hideInstruction(v);
			if (!nativeLibsLoaded)
				throw new Exception("Unsupported platform, failed to load the native libs");
			
			File theDir = new File(Constants.Trigger_Location);
	        if (!theDir.exists()){
	              theDir.mkdir();
	         }
			// Create sensors component
			mSensors = new SensorsComponentAndroid( getApplicationContext());
			file = new FileTransaction();
			
			
			// Create Unifeye Mobile by passing Activity instance and application signature
			metaioSDK = MetaioSDK.CreateMetaioSDKAndroid(this, getResources().getString(R.string.metaioSDKSignature));
			metaioSDK.registerSensorsComponent(mSensors);
			
			// Inflate GUI view if provided
			final int layout = getGUILayout(); 
	        
			if (layout != 0){
				mGUIView = View.inflate(this, layout, null);
				mIntroView = null;
			}
			
			rlayout = (RelativeLayout) findViewById(R.id.camera_view);
			mVideoView = new VideoView(ARDisplayActivity.this);
			
			progressBarVideoNew = (ProgressBar) mGUIView.findViewById(R.id.progressBar);
			RelativeLayout.LayoutParams rlpProgressBar = (RelativeLayout.LayoutParams) progressBarVideoNew.getLayoutParams();
			rlpProgressBar.topMargin = (int) (0.026 * Common.sessionDeviceHeight);
			rlpProgressBar.rightMargin = (int) (0.042 * Common.sessionDeviceWidth);
			rlpProgressBar.bottomMargin = (int) (0.026 * Common.sessionDeviceHeight);
			rlpProgressBar.leftMargin = (int) (0.042 * Common.sessionDeviceWidth);		
			progressBarVideoNew.setLayoutParams(rlpProgressBar);
			
			
			Intent serviceIntent = new Intent(ARDisplayActivity.this,NotifyService.class);
		    PendingIntent pendingIntent = PendingIntent.getService(getApplicationContext(), 0, serviceIntent, 0);
		    AlarmManager alarmManager = (AlarmManager) getSystemService(ALARM_SERVICE);
		    alarmManager.cancel(pendingIntent);
		    LocationManager locationManager = (LocationManager)getSystemService(Context.LOCATION_SERVICE);
            locationManager.removeUpdates(pendingIntent);  //remove pendingIntent
		    pendingIntent.cancel();
		      		      
		    String screenName = "/";
		    String productIds = "";
		    String offerIds = "";
			Common.sendJsonWithAQuery(ARDisplayActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
		     
		}
		catch (Exception e)
		{
			e.printStackTrace();
			MetaioDebug.log(Log.ERROR, "ARViewActivity.onCreate: failed to create or intialize metaio SDK: "+e.getMessage());						
			String errorMsg = className+" | onCreate | failed to create or intialize metaio SDK: "+e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
        	finish();
		}
		
	}
	
	@Override
	protected void onStart() 
	{
		super.onStart();
		try 
		{
			mSurfaceView = null;			
			// Set empty content view
			setContentView(new FrameLayout(this));			
			// Start camera
			startCamera();
			if(Constants.ARFlag){
				xml_client_marker_ajax();
				Constants.ARFlag = false;
			}
			
			// Add Unifeye GL Surface view
			mSurfaceView = new MetaioSurfaceView(this);
			mSurfaceView.registerCallback(this);
			mSurfaceView.setKeepScreenOn(true);
			mSurfaceView.setOnTouchListener(this);

			MetaioDebug.log("ARViewActivity.onStart: addContentView(mMetaioSurfaceView)");
			FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT);
			addContentView(mSurfaceView, params);
			mSurfaceView.setZOrderMediaOverlay(true);
			
			// If GUI view is inflated, add it
	   		if (mGUIView != null)
	   		{
	   			addContentView(mGUIView, new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
		   		mGUIView.bringToFront();
		   		
		   		RelativeLayout rlFooter = (RelativeLayout) findViewById(R.id.footer);
		   		RelativeLayout.LayoutParams rlpFooter = (RelativeLayout.LayoutParams) rlFooter.getLayoutParams();
		   		rlpFooter.width = LayoutParams.MATCH_PARENT;
		   		rlpFooter.height = (int) (0.082 * Common.sessionDeviceHeight);
		   		//rlpFooter.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
		   		rlFooter.setLayoutParams(rlpFooter);
		   		
		        ImageView imgMyCloset = (ImageView) findViewById(R.id.imgvGoToMyCloset);
		        RelativeLayout.LayoutParams rlInstructionMyCloset = (RelativeLayout.LayoutParams) imgMyCloset.getLayoutParams();
		        rlInstructionMyCloset.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlInstructionMyCloset.height = (int) (0.082 * Common.sessionDeviceHeight);
		        rlInstructionMyCloset.leftMargin = (int) (0.067 * Common.sessionDeviceWidth);
		        imgMyCloset.setLayoutParams(rlInstructionMyCloset);
		        
		        ImageView imgInfoIcon = (ImageView) findViewById(R.id.imgvInfoIcon);
		        RelativeLayout.LayoutParams rlInstructionInfoIcon = (RelativeLayout.LayoutParams) imgInfoIcon.getLayoutParams();
		        rlInstructionInfoIcon.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlInstructionInfoIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
		        imgInfoIcon.setLayoutParams(rlInstructionInfoIcon);
		        
		        ImageView imgMyOffers = (ImageView) findViewById(R.id.imgvMyOffers);
		        RelativeLayout.LayoutParams rlInstructionMyOffers = (RelativeLayout.LayoutParams) imgMyOffers.getLayoutParams();
		        rlInstructionMyOffers.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlInstructionMyOffers.height = (int) (0.082 * Common.sessionDeviceHeight);
		        rlInstructionMyOffers.leftMargin = (int) (0.067 * Common.sessionDeviceWidth);
		        imgMyOffers.setLayoutParams(rlInstructionMyOffers);

		        ImageView imgvTopMenuInfo = (ImageView) findViewById(R.id.imgvTopMenuInfo);
		        RelativeLayout.LayoutParams rlImgTopMenuInfo = (RelativeLayout.LayoutParams) imgvTopMenuInfo.getLayoutParams();
		        rlImgTopMenuInfo.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlImgTopMenuInfo.height = (int) (0.082 * Common.sessionDeviceHeight);
		        imgvTopMenuInfo.setLayoutParams(rlImgTopMenuInfo);

		        ImageView imgvShopNow = (ImageView) findViewById(R.id.imgvShopNow);
		        RelativeLayout.LayoutParams rlImgShopNow = (RelativeLayout.LayoutParams) imgvShopNow.getLayoutParams();
		        rlImgShopNow.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlImgShopNow.height = (int) (0.082 * Common.sessionDeviceHeight);
		        rlImgShopNow.leftMargin = (int) (0.0334 * Common.sessionDeviceWidth);
		        imgvShopNow.setLayoutParams(rlImgShopNow);
		        
		        ImageView imgvShopparVision = (ImageView) findViewById(R.id.imgvShopparVision);
		        RelativeLayout.LayoutParams rlImgShopparVision = (RelativeLayout.LayoutParams) imgvShopparVision.getLayoutParams();
		        rlImgShopparVision.width = (int) (0.1334 * Common.sessionDeviceWidth);
		        rlImgShopparVision.height = (int) (0.082 * Common.sessionDeviceHeight);
		        rlImgShopparVision.rightMargin = (int) (0.0334 * Common.sessionDeviceWidth);
		        imgvShopparVision.setLayoutParams(rlImgShopparVision);
		        
				/*SlidingDrawer slidingDrawer1 = (SlidingDrawer) findViewById(R.id.slidingDrawer1);
				RelativeLayout.LayoutParams rlSlidingDrawer1 = (RelativeLayout.LayoutParams) slidingDrawer1.getLayoutParams();
				rlSlidingDrawer1.width = LayoutParams.MATCH_PARENT;
				rlSlidingDrawer1.height = LayoutParams.MATCH_PARENT;
		        rlSlidingDrawer1.leftMargin = (int) (0.1834 * Common.sessionDeviceWidth);
				slidingDrawer1.setLayoutParams(rlSlidingDrawer1);*/
	   		}
	   		//FileTransaction file = new FileTransaction();
			Triggers triggers = file.getClientTriggers();
        	if(triggers.size() == 0){
        		int introLayout =getGUIIntroLayout();
        		if (introLayout != 0)
        		{
        			
        			mIntroView = View.inflate(this, introLayout, null);
    				mViewFlipper = (ViewFlipper) mIntroView.findViewById(R.id.view_flipper);

    				
    			    String screenName = "/downloads";
    			    String productIds = "";
    			    String offerIds = "";
    				Common.sendJsonWithAQuery(ARDisplayActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);
    			     
    				ImageView imgvReadyToSeemore = (ImageView) mIntroView.findViewById(R.id.imgvReadyToSeemore);
    				RelativeLayout.LayoutParams rlpReadyToSeemore = (RelativeLayout.LayoutParams) imgvReadyToSeemore.getLayoutParams();
    				rlpReadyToSeemore.width = LayoutParams.MATCH_PARENT;
    				rlpReadyToSeemore.height = (int) (0.205 * Common.sessionDeviceHeight);
    				rlpReadyToSeemore.topMargin = (int) (0.615 * Common.sessionDeviceHeight);
    				imgvReadyToSeemore.setLayoutParams(rlpReadyToSeemore);
    			    imgvReadyToSeemore.setOnClickListener(new OnClickListener() {					
    					@Override
    					public void onClick(View arg0) {
    						try{
	    						mIntroView.setVisibility(View.GONE);
	    						if(!session.isLoggedIn() && flagForLogin==false){
	    							flagForLogin = true;
	    							new Common().getLoginDialog(ARDisplayActivity.this, ARDisplayActivity.class, "ARDisplay", new ArrayList<String>());
	    						}
    						}catch(Exception e){
    							e.printStackTrace();
    							String errorMsg = className+" | onStart | Seemore button click :" +e.getMessage();
    				        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
    						}
    					}
    				});
    			    
    			}
        		if (mIntroView != null)
    	   		{	
        			new MainActivity();
					if(!MainActivity.ResetFlag){
	    				addContentView(mIntroView, new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
	    		   		mIntroView.bringToFront();
	    		   		mViewFlipper.setVisibility(View.VISIBLE);
	    		   	    MainActivity.mendnow = android.os.SystemClock.uptimeMillis();
						//Log.e("MYTAG main ", "Excution time: "+(MainActivity.mendnow-MainActivity.mstartnow)/1000+" s");
						
						MainActivity.mstartnow = android.os.SystemClock.uptimeMillis();
						//Log.e("MYTAG main intro ", "Excution time: "+(MainActivity.mendnow-MainActivity.mstartnow)/1000+" s");
						
						mViewFlipper.setOnTouchListener(new OnTouchListener() {
							@Override
							public boolean onTouch(final View view, final MotionEvent event) {
								detector.onTouchEvent(event);
									return true;
							}
						});
						timer = new Timer();
						
						//animation listener
						mAnimationListener = new Animation.AnimationListener() {
							@Override
							public void onAnimationStart(Animation animation) {
								//animation started event
							}
							@Override
							public void onAnimationRepeat(Animation animation) {
							}
							@Override
							public void onAnimationEnd(Animation animation) {
							}
						};
	    	   		}else{
	    	   			mIntroView = null;
	    	   		}
    	   		}
	    	}else{
	    		mIntroView = null;
				
	    	}
			Tracker easyTracker = EasyTracker.getInstance(this);
			easyTracker.set(Fields.SCREEN_NAME, "/");
			easyTracker.send(MapBuilder
			    .createAppView()
			    .build()
			);
			 String[] segments = new String[1];
			 segments[0] = "Camera Screen"; 
			 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
	  
		} 
		catch (Exception e) 
		{
			e.printStackTrace();
			MetaioDebug.log(Log.ERROR, "Error creating views: "+e.getMessage());			
			MetaioDebug.printStackTrace(Log.ERROR, e);
			String errorMsg = className+" | onStart | creating views :" +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}

	@Override
	protected void onPause() 
	{
		try{
			super.onPause();
			MetaioDebug.log("ARViewActivity.onPause()");		
			metaioFlag = false;
	
			if (mWakeLock != null)
				mWakeLock.release();
			
			RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
	    	rlayCameraView1.setBackgroundResource(0);
			findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
			findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
			findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
			findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
			findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
			Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(ARDisplayActivity.this);			
			if(appInBackgrnd){
				 Common.isAppBackgrnd = true;
			}
		
			
		}catch (Exception e) {		
			e.printStackTrace();			
			String errorMsg = className+" | onPause | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}
   Boolean AlarmFlag = true;
	@Override
	public void onBackPressed() 
	{
		try{		
			if(AlarmFlag){
				
				int minutes = 1;
			    AlarmManager am = (AlarmManager) getSystemService(ALARM_SERVICE);
			    Intent i = new Intent(this, NotifyService.class);
			    PendingIntent pi = PendingIntent.getService(this, 0, i, 0);		    
			    am.cancel(pi);
			    // by my own convention, minutes <= 0 means notifications are disabled
			    if (minutes > 0) {
			        am.setInexactRepeating(AlarmManager.ELAPSED_REALTIME_WAKEUP,
			            SystemClock.elapsedRealtime() + 1000,
			            1000, pi);  
		       }
			    AlarmFlag = false;
			}
		    new Common().deleteFiles(Constants.Model_Location);
		
		    //this.moveTaskToBack(true);
		    getIntent().setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
		    // this.finish();
		 
		
			 ARDisplayActivity.this.finish();//try activityname.finish instead of this
			 Intent intent = new Intent(Intent.ACTION_MAIN);
			 intent.addCategory(Intent.CATEGORY_HOME);
			 intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
			 startActivity(intent);
		
		 super.onBackPressed();
		}catch (Exception e) 
		{		
			e.printStackTrace();
			//Toast.makeText(getApplicationContext(), "Error: ARDisplayActivity onBackPressed", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onBackPressed | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		
		}
			
	}
	@Override
	protected void onResume() 
	{
		try{
			super.onResume();
			MetaioDebug.log("ARViewActivity.onResume()");
	
			if (mWakeLock != null)
				mWakeLock.acquire();

			metaioFlag = true;
			// make sure to resume the OpenGL surface
			/*if (mSurfaceView != null)
				mSurfaceView.onResume();*/
			trackingFlag = true;
			trackingXMlFlag = true;
			RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
	    	rlayCameraView1.setBackgroundResource(R.drawable.camerafocus);
			//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
			//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
			findViewById(R.id.imgvGoToMyCloset).setVisibility(View.VISIBLE);
			findViewById(R.id.imgvMyOffers).setVisibility(View.VISIBLE);
			findViewById(R.id.imgvInfoIcon).setVisibility(View.VISIBLE);
			findViewById(R.id.imgvShopparVision).setVisibility(View.VISIBLE);
			findViewById(R.id.imgvShopNow).setVisibility(View.VISIBLE);
			//mGUIView.setVisibility(View.VISIBLE);
			metaioSDK.resume();
			//metaioSDK.resumeTracking();
			//metaioSDK.setFreezeTracking(false);
			
			if(Common.isAppBackgrnd){
				changeLogResultFromServer();			
				Common.isAppBackgrnd = false;
			}
			
		}catch (Exception e) 
		{		
			e.printStackTrace();			
			String errorMsg = className+" | onResume | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}

	@Override
	protected void onStop() 
	{
		try{
			super.onStop();	
		    EasyTracker.getInstance(this).activityStop(this);  // Add this method.	
		    QuantcastClient.activityStop();
			MetaioDebug.log("ARViewActivity.onStop()");
			
			if (metaioSDK != null)
			{
				// Disable the camera
				metaioSDK.stopCamera();
			}
			
			if (mSurfaceView != null)
			{
				ViewGroup v = (ViewGroup) findViewById(android.R.id.content);
				v.removeAllViews();
			}
			
			
			System.runFinalization();
			System.gc();
			
		}catch (Exception e){	
			e.printStackTrace();
			//Toast.makeText(getApplicationContext(), "Error: ARDisplayActivity onStop", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onStop | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		
		}
	} 

	@Override
	protected void onDestroy() 
	{
		try{
			super.onDestroy();
			
			MetaioDebug.log("ARViewActivity.onDestroy");
			this.mWakeLock.release();
			if (metaioSDK != null) 
			{
				metaioSDK.delete();
				metaioSDK = null;
			}
			
			MetaioDebug.log("ARViewActivity.onDestroy releasing sensors");
			if (mSensors != null)
			{
				mSensors.registerCallback(null);
				mSensors.release();
				mSensors.delete();
				mSensors = null;
			}
			
			Memory.unbindViews(findViewById(android.R.id.content));
			
			System.runFinalization();
			System.gc();
			
		}catch (Exception e){		
			e.printStackTrace();			
			String errorMsg = className+" | onDestroy | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		
		}
	}
	
	
	@Override
	public void onConfigurationChanged(Configuration newConfig) 
	{
		try{
			super.onConfigurationChanged(newConfig);		
			final ESCREEN_ROTATION rotation = Screen.getRotation(this);		
			metaioSDK.setScreenRotation(rotation);		
			MetaioDebug.log("onConfigurationChanged: "+rotation);
		}catch (Exception e){			
			e.printStackTrace();
			String errorMsg = className+" | onConfigurationChanged | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}
	

	@Override
	public boolean onTouch(View v, MotionEvent event)
	{
		try{
			if (event.getAction() == MotionEvent.ACTION_UP) 
			{
				try
				{				
					final int x = (int) event.getX();
					final int y = (int) event.getY();					
					// ask the SDK if a geometry has been hit
					IGeometry geometry = metaioSDK.getGeometryFromScreenCoordinates(x, y, true);
					if (geometry != null) 
					{
						MetaioDebug.log("ARViewActivity geometry found: "+geometry);
						onGeometryTouched(geometry);
						hideInstruction(v);
					}					
				}
				catch (Exception e){
					e.printStackTrace();
					MetaioDebug.log(Log.ERROR, "onTouch: "+e.getMessage());
					String errorMsg = className+" | onTouch | ACTION_UP | " +e.getMessage();
		        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				}			
			}
		}catch (Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onTouch  | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);		
		}
		return true;
	}
	
	/**
	 * This function will be called, right after the OpenGL context was created.
	 * All calls to metaio SDK must be done after this callback has been
	 * triggered.
	 */
	@Override
	public void onSurfaceCreated() 
	{
		MetaioDebug.log("ARViewActivity.onSurfaceCreated: GL thread: "+Thread.currentThread().getId());
		try
		{
			// initialized the renderer
			if(!mRendererInitialized)
			{
				metaioSDK.initializeRenderer(mSurfaceView.getWidth(), mSurfaceView.getHeight(),
						Screen.getRotation(this), ERENDER_SYSTEM.ERENDER_SYSTEM_OPENGL_ES_2_0 );
				mRendererInitialized = true;
				
				// Add loadContent to the event queue to allow rendering to start 
				/*mSurfaceView.queueEvent(new Runnable() 
				{
					@Override
					public void run() {
						
					}
				});*/
			}
			else
			{
				MetaioDebug.log("ARViewActivity.onSurfaceCreated: Reloading textures...");
				metaioSDK.reloadTextures();
			}
			
			// connect the audio callbacks
			MetaioDebug.log("ARViewActivity.onSurfaceCreated: Registering audio renderer...");			
			metaioSDK.registerAudioCallback( mSurfaceView.getAudioRenderer() );
			mCallbackHandler = new MetaioSDKCallbackHandler();
			metaioSDK.registerCallback(getMetaioSDKCallbackHandler());

			MetaioDebug.log("ARViewActivity.onSurfaceCreated");

		}
		catch (final Exception e)
		{
			MetaioDebug.log(Log.ERROR, "ARViewActivity.onSurfaceCreated: "+e.getMessage()+" "+e.getStackTrace());
			runOnUiThread(new Runnable() {
				@Override
				public void run() {
					Toast.makeText(getApplicationContext(), "Error: ARDisplayActivity onSurfaceCreated", Toast.LENGTH_LONG).show();
					e.printStackTrace();
					String errorMsg = className+" | onSurfaceCreated  | " +e.getMessage();
		        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				}
			});
		}
 	}

	@Override
	public void onSurfaceDestroyed() 
	{
		try{
			MetaioDebug.log("ARViewActivity.onSurfaceDestroyed(){");
			mSurfaceView = null;			
			metaioSDK.registerAudioCallback(null);
		}catch (Exception e){								
			e.printStackTrace();
			String errorMsg = className+" | onSurfaceDestroyed  | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			
		}
	}
	RelativeLayout rlInstruction1;
	@Override
	public void onSurfaceChanged(final int width, final int height) 
	{
		try{	
				MetaioDebug.log("ARViewActivity.onSurfaceChanged: "+width+", "+height);		
				// resize renderer viewport
				metaioSDK.resizeRenderer(width, height);
			
				runOnUiThread(new Runnable() {
					@Override
					public void run() {
						try{
						if(strFlagVideo.equals("")){
							mVideoView = new VideoView(ARDisplayActivity.this);
							rlayout = (RelativeLayout) findViewById(R.id.camera_view);
						}
							close = (ImageView)findViewById(R.id.close);
							//md = new MediaController(ARDisplayActivity.this);					
						    //progressBarVideo = (ProgressBar) mGUIView.findViewById(R.id.progressBar);
			
						    rlInstruction1 = (RelativeLayout) findViewById(R.id.rlInstruction);
						} catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | onSurfaceChanged  |  runOnUiThread | " +e.getMessage();
				        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
						}
					}
				});
				
				// Session class instance		
				Thread t = new Thread(new Runnable() {
					@Override
					public void run() {
						runOnUiThread(new Runnable() {
							@Override
							public void run() {		
								try {
									ImageView imgGoToCloset = (ImageView) mGUIView.findViewById(R.id.imgvGoToMyCloset);
									ImageView imgMyOffers = (ImageView) mGUIView.findViewById(R.id.imgvMyOffers);
									ImageView imgInfoIcon = (ImageView) mGUIView.findViewById(R.id.imgvInfoIcon);
									ImageView imgvTopMenuInfo = (ImageView) mGUIView.findViewById(R.id.imgvTopMenuInfo);
									ImageView imgvShopNow = (ImageView) mGUIView.findViewById(R.id.imgvShopNow);
									ImageView imgvShopparVision = (ImageView) mGUIView.findViewById(R.id.imgvShopparVision);
									
									if(imgInfoIcon!=null){
										imgInfoIcon.setOnClickListener(new OnClickListener() {
											@Override
											public void onClick(View arg0) {
												// TODO Auto-generated method stub
												try{
													metaioSDK.requestScreenshot(Constants.Trigger_Location+"shopparvision.png");
													 takeScreenShot();
												} catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgvShopparVision click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
												}
											}
										});
									}
									if(imgvTopMenuInfo!=null){
										imgvTopMenuInfo.setOnClickListener(new OnClickListener() {
											@Override
											public void onClick(View arg0) {
												try{
													Intent intent = new Intent(getApplicationContext(), TopMenuInfo.class);
													int requestCode = 1;
													intent.putExtra("activityThis", context.getPackageName()+".ARDisplayActivity");									
													startActivityForResult(intent, requestCode);
													overridePendingTransition(R.anim.slide_in_from_top, R.anim.slide_out_to_top);
													/*if (slidingDrawer1.isOpened()){
										               slidingDrawer1.animateClose();
										            } 
										            else{
										               slidingDrawer1.animateOpen();    
										            }*/
												} catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgvTopMenuInfo click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
												}
											}
										});
									}
									if(imgvShopNow != null){
										imgvShopNow.setOnClickListener(new OnClickListener() {
											@Override
											public void onClick(View arg0) {
												try{
													if(Common.isNetworkAvailable(ARDisplayActivity.this))
													{
														if(!session.isLoggedIn()){
															new Common().getLoginDialog(ARDisplayActivity.this, Products.class, "Products", new ArrayList<String>());
														} else {
															Intent intent = new Intent(getApplicationContext(), Products.class);
															startActivityForResult(intent, 1);
															overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);						
														}
													}else{
														 new Common().instructionBox(ARDisplayActivity.this,R.string.title_case7,R.string.instruction_case7);
													  }											
													/*
													
													Intent intent = new Intent(ARDisplayActivity.this, Products.class);	
													startActivity(intent);
													overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);*/
												} catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgvShopNow click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
												}
											}
										});
									}							
									if(imgGoToCloset != null){
										imgGoToCloset.setOnClickListener(new OnClickListener() {							
											@Override
											public void onClick(View v) {
												try{
													if(Common.isNetworkAvailable(ARDisplayActivity.this)){
														new Common().getLoginDialog(ARDisplayActivity.this, Closet.class, "Closet", new ArrayList<String>());
													}else{
														Handler mHandler=new Handler();
											   			Runnable mRunnable = new Runnable() {
											   	            @Override
											   	            public void run() {
											   	            	if(session.isLoggedIn())
											   	            		new Common().getLoginDialog(ARDisplayActivity.this, Closet.class, "Closet", new ArrayList<String>());
											   	            	else
											   	            		new Common().instructionBox(ARDisplayActivity.this, R.string.title_case7, R.string.instruction_case7);
											   	            }
											   	        };
											   			mHandler.postDelayed(mRunnable,1000);   
													}
												}catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgGoToCloset click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
													
												}
											}
										});
									}
									if(imgMyOffers != null){
										imgMyOffers.setOnClickListener(new OnClickListener() {							
											@Override
											public void onClick(View v) {
												try{
													//hideInstruction(v);
													if(Common.isNetworkAvailable(ARDisplayActivity.this)){
														new Common().getLoginDialog(ARDisplayActivity.this, MyOffers.class, "MyOffers", new ArrayList<String>());
													}else{												
														Handler mHandler=new Handler();
											   			Runnable mRunnable = new Runnable() {
											   	            @Override
											   	            public void run() {
											   	            	if(session.isLoggedIn())
											   	            		new Common().getLoginDialog(ARDisplayActivity.this, MyOffers.class, "MyOffers", new ArrayList<String>());
											   	            	else	
											   	            		new Common().instructionBox(ARDisplayActivity.this, R.string.title_case7, R.string.instruction_case7);
											   	            }
											   	         };
											   			mHandler.postDelayed(mRunnable,1000);   										
													}
												}catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgMyOffers click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
												}
											}
										});
									}
									if(imgvShopparVision != null){
										imgvShopparVision.setOnClickListener(new OnClickListener() {							
											@Override
											public void onClick(View v) {
												try{
													Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
													int requestCode = 1;
													intent.putExtra("activityThis", context.getPackageName()+".ARDisplayActivity");									
													startActivityForResult(intent, requestCode);
													overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
												}catch(Exception e){
													e.printStackTrace();
													String errorMsg = className+" | onSurfaceChanged  |  imgInfoIcon click :  " +e.getMessage();
										        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
												}
											}
										});
									}
								} catch (Exception e){
									e.printStackTrace();
									String errorMsg = className+" | onSurfaceChanged  |  thread :  " +e.getMessage();
						        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}						
							}                     					
						});
					}
				});
		try {
			//Thread.sleep(1000);
			t.start();
		} catch (Exception e) {
			e.printStackTrace();			
			String errorMsg = className+" | onSurfaceChanged  | in thread sleep:  " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	
		}catch (Exception e){			
			e.printStackTrace();
			String errorMsg = className+" | onSurfaceChanged  |   " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		

	}
	public void takeScreenShot(){
		Intent intent = new Intent(getApplicationContext(), ShopparVision.class);
		startActivity(intent);
		overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
	}
	
	@Override
	public void onLowMemory() {
		try{
			MetaioDebug.log(Log.ERROR, "Low memory");
			MetaioDebug.logMemory(getApplicationContext());
		}catch (Exception e){
			//Toast.makeText(getApplicationContext(), "Error: ARDisplayActivity Low memory", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onLowMemory   | " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}

	int productIdForRS = 0;
	String offerIdForRS = "";
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{			
			 super.onActivityResult(requestCode, resultCode, data);				
			 String screenName = "/";
		     String productIds = "";
		     String offerIds = "";
			 Common.sendJsonWithAQuery(ARDisplayActivity.this, ""+Common.sessionIdForUserLoggedIn, screenName, productIds, offerIds);;
			 if(resultCode==2){					
					if(data != null){
						try{
							 String COS_name = data.getStringExtra("triggerId");
							 int cosID = Integer.parseInt(data.getStringExtra("cosId")); 
							 if(data.getStringExtra("productId").equals("null")){
								 productIdForRS = 0;
							 } else if(data.getStringExtra("productId").startsWith("Video_")){
								 productIdForRS = 0;
							 } else {
								 productIdForRS = Integer.parseInt(data.getStringExtra("productId"));
							 }
							 if(data.getStringExtra("offerId").equals("null")){
								 offerIdForRS = "";
							 } else if(data.getStringExtra("offerId").startsWith("Video_")){
								 offerIdForRS = "";
							 } else {
								 offerIdForRS = data.getStringExtra("offerId");
							 }
							 mDetectedCosID = 0;
							 strFlagVideo = "recentlyScannedForActivity";
							 markerDetected(cosID, COS_name, "recentlyScannedForActivity", productIdForRS, offerIdForRS);
							/* if(productId!=0 && offerId!=0){
								 mDetectedCosID = 0;
								 markerDetected(cosID, COS_name, "recentlyScannedForActivity", productId);
							 }*/
						} catch(Exception e){
							e.printStackTrace();
						}
					}
				} else if(requestCode == 1){
					if(!Common.isNetworkAvailable(ARDisplayActivity.this)){
						if(data != null){
							 String activity=data.getStringExtra("activity");						 
							 if(activity !=null && activity.equals("menu")){
								 Runnable mRunnable;
									Handler mHandler=new Handler();
									mRunnable=new Runnable() {
							            @Override
							            public void run() {            	
							            	new Common().instructionBox(ARDisplayActivity.this, R.string.title_case7, R.string.instruction_case7);
							            }
							        };
									mHandler.postDelayed(mRunnable,1000);
								 
							 }
						 }
					}
			} 
		   
		}catch (Exception e){
			e.printStackTrace();			
			String errorMsg = className+" | onActivityResult |   " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}
	
	boolean metaioFlag = true;
	boolean trackingFlag = true;
	boolean trackingXMlFlag = true;
	
	
	@Override
	public void onDrawFrame() 
	{
		try{
			//Log.i("ondraw","trackingXMlFlag"+trackingXMlFlag+"trackingFlag"+trackingFlag);
			if (mRendererInitialized)
				metaioSDK.render();
		
			if (metaioSDK != null)
			{
				//long posesSize = 0;
				
				// get all detected poses/targets
				/*if(trackingXMlFlag){					
					poses = metaioSDK.getTrackingValues();
					posesSize =  poses.size();
				}else{
					posesSize = 0;
					//posesSize =  poses.size();
				}*/
				
				 if(trackingXMlFlag){
					 
					 final TrackingValuesVector poses = metaioSDK.getTrackingValues();			
					// Log.e("poses",""+poses.size());
					 if (poses.size() > 0) {
						 runOnUiThread(new Runnable() {
						@Override
						public void run() {		
							try{
								//og.e("poses a",""+poses);
								rlayCameraView = (RelativeLayout) findViewById(R.id.camera_view);
								if(rlayCameraView!=null){
									rlayCameraView.setBackgroundResource(0);
									//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
									//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
									if(poses.get(0).getCoordinateSystemID() != mDetectedCosID ){
										progressBarVideoNew.setVisibility(View.VISIBLE);
									}
								}
								
							} catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | onDrawFrame | poses | runOnUiThread | " +e.getMessage();
					        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
							}
						}
					});
					  String COS_name = metaioSDK.getCoordinateSystemName(poses.get(0).getCoordinateSystemID());
					 if(trackingFlag){	
							if(mMarkerType.equals("image")){	
								markerDetected(poses.get(0).getCoordinateSystemID(), COS_name, "", 0, "");					
							}else{
								metaioMarkerDetected(poses.get(0).getCoordinateSystemID());
							}
					 }else{
						 runOnUiThread(new Runnable() {
								@Override						
								public void run() {	
									try{								
										progressBarVideoNew.setVisibility(View.INVISIBLE);
									}catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | onDrawFrame | runOnUiThread |   progressBarVideoNew |" +e.getMessage();
							        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
									}
								}
								});
					 }						
				} else {
					if(metaioFlag==true){
						mDetectedCosID = -1;
						runOnUiThread(new Runnable() {
							@Override						
							public void run() {	
								try{
									RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);									
									if(rlInstruction != null){
										if (rlInstruction.getVisibility() == View.VISIBLE) {
											if(Common.isNetworkAvailable(ARDisplayActivity.this))
												rlInstruction.setVisibility(View.GONE);										    
										} 
									}
									if(mVideoView.isPlaying()){
				                    	RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
				                    	rlayCameraView1.setBackgroundResource(0);
										//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
										//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
									} else {
										rlayCameraView = (RelativeLayout) findViewById(R.id.camera_view);
										if(rlayCameraView != null){
											rlayCameraView.setBackgroundResource(R.drawable.camerafocus);
										}
										//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
										//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
										findViewById(R.id.imgvGoToMyCloset).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvMyOffers).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvInfoIcon).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvShopparVision).setVisibility(View.VISIBLE);
										findViewById(R.id.imgvShopNow).setVisibility(View.VISIBLE);
									}
								} catch (Exception e){
									e.printStackTrace();
									String errorMsg = className+" | onDrawFrame | runOnUiThread |   " +e.getMessage();
						        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}					
							}
						});
					}
				}			 
				 }else{
						if(metaioFlag==true){
							mDetectedCosID = -1;
							runOnUiThread(new Runnable() {
								@Override						
								public void run() {	
									try{
										RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);									
										if(rlInstruction != null){
											if (rlInstruction.getVisibility() == View.VISIBLE) {
												if(Common.isNetworkAvailable(ARDisplayActivity.this))
													rlInstruction.setVisibility(View.GONE);										    
											} 
										}
										if(mVideoView.isPlaying()){
					                    	RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
					                    	rlayCameraView1.setBackgroundResource(0);
											//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
											//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
										} else {
											rlayCameraView = (RelativeLayout) findViewById(R.id.camera_view);
											if(rlayCameraView != null){
												rlayCameraView.setBackgroundResource(R.drawable.camerafocus);
											}
											//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
											//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvGoToMyCloset).setVisibility(View.VISIBLE);
											findViewById(R.id.imgvMyOffers).setVisibility(View.VISIBLE);
											findViewById(R.id.imgvInfoIcon).setVisibility(View.VISIBLE);
											findViewById(R.id.imgvShopparVision).setVisibility(View.VISIBLE);
											findViewById(R.id.imgvShopNow).setVisibility(View.VISIBLE);
										}
									} catch (Exception e){
										e.printStackTrace();
										String errorMsg = className+" | onDrawFrame | runOnUiThread |   " +e.getMessage();
							        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
									}					
								}
							});
						}
					
				 }
			}
		}catch (final Exception e){
			runOnUiThread(new Runnable() {
				@Override
				public void run() {					
					e.printStackTrace();
					String errorMsg = className+" | onDrawFrame |    " +e.getMessage();
		        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				}
			});
		}
	}
	
	private void metaioMarkerDetected(int coordinateSystemID) {
		if (mDetectedCosID == -1) {
			unloadLoadedModels();
		}

		switch (coordinateSystemID) {
			case 6: {
				// Load 3D Bag
				String metaioManModel = AssetsManager
						.getAssetPath("bagInterior_1.md2");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setScale(new Vector3d(4, 4, 4));
						mModel.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel.setCoordinateSystemID(6);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("bag_1.md2");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setScale(new Vector3d(4, 4, 4));
						mModel1.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel1.setCoordinateSystemID(6);
					}
				}
				String metaioManModel2 = AssetsManager.getAssetPath("strap.md2");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setScale(new Vector3d(4, 4, 4));
						mModel2.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel2.setCoordinateSystemID(6);
					}
				}
				break;
	
			}
	
			case 8: {
	
				String metaioManModel = AssetsManager
						.getAssetPath("bagInterior_2.md2");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setScale(new Vector3d(4, 4, 4));
						mModel.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel.setCoordinateSystemID(8);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("bag_2.md2");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setScale(new Vector3d(4, 4, 4));
						mModel1.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel1.setCoordinateSystemID(8);
					}
				}
				String metaioManModel2 = AssetsManager.getAssetPath("strap.md2");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setScale(new Vector3d(4, 4, 4));
						mModel2.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel2.setCoordinateSystemID(8);
					}
				}
	
				break;
	
			}
	
			case 9: {
	
				String metaioManModel = AssetsManager
						.getAssetPath("bagInterior_3.md2");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setScale(new Vector3d(4, 4, 4));
						mModel.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel.setCoordinateSystemID(9);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("bag_3.md2");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setScale(new Vector3d(4, 4, 4));
						mModel1.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel1.setCoordinateSystemID(9);
					}
				}
				String metaioManModel2 = AssetsManager.getAssetPath("strap.md2");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel.setTexture(AssetsManager.getAssetPath("bag_1.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setScale(new Vector3d(4, 4, 4));
						mModel2.setRotation(new Rotation((float) 1.5, 0,
								(float) 1.57));
						mModel2.setCoordinateSystemID(9);
					}
				}
				break;
	
			}
	
			case 4: {
	
				// Load Jewel's Ring
				String metaioManModel = AssetsManager.getAssetPath("jewels.md2");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("redRing.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setScale(new Vector3d(4, 4, 4));
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setCoordinateSystemID(4);
					}
				}
	
				String metaioManModel1 = AssetsManager.getAssetPath("redRing.md2");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("redRing.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setScale(new Vector3d(4, 4, 4));
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setCoordinateSystemID(4);
					}
				}
				break;
	
			}
	
			case 5: {
	
				// Load Walk
	
				final String moviePath = AssetsManager.getAssetPath("walk.3g2");
				if (moviePath != null) {
					IGeometry mMoviePlane = metaioSDK.createGeometryFromMovie(
							moviePath, true);
					if (mMoviePlane != null) {
						mMoviePlane.setScale(2.0f);
						// mMoviePlane.setRotation(new Rotation(0f, 0f,
						// (float)-Math.PI/2));
						mMoviePlane.setVisible(false);
						mMoviePlane.startMovieTexture(true);
						MetaioDebug.log("Loaded geometry " + moviePath);
					} else {
						MetaioDebug.log(Log.ERROR, "Error loading geometry: "
								+ moviePath);
					}
				}
	
				break;
	
			}
	
			case 1: {
	
				// Load 3D Car
				String metaioManModel = AssetsManager.getAssetPath("mksTires.obj");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBlue.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(1);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("mksBlue.obj");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBlue.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(1);
					}
				}
				break;
	
			}
	
			case 7: {
	
				// Load 3D Car
				String metaioManModel = AssetsManager.getAssetPath("mksTires.obj");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("mksRed.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(7);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("mksRed.obj");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBlue.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(7);
					}
				}
	
				break;
	
			}
	
			case 2: {
	
				String metaioManModel = AssetsManager.getAssetPath("mksTires.obj");
	
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("mksGreen.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(2);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("mksGreen.obj");
	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBlue.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(2);
					}
				}
	
				break;
	
			}
	
			case 3: {	
				// Load 3D Car
				String metaioManModel = AssetsManager.getAssetPath("mksTires.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBase.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(3);
					}
				}	
				String metaioManModel1 = AssetsManager.getAssetPath("mksBase.obj");	
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel.setTexture(AssetsManager.getAssetPath("mksBlue.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(3);
					}
				}	
				break;	
			}
	
			case 10: {	
				// Load 3D Car
				String metaioManModel = AssetsManager.getAssetPath("body.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("body.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel.setScale(new Vector3d(2, 2, 2));
						mModel.setCoordinateSystemID(10);
					}
				}
				String metaioManModel1 = AssetsManager.getAssetPath("mirrors.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("mirrors.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel1.setScale(new Vector3d(2, 2, 2));
						mModel1.setCoordinateSystemID(10);
					}
				}
				String metaioManModel2 = AssetsManager.getAssetPath("wipers.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("wipers.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel2.setScale(new Vector3d(2, 2, 2));
						mModel2.setCoordinateSystemID(10);
					}
				}
				String metaioManModel3 = AssetsManager.getAssetPath("tires.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					// mModel3.setTexture(AssetsManager.getAssetPath("tires.png"));
					if (mModel3 != null) {
						// Set geometry properties
						mModel3.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						mModel3.setScale(new Vector3d(2, 2, 2));
						mModel3.setCoordinateSystemID(10);
					}
				}	
				break;	
			}
	
			case 11: {	
				// Load 3D Blue Ring	
				String metaioManModel = AssetsManager
						.getAssetPath("blueRing_base_0208.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("blueRing_base_0208.png"));
					if (mModel != null) {
						// Set geometry properties
	
						// mModel.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel.setTranslation(new Vector3d(0, 0, -10));
						mModel.setScale(new Vector3d(2, 2, 2));
						mModel.setCoordinateSystemID(11);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("blueRing_diamonds_0208.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("blueRing_diamonds_0208.png"));
					if (mModel1 != null) {
						// Set geometry properties
						// mModel1.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel1.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel1.setTranslation(new Vector3d(0, 0, -10));
						mModel1.setScale(new Vector3d(2, 2, 2));
						mModel1.setCoordinateSystemID(11);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("blueRing_frame_0208.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("blueRing_frame_0208.png"));
					if (mModel2 != null) {
						// Set geometry properties
						// mModel2.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel2.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel2.setTranslation(new Vector3d(0, 0, -10));
						mModel2.setScale(new Vector3d(2, 2, 2));
						mModel2.setCoordinateSystemID(11);
					}
				}
				String metaioManModel3 = AssetsManager
						.getAssetPath("blueRing_jewel_0208.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					// mModel3.setTexture(AssetsManager.getAssetPath("blueRing_jewel_0208.png"));
					if (mModel3 != null) {
						// Set geometry properties
						// mModel3.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel3.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel3.setTranslation(new Vector3d(0, 0, -10));
						mModel3.setScale(new Vector3d(2, 2, 2));
						mModel3.setCoordinateSystemID(11);
					}
				}	
				break;	
			}	
			case 12: {	
				// Load 3D Silver Ring
				String metaioManModel = AssetsManager
						.getAssetPath("silverRing_base_cutaway.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("silverRing_base_cutaway.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						// mModel.setRotation(new Rotation((float) 1.57,0,0));
						// mModel.setTranslation(new Vector3d((float)-10.0,
						// (float)5.0, (float)-20.0));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(12);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("silverRing_diamonds_cutaway.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("silverRing_diamonds_cutaway.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						// mModel1.setTranslation(new Vector3d((float)-10.0,
						// (float)5.0, (float)-20.0));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(12);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("silverRing_frame_cutaway.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("silverRing_frame_cutaway.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setRotation(new Rotation((float) 1.57, 0,
								(float) 1.57));
						// mModel2.setTranslation(new Vector3d((float)-10.0,
						// (float)5.0, (float)-20.0));
						mModel2.setScale(new Vector3d(3, 3, 3));
						mModel2.setCoordinateSystemID(12);
					}
				}	
				break;	
			}
	
			case 13: {	
				// Load Zales Box Cards	
				String metaioManModel = AssetsManager
						.getAssetPath("zalesRing_base_cut.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("zalesRing_base_cut.png"));
					if (mModel != null) {
						// Set geometry properties
						// mModel.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel.setScale(new Vector3d(4, 4, 4));
						mModel.setCoordinateSystemID(13);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("zalesRing_diamonds_cut.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("zalesRing_diamonds_cut.png"));
					if (mModel1 != null) {
						// Set geometry properties
						// mModel1.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel1.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel1.setScale(new Vector3d(4, 4, 4));
						mModel1.setCoordinateSystemID(13);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("zalesRing_frame_cut.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("zalesRing_frame_cut.png"));
					if (mModel2 != null) {
						// Set geometry properties
						// mModel2.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel2.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel2.setScale(new Vector3d(4, 4, 4));
						mModel2.setCoordinateSystemID(13);
					}
				}
				String metaioManModel3 = AssetsManager
						.getAssetPath("zalesRing_jewel_cut.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					// mModel3.setTexture(AssetsManager.getAssetPath("zalesRing_jewel_cut.png"));
					if (mModel3 != null) {
						// Set geometry properties
						// mModel3.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel3.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel3.setScale(new Vector3d(4, 4, 4));
						mModel3.setCoordinateSystemID(13);
					}
				}
	
				String metaioManModel4 = AssetsManager
						.getAssetPath("zalesRing_lattice_cut.obj");
				IGeometry mModel4;
				if (metaioManModel4 != null) {
					// Loading 3D geometry
					mModel4 = metaioSDK.createGeometry(metaioManModel4);
					// mModel4.setTexture(AssetsManager.getAssetPath("zalesRing_lattice_cut.png"));
					if (mModel4 != null) {
						// Set geometry properties
						// mModel4.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel4.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel4.setScale(new Vector3d(4, 4, 4));
						mModel4.setCoordinateSystemID(13);
					}
				}	
				break;	
			}	
			case 14: {	
				// Load 3D Blue Ring	
				String metaioManModel = AssetsManager
						.getAssetPath("blueRing_base_0208.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("blueRing_base_0208.png"));
					if (mModel != null) {
						// Set geometry properties
						mModel.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(14);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("blueRing_diamonds_0208.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("blueRing_diamonds_0208.png"));
					if (mModel1 != null) {
						// Set geometry properties
						mModel1.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(14);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("blueRing_frame_0208.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("blueRing_frame_0208.png"));
					if (mModel2 != null) {
						// Set geometry properties
						mModel2.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel2.setScale(new Vector3d(3, 3, 3));
						mModel2.setCoordinateSystemID(14);
					}
				}
				String metaioManModel3 = AssetsManager
						.getAssetPath("blueRing_jewel_0208.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					// mModel3.setTexture(AssetsManager.getAssetPath("blueRing_jewel_0208.png"));
					if (mModel3 != null) {
						// Set 2geometry properties
						mModel3.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel3.setScale(new Vector3d(3, 3, 3));
						mModel3.setCoordinateSystemID(14);
					}
				}	
				break;	
			}
	
			case 15: {	
				// Load 3D Silver Ring
				String metaioManModel = AssetsManager
						.getAssetPath("silverRing_base_cutaway.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("silverRing_base_cutaway.png"));
					if (mModel != null) {
						// Set geometry properties
						// mModel.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel.setRotation(new Rotation((float) 1.57, (float) -0.4,
								0));
						// mModel.setTranslation(new Vector3d(0,0,-10));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(15);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("silverRing_diamonds_cutaway.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("silverRing_diamonds_cutaway.png"));
					if (mModel1 != null) {
						// Set geometry properties
						// mModel1.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel1.setRotation(new Rotation((float) 1.57,
								(float) -0.4, 0));
						// mModel1.setTranslation(new Vector3d(0,0,-10));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(15);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("silverRing_frame_cutaway.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("silverRing_frame_cutaway.png"));
					if (mModel2 != null) {
						// Set geometry properties
	
						// mModel2.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel2.setRotation(new Rotation((float) 1.57,
								(float) -0.4, 0));
						// mModel2.setTranslation(new Vector3d(0,0,-10));
						mModel2.setScale(new Vector3d(3, 3, 3));
						mModel2.setCoordinateSystemID(15);
					}
				}	
				break;	
			}
	
			case 16: {	
				// Load 3D Ruby Ring
				String metaioManModel = AssetsManager
						.getAssetPath("redRing_base_cutaway.obj");
				IGeometry mModel;
				if (metaioManModel != null) {
					// Loading 3D geometry
					mModel = metaioSDK.createGeometry(metaioManModel);
					// mModel.setTexture(AssetsManager.getAssetPath("redRing_base_cutaway.png"));
					if (mModel != null) {
						// Set geometry properties
						// mModel.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
	
						mModel.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel.setScale(new Vector3d(3, 3, 3));
						mModel.setCoordinateSystemID(16);
					}
				}
				String metaioManModel1 = AssetsManager
						.getAssetPath("redRing_diamonds.obj");
				IGeometry mModel1;
				if (metaioManModel1 != null) {
					// Loading 3D geometry
					mModel1 = metaioSDK.createGeometry(metaioManModel1);
					// mModel1.setTexture(AssetsManager.getAssetPath("redRing_diamonds.png"));
					if (mModel1 != null) {
						// Set geometry properties
						// mModel1.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel1.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel1.setScale(new Vector3d(3, 3, 3));
						mModel1.setCoordinateSystemID(16);
					}
				}
				String metaioManModel2 = AssetsManager
						.getAssetPath("redRing_frame.obj");
				IGeometry mModel2;
				if (metaioManModel2 != null) {
					// Loading 3D geometry
					mModel2 = metaioSDK.createGeometry(metaioManModel2);
					// mModel2.setTexture(AssetsManager.getAssetPath("redRing_frame.png"));
					if (mModel2 != null) {
						// Set geometry properties
						// mModel2.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel2.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel2.setScale(new Vector3d(3, 3, 3));
						mModel2.setCoordinateSystemID(16);
					}
				}
				String metaioManModel3 = AssetsManager
						.getAssetPath("redRing_jewel.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					// mModel3.setTexture(AssetsManager.getAssetPath("redRing_jewel.png"));
					if (mModel3 != null) {
						// Set geometry properties
						// mModel3.setRotation(new Rotation((float) 1.57,0,(float)
						// 1.57));
						mModel3.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel3.setScale(new Vector3d(3, 3, 3));
						mModel3.setCoordinateSystemID(16);
					}
				}	
				break;	
			}
	
			case 17: {	
				// Load 3D Watch	
				String metaioManModel3 = AssetsManager
						.getAssetPath("watchFinal.obj");
				IGeometry mModel3;
				if (metaioManModel3 != null) {
					// Loading 3D geometry
					mModel3 = metaioSDK.createGeometry(metaioManModel3);
					mModel3.setTexture(AssetsManager.getAssetPath("watchFinal.png"));
					if (mModel3 != null) {
						// Set geometry properties
						mModel3.setRotation(new Rotation((float) 1.57, 0, 0));
						mModel3.setScale(new Vector3d((float) 6.25, (float) 6.25,
								(float) 6.25));
						mModel3.setCoordinateSystemID(17);
					}
				}	
				break;
			}
		}
	}

	int p=0;
	int checkOfferFlag = 0;
	String afterCompleteDoInBg = "";
	String downloadModelProcess = "false";
	int appendVal = 0;
	
	ArrayList<String> strArrayForMarkerTriggerID = new ArrayList<String>();
	ArrayList<String> strArrayForMarkerTriggerTimeNow = new ArrayList<String>();
	ArrayList<String> strArrayForMarkerInfo;
	public void markerDetected(final int cosID, String cosName, final String strFlag, final int productIdForRS2, String offerIdForRS2) {
		try{
			
			checkOfferFlag = 0;
			//Log.i("mDetectedCosID if -1", ""+mDetectedCosID+" "+mDetectedCosName);
			if(mDetectedCosID == -1){
				unloadLoadedModels();
			}
			//Log.i("cosName",""+cosName+" "+cosID);
			//Log.i("mDetectedCosID if -1", ""+mDetectedCosID+" "+mDetectedCosName);
			if (cosID != mDetectedCosID){
				startnow = android.os.SystemClock.uptimeMillis();
			//	Log.i("cosName",""+cosName+" "+cosID);
			//	Log.i("clientTriggerId",""+clientTriggerId);
				runOnUiThread(new Runnable() {
					@Override							
					public void run() {	
						progressBarVideoNew.setVisibility(View.VISIBLE);
					}
				});
				mDetectedCosID = cosID;	
				mDetectedCosName = cosName;
				afterCompleteDoInBg = "false";
				displayVisuals(cosName,cosID,strFlag,productIdForRS2,offerIdForRS2);
				
			} else if(afterCompleteDoInBg=="true")
			{
				triggerModel(mDetectedCosID,mDetectedCosName);
			}
			
		}catch (final Exception e) {
			runOnUiThread(new Runnable() {				
				@Override
				public void run() {	
					e.printStackTrace();
					String errorMsg = className+" | markerDetected |    " +e.getMessage();
		        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);					
				}
			});
		}
	}	
	private void displayVisuals(String cosName, final int cosID, final String strFlag, int productIdForRS2, String offerIdForRS2) {
		try{
			final ArrayList<String> arrOfferFlagCount = new ArrayList<String>();
			final ArrayList<String> arrOfferImages = new ArrayList<String>();
			final ArrayList<String> arrOfferIds = new ArrayList<String>();
			final ArrayList<String> arrOfferWithClientIds = new ArrayList<String>();
			final Visuals visuals = file.getVisuals();
			final List<Visual> triggerVisual = visuals.getVisualByTriggerId(Integer.parseInt(cosName));
			
			Log.i("triggerVisual", triggerVisual.size()+" "+triggerVisual);
		
			if(triggerVisual.size() > 0){				
				for ( final Visual visual : triggerVisual) {
					if(!visual.getInstruction().equals("no instuction")){							
						runOnUiThread(new Runnable() {
							@Override								
							public void run() {										
								try{
									RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
									RelativeLayout.LayoutParams rlInstrLayout = (RelativeLayout.LayoutParams) rlInstruction.getLayoutParams();
									rlInstrLayout.height = (int) (0.291 * Common.sessionDeviceHeight);
									rlInstrLayout.topMargin = (int) (0.125 * Common.sessionDeviceHeight);
									rlInstruction.setLayoutParams(rlInstrLayout);
									rlInstruction.setVisibility(View.VISIBLE);
									
									TextView instruction = (TextView)findViewById(R.id.instruction);
									RelativeLayout.LayoutParams rlInstrMsg = (RelativeLayout.LayoutParams) instruction.getLayoutParams();
									rlInstrMsg.leftMargin = (int) (0.119 * Common.sessionDeviceWidth);
									rlInstrMsg.bottomMargin = (int) (0.061 * Common.sessionDeviceHeight);
									rlInstrMsg.width = (int) (0.67 * Common.sessionDeviceWidth);
									rlInstrMsg.height = (int) (0.082 * Common.sessionDeviceHeight);
									instruction.setLayoutParams(rlInstrMsg);
									instruction.setText(visual.getInstruction());
									instruction.setTextSize((float) ((0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));

									TextView label = (TextView)findViewById(R.id.label);										
									label.setTextSize((float) ((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));

									ImageView imgClose = (ImageView)findViewById(R.id.closeinc);
									RelativeLayout.LayoutParams rlInstrClose = (RelativeLayout.LayoutParams) imgClose.getLayoutParams();
									rlInstrClose.width = (int) (0.084 * Common.sessionDeviceWidth);
									rlInstrClose.height = (int) (0.052 * Common.sessionDeviceHeight);
									rlInstrClose.bottomMargin = (int) (0.04 * Common.sessionDeviceHeight);
									//rlInstrClose.leftMargin = (int) (0.012 * Common.sessionDeviceWidth);
									imgClose.setLayoutParams(rlInstrClose);

									/*ImageView imgIcon= (ImageView)findViewById(R.id.icon);
									RelativeLayout.LayoutParams rlInstrIcon = (RelativeLayout.LayoutParams) imgIcon.getLayoutParams();
									rlInstrIcon.width = (int) (0.134 * Common.sessionDeviceWidth);
									rlInstrIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
									rlInstrIcon.topMargin = (int) (0.0154 * Common.sessionDeviceWidth);
									imgIcon.setLayoutParams(rlInstrIcon);*/
								} catch (Exception e){
									e.printStackTrace();
									String errorMsg = className+" | markerDetected | no instuction | runOnUiThread |  " +e.getMessage();
							        Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}
							}
						});
					}
					if(visual.getDiscriminator().equalsIgnoreCase("BUTTON")){
						
						runOnUiThread(new Runnable() {
							@Override							
							public void run() {										
								progressBarVideoNew.setVisibility(View.INVISIBLE);
								findViewById(R.id.rlInstruction).setVisibility(View.INVISIBLE);
							}
						});
						if(visual.getTitle().equals(visual.getTitle()))
						{					
							if(triggerVisual.size() == 1){
								strArrayForMarkerInfo = new ArrayList<String>();									
								strArrayForMarkerInfo.add(strFlag);
								strArrayForMarkerInfo.add(""+visual.getTriggerId());
								strArrayForMarkerInfo.add(visual.getImageName());
								strArrayForMarkerInfo.add(visual.getTitle());
								strArrayForMarkerInfo.add(""+cosID);
								strArrayForMarkerInfo.add(""+visual.getProductId());	
								strArrayForMarkerInfo.add(visual.getProductName());	
								strArrayForMarkerInfo.add(""+visual.getOfferId());		
								strArrayForMarkerInfo.add(visual.getOfferName());												
								
								storedRecentlyScannedInfo(strArrayForMarkerInfo);
								
								stringArrayList = new ArrayList<String>();
				    			stringArrayList.add(""+visual.getClientId());
				    			stringArrayList.add(visual.getClientLogo());
				    			stringArrayList.add(visual.getImageName());
				    			stringArrayList.add(visual.getClientBackgroundImage());
				    			stringArrayList.add(visual.getClientBackgroundColor());
				    			stringArrayList.add(visual.getClientUrl());
								stringArrayList.add(""+visual.getProductId());
				    			stringArrayList.add(visual.getProductName());
				    			stringArrayList.add(visual.getProductPrice());
				    			stringArrayList.add(visual.getProductShortDesc());
				    			stringArrayList.add(visual.getProdTapForDetailsImgId());
				    			stringArrayList.add(visual.getProdTapForDetailsImgPdId() );
				    			stringArrayList.add(visual.getProductUrl());
				    			stringArrayList.add(visual.getProductBgColor());
				    			stringArrayList.add(""+visual.getProductHideImage());
				    			stringArrayList.add(visual.getProductButtonName());
				    			stringArrayList.add(visual.getOfferButtonName());
				    			stringArrayList.add(""+visual.getProdIsTryOn());
				    			stringArrayList.add(visual.getClientLightColor());
				    			stringArrayList.add(visual.getClientDarkColor());
				    			
				    			String visualImageName = "";
    							if(visual.getProImageFile().equals("")){
    								visualImageName = visual.getOfferImage();
    							} else {
    								visualImageName = visual.getProImageFile();
    							}
				    			
								stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
								session.createClientProductValuesInSession(stringArray);
								new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
								Intent intent; 
								if( visual.getClientVerticalId()== 1){
									runOnUiThread(new Runnable() {
										@Override							
										public void run() {	
											Log.e(" visual.getClientVerticalId()",""+ visual.getClientVerticalId());
											RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
											rlInstruction.setVisibility(View.INVISIBLE);
											RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view);
											if(rlayCameraView1 != null){
										    	rlayCameraView1.setBackgroundResource(0);
												findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
												findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
												findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
												findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
												findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
											}
										  }
										});
                                    trackingFlag = false;
									intent = new Intent(getApplicationContext(), FinancialActivity.class);
									intent.putExtra("productId", ""+visual.getProductId());
									intent.putExtra("clientId", ""+visual.getClientId());
									intent.putExtra("discriminator",visual.getDiscriminator());
									intent.putExtra("buttonName",  visual.getProductButtonName());
									intent.putExtra("buttonUrl", visual.getProductUrl());
									intent.putExtra("productPrice", visual.getProductPrice());
									intent.putExtra("offerTitle", visual.getProductName());
									
								}else{
									trackingFlag = false;
									intent = new Intent(getApplicationContext(), ProductList.class);
							    	
								}
								intent.putExtra("product_image_url",visualImageName);
								if(strFlag.equals("recentlyScannedForActivity")){										
									intent.putExtra("pageRedirectFlag", "RecentlyScanned");
								}
								startActivityForResult(intent, 1);
								overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
							}else{
								if(strFlag.equals("")){
									String metaioManModel = AssetsManager.getAssetPath("button.md2");
									MetaioDebug.log("AssetsManager.getAssetPath: " + metaioManModel); 
									IGeometry mModel;
									if (metaioManModel != null) 
									{
										// Loading 3D geometry
										mModel = metaioSDK.createGeometry(metaioManModel);
										if (mModel != null) 
										{
											mModel.setScale(new Vector3d(15, 15, 15));
											mModel.setTranslation(new Vector3d(visual.getX(),visual.getY(),0));
											mModel.setCoordinateSystemID(mDetectedCosID);	
											
			    							String visualImageName = "";
			    							if(visual.getProImageFile().equals("")){
			    								visualImageName = visual.getOfferImage();
			    							} else {
			    								visualImageName = visual.getProImageFile();
			    							}
			    							
											mModel.setName(visual.getDiscriminator()+","
													+ visualImageName + ","
													+ visual.getClientId() + ","
													+ visual.getImageName() + ","
													+ visual.getProductPrice() + ","
													+ visual.getProductId() + ","
													+ visual.getProductName() + ","
													+ visual.getClientLogo() + ","
													+ visual.getClientBackgroundImage() + ","
													+ visual.getClientBackgroundColor() + "," 
													+ visual.getProductShortDesc() +","
													+ visual.getModel().size() + "," 
													+ visual.getProdTapForDetailsImgId() + "," 
													+ visual.getProdTapForDetailsImgPdId() + "," 
													+ visual.getClientUrl().toString() + "," 
													+ visual.getProductUrl().toString() +","
													+ visual.getOfferId() + ","
													+ visual.getCalendarEventStartDate() + ","
													+ visual.getCalendarEventEndDate() + ","
													+ visual.getCalendarEventAllDay() + ","
													+ visual.getCalendarEventHasAlarm() + ","
													+ visual.getCalendarReminderDays()+ ","
													+ visual.getProductBgColor()+ ","
													+ visual.getProductHideImage()+","
													+ visual.getOfferPurchaseUrl()+","
													+ visual.getClientVerticalId()+","
													+ visual.getProductButtonName()+","
													+ visual.getOfferButtonName()+","
													+ visual.getProdIsTryOn()+","
													+ visual.getClientLightColor()+","
													+ visual.getClientDarkColor()+","
													+ visual.getTriggerId()+","
													+ visual.getTitle()+","
													+ cosID+","
													+ visual.getOfferName());
										}
										else
											MetaioDebug.log(Log.ERROR, "Error loading geometry: "+metaioManModel);
							
									}
								
								} else if(strFlag.equals("recentlyScannedForActivity") && productIdForRS2 == visual.getProductId()){

									strArrayForMarkerInfo = new ArrayList<String>();										
									strArrayForMarkerInfo.add(strFlag);
									strArrayForMarkerInfo.add(""+visual.getTriggerId());
									strArrayForMarkerInfo.add(visual.getImageName());
									strArrayForMarkerInfo.add(visual.getTitle());
									strArrayForMarkerInfo.add(""+cosID);
									strArrayForMarkerInfo.add(""+visual.getProductId());	
									strArrayForMarkerInfo.add(visual.getProductName());	
									strArrayForMarkerInfo.add(""+visual.getOfferId());		
									strArrayForMarkerInfo.add(visual.getOfferName());													
									
									storedRecentlyScannedInfo(strArrayForMarkerInfo);
									
									stringArrayList = new ArrayList<String>();
					    			stringArrayList.add(""+visual.getClientId());
					    			stringArrayList.add(visual.getClientLogo());
					    			stringArrayList.add(visual.getImageName());
					    			stringArrayList.add(visual.getClientBackgroundImage());
					    			stringArrayList.add(visual.getClientBackgroundColor());
					    			stringArrayList.add(visual.getClientUrl());
									stringArrayList.add(""+visual.getProductId());
					    			stringArrayList.add(visual.getProductName());
					    			stringArrayList.add(visual.getProductPrice());
					    			stringArrayList.add(visual.getProductShortDesc());
					    			stringArrayList.add(visual.getProdTapForDetailsImgId());
					    			stringArrayList.add(visual.getProdTapForDetailsImgPdId() );
					    			stringArrayList.add(visual.getProductUrl());
					    			stringArrayList.add(visual.getProductBgColor());
					    			stringArrayList.add(""+visual.getProductHideImage());
					    			stringArrayList.add(visual.getProductButtonName());
					    			stringArrayList.add(visual.getOfferButtonName());
					    			stringArrayList.add(""+visual.getProdIsTryOn());
					    			stringArrayList.add(visual.getClientLightColor());
					    			stringArrayList.add(visual.getClientDarkColor());
					    			
					    			String visualImageName = "";
	    							if(visual.getProImageFile().equals("")){
	    								visualImageName = visual.getOfferImage();
	    							} else {
	    								visualImageName = visual.getProImageFile();
	    							}
					    			
									stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
									session.createClientProductValuesInSession(stringArray);
									new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
									Intent intent; 
									if( visual.getClientVerticalId()== 1){
										runOnUiThread(new Runnable() {
											@Override							
											public void run() {	
												RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
												rlInstruction.setVisibility(View.INVISIBLE);
											}
											});
                                        trackingFlag = false;
										intent = new Intent(getApplicationContext(), FinancialActivity.class);
										intent.putExtra("productId", ""+visual.getProductId());
										intent.putExtra("clientId", ""+visual.getClientId());
										intent.putExtra("discriminator",visual.getDiscriminator());
										intent.putExtra("buttonName",  visual.getProductButtonName());
										intent.putExtra("buttonUrl", visual.getProductUrl());
										intent.putExtra("productPrice", visual.getProductPrice());
										intent.putExtra("offerTitle", visual.getProductName());
										
									}else{
										trackingFlag = false;
										intent = new Intent(getApplicationContext(), ProductList.class);									    	
									}
									intent.putExtra("pageRedirectFlag", "RecentlyScanned");
									intent.putExtra("product_image_url",visualImageName);
									startActivityForResult(intent, 1);
									overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
									break;
								}
							}
						}
					}else if(visual.getDiscriminator().equalsIgnoreCase("VIDEO")){
						runOnUiThread(new Runnable() {
							@Override							
							public void run() {	
								try{
									if(visual.getClientVerticalId() == 1){
										try{
											strArrayForMarkerInfo = new ArrayList<String>();												
											strArrayForMarkerInfo.add(strFlag);
											strArrayForMarkerInfo.add(""+visual.getTriggerId());
											strArrayForMarkerInfo.add(visual.getImageName());
											strArrayForMarkerInfo.add(visual.getTitle());
											strArrayForMarkerInfo.add(""+cosID);
											strArrayForMarkerInfo.add(""+visual.getProductId());	
											strArrayForMarkerInfo.add(visual.getProductName());	
											strArrayForMarkerInfo.add(""+visual.getOfferId());		
											strArrayForMarkerInfo.add(visual.getOfferName());												
											
											storedRecentlyScannedInfo(strArrayForMarkerInfo);
											
											progressBarVideoNew.setVisibility(View.INVISIBLE);
											
											stringArrayList = new ArrayList<String>();
							    			stringArrayList.add(""+visual.getClientId());
							    			stringArrayList.add(visual.getClientLogo());
							    			stringArrayList.add(visual.getImageName());
							    			stringArrayList.add(visual.getClientBackgroundImage());
							    			stringArrayList.add(visual.getClientBackgroundColor());
							    			stringArrayList.add(visual.getClientUrl());
											stringArrayList.add(""+visual.getProductId());
							    			stringArrayList.add(visual.getProductName());
							    			stringArrayList.add(visual.getProductPrice());
							    			stringArrayList.add(visual.getProductShortDesc());
							    			stringArrayList.add(visual.getProdTapForDetailsImgId());
							    			stringArrayList.add(visual.getProdTapForDetailsImgPdId() );
							    			stringArrayList.add(visual.getProductUrl());
							    			stringArrayList.add(visual.getProductBgColor());
							    			stringArrayList.add(""+visual.getProductHideImage());
							    			
							    					
							    			stringArrayList.add(visual.getProductButtonName());
							    			stringArrayList.add(visual.getOfferButtonName());
							    			stringArrayList.add(""+visual.getProdIsTryOn());
							    			stringArrayList.add(visual.getClientLightColor());
							    			stringArrayList.add(visual.getClientDarkColor());
							    			
											stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
											session.createClientProductValuesInSession(stringArray);
											new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
													
											RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
	                                        rlInstruction.setVisibility(View.INVISIBLE);
	                                        trackingFlag = false;
	                                        
											Intent finacialIntent = new Intent(getApplicationContext(),FinancialActivity.class);
											finacialIntent.putExtra("discriminator",visual.getDiscriminator());
											finacialIntent.putExtra("videoUrl", visual.getVisualUrl());										
											finacialIntent.putExtra("buttonUrl", visual.getProductUrl());
											finacialIntent.putExtra("productPrice", visual.getProductPrice());
											finacialIntent.putExtra("productId", ""+visual.getProductId());
											finacialIntent.putExtra("offerId", ""+visual.getOfferId());
											finacialIntent.putExtra("offerDiscType", visual.getOfferDiscountType());
											finacialIntent.putExtra("offerValidTo",""+visual.getOfferValidTo());	
											finacialIntent.putExtra("clientId", ""+visual.getClientId());												
											if(visual.getProductId() != 0){												
												finacialIntent.putExtra("product_image_url", visual.getProImageFile());
												finacialIntent.putExtra("buttonName", visual.getProductButtonName());
												finacialIntent.putExtra("offerTitle",""+visual.getProductName());	
											}
											else{
												finacialIntent.putExtra("buttonName", visual.getOfferButtonName());
												finacialIntent.putExtra("product_image_url", visual.getOfferImage());
												finacialIntent.putExtra("offerTitle",""+visual.getOfferName());	
											}											
											if(strFlag.equals("recentlyScannedForActivity")){
												finacialIntent.putExtra("pageRedirectFlag", "RecentlyScanned");
											}									
											
											int requestCode = 0;
											startActivityForResult(finacialIntent, requestCode);
											overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
										}catch(Exception e){
											e.printStackTrace();
										}
									}else{	
										strArrayForMarkerInfo = new ArrayList<String>();											
										strArrayForMarkerInfo.add(strFlag);
										strArrayForMarkerInfo.add(""+visual.getTriggerId());
										strArrayForMarkerInfo.add(visual.getImageName());
										strArrayForMarkerInfo.add(visual.getTitle());
										strArrayForMarkerInfo.add(""+cosID);
										String strId = "null";
										String strName = "Video";
										if(visual.getProductId()!=0){
											strId = ""+visual.getProductId();
											strArrayForMarkerInfo.add(strId);	
											strArrayForMarkerInfo.add(strName);	
											strArrayForMarkerInfo.add("null");		
											strArrayForMarkerInfo.add("null");
										} else if(visual.getOfferId()!=null){
											strId = visual.getOfferId();
											strArrayForMarkerInfo.add("null");		
											strArrayForMarkerInfo.add("null");	
											strArrayForMarkerInfo.add(strId);	
											strArrayForMarkerInfo.add(strName);	
										} else {
											strArrayForMarkerInfo.add("Video_"+cosID);		
											strArrayForMarkerInfo.add("Video");
											strArrayForMarkerInfo.add("null");		
											strArrayForMarkerInfo.add("null");												
										}
										
										storedRecentlyScannedInfo(strArrayForMarkerInfo);							
										
					                	if(visual.getX() == 0 && visual.getY()==0){			
											progressBarVideoNew.setVisibility(View.INVISIBLE);
					                	} else {
											progressBarVideoNew.setVisibility(View.INVISIBLE);		                		
					                	}
					                	playVideo(visual.getVisualUrl(), visual.getX(), visual.getY(), strFlag);
									}
								}catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | markerDetected | Discriminator | VIDEO |  " +e.getMessage();
							        Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}
							}
						});		
					}else if(visual.getDiscriminator().equalsIgnoreCase("AUDIO")){
						runOnUiThread(new Runnable() {
							@Override							
							public void run() {	
								try{
								if(visual.getClientVerticalId() == 1){
									try{
										strArrayForMarkerInfo = new ArrayList<String>();											
										strArrayForMarkerInfo.add(strFlag);
										strArrayForMarkerInfo.add(""+visual.getTriggerId());
										strArrayForMarkerInfo.add(visual.getImageName());
										strArrayForMarkerInfo.add(visual.getTitle());
										strArrayForMarkerInfo.add(""+cosID);
										strArrayForMarkerInfo.add(""+visual.getProductId());	
										strArrayForMarkerInfo.add(visual.getProductName());	
										strArrayForMarkerInfo.add(""+visual.getOfferId());		
										strArrayForMarkerInfo.add(visual.getOfferName());												
										
										storedRecentlyScannedInfo(strArrayForMarkerInfo);
										progressBarVideoNew.setVisibility(View.INVISIBLE);
										
										stringArrayList = new ArrayList<String>();
						    			stringArrayList.add(""+visual.getClientId());
						    			stringArrayList.add(visual.getClientLogo());
						    			stringArrayList.add(visual.getImageName());
						    			stringArrayList.add(visual.getClientBackgroundImage());
						    			stringArrayList.add(visual.getClientBackgroundColor());
						    			stringArrayList.add(visual.getClientUrl());
										stringArrayList.add(""+visual.getProductId());
						    			stringArrayList.add(visual.getProductName());
						    			stringArrayList.add(visual.getProductPrice());
						    			stringArrayList.add(visual.getProductShortDesc());
						    			stringArrayList.add(visual.getProdTapForDetailsImgId());
						    			stringArrayList.add(visual.getProdTapForDetailsImgPdId() );
						    			stringArrayList.add(visual.getProductUrl());
						    			stringArrayList.add(visual.getProductBgColor());
						    			stringArrayList.add(""+visual.getProductHideImage());
						    			
						    			

						    			stringArrayList.add(visual.getProductButtonName());
						    			stringArrayList.add(visual.getOfferButtonName());
						    			stringArrayList.add(""+visual.getProdIsTryOn());
						    			stringArrayList.add(visual.getClientLightColor());
						    			stringArrayList.add(visual.getClientDarkColor());
						    			
										stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
										session.createClientProductValuesInSession(stringArray);
										new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
										

										RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
                                        rlInstruction.setVisibility(View.INVISIBLE);
                                        trackingFlag = false;
                                        
										Intent finacialIntent = new Intent(getApplicationContext(),FinancialActivity.class);
										finacialIntent.putExtra("discriminator",visual.getDiscriminator());
										finacialIntent.putExtra("videoUrl", visual.getVisualUrl());											
										finacialIntent.putExtra("buttonUrl", visual.getProductUrl());
										finacialIntent.putExtra("productPrice", visual.getProductPrice());
										finacialIntent.putExtra("productId", ""+visual.getProductId());
										finacialIntent.putExtra("offerId", ""+visual.getOfferId());
										finacialIntent.putExtra("clientId", ""+visual.getClientId());
										
										if(visual.getProductId() != 0){												
											finacialIntent.putExtra("product_image_url", visual.getProImageFile());
											finacialIntent.putExtra("buttonName", visual.getProductButtonName());
											finacialIntent.putExtra("offerTitle",""+visual.getProductName());	
										}
										else{
											finacialIntent.putExtra("buttonName", visual.getOfferButtonName());
											finacialIntent.putExtra("product_image_url", visual.getOfferImage());
											finacialIntent.putExtra("offerTitle",""+visual.getOfferName());	
										}
										
										if(strFlag.equals("recentlyScannedForActivity")){
											finacialIntent.putExtra("pageRedirectFlag", "RecentlyScanned");
										}

										int requestCode = 0;
										startActivityForResult(finacialIntent, requestCode);
										overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
										}catch(Exception e){
											e.printStackTrace();
									 		String errorMsg = className+" | markerDetected | Discriminator | AUDIO | if (visual.getClientVerticalId) |  " +e.getMessage();
							          		Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
										}
									}
								}catch(Exception e){
									  e.printStackTrace();
									  String errorMsg = className+" | markerDetected | Discriminator | AUDIO |  " +e.getMessage();
							          Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}
							}
						});		
					}
					else if(visual.getDiscriminator().equalsIgnoreCase("3DMODEL")){
						runOnUiThread(new Runnable() {
							@Override							
							public void run() {	
								progressBarVideoNew.setVisibility(View.INVISIBLE);
							}
						});
				    	flag = true;				    	
				    	if(visual.getModel().size() >0){			    		
				    		
				    		modelArray = visual.getModel();
				    		modelArrayCount = visual.getModelCount();
				    		if(downloadModelProcess == "false"){
				    			new LoadModel().execute();
				    		}
				    		if(afterCompleteDoInBg=="true"){
				    			for(int i = 0; i < visual.getModel().size(); i++) {								
								
									final String[] modelFileArray = visual.getModel().get(i).split(",");
									modelName = modelFileArray[0].substring(modelFileArray[0].lastIndexOf('/') + 1, modelFileArray[0].length());	    						
									textureName = modelFileArray[1].substring(modelFileArray[1].lastIndexOf('/') + 1, modelFileArray[1].length());
										
									String mdModel = Constants.Model_Location+modelName;
									File target = new File(Constants.Model_Location,modelName);
									String mdTexture = Constants.Model_Location+textureName;	
									File textureFile = new File(Constants.Model_Location,textureName);
									  if (target.exists()){
				    		    		  try{
				    		    			  
				    						IGeometry geometry = null;
				    						geometry = metaioSDK.createGeometry(mdModel);
				    						
				    						if(geometry != null){
				    							// Set geometry properties
				    							 if (textureFile.exists()){				    								
				    								 geometry.setTexture(mdTexture);
				    							 }
				    							
				    							if(visual.getScale() != 0){
				    								geometry.setScale(new Vector3d(visual.getScale(),visual.getScale(),visual.getScale()));
				    							}
				    							geometry.setTranslation(new Vector3d(visual.getX(),visual.getY(),0));
				    							geometry.setRotation(new Rotation(visual.getRotationX(), visual.getRotationY(), visual.getRotationZ()));							
				    							geometry.setCoordinateSystemID(mDetectedCosID);
				    							String visualImageName = "";
				    							if(visual.getProImageFile().equals("")){
				    								visualImageName = visual.getOfferImage();
				    							} else {
				    								visualImageName = visual.getProImageFile();
				    							}
				    							geometry.setName(visual.getDiscriminator()+","
														+ visualImageName + ","
														+ visual.getClientId() + ","
														+ visual.getImageName() + ","
														+ visual.getProductPrice() + ","
														+ visual.getProductId() + ","
														+ visual.getProductName() + ","
														+ visual.getClientLogo() + ","
														+ visual.getClientBackgroundImage() + ","
														+ visual.getClientBackgroundColor() + "," 
														+ visual.getProductShortDesc() +","
														+ visual.getModel().size() + "," 
														+ visual.getProdTapForDetailsImgId() + "," 
														+ visual.getProdTapForDetailsImgPdId() + "," 
														+ visual.getClientUrl().toString() + "," 
														+ visual.getProductUrl().toString() +","
														+ visual.getOfferId() + ","
														+ visual.getCalendarEventStartDate() + ","
														+ visual.getCalendarEventEndDate() + ","
														+ visual.getCalendarEventAllDay() + ","
														+ visual.getCalendarEventHasAlarm() + ","
														+ visual.getCalendarReminderDays()+ ","
														+ visual.getProductBgColor()+ ","
														+ visual.getProductHideImage()+ ","
														+ visual.getOfferPurchaseUrl()+","
														+ visual.getClientVerticalId()+","
														+ visual.getProductButtonName()+","
														+ visual.getOfferButtonName()+","
														+ visual.getProdIsTryOn()+","
														+ visual.getClientLightColor()+","
														+ visual.getClientDarkColor()+","
														+ visual.getTriggerId()+","
														+ visual.getTitle()+","
														+ cosID+","
														+ visual.getOfferName());
				    							
				    							StringVector animationName = geometry.getAnimationNames();				    							
				    							if(visual.getAnimateOnRecognition() == 1){
					    							if(!animationName.isEmpty()){
					    								geometry.startAnimation(animationName.get(0),false);
					    								geometry.setAnimationSpeed(96);
					    							}
				    							}
				    							flag= false;
				    							mdownload = false;
				    						}
				    						else
				    							MetaioDebug.log(Log.ERROR, "Error loading geometry: "+mdModel);
				    							
				    						
				    		    		  }catch(Exception e){
											  e.printStackTrace();
											  String errorMsg = className+" | markerDetected | Discriminator | 3DMODEL |  " +e.getMessage();
									          Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				    		    		  }
				    					}
					    		
				    				}
								
					    	}
				    	}
				    	
				    } else if(visual.getDiscriminator().equalsIgnoreCase("URL")){
				    	runOnUiThread(new Runnable() {
							@Override							
							public void run() {	
								progressBarVideoNew.setVisibility(View.INVISIBLE);
							}
				    	});
						strArrayForMarkerInfo = new ArrayList<String>();							
						strArrayForMarkerInfo.add(strFlag);
						strArrayForMarkerInfo.add(""+visual.getTriggerId());
						strArrayForMarkerInfo.add(visual.getImageName());
						strArrayForMarkerInfo.add(visual.getTitle());
						strArrayForMarkerInfo.add(""+cosID);
						strArrayForMarkerInfo.add("null");	
						strArrayForMarkerInfo.add("Web");	
						strArrayForMarkerInfo.add("null");		
						strArrayForMarkerInfo.add("null");												
						
						storedRecentlyScannedInfo(strArrayForMarkerInfo);
						
				    	Intent intent = new Intent(getApplicationContext(), WeburlActivity.class);		
				    	intent.putExtra("url", visual.getVisualUrl());
				    	intent.putExtra("clientId", visual.getClientId());
				    	intent.putExtra("clientLogo",  visual.getClientLogo());
				    	intent.putExtra("clientBackgroundImage", visual.getClientBackgroundImage());				    	
				    	intent.putExtra("clientBackgroundColor", visual.getClientBackgroundColor());
	    				int requestCode = 0;
						startActivityForResult(intent, requestCode);
				    } else if(visual.getDiscriminator().equalsIgnoreCase("OFFERS")){
				    	
						runOnUiThread(new Runnable() {
							@Override							
							public void run() {	
								try{
								progressBarVideoNew.setVisibility(View.INVISIBLE);										
								stringArrayList = new ArrayList<String>();
				    			stringArrayList.add(""+visual.getClientId());
				    			stringArrayList.add(visual.getClientLogo());
				    			stringArrayList.add(visual.getImageName());
				    			stringArrayList.add(visual.getClientBackgroundImage());
				    			stringArrayList.add(visual.getClientBackgroundColor());
				    			stringArrayList.add(visual.getClientUrl());
								stringArrayList.add(""+visual.getProductId());
				    			stringArrayList.add(visual.getProductName());
				    			stringArrayList.add(visual.getProductPrice());
				    			stringArrayList.add(visual.getProductShortDesc());
				    			stringArrayList.add(visual.getProdTapForDetailsImgId());
				    			stringArrayList.add(visual.getProdTapForDetailsImgPdId() );
				    			stringArrayList.add(visual.getProductUrl());
				    			stringArrayList.add(visual.getProductBgColor());
				    			stringArrayList.add(""+visual.getProductHideImage());
				    			stringArrayList.add(visual.getProductButtonName());
				    			stringArrayList.add(visual.getOfferButtonName());
				    			stringArrayList.add(""+visual.getProdIsTryOn());
				    			stringArrayList.add(visual.getClientLightColor());
				    			stringArrayList.add(visual.getClientDarkColor());
				    			
								stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
								session.createClientProductValuesInSession(stringArray);
								new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
								
								if(triggerVisual.size()==1){
									if(strFlag.equals("")){
										strArrayForMarkerInfo = new ArrayList<String>();											
										strArrayForMarkerInfo.add(strFlag);
										strArrayForMarkerInfo.add(""+visual.getTriggerId());
										strArrayForMarkerInfo.add(visual.getImageName());
										strArrayForMarkerInfo.add(visual.getTitle());
										strArrayForMarkerInfo.add(""+cosID);
										strArrayForMarkerInfo.add("null");	
										strArrayForMarkerInfo.add("null");	
										strArrayForMarkerInfo.add(""+visual.getOfferId());		
										strArrayForMarkerInfo.add(visual.getOfferName());											
										
										storedRecentlyScannedInfo(strArrayForMarkerInfo);										
									}
									checkOfferFlag++;
									
									
									if(visual.getClientVerticalId()== 1){
										RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
                                        rlInstruction.setVisibility(View.INVISIBLE);
                                        trackingFlag = false;
                                        
										Intent finacialIntent = new Intent(getApplicationContext(),FinancialActivity.class);
										finacialIntent.putExtra("discriminator",visual.getDiscriminator());
										finacialIntent.putExtra("buttonName", visual.getOfferButtonName());
										finacialIntent.putExtra("buttonUrl", visual.getOfferPurchaseUrl());
										finacialIntent.putExtra("offerId",""+visual.getOfferId());	
										finacialIntent.putExtra("offerTitle",""+visual.getOfferName());	
										finacialIntent.putExtra("offerPurchaseUrl",""+visual.getOfferPurchaseUrl());
										finacialIntent.putExtra("product_image_url",visual.getOfferImage());
										finacialIntent.putExtra("productPrice", visual.getOfferDiscountValue());
										finacialIntent.putExtra("offerDiscType", visual.getOfferDiscountType());
										finacialIntent.putExtra("clientId",  ""+visual.getClientId());
										finacialIntent.putExtra("offerValidTo",""+visual.getOfferValidTo());
										if(strFlag.equals("recentlyScannedForActivity")){
											finacialIntent.putExtra("pageRedirectFlag", "RecentlyScanned");
										}
										int requestCode = 0;
										startActivityForResult(finacialIntent, requestCode);
										overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
										
									}else{
										
										trackingFlag = false;
										Intent intent = new Intent(getApplicationContext(), OfferViewActivity.class);		
										intent.putExtra("imagename", visual.getOfferImage());
								    	intent.putExtra("clientId",  ""+visual.getClientId());
										intent.putExtra("clientLogo", visual.getClientLogo());
										intent.putExtra("clientBackgroundImage", visual.getClientBackgroundImage());
										intent.putExtra("clientBackgroundColor", visual.getClientBackgroundColor());
										intent.putExtra("clientBackgroundLightColor", visual.getClientLightColor());
										intent.putExtra("clientBackgroundDarkColor", visual.getClientDarkColor());
										//intent.putExtra("productId",""+visual.getProductId());	
										intent.putExtra("offerId",""+visual.getOfferId());	
										intent.putExtra("offerPurchaseUrl",""+visual.getOfferPurchaseUrl());
										if(strFlag.equals("recentlyScannedForActivity")){
											intent.putExtra("pageRedirectFlag", "RecentlyScanned");
										}
					    				int requestCode = 1;
										startActivityForResult(intent, requestCode);
										progressBarVideoNew.setVisibility(View.INVISIBLE);		
									}
								} else {
									arrOfferImages.add(visual.getOfferImage());
									arrOfferWithClientIds.add(String.valueOf(visual.getClientId()));
									arrOfferIds.add(String.valueOf(visual.getOfferId()));
									p++;
									checkOfferFlag++;
								}

								
								if(triggerVisual.size()>=2 && triggerVisual.size()==checkOfferFlag && visual.getOfferIsCalendarBased().equals("0")){
									if(strFlag.equals("")){
										strArrayForMarkerInfo = new ArrayList<String>();
										
										strArrayForMarkerInfo.add(strFlag);
										strArrayForMarkerInfo.add(""+visual.getTriggerId());
										strArrayForMarkerInfo.add(visual.getImageName());
										strArrayForMarkerInfo.add(visual.getTitle());
										strArrayForMarkerInfo.add(""+cosID);
										strArrayForMarkerInfo.add("null");	
										strArrayForMarkerInfo.add("null");
										strArrayForMarkerInfo.add(""+TextUtils.join("`", arrOfferIds));		
										strArrayForMarkerInfo.add("Multiple Offers");											
										
										storedRecentlyScannedInfo(strArrayForMarkerInfo);										
									}
									
									checkOfferFlag = 0;										
									RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
							    	rlayCameraView1.setBackgroundResource(0);
									//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
									//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
									arrOfferFlagCount.add("My Offers");
									trackingFlag = false;
									Intent intent = new Intent(getApplicationContext(), OfferCalendarActivity.class);		
									intent.putStringArrayListExtra("stringArrOfferFlagCount", arrOfferFlagCount);										
									intent.putStringArrayListExtra("stringArrayOfferCalImages", arrOfferImages);
									intent.putStringArrayListExtra("stringArrOfferWithClientIds", arrOfferWithClientIds);
									intent.putStringArrayListExtra("stringArrOfferIds", arrOfferIds);
									if(strFlag.equals("recentlyScannedForActivity")){
										intent.putExtra("pageRedirectFlag", "RecentlyScanned");
									}
				    				int requestCode = 0;
									startActivityForResult(intent, requestCode);
								} else if(visual.getOfferIsCalendarBased().equals("1") && triggerVisual.size()==p && checkOfferFlag>=1){
									if(strFlag.equals("")){
										strArrayForMarkerInfo = new ArrayList<String>();
										strArrayForMarkerInfo.add(strFlag);
										strArrayForMarkerInfo.add(""+visual.getTriggerId());
										strArrayForMarkerInfo.add(visual.getImageName());
										strArrayForMarkerInfo.add(visual.getTitle());
										strArrayForMarkerInfo.add(""+cosID);
										strArrayForMarkerInfo.add("null");	
										strArrayForMarkerInfo.add("null");
										strArrayForMarkerInfo.add(""+TextUtils.join("`", arrOfferIds));		
										strArrayForMarkerInfo.add("Multiple Offers");											
										
										storedRecentlyScannedInfo(strArrayForMarkerInfo);										
									}			
									
									p=0;										
									checkOfferFlag = 0;		
									RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
							    	rlayCameraView1.setBackgroundResource(0);
									//findViewById(R.id.imgvActivateTag).setVisibility(View.INVISIBLE);
									//findViewById(R.id.imgvActivateImg).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
									findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
									arrOfferFlagCount.add("Calendar");
									trackingFlag = false;
									Intent intent = new Intent(getApplicationContext(), OfferCalendarActivity.class);		
									intent.putStringArrayListExtra("stringArrOfferFlagCount", arrOfferFlagCount);
									intent.putStringArrayListExtra("stringArrayOfferCalImages", arrOfferImages);
									intent.putStringArrayListExtra("stringArrOfferWithClientIds", arrOfferWithClientIds);
									intent.putStringArrayListExtra("stringArrOfferIds", arrOfferIds);					
									if(strFlag.equals("recentlyScannedForActivity")){
										intent.putExtra("pageRedirectFlag", "RecentlyScanned");
									}
				    				int requestCode = 0;
									startActivityForResult(intent, requestCode);
								}
								if(p>triggerVisual.size()){
									p=0;
									checkOfferFlag = 0;
								}
								}catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | markerDetected |runOnUiThread| Discriminator | OFFERS |  " +e.getMessage();
						        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
								}
							}
						});
				    }else if(visual.getDiscriminator().equalsIgnoreCase("GAMES")){
				    	
				    	runOnUiThread(new Runnable() {
							
								@Override
								public void run() {
									try{
										    trackingFlag = false;
											progressBarVideoNew.setVisibility(View.INVISIBLE);
											Intent intent = new Intent(ARDisplayActivity.this,GameActivity.class);													
											intent.putExtra("client_game_id", visual.getClientGameId());
											intent.putExtra("client_id", ""+visual.getClientId());
											intent.putExtra("image", visual.getGameImage());
											intent.putExtra("game_rules", visual.getGameRules());
											intent.putExtra("game_rules_url", visual.getGameRuleUrl());		
											intent.putExtra("game_diretion_type", visual.getDirectionType());
											
											startActivity(intent);
											overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
									     } catch(Exception e){
				        					e.printStackTrace();
				        					String errorMsg = className+" | GAMES    alert yes click |   " +e.getMessage();
				    			       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
							        	}
						          }
						});
				    	
				    }
				}
			}else{					
			if(Common.isNetworkAvailable(ARDisplayActivity.this)){		    			
					createClientMarkerXml(cosID,cosName);
				//new LoadVisualXml().execute(""+cosID,cosName);
				}else{
					runOnUiThread(new Runnable() {
						@Override							
						public void run() {
							try{
								progressBarVideoNew.setVisibility(View.INVISIBLE);
								new Common().instructionBox(ARDisplayActivity.this,R.string.title_case7,R.string.instruction_case7);
							}catch(Exception e){
								e.printStackTrace();
								String errorMsg = className+" | markerDetected |runOnUiThread| instructionBox |   " +e.getMessage();
					        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
							}
						}
					});
				}
			}
			
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | display visuals  |  " +e.getMessage();
        	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}


	Handler handler = new Handler();
	String clientTriggerId ="";
	int allMarkerScanCount = 0;
	String clientId="null";	
	private void createClientMarkerXml(int cosID, String cosName) {	
		try{
			//Log.i("MYTAG createClientMarkerXml ", "createClientMarkerXml");
			trackingXMlFlag = false;
		if(!cosName.equals(clientTriggerId)){			
			clientTriggerId = cosName;	
			//final FileTransaction file = new FileTransaction();
			Triggers triggers = file.getClientTriggers();
			Log.i("MYTAG clientTriggerId ", "clientTriggerId");
			ClientTriggers clientDetails = triggers.getClientDetails(Long.parseLong(cosName));
			
			String clientIDs =  clientDetails.getClientId();		
			String[] arrclientIds = clientIDs.split(",");			
			Visuals visuals = file.getVisuals();
					
			for(int i=0;i<arrclientIds.length;i++){				
				List<Visual> triggerVisual = visuals.getVisualByClientDetail(Integer.parseInt(arrclientIds[i].trim()));				
				if(triggerVisual.size() ==0){					
					if(arrclientIds.length >1){						
						if(!clientId.equals("null")){						
							clientId = clientId +","+ arrclientIds[i];												
						}else{
							clientId = arrclientIds[i];								
						}
					}else{						
						clientId = arrclientIds[i];						
					}
				}
			}
			//Log.i("clientId",clientId);
			if(clientId !="null"){
				if(clientDetails.getTriggerId() != null){
					if(!clientDetails.getTriggerId().equals("") || clientDetails.getTriggerId() != null){
						mDetectedTriggerId = Integer.parseInt(clientDetails.getTriggerId());
					}
				}
				runOnUiThread(new Runnable() {					
					@Override
					public void run() {	
						//Log.i("MYTAG if xml_ajax ", "xml_ajax");
						xml_ajax(clientId);
						//new LoadVisualXml().execute();
						
					}
				});
			}else{				
				/*runOnUiThread(new Runnable() {					
					@Override
					public void run() {
						//Log.i("MYTAG else xml_ajax ", "xml_ajax");
						trackingXMlFlag = false;
						file.removeXMLNode(mDetectedCosName);
						new LoadContents().execute();
					}
				});*/
				
				//loadContents(Constants.Trigger_Location+"TrackingData_ClientMarker.xml","image");
				
			}			
			
		}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | createClientMarkerXml |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			
		}
	}
	int j=0;
	int countFalseFlag = 0;
	int countTrueFlag = 0;
	int countExistsFalseFlag = 0;
	int countExistsTrueFlag = 0;
	int t=0;
	int countCheckingFlag = 0;
	ArrayList<String> arrayTriggerIds;
	ArrayList<String> arrayTriggerUrls;

	Locale locale;
	String countryLanguage = "";
	String countryCode = "";
	String countryCurrencyCode = "";
	/*class LoadVisualXml extends AsyncTask<String, String, String> {		
		@Override
		protected String doInBackground(final String... args) {
			try {		
				createClientMarkerXml(args[0],args[1]);
			} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | LoadTrackingFile | doInBackground |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			}
			return null;			
			
		}

		
	}*/
	
	
	
	public void xml_ajax(final String arrClientIds){        
		//Log.i("MYTAG xml_ajax ", "xml_ajax");
		String url = Constants.Client_Triggers_Xml_URL+"";		
		Map<String, String> params = new HashMap<String, String>();
		params.put("clientIds", arrClientIds);
	     aq1.ajax(url, params, XmlDom.class, new AjaxCallback<XmlDom>(){
	    	@Override
			public void callback(String url, XmlDom xml, AjaxStatus status){
	    		try{
				      
		    		if(xml!=null){	
		   	  		 	arrayTriggerIds = new ArrayList<String>();
		   	  		 	arrayTriggerUrls = new ArrayList<String>();		   			
		   	  		 	final List<XmlDom> entries = xml.tags("trigger");
		   	  		 	Visuals visuals = new Visuals();	    
		   	  		 	if(entries.size()>0){
			   	  		 		for(XmlDom entry: entries){
				   			    	Marker marker = new Marker(entry.text("triggerUrl").replaceAll(" ", "%20"), entry.text("clientName").toString(),100, 100, 0);
				   					Visual visual = new Visual(j);
		  	   					    //visual.setVisualId(Integer.parseInt(entry.text("visual_id").toString()));
				   					if(!entry.text("visual_id").toString().equals("")){
				   						visual.setVisualId(Integer.parseInt(entry.text("visual_id").toString()));
				   					}else{
				   						visual.setVisualId(0);
				   					}
				   					//Log.i("MYTAG triggerId ", entry.text("trigger_id").toString());
				   					visual.setTriggerId(Integer.parseInt(entry.text("trigger_id").toString()));
				   					visual.setImage(entry.text("triggerUrl").toString());
				   					visual.setImageFile(entry.text("triggerUrl").toString());
				   					visual.setLegalImage(entry.text("triggerUrl").toString());
				   					visual.setTitle(entry.text("triggerTitle").toString());
				   					visual.setDiscriminator(entry.text("discriminator").toString());
				   					visual.setVisualUrl(entry.text("visualUrl").toString());
				   					visual.setClientId(Integer.parseInt(entry.text("client_id").toString()));
				   					visual.setClientVerticalId(Integer.parseInt(entry.text("trigger_by_vertical").toString()));
				   					visual.setBuyButtonName(entry.text("buy_button_name").toString());
				   					visual.setBuyButtonUrl(entry.text("buy_button_url").toString());
				   					visual.setImageName(entry.text("clientName").toString());	   					
				   					visual.setClientUrl(entry.text("clientUrl").toString());
				   					visual.setClientLogo(entry.text("clientLogo").toString());
				   					visual.setClientBackgroundImage(entry.text("background_image").toString());
				   					if(entry.text("background_color").toString().equals("null")){
				   						visual.setClientBackgroundColor("ff2600");
				   					} else {
				   						visual.setClientBackgroundColor(entry.text("background_color").toString());
				   					}
				   					if(entry.text("light_color").toString().equals("null")){
				   						visual.setClientLightColor("ff2600");
				   					} else {
				   						visual.setClientLightColor(entry.text("light_color").toString());
				   					}
				   					if(entry.text("dark_color").toString().equals("null")){
				   						visual.setClientDarkColor("ff2600");
				   					} else {
				   						visual.setClientDarkColor(entry.text("dark_color").toString());
				   					}
				   					
				   					if(!entry.text("product_id").equals("null")){visual.setProductId(Integer.parseInt(entry.text("product_id").toString()));}
				   					visual.setProductName(entry.text("prodName").toString());
				   					
				   					
				   					if(entry.text("country_languages").toString().equals("") || 
				   							entry.text("country_languages").toString().equals("null") || 
				   							entry.text("country_languages").toString()==null){
				   						countryLanguage = "en";
				   					}else {
				   						countryLanguage = entry.text("country_languages").toString();		   						
				   					}
				   					
				   					if(entry.text("country_code_char2").toString()==null || 
				   							entry.text("country_code_char2").toString().equals("") || 
				   							entry.text("country_code_char2").toString().equals("null")){	 
				   						countryCode = "US";
				   					} else { 
				   						countryCode = entry.text("country_code_char2").toString();		   						
				   					}
				   					
									String symbol = new Common().getCurrencySymbol(countryLanguage, countryCode);									
									if (entry.text("pdPrice").toString().equals("null") || 
											entry.text("pdPrice").toString().equals("") || 
											entry.text("pdPrice").toString().equals("0") || 
											entry.text("pdPrice").toString().equals("0.00") || 
											entry.text("pdPrice").toString() == null) {
										visual.setProductPrice("");
									} else {
										visual.setProductPrice(symbol+entry.text("pdPrice").toString());
									}
				   					
				   					
				   					visual.setProductUrl(entry.text("productUrl").toString());
				   					visual.setProductButtonName(entry.text("pd_button_name").toString());
				   					visual.setProductShortDesc(entry.text("pd_short_description").toString().replaceAll(",", "-"));
				   					visual.setProImageFile(entry.text("pdImage").toString());
				   					if(!entry.text("offer_id").equals("null")){visual.setOfferId(entry.text("offer_id").toString());}				   					
				   					if(!entry.text("offer_name").equals("")){visual.setOfferName(entry.text("offer_name").toString());}else{visual.setOfferName("null");}
				   					visual.setOfferImage(entry.text("offer_image").toString());
				   					if(!entry.text("offer_discount_type").equals("")){visual.setOfferDiscountType(entry.text("offer_discount_type").toString());}else{visual.setOfferDiscountType("null");}
				   					if(!entry.text("offer_discount_value").equals("")){visual.setOfferDiscountValue(entry.text("offer_discount_value").toString());}else{visual.setOfferDiscountValue("null");}
				   					visual.setOfferButtonName(entry.text("offer_button_name").toString());
									visual.setOfferValidFrom(entry.text("offer_valid_from").toString());
				   					visual.setOfferValidTo(entry.text("offer_valid_to").toString());
				   				
				   					visual.setOfferPurchaseUrl(entry.text("offer_purchase_url").toString());
				   					visual.setOfferIsCalendarBased(entry.text("offer_is_calendar_based").toString());
				   					visual.setProdTapForDetailsImg(entry.text("tapForDetailsImgs").toString());
				   					visual.setProdTapForDetailsImgId(entry.text("tapForDetailsImgId").toString());
				   					visual.setProdTapForDetailsImgPdId(entry.text("tapForDetailsImgPdId").toString());
				   					if(entry.text("x") != "null"){visual.setX(Integer.parseInt(entry.text("x").toString()));}
				   					if(entry.text("y") != "null"){visual.setY(Integer.parseInt(entry.text("y").toString()));}
				   					if(entry.text("animate_on_recognition") != "null"){visual.setAnimateOnRecognition(Integer.parseInt(entry.text("animate_on_recognition").toString()));}
				   					if(entry.text("scale") != "null"){visual.setScale(Float.parseFloat(entry.text("scale").toString()));}
				   					if(entry.text("rotation_x") != "null"){visual.setRotationX(Float.parseFloat(entry.text("rotation_x").toString()));}
				   					if(entry.text("rotation_y") != "null"){visual.setRotationY(Float.parseFloat(entry.text("rotation_y").toString()));}
				   					if(entry.text("rotation_z") != "null"){visual.setRotationZ(Float.parseFloat(entry.text("rotation_z").toString()));}
				   					if(!entry.text("triggerInstruction").equals("null")){visual.setInstruction(entry.text("triggerInstruction").toString());}else{visual.setInstruction("no instuction");}
				   					visual.setCalendarEventStartDate(entry.text("offer_info_event_start").toString());
				   					visual.setCalendarEventEndDate(entry.text("offer_info_event_end").toString());
				   					visual.setCalendarEventAllDay(entry.text("offer_info_event_allday").toString());
				   					visual.setCalendarEventHasAlarm(entry.text("offer_info_event_has_alarm").toString());
				   					visual.setCalendarReminderDays(entry.text("offer_info_reminder_days").toString().replaceAll(",", "_"));
				   					visual.setProductBgColor(entry.text("pdBgColor").toString());
				   					visual.setClientGameId(entry.text("clientGameId").toString());
				   					if(!entry.text("pdHideImage").equals("null")){visual.setProductHideImage(Integer.parseInt(entry.text("pdHideImage")));}
									visual.setProdIsTryOn(Integer.parseInt(entry.text("pd_istryon").toString()));
				   					ArrayList<String> list =  new ArrayList<String>();
				   					List<XmlDom> entriesModel = entry.tags("Model");
				   					if(entriesModel.size() > 1){
				   						for(XmlDom entryModel: entriesModel){
				   							if(entryModel.text("model")!=null){
				   								list.add(entryModel.text("model").toString()+","+entryModel.text("texture").toString()+","+entryModel.text("material").toString()+","+entryModel.text("product").toString());
				   							}		   							
				   							visual.setModelCount(Integer.parseInt(entryModel.text("totalModelCount").toString()));		   							
				   						}
				   					}
				   					
				   					visual.setModel(list); 		   					
				   					//visual.setMarker(marker);
				   					
				   					List<XmlDom> entriesGames = entry.tags("Games");
				   					if(entriesGames.size() > 0){
				   						for(XmlDom entriesGame: entriesGames){
				   							if(entriesGame.text("client_games_details_id")!=null){
				   								visual.setGameImage(entriesGame.text("image").toString());
				   								visual.setGameRules(entriesGame.text("game_rules").toString());
				   								visual.setGameRuleUrl(entriesGame.text("game_rules_url").toString());
				   								visual.setDirectionType(entriesGame.text("direction_type").toString());
				   								
				   								Log.e("setGameRules",entriesGame.text("game_rules").toString());
				   							}		   							
				   						}
				   					}
				   					visuals.add(visual);
				   			    	j++;
				   			    	
				   					if(!arrayTriggerIds.contains(entry.text("trigger_id").toString())){
				   		    			arrayTriggerIds.add(entry.text("trigger_id").toString());
				   		    			arrayTriggerUrls.add(entry.text("triggerUrl").replaceAll(" ", "%20"));
				   		    		}
				   			    }
		   	    	}
		   	  		clientId ="null";
		   		//	FileTransaction file = new FileTransaction();
		              /* if (!mPrefs.getBoolean("is_marker_loaded", false)){
		   				Editor editor = mPrefs.edit();
		   				editor.putBoolean("is_marker_loaded", true);
		   				editor.commit();
		   			}*/
		   	  		   
		               //file.removeXMLNode(mDetectedCosName);
		   			   file.createXML(visuals,arrClientIds,mDetectedCosName);
		               if (visuals != null) {
			   				Visuals existingVisuals = file.getVisuals();
			   				existingVisuals.mergeWith(visuals);	   		   				
			   				file.setVisuals(existingVisuals);
			               }
		                  trackingXMlFlag = true;
		                  metaioFlag = false; 
						
						displayVisuals(""+mDetectedTriggerId, mDetectedCosID, "", 0, "");
		   			   	
       	    		   endnow = android.os.SystemClock.uptimeMillis();
						Log.e("MYTAG Visual Store Complete ", "Excution time: "+(endnow-startnow)/1000+" s");
		             	     		            
			             for(t=0; t<arrayTriggerIds.size(); t++){  	           		
			       			String imageUrl = arrayTriggerUrls.get(t);				
			       			String imageName = imageUrl.substring(imageUrl.lastIndexOf('/') + 1, imageUrl.length());
			       			File target = new File(Constants.Trigger_Location, imageName);			       			
			       			downloadAllMarkers(imageUrl, target, arrayTriggerIds.size());	 
			               }	      		   
		    		}else{
		    			trackingXMlFlag = true;
		    		}
    			
	    		}catch(Exception e){
	    			e.printStackTrace();
	    			String errorMsg = className+" | xml_ajax |   " +e.getMessage();
	    			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
	    		}
	    	}
	    });
	}

	
	long folderSize = 0;
	int imgUrlFileSize=0;

	public void downloadAllMarkers(final String imageUrl, final File target, final int entriesSize)
	{
		aq1.download(imageUrl, target, new AjaxCallback<File>(){		        
		        @Override
				public void callback(String url, File file, AjaxStatus status) {
		        	try{
		                if(file != null){
		                	countTrueFlag++;
		                	countCheckingFlag++;
		                }else{
		                	target.delete();	                	 
		                	downloadAllMarkers(imageUrl,target,entriesSize);
		                	 
		                }	  
		                 if((countTrueFlag)==entriesSize){
		                	   //progressBarVideoNew.setVisibility(View.VISIBLE);
		                	 	countCheckingFlag =0;
		        	    		countTrueFlag=0;
		        	    		countFalseFlag=0;
		        	    		//loadContents(Constants.Trigger_Location+"TrackingData_ClientMarker.xml","image");	 
		        	    		endnow = android.os.SystemClock.uptimeMillis();
								Log.e("MYTAG Download Complete ", "Excution time: "+(endnow-startnow)/1000+" s");
		        	    		new LoadContents().execute();
		                 }
		        	} catch (Exception e){
		        		e.printStackTrace();
	               	 	Log.i("Failed catch", ""+e.getMessage());
	               	    String errorMsg = className+" | downloadAllMarkers |   " +e.getMessage();
		    			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		        	}
		        }		        
		    });
	}
	AQuery aq;
	public void hideInstruction(View v){
		try{
			RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
			rlInstruction.setVisibility(View.GONE);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | hideInstruction |   " +e.getMessage();
 			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}

	protected void unloadLoadedModels() 
	{
		try{	
			 IGeometryVector geometries = metaioSDK.getLoadedGeometries();
			 for (int i=0; i<geometries.size(); i++)
			 {
				 geometries.get(i).setVisible(false);
				 
				 if(geometries != null)
					 metaioSDK.unloadGeometry(geometries.get(i));
			 }
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | unloadLoadedModels |   " +e.getMessage();
 			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}
	
	protected void hideLoadedModels() 
	{
		try{
			 IGeometryVector geometries = metaioSDK.getLoadedGeometries();
			 for (int i=0; i<geometries.size(); i++)
			 {
				 geometries.get(i).setVisible(false);
				
			 }
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | hideLoadedModels |   " +e.getMessage();
 			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
		
	}
	protected void LoadModelsAnimations() 
	{
		try{
			 IGeometryVector geometries = metaioSDK.getLoadedGeometries();
			 for (int i=0; i<geometries.size(); i++)
			 {
				 StringVector animationName = geometries.get(i).getAnimationNames();
					//Log.i("info",animationName.get(0));
					if(!animationName.isEmpty()){
						 geometries.get(i).startAnimation(animationName.get(0),false);
						 geometries.get(i).setAnimationSpeed(96);
					}
			 }		
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | LoadModelsAnimations |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}
	
	
	class LoadContents extends AsyncTask<String, String, String> {		
		@Override
		protected String doInBackground(final String... args) {
			try {	
				//Log.i("MYTAG xml_ajax ", "LoadContents");
				
				 String trackingConfigFile = Constants.Trigger_Location+"TrackingData_ClientMarker.xml";
				 File file = new File(trackingConfigFile);
				 Uri uri =Uri.parse(trackingConfigFile);
				 if(!file.exists()){
					 sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, uri));
				 }
				 if(!Common.isNetworkAvailable(ARDisplayActivity.this)){
					 if (!file.exists()) {
						 trackingConfigFile = AssetsManager.getAssetPath("TrackingData_MarkerlessFast.xml");
					 }
				 }
				trackingXMlFlag = false;
				boolean result = metaioSDK.setTrackingConfiguration(trackingConfigFile); 
				MetaioDebug.log("Tracking data loaded: " + result);
				trackingXMlFlag = true;
				if(result){
					 runOnUiThread(new Runnable() {						
							@Override
							public void run() {			
								if(progressBarVideoNew.getVisibility()==View.VISIBLE){
									progressBarVideoNew.setVisibility(View.INVISIBLE);
								}
								 //Log.e("mDetectedTriggerId",""+mDetectedTriggerId);
					   			   if(mDetectedTriggerId != 0){
						   				rlayCameraView = (RelativeLayout) findViewById(R.id.camera_view);
										if(rlayCameraView!=null){
											rlayCameraView.setBackgroundResource(0);
											findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
											findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
										}
						   				  // displayVisuals(""+mDetectedTriggerId, mDetectedCosID, "", 0, "");
					   			   }					   			   
								endnow = android.os.SystemClock.uptimeMillis();
								Log.e("MYTAG Tracking File ", "Excution time: "+(endnow-startnow)/1000+" s");
							}
						});
				}
			} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | LoadTrackingFile | doInBackground |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			}
			return null;			
			
		}

		/*@Override
		protected void onPostExecute(String result) {
			// dismiss the dialog once done
			try {
				 runOnUiThread(new Runnable() {						
						@Override
						public void run() {			
							if(progressBarVideoNew.getVisibility()==View.VISIBLE){
								progressBarVideoNew.setVisibility(View.INVISIBLE);
							}
							endnow = android.os.SystemClock.uptimeMillis();
							Log.i("MYTAG xml_ajax ", "Excution time: "+(endnow-startnow)/1000+" s");
						}
					});
				
			} catch (Exception e) {
				e.printStackTrace();				
				String errorMsg = className+" | LoadTrackingFile | onPostExecute |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				
			}
		}*/
	}
	
/*	protected void loadContents(String trackingConfigFile,String type) 
	{
		try
		{
			 mMarkerType = type;
			 File file = new File(trackingConfigFile);
			 Uri uri =Uri.parse(trackingConfigFile);
			 if(!file.exists()){
				 sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, uri));
			 }
			 if(!Common.isNetworkAvailable(this)){
				 if (!file.exists()) {
					 trackingConfigFile = AssetsManager.getAssetPath("TrackingData_MarkerlessFast.xml");
				 }
			 }
			boolean result = metaioSDK.setTrackingConfiguration(trackingConfigFile); 
			MetaioDebug.log("Tracking data loaded: " + result);
			if(result){
				 runOnUiThread(new Runnable() {						
					@Override
					public void run() {			
						if(progressBarVideoNew.getVisibility()==View.VISIBLE){
							progressBarVideoNew.setVisibility(View.INVISIBLE);
						}
						endnow = android.os.SystemClock.uptimeMillis();
						// Log.e("MYTAG xml_ajax ", "Excution time: "+(endnow-startnow)/1000+" s");
					}
				});
			}
		}catch (Exception e){
			 runOnUiThread(new Runnable() {					
				@Override
				public void run() {
					Toast.makeText(getApplicationContext(),"Error: ARDisplayActivity loadContents", Toast.LENGTH_LONG).show();
				}
			});			
			e.printStackTrace();
			String errorMsg = className+" | loadContents |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}*/
	
	ArrayList<String> stringArrayList;
	String [] stringArray = {};
	protected void onGeometryTouched(IGeometry geometry) {		
		try{
			String Product[] = geometry.getName().split(",");
			afterScanningMarkerInfo(Product);
		}catch (Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onGeometryTouched |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}		
	}
	private void afterScanningMarkerInfo(String[] Product) {
		try{			
			if(Product[0].equalsIgnoreCase("3DMODEL")){
				LoadModelsAnimations();				
			}else{
				if(!Product[1].equals("")){
					String getProImageFile = Product[1];
					String getClientId = Product[2];
					String getClientImageName = Product[3];
					String getProductPrice = Product[4];
					String getProductId = Product[5];
					String getProductName = Product[6];
					String getClientLogo = Product[7];
					String getClientBackgroundImage = Product[8].toString().replaceAll(" ", "%20");
					String getClientBackgroundColor = "ff2600";
					//Log.i("getClientBackgroundColor", ""+getClientBackgroundColor);
					if(!Product[9].equals("null")){
						getClientBackgroundColor = Product[9];
					}
					String getProductShortDesc = "";
					if(Product[10]!="null"){
						getProductShortDesc = Product[10].toString();
					}
					String getTapForDetailsId = Product[12].toString();
					String getTapForDetailsPdId = Product[13].toString();
					String getProductBgColor = Product[22].toString();
					String getProductHideImage = Product[23].toString();
					String getOfferPurchaseUrl = Product[24].toString();
					String getClientVertical = Product[25].toString();
					String getProductButtonName = Product[26].toString();
					String getOfferButtonName = Product[27].toString();
					int getProdIsTryOn = Integer.parseInt(Product[28].toString());
					String getClientLigtColor = Product[29].toString();
					String getClientDarkColor = Product[30].toString();

					String getClientUrl = "";
					if(Product[14]!="null"){
						getClientUrl = Product[14].toString();
					}
					String getProductUrl = "";
					if(Product[15]!="null"){
						getProductUrl = Product[15].toString();
					}
					stringArrayList = new ArrayList<String>();
	    			stringArrayList.add(getClientId);
	    			stringArrayList.add(getClientLogo);
	    			stringArrayList.add(getClientImageName);
	    			stringArrayList.add(getClientBackgroundImage);
	    			stringArrayList.add(getClientBackgroundColor);
	    			stringArrayList.add(getClientUrl);
					stringArrayList.add(getProductId);
	    			stringArrayList.add(getProductName);
	    			stringArrayList.add(getProductPrice);
	    			stringArrayList.add(getProductShortDesc);
	    			stringArrayList.add(getTapForDetailsId);
	    			stringArrayList.add(getTapForDetailsPdId);
	    			stringArrayList.add(getProductUrl);
	    			stringArrayList.add(getProductBgColor);
	    			stringArrayList.add(getProductHideImage);
	    			stringArrayList.add(getProductButtonName);
	    			stringArrayList.add(getOfferButtonName);
	    			stringArrayList.add(""+getProdIsTryOn);
	    			stringArrayList.add(getClientLigtColor);
	    			stringArrayList.add(getClientDarkColor);
	    			stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
					session.createClientProductValuesInSession(stringArray);
					new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);

					strArrayForMarkerInfo = new ArrayList<String>();
					strArrayForMarkerInfo.add("");
					strArrayForMarkerInfo.add(Product[31].toString());
					strArrayForMarkerInfo.add(getClientImageName);
					strArrayForMarkerInfo.add(Product[32].toString());
					strArrayForMarkerInfo.add(Product[33].toString());
					strArrayForMarkerInfo.add(getProductId);	
					strArrayForMarkerInfo.add(getProductName);	
					strArrayForMarkerInfo.add(Product[16].toString());		
					strArrayForMarkerInfo.add(Product[34].toString());
					storedRecentlyScannedInfo(strArrayForMarkerInfo);
					
					Intent intent; 
					if(getClientVertical.equals("1")){
						RelativeLayout rlInstruction = (RelativeLayout)findViewById(R.id.rlInstruction);
                        rlInstruction.setVisibility(View.INVISIBLE);
                        
                        trackingFlag = false;
						intent = new Intent(getApplicationContext(), FinancialActivity.class);
						intent.putExtra("productId", getProductId);
						intent.putExtra("clientId", getClientId);
						intent.putExtra("discriminator",Product[0]);
						intent.putExtra("buttonName", getProductButtonName);
						intent.putExtra("buttonUrl", getProductUrl);
						intent.putExtra("productPrice", getProductPrice);
						intent.putExtra("offerTitle", getProductName);
					}else{
						trackingFlag = false;
						intent = new Intent(getApplicationContext(), ProductList.class);
					}
					intent.putExtra("product_image_url",getProImageFile);
					startActivityForResult(intent, 1);
					overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
						
				} else
				{
					Toast.makeText(getApplicationContext(),"No Products Available", Toast.LENGTH_LONG).show();
				}
			}			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onGeometryTouched | afterScanningMarkerInfo | " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}
	protected IMetaioSDKCallback getMetaioSDKCallbackHandler() {
		return mCallbackHandler;
	}
	
	private double roundThreeDecimalsCommon(double d)
	{
	    DecimalFormat twoDForm = new DecimalFormat("#.###");
	    return Double.valueOf(twoDForm.format(d));
	}
		
	private void playVideo(final String path,final int x, final int y, final String strFlag) {
        try {
        	if(Common.isNetworkAvailable(ARDisplayActivity.this)){        		
        		if(!strFlag.equals("")){
        		 mVideoView = new VideoView(ARDisplayActivity.this);
				 rlayout = (RelativeLayout) findViewById(R.id.camera_view);
        		}
		         rlayout.removeView(mVideoView);
		    				
					RelativeLayout.LayoutParams rlpForVideoClose = (RelativeLayout.LayoutParams) close.getLayoutParams();
					rlpForVideoClose.width = LayoutParams.WRAP_CONTENT;
					rlpForVideoClose.height = LayoutParams.WRAP_CONTENT;
				    mVideoView.setId(1);
					rlayout.addView(mVideoView);
					rlpForVideoClose.addRule(RelativeLayout.RIGHT_OF, mVideoView.getId());
					
				    RelativeLayout.LayoutParams rlpForVideo = (RelativeLayout.LayoutParams) mVideoView.getLayoutParams();				    
					rlpForVideo.width = (int) (0.834 * Common.sessionDeviceWidth);
					rlpForVideo.height = (int) (0.36 * Common.sessionDeviceHeight);
						
					if(x == 0 && y==0){
						rlpForVideo.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
						rlpForVideo.addRule(RelativeLayout.CENTER_VERTICAL, RelativeLayout.TRUE);
						rlpForVideo.height = (int) (0.36 * Common.sessionDeviceHeight);
						rlpForVideo.leftMargin = x;
						rlpForVideo.topMargin = y;
						rlpForVideo.rightMargin = 0;
						rlpForVideo.bottomMargin = 0;	
		      		  	rlpForVideoClose.topMargin = (int) (((0.4 * Common.sessionDeviceHeight) + y) - (0.041 * Common.sessionDeviceHeight));
		
		      		  	
					} else if(x == 0 && y!=0){
						rlpForVideo.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
						rlpForVideo.addRule(RelativeLayout.CENTER_VERTICAL, 0);
						rlpForVideo.leftMargin = 0;
						rlpForVideo.topMargin = (int) ((0.4 * Common.sessionDeviceHeight) + y);							    		
						rlpForVideo.rightMargin = 0;
						rlpForVideo.bottomMargin = 0;
		      		  	rlpForVideoClose.topMargin = (int) (((0.4 * Common.sessionDeviceHeight) + y) - (0.041 * Common.sessionDeviceHeight));
					} else {
						rlpForVideo.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
						rlpForVideo.addRule(RelativeLayout.CENTER_VERTICAL, 0);
						rlpForVideo.leftMargin = 0;
						rlpForVideo.topMargin = (int) ((0.4 * Common.sessionDeviceHeight) + y);
						rlpForVideo.rightMargin = 0;
						rlpForVideo.bottomMargin = 0;	
					 	rlpForVideoClose.topMargin = (int) (((0.4 * Common.sessionDeviceHeight) + y) - (0.041 * Common.sessionDeviceHeight));
					}
				
					close.setLayoutParams(rlpForVideoClose);				
					if (path == null || path.length() == 0) {
		                Toast.makeText(getApplicationContext(), "File URL/path is empty", Toast.LENGTH_LONG).show();  
		                if(x == 0 && y==0){
		                    progressBarVideoNew.setVisibility(View.INVISIBLE);   
		                } else {
		                    progressBarVideoNew.setVisibility(View.INVISIBLE);                	
		                }
					} else {
						getWindow().setFormat(PixelFormat.TRANSLUCENT);
		                mVideoView.setMediaController(md);
		        	    mVideoView.setVisibility(View.VISIBLE);
					    mVideoView.setZOrderOnTop(true);
		                mVideoView.setVideoPath(path); 
		                mVideoView.start();
						mVideoView.requestFocus();				
		                mVideoView.setOnPreparedListener(new OnPreparedListener() {
		                    @Override
							public void onPrepared(MediaPlayer arg0) {
		                        mVideoView.bringToFront();
		                        mVideoView.requestFocus();
		                        mVideoView.start();  
		                        if(x == 0 && y==0){
		                        	progressBarVideoNew.setVisibility(View.INVISIBLE);	                	
		                        } else {
		                        	progressBarVideoNew.setVisibility(View.INVISIBLE);                	
		                        }
		                        if(mVideoView.isPlaying()){
		                        	close.setVisibility(View.VISIBLE);
		                        }
		                        arg0.setOnVideoSizeChangedListener(new OnVideoSizeChangedListener() { 
		                            @Override
		                            public void onVideoSizeChanged(MediaPlayer mp, int width, int height) {
		                                    md = new MediaController(ARDisplayActivity.this);
		                                    mVideoView.setMediaController(md);
		                                    md.setAnchorView(mVideoView);
		                                }
		                            });		                        
		                    }
		                   
		                });
		                mVideoView.setOnCompletionListener(new OnCompletionListener() {					
							@Override
							public void onCompletion(MediaPlayer mp) {
								mVideoView.setVisibility(View.GONE);
								rlayout.removeView(mVideoView);
								close.setVisibility(View.GONE);
							  if(x == 0 && y==0){
				                	progressBarVideoNew.setVisibility(View.INVISIBLE);	
				                } else {
				                    progressBarVideoNew.setVisibility(View.INVISIBLE);                	
				                }
							}
						});            
					}
            	}else{
        			new Common().instructionBox(ARDisplayActivity.this, R.string.title_case7, R.string.instruction_case7);
        		}
        } catch (Exception e) {
            e.printStackTrace();
            String errorMsg = className+" | playVideo |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
        }
    }

	public void closeVideo(View v){
		try{			
			rlayout.removeView(mVideoView);
			close.setVisibility(View.GONE);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | playVideo | closeVideo |  " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}       
	}

	String offerId = "null";
	final class MetaioSDKCallbackHandler extends IMetaioSDKCallback
	{
		int count=0;
		@Override
		public void onAnimationEnd(final IGeometry geometry, String animationName) 
		{	
			try{
			count++;			
			final String Product[] = geometry.getName().split(",");			
			if(!Product[1].equals("")){
				String getProImageFile = Product[1];
				String getClientId = Product[2];
				String getClientImageName = Product[3];
				String getProductPrice = Product[4];
				String getProductId = Product[5];
				String getProductName = Product[6];
				String getClientLogo = Product[7];
				String getClientBackgroundImage = Product[8].toString().replaceAll(" ", "%20");
				String getClientBackgroundColor = "ff2600";
				if(!Product[9].equals("null")){
					getClientBackgroundColor = Product[9];
				}
				
				String getProductShortDesc = "";
				if(!Product[10].equals("null")){
					getProductShortDesc = Product[10].toString();
				}
				String getTapForDetailsId = Product[12].toString();
				String getTapForDetailsPdId = Product[13].toString();

    			String getProductBgColor = Product[22].toString();
				String getProductHideImage = Product[23].toString();
				String getOfferPurchaseUrl = Product[24].toString();
				String getClientVertical = Product[25].toString();
				String getProductButtonName = Product[26].toString();
				String getOfferButtonName = Product[27].toString();
				int getProdIsTryOn = Integer.parseInt(Product[28].toString());
				String getClientLigtColor = Product[29].toString();
				String getClientDarkColor = Product[30].toString();
			
				String getClientUrl = "";
				if(!Product[14].equals("null")){
					getClientUrl = Product[14].toString();
				}
				//Log.i("getClientUrl", ""+getClientUrl);
				String getProductUrl = "";
				if(Product[15]!="null"){
					getProductUrl = Product[15].toString();
				}
				if(!Product[16].equals("null")){
					offerId = Product[16].toString();
				}
				stringArrayList = new ArrayList<String>();
    			stringArrayList.add(getClientId);
    			stringArrayList.add(getClientLogo);
    			stringArrayList.add(getClientImageName);
    			stringArrayList.add(getClientBackgroundImage);
    			stringArrayList.add(getClientBackgroundColor);
    			stringArrayList.add(getClientUrl);
				stringArrayList.add(getProductId);
    			stringArrayList.add(getProductName);
    			stringArrayList.add(getProductPrice);
    			stringArrayList.add(getProductShortDesc);
    			stringArrayList.add(getTapForDetailsId);
    			stringArrayList.add(getTapForDetailsPdId);
    			stringArrayList.add(getProductUrl);    			
    			stringArrayList.add(getProductBgColor);
    			stringArrayList.add(getProductHideImage);
    			stringArrayList.add(getProductButtonName);
    			stringArrayList.add(getOfferButtonName);
    			stringArrayList.add(""+getProdIsTryOn);
    			stringArrayList.add(getClientLigtColor);
    			stringArrayList.add(getClientDarkColor);
    			
				stringArray = stringArrayList.toArray(new String[stringArrayList.size()]);
				session.createClientProductValuesInSession(stringArray);
				new Common().getStoredClientProductSessionValues(ARDisplayActivity.this);
				
				if( Integer.parseInt(Product[11]) == count){
					if(!getProductId.equals("0") && offerId.equals("null")){
						runOnUiThread(new Runnable() {						
							@Override
							public void run() {					
								progressBarVideoNew.setVisibility(View.INVISIBLE);
							}
						});
						RelativeLayout rlayCameraView1 = (RelativeLayout) findViewById(R.id.camera_view); 
				    	rlayCameraView1.setBackgroundResource(0);
				   		findViewById(R.id.imgvGoToMyCloset).setVisibility(View.INVISIBLE);
						findViewById(R.id.imgvMyOffers).setVisibility(View.INVISIBLE);
						findViewById(R.id.imgvInfoIcon).setVisibility(View.INVISIBLE);
						findViewById(R.id.imgvShopparVision).setVisibility(View.INVISIBLE);
						findViewById(R.id.imgvShopNow).setVisibility(View.INVISIBLE);
						trackingFlag = false;
						Intent intent = new Intent(getApplicationContext(), ProductList.class);
						intent.putExtra("product_image_url",getProImageFile);
						startActivityForResult(intent, 1);
						overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
						getProductId = "0";
						count = 0;
					}
					else if(!offerId.equals("null") && getProductId.equals("0")){
						runOnUiThread(new Runnable() {						
							@Override
							public void run() {					
								progressBarVideoNew.setVisibility(View.INVISIBLE);
							}
						});
						Intent intent = new Intent(getApplicationContext(), OfferViewActivity.class);		
				    	intent.putExtra("imagename", getProImageFile);
				    	intent.putExtra("clientId", getClientId);
						intent.putExtra("clientLogo", getClientLogo);
						intent.putExtra("clientBackgroundImage", getClientBackgroundImage);
						intent.putExtra("clientBackgroundColor", getClientBackgroundColor);
						intent.putExtra("clientBackgroundLightColor", getClientLigtColor);
						intent.putExtra("clientBackgroundDarkColor", getClientDarkColor);
						//Log.i("productId", ""+productId);
						intent.putExtra("offerId", offerId);
						intent.putExtra("clientUrl", getClientUrl);
						intent.putExtra("productUrl", getProductUrl);
						intent.putExtra("offerPurchaseUrl", getOfferPurchaseUrl);
						
						offerId = "null";
	    				int requestCode = 1;
						count = 0;
						startActivityForResult(intent, requestCode);						
					}
				}
			} else
			{
				runOnUiThread(new Runnable() {
					@Override							
					public void run() {	
						Toast.makeText(getApplicationContext(),"No Products/Offers Available.", Toast.LENGTH_LONG).show();						
						progressBarVideoNew.setVisibility(View.INVISIBLE);	
					}
				});
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onAnimationEnd |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}	

	}
	int countForDialog = 0;
	ProgressBar progressBarNew;
	public void triggerModel(int mdetCosId,String cosName){
		try{
				//FileTransaction file = new FileTransaction();
				final Visuals visuals = file.getVisuals();
				final List<Visual> triggerVisual = visuals.getVisualByTriggerId(Integer.parseInt(cosName));
				if(triggerVisual != null){
					for ( final Visual visual : triggerVisual) {
						if(visual.getDiscriminator().equalsIgnoreCase("3DMODEL")){
				    	if(visual.getModel().size() >0){
				    		modelArray = visual.getModel();		
			    			for(int i = 0; i < visual.getModel().size(); i++) {								
							
								  final String[] modelFileArray = visual.getModel().get(i).split(",");
								  modelName = modelFileArray[0].substring(modelFileArray[0].lastIndexOf('/') + 1, modelFileArray[0].length());	    						
								  textureName = modelFileArray[1].substring(modelFileArray[1].lastIndexOf('/') + 1, modelFileArray[1].length());
						
								  String mdModel = Constants.Model_Location+modelName;	
								  File target = new File(Constants.Model_Location,modelName);
			    		    	  String mdTexture = Constants.Model_Location+textureName;	
			    		    	  File textureFile = new File(Constants.Model_Location,textureName);
			    		    	  
			    		    	  
			    		    	  if (target.exists()){
			    		    		  try{
			    						IGeometry geometry = null;
			    						geometry = metaioSDK.createGeometry(mdModel);
			    						
			    						if(geometry != null){
			    							// Set geometry properties
			    							 if (textureFile.exists()){				    								
			    								 geometry.setTexture(mdTexture);
			    							 }
			    							
			    							if(visual.getScale() != 0){
			    								geometry.setScale(new Vector3d(visual.getScale(),visual.getScale(),visual.getScale()));
			    							}
			    							geometry.setTranslation(new Vector3d(visual.getX(),visual.getY(),0));
			    							geometry.setRotation(new Rotation(visual.getRotationX(), visual.getRotationY(), visual.getRotationZ()));							
			    							geometry.setCoordinateSystemID(mDetectedCosID);
			    							//Log.i("visual.getProImageFile() 3D fun", ""+visual.getProImageFile()+" "+visual.getOfferImage());
			    							String visualImageName = "";
			    							if(visual.getProImageFile().equals("")){
			    								visualImageName = visual.getOfferImage();
			    							} else {
			    								visualImageName = visual.getProImageFile();
			    							}
			    							//Log.i("visualImageName", ""+visual.getProductPrice());
			    							geometry.setName(visual.getDiscriminator()+","
													+ visualImageName + ","
													+ visual.getClientId() + ","
													+ visual.getImageName() + ","
													+ visual.getProductPrice() + ","
													+ visual.getProductId() + ","
													+ visual.getProductName() + ","
													+ visual.getClientLogo() + ","
													+ visual.getClientBackgroundImage() + ","
													+ visual.getClientBackgroundColor() + "," 
													+ visual.getProductShortDesc() +","
													+ visual.getModel().size() + "," 
													+ visual.getProdTapForDetailsImgId() + "," 
													+ visual.getProdTapForDetailsImgPdId() + "," 
													+ visual.getClientUrl().toString() + "," 
													+ visual.getProductUrl().toString() +","
													+ visual.getOfferId() + ","
													+ visual.getCalendarEventStartDate() + ","
													+ visual.getCalendarEventEndDate() + ","
													+ visual.getCalendarEventAllDay() + ","
													+ visual.getCalendarEventHasAlarm() + ","
													+ visual.getCalendarReminderDays()+ ","
													+ visual.getProductBgColor()+ ","
													+ visual.getProductHideImage()+ ","
													+ visual.getOfferPurchaseUrl()+","
													+ visual.getClientVerticalId()+","
													+ visual.getProductButtonName()+","
													+ visual.getOfferButtonName()+","
													+ visual.getProdIsTryOn()+","
													+ visual.getClientLightColor()+","
													+ visual.getClientDarkColor()+","
													+ visual.getTriggerId()+","
													+ visual.getTitle()+","
													+ mdetCosId+","
													+ visual.getOfferName());
			    							
			    							StringVector animationName = geometry.getAnimationNames();				    							
			    							if(visual.getAnimateOnRecognition() == 1){
				    							if(!animationName.isEmpty()){
				    								geometry.startAnimation(animationName.get(0),false);
				    								geometry.setAnimationSpeed(96);
			    								}
			    							}
			    							flag= false;
			    							mdownload = false;
			    							afterCompleteDoInBg = "";
			    						}
			    						else
			    							MetaioDebug.log(Log.ERROR, "Error loading geometry: "+mdModel);    							
			    						
			    		    		  }catch(Exception e){
			    		    			  e.printStackTrace();
			    		    		  }
			    					}
			    		    	  countForDialog++;
			    		    	  if(countForDialog == visual.getModel().size()){
			    		    		  countForDialog=0;
			    		    	  }
			    				}
				    	}
					}
			}
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | triggerModel |   " +e.getMessage();
			Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}
	int countModelFile =0;
	int countCheckingModelFlag =0;
	int countTrueModelFlag = 0;
	int countFalseModelFlag = 0;
	public void downloadModels(final String imageUrl, final File target, final int entriesSize)
	{
		if(target.exists()==false ){		
		    aq1.download(imageUrl, target, new AjaxCallback<File>(){		        
		        @Override
				public void callback(String url, File file, AjaxStatus status) {
		        	try{
		                if(file != null){		                
		                	countTrueModelFlag++;
		                	countCheckingModelFlag++;
		                }else{
		                	Log.i("Failed", ""+status);
		                	target.delete();	                	 
		                	downloadModels(imageUrl,target,entriesSize);
		                	 
		                }	  
		                 if((countTrueModelFlag)==entriesSize){
		                	 countCheckingModelFlag =0;
		    				countTrueModelFlag=0;
		    				countFalseModelFlag=0;
		    				afterCompleteDoInBg = "true";
		                 }
		        	} catch (Exception e){
		        		e.printStackTrace();	               	 	
	               	 	String errorMsg = className+" | downloadModels |   " +e.getMessage();
	               	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		        	}
		        }		        
		    });
		}else if(target.exists()==true){
			countTrueModelFlag++;    
			countCheckingModelFlag++;			
		}else if(target.exists()==true && countCheckingModelFlag==entriesSize){			 
			 	countCheckingModelFlag =0;
				countTrueModelFlag=0;
				countFalseModelFlag=0;
				afterCompleteDoInBg = "true";
		 }

	}
	
	class LoadModel extends AsyncTask<String, String, String> {
		@Override
		protected void onPreExecute() {
			try {
				downloadModelProcess ="true";
				runOnUiThread(new Runnable() {
					@Override
					public void run() {
						progressBarVideoNew.setVisibility(View.VISIBLE);					
					}
				});
				super.onPreExecute();
			} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | LoadModel | onPreExecute |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);				
			}
		}

		@Override
		protected String doInBackground(final String... args) {

			try {				
				if (modelArray.size() > 0) {					
					int l=0;
					for (int i = 0; i < modelArray.size(); i++) {
						final String[] modelFileArray = modelArray.get(i)
								.split(",");
						for (int k = 0; k <= 2; k++) {
							if (!modelFileArray[k].toString().equals("null")) {
								String filename = modelFileArray[k].substring(
										modelFileArray[k].lastIndexOf('/') + 1,
										modelFileArray[k].length());
								File target = new File(
										Constants.Model_Location, filename);
								
								if(!target.exists()){
									try {
										if(Common.isNetworkAvailable(ARDisplayActivity.this)){	
							        	URLConnection urlConnection;
										urlConnection = new URL(modelFileArray[k]).openConnection();
										urlConnection.connect();	       
									    imgUrlFileSize = Integer.parseInt(String.valueOf(urlConnection.getContentLength()/1024));
									    folderSize = folderSize +imgUrlFileSize;
										}
										
									} catch (MalformedURLException e1) {
										e1.printStackTrace();
									} catch (IOException e1) {
										e1.printStackTrace();
									}
									
								}else{									
									l++;
								}
								if(Common.isNetworkAvailable(ARDisplayActivity.this))					    			
									downloadModels(modelFileArray[k],target, modelArrayCount);	
								else{
									if(l == modelArrayCount){
									afterCompleteDoInBg = "true";
									}
								}
								if(l == modelArrayCount){
									countCheckingModelFlag =0;
									countTrueModelFlag=0;
									countFalseModelFlag=0;
									afterCompleteDoInBg = "true";
									
								}
								
							}
						}
					}				
					
				}
				downloadModelProcess = "false";
			} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | LoadModel | doInBackground |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);				
			}			
			return afterCompleteDoInBg;
		}

		@Override
		protected void onPostExecute(String result) {
			// dismiss the dialog once done
			try {
				super.onPostExecute(result);
				runOnUiThread(new Runnable() {
					@Override
					public void run() {
						progressBarVideoNew.setVisibility(View.INVISIBLE);
						mdownload = true;
					}
				});
			} catch (Exception e) {
				e.printStackTrace();				
				String errorMsg = className+" | LoadModel | onPostExecute |  " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				
			}
		}
	}
	public void xml_client_marker_ajax(){  
		
		runOnUiThread(new Runnable() {
			@Override
			public void run() {
				try {
					
					final Handler handler = new Handler();
			        handler.postDelayed(new Runnable() {
			            @Override
			            public void run() {
			            	if(mIntroView !=null){
		        	    		if (mIntroView.getVisibility() == View.VISIBLE) 
		        	    			mIntroView.setVisibility(View.GONE);
		        	    		if(!session.isLoggedIn() && flagForLogin==false){
	    							flagForLogin = true;
	    							new Common().getLoginDialog(ARDisplayActivity.this, ARDisplayActivity.class, "ARDisplay", new ArrayList<String>());
	    							MainActivity.mendnow = android.os.SystemClock.uptimeMillis();
	    							//Log.e("MYTAG main intro ", "Excution time: "+(MainActivity.mendnow-MainActivity.mstartnow)/1000+" s");
	    						}
		        	    		
	        	    		}
			            }
			        }, 10000);

				} catch (Exception e) {
					e.printStackTrace();
					String errorMsg = className+" | xml_client_marker_ajax | runOnUiThread |  " +e.getMessage();
	           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
				}
			}
             					
		});
	    String url = Constants.Client_Xml_URL;  
	    if(Common.isNetworkAvailable(ARDisplayActivity.this)){	    
	    	startnow = android.os.SystemClock.uptimeMillis();
			 //Log.e("MYTAG startnow ", "start time: "+(endnow-startnow)/1000+" s");
	    	 AQuery aquery = new AQuery(ARDisplayActivity.this);
	 	    aquery.ajax(url, XmlDom.class, this, "clientResultFromServer");
	 	   
	    }else{
	    	
	    	new LoadContents().execute();
		    /*File file = new File(Constants.Trigger_Location+"TrackingData_ClientMarker.xml");
		    if(!file.exists()){
		    	//Toast.makeText(getApplicationContext(), "You don't have Internet Connection. Please try agian!", Toast.LENGTH_LONG).show();
		    	//progressBarVideo.setVisibility(View.INVISIBLE);
		    }else{
		    	loadContents(Constants.Trigger_Location+"TrackingData_ClientMarker.xml", "image");
		    }*/
	    }
	}
	
	public void clientResultFromServer(String url, XmlDom xml, AjaxStatus status){
	  	try { 
	  		if(xml!=null){
	  			endnow = android.os.SystemClock.uptimeMillis();
	  			 //Log.e("MYTAG endnow ", "Excution time: "+(endnow-startnow)/1000+" s");
	  			//Log.e("MYTAG xml ", "xml: "+xml);
	  			arrayClientMarkerIds =  new ArrayList<String>();
	  			arrayTriggerImage = new ArrayList<String>();
			    final List<XmlDom> entries = xml.tags("trigger");	
				//FileTransaction file = new FileTransaction();
				Triggers triggers = file.getClientTriggers();			
	        	Triggers newTriggers = new Triggers();
	        	int index =0;
				int j=0;		
				boolean triggerStatusFlag = false;
				
	        	if(entries.size()>0)
		    	{
	        		if(triggers.size() == 0){
	        			
		        		 j=index;
						 for(XmlDom entry: entries){		
							
							    arrayClientMarkerIds.add(entry.text("id").toString());
						    	arrayTriggerImage.add(entry.text("clientImage").toString());
						    	if(!arraySessionClientImages.contains(entry.text("clientImage").toString())){
						    		j++;
							    	ClientTriggers clientTriggers =  new ClientTriggers(j);
									clientTriggers.setId(Integer.parseInt(entry.text("id").toString())+90000);		    
									clientTriggers.setClientId(entry.text("client_id").toString());
									clientTriggers.setTriggerId(entry.text("trigger_id").toString());
									clientTriggers.setClientImage(entry.text("clientImage").toString());
									newTriggers.add(clientTriggers);
									index = j;
						    	}
						 }
						 triggerStatusFlag =true;
					     file.setClientTriggers(newTriggers);
						 file.createClientXML();
					
			    	}else if (entries.size()> triggers.size()){			    		
			    		changeLogResultFromServer();
		    			j=triggers.size();
		    			List<ClientTriggers> clTrigger = triggers.getAllTrigger();
		    			for (ClientTriggers c : clTrigger) { 				    			   
					    	arrayTriggerClientIds.add(""+c.getId());				    			 
		    		   }
		    			for(XmlDom entry: entries){		
		    				int id = Integer.parseInt(entry.text("id").toString())+90000;
		    				if(id == 90006)
		    				{
		    					 masterClientId = entry.text("client_id").toString();
		    				}
		    				if(!arrayTriggerClientIds.contains(""+id)){
					    		j++;
					    		arrayClientMarkerIds.add(entry.text("id").toString());
					    		arrayTriggerImage.add(entry.text("clientImage").toString());
					    				    			   
						    	ClientTriggers clientTriggers =  new ClientTriggers(j);
								clientTriggers.setId(Integer.parseInt(entry.text("id").toString())+90000);		    
								clientTriggers.setClientId(entry.text("client_id").toString());
								clientTriggers.setClientImage(entry.text("clientImage").toString());
								newTriggers.add(clientTriggers);
								index = j;
								ClientTriggers masterTrigger = triggers.getClientDetails(90006);
								if(masterClientId != null)
									masterTrigger.setClientId(masterClientId);
								
			    			   }
		    			}
		    			triggerStatusFlag =true;
		   				triggers.mergeWithTriggers(newTriggers);
		   				file.setClientTriggers(triggers);
		    			file.updateClientXML(newTriggers);	    		
		    	}else{
		    			
		    		changeLogResultFromServer();
		    		if(mSurfaceView==null){
		    			mSurfaceView = new MetaioSurfaceView(this);
		    			mSurfaceView.registerCallback(this);
		    			mSurfaceView.setKeepScreenOn(true);
		    			mSurfaceView.setOnTouchListener(this);
	
		    			MetaioDebug.log("ARViewActivity.onStart: addContentView(mMetaioSurfaceView)");
		    			FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT);
		    			addContentView(mSurfaceView, params);
		    			mSurfaceView.setZOrderMediaOverlay(true);
	
		    			//loadContents(Constants.Trigger_Location+"TrackingData_ClientMarker.xml","image");
		    			new LoadContents().execute();
		    			
		    		} else {
		    			new LoadContents().execute();
		    			/*final Handler handler = new Handler();
				        handler.postDelayed(new Runnable() {
				            @Override
				            public void run() {
				            	//loadContents(Constants.Trigger_Location+"TrackingData_ClientMarker.xml","image");
				            	new LoadContents().execute();
				            }
				        }, 3000);*/
		    		}
				}    
	        		if(triggerStatusFlag){        		
						/* if (!mPrefs.getBoolean("is_marker_loaded", false)){
								Editor editor = mPrefs.edit();
								editor.putBoolean("is_marker_loaded", true);
								editor.commit();
							}*/
						 File theDir = new File(Constants.Trigger_Location);
			             
			               // if the directory does not exist, create it
			               if (!theDir.exists()) {
			                 theDir.mkdir();  
			               }
					    	for(t=0; t<arrayClientMarkerIds.size(); t++){	        	
								String imageUrl = arrayTriggerImage.get(t);	
					            //Log.i("imageUrl",""+imageUrl);			
								String imageName = imageUrl.substring(imageUrl.lastIndexOf('/') + 1, imageUrl.length());
								File target = new File(Constants.Trigger_Location, imageName);
								if(t== arrayClientMarkerIds.size()-1){
								if(!target.exists()){
									try {
							        	URLConnection urlConnection;
										urlConnection = new URL(imageUrl).openConnection();
										urlConnection.connect();	       
									   } catch (MalformedURLException e1) {
										e1.printStackTrace();
									} catch (IOException e1) {
										e1.printStackTrace();
									}catch (Exception e1) {
										e1.printStackTrace();
									}									
									}
								}
								downloadAllMarkers(imageUrl, target, arrayClientMarkerIds.size());
							
					    	}
					    	
	        		}
				}
		  	}
	  	} catch (Exception e) {
			e.printStackTrace();
			String errorMsg = className+" | clientResultFromServer |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
	}
	/*public void changeLogResultFromServer(){
	  	try { 
	  		new Common().storeChangeLogResultFromServer(ARDisplayActivity.this);	
	  		if(Common.isActive){
	  		ChangeLogModel changeLogModelData = file.getChangeLog();		
			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
			if(userChangeLogList.size() > 0){
				Date currentDate;
				Date d = Calendar.getInstance().getTime(); // Current time
				SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); // Set your date format
				String date = sdf.format(d);
				if(!Common.sessionForUserAppOpenDate.equals("null"))
					currentDate = sdf.parse(Common.sessionForUserAppOpenDate);
				else
					currentDate = sdf.parse(date);
			for(UserChangeLog userChangeLog : userChangeLogList){
				Log.e("userChangeLogList",""+userChangeLog.getClientId()+"triggerId"+userChangeLog.getTriggerId());
				if(userChangeLog.getClientId() !=0 || userChangeLog.getTriggerId() != 0 ){		    				 
					resetTriggerFlag = true;
				}
				if(currentDate.getTime() >  sdf.parse(userChangeLog.getCreatedDate()).getTime()){
					if(userChangeLog.getClientId()== 0 &&
							userChangeLog.getTriggerId() == 0 && 
							userChangeLog.getOfferId() ==0 && 
							userChangeLog.getProdtId()==0 &&
							userChangeLog.getTriggerVisualId()==0
							&& userChangeLog.getVisulaId()==0){
						changeLogModelData.remove(userChangeLog);
						file.setChangeLog(changeLogModelData);
					}
				}
			  }
			}
			if(resetTriggerFlag){
				new Common().resetTriggers();			    				
				xml_client_marker_ajax();
			}
	  		}
	  	} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | clientResultFromServer |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			
	  }
}
	  	*/
	
	public void changeLogResultFromServer(){
	  	try { 	  		
	  			aq1 = new AQuery(ARDisplayActivity.this);
	  			aq1.ajax(Constants.changeLog, XmlDom.class, new AjaxCallback<XmlDom>(){
		    	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status){
		    		try{
		    			if(xml!=null){
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
		    				
		    				Date currentDate,newDate;
		    				if(Common.sessionForUserAppOpenDate.equals("null") || Common.sessionForUserAppOpenDate.equals(""))
		    					currentDate = sdf.parse(date);
		    				else
		    					currentDate = sdf.parse(Common.sessionForUserAppOpenDate);
		    					
		    				int dateFlag=0;		    				
		    				if(entries.size() > 0){
		    					List<String> idsList = changeLogModelData.getChangeLogIdsbyDate();
		    					
		    				 for(XmlDom entry: entries){
		    					
		    					if(entry.text("created_date") != null){
			    					 newDate = sdf.parse(entry.text("created_date"));
			    					 if(newDate != null){			    						
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
					    					// Log.e("created_date",entry.text("created_date"));			    						    						 
							    			dateFlag ++;					    			    					 
				    					 }			    					
			    					 }
		    					}
		    				 }
		    				 if(dateFlag >0){
		    					 file.setChangeLog(changeLogModel);
		    				 }
		    				}
		    				
		    				changeLogModelData = file.getChangeLog();		
			    			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
			    			if(userChangeLogList.size() > 0){
			    			for(UserChangeLog userChangeLog : userChangeLogList){			    				
			    				if(userChangeLog.getClientId() !=0 || userChangeLog.getTriggerId() != 0 && dateFlag !=0 ){		    				 
			    					resetTriggerFlag = true;
			    				}
			    				if(currentDate.getTime() >  sdf.parse(userChangeLog.getCreatedDate()).getTime()){
			    					if(userChangeLog.getClientId()== 0 &&
			    							userChangeLog.getTriggerId() == 0 && 
			    							userChangeLog.getOfferId() ==0 && 
			    							userChangeLog.getProdtId()==0 &&
			    							userChangeLog.getTriggerVisualId()==0
			    							&& userChangeLog.getVisulaId()==0){
			    						changeLogModelData.remove(userChangeLog);
			    						file.setChangeLog(changeLogModelData);
			    					}
			    				}
			    			  }
			    			}
			    			if(resetTriggerFlag){
			    				new Common().resetTriggers();			    				
			    				xml_client_marker_ajax();
			    				clientTriggerId ="";
			    			}
			    			session = new SessionManager(getApplicationContext());
			    	        session.createAppOpenDate(); 
		    				 
		    			}
		    		}catch(Exception e){
		    			e.printStackTrace();
		    			String errorMsg = className+" | changeLogResultFromServer |   " +e.getMessage();
		           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		    		}
		    	}
	  			});
	  			} catch (Exception e) {
	  				e.printStackTrace();
	  				String errorMsg = className+" | clientResultFromServer |   " +e.getMessage();
	  	       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
	  			}
	}
	
	
	public void checkTriggerExistinChangeLog(String triggerId){
	  	try{ 
	  		   //function 2 to check whether visual exist in change log
	  				 			
				int i=0;
				ChangeLogModel changeLogModelData = file.getChangeLog();		
    			final List<UserChangeLog> userChangeLogList = changeLogModelData.getChangeLogList();
    			if(userChangeLogList.size() > 0){    	  			    	 			
	    			for(UserChangeLog userChangeLog : userChangeLogList){	    				
		    			if(userChangeLog.getTriggerVisualId() == Integer.parseInt(triggerId)){	
		    				if(userChangeLog.getVisulaId() != 0){
		    					//Log.e("visualId",""+userChangeLog.getVisulaId());
		    					saveUpdateVisual(""+userChangeLog.getVisulaId());
		    					visualFlag = false;
		    					i++;		    					
		    				}    			 
		    			} 
	    			}
	    			if(i==0){
	    				visualFlag = true;
	    			}
    			}else{
    				visualFlag = true;
    			}
    		}catch(Exception e){
    			e.printStackTrace();
    			String errorMsg = className+" | changeLogResultFromServer |   " +e.getMessage();
           	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
    		}    	
	}
	
	
	private void saveUpdateVisual(final String visualId) {
		try{		
			 //function 3 to pull data from server 
			aq1 = new AQuery(ARDisplayActivity.this);
			final Map<String, String> params = new HashMap<String, String>();
			params.put("visual_id", visualId);	       			
  			aq1.progress(R.id.progressBar).ajax(Constants.visualUrl, params, XmlDom.class, new AjaxCallback<XmlDom>(){
	    	@Override
			public void callback(String url, XmlDom xml, AjaxStatus status){
	    		try{
	    		
			    	if(xml!=null){
			    			final Visuals visuals = file.getVisuals();	    			
			    			int j = visuals.size() + 1;	    			
			    			Visual checkVisualExist = visuals.getVisualByVID(Integer.parseInt(visualId));	    				
			    			if(checkVisualExist != null){
			    				visuals.removeVisual(Integer.parseInt(visualId));
			    				file.setVisuals(visuals);
			    			}	    			
			    		    final List<XmlDom> entries = xml.tags("visual");
			    			if(entries.size() > 0){
			    				for(XmlDom entry: entries){    	
			    			
			   			    	Marker marker = new Marker(entry.text("triggerUrl").replaceAll(" ", "%20"), entry.text("clientName").toString(),100, 100, 0);
			   					Visual visual = new Visual(j);
			   					visual.setVisualId(Integer.parseInt(entry.text("visual_id").toString()));
			   					visual.setTriggerId(Integer.parseInt(entry.text("trigger_id").toString()));
			   					visual.setImage(entry.text("triggerUrl").toString());
			   					visual.setImageFile(entry.text("triggerUrl").toString());
			   					visual.setLegalImage(entry.text("triggerUrl").toString());
			   					visual.setTitle(entry.text("triggerTitle").toString());
			   					visual.setDiscriminator(entry.text("discriminator").toString());
			   					visual.setVisualUrl(entry.text("visualUrl").toString());
			   					visual.setClientId(Integer.parseInt(entry.text("client_id").toString()));
			   					visual.setClientVerticalId(Integer.parseInt(entry.text("trigger_by_vertical").toString()));
			   					visual.setBuyButtonName(entry.text("buy_button_name").toString());
			   					visual.setBuyButtonUrl(entry.text("buy_button_url").toString());
			   					visual.setImageName(entry.text("clientName").toString());	   					
			   					visual.setClientUrl(entry.text("clientUrl").toString());
			   					visual.setClientLogo(entry.text("clientLogo").toString());
			   					visual.setClientBackgroundImage(entry.text("background_image").toString());
			   					if(entry.text("background_color").toString().equals("null")){
			   						visual.setClientBackgroundColor("ff2600");
			   					} else {
			   						visual.setClientBackgroundColor(entry.text("background_color").toString());
			   					}
			   					if(entry.text("light_color").toString().equals("null")){
			   						visual.setClientLightColor("ff2600");
			   					} else {
			   						visual.setClientLightColor(entry.text("light_color").toString());
			   					}
			   					if(entry.text("dark_color").toString().equals("null")){
			   						visual.setClientDarkColor("ff2600");
			   					} else {
			   						visual.setClientDarkColor(entry.text("dark_color").toString());
			   					}
			   					if(!entry.text("product_id").equals("null")){visual.setProductId(Integer.parseInt(entry.text("product_id").toString()));}
			   					visual.setProductName(entry.text("prodName").toString()); 	 					
			   					  	   					
			   					if(entry.text("country_languages").toString().equals("") || 
			   							entry.text("country_languages").toString().equals("null") || 
			   							entry.text("country_languages").toString()==null){
			   						countryLanguage = "en";
			   					}else {
			   						countryLanguage = entry.text("country_languages").toString();		   						
			   					}
			   					
			   					if(entry.text("country_code_char2").toString()==null || 
			   							entry.text("country_code_char2").toString().equals("") || 
			   							entry.text("country_code_char2").toString().equals("null")){	 
			   						countryCode = "US";
			   					} else { 
			   						countryCode = entry.text("country_code_char2").toString();		   						
			   					}
			   					
								String symbol = new Common().getCurrencySymbol(countryLanguage, countryCode);						
								if (entry.text("pdPrice").toString().equals("null") || 
										entry.text("pdPrice").toString().equals("") || 
										entry.text("pdPrice").toString().equals("0") || 
										entry.text("pdPrice").toString().equals("0.00") || 
										entry.text("pdPrice").toString() == null) {
									visual.setProductPrice("");
								} else {
									visual.setProductPrice(symbol+entry.text("pdPrice").toString());
								}
			   					
			   					visual.setProductUrl(entry.text("productUrl").toString());
			   					visual.setProductButtonName(entry.text("pd_button_name").toString());
			   					visual.setProductShortDesc(entry.text("pd_short_description").toString().replaceAll(",", "-"));
			   					visual.setProImageFile(entry.text("pdImage").toString());
			   					if(!entry.text("offer_id").equals("null")){visual.setOfferId(entry.text("offer_id").toString());}
			   					visual.setOfferName(entry.text("offer_name").toString());
			   					visual.setOfferImage(entry.text("offer_image").toString());
			   					if(!entry.text("offer_discount_type").equals("")){visual.setOfferDiscountType(entry.text("offer_discount_type").toString());}else{visual.setOfferDiscountType("null");}
			   					if(!entry.text("offer_discount_value").equals("")){visual.setOfferDiscountValue(entry.text("offer_discount_value").toString());}else{visual.setOfferDiscountValue("null");}
			   					visual.setOfferButtonName(entry.text("offer_button_name").toString());
								visual.setOfferValidFrom(entry.text("offer_valid_from").toString());
			   					visual.setOfferValidTo(entry.text("offer_valid_to").toString());
			   				
			   					visual.setOfferPurchaseUrl(entry.text("offer_purchase_url").toString());
			   					visual.setOfferIsCalendarBased(entry.text("offer_is_calendar_based").toString());
			   					visual.setProdTapForDetailsImg(entry.text("tapForDetailsImgs").toString());
			   					visual.setProdTapForDetailsImgId(entry.text("tapForDetailsImgId").toString());
			   					visual.setProdTapForDetailsImgPdId(entry.text("tapForDetailsImgPdId").toString());
			   					if(entry.text("x") != "null"){visual.setX(Integer.parseInt(entry.text("x").toString()));}
			   					if(entry.text("y") != "null"){visual.setY(Integer.parseInt(entry.text("y").toString()));}
			   					if(entry.text("animate_on_recognition") != "null"){visual.setAnimateOnRecognition(Integer.parseInt(entry.text("animate_on_recognition").toString()));}
			   					if(entry.text("scale") != "null"){visual.setScale(Float.parseFloat(entry.text("scale").toString()));}
			   					if(entry.text("rotation_x") != "null"){visual.setRotationX(Float.parseFloat(entry.text("rotation_x").toString()));}
			   					if(entry.text("rotation_y") != "null"){visual.setRotationY(Float.parseFloat(entry.text("rotation_y").toString()));}
			   					if(entry.text("rotation_z") != "null"){visual.setRotationZ(Float.parseFloat(entry.text("rotation_z").toString()));}
			   					if(!entry.text("triggerInstruction").equals("null")){visual.setInstruction(entry.text("triggerInstruction").toString());}else{visual.setInstruction("no instuction");}
			   					visual.setCalendarEventStartDate(entry.text("offer_info_event_start").toString());
			   					visual.setCalendarEventEndDate(entry.text("offer_info_event_end").toString());
			   					visual.setCalendarEventAllDay(entry.text("offer_info_event_allday").toString());
			   					visual.setCalendarEventHasAlarm(entry.text("offer_info_event_has_alarm").toString());
			   					visual.setCalendarReminderDays(entry.text("offer_info_reminder_days").toString().replaceAll(",", "_"));
			   					visual.setProductBgColor(entry.text("pdBgColor").toString());
			   					visual.setClientGameId(entry.text("clientGameId").toString());
			   					if(!entry.text("pdHideImage").equals("null")){visual.setProductHideImage(Integer.parseInt(entry.text("pdHideImage")));}
								visual.setProdIsTryOn(Integer.parseInt(entry.text("pd_istryon").toString()));
			   					ArrayList<String> list =  new ArrayList<String>();
			   					List<XmlDom> entriesModel = entry.tags("Model");
			   					if(entriesModel.size() > 1){
			   						for(XmlDom entryModel: entriesModel){
			   							if(entryModel.text("model")!=null){
			   								list.add(entryModel.text("model").toString()+","+entryModel.text("texture").toString()+","+entryModel.text("material").toString()+","+entryModel.text("product").toString());
			   							}		   							
			   							visual.setModelCount(Integer.parseInt(entryModel.text("totalModelCount").toString()));		   							
			   						}
			   					}
			   					visual.setModel(list); 		   					
			   					visual.setMarker(marker);
			   					visuals.add(visual);
			   			    	j++;
			   			    }
		   	    	}                     	    	
		            if (visuals != null) {   					   				
		   				file.setVisuals(visuals);
		               }            	
		            	new Common().deleteChangeLogFields("visual",0);
		            	visualFlag = true;
		            	mDetectedCosID = -1;
			    	}
	    		}catch(Exception e){
	    			e.printStackTrace();
	    			String errorMsg = className+" | saveUpdateVisual |xml callback |   " +e.getMessage();
	  	       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
	    		}
	    	}
  			});			
			
		}catch(Exception e){
			e.printStackTrace();			
			String errorMsg = className+" | saveUpdateVisual |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
		}
		
	}


	int viewPagerCurrentItem = 0;
	public void slideshowtimer()
    {
	     timer.scheduleAtFixedRate(new TimerTask() 
	      {	
	       @Override
	       public void run() 
	       {
	           runOnUiThread(new Runnable() 
	           {	
	               @SuppressLint("NewApi")
				@Override
				public void run() 
	               {
	                   
	                   if(viewPagerCurrentItem<4 && detectorFlag == false){
	                	   
		                    viewPagerCurrentItem++;
							mViewFlipper.setAutoStart(true);
							mViewFlipper.setFlipInterval(1000);
							mViewFlipper.startFlipping();
	                   } else {
	                	   timer.cancel();
	                	   mViewFlipper.stopFlipping();
	                	   if(viewPagerCurrentItem==3 && !session.isLoggedIn() && flagForLogin==false){
	                		   flagForLogin = true;
								new Common().getLoginDialog(ARDisplayActivity.this, ARDisplayActivity.class, "ARDisplay", new ArrayList<String>());
							}
	                   }
	               }	
	           });
	       }	
	   }, 0, 1000);
	}
	
	private static final int SWIPE_MIN_DISTANCE = 120;
	private static final int SWIPE_THRESHOLD_VELOCITY = 200;
	class SwipeGestureDetector extends SimpleOnGestureListener {
		@Override
		public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {
			try {
				// right to left swipe
				if(viewPagerCurrentItem < 3){					
					detectorFlag = true;
					if (e1.getX() - e2.getX() > SWIPE_MIN_DISTANCE && Math.abs(velocityX) > SWIPE_THRESHOLD_VELOCITY) {
						
							mViewFlipper.setInAnimation(AnimationUtils.loadAnimation(mContext, R.anim.left_in));
							mViewFlipper.setOutAnimation(AnimationUtils.loadAnimation(mContext, R.anim.left_out));
							viewPagerCurrentItem++;
							mViewFlipper.setFlipInterval(1000);
							// controlling animation
							mViewFlipper.getInAnimation().setAnimationListener(mAnimationListener);
							mViewFlipper.showNext();
							
							detectorFlag =false;					
						return true;
					}
				}
			} catch (Exception e) {
				e.printStackTrace();
				String errorMsg = className+" | SimpleOnGestureListener |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(ARDisplayActivity.this,errorMsg);
			}

			return false;
		}
	}
	
	public static ArrayList<String> finalArrForAllMarkerInfo;
	StringBuilder strAppendValues;
	public static HashMap<String, ArrayList<String>> multiHMapForAllMarkerInfo = new HashMap<String, ArrayList<String>>();
	public void storedRecentlyScannedInfo(ArrayList<String> strArrayForMarkerInfo2) {
		try{
			if(strArrayForMarkerInfo2.get(0).equals("")){
				//both Date and Time
				long timeNow = System.currentTimeMillis();
				Date date = new Date(timeNow);
				DateFormat df = new SimpleDateFormat("MM-dd-yyyy HH:mm:ss");
				String currentDate = df.format(date);
				
				strArrayForMarkerInfo2.remove(0);
				String strProductId = strArrayForMarkerInfo2.get(4);
				String strOfferId = strArrayForMarkerInfo2.get(6);
				//Log.e("strOfferId", ""+strOfferId);
		    	/*String[] splitOfferIds1 = strOfferId.split("`");
		    	Log.e("splitOfferIds1", ""+splitOfferIds1+" "+splitOfferIds1.length+" "+splitOfferIds1[0]);
		    	String[] splitOfferIds = strOfferId.split("\\`");
		    	Log.e("splitOfferIds", ""+splitOfferIds+" "+splitOfferIds.length+" "+splitOfferIds[0]);*/
				multiHMapForAllMarkerInfo.put(currentDate, strArrayForMarkerInfo2);
							
				if(multiHMapForAllMarkerInfo.size()>1){
					List listForHmapEntrySet = new ArrayList(multiHMapForAllMarkerInfo.entrySet());
					Collections.reverse(listForHmapEntrySet);
					Iterator iter = listForHmapEntrySet.iterator();  
					while(iter.hasNext()){ 
						Map.Entry<String, ArrayList<String>> entry = (Map.Entry) iter.next();
						String key = entry.getKey();
					    ArrayList<String> value = entry.getValue();
					    //Log.i("offer id value", ""+value.get(6));
					    
					    if(!strProductId.equals("null")){
						    if(!key.contains(currentDate) && value.get(4).contains(strProductId)){
					    		multiHMapForAllMarkerInfo.remove(key);
					    	}
					    }else if(!strOfferId.equals("null")){
					    	String[] splitOfferIds = strOfferId.split("\\`");
					    	//Log.i("splitOfferIds", ""+splitOfferIds.length);
					    	if(!key.contains(currentDate) && splitOfferIds.length>0){
						    	for(int s=0; s<splitOfferIds.length; s++){
						    		if(splitOfferIds[s]!=null && value.get(6).contains(splitOfferIds[s])){
							    		multiHMapForAllMarkerInfo.remove(key);						    			
						    		}
						    	}
					    	} else if(!key.contains(currentDate) && value.get(6).contains(strOfferId)){
					    		multiHMapForAllMarkerInfo.remove(key);
					    	}
					    } else{
					    	if(!key.contains(currentDate)){
					    		multiHMapForAllMarkerInfo.remove(key);
					    	}
					    }
					}
				}
				Set setOfKeys = multiHMapForAllMarkerInfo.keySet();
				Iterator iterator = setOfKeys.iterator();
				finalArrForAllMarkerInfo = new ArrayList<String>();
				while( iterator. hasNext() )
				{					 
					String key = (String) iterator.next();
				    ArrayList<String> value = multiHMapForAllMarkerInfo.get(key);
				    //Log.e("map entrys", key+" == "+value);
					strAppendValues = new StringBuilder();
				    strAppendValues.append(key);
				    for(int i=0; i<value.size(); i++){
				    	strAppendValues.append("|");
				    	strAppendValues.append(value.get(i));
				    }
				    //Log.e("strAppendValues", ""+strAppendValues);
				    finalArrForAllMarkerInfo.add(strAppendValues.toString());
				}
			    //Log.i("strAppendValues", ""+strAppendValues);
				//Log.i("finalArrForAllMarkerInfo", ""+finalArrForAllMarkerInfo);
				Collections.sort(finalArrForAllMarkerInfo);
				//Log.i("finalArrForAllMarkerInfo sort", ""+finalArrForAllMarkerInfo);
				Collections.reverse(finalArrForAllMarkerInfo);
				//Log.i("finalArrForAllMarkerInfo reverse", ""+finalArrForAllMarkerInfo);
				
				session.createSessionForRecentlyScanned3(finalArrForAllMarkerInfo);
				new Common().getCommonStoredSessionForRecentlyScanned2(ARDisplayActivity.this);
			}
		} catch(NoSuchElementException e){
			e.printStackTrace();
		} catch(Exception e){
			e.printStackTrace();
		}
	}
		
	@Override
	protected void onNewIntent(Intent intent) {
	    super.onNewIntent(intent);
	    overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
	}
}
