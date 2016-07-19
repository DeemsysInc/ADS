package com.seemoreinteractive.seemoreinteractive.wheel;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Matrix;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.helper.Common;


public class CarouselItem extends FrameLayout 
	implements Comparable<CarouselItem> {
	
	private ImageView mImage;
	private TextView mText;
	
	private int index;
	private float currentAngle;
	private float itemX;
	private float itemY;
	private float itemZ;
	private boolean drawn;	

	// It's needed to find screen coordinates
	private Matrix mCIMatrix;
	
	public CarouselItem(Context context) {
		
		super(context);
		
		FrameLayout.LayoutParams params = 
				new FrameLayout.LayoutParams(
						LayoutParams.WRAP_CONTENT, 
						LayoutParams.WRAP_CONTENT);
		
		this.setLayoutParams(params);
		
	  	LayoutInflater inflater = LayoutInflater.from(context);
		View itemTemplate = inflater.inflate(R.layout.wheel_item, this, true);
	  	
		mImage = (ImageView)itemTemplate.findViewById(R.id.item_image);
		RelativeLayout.LayoutParams rlpmImage = (RelativeLayout.LayoutParams) mImage.getLayoutParams();
		rlpmImage.width  = (int) (0.6667  * Common.sessionDeviceWidth);
		rlpmImage.height = (int) (0.25615 * Common.sessionDeviceHeight);			
		mImage.setLayoutParams(rlpmImage);
		
		mText = (TextView)itemTemplate.findViewById(R.id.item_text);
				
	}	
	
	public String getName(){
		return mText.getText().toString();
	}	
	
	public void setIndex(int index) {
		this.index = index;
	}

	public int getIndex() {
		return index;
	}
	

	public void setCurrentAngle(float currentAngle) {
		
		if(index == 0 && currentAngle > 5){
			Log.d("", "");
		}
		
		this.currentAngle = currentAngle;
	}

	public float getCurrentAngle() {
		return currentAngle;
	}

	public int compareTo(CarouselItem another) {
		return (int)(another.itemZ - this.itemZ);
	}

	public void setItemX(float x) {
		this.itemX = x;
	}

	public float getItemX() {
		return itemX;
	}

	public void setItemY(float y) {
		this.itemY = y;
	}

	public float getItemY() {
		return itemY;
	}

	public void setItemZ(float z) {
		this.itemZ = z;
	}

	public float getItemZ() {
		return itemZ;
	}

	public void setDrawn(boolean drawn) {
		this.drawn = drawn;
	}

	public boolean isDrawn() {
		return drawn;
	}
	
	public void setImageBitmap(Bitmap bitmap){
		mImage.setImageBitmap(bitmap);
		
	}
	
	public void setText(String txt){
		mText.setText(txt);
	}
	
	Matrix getCIMatrix() {
		return mCIMatrix;
	}

	void setCIMatrix(Matrix mMatrix) {
		this.mCIMatrix = mMatrix;
	}	
	
}
