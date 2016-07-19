package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FullImage extends Activity {

	String className = this.getClass().getSimpleName();
	AQuery aq;
	String storedProdImage="null", storedProdId="null", storedClientId="null", storedProdUrl="null", storedProdName="null";
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_full_image);
		try{
			aq = new AQuery(this);			
			
	        Intent i = getIntent();	 	        
	        ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);			
				
	        storedProdImage    = i.getStringExtra("storedProdImage");
	        storedProdId		= i.getStringExtra("storedProdId");
	        storedClientId		= i.getStringExtra("storedClientId");
	        storedProdUrl		= i.getStringExtra("storedProdUrl");
	        storedProdName		= i.getStringExtra("storedProdName");	         
	        
	        Bitmap bmpImage = aq.getCachedImage(storedProdImage);
	        ImageView imageView = (ImageView) findViewById(R.id.full_image_view);
	        RelativeLayout.LayoutParams rlImageView = (RelativeLayout.LayoutParams)imageView.getLayoutParams();
	        rlImageView.height= (int)(0.911 * Common.sessionDeviceHeight);
	        imageView.setLayoutParams(rlImageView);
	        imageView.setImageBitmap(bmpImage);
	        imageView.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						FullImage.this.finish();
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imageview click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(FullImage.this, errorMsg);
					}
				}
			});
	        
	        ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle); 
	        imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View view) {
	            	try{
						Intent intent = new Intent(FullImage.this, MenuOptions.class);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
	            	} catch (Exception e) {
	            		e.printStackTrace();
	            		String errorMsg = getClass().getSimpleName()+" | imgFooterMiddle  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(FullImage.this, errorMsg);
					}
	            }
		    });
	        
	        ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);	
	        imgvBtnBack.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					FullImage.this.finish();
				}
	        });
	        
	        ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);	
	        imgvBtnShare.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					try{				
						Intent intent = new Intent(FullImage.this, ShareActivity.class);
						intent.putExtra("tapOnImage", false);
						intent.putExtra("image", storedProdImage);						
						intent.putExtra("productId",  storedProdId);
						intent.putExtra("clientId", storedClientId);						
						startActivityForResult(intent, 1);
						overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);					
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnShare  click  |   " +e.getMessage();
					    Common.sendCrashWithAQuery(FullImage.this,errorMsg);
					}
				}
			});
		
	    ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
		imgBtnCart.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View view) {
				try{
					if(Common.isNetworkAvailable(FullImage.this))
					{
						if(storedProdUrl.equals("null") || storedProdUrl.equals("") || storedProdUrl == null){
							Toast.makeText(getApplicationContext(), "Product is not available.", Toast.LENGTH_LONG).show();
			            	//pDialog.dismiss();
						} else {
							String[] separated = storedProdUrl.split(":");
							if(separated[0]!=null && separated[0].equals("tel")){
							Intent callIntent = new Intent(Intent.ACTION_CALL);
							callIntent.setData(Uri.parse("tel://"+separated[1]));
		                   	startActivity(callIntent);
							} else if(separated[0]!=null && separated[0].equals("telprompt")){
								Intent callIntent = new Intent(Intent.ACTION_CALL);
			                    callIntent.setData(Uri.parse("tel://"+separated[1]));
		                    	startActivity(callIntent);
							} else {
								Intent intent = new Intent(FullImage.this, PurchaseProductWithClientUrl.class);
								intent.putExtra("productName", storedProdName);
								intent.putExtra("finalWebSiteUrl", storedProdUrl);
								startActivity(intent);
								overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);		
							}
						}
					}else{
						 new Common().instructionBox(FullImage.this,R.string.title_case7,R.string.instruction_case7);
					}
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | imgBtnCart  click  |   " +e.getMessage();
					 Common.sendCrashWithAQuery(FullImage.this,errorMsg);
				}
			}
		});
		
	        
	        Button btnCloseFullImage = (Button) findViewById(R.id.btnCloseFullImage);
	        btnCloseFullImage.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						FullImage.this.finish();
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | closefullimage click   |   " +e.getMessage();
						Common.sendCrashWithAQuery(FullImage.this, errorMsg);
					}
				}
			});
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, Common.sessionClientBgColor,
					Common.sessionClientBackgroundLightColor,
					Common.sessionClientBackgroundDarkColor,
					Common.sessionClientLogo, i.getStringExtra("storedProdName"), "");	        
	        
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(FullImage.this, errorMsg);
		}
	}
}
