// Copyright 2007-2013 metaio GmbH. All rights reserved.
package com.seemoreinteractive.virtualshot;

import java.io.File;

import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.graphics.PointF;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.hardware.Camera.CameraInfo;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Environment;
import android.os.PowerManager;
import android.util.DisplayMetrics;
import android.util.FloatMath;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnTouchListener;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.view.WindowManager;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;
import android.widget.TextView;
import android.widget.Toast;

import com.metaio.sdk.MetaioDebug;
import com.metaio.sdk.MetaioSurfaceView;
import com.metaio.sdk.SensorsComponentAndroid;
import com.metaio.sdk.jni.ERENDER_SYSTEM;
import com.metaio.sdk.jni.ESCREEN_ROTATION;
import com.metaio.sdk.jni.IGeometry;
import com.metaio.sdk.jni.IMetaioSDKAndroid;
import com.metaio.sdk.jni.IMetaioSDKCallback;
import com.metaio.sdk.jni.MetaioSDK;
import com.metaio.sdk.jni.TrackingValuesVector;
import com.metaio.sdk.jni.Vector2di;
import com.metaio.tools.Memory;
import com.metaio.tools.Screen;
import com.metaio.tools.SystemInfo;
import com.seemoreinteractive.virtualshot.helper.FirstTimePreference;
import com.seemoreinteractive.virtualshot.helper.LoadMarkers;
import com.seemoreinteractive.virtualshot.manager.SessionManager;
import com.seemoreinteractive.virtualshot.model.Image;
import com.seemoreinteractive.virtualshot.model.Visual;
import com.seemoreinteractive.virtualshot.model.Visuals;
import com.seemoreinteractive.virtualshot.utils.Constants;
import com.seemoreinteractive.virtualshot.utils.FileTransaction;


/**
 * This is base activity to use metaio SDK. It creates metaio GLSurfaceView and
 * handle all its callbacks and lifecycle. 
 * 
 */ 
public   class MainActivity extends Activity implements MetaioSurfaceView.Callback, OnTouchListener 
{    
	private static boolean nativeLibsLoaded;
	
	
	ImageView trigger = null;
	
	ImageView capture;
	View instruction;
	VerticalSeekBar slider;
	static Matrix matrix = null;
	Matrix savedMatrix = null;
	PointF start = new PointF();
	public static PointF translateXY = new PointF(0, 0);

	int deviceWidth, deviceHeight, photoWidth, photoHeight;

	ImageView character = null;
	// We can be in one of these 3 states
	public static final int NONE = 0;
	public static final int DRAG = 1;
	public static final int ZOOM = 2;
	public static int mode = NONE;
	float oldDist;
	public static Timer timer = null;
	public boolean timerHasStarted = false;
	ProgressBar progressBar;
	protected PowerManager.WakeLock mWakeLock;
	private boolean rendererInitialized = false;
	private final String mTrackingDataFileName = "TrackingData_MarkerlessFast.xml";
	private final String mTrackingDefaultFileName = "TrackingData_MarkerlessFast_Default.xml";
	
	protected int mDetectedCosID = -1;
	protected int lastDetectedCosID = 0;
	private boolean isMarkerLoadingComplete;
	View helpView;
	DisplayMetrics displayMetrics = new DisplayMetrics();
	Bitmap characterImage = null;
	float matrixValues[] = { 0, 0, 0, 0, 0, 0, 0, 0, 0 };
	int delayTimeInMille = 3000;
	ImageView mainHelp;
	SharedPreferences mPrefs;
	TextView tryNow;
	Boolean fileFound = false;
	
	static 
	{     
		nativeLibsLoaded = IMetaioSDKAndroid.loadNativeLibs();
	} 

	private IGeometry mModel;
	
