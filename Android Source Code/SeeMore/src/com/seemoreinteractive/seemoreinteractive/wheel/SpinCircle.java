package com.seemoreinteractive.seemoreinteractive.wheel;




import java.util.ArrayList;

import org.json.JSONObject;

import com.seemoreinteractive.seemoreinteractive.GameOffer;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.SpinWheel;
import com.seemoreinteractive.seemoreinteractive.WheelOfDeals;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.Rect;
import android.graphics.RectF;
import android.graphics.Region;
import android.graphics.Typeface;
import android.media.AudioManager;
import android.media.SoundPool;
import android.os.Handler;
import android.os.SystemClock;
import android.util.Log;
import android.view.GestureDetector;
import android.view.GestureDetector.SimpleOnGestureListener;
import android.view.MotionEvent;
import android.view.View;
  
public class SpinCircle extends View {
	 
	
    private int startAngle = 0;
    private int numberOfSegments;
   // private int sweepAngle = 360 / numberOfSegments;
    private String[] COLORS;
    String[] name;
    private int sweepAngle;
    private Wedge[] mWedges;
	private Wedge mSelectionWedge = null;
    private RectF oval = new RectF();
    private RectF middleOval = new RectF();
    private RectF finalOVal = new RectF();
    private GestureDetector mDetector;
    private RectF mViewRect = new RectF();
    int[] colors = new int[]{0xFF33B5E5,
            0xFFAA66CC,
            0xFF99CC00,
            0xFFFFBB33,
            0xFFFF4444,
            0xFF0099CC,
            0xFFAA66CC,
            0xFF669900,
            0xFFFF8800,
            0xFFCC0000};
    private boolean[] mQuadrantTouched = new boolean[] { false, false, false, false, false };
    private double mStartAngle;
	private double mRotationAngle;
	private int xPosition = scalePX(360);			//Center X location of Radial Menu
	private int yPosition = scalePX(360);
	private int mMaxSize = scalePX(90);		
	private int mMinSize = scalePX(35);		
	private boolean mAllowRotating = true;
	private float screen_density = getContext().getResources().getDisplayMetrics().density;
	Typeface tf;
	
	private static SoundPool soundPool;
	private static int clickSoundId;
	Handler mHandler = new Handler();
	Context mContext;
	Activity activitythis;
	
