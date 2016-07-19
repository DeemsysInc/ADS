package com.seemoreinteractive.seemoreinteractive.wheel;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.Camera;
import android.graphics.Canvas;
import android.graphics.LinearGradient;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.PorterDuff.Mode;
import android.graphics.PorterDuffXfermode;
import android.graphics.Rect;
import android.graphics.Shader.TileMode;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.util.AttributeSet;
import android.util.Log;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.GestureDetector;
import android.view.Gravity;
import android.view.HapticFeedbackConstants;
import android.view.KeyEvent;
import android.view.MotionEvent;
import android.view.SoundEffectConstants;
import android.view.View;
import android.view.ViewConfiguration;
import android.view.ViewGroup;
import android.view.animation.Transformation;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;

import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.WheelOfDeals;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlowAdapter;
import com.seemoreinteractive.seemoreinteractive.helper.Common;


/**
 * 
 * @author kushnarev
 * Carousel class
 */
public class Carousel extends CarouselSpinner implements GestureDetector.OnGestureListener {
		    
	
	// Static private members

	/**
	 * Tag for a class logging
	 */
	private static final String TAG = Carousel.class.getSimpleName();

	/**
	 * If logging should be inside class
	 */
	private static final boolean localLOGV = false;
	
	/**
	 * Default min quantity of images
	 */
	private static final int MIN_QUANTITY = 3;
	
	/**
	 * Default max quantity of images
	 */
	private static final int MAX_QUANTITY = 12;
	
	/**
	 * Max theta
	 */
	private static final float MAX_THETA = 15.0f;

	/**
     * Duration in milliseconds from the start of a scroll during which we're
     * unsure whether the user is scrolling or flinging.
     */
    private static final int SCROLL_TO_FLING_UNCERTAINTY_TIMEOUT = 250;
	
	// Private members		
    
    /**
     * The info for adapter context menu
     */
    private AdapterContextMenuInfo mContextMenuInfo;
    
    /**
     * How long the transition animation should run when a child view changes
     * position, measured in milliseconds.
     */
    private int mAnimationDuration = 900;    
    
	/**
	 * Camera to make 3D rotation
	 */
	private Camera mCamera = new Camera();

    /**
     * Sets mSuppressSelectionChanged = false. This is used to set it to false
     * in the future. It will also trigger a selection changed.
     */
    private Runnable mDisableSuppressSelectionChangedRunnable = new Runnable() {
        public void run() {
            mSuppressSelectionChanged = false;
            selectionChanged();
        }
    };
    	
    /**
     * The position of the item that received the user's down touch.
     */
    private int mDownTouchPosition;
	
    /**
     * The view of the item that received the user's down touch.
     */
    private View mDownTouchView;
        
    /**
     * Executes the delta rotations from a fling or scroll movement. 
     */
    public FlingRotateRunnable mFlingRunnable = new FlingRotateRunnable();
    
    /**
     * Helper for detecting touch gestures.
     */
    private GestureDetector mGestureDetector;
	
    /**
     * Gravity for the widget
     */
    private int mGravity;
    	
    /**
     * If true, this onScroll is the first for this user's drag (remember, a
     * drag sends many onScrolls).
     */
    private boolean mIsFirstScroll;
    
    /**
     * Set max qantity of images
     */
    private int mMaxQuantity = MAX_QUANTITY;
    
    /**
     * Set min quantity of images
     */
    private int mMinQuantity = MIN_QUANTITY;
    
    /**
     * If true, we have received the "invoke" (center or enter buttons) key
     * down. This is checked before we action on the "invoke" key up, and is
     * subsequently cleared.
     */
    private boolean mReceivedInvokeKeyDown;
        
    /**
     * The currently selected item's child.
     */
    private View mSelectedChild;
        

    /**
     * Whether to continuously callback on the item selected listener during a
     * fling.
     */
    private boolean mShouldCallbackDuringFling = true;
    
    /**
     * Whether to callback when an item that is not selected is clicked.
     */
    private boolean mShouldCallbackOnUnselectedItemClick = true;
    
    /**
     * When fling runnable runs, it resets this to false. Any method along the
     * path until the end of its run() can set this to true to abort any
     * remaining fling. For example, if we've reached either the leftmost or
     * rightmost item, we will set this to true.
     */
    private boolean mShouldStopFling;
        
    /**
     * If true, do not callback to item selected listener. 
     */
    private boolean mSuppressSelectionChanged;
    
	/**
	 * The axe angle
	 */
    private float mTheta = (float)(15.0f*(Math.PI/180.0));

    //private float mTheta = (float)(0.0f*(Math.PI/180.0)); 
    
    /**
     * If items should be reflected
     */
    private boolean mUseReflection;

    // Constructors

	public Carousel(Context context)  {
		this(context, null);
		Log.e("carosel0","attr");
		try{
			// It's needed to make items with greater value of
			// z coordinate to be behind items with lesser z-coordinate
			setChildrenDrawingOrderEnabled(true);
			
			// Making user gestures available 
			mGestureDetector = new GestureDetector(this.getContext(), this);
			mGestureDetector.setIsLongpressEnabled(true);
						
			
			setStaticTransformationsEnabled(true);
			
			mAnimationDuration = 400;
			mUseReflection = false;
			int min = 3;
			int max = 5;
			
			float mTheta = 0.0f;
			if(mTheta > MAX_THETA || mTheta < 0.0f)
				mTheta = MAX_THETA;
			
			mMinQuantity = min < MIN_QUANTITY ? MIN_QUANTITY : min;
			mMaxQuantity = max > MAX_QUANTITY ? MAX_QUANTITY : max;
			
			// It's needed to apply 3D transforms to items
			// before they are drawn
			
			 
			//ImageAdapter adapter = new ImageAdapter(getContext());
			//adapter.SetImagesNew(WheelOfDeals.arrImagesBitmap, WheelOfDeals.arrImagesOfferId, false);
				
			//setAdapter(adapter);
			    
			 /* int selectedItem = 0;
			    if(selectedItem < 0 || selectedItem >= adapter.getCount())
			    	selectedItem = 0;

			    // next time we go through layout with this value
			    setNextSelectedPositionInt(0);
		        */
			
			 setNextSelectedPositionInt(0);

			}catch(Exception e){
				e.printStackTrace();
			}
			
	}
	