	JSONObject json;
	protected int getGUILayout() 
	{
		// Attaching layout to the activity
		return R.layout.main;
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
	protected View mGUIView;
	
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
	@SuppressLint("InlinedApi")
	protected void startCamera()
	{
		// Select the back facing camera by default
		final int cameraIndex = SystemInfo.getCameraIndex(CameraInfo.CAMERA_FACING_BACK);
		mCameraResolution = metaioSDK.startCamera(cameraIndex, 320, 240);
	}
	
	LoadMarkers loadMarkers;
	
	@Override
	public void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		

		FirstTimePreference prefFirstTime = new FirstTimePreference(getApplicationContext());
		
		if (prefFirstTime.runTheFirstTime("myKey")) {
			//Toast.makeText(this, "Test myKey & coutdown: "+ prefFirstTime.getCountDown("myKey"),Toast.LENGTH_LONG).show();
			deleteFiles(Constants.LOCATION);
		}	
		
		MetaioDebug.log("ARViewActivity.onCreate()");
		mPrefs = getSharedPreferences(Constants.TRENDS_PREF, Context.MODE_PRIVATE);
		isMarkerLoadingComplete = false;
		loadMarkers = new LoadMarkers(MainActivity.this, mPrefs);
		loadMarkers.execute();
		
		metaioSDK = null;
		mSurfaceView = null;
		mRendererInitialized = false;
		getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
		
		try
		{
			if (!nativeLibsLoaded)
				throw new Exception("Unsupported platform, failed to load the native libs");
			
			// Create sensors component
			mSensors = new SensorsComponentAndroid( getApplicationContext() );
			
			// Create Unifeye Mobile by passing Activity instance and application signature
			metaioSDK = MetaioSDK.CreateMetaioSDKAndroid(this, getResources().getString(R.string.metaioSDKSignature));
			metaioSDK.registerSensorsComponent(mSensors);
			
			// Inflate GUI view if provided
			final int layout = getGUILayout(); 
			if (layout != 0)
				mGUIView = View.inflate(this, layout, null);
		}
		catch (Exception e)
		{
			MetaioDebug.log(Log.ERROR, "ARViewActivity.onCreate: failed to create or intialize metaio SDK: "+e.getMessage());
			finish();
		}
		
		PowerManager pm = (PowerManager) getSystemService(Context.POWER_SERVICE);
		mWakeLock = pm.newWakeLock(PowerManager.SCREEN_DIM_WAKE_LOCK, getPackageName());
		
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
			
			// Add Unifeye GL Surface view
			mSurfaceView = new MetaioSurfaceView(this);
			mSurfaceView.registerCallback(this);
			mSurfaceView.setKeepScreenOn(true);
			mSurfaceView.setOnTouchListener(this);

			MetaioDebug.log("ARViewActivity.onStart: addContentView(mMetaioSurfaceView)");
			FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT);
			getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
			deviceWidth = displayMetrics.widthPixels;
			deviceHeight = displayMetrics.heightPixels;

			int captureWidth, captureHeight;
			captureWidth = params.height;
			captureHeight = params.width;

			float deviceRatio = (float) deviceWidth / (float) deviceHeight;
			float captureRatio = (float) captureWidth / (float) captureHeight;
			//Log.i(Constants.TAG, ">>> deviceRatio, captureRatio " + deviceRatio + ", " + captureRatio);

			if (deviceRatio > captureRatio) {
				//Log.i(Constants.TAG, ">>> INSIDE IF");
				photoWidth = deviceWidth;
				photoHeight = (int) (photoWidth / captureRatio);
			} else {
				//Log.i(Constants.TAG, ">>> INSIDE ELSE");
				photoHeight = deviceHeight;
				photoWidth = (int) (captureRatio * photoHeight);
			}
			params.width = photoWidth;
			params.height = photoHeight;
			/*Log.i(Constants.TAG, ">>>Capture: " + captureWidth + "," + captureHeight);
			Log.i(Constants.TAG, ">>>Device : " + deviceWidth + "," + deviceHeight);
			Log.i(Constants.TAG, ">>>Photo  : " + photoWidth + "," + photoHeight);*/
			addContentView(mSurfaceView, params);
			mSurfaceView.setZOrderMediaOverlay(true);
			
