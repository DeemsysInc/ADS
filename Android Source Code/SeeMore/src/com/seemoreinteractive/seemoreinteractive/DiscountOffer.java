package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.List;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.util.AQUtility;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.UserMyOffers;
import com.seemoreinteractive.seemoreinteractive.Model.UserOffers;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class DiscountOffer extends Activity {
	String className = getClass().getSimpleName();
	AQuery aq;
	View prevView=null;
	public static String offerDiscountValue,offerDiscountValuType,offerName,offerId;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		try{
		setContentView(R.layout.activity_discount_offer);
		
		
		ImageView imgGoBack = (ImageView) findViewById(R.id.go_back);
		RelativeLayout.LayoutParams rlpForImgGoBack = (RelativeLayout.LayoutParams) imgGoBack.getLayoutParams();
		rlpForImgGoBack.width = (int) (0.5 * Common.sessionDeviceWidth);
		rlpForImgGoBack.height = (int) (0.066 * Common.sessionDeviceHeight);
		imgGoBack.setLayoutParams(rlpForImgGoBack);
		
		
		ImageView imgSave= (ImageView) findViewById(R.id.save_it);
		RelativeLayout.LayoutParams rlpForImgSave = (RelativeLayout.LayoutParams) imgSave.getLayoutParams();
		rlpForImgSave.width = (int) (0.5 * Common.sessionDeviceWidth);
		rlpForImgSave.height = (int) (0.066 * Common.sessionDeviceHeight);
		imgSave.setLayoutParams(rlpForImgSave);
		
		FileTransaction file = new FileTransaction();
        Offers myoffers = file.getMyOffers();
        Offers offers = file.getOffers();
        final String clientId = getIntent().getStringExtra("clientId"); 
		List<UserMyOffers> userMyOffers = myoffers.getAllMyOffersByClient(clientId);
   
	   //Log.e("userMyOffers",""+userMyOffers.size());
		
		if(userMyOffers.size() > 0){
	   
	   int c=0;
	   String[] offerFinalArray;
	   offerFinalArray = new String[userMyOffers.size()];
	   aq = new AQuery(DiscountOffer.this);
	   if(userMyOffers != null){	
			for ( final UserMyOffers usermyOffer : userMyOffers) {	
				ArrayList<String> offerResArrays = new ArrayList<String>(); 
				UserOffers userOffer = offers.getUserOffers(usermyOffer.getOfferId());		
	           	String curveImagesUrl = userOffer.getOfferImage().replaceAll(" ", "%20");	 
	           	offerResArrays.add(curveImagesUrl);
	           	offerResArrays.add(""+userOffer.getOfferClientId());
	           	offerResArrays.add(""+userOffer.getOfferId());
	           	offerResArrays.add(userOffer.getOfferName());
	           	offerResArrays.add(userOffer.getOfferDiscountType());
	           	offerResArrays.add(userOffer.getOfferDiscountValue());
	        	Log.e("usermyOffer.getOfferId()",""+userOffer.getOfferId());
	           	Log.e("userOffer.getOfferClientId()",""+userOffer.getOfferClientId());
	       		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
	   			if(bitmap1==null) {
	   				aq.cache(curveImagesUrl, 144000);
	   			}
   			
	   			offerFinalArray[c] = offerResArrays.toString();
	   			c++;
			}
			render(offerFinalArray);	
			
			    GridView gridView = (GridView) findViewById(R.id.grid_view);      
				RelativeLayout.LayoutParams rlpForGridView = (RelativeLayout.LayoutParams) gridView.getLayoutParams();
				rlpForGridView.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
				rlpForGridView.height = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
				rlpForGridView.bottomMargin = (int) (0.066 * Common.sessionDeviceHeight);
				gridView.setLayoutParams(rlpForGridView);     	
				// Instance of ImageAdapter Class	
				gridView.setOnItemClickListener(new OnItemClickListener() {
					@Override
					public void onItemClick(AdapterView<?> parent, View v,
							final int position, long id) {
						try{
								if(prevView != null){
									if(prevView != v){
										ImageView check = (ImageView)prevView.findViewById(R.id.check);							
										RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
										rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
										rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
										check.setLayoutParams(rlpForImgCheck);	
										check.setVisibility(View.INVISIBLE);  
										
									}			
								}
								     prevView = v;
									ImageView check = (ImageView)v.findViewById(R.id.check);							
									RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
									rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
									rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
									check.setLayoutParams(rlpForImgCheck);							
									if(check.getVisibility()==View.INVISIBLE){
						   				check.setVisibility(View.VISIBLE);    			   							    				  				
									} else {
										check.setVisibility(View.INVISIBLE);   			   							
									}
									 ImageView img =(ImageView) v.findViewById(R.id.image);
									 offerDiscountValue = img.getTag(R.string.imagePrice).toString();
									 offerDiscountValuType = img.getTag(R.string.imageDesc).toString();
									 offerName = img.getTag(R.string.imageName).toString();
									 offerId = img.getTag(R.string.offerId).toString();

									 
					}catch(Exception e){
						e.printStackTrace();
					}
					}
				});		
				
	   }
		}else{
			//finish();
			TextView txtvMsg = (TextView) findViewById(R.id.txtvMsg);
			txtvMsg.setVisibility(View.VISIBLE);
			imgSave= (ImageView) findViewById(R.id.save_it);
			imgSave.setEnabled(false);
		}
	   
		
		imgGoBack.setOnClickListener(new OnClickListener() {				
			@Override
			public void onClick(View arg0) {
				finish();
			}
		});
		
		
		imgSave.setOnClickListener(new OnClickListener() {				
			@Override
			public void onClick(View arg0) {
				try{
					
					Intent intent = new Intent(DiscountOffer.this,OrderConfirmation.class);
					intent.putExtra("offerDiscountValue", offerDiscountValue);
					intent.putExtra("offerDiscountValuType", offerDiscountValuType);
					intent.putExtra("offerName", offerName);
					intent.putExtra("offerId", offerId);					
					intent.putExtra("clientId", clientId);	
					setResult(4, intent);											
					finish();
				    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					
				}catch(Exception e){
					e.printStackTrace();
				}
			}
		});
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	int gridItemLayout;
	private void render(final String[] offersStrArray){	  
		try{				
		    AQUtility.debug("render setup");
			gridItemLayout = R.layout.griditem;	
			ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, offersStrArray){				
				@Override
				public View getView(int position, View convertView, ViewGroup parent) {		
					try{
						if(convertView == null){
							convertView = aq.inflate(convertView, gridItemLayout, parent);
						}			
						
						if(offersStrArray[position]!=null){
							String s ="[";
							String q ="]";
							String w ="";
							String strReplaceSymbol = String.valueOf(offersStrArray[position]).replace(s, w).replace(q, w);
						
							String[] expClosetArray = strReplaceSymbol.split(",");
							
							String expImageUrl = expClosetArray[0].trim();		
							String expClientId = expClosetArray[1].trim();		
							String expOfferId = expClosetArray[2].trim();		
							String expOfferName = expClosetArray[3].trim();
							String expOfferType = expClosetArray[4].trim();	
							String expOfferValue = expClosetArray[5].trim();	
													
							
							AQuery aq2 = new AQuery(convertView);				
							Bitmap placeholder = aq2.getCachedImage(expImageUrl);
							if(placeholder==null){
								aq2.cache(expImageUrl, 1440000);					
							}
						
						ImageView img =(ImageView) convertView.findViewById(R.id.image);
						aq2.id(R.id.image).progress(R.id.progressBarGrid).image(expImageUrl, true, true, 0, 0, placeholder, com.androidquery.util.Constants.FADE_IN_NETWORK, 0);
						img.setTag(expImageUrl);	
						img.setTag(R.string.imagePrice ,expOfferValue);
						img.setTag(R.string.imageDesc ,expOfferType);
						img.setTag(R.string.imageName,expOfferName);
						img.setTag(R.string.offerId,expOfferId);
						RelativeLayout.LayoutParams rlpForImg = (RelativeLayout.LayoutParams) img.getLayoutParams();
						rlpForImg.width = (int) (0.3 * Common.sessionDeviceWidth);
						rlpForImg.height = (int) (0.123 * Common.sessionDeviceHeight);
						img.setLayoutParams(rlpForImg);
						
						ImageView check = (ImageView)convertView.findViewById(R.id.check);
						RelativeLayout.LayoutParams rlpForImgCheck = (RelativeLayout.LayoutParams) check.getLayoutParams();
						rlpForImgCheck.width = (int)(0.05 * Common.sessionDeviceWidth);
						rlpForImgCheck.height = (int) (0.031 * Common.sessionDeviceHeight);
						check.setLayoutParams(rlpForImgCheck);
						/*if(!imagesUrlList.contains(img.getTag().toString())){
							check.setVisibility(View.INVISIBLE);
							arrListForCheckedProdImages.remove(img.getTag().toString());					
						}else{
							check.setVisibility(View.VISIBLE);
							if(!arrListForCheckedProdImages.contains(img.getTag().toString()))
								arrListForCheckedProdImages.add(img.getTag().toString());
						}*/
						}
					} catch (Exception e){
						e.printStackTrace();
					}
					return convertView;					
				}			
			};			
			aq.id(R.id.grid_view).adapter(aa);
		} catch (Exception e){
			e.printStackTrace();
			String errorMsg = className+" | render      |   " +e.getMessage();
			Common.sendCrashWithAQuery(DiscountOffer.this,errorMsg);
		}
	}
	

}