	public Carousel(Context context, AttributeSet attrs)  {
		super(context, attrs);
		Log.e("carosel1","attr");
		//super(context, attrs, 0);
		try{
			// It's needed to make items with greater value of
			// z coordinate to be behind items with lesser z-coordinate
			setChildrenDrawingOrderEnabled(true);
			
			// Making user gestures available 
			mGestureDetector = new GestureDetector(this.getContext(), this);
			mGestureDetector.setIsLongpressEnabled(true);
						
			
			setStaticTransformationsEnabled(true);
			
			mAnimationDuration = 400;
			mUseReflection = false;
			int min = 3;
			int max = 5;
			
			float mTheta = 0.0f;
			if(mTheta > MAX_THETA || mTheta < 0.0f)
				mTheta = MAX_THETA;
			
			mMinQuantity = min < MIN_QUANTITY ? MIN_QUANTITY : min;
			mMaxQuantity = max > MAX_QUANTITY ? MAX_QUANTITY : max;
			
			// It's needed to apply 3D transforms to items
			// before they are drawn
			
			 
			 
		//	ImageAdapter adapter = new ImageAdapter(getContext());
			//adapter.SetImagesNew(WheelOfDeals.arrImagesBitmap, WheelOfDeals.arrImagesOfferId, false);
				
			 // setAdapter(adapter);
			    
			 /* int selectedItem = 0;
			    if(selectedItem < 0 || selectedItem >= adapter.getCount())
			    	selectedItem = 0;
*/
			    // next time we go through layout with this value
			    setNextSelectedPositionInt(0);
		        

			}catch(Exception e){
				e.printStackTrace();
			}
	}
	
	public Carousel(Context context, AttributeSet attrs, int defStyle) {
		
		super(context, attrs, defStyle);
		try{/*
		// It's needed to make items with greater value of
		// z coordinate to be behind items with lesser z-coordinate
		setChildrenDrawingOrderEnabled(true);
		
		// Making user gestures available 
		mGestureDetector = new GestureDetector(this.getContext(), this);
		mGestureDetector.setIsLongpressEnabled(true);
		
		// It's needed to apply 3D transforms to items
		// before they are drawn
		setStaticTransformationsEnabled(true);
		
		// Retrieve settings
		TypedArray arr = getContext().obtainStyledAttributes(attrs, R.styleable.Carousel);
		mAnimationDuration = arr.getInteger(R.styleable.Carousel_android_animationDuration, 400);
		mUseReflection = arr.getBoolean(R.styleable.Carousel_UseReflection, false);	
		int selectedItem = arr.getInteger(R.styleable.Carousel_SelectedItem, 0);
		int imageArrayID = arr.getResourceId(R.styleable.Carousel_Items, -1);		
		//TypedArray images = getResources().obtainTypedArray(imageArrayID);
		
		// Retrieve names
		int namesForItems = arr.getResourceId(R.styleable.Carousel_Names, -1);
		
		TypedArray names = null;
		if(namesForItems != -1)
			names = getResources().obtainTypedArray(namesForItems);
		
		//ArrayList<Bitmap> images = WheelOfDeals.arrImagesBitmap;
		//ArrayList<String> names = WheelOfDeals.arrImagesOfferId;
	//	int selectedItem = images.size();
		int min = arr.getInteger(R.styleable.Carousel_minQuantity, MIN_QUANTITY);
		int max = arr.getInteger(R.styleable.Carousel_maxQuantity, MAX_QUANTITY);
		
		float mTheta = arr.getFloat(R.styleable.Carousel_maxTheta, MAX_THETA);
		if(mTheta > MAX_THETA || mTheta < 0.0f)
			mTheta = MAX_THETA;
		
		mMinQuantity = min < MIN_QUANTITY ? MIN_QUANTITY : min;
		mMaxQuantity = max > MAX_QUANTITY ? MAX_QUANTITY : max;
		
		if(arr.length() < mMinQuantity || arr.length() > mMaxQuantity)
			throw new IllegalArgumentException("Invalid set of items.");
								
		// Initialize image adapter
		//ImageAdapter adapter = new ImageAdapter(getContext());
		//adapter.SetImages(images, names, mUseReflection);
		
	    //setAdapter(adapter);

	    //if(selectedItem < 0 || selectedItem >= adapter.getCount())
	   // 	selectedItem = 0;

	    // next time we go through layout with this value
        //setNextSelectedPositionInt(selectedItem);
        
      //  images.recycle();        
        //if(names != null)
       // 	names.recycle();
		*/}catch(Exception e){
			e.printStackTrace();
		}
		
	}
	
	 public void setAdapter(ImageAdapter adapter) {
	    	try{
		        if (!(adapter instanceof ImageAdapter)) {
		            throw new ClassCastException(Carousel.class.getSimpleName() + " only works in conjunction with a " + Carousel.class.getSimpleName());
		        }		        
		        setAdapter(adapter);
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	    }
			
	// View overrides
	
	// These are for use with horizontal scrollbar
	
	/**
	 * Compute the horizontal extent of the horizontal scrollbar's thumb 
	 * within the horizontal range. This value is used to compute 
	 * the length of the thumb within the scrollbar's track.
	 */
    @Override
    protected int computeHorizontalScrollExtent() {
        // Only 1 item is considered to be selected
        return 1;
    }
    
    /**
     * Compute the horizontal offset of the horizontal scrollbar's 
     * thumb within the horizontal range. This value is used to compute 
     * the position of the thumb within the scrollbar's track.
     */
    @Override
    protected int computeHorizontalScrollOffset() {
        // Current scroll position is the same as the selected position
        return mSelectedPosition;
    }
    
    /**
     * Compute the horizontal range that the horizontal scrollbar represents.
     */
    @Override
    protected int computeHorizontalScrollRange() {
        // Scroll range is the same as the item count
        return mItemCount;
    }
        
    /**
     * Implemented to handle touch screen motion events.
     */
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        boolean retValue = false;
    	try{
        // Give everything to the gesture detector
         retValue = mGestureDetector.onTouchEvent(event);

        int action = event.getAction();
        if (action == MotionEvent.ACTION_UP) {
            // Helper method for lifted finger
            onUp();
        } else if (action == MotionEvent.ACTION_CANCEL) {
            onCancel();
        }
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    	
    
		return retValue;
    }	    
    
    /**
     * Extra information about the item for which the context menu should be shown.
     */
    @Override
    protected ContextMenuInfo getContextMenuInfo() {
        return mContextMenuInfo;
    }    

    /**
     * Bring up the context menu for this view.
     */
    @Override
    public boolean showContextMenu() {
        try{
	        if (isPressed() && mSelectedPosition >= 0) {
	            int index = mSelectedPosition - mFirstPosition;
	            View v = getChildAt(index);
	            return dispatchLongPress(v, mSelectedPosition, mSelectedRowId);
	        }             
        }catch(Exception e){
        	e.printStackTrace();
        }
        
        return false;
    }
    
    /**
     * Handles left, right, and clicking
     * @see android.view.View#onKeyDown
     */
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        switch (keyCode) {
            
        case KeyEvent.KEYCODE_DPAD_LEFT:
            ////if (movePrevious()) {
                playSoundEffect(SoundEffectConstants.NAVIGATION_LEFT);
            ////}
            return true;

        case KeyEvent.KEYCODE_DPAD_RIGHT:
            /////if (moveNext()) {
                playSoundEffect(SoundEffectConstants.NAVIGATION_RIGHT);
            ////}
            return true;

        case KeyEvent.KEYCODE_DPAD_CENTER:
        case KeyEvent.KEYCODE_ENTER:
            mReceivedInvokeKeyDown = true;
            // fallthrough to default handling
        }
        
        return super.onKeyDown(keyCode, event);
    }
    
