package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) public class ShopparList extends Activity{
	AQuery aq;
	ArrayList<String> closetResArrays;
	String[] closetFinalArray;
	FancyCoverFlow fancyCoverFlow;
	int width,height;
	TextView label;
	String className = this.getClass().getSimpleName();
	
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
	super.onCreate(savedInstanceState);
	setContentView(R.layout.activity_shoppar_list);
	try{
		 
		fancyCoverFlow = (FancyCoverFlow) findViewById(R.id.fancyCoverFlow1);	
		RelativeLayout.LayoutParams rlForFancyCoverFlow = (RelativeLayout.LayoutParams) fancyCoverFlow.getLayoutParams();		
		rlForFancyCoverFlow.topMargin = (int) (0.041 * Common.sessionDeviceHeight);			 
		fancyCoverFlow.setLayoutParams(rlForFancyCoverFlow);
		
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					ShopparList.this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "ShopparVision", "");	
		
		 new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_2, R.id.imgvBtnCart);
			 
		 ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
		 imgvBtnCloset.setImageAlpha(0);
		     
		 ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
		 imgvBtnShare.setImageAlpha(0);
		 
		 ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{						
						finish();
						overridePendingTransition(R.xml.slide_in_right, R.xml.slide_out_right);
					} catch (Exception e) {
						e.printStackTrace();		
						String errorMsg = className+" | imgvBtnBack click   |   " +e.getMessage();
					    Common.sendCrashWithAQuery(ShopparList.this,errorMsg);
					}
				}
			});
			
			ImageView imgBtnCamera = (ImageView) findViewById(R.id.imgvBtnCart);        
			imgBtnCamera.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
							finish();
							overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
						} catch (Exception e) {			
							e.printStackTrace();
							String errorMsg = className+" | imgBtnCamera click   |   " +e.getMessage();
						    Common.sendCrashWithAQuery(ShopparList.this,errorMsg);
						}
					}
				});
			
		  label = (TextView)findViewById(R.id.label);
		 
		  Intent getValues = getIntent();
		  Map<String, String> params = new HashMap<String, String>();
		  params.put("colorCode", getValues.getStringExtra("colorCode"));
		  params.put("prodType", getValues.getStringExtra("prodType"));
		
		  aq = new AQuery(ShopparList.this);
		
		  JSONObject jsonObject = new JSONObject(params);		
		  Map<String, String> json = new HashMap<String, String>();
		  json.put("json", jsonObject.toString());	
		
		  aq.ajax(Constants.Live_Url+"mobileapps/ios/public/shoppar/match",json, JSONArray.class, new AjaxCallback<JSONArray>() {
	        @Override
	        public void callback(String url, JSONArray jArray, AjaxStatus status) {
	            try{
	            	Log.e("url",""+url);
	            	Log.e("JSONArray",""+jArray);
	            	int c=0;	
	            	if(jArray != null){
	            	if(jArray.length() >0){
	            		closetFinalArray = new String[jArray.length()];
		            	for(int i=0;i<jArray.length();i++)
						  {
							  JSONObject json_obj = jArray.getJSONObject(i);				
						     
						        closetResArrays = new ArrayList<String>();		    	    			   
		                		String curveImagesUrl = json_obj.getString("image").toString().replaceAll(" ", "%20");	
		                		closetResArrays.add(curveImagesUrl);
		                		closetResArrays.add(json_obj.getString("title").toString());	                		
		                		Bitmap bitmap1 = aq.getCachedImage(curveImagesUrl);
		                		if(bitmap1==null) {
				    				aq.cache(curveImagesUrl, 144000);
				    			}	                	
		                    	closetFinalArray[c] = closetResArrays.toString();
				    			c++;
						  }
		            	
		            	fancyCoverFlow.setAdapter(renderForCoverFlow(closetFinalArray));
				        fancyCoverFlow.setSpacing(-(int)(0.2 *  Common.sessionDeviceWidth));
				        fancyCoverFlow.setMaxRotation(120);
				        fancyCoverFlow.setOnItemClickListener(new OnItemClickListener() {
							@Override
							public void onItemClick(AdapterView<?> arg0, View arg1,
									int arg2, long arg3) {
								
							}	
			    		});	 
						}else{
							label.setText("No Products Available");
						}
	            	}else{						
						label.setText("No Products Available");
					}
    		  } catch (Exception e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
					String errorMsg = className+" | ajax call   |   " +e.getMessage();
				    Common.sendCrashWithAQuery(ShopparList.this,errorMsg);
				}
    	  }
        });
			
		
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | oncreate   |   " +e.getMessage();
	    Common.sendCrashWithAQuery(ShopparList.this,errorMsg);
	}
}
	
	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
    int gridItemLayout = 0;    
    ArrayList<String> closetEachArray;
	private ArrayAdapter<String> renderForCoverFlow(final String[] closetFinalArray2){	    	
	    AQUtility.debug("render setup");
	    gridItemLayout = R.layout.coverflowitem_shoppar;	
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, gridItemLayout, closetFinalArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						//convertView = aq.inflate(convertView, gridItemLayout, parent);
						convertView = getLayoutInflater().inflate(gridItemLayout, parent, false);
					}	
					AQuery aq2 = new AQuery(convertView);						
					if(closetFinalArray2[position]!=null){
						String s ="[";
						String q ="]";
						String w ="";
						String strReplaceSymbol = String.valueOf(closetFinalArray2[position]).replace(s, w).replace(q, w);					
						String[] expClosetArray = strReplaceSymbol.split(",");
						
						String expImageUrl = expClosetArray[0].trim();	
						String expTitle = expClosetArray[1].trim();
						
						RelativeLayout coverflowLlayout1 = (RelativeLayout) convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams llpForLl = (LinearLayout.LayoutParams) coverflowLlayout1.getLayoutParams();
						llpForLl.width = (int) (0.667 * Common.sessionDeviceWidth);
						llpForLl.height = (int) (0.4611 * Common.sessionDeviceHeight);						
						coverflowLlayout1.setLayoutParams(llpForLl);
						
						Bitmap placeholder = aq2.getCachedImage(expImageUrl);
						if(placeholder==null){
							aq2.cache(expImageUrl, 1440000);			
						}
						TextView txtProdName =(TextView) convertView.findViewById(R.id.txtvProdName);
						txtProdName.setText(expTitle);
						aq2.id(R.id.coverFlowImage).image(expImageUrl, true, true, 0, 0, placeholder, 0, 0);
						
				
					}						
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | render coverflow   |   " +e.getMessage();
				    Common.sendCrashWithAQuery(ShopparList.this,errorMsg);
					
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}	
}