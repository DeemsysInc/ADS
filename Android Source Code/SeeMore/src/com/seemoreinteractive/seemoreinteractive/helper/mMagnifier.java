package com.seemoreinteractive.seemoreinteractive.helper;  


import com.seemoreinteractive.seemoreinteractive.R;

import android.content.Context;
import android.graphics.Bitmap;  
import android.graphics.BitmapFactory;
import android.graphics.Canvas;  
import android.graphics.Matrix;  
import android.graphics.Paint;  
import android.graphics.Path;  
import android.graphics.Path.Direction;  
import android.graphics.drawable.BitmapDrawable;  
import android.os.Handler;  
import android.os.Message;  
import android.util.AttributeSet;  
import android.util.Log;  
import android.view.MotionEvent;  
import android.widget.ImageView;  
  
public class mMagnifier extends ImageView {  
      
    //  
    private static final int RADIUS = 80;  
    //  
    private static final int FACTOR = 2;  
  
    private Matrix matrix = new Matrix();  

	 int width;
	  int height;
      
      
    public static final String TAG = "MagnifierView";  
  
    private Paint mPaint = new Paint();  
    private Bitmap mBitmap,bm; 
      
    private float mPointX = -1.0f, mPointY = -1.0f;  
      
    private boolean isFirstDraw = true;  
      
    Handler mHandler = new Handler(){  
            @Override
			public void dispatchMessage(Message msg) {  
                    switch(msg.what){  
                    case -1:  
                            postInvalidate();  
                            break;  
                    }  
            };  
    };  
  
    public mMagnifier(Context context, AttributeSet attrs) {  
        super(context, attrs);  
        try{
	        Bitmap bImage = ((BitmapDrawable)this.getDrawable()).getBitmap();            
	        mPaint.setAntiAlias(true);          
	        mBitmap = bImage;              
	        matrix.setScale(FACTOR, FACTOR); 
	        bm = BitmapFactory.decodeResource(getResources(), R.drawable.magnifying_glass);
			bm =Bitmap.createScaledBitmap(bm, 300, 200, false);
        }catch(Exception e){
        	e.printStackTrace();
        }
		
          
    }  
    
    public mMagnifier(Context context,  AttributeSet attrs, ImageView image) {
    	super(context, attrs);  
    	try{
	        Bitmap bImage = ((BitmapDrawable)image.getDrawable()).getBitmap();            
	        mPaint.setAntiAlias(true);          
	        mBitmap = bImage;              
	        matrix.setScale(FACTOR, FACTOR); 
	        bm = BitmapFactory.decodeResource(getResources(), R.drawable.magnifying_glass);
			bm =Bitmap.createScaledBitmap(bm, 300, 200, false);
    	}catch(Exception e){
    		e.printStackTrace();
    	}
	}

	@Override
	 protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) 
	 {
	  super.onMeasure(widthMeasureSpec, heightMeasureSpec);
	  try{

	   width = MeasureSpec.getSize(widthMeasureSpec);
	   height = MeasureSpec.getSize(heightMeasureSpec);
	   this.setMeasuredDimension(width, height );
	  }catch(Exception e){
		  e.printStackTrace();
	  }
	 }

    @Override
    protected void onDraw(Canvas canvas) {
    	try{
			if(width > 0 && height >0){
				mBitmap =Bitmap.createScaledBitmap(mBitmap, width, height, false);
				bm = Bitmap.createScaledBitmap(bm, 315, 200, false);
		  	}
            canvas.drawBitmap(mBitmap, 0, 0, mPaint);
            if (isFirstDraw) {
                    isFirstDraw = false;
            } else {
                    drawMagnifierPart(canvas);
            }
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
      
    //  
    private void drawMagnifierPart(Canvas canvas){  
    	try{
            if(mPointX == -1.0f || mPointY == -1.0f) return;  
              
            Path path = new Path();  
            path.addCircle(RADIUS, RADIUS, RADIUS, Direction.CW);  
            
            canvas.drawBitmap(mBitmap, 0, 0, null); 
          
            canvas.translate(mPointX - RADIUS, mPointY - 150);  
            canvas.clipPath(path);              
            canvas.translate(RADIUS-mPointX*FACTOR, RADIUS-mPointY*FACTOR);                
            canvas.drawBitmap(mBitmap, matrix, null);  
            canvas.save();
            
    		canvas.translate(mPointX-RADIUS ,mPointY-RADIUS);
    		canvas.drawBitmap(bm, mPointX,mPointY, null);
    		canvas.translate(RADIUS-mPointX*FACTOR, RADIUS-mPointY*FACTOR);
    		canvas.restore();
    	}catch(Exception e){
    		e.printStackTrace();
    	}
              
    }  
  
    @Override  
    public boolean onTouchEvent(MotionEvent event) {  
            switch(event.getAction()){  
            case MotionEvent.ACTION_DOWN:  
            case MotionEvent.ACTION_MOVE:  
                    Log.d(TAG, "action move");  
                    mPointX = event.getX();  
                    mPointY = event.getY();  
                    mHandler.sendEmptyMessage(-1);  
                    break;  
            case MotionEvent.ACTION_CANCEL:  
            case MotionEvent.ACTION_UP:  
                    mPointX = -1.0f;  
                    mPointY = -1.0f;  
                    mHandler.sendEmptyMessage(-1);  
                    break;  
            }  
            return true;  
    }  
}  