    @Override
    public boolean onKeyUp(int keyCode, KeyEvent event) {
        switch (keyCode) {
        case KeyEvent.KEYCODE_DPAD_CENTER:
        case KeyEvent.KEYCODE_ENTER: {
            
            if (mReceivedInvokeKeyDown) {
                if (mItemCount > 0) {
    
                    dispatchPress(mSelectedChild);
                    postDelayed(new Runnable() {
                        public void run() {
                            dispatchUnpress();
                        }
                    }, ViewConfiguration.getPressedStateDuration());
    
                    int selectedIndex = mSelectedPosition - mFirstPosition;
                    performItemClick(getChildAt(selectedIndex), mSelectedPosition, mAdapter
                            .getItemId(mSelectedPosition));
                }
            }
            
            // Clear the flag
            mReceivedInvokeKeyDown = false;
            
            return true;
        }
        }

        return super.onKeyUp(keyCode, event);
    }    
        
    @Override
    protected void onFocusChanged(boolean gainFocus, int direction, Rect previouslyFocusedRect) {
        super.onFocusChanged(gainFocus, direction, previouslyFocusedRect);
        
        /*
         * The gallery shows focus by focusing the selected item. So, give
         * focus to our selected item instead. We steal keys from our
         * selected item elsewhere.
         */
        if (gainFocus && mSelectedChild != null) {
            mSelectedChild.requestFocus(direction);
        }

    }       
    
    // ViewGroup overrides
    
    @Override
    protected boolean checkLayoutParams(ViewGroup.LayoutParams p) {
        return p instanceof LayoutParams;
    }
    
    @Override
    protected ViewGroup.LayoutParams generateLayoutParams(ViewGroup.LayoutParams p) {
        return new LayoutParams(p);
    }
    
    @Override
    public ViewGroup.LayoutParams generateLayoutParams(AttributeSet attrs) {
        return new LayoutParams(getContext(), attrs);
    }
        
    @Override
    public void dispatchSetSelected(boolean selected) {
        /*
         * We don't want to pass the selected state given from its parent to its
         * children since this widget itself has a selected state to give to its
         * children.
         */
    }
    
    @Override
    protected void dispatchSetPressed(boolean pressed) {
        
        // Show the pressed state on the selected child
        if (mSelectedChild != null) {
            mSelectedChild.setPressed(pressed);
        }
    }
    
    @Override
    public boolean showContextMenuForChild(View originalView) {

        final int longPressPosition = getPositionForView(originalView);
        if (longPressPosition < 0) {
            return false;
        }
        
        final long longPressId = mAdapter.getItemId(longPressPosition);
        return dispatchLongPress(originalView, longPressPosition, longPressId);
    }
        
        
    @Override
    public boolean dispatchKeyEvent(KeyEvent event) {
        // Gallery steals all key events
        return event.dispatch(this, null, null);
    }
        
    /**
     * Index of the child to draw for this iteration
     */
    @Override
    protected int getChildDrawingOrder(int childCount, int i) {
    	// Sort Carousel items by z coordinate in reverse order
    	int idx = 0;
	    try{
	    	ArrayList<CarouselItem> sl = new ArrayList<CarouselItem>();
	    	for(int j = 0; j < childCount; j++)
	    	{
	    		
	    		CarouselItem view = (CarouselItem)getAdapter().getView(j,null, null);
	    		if(i == 0)
	    			view.setDrawn(false);
	    		sl.add((CarouselItem)getAdapter().getView(j,null, null));
	    	}
	
	    	Collections.sort(sl);
	    	
	    	// Get first undrawn item in array and get result index   	
	    	
	    	for(CarouselItem civ : sl)
	    	{
	    		if(!civ.isDrawn())
	    		{
	    			civ.setDrawn(true);
	    			idx = civ.getIndex();
	    			break;
	    		}
	    	}
	    	}catch(Exception e){
    		e.printStackTrace();
    	}
    	return idx;

    }
    
    /**
     * Transform an item depending on it's coordinates  
     */
	 @SuppressLint("NewApi") @Override
	protected boolean getChildStaticTransformation(View child, Transformation transformation) {
	   try{
				transformation.clear();
				transformation.setTransformationType(Transformation.TYPE_MATRIX);
				
				// Center of the view
				float centerX = (float)getWidth()/2, centerY = (float)getHeight()/2;
				
				// Save camera
				mCamera.save();
				
				// Translate the item to it's coordinates
				final Matrix matrix = transformation.getMatrix();
						
				mCamera.translate(((CarouselItem)child).getItemX(), ((CarouselItem)child).getItemY(), 
									((CarouselItem)child).getItemZ());
						
				// Align the item
				mCamera.getMatrix(matrix);
				
				float angle = ((CarouselItem)child).getCurrentAngle();
		    	//angle += deltaAngle;
		    	
		    	while(angle > 360.0f)
		    		angle -= 360.0f;
		    	
		    	while(angle < 0.0f)
		    		angle += 360.0f;
		    	
		    	((CarouselItem)child).setCurrentAngle(angle);
		    	/*if(i==0){
		    		child.setRotationX(angle+20);
		    	} else {
		        	child.setRotationX(angle-20);
		    		
		    	}*/
		    	((CarouselItem)child).setRotationX(angle);
						
				matrix.preTranslate(-centerX, -centerY);
				matrix.postTranslate(centerX, centerY);
				
				
			/*	float deform2 = 10f;
			    float[] src2 = new float[] { 0, 0, child.getWidth(), 0, child.getWidth(), child.getHeight(), 0, child.getHeight() };
			    float[] dst2 = new float[] { 0, 0, child.getWidth() - deform2, deform2, child.getWidth() - deform2, child.getHeight() - deform2, 0, child.getHeight() };
			    matrix.setPolyToPoly(src2, 0, dst2, 0, src2.length >> 1);
			    */
				float[] values = new float[9];
				matrix.getValues(values);
				
				// Restore camera
				mCamera.restore();
				
				Matrix mm = new Matrix();
				mm.setValues(values);
				((CarouselItem)child).setCIMatrix(mm);
		
				/*int currentIndex = ((CarouselItem)child).getIndex();
				int currentTop = ((CarouselItem)child).getTop();
				int currentBottom = ((CarouselItem)child).getBottom();
				
				Log.e("trans", ""+mSelectedPosition+" == "+currentIndex+" "+currentTop+" "+currentBottom);*/
				/*if(((CarouselItem)child).getIndex()==mSelectedPosition){
					((CarouselItem)child).setRotationX(360);
				}
				if(((CarouselItem)child).getIndex()<=mSelectedPosition){
					((CarouselItem)child).setRotationX(330);
				}
				if(((CarouselItem)child).getIndex()>=mSelectedPosition){
					((CarouselItem)child).setRotationX(45);
				}*/
				/*if(z>=400 && z<=420){
		        	child.setRotationX(45);
		        }
		        if(z >= 175 && z <= 176){
		        	child.setRotationX(360);
		        } 
		        if(z >= 420 && z<=430) {
		        	child.setRotationX(330);
		        } */
				//http://code.google.com/p/android/issues/detail?id=35178
				child.invalidate();				
			   }catch(Exception e){
				   e.printStackTrace();
			   }
		 return true;
	}     
    