	private static void initSoundPoolIfNecessary(Context context) {
		try{
			if (soundPool == null) {
				soundPool = new SoundPool(1, AudioManager.STREAM_SYSTEM, 0);
				clickSoundId = soundPool.load(context, R.raw.spinwheelsound_modified, 1);
			}
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	
	private int scalePX( int dp_size )
    {
       int px_size = (int) (dp_size * screen_density + 0.5f);
       return px_size;
    }
    public SpinCircle(Context context,String[] name,String[] COLORS,int numberOfSegments, Activity activity) {
		super(context);
		try{
		  mDetector = new GestureDetector(context, new MyGestureDetector());
		  this.xPosition = (getResources().getDisplayMetrics().widthPixels)/2;
	      this.yPosition = 360;
	      this.mContext = context;
	      this.numberOfSegments = numberOfSegments;
	      sweepAngle = 360 / numberOfSegments;
	      this.COLORS = COLORS;
	      this.name = name;
	      
	      tf = Typeface.createFromAsset(getResources().getAssets(),
                    "ufonts.com_bernard-mt-condensed (1).ttf");
	      initSoundPoolIfNecessary(context);
	      determineWedges();
	      this.activitythis = activity;
		}catch(Exception e){
			e.printStackTrace();
		}
	}
    
    protected void onDraw(Canvas canvas) {
	   try{
	    	canvas.save(Canvas.MATRIX_SAVE_FLAG); //Saving the canvas and later restoring it so only this image will be rotated.	    	
	        int px = getMeasuredWidth()/2;
	        int py = getMeasuredHeight();
	        int mRadius = (int)(0.416667 * Common.sessionDeviceWidth);
	    	canvas.rotate((float)mRotationAngle, getWidth()/2, getHeight()/2);
	    	//canvas.rotate((float)180, xPosition, yPosition);
		       final RectF rect = new RectF();
		      
		       rect.set(getWidth()/2- mRadius, getHeight()/2 - mRadius, getWidth()/2 + mRadius, getHeight()/2 + mRadius);
	 	  
	        Paint paint2 =  new Paint(); 
	        paint2.setColor(Color.TRANSPARENT);
	        paint2.setStrokeWidth(mRadius);
	        paint2.setAntiAlias(true);
	        paint2.setStrokeCap(Paint.Cap.BUTT);
	        paint2.setStyle(Paint.Style.STROKE);
	        for (int i = 0; i < numberOfSegments; i++) 
	        {      
		            int middleCircleRadius = 140;
		            Paint pencil =  new Paint(); 
		            pencil.setStrokeWidth(0.5f);
		            pencil.setColor(0x80444444);
		            pencil.setStyle(Paint.Style.STROKE);
		            
		            Paint  middleCircleBody = new Paint();
		            middleCircleBody.setColor(0xFFE6E6E6);
		            middleCircleBody.setColor(Color.parseColor(COLORS[i]));
		           // middleCircleBody.setStrokeWidth(10);
		           
		            finalOVal.set(px-middleCircleRadius, py-middleCircleRadius, px+middleCircleRadius, py+middleCircleRadius);
		            middleOval.set(px-middleCircleRadius, py-middleCircleRadius, px+middleCircleRadius, py+middleCircleRadius);
		                
		            canvas.drawArc(rect, startAngle, sweepAngle, true, middleCircleBody);
		            //canvas.drawArc(rect, startAngle, sweepAngle, true, pencil);
		            
		            Path mArc = new Path();
		            
		            Paint mPaintText = new Paint(Paint.ANTI_ALIAS_FLAG);
		            mArc.addArc(rect, startAngle, sweepAngle);
		            mPaintText = new Paint(Paint.ANTI_ALIAS_FLAG);
		            //mPaintText.setStyle(Paint.Style.FILL_AND_STROKE);
		            mPaintText.setColor(Color.WHITE);
		            mPaintText.setTypeface(tf);
		            mPaintText.setTextSize((int)(0.058334 * Common.sessionDeviceWidth));		            
		          
		            canvas.drawTextOnPath(name[i], mArc,  40, 70, mPaintText);
		            startAngle += sweepAngle;
		           
		           canvas.save();
		           canvas.restore();
		       	
	     }
	        
	        Paint circlePaint = new Paint(Paint.ANTI_ALIAS_FLAG);
	        circlePaint.setStyle(Paint.Style.STROKE);
	        circlePaint.setStrokeWidth(10);
	        //circlePaint.setColor(colors[i]);
	        int init, fina;
	        init = 0;
	        fina = 360;
	        int i=0;
	        while(init<fina)
	        {
	            circlePaint.setColor(Color.parseColor("#976D2A"));
	            canvas.drawArc(rect,init,10,false, circlePaint);
	            i++;
	            if(i>=colors.length)
	            {
	                i=0;
	            }
	            init = init + 10;
	
	        }
	        
	     /*   Paint paint1 = new Paint();
	        paint1.setColor(0xcc000000);
	        Paint transparentPaint = new Paint();
	        transparentPaint.setColor(getResources().getColor(android.R.color.transparent));
	        transparentPaint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.CLEAR));
	        canvas.drawCircle(getWidth()/4, getHeight()/4, 140, transparentPaint);*/
	       /* float[] direection = new float[]{1,1,1};
	        float light = 0.4f;
	        float specualr = 6;
	        float blur = 3.5f;   
	        
	        Paint bigArc = new Paint(Paint.ANTI_ALIAS_FLAG);
	        bigArc.setColor(0xFF424242);
	        
	        bigArc.setStyle(Paint.Style.FILL);
	        EmbossMaskFilter forBig = new EmbossMaskFilter(direection, 1f, 4, 2.5f);
	
	        bigArc.setMaskFilter(forBig);
	        int px = getMeasuredWidth()/2;
	        int py = getMeasuredHeight();
	
	        canvas.drawCircle(px/2, py/5, 35, bigArc);*/
	       // canvas.drawCircle(px, py-10, 20, smallCircleCore);
	        
	        
	        Rect rect2 = new  Rect();
	        int mImgRadius = (int)(0.1 * Common.sessionDeviceWidth);
	        rect2.set(getWidth()/2- mImgRadius, getHeight()/2 - mImgRadius, getWidth()/2 +mImgRadius, getHeight()/2 + mImgRadius);
	        //(putBitmapTo(150, 80, 30, px1+20, py1))
	         canvas.drawBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.inner), null, rect2 , null);
	      //  canvas.drawBitmap(BitmapFactory.decodeResource(getResources(), R.drawable.inner), null, (putBitmapTo(150, 80, 30, px1+20, py1)) , null);
	        checkSelection(canvas);
	        
	        
	        Paint paint4 = new Paint(Paint.ANTI_ALIAS_FLAG);
	        paint4.setStyle(Paint.Style.STROKE);
	        paint4.setStrokeWidth(1);	       
	        RectF rect3 = new  RectF();
	        int mInnerRadius = (int)(0.25 * Common.sessionDeviceWidth);
	        rect3.set(getWidth()/2- mInnerRadius, getHeight()/2 - mInnerRadius, getWidth()/2 +mInnerRadius, getHeight()/2 + mInnerRadius);
	        init = 0;
	        fina = 360;
	        int j=0;
	        while(init<fina)
	        {
	        	paint4.setColor(Color.GRAY);
	            canvas.drawArc(rect3,init,10,false, paint4);
	            j++;
	            if(j>=colors.length)
	            {
	                j=0;
	            }
	            init = init + 10;
	
	        }
	        
	    	 }catch (Exception e) {
	    		 e.printStackTrace();
 		    }
    }
      
 
    
    
    
    
    private RectF putBitmapTo(int startAngle, int SweepAngle, float radius, float centerX, float centerY)
    {
        float locx = (float) (centerX +((radius/17*11)*Math.cos(Math.toRadians(
                                                         (startAngle+SweepAngle+startAngle)/2
                                                         ))
                                                         ));
        float locy = (float) (centerY + ((radius/17*11)*Math.sin(Math.toRadians(
                                                         (startAngle+SweepAngle+startAngle)/2
                                                         ))
                                                         ));

        return new RectF(locx-20, locy-20, locx+20, locy+20);   

    }
  Boolean touchFlag = true;
  Boolean mAllowMove = false;
    @Override
    public boolean onTouchEvent(MotionEvent e) {
    	int state = e.getAction();
		int eventX = (int) e.getX();
		int eventY = (int) e.getY();
		
		if(touchFlag){	
			try{
			switch(state) {
			case MotionEvent.ACTION_DOWN:
				// reset the touched quadrants
		        for (int i = 0; i < mQuadrantTouched.length; i++) {
		        	mQuadrantTouched[i] = false;
		        }				
				mStartAngle = getAngle(eventX, eventY);			
				mAllowRotating = false;				
				
				break;
			case MotionEvent.ACTION_MOVE:
				double currentAngle = getAngle(eventX, eventY);
				mRotationAngle += mStartAngle - currentAngle;
				mStartAngle = currentAngle;
				
				for(Wedge w : mWedges) {
					w.minValue += normalizeAngle(mRotationAngle);
					w.midValue += normalizeAngle(mRotationAngle);
					w.maxValue += normalizeAngle(mRotationAngle);
				}
				mAllowMove =  true;
				//playSound(8);
				break;
			case MotionEvent.ACTION_UP:
				mAllowRotating = true;
				if(mAllowMove){
					touchFlag = false;
					if (soundPool != null) {
						soundPool.play(clickSoundId, 1.0f, 1.0f, 0, -1, 1.0f);
					}
					mHandler.postDelayed(new Runnable() {
			            @Override
				            public void run() {
			            	 if (soundPool != null) {
			 					soundPool.autoPause();
			 				   }
			            	    releaseSoundPool();
			            	    
			            	    double totalRotation = mRotationAngle % 360;

			                    //represent total rotation in positive value
			                    if (totalRotation < 0) {
			                        totalRotation = 360 + mRotationAngle;
			                    }

			                    //calculate the no of divs the rotation has crossed
			                    int no_of_divs_crossed = (int) ((totalRotation) / sweepAngle);
			                    
			                    int top = 6;
			                    
			                    //calculate current top
			                    top = (numberOfSegments + top - no_of_divs_crossed) % numberOfSegments;

			                    //for next rotation, the initial total rotation will be the no of degrees
			                    // inside the current top
			                    totalRotation = totalRotation % sweepAngle;

			                    
			                    int selectedPosition;
								//set the currently selected option
			                    if (top == 0) {
			                        selectedPosition = numberOfSegments - 1;//loop around the array
			                    } else {
			                        selectedPosition = top - 1;
			                    }
			            	    
			            	    Intent intent = new Intent(mContext,GameOffer.class);
				            	intent.putExtra("jsonObject", new SpinWheel().arrOfferDetails.get(selectedPosition).toString());
				            	intent.putExtra("game_rules_url", new SpinWheel().gameRules);
				            	activitythis.startActivity(intent);	
				            	new SpinWheel().arrOfferDetails = new ArrayList<JSONObject>();
				            	new SpinWheel().gameRules = "";
				            	activitythis.finish();
				            	activitythis.overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
				            }
			            
				        }, 4500);
				}
				
				break;
			}
			
				mQuadrantTouched[getQuadrant(e.getX() - (getWidth() / 2), getHeight() - e.getY() - (getHeight() / 2))] = true;
				mDetector.onTouchEvent(e);
			}catch(Exception ex){
				ex.printStackTrace();
			}
				invalidate();
		    	return true;
			}else{
				return false;
		    }
    }
    