			// If GUI view is inflated, add it
	   		if (mGUIView != null)
	   		{
		   		addContentView(mGUIView, new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
		   		mGUIView.bringToFront();
	   		}
	   		/*if (!isMarkerLoadingComplete) {
				addContentView(View.inflate(MainActivity.this, R.layout.loading, null), new LayoutParams(LayoutParams.FILL_PARENT,
						LayoutParams.FILL_PARENT));
				
			}
	  */
		} 
		catch (Exception e) 
		{
			MetaioDebug.log(Log.ERROR, "Error creating views: "+e.getMessage());
			MetaioDebug.printStackTrace(Log.ERROR, e);
		}

	}

	@Override
	protected void onPause() 
	{
		super.onPause();
		MetaioDebug.log("ARViewActivity.onPause()");
		
		// pause the OpenGL surface
		if (mSurfaceView != null)
			mSurfaceView.onPause();
		
		//metaioSDK.pause();
		
		
		try{
			super.onPause();
			MetaioDebug.log("ARViewActivity.onPause()");
			
			// pause the OpenGL surface
			if (mSurfaceView != null)
				mSurfaceView.onPause();
			
			//metaioSDK.pause();
			
			if (progressBar != null) {
				progressBar.setVisibility(View.INVISIBLE);
			}
			if (mWakeLock != null)
				mWakeLock.release();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onPause.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	@Override
	public void onBackPressed() {
		try{
			super.onBackPressed();
			//clearApplicationData();
			//deleteFiles(Constants.LOCATION);
			finish();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onBackPressed.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
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
	@Override
	protected void onResume() 
	{
		try{
	
		super.onResume();
		if (mWakeLock != null)
			mWakeLock.acquire();
		// make sure to resume the OpenGL surface
		if (mSurfaceView != null)
			mSurfaceView.onResume();
		
		
		if (delayTimeInMille == 0) {
			delayTimeInMille = 1000;
		}
		
		metaioSDK.resume();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onResume - UnifeyeSensorsManager.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	
	public void markersLoadingComplete() {
		try{
			isMarkerLoadingComplete = true;
			/*View view = findViewById(R.id.loading_view);
			Log.e("view", ""+view);
			Log.e("view", ""+view.isShown());
			if (view != null) {
				view.setVisibility(View.GONE);
			}*/
			if (mainHelp != null) {
				mainHelp.setClickable(true);
			}
			Log.e("loadContents", ""+mTrackingDataFileName);
			loadContents(mTrackingDataFileName);
			Log.e("loadContents", ""+mTrackingDataFileName);
			ProgressBar progressBar = (ProgressBar) findViewById(R.id.progressBar);
			progressBar.setVisibility(View.INVISIBLE);
			Log.e("progressBar", progressBar+" "+progressBar.isShown());
			if(progressBar.isShown()){
				progressBar.setVisibility(View.INVISIBLE);
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity markersLoadingComplete - Markers loading failed.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	public void initializeMainLayout() {
		try{		
			progressBar = (ProgressBar) findViewById(R.id.progressBar);
			RelativeLayout view = (RelativeLayout) findViewById(R.id.camera_view);
			this.character = (ImageView) findViewById(R.id.character_view);
			view.setOnTouchListener(this);
			
			tryNow = (TextView) findViewById(R.id.txtTryNow);
			//tryNow.setVisibility(View.VISIBLE);
			tryNow.setOnClickListener(new View.OnClickListener() {				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					Log.e("demoMarkerId", ""+FileTransaction.demoMarkerId);
					markerDetected(FileTransaction.demoMarkerId);
				}
			});	
			/*if(!mPrefs.getBoolean("is_marker_loaded", false)){
				tryNow.setVisibility(View.VISIBLE);
				tryNow.setOnClickListener(new View.OnClickListener() {				
					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
						Log.e("demoMarkerId mainactivity", ""+loadMarkers.demoMarkerId);
						markerDetected(loadMarkers.demoMarkerId);
						Toast.makeText(getApplicationContext(), "Testing", Toast.LENGTH_LONG).show();
					}
				});	
			} else {
				tryNow.setVisibility(View.INVISIBLE);				
			}*/
	
			capture = (ImageView) findViewById(R.id.main_camera);
			instruction = findViewById(R.id.main_instruction);
			timer = new Timer(Constants.CHARACTER_TIMEOUT_IN_MILLIS, 1000);
			mainHelp = (ImageView) findViewById(R.id.main_help);
			mainHelp.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						//Log.i(Constants.TAG, "Help clicked.");
						helpView = View.inflate(MainActivity.this, R.layout.help_tutorial, null);
						MainActivity.this.addContentView(helpView, new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.FILL_PARENT));
						View tutorialNext = findViewById(R.id.tutorial_next);
						tutorialNext.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								try{
									//Log.i("get image", ""+character.getDrawable());

									capture.setVisibility(View.INVISIBLE);
									slider.setVisibility(View.INVISIBLE);
									character.setImageDrawable(null);
									ViewGroup vg = (ViewGroup) helpView.getParent();
									vg.removeView(helpView);
								} catch (Exception e) {
									// TODO: handle exception
									Toast.makeText(getApplicationContext(), "Error: MainActivity tutorialNext - ViewGroup helpView failed.", Toast.LENGTH_LONG).show();
									e.printStackTrace();
								}
							}
						});
		
						View tutorialHelp = findViewById(R.id.tutorial_help);
						tutorialHelp.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								try{
									final View aboutView = View.inflate(MainActivity.this, R.layout.help_about, null);
									MainActivity.this.addContentView(aboutView, new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.FILL_PARENT));
			
									View aboutNext = findViewById(R.id.about_next);
									aboutNext.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
											ViewGroup vg = (ViewGroup) aboutView.getParent();
											vg.removeView(aboutView);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity aboutNext - ViewGroup aboutView failed.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
			
									View seemoreCopyright = findViewById(R.id.web_seemore_copyright);
									//Log.i(Constants.TAG, "Seemore copyright is not null");
									seemoreCopyright.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												Uri uriUrl = Uri.parse("http://www.seemoreinteractive.com/");
												// http://www.seemoreinteractive.com/
												Intent launchBrowser = new Intent(Intent.ACTION_VIEW, uriUrl);
												startActivity(launchBrowser);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity seemoreCopyright - seemore copy right is null.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
			
									View metaioCopyright = findViewById(R.id.web_metaio_copyright);
									metaioCopyright.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												Uri uriUrl = Uri.parse("http://www.metaio.com/imprint/");
												Intent launchBrowser = new Intent(Intent.ACTION_VIEW, uriUrl);
												startActivity(launchBrowser);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity metaioCopyright - metaio copy right is null.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
			
									View trendsIntl = findViewById(R.id.web_trends);
									trendsIntl.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												Uri uriUrl = Uri.parse("http://www.trendsinternational.com/");
												Intent launchBrowser = new Intent(Intent.ACTION_VIEW, uriUrl);
												startActivity(launchBrowser);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity trendsIntl - click on trendsIntl.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
			
									View trendsIntlFb = findViewById(R.id.web_trends_fb);
									trendsIntlFb.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												Uri uriUrl = Uri.parse("https://www.facebook.com/IntlTrends/");
												Intent launchBrowser = new Intent(Intent.ACTION_VIEW, uriUrl);
												startActivity(launchBrowser);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity trendsIntlFb - click on trendsIntlFb.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
			
									View trendsIntlTwitter = findViewById(R.id.web_trends_twitter);
									trendsIntlTwitter.setOnClickListener(new View.OnClickListener() {
										@Override
										public void onClick(View v) {
											try{
												Uri uriUrl = Uri.parse("https://twitter.com/IntlTrends/");
												Intent launchBrowser = new Intent(Intent.ACTION_VIEW, uriUrl);
												startActivity(launchBrowser);
											} catch (Exception e) {
												// TODO: handle exception
												Toast.makeText(getApplicationContext(), "Error: MainActivity trendsIntlTwitter - click on trendsIntlTwitter.", Toast.LENGTH_LONG).show();
												e.printStackTrace();
											}
										}
									});
								} catch (Exception e) {
									// TODO: handle exception
									Toast.makeText(getApplicationContext(), "Error: MainActivity tutorialHelp - tutorialHelp failed.", Toast.LENGTH_LONG).show();
									e.printStackTrace();
								}
							}
						});

						
					} catch (Exception e) {
						// TODO: handle exception
						Toast.makeText(getApplicationContext(), "Error: MainActivity mainHelp - Help clicked.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
					}
				}
			});
	
			capture.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						if(matrix != null){
							int xOffset = (photoWidth - deviceWidth) / 2;
							int yOffset = (photoHeight - deviceHeight) / 2;
							matrix.postTranslate(xOffset, yOffset);
							Log.i(Constants.TAG, "xOffset: " + xOffset + " yOffset: " + yOffset);
							character.setDrawingCacheEnabled(true);
							character.setImageMatrix(matrix);
							Drawable drawable = null;				
						
						try{						
						 drawable = character.getDrawable();
						 fileFound = true;
						}
						catch(NullPointerException e){
						fileFound = false;
						}
						if(fileFound){
						if(drawable != null){
						Bitmap characterBitmap = ((BitmapDrawable) drawable).getBitmap();
						Log.e("xOffset",xOffset+"yOffset"+yOffset);
						matrix.postTranslate(-xOffset, -yOffset);
						character.setImageMatrix(matrix);
						Image.setCharacterBitmap(characterBitmap);
						progressBar.setVisibility(View.VISIBLE);
						//mUnifeyeSurfaceView.takeScreenshot();
						//takeScreenShot(characterBitmap);						
						metaioSDK.requestScreenshot(Constants.LOCATION+"camera_picture.png",1047);
						
						takeScreenShot();
						
						}
						}
					}
					} catch (Exception e) {
						// TODO: handle exception
						Toast.makeText(getApplicationContext(), "Error: MainActivity capture - click on capture.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
					}
				}

			});
			capture.setVisibility(View.INVISIBLE);
			progressBar.setVisibility(View.INVISIBLE);
			instruction.setVisibility(View.VISIBLE);
			Image.setCameraBitmap(null);
			Image.setCharacterBitmap(null);
			Image.setCombinedBitmap(null);
			Image.setLegalBitmap(null);
			File externalStorage = Environment.getExternalStorageDirectory();
			File file = new File(externalStorage.getAbsolutePath() + "/" + Constants.TEMP_IMAGE_NAME);
			File tempImage = new File(file.getAbsolutePath());
			if (tempImage.isFile()) {
				tempImage.delete();
			}
			slider = (VerticalSeekBar) findViewById(R.id.main_slider);
			slider.setVisibility(View.INVISIBLE);
			slider.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
	
				@Override
				public void onStopTrackingTouch(SeekBar seekBar) {
					Log.i("FSM", "onStop Tracking called.");
				}
	
				@Override
				public void onStartTrackingTouch(SeekBar seekBar) {
					Log.i("FSM", "onStart Tracking called.");
				}
	
				@Override
				public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
					try{
						if (matrix != null) {
							float scaledWidth = displayMetrics.widthPixels * (progress + 20) / 100;
							float matrixValues[] = { 0, 0, 0, 0, 0, 0, 0, 0, 0 };
							matrix.getValues(matrixValues);
							float newScaleFactor = (scaledWidth / characterImage.getWidth()) / matrixValues[0];
							translateXY.x = matrixValues[2] + ((characterImage.getWidth() * matrixValues[0]) / 2);
							translateXY.y = matrixValues[5] + ((characterImage.getHeight() * matrixValues[0]) / 2);
							matrix.postScale(newScaleFactor, newScaleFactor, translateXY.x, translateXY.y);
							character.setImageMatrix(matrix);
						}
					} catch (Exception e) {
						// TODO: handle exception
						Toast.makeText(getApplicationContext(), "Error: MainActivity onProgressChanged.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
					}
				}
			});
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: MainActivity initializeMainLayout - camera view.", Toast.LENGTH_LONG).show();
		}
	}

	public void onClickUrl() {
		Log.i(Constants.TAG, "URL called.");
	}


	private void takeScreenShot() {
		// TODO Auto-generated method stub
		/*Bitmap bitmap;
	    characterBitmap.setDrawingCacheEnabled(true);
	    bitmap = Bitmap.createBitmap(characterBitmap.getDrawingCache());
	    characterBitmap.setDrawingCacheEnabled(false);*/
	
		
		/*String filepath = Constants.LOCATION+"camera_picture.png";
		
		File imgFile = new  File(filepath);
	
		Log.e("filepath",""+filepath);
		if(imgFile.exists()){
			Log.e("imgFile",""+imgFile);
		    Bitmap myBitmap = BitmapFactory.decodeFile(imgFile.getAbsolutePath());
		*/
		    
		    //Image.setCameraBitmap(myBitmap);
			Intent intent = new Intent(MainActivity.this, PreviewActivity.class);			
			overridePendingTransition(R.xml.slide_in_left, R.xml.slide_in_right);
			startActivity(intent);

		
		
	
	}
	
	@Override
	protected void onStop() 
	{
		try{
			super.onStop();
			
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
			
			View characterView = findViewById(R.id.character_view);
			if (characterView != null) {
				characterView.setDrawingCacheEnabled(false);
			}
			if (this.character != null) {
				this.character.setImageDrawable(null);
			}
			// matrix = null;
			// savedMatrix = null;
			stopTimer();
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onStop.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
		
	} 

	@Override
	protected void onDestroy() 
	{
		try{
			super.onDestroy();
			
			MetaioDebug.log("ARViewActivity.onDestroy");

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
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onDestroy.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
		
	}
	
	
	@Override
	public void onConfigurationChanged(Configuration newConfig) 
	{
		super.onConfigurationChanged(newConfig);
		
		final ESCREEN_ROTATION rotation = Screen.getRotation(this);
		
		metaioSDK.setScreenRotation(rotation);
		
		MetaioDebug.log("onConfigurationChanged: "+rotation);
	}
	
/*
	@Override
	public boolean onTouch(View v, MotionEvent event)
	{
		if (event.getAction() == MotionEvent.ACTION_UP) 
		{
			MetaioDebug.log("ARViewActivity touched at: "+event.toString());
			
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
				}
				
			}
			catch (Exception e)
			{
				MetaioDebug.log(Log.ERROR, "onTouch: "+e.getMessage());
			}
			
		}
		return true;
		
		
	}*/
	
	
	@Override
	public boolean onTouch(View arg0, MotionEvent event) {
		try{
			
			this.character = (ImageView) findViewById(R.id.character_view);
			if (this.character.getDrawable()!= null) {
				
			switch (event.getAction() & MotionEvent.ACTION_MASK) {
			case MotionEvent.ACTION_DOWN:
				savedMatrix.set(matrix);
				start.set(event.getX(), event.getY());
				Log.i(Constants.TAG, "mode=DRAG");
				mode = DRAG;
				break;
	
			case MotionEvent.ACTION_POINTER_DOWN:
	
				oldDist = spacing(event);
				Log.i(Constants.TAG, "oldDist=" + oldDist);
				if (oldDist > 10f) {
					savedMatrix.set(matrix);
					mode = ZOOM;
					Log.i(Constants.TAG, "mode=ZOOM");
				}
				break;
	
			case MotionEvent.ACTION_MOVE:
				if (mode == DRAG) {
					matrix.set(savedMatrix);
					matrix.postTranslate(event.getX() - start.x, event.getY() - start.y);
					matrix.getValues(matrixValues);
				} else if (mode == ZOOM) {
					float newDist = spacing(event);
					Log.i(Constants.TAG, "newDist=" + newDist);
					if (newDist > 10f) {
						matrix.set(savedMatrix);
						float scale = newDist / oldDist;
						Log.i("FSM", "Scaling Factor: " + scale);
						matrix.getValues(matrixValues);
						float scalingFactor = matrixValues[0];
						// float deviceWidth = displayMetrics.widthPixels;
						translateXY.x = matrixValues[2] + ((characterImage.getWidth() * scalingFactor) / 2);
						translateXY.y = matrixValues[5] + ((characterImage.getHeight() * scalingFactor) / 2);
						matrix.postScale(scale, scale, translateXY.x, translateXY.y);
						matrix.getValues(matrixValues);
						scalingFactor = matrixValues[0];
						int characterWidthInPercentage = (int) ((characterImage.getWidth() * scalingFactor * 100) / deviceWidth);
						slider.setProgressAndThumb(characterWidthInPercentage - 20);
					}
				}
				break;
	
			case MotionEvent.ACTION_UP:
			case MotionEvent.ACTION_POINTER_UP:
				mode = NONE;
				Log.i(Constants.TAG, "mode=NONE");
				break;
			}
			this.character.setImageMatrix(matrix);
			}
			return true; // indicate event was handled
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onTouch.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			return false;
		}
	}

	
	private float spacing(MotionEvent event) {
		try{
			float x = event.getX(0) - event.getX(1);
			float y = event.getY(0) - event.getY(1);
			return FloatMath.sqrt(x * x + y * y);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity spacing.", Toast.LENGTH_LONG).show();
			return FloatMath.sqrt((float) 0.0);
		}
	}
	
	
	private void onGeometryTouched(IGeometry geometry) {
		// TODO Auto-generated method stub
		
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
				mSurfaceView.queueEvent(new Runnable() 
				{
					@Override
					public void run() {
						//loadContents(mTrackingDefaultFileName);
					}
						});
						
					
				
			}
			else
			{
				MetaioDebug.log("ARViewActivity.onSurfaceCreated: Reloading textures...");
				metaioSDK.reloadTextures();
			}
			
			// connect the audio callbacks
			MetaioDebug.log("ARViewActivity.onSurfaceCreated: Registering audio renderer...");
			metaioSDK.registerAudioCallback( mSurfaceView.getAudioRenderer() );
			metaioSDK.registerCallback(getMetaioSDKCallbackHandler());

			MetaioDebug.log("ARViewActivity.onSurfaceCreated");

		}
		catch (Exception e)
		{
			MetaioDebug.log(Log.ERROR, "ARViewActivity.onSurfaceCreated: "+e.getMessage());
		}
 	}
	private void scaleImageToFitCenter() {
		try{
			DisplayMetrics displayMetrics = new DisplayMetrics();
			getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
			float xScale = ((float) displayMetrics.widthPixels / 2) / characterImage.getWidth();
			this.character.setImageBitmap(characterImage);
			matrix.postScale(xScale, xScale);
			matrix.postTranslate(displayMetrics.widthPixels / 4, displayMetrics.heightPixels / 4);
			this.character.setImageMatrix(matrix);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity scaleImageToFitCenter.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	public void startOrResetTimer() {
		try{
			if (timerHasStarted) {
				stopTimer();
			}
			timer.start();
			timerHasStarted = true;
			Log.i(Constants.TAG, "Timer starts.");
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity StartOrResetTimer.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	public void stopTimer() {
		try{
			if(timer!=null){
				timer.cancel();
			}
			timerHasStarted = false;
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity stopTimer.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	public class Timer extends CountDownTimer {

		public Timer(long startTime, long interval) {
			super(startTime, interval);
		}

		@Override
		public void onFinish() {
			try{
				View view = findViewById(R.id.character_view);
				view.setDrawingCacheEnabled(false);
				character.setImageDrawable(null);
				Log.i("Trends", "Timer Finished.");
				capture.setVisibility(View.INVISIBLE);
				slider.setVisibility(View.INVISIBLE);
				instruction.setVisibility(View.VISIBLE);
				
				//matrix = null;
				//savedMatrix = null;
			} catch (Exception e) {
				// TODO: handle exception
				Toast.makeText(getApplicationContext(), "Error: MainActivity onFinish.", Toast.LENGTH_LONG).show();
				e.printStackTrace();
			}
		}

		@Override
		public void onTick(long millisUntilFinished) {
		}
	}

 
	

	
	@Override
	public void onSurfaceDestroyed() 
	{
		MetaioDebug.log("ARViewActivity.onSurfaceDestroyed(){");
		mSurfaceView = null;		
		metaioSDK.registerAudioCallback(null);
	}

	@Override
	public void onSurfaceChanged(int width, int height) 
	{
		
		try{
			
			
		MetaioDebug.log("ARViewActivity.onSurfaceChanged: "+width+", "+height);
		
		// resize renderer viewport
		metaioSDK.resizeRenderer(width, height);	
		
		Thread t = new Thread(new Runnable() {
				@Override
				public void run() {
					runOnUiThread(new Runnable() {
						@Override
						public void run() {
							try{
								/*((ImageView) findViewById(R.id.splash_view)).setImageDrawable(null);
								addContentView(View.inflate(MainActivity.this, R.layout.main, null), new LayoutParams(LayoutParams.FILL_PARENT,
										LayoutParams.FILL_PARENT));*/
								initializeMainLayout();
								if (!isMarkerLoadingComplete) {
									/*addContentView(View.inflate(MainActivity.this, R.layout.loading, null), new LayoutParams(LayoutParams.FILL_PARENT,
											LayoutParams.FILL_PARENT));*/
									ProgressBar progressBar = (ProgressBar) findViewById(R.id.progressBar);
									progressBar.setVisibility(View.VISIBLE);
									mainHelp.setClickable(false);
								}
							} catch (Exception e) {
								// TODO: handle exception
								e.printStackTrace();
								Toast.makeText(getApplicationContext(), "Error: MainActivity onSurfaceChanged runOnUiThread.", Toast.LENGTH_LONG).show();
							}
						}
					});
				}
			});
			Log.e("Thread", ""+t+" "+delayTimeInMille);
			try {
				Thread.sleep(delayTimeInMille);
				delayTimeInMille = 0;
				t.start();
			} catch (Exception e) {
				e.printStackTrace();
				Log.i(Constants.TAG, "Error in surfaceChange in thread sleep: " + e);
				Toast.makeText(getApplicationContext(), "Error: MainActivity Thread sleep.", Toast.LENGTH_LONG).show();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onSurfaceChanged.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	
	
	@Override
	public void onLowMemory() {
		
		MetaioDebug.log(Log.ERROR, "Low memory");
		MetaioDebug.logMemory(getApplicationContext());
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		Log.e("CheckStartActivity","onActivityResult and resultCode = "+resultCode+" requestCode="+requestCode+" data="+data);
		// TODO Auto-generated method stub
		super.onActivityResult(requestCode, resultCode, data);
		if(resultCode==1){
			//Toast.makeText(this, "Pass", Toast.LENGTH_LONG).show();
			
					
		}
		
	}
	@Override
	public void onDrawFrame() 
	{
		try {
		if (mRendererInitialized)
			metaioSDK.render();
		
		if (metaioSDK != null)
		{
			// get all detected poses/targets
			TrackingValuesVector poses = metaioSDK.getTrackingValues();
			
			
				if (poses.size() > 0) {
					markerDetected(poses.get(0).getCoordinateSystemID());
					} else {
					mDetectedCosID = -1;
				}
				
			 
			}
		
	} catch (Exception e) {
		runOnUiThread(new Runnable() {
			@Override
			public void run() {				
				
				Toast.makeText(getApplicationContext(), "Error: MainActivity onDrawFrame.", Toast.LENGTH_LONG).show();
				
			}
		});
		// TODO: handle exception
		e.printStackTrace();
	}
	}
	

	/*private void markerDetected(int cosID) {
		// TODO Auto-generated method stub
		
		try{
    		//Log.i("mDetectedCosID","mcD"+mDetectedCosID);
			if (cosID != mDetectedCosID) {
				mDetectedCosID = cosID;
			
			}
			
		}catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
			}
			
		
	}	*/
	

	public void markerDetected(int cosID) {
		try{
			Log.i("cosID", cosID+" != "+mDetectedCosID);
			if (cosID != mDetectedCosID) {
				mDetectedCosID = cosID;
				//lastDetectedCosID = mDetectedCosID;
				Log.i("mDetectedCosID", ""+mDetectedCosID+" "+character.getDrawable()+" lastDetectedCosID: "+lastDetectedCosID);
				
				if (character.getDrawable() == null || lastDetectedCosID != cosID) {
					Log.i("cosID"," "+cosID);
					lastDetectedCosID = cosID;
					Log.i("lastDetectedCosID"," "+lastDetectedCosID);
					matrix = new Matrix();
					savedMatrix = new Matrix();
					runOnUiThread(new Runnable() {
						@Override
						public void run() {
							try{
								Log.i("Test"," "+mPrefs.getBoolean("is_marker_loaded", false));
								if (mPrefs.getBoolean("is_marker_loaded", false)) {
									FileTransaction fileTrans = new FileTransaction();
									Log.i("file"," "+fileTrans);
									Visuals visuals = fileTrans.getVisuals();
									Log.i("visuals"," "+visuals);
									Log.i("mDetectedCosID", lastDetectedCosID+" "+mDetectedCosID);
									if(lastDetectedCosID==2){
										Log.i("mDetectedCosID", lastDetectedCosID+" "+mDetectedCosID);	
										mDetectedCosID=lastDetectedCosID;
									}
									Log.i("mDetectedCosID", lastDetectedCosID+" "+mDetectedCosID);	
									if(mDetectedCosID > 0){
										Visual visual = visuals.getVisualByMarkerIndex(mDetectedCosID);
										Log.i("visual"," "+visual);
										Log.i("visual"," "+visual.getImage());
										/*Log.i("visual"," "+visual);
										Log.i(Constants.TAG,"detected file uri: "+Uri.parse(visual.getImageFile())+" Detect Id:"+mDetectedCosID+" Marker file: "+Uri.parse(visual.getMarker().getImageFile())+" Image getId "+visual.getId()+" getMarker id: "+visual.getMarker().getId());
										Log.i(Constants.TAG,"ENUM: "+visual.getLegalPosition().toString()+" isLegalSwitch: "+visual.isLegalSwitch());

										Log.i("visual1"," "+visual.getImageFile());
										Log.i("visual.getId"," "+(visual.getMarker().getId()+1));*/
										if(visual==null){
											Toast.makeText(getApplicationContext(), "Error: Scanning failed. Please scan properly.", Toast.LENGTH_LONG).show();
										} else {											
											Log.i("get char image", ""+visual.getImage());	
											Log.i("get char image", ""+character.getDrawable());	
											slider = (VerticalSeekBar) findViewById(R.id.main_slider);
											slider.setVisibility(View.VISIBLE);
											capture = (ImageView) findViewById(R.id.main_camera);
											capture.setVisibility(View.VISIBLE);
											character.setImageURI(null);	
											character.setImageURI(Uri.parse(visual.getImageFile()));
											Log.i("after char"," "+visual.getImageFile());
											
											if(visual.isLegalSwitch()){
												//Log.i("after if visual.isLegalSwitch()"," "+visual.isLegalSwitch());
												SessionManager.getInstance().setLegalPosition(visual.getLegalPosition());
												//Log.i("after if SessionManager.getInstance()"," "+visual.getLegalPosition());
												//Log.i("after if visual.getLegalImageFile()"," "+visual.getLegalImageFile());
												//Log.i("after if visual.getLegalImageFile().replaceAll"," "+visual.getLegalImageFile().replaceAll("file:", ""));
												Bitmap legalBitmap = BitmapFactory.decodeFile(visual.getLegalImageFile().replaceAll("file:", ""));
												//Log.i("after if legalBitmap"," "+legalBitmap);
												legalBitmap = Bitmap.createScaledBitmap(legalBitmap,legalBitmap.getWidth()/2, legalBitmap.getHeight()/2, true);
												//Log.i("after if cretaeScale legalBitmap"," "+legalBitmap);
												Image.setLegalBitmap(legalBitmap);
												//Log.i("after if setLegalBitmap"," "+legalBitmap);
											}else{
												//Log.i("after else"," "+visual.isLegalSwitch());
												Image.setLegalBitmap(null);
												//Log.i("after else setLegalBitmap"," "+visual.isLegalSwitch());
												SessionManager.getInstance().setLegalPosition(null);
												//Log.i("after else SessionManager.getInstance"," "+visual.isLegalSwitch());
											}
											tryNow.setVisibility(View.INVISIBLE);
										}
									}									
								} else {
									switch (mDetectedCosID) {
									case 1:
										character.setImageResource(R.drawable.the_hobbit_visual);
										break;
									case 2:
										character.setImageResource(R.drawable.monster_high_visual_2);
										break;
									}
								}
								capture.setVisibility(View.VISIBLE);
								slider.setVisibility(View.VISIBLE);
								instruction.setVisibility(View.INVISIBLE);
								Log.i("try now image", ""+character.getDrawable());
								if(character.getDrawable()!=null){
									Drawable drawable = character.getDrawable();
									characterImage = ((BitmapDrawable) drawable).getBitmap();
									scaleImageToFitCenter();
								}
								slider.setProgressAndThumb(40);
							} catch (Exception e) {
								// TODO: handle exception
								e.printStackTrace();
								Toast.makeText(getApplicationContext(), "Error: MainActivity runOnUiThread scanning.", Toast.LENGTH_LONG).show();
							}
						}
					});
				} else {
					character.setImageURI(null);
				}
				startOrResetTimer();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity markerDetected.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}

	
/*	@Override
	public void onScreenshot(Bitmap bitmap) {
		try{
			Image.setCameraBitmap(bitmap);
			Intent intent = new Intent(MainActivity.this, PreviewActivity.class);
			// startActivityForResult(intent, 1);
			startActivity(intent);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: MainActivity onScreenshot.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	*/
	
	protected void loadContents(String trackingDataFileName) 
	{
		try
		{
			
			
			if (!mPrefs.getBoolean("is_marker_loaded", false)) {
				if (!isNetworkAvailable()) {
					trackingDataFileName = "TrackingData_MarkerlessFast_Default.xml";
				}
			}
			String filepathTracking = Constants.LOCATION + trackingDataFileName;
			 File file = new File(filepathTracking);
			 Uri uri = Uri.parse(filepathTracking);
			 if(!file.exists()){
				 sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, uri));
			 }
			 boolean result = metaioSDK.setTrackingConfiguration(filepathTracking); 
			 MetaioDebug.log("Tracking data loaded: " + result); 
		        
				
		} catch (Exception e) {
			Log.i(Constants.TAG, "Loading of the tracking data failed.");
			Toast.makeText(getApplicationContext(), "Error: MainActivity loadTrackingData - Loading of the tracking data failed.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
			
		}
		       
		
	}
	
	

	protected IMetaioSDKCallback getMetaioSDKCallbackHandler() {
		// TODO Auto-generated method stub
		return null;
	}
	
	public boolean isNetworkAvailable() {
		 boolean connected = false;
			ConnectivityManager connectivityManager = (ConnectivityManager)getSystemService(Context.CONNECTIVITY_SERVICE);
			    if(connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).getState() == NetworkInfo.State.CONNECTED || 
			            connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI).getState() == NetworkInfo.State.CONNECTED) {
			        //we are connected to a network
			        connected = true;
			    }
			    else
			    	connected = false;
				return connected;
			   
	}
	
	
}