    // CarouselAdapter overrides
    
	/**
	 * Setting up images
	 */
	@Override
	void layout(int delta, boolean animate){
		try{
        if (mDataChanged) {
            handleDataChanged();
        }
        
        // Handle an empty gallery by removing all views.
        if (getCount() == 0) {
            resetList();
            return;
        }
        
        // Update to the new selected position.
        if (mNextSelectedPosition >= 0) {
            setSelectedPositionInt(mNextSelectedPosition);
        }        
        
        // All views go in recycler while we are in layout
        recycleAllViews();        
        
        // Clear out old views
        detachAllViewsFromParent();
        
        
        int count = getAdapter().getCount();
        float angleUnit = 360.0f / count;

        float angleOffset = mSelectedPosition * angleUnit;
        for(int i = 0; i< getAdapter().getCount(); i++){
        	float angle = angleUnit * i - angleOffset;
        	if(angle < 0.0f)
        		angle = 360.0f + angle;
           	makeAndAddView(i, angle);        	
        }

        // Flush any cached views that did not get reused above
        mRecycler.clear();

        invalidate();

        setNextSelectedPositionInt(mSelectedPosition);
        
        checkSelectionChanged();
        
        ////////mDataChanged = false;
        mNeedSync = false;
        
        updateSelectedItemMetadata();
		}catch(Exception e){
			e.printStackTrace();
		}
	} 
    
	/**
	 * Setting up images after layout changed
	 */
    @Override
    protected void onLayout(boolean changed, int l, int t, int r, int b) {
        super.onLayout(changed, l, t, r, b);
        try{
        /*
         * Remember that we are in layout to prevent more layout request from
         * being generated.
         */
        mInLayout = true;
        layout(0, false);
        mInLayout = false;
        }catch(Exception e){
        	e.printStackTrace();
        }
    }	    
    	
    @Override
    void selectionChanged() {
        if (!mSuppressSelectionChanged) {
            super.selectionChanged();
        }
    }    
	