    public void releaseSoundPool() {
    	try{
			if (soundPool  != null) {
				soundPool.release();
				soundPool = null;
			}
    	}catch(Exception e){
    		e.printStackTrace();
    	}
	}
    private void checkSelection(Canvas canvas) {
		Matrix cmt = new Matrix();
		cmt.postRotate((float)mRotationAngle, xPosition, yPosition);
		
		RectF f = new RectF();
		/*//mSelectionWedgeRect.set( mSelectionWedge.getWedgeRegion().getBounds() );
		for(int i = 0; i < mWedges.length; i++) {
			//mWedgeRect.set( mWedges[i].getWedgeRegion().getBounds() );
			mWedges[i].computeBounds(mWedgeRect, true);
			
			mWedgeRect.inset(6, 6);
			cmt.mapRect(mWedgeRect);
			//mPaint.setColor(Color.BLACK);
			//canvas.drawRect(mWedgeRect, mPaint);
			//mPaint.setColor(Color.RED);
			canvas.drawRect(mSelectionWedgeRect, mPaint);
			if( mWedgeRect.contains(mSelectionWedgeRect) ) {
				if(mListener != null) {
					if(mCurrentSelection != mMenuEntries.get(i)) {
						mListener.onMenuEntryChanged(mMenuEntries.get(i));
						mCurrentSelection = mMenuEntries.get(i);
						System.out.println(mWedges[i]);
						System.out.println(mSelectionWedge);
						//mRotationAngle += mSelectionWedge.midValue - mWedges[i].midValue;
					}
				}
			}
		}*/
	}
    private void determineWedges() {
    	try{
    	int entriesQty = name.length;
	    if ( entriesQty > 0) {
		    int mWedgeQty = entriesQty;
		
	    	float degSlice = 360 / mWedgeQty;
			float start_degSlice = 270 - (degSlice/2);
	    	//calculates where to put the images
			double rSlice = (2*Math.PI) / mWedgeQty;
			double rStart = (2*Math.PI)*(0.75) ;
			
			
			this.mWedges = new Wedge[mWedgeQty];
			
			double mid = 0, min = 0, max = 0;
			for(int i = 0; i < this.mWedges.length; i++) {
				this.mWedges[i] = new Wedge(xPosition, yPosition, mMinSize, mMaxSize, (i
						* degSlice)+start_degSlice, degSlice);
				float xCenter = (float)(Math.cos((rSlice*i)+ rStart) * (mMaxSize+mMinSize)/2)+xPosition;
				float yCenter = (float)(Math.sin((rSlice*i)+rStart) * (mMaxSize+mMinSize)/2)+yPosition;
				
				mid = this.mWedges[i].midValue = normalizeAngle( ((i * degSlice) + start_degSlice + degSlice) / 2 );
				min = normalizeAngle( (i * degSlice) + start_degSlice );
				max = normalizeAngle( (i * degSlice) + start_degSlice + degSlice);
				
				this.mWedges[i].minValue = min;
				this.mWedges[i].midValue = mid;
				this.mWedges[i].maxValue = max;
				
				/*if (mWedges[i].maxValue - degSlice < - 360) {
		            mid = 360;
		            mWedges[i].midValue = mid;
		            mWedges[i].minValue = Math.abs(mWedges[i].maxValue);
		            min = mWedges[i].minValue;
		        }*/
				
				if( i == mWedgeQty / 2) {
					mSelectionWedge = new Wedge(xPosition, yPosition, 0, mMaxSize, ( i * degSlice) +  start_degSlice , degSlice );
					mSelectionWedge.midValue = mid;
					mSelectionWedge.minValue = min;
					mSelectionWedge.maxValue = max;
				}
				
				//mid -= degSlice;
			
				
			
			    mViewRect.union( new RectF( mWedges[i].getWedgeRegion().getBounds() ) );
			}
	    	
			mSelectionWedge = new Wedge(xPosition, yPosition, 0, mMaxSize, ( (mWedgeQty / 2) * degSlice) +  start_degSlice + (degSlice / 4), degSlice / 2 );
			
			
			//mSelectionWedgeRect = new RectF( mSelectionWedge.getWedgeRegion().getBounds() );
			
			////WedgeRect = new RectF();
			
			//Reduce the selectionWedge region with 50% to be fully contained inside the other wedges
			Region selectionWedgeRegion = mSelectionWedge.getWedgeRegion();
			Rect r = selectionWedgeRegion.getBounds();
			//selectionWedgeRegion.set(r.left + scalePX(20), r.top + (r.height() / 2)  , r.right - scalePX(20), r.bottom - scalePX(6));
			
			invalidate();
			
			
	    }
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
    private double normalizeAngle(double angle) {
		if(angle >= 0) {
			while( angle > 360 ) {
				angle -= 360;
			}
		}
		else {
			while( angle < -360) {
				angle += 360;
			}
		}
		return angle;
	}
    int i=0;
   private class MyGestureDetector extends SimpleOnGestureListener {
    	
        @Override
        public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {
            // get the quadrant of the start and the end of the fling
            int q1 = getQuadrant(e1.getX() - (mViewRect.width() / 2), mViewRect.height() - e1.getY() - (mViewRect.height() / 2));
            int q2 = getQuadrant(e2.getX() - (mViewRect.width() / 2), mViewRect.height() - e2.getY() - (mViewRect.height() / 2));
            // the inversed rotations
          /*  if ((q1 == 2 && q2 == 2 && Math.abs(velocityX) < Math.abs(velocityY))
                    || (q1 == 3 && q2 == 3)
                    || (q1 == 1 && q2 == 3)
                    || (q1 == 4 && q2 == 4 && Math.abs(velocityX) > Math.abs(velocityY))
                    || ((q1 == 2 && q2 == 3) || (q1 == 3 && q2 == 2))
                    || ((q1 == 3 && q2 == 4) || (q1 == 4 && q2 == 3))
                    || (q1 == 2 && q2 == 4 && mQuadrantTouched[3])
                    || (q1 == 4 && q2 == 2 && mQuadrantTouched[3]) ) {
                SpinCircle.this.post(new FlingRunnable(-1 * (velocityX + velocityY)));
            } else {
            	
                // the normal rotation
            	SpinCircle.this.post(new FlingRunnable(velocityX + velocityY));
            }*/
            i++;
            Log.e("MyGestureDetector",""+i);
            SpinCircle.this.post(new FlingRunnable(velocityX + velocityY));
            return true;
        }
   }
   
	/**
	 * A {@link Runnable} for animating the the dialer's fling.
	 */
	private class FlingRunnable implements Runnable {
	    private float velocity;
	    public FlingRunnable(float velocity) {
	        this.velocity = velocity;
	    }
	    @Override
	    public void run() {
	        if (Math.abs(velocity) > 5 && mAllowRotating) {
	        	Log.e("velocity",""+velocity);
	            mRotationAngle += velocity / 75;
	            Log.e("fling mRotationAngle",""+mRotationAngle);
	            invalidate();
	            velocity /= 1.0666F;
	            // post this instance again
	            SpinCircle.this.post(this);
	           
	        }else{
	        	Log.e("stop","stop");
	        }
	    }	   
	}
  
	 private double getAngle(double xTouch, double yTouch) {
	    	double x = xTouch - (getWidth() / 2d);
	        double y = getHeight() - yTouch - (getHeight()/ 2d);
	        switch (getQuadrant(x, y)) {
	            case 1:
	                return Math.asin(y / Math.hypot(x, y)) * 180 / Math.PI;
	            case 2:
	                return 180 - Math.asin(y / Math.hypot(x, y)) * 180 / Math.PI;
	            case 3:
	                return 180 + (-1 * Math.asin(y / Math.hypot(x, y)) * 180 / Math.PI);
	            case 4:
	                return 360 + Math.asin(y / Math.hypot(x, y)) * 180 / Math.PI;
	            default:
	                return 0;
	        }
	    }
	    /**
	     * @return The selected quadrant.
	     */
	    private static int getQuadrant(double x, double y) {
	        if (x >= 0) {
	            return y >= 0 ? 1 : 4;
	        } else {
	            return y >= 0 ? 2 : 3;
	        }
	    }
	    
	    private class Wedge extends Path {
	    	private int x, y;
	    	private int InnerSize, OuterSize;
	    	private float StartArc;
	    	private float ArcWidth;
	    	private Region mWedgeRegion;
	    	
	    	public double minValue;
	    	public double midValue;
	    	public double maxValue;
	    	
	    	private Wedge(int x, int y, int InnerSize, int OuterSize, float StartArc, float ArcWidth) {
	    		super();
	    		
	    		if (StartArc >= 360) {
	    			StartArc = StartArc-360;
	    		}
	    		
	    		minValue = midValue = maxValue = 0;
	    		mWedgeRegion = new Region();
	    		this.x = x; this.y = y;
	    		this.InnerSize = InnerSize;
	    		this.OuterSize = OuterSize;
	    		this.StartArc = StartArc;
	    		this.ArcWidth = ArcWidth;
	    		this.buildPath();
	    	}
	    	
	    	public String toString() {
	    		return minValue + "  " + midValue + "  " + maxValue;
	    	}
	    	/**
	    	 * 
	    	 * @return the bottom rect that will be used for intersection 
	    	 */
	    	public Region getWedgeRegion() {
	    		return mWedgeRegion;
	    	}
	    	
	    	private void buildPath() {

	    	    final RectF rect = new RectF();
	    	    final RectF rect2 = new RectF();
	    	    
	    	    //Rectangles values
	    	    rect.set(this.x-this.InnerSize, this.y-this.InnerSize, this.x+this.InnerSize, this.y+this.InnerSize);
	    	    rect2.set(this.x-this.OuterSize, this.y-this.OuterSize, this.x+this.OuterSize, this.y+this.OuterSize);
	    	   	
	    	    
	    		this.reset();
	    		//this.moveTo(100, 100);
	    		this.arcTo(rect2, StartArc, ArcWidth);
	    		this.arcTo(rect, StartArc+ArcWidth, -ArcWidth);
	    				
	    		this.close();
	    		
	    		
	    		mWedgeRegion.setPath( this, new Region(0,0,480,800) );

	    	}
	    }
	    
	    public interface WheelChangeListener {
	        /**
	         * Called when user selects a new position in the wheel menu.
	         *
	         * @param selectedPosition the new position selected.
	         */
	        public void onSelectionChange(int selectedPosition);
	    }

	    
}  
