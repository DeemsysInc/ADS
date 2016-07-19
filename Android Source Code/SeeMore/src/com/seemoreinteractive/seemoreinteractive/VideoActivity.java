package com.seemoreinteractive.seemoreinteractive;

import java.util.List;

import com.seemoreinteractive.seemoreinteractive.helper.Common;

import android.app.Activity;
import android.content.Intent;
import android.graphics.PixelFormat;
import android.hardware.Camera;
import android.hardware.Camera.Size;
import android.media.MediaPlayer;
import android.media.MediaPlayer.OnCompletionListener;
import android.media.MediaPlayer.OnPreparedListener;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.VideoView;

public class VideoActivity extends Activity {
	    private SurfaceView preview=null;
	    private SurfaceHolder previewHolder=null;
	    private Camera camera=null;
	    private boolean inPreview=false;
	    private boolean cameraConfigured=false;
	    String className = this.getClass().getSimpleName();
	    boolean previewing = false;
		int currentCameraId = 0;		
		VideoView playerSurfaceView;
		
		
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		getWindow().setFormat(PixelFormat.TRANSLUCENT);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
        WindowManager.LayoutParams.FLAG_FULLSCREEN);
        
        try{
        	
        	setContentView(R.layout.activity_video);
		
		
			 new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
						VideoActivity.this, Common.sessionClientBgColor,
						Common.sessionClientBackgroundLightColor,
						Common.sessionClientBackgroundDarkColor,
						Common.sessionClientLogo, "", "");
			 
			 new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			 
			 ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
		     imgvBtnCloset.setImageAlpha(0);
		     
		     ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
			 imgvBtnShare.setImageAlpha(0);
			
			getWindow().setFormat(PixelFormat.UNKNOWN);
	        preview=(SurfaceView)findViewById(R.id.surfaceviewForCameraView);
	        previewHolder=preview.getHolder();
	        previewHolder.addCallback(surfaceCallback);
	        previewHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
	        
	        
	        ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{	
						//new Common().clickingOnBackButtonWithAnimationWithBackPressed(MyOffers.this, ARDisplayActivity.class, "0");
						Intent intent = new Intent(VideoActivity.this, ARDisplayActivity.class);	
	    				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);						
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
					}
				}
			});
        
		
		ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);
		imgBackButton.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
					finish();
					overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);						
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   imgBackButton click |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
				}
			}
		});	
		
		
        Intent getIntent = getIntent();
        String videoUrl = getIntent.getStringExtra("videoUrl");
        Log.e("videoUrl",videoUrl);
        playFinVideo(videoUrl);
		
        }catch(Exception e){
        	e.printStackTrace();
        	String errorMsg = className+" | onCreate   |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
        }
		
	}
	int stopPosition = -1; 
	public void playFinVideo(String videoUrl){
		try{			  
		      RelativeLayout rlForVideo = (RelativeLayout) findViewById(R.id.rlForVideos);
		      RelativeLayout.LayoutParams rlpVideoParams = (RelativeLayout.LayoutParams) rlForVideo.getLayoutParams();
		      rlpVideoParams.height	    = (int)(0.365 * Common.sessionDeviceHeight);
		      rlpVideoParams.width      = (int)(0.8334 * Common.sessionDeviceWidth);
		      rlForVideo.setLayoutParams(rlpVideoParams);
		      
		      playerSurfaceView = (VideoView)findViewById(R.id.playersurface);
		      RelativeLayout.LayoutParams rlSurfaceView = (RelativeLayout.LayoutParams) playerSurfaceView.getLayoutParams();
			  rlSurfaceView.width     = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			  rlSurfaceView.height    = (int) (0.3074 * Common.sessionDeviceHeight);
			  playerSurfaceView.setLayoutParams(rlSurfaceView);
			
			  
			   ImageView imgvPlayPause = (ImageView) findViewById(R.id.imgvPlayPause);
			   RelativeLayout.LayoutParams rlImgvPlayPause = (RelativeLayout.LayoutParams) imgvPlayPause.getLayoutParams();
			   rlImgvPlayPause.width      = (int)(0.05834 * Common.sessionDeviceWidth);
			   rlImgvPlayPause.height     = (int)(0.03587 * Common.sessionDeviceHeight);
			   rlImgvPlayPause.leftMargin = (int)(0.11834 * Common.sessionDeviceWidth);
			   imgvPlayPause.setLayoutParams(rlImgvPlayPause);				
			   imgvPlayPause.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					if(stopPosition !=0){
						if(playerSurfaceView.isPlaying()){
							playerSurfaceView.pause();
						    stopPosition = playerSurfaceView.getCurrentPosition();
						    new Common().showDrawableImageFromAquery(VideoActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
						}else{
							new Common().showDrawableImageFromAquery(VideoActivity.this, R.drawable.pause_player_button, R.id.imgvPlayPause);
							//playerSurfaceView.resume();
							playerSurfaceView.seekTo(stopPosition);
							playerSurfaceView.start(); 
						}			
					}else{
						playerSurfaceView.resume(); 
						new Common().showDrawableImageFromAquery(VideoActivity.this, R.drawable.pause_player_button, R.id.imgvPlayPause);
					}
				}
			  });
			  
			  
			   ImageView imgvStop = (ImageView) findViewById(R.id.imgvStop);
			   RelativeLayout.LayoutParams rlImgvStop = (RelativeLayout.LayoutParams) imgvStop.getLayoutParams();
			   rlImgvStop.width        = (int)(0.05834 * Common.sessionDeviceWidth);
			   rlImgvStop.height       = (int)(0.03587 * Common.sessionDeviceHeight);
			   rlImgvStop.rightMargin  = (int)(0.11834 * Common.sessionDeviceWidth);
			   imgvStop.setLayoutParams(rlImgvStop);
			   imgvStop.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					playerSurfaceView.stopPlayback();
					stopPosition = 0;
					new Common().showDrawableImageFromAquery(VideoActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
				}
			  });
			  
			  playerSurfaceView.setVideoPath(videoUrl); 
			  playerSurfaceView.start();
			  playerSurfaceView.requestFocus();
			  playerSurfaceView.setOnPreparedListener(new OnPreparedListener() {
			        @Override
					public void onPrepared(MediaPlayer arg0) {
					    findViewById(R.id.rlForVideos).setVisibility(View.VISIBLE);
			        	playerSurfaceView.bringToFront();
			        	playerSurfaceView.requestFocus();
			        	playerSurfaceView.start(); 	
			        	stopPosition =1;
			           }
			    });
			  
			  playerSurfaceView.setOnCompletionListener(new OnCompletionListener() {					
					@Override
					public void onCompletion(MediaPlayer mp) {						
						stopPosition = -1;
						new Common().showDrawableImageFromAquery(VideoActivity.this, R.drawable.play_player_button, R.id.imgvPlayPause);
					}
				});     
			  
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | playFinVideo |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
		}
	}
	
	@Override
	  public void onResume() {
	    super.onResume();
	    
	    try{
	    	if(!previewing){
		    camera=Camera.open(currentCameraId);
		    startPreview();
	    	}
	    } catch(Exception e){
	    	e.printStackTrace();
	    }
	  }
	    
	  @Override
	  public void onPause() {
		  try{
		    if (inPreview) {
		      camera.stopPreview();
		    }
		    

		    if(camera!=null){
		    	camera.release();
		    }
		    camera=null;
		    inPreview=false;

		  
	    } catch(Exception e){
	    	e.printStackTrace();
	    } 
	    super.onPause();
	  }
	  
	  private void initPreview(int width, int height) {
		try {

			if (camera != null && previewHolder.getSurface() != null) {
				try {
					camera.setPreviewDisplay(previewHolder);
					camera.setDisplayOrientation(90);
				} catch (Throwable t) {
					Log.i("PreviewDemo-surfaceCallback",
							"Exception in setPreviewDisplay()", t);
					/*Toast.makeText(TryOn.this, t.getMessage(),
							Toast.LENGTH_LONG).show();*/
				}

				if (!cameraConfigured) {
					Camera.Parameters parameters = camera.getParameters();
					/*camera.setParameters(parameters);
					cameraConfigured = true;
					List<Size> sizes = parameters.getSupportedPreviewSizes();
					Log.i("optimalSize", "" +sizes);
					Log.i("optimalSize", "" + width + " " + height);
					parameters.setPreviewSize(width, height);
					parameters.setPictureSize(width, height);*/
					List<Size> sizes = parameters.getSupportedPreviewSizes();
					Log.i("optimalSize", "" + width + " " + height);
					Size size = getOptimalPreviewSize(sizes, width, height);
					parameters.setPreviewSize(size.width, size.height);
					parameters.setPictureSize(size.width, size.height);

					if (size != null) {
						parameters.setPreviewSize(size.width, size.height);
						//camera.setParameters(parameters);
						cameraConfigured = true;
					}
				}
			}
		} catch (Exception e) {
			e.printStackTrace();
			 String errorMsg = className+" |  initPreview    |   " +e.getMessage();
			 Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
		}
	}
	  
	  private void startPreview() {
		  try{
	    if (cameraConfigured && camera!=null) {
	      camera.startPreview();
	      inPreview=true;
	      
	    }
		  }catch(Exception e){
			  e.printStackTrace();
			  String errorMsg = className+" |  startPreview    |   " +e.getMessage();
			  Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
		  }
	  }
	  
	  SurfaceHolder.Callback surfaceCallback=new SurfaceHolder.Callback() {
	    @Override
		public void surfaceCreated(SurfaceHolder holder) {
	     }
	    
	    @Override
		public void surfaceChanged(SurfaceHolder holder,
	                               int format, int width,
	                               int height) {
	      initPreview(width, height);
	      startPreview();
	    }
	    
	    @Override
		public void surfaceDestroyed(SurfaceHolder holder) {
	   
	    }
	  };

	  
	  public Size getOptimalPreviewSize(List<Size> sizes, int w, int h) {           
		    final double ASPECT_TOLERANCE = 0.1;           
		    double targetRatio = (double) w / h;           
		    if (sizes == null) return null;             
		    Size optimalSize = null;           
		    double minDiff = Double.MAX_VALUE;             
		    int targetHeight = h;             
		    try{
			    // Try to find an size match aspect ratio and size           
			    for (Size size : sizes) {               
			        double ratio = (double) size.width / size.height;               
			        if (Math.abs(ratio - targetRatio) > ASPECT_TOLERANCE) continue;              
			        if (Math.abs(size.height - targetHeight) < minDiff) {                   
			            optimalSize = size;                   
			            minDiff = Math.abs(size.height - targetHeight);               
			        }           
			    }             
			  
			    // Cannot find the one match the aspect ratio, ignore the requirement           
			    if (optimalSize == null) {               
			        minDiff = Double.MAX_VALUE;               
			        for (Size size : sizes) {                   
			            if (Math.abs(size.height - targetHeight) < minDiff) {                      
			                optimalSize = size;                       
			                minDiff = Math.abs(size.height - targetHeight);                   
			            }               
			        }           
			    }           
		    } catch(Exception e){
		    	e.printStackTrace();
		    	 String errorMsg = className+" |  getOptimalPreviewSize     |   " +e.getMessage();
				 Common.sendCrashWithAQuery(VideoActivity.this,errorMsg);
		    }
		    return optimalSize;       
		}
		

}