    @Override
    void setSelectedPositionInt(int position) {
    	try{
	        super.setSelectedPositionInt(position);
	        super.setNextSelectedPositionInt(position);
			newSelectedPosVal = position;
	        // Updates any metadata we keep about the selected item.
	        updateSelectedItemMetadata();
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
    	
      
    // Rotation class for the Carousel    
	
	public class FlingRotateRunnable implements Runnable {

        /**
         * Tracks the decay of a fling rotation
         */		
		public Rotator mRotator;

		/**
         * Angle value reported by mRotator on the previous fling
         */
        private float mLastFlingAngle;
        
        /**
         * Constructor
         */
        public FlingRotateRunnable(){
        	mRotator = new Rotator(getContext());
        }
        
        private void startCommon() {
            // Remove any pending flings
            removeCallbacks(this);
        }
        
        public void startUsingVelocity(float initialVelocity) {
        	try{
	            if (initialVelocity == 0) return;
	            
	            startCommon();
	                        
	            mLastFlingAngle = 0.0f;
	            
	           	mRotator.fling(initialVelocity);
	                        
	            post(this);
        	}catch(Exception e){
        		e.printStackTrace();
        	}
        }        
        
        
        public void startUsingDistance(float deltaAngle) {
        	try{
	            if (deltaAngle == 0) return;
	            
	            startCommon();
	            
	            mLastFlingAngle = 0;
	            synchronized(this)
	            {
	            	mRotator.startRotate(0.0f, -deltaAngle, mAnimationDuration);
	            }
	            post(this);
        	}catch(Exception e){
        		e.printStackTrace();
        	}
        }
        
        public void stop(boolean scrollIntoSlots) {
        	try{
        		removeCallbacks(this);
        		endFling(scrollIntoSlots);
        	}catch(Exception e){
        		e.printStackTrace();
        	}
        }        
        
        private void endFling(boolean scrollIntoSlots) {
        	try{
	            /*
	             * Force the scroller's status to finished (without setting its
	             * position to the end)
	             */
	        	synchronized(this){
	        		mRotator.forceFinished(true);
	        	}
	            
	            if (scrollIntoSlots) scrollIntoSlots();
        	}catch(Exception e){
        		e.printStackTrace();
        	}
        }
                		
		public void run() {
			try{
	            if (Carousel.this.getChildCount() == 0) {
	                endFling(true);
	                return;
	            }			
	            
	            mShouldStopFling = false;
	            
	            final Rotator rotator;
	            final float angle;
	            boolean more;
	            synchronized(this){
		            rotator = mRotator;
		            more = rotator.computeAngleOffset();
		            angle = rotator.getCurrAngle();	            
	            }
	            
	            
	            // Flip sign to convert finger direction to list items direction
	            // (e.g. finger moving down means list is moving towards the top)
	            float delta = mLastFlingAngle - angle;                        
	
	            //////// Shoud be reworked
	            trackMotionScroll(delta);
	            
	            if (more && !mShouldStopFling) {
	                mLastFlingAngle = angle;
	                post(this);
	            } else {
	                mLastFlingAngle = 0.0f;
	                endFling(true);
	            }            
			}catch(Exception e){
				e.printStackTrace();
			}
		}
		
	}
        
	// Image adapter class for the Carousel
	
	private class ImageAdapter extends BaseAdapter {

		private Context mContext;
		private CarouselItem[] mImages;		
		
		public ImageAdapter(Context c) {
			mContext = c;
		}		
						
		@SuppressWarnings("unused")
		public void SetImages(TypedArray array,TypedArray names){
			SetImages(array, names, true);
		}
		
		@SuppressLint("NewApi") 
		public void SetImages(TypedArray images, TypedArray names, boolean reflected){
			if(names != null)
				if(images.length() != names.length())
					throw new RuntimeException("Images and names arrays length doesn't match");
			
			final int reflectionGap = 4;
			
			Drawable[] drawables = new Drawable[images.length()];
			mImages = new CarouselItem[images.length()];
			
			for(int i = 0; i< images.length(); i++)
			{
				drawables[i] = images.getDrawable(i);
				Bitmap originalImage = ((BitmapDrawable)drawables[i]).getBitmap();
			//	Bitmap originalImage = images.get(i);
				if(reflected){
					int width = originalImage.getWidth();
					int height = originalImage.getHeight();
					Log.e("reflected", ""+width+" "+height);

					//float distance = 1;
					// This will not scale but will flip on the Y axis
					Matrix matrix = new Matrix();
					matrix.preScale(1, -1);
					/*matrix.preTranslate(-200/2f , -130/2f);
					matrix.postTranslate(200/2f , 130/2f);
					matrix.postRotate(45f);*/
					// Create a Bitmap with the flip matrix applied to it.
					// We only want the bottom half of the image
					Bitmap reflectionImage = Bitmap.createBitmap(originalImage, 0,
							height / 2, width, height / 2, matrix, false);

					// Create a new bitmap with same width but taller to fit
					// reflection
					Bitmap bitmapWithReflection = Bitmap.createBitmap(width,
							(height + height / 2), Config.ARGB_8888);

					
					// Create a new Canvas with the bitmap that's big enough for
					// the image plus gap plus reflection
					Canvas canvas = new Canvas(bitmapWithReflection);
					// Draw in the original image
					canvas.drawBitmap(originalImage, 0, 0, null);
					// Draw in the gap
					Paint deafaultPaint = new Paint();
					canvas.drawRect(0, height, width, height + reflectionGap,
							deafaultPaint);
					// Draw in the reflection
					canvas.drawBitmap(reflectionImage, 0, height + reflectionGap,
							null);

					// Create a shader that is a linear gradient that covers the
					// reflection
					Paint paint = new Paint();
					LinearGradient shader = new LinearGradient(0,
							originalImage.getHeight(), 0,
							bitmapWithReflection.getHeight() + reflectionGap,
							0x70ffffff, 0x00ffffff, TileMode.CLAMP);
					// Set the paint to use this shader (linear gradient)
					paint.setShader(shader);
					// Set the Transfer mode to be porter duff and destination in
					paint.setXfermode(new PorterDuffXfermode(Mode.DST_IN));
					// Draw a rectangle using the paint with our linear gradient
					canvas.drawRect(0, height, width,
							bitmapWithReflection.getHeight() + reflectionGap, paint);
					
					originalImage = bitmapWithReflection;
				}
								
				CarouselItem item = new CarouselItem(mContext);
				item.setIndex(i);
				item.setImageBitmap(originalImage);
				
				//Log.e("item.getIndex()", ""+item.getIndex()+" "+item.getTransitionName()+" "+item.getTranslationX()+" "+item.getTop()+" "+item.getBottom());
				/*if(item.getIndex() == 1){
					item.setRotationX(45);
		        }else if(item.getIndex() > 1){
		        	item.setRotationX(330);
		        }*/
				/*if(i==0){
					item.setRotationX(45);
				} 
				if(i==4){
					item.setRotationX(330);
				}
				*/
				Log.e("names", ""+names.getString(i));
				if(names != null){
					item.setText(names.getString(i));
				}
				mImages[i] = item;
				
				
			}
			
			
		}

		public int getCount() {
			if(mImages == null)
				return 0;
			else
				return mImages.length;
		}

		public Object getItem(int position) {
			return position;
		}

		public long getItemId(int position) {
			return position;
		}

		public View getView(int position, View convertView, ViewGroup parent) {
			return mImages[position];
		}

	}		
	
	// OnGestureListener implementation
	
	public boolean onDown(MotionEvent e) {
        // Kill any existing fling/scroll
        mFlingRunnable.stop(false);

        ///// Don't know yet what for it is
        // Get the item's view that was touched
       
        
        mDownTouchPosition = pointToPosition((int) e.getX(), (int) e.getY());
      
        if (mDownTouchPosition >= 0) {
            mDownTouchView = getChildAt(mDownTouchPosition - mFirstPosition);
            mDownTouchView.setPressed(true);
        }
        
        // Reset the multiple-scroll tracking state
        mIsFirstScroll = true;
        
        // Must return true to get matching events for this down event.
        return true;
	}	
	
    public boolean onFling(MotionEvent e1, MotionEvent e2, float velocityX, float velocityY) {

    	if (!mShouldCallbackDuringFling) {
            // We want to suppress selection changes
            
            // Remove any future code to set mSuppressSelectionChanged = false
            removeCallbacks(mDisableSuppressSelectionChangedRunnable);

            // This will get reset once we scroll into slots
            if (!mSuppressSelectionChanged) mSuppressSelectionChanged = true;
        }
        
        // Fling the gallery!
        
        //mFlingRunnable.startUsingVelocity((int) -velocityX);
       // mFlingRunnable.startUsingVelocity((int) velocityX);
    	
    	 mFlingRunnable.startUsingVelocity((int) velocityY);
        
        return true;
    }
					
	
	public void onLongPress(MotionEvent e) {
        
        if (mDownTouchPosition < 0) {
            return;
        }
        
        performHapticFeedback(HapticFeedbackConstants.LONG_PRESS);
        long id = getItemIdAtPosition(mDownTouchPosition);
        dispatchLongPress(mDownTouchView, mDownTouchPosition, id);
        
	}
			
	
	public boolean onScroll(MotionEvent e1, MotionEvent e2, float distanceX,
			float distanceY) {
			try{
	        if (localLOGV) Log.v(TAG, String.valueOf(e2.getX() - e1.getX()));
	        
	        /*
	         * Now's a good time to tell our parent to stop intercepting our events!
	         * The user has moved more than the slop amount, since GestureDetector
	         * ensures this before calling this method. Also, if a parent is more
	         * interested in this touch's events than we are, it would have
	         * intercepted them by now (for example, we can assume when a Gallery is
	         * in the ListView, a vertical scroll would not end up in this method
	         * since a ListView would have intercepted it by now).
	         */
	        
	        
	        getParent().requestDisallowInterceptTouchEvent(true);
	        
	        // As the user scrolls, we want to callback selection changes so related-
	        // info on the screen is up-to-date with the gallery's selection
	        if (!mShouldCallbackDuringFling) {
	            if (mIsFirstScroll) {
	                /*
	                 * We're not notifying the client of selection changes during
	                 * the fling, and this scroll could possibly be a fling. Don't
	                 * do selection changes until we're sure it is not a fling.
	                 */
	                if (!mSuppressSelectionChanged) mSuppressSelectionChanged = true;
	                postDelayed(mDisableSuppressSelectionChangedRunnable, SCROLL_TO_FLING_UNCERTAINTY_TIMEOUT);
	            }
	        } else {
	            if (mSuppressSelectionChanged) mSuppressSelectionChanged = false;
	        }
	        
	        // Track the motion
	      //  trackMotionScroll(/* -1 * */ (int) distanceX);
	        trackMotionScroll(/* -1 * */ (int) distanceY);
	       
	        mIsFirstScroll = false;
		}catch(Exception e){
			e.printStackTrace();
		}
        return true;
     }
	
	
	public boolean onSingleTapUp(MotionEvent e) {
		try{
	        if (mDownTouchPosition >= 0) {
	            
	            // Pass the click so the client knows, if it wants to.
	            if (mShouldCallbackOnUnselectedItemClick || mDownTouchPosition == mSelectedPosition) {
	                performItemClick(mDownTouchView, mDownTouchPosition, mAdapter
	                        .getItemId(mDownTouchPosition));
	            }
	            
	            return true;
	        }
		}catch(Exception ex){
			ex.printStackTrace();
		}
        
        return false;
    }
		
	
	///// Unused gestures
	public void onShowPress(MotionEvent e) {
	}
		
	
    /*private void Calculate3DPosition(CarouselItem child, int diameter, float angleOffset){
    	
    	angleOffset = angleOffset * (float)(Math.PI/180.0f);    	

    	float x = - (float)(diameter/2  * android.util.FloatMath.sin(angleOffset)) + diameter/2 - child.getWidth()/2;
    	float z = diameter/2 * (1.0f - (float)android.util.FloatMath.cos(angleOffset));
    	float y = - getHeight()/2 + (float) (z * android.util.FloatMath.sin(mTheta));

    	child.setItemX(x);
    	child.setItemZ(z);
    	child.setItemY(y);
    	
    }
    	*/
	
	/* private void Calculate3DPosition
		(CarouselItem child, int diameter, float angleOffset){
	    	angleOffset = angleOffset * (float)(Math.PI/180.0f);
	    	
	    	float x = -(float)(diameter/2*Math.sin(angleOffset));
	    	float z = diameter/2 * (1.0f - (float)Math.cos(angleOffset));
	    	float y = - getHeight()/2 + (float) (z * Math.sin(mTheta));
	    	
	    	child.setItemX(x);
	    	child.setItemZ(z);
	    	child.setItemY(y);
	    }*/
	
	/*private void Calculate3DPosition(CarouselItem child, int diameter, float angleOffset){
	    float x = (720 - child.getWidth())/2;       
	    float y = (float) (diameter / 2 * Math.sin(angleOffset)) + diameter / 2 - child.getWidth() / 2;
	    float z = diameter*2 * (1.0f - (float) Math.cos(angleOffset));

	    child.setItemX(x);
	    child.setItemZ(z);
	    child.setItemY(y-(1280 - child.getHeight())/2);
	}*/
	
	static int newSelectedPosVal = 0; 
	@SuppressLint("NewApi") 
	private void Calculate3DPosition(CarouselItem child, int diameter,
            float angleOffset) {
		try{
        angleOffset = angleOffset * (float) (Math.PI / 180.0f);
        float y = (float) (diameter / 2 * Math.sin(angleOffset)) + diameter / 2  - child.getWidth() / 2;
        float z;       
        float x = (Common.sessionDeviceWidth - child.getWidth())/2;
        z  = diameter / 2 * (0.65f - (float) Math.cos(angleOffset));
  	    y  = (float) (y-(Common.sessionDeviceHeight - child.getHeight())/2);
  	    
	  	child.setItemX(x);
	    child.setItemZ(z);
	    child.setItemY(y);
      
        //float z = diameter / 2 * (1.0f - (float) Math.cos(angleOffset));  
      /* if(Common.sessionDeviceDensity == 1){
        	  z = diameter / 2 * (0.75f - (float) Math.cos(angleOffset));
        	  y = y-(Common.sessionDeviceHeight - child.getHeight())/3;
        }else{
        	  z = diameter / 2 * (0.75f - (float) Math.cos(angleOffset));
        	  y = y-(Common.sessionDeviceHeight - child.getHeight())/2;
        }*/
      /*  float x = (Common.sessionDeviceWidth - child.getWidth())/2;  
        z = diameter / 2 * (0.75f - (float) Math.cos(angleOffset));
  	    y = y-(Common.sessionDeviceHeight - child.getHeight())/3;*/
       
		}catch(Exception e){
			e.printStackTrace();
		}
        
    }
	
    /**
     * Figure out vertical placement based on mGravity
     * 
     * @param child Child to place
     * @return Where the top of the child should be
     */
    private int calculateTop(View child, boolean duringLayout) {    	
        int myHeight = duringLayout ? getMeasuredHeight() : getHeight();
        int childHeight = duringLayout ? child.getMeasuredHeight() : child.getHeight(); 
        
        int childTop = 0;
        switch (mGravity) {
        case Gravity.TOP:
            childTop = mSpinnerPadding.top;
            break;
        case Gravity.CENTER_VERTICAL:
            int availableSpace = myHeight - mSpinnerPadding.bottom
                    - mSpinnerPadding.top - childHeight;
            childTop = mSpinnerPadding.top + (availableSpace / 2);
            break;
        case Gravity.BOTTOM:
            childTop = myHeight - mSpinnerPadding.bottom - childHeight;
            break;
        }
        return childTop;
    }    	
	
    private boolean dispatchLongPress(View view, int position, long id) {
        boolean handled = false;
        try{
	        if (mOnItemLongClickListener != null) {
	            handled = mOnItemLongClickListener.onItemLongClick(this, mDownTouchView,
	                    mDownTouchPosition, id);
	        }
	
	        if (!handled) {
	            mContextMenuInfo = new AdapterContextMenuInfo(view, position, id);
	            handled = super.showContextMenuForChild(this);
	        }
	
	        if (handled) {
	            performHapticFeedback(HapticFeedbackConstants.LONG_PRESS);
	        }
        }catch(Exception e){
        	e.printStackTrace();
        }
        return handled;
    }    
    
    private void dispatchPress(View child) {
    	try{        
	        if (child != null) {
	            child.setPressed(true);
	        }
	        
	        setPressed(true);
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
    
    private void dispatchUnpress() {
        try{
	        for (int i = getChildCount() - 1; i >= 0; i--) {
	            getChildAt(i).setPressed(false);
	        }
	        
	        setPressed(false);
        }catch(Exception e){
        	e.printStackTrace();
        }
    }            
	
    /**
     * @return The center of this Gallery.
     */
    private int getCenterOfGallery() {
        return (getWidth() - Carousel.this.getPaddingLeft() - Carousel.this.getPaddingRight()) / 2 + 
        	Carousel.this.getPaddingLeft();
    }	
	
    /**
     * @return The center of the given view.
     */
    private static int getCenterOfView(View view) {
        return view.getLeft() + view.getWidth() / 2;
    }	
		
    

    float getLimitedMotionScrollAmount(boolean motionToLeft, float deltaX) {
        int extremeItemPosition = motionToLeft ? Carousel.this.getCount() - 1 : 0;
        View extremeChild = getChildAt(extremeItemPosition - Carousel.this.getFirstVisiblePosition());
        
        if (extremeChild == null) {
            return deltaX;
        }
        
        int extremeChildCenter = getCenterOfView(extremeChild);
        int galleryCenter = getCenterOfGallery();
        
        if (motionToLeft) {
            if (extremeChildCenter <= galleryCenter) {
                
                // The extreme child is past his boundary point!
                return 0;
            }
        } else {
            if (extremeChildCenter >= galleryCenter) {

                // The extreme child is past his boundary point!
                return 0;
            }
        }	
        int centerDifference = galleryCenter - extremeChildCenter;

        return motionToLeft
                ? Math.max(centerDifference, deltaX)
                : Math.min(centerDifference, deltaX); 
    }
       
	
    int getLimitedMotionScrollAmount(boolean motionToLeft, int deltaX) {
        int extremeItemPosition = motionToLeft ? mItemCount - 1 : 0;
        View extremeChild = getChildAt(extremeItemPosition - mFirstPosition);
        
        if (extremeChild == null) {
            return deltaX;
        }
        
        int extremeChildCenter = getCenterOfView(extremeChild);
        int galleryCenter = getCenterOfGallery();
        
        if (motionToLeft) {
            if (extremeChildCenter <= galleryCenter) {
                
                // The extreme child is past his boundary point!
                return 0;
            }
        } else {
            if (extremeChildCenter >= galleryCenter) {

                // The extreme child is past his boundary point!
                return 0;
            }
        }
        
        int centerDifference = galleryCenter - extremeChildCenter;

        return motionToLeft
                ? Math.max(centerDifference, deltaX)
                : Math.min(centerDifference, deltaX); 
    }    
    
    private void makeAndAddView(int position, float angleOffset) {
    	try{
	        CarouselItem child;
	  
	        if (!mDataChanged) {
	            child = (CarouselItem)mRecycler.get(position);
	            if (child != null) {
	
	                // Position the view
	                setUpChild(child, child.getIndex(), angleOffset);
	            }
	            else
	            {
	                // Nothing found in the recycler -- ask the adapter for a view
	                child = (CarouselItem)mAdapter.getView(position, null, this);
	
	                // Position the view
	                setUpChild(child, child.getIndex(), angleOffset);            	
	            }
	            return;
	        }
	
	        // Nothing found in the recycler -- ask the adapter for a view
	        child = (CarouselItem)mAdapter.getView(position, null, this);
	
	        // Position the view
	        setUpChild(child, child.getIndex(), angleOffset);
    	}catch(Exception e){
    		e.printStackTrace();
    	}

    }      
    
    void onCancel(){
    	onUp();
    }

    /**
     * Called when rotation is finished
     */
    private void onFinishedMovement() {
    	try{
	        if (mSuppressSelectionChanged) {
	            mSuppressSelectionChanged = false;
	            
	            // We haven't been callbacking during the fling, so do it now
	            super.selectionChanged();
	        }
	    	checkSelectionChanged();
	        invalidate();
    	}catch(Exception e){
    		e.printStackTrace();
    	}

    }    
         
    void onUp(){
    	try{
	        if (mFlingRunnable.mRotator.isFinished()) {
	            scrollIntoSlots();
	        }        
	        dispatchUnpress();  
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
        
    /**
     * Brings an item with nearest to 0 degrees angle to this angle and sets it selected 
     */
    @SuppressLint("NewApi") private void scrollIntoSlots(){
    	try{
    	// Nothing to do
        if (getChildCount() == 0 || mSelectedChild == null) return;
        
        // get nearest item to the 0 degrees angle
        // Sort itmes and get nearest angle
    	float angle; 
    	int position;
    	
    	ArrayList<CarouselItem> arr = new ArrayList<CarouselItem>();
    	
        for(int i = 0; i < getAdapter().getCount(); i++)
        	arr.add(((CarouselItem)getAdapter().getView(i, null, null)));
        
        Collections.sort(arr, new Comparator<CarouselItem>(){

        	public int compare(CarouselItem c1, CarouselItem c2) {
				int a1 = (int)c1.getCurrentAngle();
				//Log.e("a1", ""+a1);
				if(a1 > 180)
					a1 = 360 - a1;
				int a2 = (int)c2.getCurrentAngle();
				//Log.e("a2", ""+a2+" "+a1);
				if(a2 > 180)
					a2 = 360 - a2;
				return (a1 - a2) ;
			}
        	
        });
        
        
        angle = arr.get(0).getCurrentAngle();
       // Log.e("angle def", ""+angle);
       boolean scrollFlag = true;
      
        // Make it minimum to rotate
    	if(angle > 180.0f){
    		angle = -(360.0f - angle);
    		scrollFlag = false;
    	}
    	
        // Start rotation if needed
        //if(angle != 0.0f)
    	if (Math.abs(angle) > 1)
        {
        	mFlingRunnable.startUsingDistance(-angle);
        }
        else
        {
            // Set selected position
            position = arr.get(0).getIndex();
            setSelectedPositionInt(position);
        	onFinishedMovement();
        }
    	
    	 /*Log.e("angle", ""+angle);
    	 Log.e("Math.abs(angle", ""+Math.abs(angle));
    	 Log.e("arr", ""+arr.get(0).getName());*/
	    /*	Log.e("arr", ""+arr.get(1).getName());
	    	Log.e("arr", ""+arr.get(2).getName());
    	 arr.get(0).setRotationX(360);
	        arr.get(1).setRotationX(45);
	        arr.get(2).setRotationX(330);
    	 */
    	
    	
    	/* Boolean angleFlag2 = Float.compare(angle, 0.0f) == 0 ? true : false;
    	 if(angleFlag2){
        	   Log.e("0.0",""+angle);
        	   	scrollFlag= false;
           }*/
    	 
    	// Log.e("scrollFlag", ""+scrollFlag);
		/*if(scrollFlag){
	    	Log.e("arr if ", ""+arr.get(0).getName()+"index()"+arr.get(0).getIndex());
	    	Log.e("arr if", ""+arr.get(1).getName()+"index()"+arr.get(1).getIndex());
	    	Log.e("arr if", ""+arr.get(2).getName()+"index()"+arr.get(2).getIndex());

	      
			arr.get(0).setRotationX(360);
	        arr.get(1).setRotationX(330);
	        arr.get(2).setRotationX(45);
		}else{
			
	    	Log.e("arr else ", ""+arr.get(0).getName()+"index()"+arr.get(0).getIndex());
	    	Log.e("arr else", ""+arr.get(1).getName()+"index()"+arr.get(1).getIndex());
	    	Log.e("arr else", ""+arr.get(2).getName()+"index()"+arr.get(2).getIndex());

	    	 
			  arr.get(0).setRotationX(360);
		      arr.get(1).setRotationX(45);
		      arr.get(2).setRotationX(330);
		}
		*/
		/* boolean angleFlag = Float.compare(angle, -0.0f) == 0 ? true : false;
    	 if(angleFlag){
      	   Log.e("0.0",""+angle);
      	  scrollFlag= false;
      	  arr.get(0).setRotationX(360);
	      arr.get(1).setRotationX(330);
	      arr.get(2).setRotationX(330);
         }*/
    	 
    	  /* Log.e("arr  ", ""+arr.get(0).getName()+"index()"+arr.get(0).getIndex());
	    	Log.e("arr ", ""+arr.get(1).getName()+"index()"+arr.get(1).getIndex());
	    	Log.e("arr ", ""+arr.get(2).getName()+"index()"+arr.get(2).getIndex());
*/
    	 int indexDowm = arr.get(0).getIndex() - 1;
    	 if(indexDowm == -1){
    		 indexDowm = getLastVisiblePosition();
    	 }
    	 
    	/* arr.get(0).setRotationX(360);
    	 if(arr.get(1).getIndex() == indexDowm){
    		 arr.get(1).setRotationX(330);
    		 arr.get(2).setRotationX(45);
    	 }else{
    		 arr.get(2).setRotationX(330);
    		 arr.get(1).setRotationX(45);
    	 }*/
	      
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }
    
	public void scrollToChild(int i){		
		try{
			CarouselItem view = (CarouselItem)getAdapter().getView(i, null, null);
			float angle = view.getCurrentAngle();
			
			if(angle == 0)
				return;
			
			if(angle > 180.0f)
				angle = 360.0f - angle;
			else
				angle = -angle;
	
	    	mFlingRunnable.startUsingDistance(angle);
		}catch(Exception e){
			e.printStackTrace();
		}
	}    
    
    /**
     * Whether or not to callback on any {@link #getOnItemSelectedListener()}
     * while the items are being flinged. If false, only the final selected item
     * will cause the callback. If true, all items between the first and the
     * final will cause callbacks.
     * 
     * @param shouldCallback Whether or not to callback on the listener while
     *            the items are being flinged.
     */
    public void setCallbackDuringFling(boolean shouldCallback) {
        mShouldCallbackDuringFling = shouldCallback;
    }
    
    /**
     * Whether or not to callback when an item that is not selected is clicked.
     * If false, the item will become selected (and re-centered). If true, the
     * {@link #getOnItemClickListener()} will get the callback.
     * 
     * @param shouldCallback Whether or not to callback on the listener when a
     *            item that is not selected is clicked.
     * @hide
     */
    public void setCallbackOnUnselectedItemClick(boolean shouldCallback) {
        mShouldCallbackOnUnselectedItemClick = shouldCallback;
    }
    
    /**
     * Sets how long the transition animation should run when a child view
     * changes position. Only relevant if animation is turned on.
     * 
     * @param animationDurationMillis The duration of the transition, in
     *        milliseconds.
     * 
     * @attr ref android.R.styleable#Gallery_animationDuration
     */
    public void setAnimationDuration(int animationDurationMillis) {
        mAnimationDuration = animationDurationMillis;
    }
            
	public void setGravity(int gravity){
		if (mGravity != gravity) {
			mGravity = gravity;
			requestLayout();
		}
	}	   


    /**
     * Helper for makeAndAddView to set the position of a view and fill out its
     * layout paramters.
     * 
     * @param child The view to position
     * @param offset Offset from the selected position
     * @param x X-coordintate indicating where this view should be placed. This
     *        will either be the left or right edge of the view, depending on
     *        the fromLeft paramter
     * @param fromLeft Are we posiitoning views based on the left edge? (i.e.,
     *        building from left to right)?
     */
    private void setUpChild(CarouselItem child, int index, float angleOffset) {
       try{
    	// Ignore any layout parameters for child, use wrap content
        addViewInLayout(child, -1 /*index*/, generateDefaultLayoutParams());

        child.setSelected(index == mSelectedPosition);
        
        int h;
        int w;
        int d;
        
        if(mInLayout)
        {
            w = child.getMeasuredWidth();
            h = child.getMeasuredHeight();
            d = getMeasuredWidth();
	        
        }
        else
        {
            w = child.getMeasuredWidth();
            h = child.getMeasuredHeight();
            d = getWidth();
        	
        }
        
        child.setCurrentAngle(angleOffset);
        
        // Measure child
        child.measure(w, h);
        
        int childLeft;
        
        // Position vertically based on gravity setting
        int childTop = calculateTop(child, true);
        
        childLeft = 0;

        child.layout(childLeft, childTop, w, h);
        
        Calculate3DPosition(child, d, angleOffset);
       }catch(Exception e){
    	   e.printStackTrace();
       }
        
    } 
    
    /**
     * Tracks a motion scroll. In reality, this is used to do just about any
     * movement to items (touch scroll, arrow-key scroll, set an item as selected).
     * 
     * @param deltaAngle Change in X from the previous event.
     */
   @SuppressLint("NewApi") public void trackMotionScroll(float deltaAngle) {
	    try{
	        if (getChildCount() == 0) {
	            return;
	        }
	                
	        for(int i = 0; i < getAdapter().getCount(); i++){        	
	        	CarouselItem child = (CarouselItem)getAdapter().getView(i, null, null);        	
	        	float angle = child.getCurrentAngle();
	        	angle += deltaAngle;
	        	
	        	while(angle > 360.0f)
	        		angle -= 360.0f;
	        	
	        	while(angle < 0.0f)
	        		angle += 360.0f;
	        	
	        	child.setCurrentAngle(angle);
	        	/*if(i==0){
	        		child.setRotationX(angle+20);
	        	} else {
	            	child.setRotationX(angle-20);
	        		
	        	}*/
	        	child.setRotationX(angle);
	            Calculate3DPosition(child, getWidth(), angle);
	            
	        }
	        
	        // Clear unused views 
	        mRecycler.clear();        
	        
	        invalidate();
	    }catch(Exception e){
	    	e.printStackTrace();
	    }
    }	
	
    private void updateSelectedItemMetadata() {
	   try{
	        View oldSelectedChild = mSelectedChild;     
	        newSelectedPosVal = mSelectedPosition;
	        View child = mSelectedChild = getChildAt(mSelectedPosition - mFirstPosition);
	        if (child == null) {
	            return;
	        }
	
	        child.setSelected(true);
	        child.setFocusable(true);
	
	        if (hasFocus()) {
	            child.requestFocus();
	        }
	
	        // We unfocus the old child down here so the above hasFocus check
	        // returns true
	        if (oldSelectedChild != null) {
	
	            // Make sure its drawable state doesn't contain 'selected'
	            oldSelectedChild.setSelected(false);
	            
	            // Make sure it is not focusable anymore, since otherwise arrow keys
	            // can make this one be focused
	            oldSelectedChild.setFocusable(false);
	        }
	      }catch(Exception e){
        	e.printStackTrace();
         }
        
    }
    
	    
}
