package com.seemoreinteractive.seemoreinteractive;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.os.Bundle;
import android.text.Html;
import android.text.Spanned;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class NearbystoreActivity extends Activity {
	
	FileTransaction file;
	AQuery aq; 
	ArrayList<String> arrForSearchTitle,arrForSearchAddress,arrForSearchAddress2,arrForSearchCity,arrForSearchState,arrForSearchZip,arrForDistance;
	ListView lvForSearchNearByPlaces;
	Button btnSearch,btnGetList;
	RelativeLayout mainRelativeLayout,relativeLayout1,relativeLayoutZip,relativeLayoutSearch;
	EditText etxtZip;
	TextView resultText,etxtLabel,txtNoLabel,textlbl;
	String className = this.getClass().getSimpleName();
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_nearbystore);
		try{

		ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
		imgBtnCart.setImageAlpha(0);
		
		 ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
	     imgvBtnCloset.setImageAlpha(0);
	        
	     ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);
	     imgvBtnShare.setImageAlpha(0);
		
		mainRelativeLayout= (RelativeLayout)findViewById(R.id.mainRelativeLayout);
		RelativeLayout.LayoutParams rlpMainRelativeLayout= (RelativeLayout.LayoutParams) mainRelativeLayout.getLayoutParams();
		rlpMainRelativeLayout.height = (int) (0.123 * Common.sessionDeviceHeight);		
		mainRelativeLayout.setLayoutParams(rlpMainRelativeLayout);
		
		new Common().clickingOnBackButtonWithAnimation(NearbystoreActivity.this, ProductList.class,"1");
		
		lvForSearchNearByPlaces = (ListView) findViewById(R.id.lvForStores);
		RelativeLayout.LayoutParams rlpForLv = (RelativeLayout.LayoutParams) lvForSearchNearByPlaces.getLayoutParams();
		rlpForLv.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
		rlpForLv.height = (int) (0.677 * Common.sessionDeviceHeight);
		rlpForLv.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
		lvForSearchNearByPlaces.setLayoutParams(rlpForLv);
		//btnSearch = (Button) findViewById(R.id.btnSearch);
		
		//btnSearch.setPadding((int) (0.017 * Common.sessionDeviceWidth), 0, 0, 0);
		
		relativeLayoutZip = (RelativeLayout)findViewById(R.id.relativeLayoutZip);
		relativeLayoutSearch  = (RelativeLayout)findViewById(R.id.relativeLayoutSearch);
		
		etxtLabel = (TextView) findViewById(R.id.etxtLabel);
		RelativeLayout.LayoutParams rlpForLabel = (RelativeLayout.LayoutParams) etxtLabel.getLayoutParams();
		rlpForLabel.width = (int) (0.75 * Common.sessionDeviceWidth);
		rlpForLabel.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
		rlpForLabel.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
		etxtLabel.setLayoutParams(rlpForLabel);
		etxtLabel.setTextSize((float)(0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		txtNoLabel = (TextView) findViewById(R.id.txtNoLabel);
		RelativeLayout.LayoutParams rlpForNoLabel = (RelativeLayout.LayoutParams) txtNoLabel.getLayoutParams();
		rlpForNoLabel.width = (int) (0.75 * Common.sessionDeviceWidth);
		rlpForNoLabel.leftMargin = (int) (0.05 * Common.sessionDeviceWidth);
		rlpForNoLabel.topMargin = (int) (0.021 * Common.sessionDeviceHeight);
		txtNoLabel.setLayoutParams(rlpForNoLabel);
		txtNoLabel.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		textlbl = (TextView) findViewById(R.id.textlbl);
		RelativeLayout.LayoutParams rlpFortextlbl = (RelativeLayout.LayoutParams) textlbl.getLayoutParams();
		rlpFortextlbl.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
		textlbl.setLayoutParams(rlpFortextlbl);
		textlbl.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		btnSearch = (Button) findViewById(R.id.btnSearch);
		RelativeLayout.LayoutParams rlpForbtnSearch = (RelativeLayout.LayoutParams) btnSearch.getLayoutParams();
		rlpForbtnSearch.width = (int) (0.54 * Common.sessionDeviceWidth);
		rlpForbtnSearch.height = (int) (0.154 * Common.sessionDeviceHeight);
		//rlpForbtnSearch.leftMargin = (int) (0.218 * Common.sessionDeviceWidth);
		rlpForbtnSearch.leftMargin = (int) (0.25 * Common.sessionDeviceWidth);
		rlpForbtnSearch.rightMargin = (int) (0.017 * Common.sessionDeviceWidth);
		rlpForbtnSearch.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
		btnSearch.setLayoutParams(rlpForbtnSearch);
		btnSearch.setTextSize((float)(0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		btnGetList = (Button) findViewById(R.id.btnGetList);
		RelativeLayout.LayoutParams rlpForbtnGetList = (RelativeLayout.LayoutParams) btnGetList.getLayoutParams();
		rlpForbtnGetList.width = (int) (0.334 * Common.sessionDeviceWidth);
		rlpForbtnGetList.height = (int) (0.062 * Common.sessionDeviceHeight);
		rlpForbtnGetList.rightMargin = (int) (0.085 * Common.sessionDeviceWidth);
		btnGetList.setLayoutParams(rlpForbtnGetList);		
		btnGetList.setTextSize((float)(0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		
		etxtZip =(EditText) findViewById(R.id.etxtZip);
		RelativeLayout.LayoutParams rlpForetxtZip = (RelativeLayout.LayoutParams) etxtZip.getLayoutParams();
		rlpForetxtZip.width = (int) (0.443 * Common.sessionDeviceWidth);
		rlpForetxtZip.height = (int) (0.062 * Common.sessionDeviceHeight);
		rlpForetxtZip.leftMargin = (int) (0.084* Common.sessionDeviceWidth);
		rlpForetxtZip.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
		etxtZip.setLayoutParams(rlpForetxtZip);
		etxtZip.setTextSize((float)(0.0417 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
		resultText = (TextView) findViewById(R.id.resultText);
		
		View hrView = findViewById(R.id.hrView);
		RelativeLayout.LayoutParams rlpForHrView = (RelativeLayout.LayoutParams) hrView.getLayoutParams();		
		rlpForHrView.height = (int) (0.0021 * Common.sessionDeviceHeight);		
		rlpForHrView.topMargin = (int) (0.011 * Common.sessionDeviceHeight);
		hrView.setLayoutParams(rlpForHrView);
		
		resultText = (TextView) findViewById(R.id.resultText);
		
		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, Common.sessionClientBgColor,
				Common.sessionClientBackgroundLightColor,
				Common.sessionClientBackgroundDarkColor,
				Common.sessionClientLogo, "Stores", "");
		
		new Common().getLatLng(NearbystoreActivity.this);
		
	    Map<String, String> params = new HashMap<String, String>();
		params.put("clientId", Common.sessionClientId);
		params.put("lat", ""+Common.lat);
		params.put("lng", ""+Common.lng);
		params.put("zip", "0");

		JSONObject jsonObject = new JSONObject(params);		
		Map<String, String> json = new HashMap<String, String>();
		json.put("json", jsonObject.toString());	
		
	    getListView(json);
		
		btnSearch.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
				relativeLayoutSearch.setVisibility(View.INVISIBLE);				
				relativeLayoutZip.setVisibility(View.VISIBLE);

				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | search click   |   " +e.getMessage();
					Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
				}
			}
		});
		btnGetList.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {
				try{
				String zip =  etxtZip.getText().toString();
				Log.e("zip",zip);
				 final Map<String, String> params = new HashMap<String, String>();
					params.put("clientId", Common.sessionClientId);
					params.put("lat", "0");
					params.put("lng", "0");
					params.put("zip", zip);
					
					JSONObject jsonObject = new JSONObject(params);		
					Map<String, String> json = new HashMap<String, String>();
					json.put("json", jsonObject.toString());	
					
					 getListView(params);

				} catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | getlist onclick   |   " +e.getMessage();
					Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
				}
			}
		});

		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
		}
	    	
	}
	
	public void getListView(Map<String, String> params){
		try{
			lvForSearchNearByPlaces.setAdapter(null);		
			aq = new AQuery(NearbystoreActivity.this);	
		   
		    String storesUrl = Constants.Live_Url+"mobileapps/ios/public/stores/clientstores/";               
	        //aq.ajax(storesUrl,params, JSONArray.class, this, "jsonCallback");		    
		    aq.progress(R.id.progress).ajax(storesUrl, params, JSONArray.class, new AjaxCallback<JSONArray>() {
		        @Override
		        public void callback(String url, JSONArray jArray, AjaxStatus status) {
		            try{
		            	//Log.e("JSONArray",""+jArray);
		            	if(jArray != null){
				        	if(jArray.length() > 0){
				        		arrForSearchTitle = new ArrayList<String>();
				        		arrForSearchAddress = new ArrayList<String>();
				        		arrForSearchAddress2= new ArrayList<String>();
				        		arrForSearchCity = new ArrayList<String>();
				        		arrForSearchState = new ArrayList<String>();
				        		arrForSearchZip = new ArrayList<String>();
				        		arrForDistance = new ArrayList<String>();
								  for(int i=0;i<jArray.length();i++)
								  {
									  JSONObject json_obj = jArray.getJSONObject(i);				
								      String storeCode   = json_obj.getString("address_1");
								      String storeName  = json_obj.getString("store_name");
								      Log.e("storeCode",storeCode);
								      Log.e("storeName",storeName);
								      
								  	arrForSearchTitle.add(json_obj.getString("store_name"));
				        			arrForSearchAddress.add(json_obj.getString("address_1"));
				        			arrForSearchAddress2.add(json_obj.getString("address_2"));
				        			arrForSearchCity.add(json_obj.getString("city"));
				        			arrForSearchState.add(json_obj.getString("state"));
				        			arrForSearchZip.add(json_obj.getString("zip"));
				        			arrForDistance.add(json_obj.getString("distance"));
								} 
							
							if(arrForSearchTitle.size()>0){
								lvForSearchNearByPlaces.setAdapter(renderForNearByStoreLv(arrForSearchTitle,arrForSearchAddress,arrForDistance,arrForSearchAddress2,arrForSearchCity,arrForSearchState,arrForSearchZip));
								lvForSearchNearByPlaces.setOnItemClickListener(new OnItemClickListener() {
									@Override
									public void onItemClick(
											AdapterView<?> arg0, View arg1,
											int arg2, long arg3) {	
								
								  }
								});
							}
				           
				        }
				       }

		    		} catch(Exception e){
		    			e.printStackTrace();
		    			String errorMsg = className+" | getlistview callback   |   " +e.getMessage();
		    			Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
		    		}
		        }
		    });
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getlistview method   |   " +e.getMessage();
			Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
		}
		
	}
			
	public void jsonCallback(String url, JSONArray jArray, AjaxStatus status){        
        if(jArray != null){               
        	try {
        		arrForSearchTitle = new ArrayList<String>();
        		arrForSearchAddress = new ArrayList<String>();
        		arrForDistance = new ArrayList<String>();
        		if(jArray.length() > 0){
        			arrForSearchTitle = new ArrayList<String>();
	        		arrForSearchAddress = new ArrayList<String>();
	        		arrForSearchAddress2= new ArrayList<String>();
	        		arrForSearchCity = new ArrayList<String>();
	        		arrForSearchState = new ArrayList<String>();
	        		arrForSearchZip = new ArrayList<String>();
					  for(int i=0;i<jArray.length();i++)
					  {
						  JSONObject json_obj = jArray.getJSONObject(i);				
					      String storeCode   = json_obj.getString("address_1");
					      String storeName  = json_obj.getString("store_name");
					      Log.e("storeCode",storeCode);
					      Log.e("storeName",storeName);
					      
					  	arrForSearchTitle.add(json_obj.getString("store_name"));
	        			arrForSearchAddress.add(json_obj.getString("address_1"));
	        			arrForSearchAddress2.add(json_obj.getString("address_2"));
	        			arrForSearchCity.add(json_obj.getString("city"));
	        			arrForSearchState.add(json_obj.getString("state"));
	        			arrForSearchZip.add(json_obj.getString("zip"));
	        			arrForDistance.add(json_obj.getString("distance"));
							} 
						
						if(arrForSearchTitle.size()>0){
							lvForSearchNearByPlaces.setAdapter(renderForNearByStoreLv(arrForSearchTitle,arrForSearchAddress,arrForDistance,arrForSearchAddress2,arrForSearchCity,arrForSearchState,arrForSearchZip));
							lvForSearchNearByPlaces.setOnItemClickListener(new OnItemClickListener() {
								@Override
								public void onItemClick(
										AdapterView<?> arg0, View arg1,
										int arg2, long arg3) {			
									String searchTitle = ((TextView) arg1.findViewById(R.id.txtSearchTitle)).getText().toString();
									String searchAddress = ((TextView) arg1.findViewById(R.id.txtvSearchAddress)).getText().toString();
									String searchReference = ((TextView) arg1.findViewById(R.id.txtSearchTitle)).getTag(R.string.reference_tag).toString();
									String searchDistance = ((TextView) arg1.findViewById(R.id.txtvDistanceVal)).getText().toString();
									Log.e("searchTitle",searchTitle);
									Log.e("searchAddress",searchAddress);
									Log.e("searchReference",searchReference);
									Log.e("searchDistance",searchDistance);
									/*Intent intent = new Intent(N.this, FindAStoreItemDetails.class);	
									intent.putExtra("storeTitle", searchTitle);
									intent.putExtra("storeAddress", searchAddress);
									intent.putExtra("storeReference", searchReference);
									intent.putExtra("storeDistance", searchDistance);
									intent.putExtra("lat", ""+lat);
									intent.putExtra("lng", ""+lng);
									startActivity(intent);*/
									//finish();
									//overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
									 
							  }
							});
						}
        		}

    		} catch(Exception e){
    			e.printStackTrace();
    			String errorMsg = className+" | jsoncallback   |   " +e.getMessage();
    			Common.sendCrashWithAQuery(NearbystoreActivity.this, errorMsg);
    		}
			
        }
}
	
    int lvItemLayout = 0;
	private ArrayAdapter<String> renderForNearByStoreLv(final ArrayList<String> arrForSearchTitle2, final ArrayList<String> arrForSearchAddress1, final ArrayList<String> arrForDistance2, final ArrayList<String> arrForSearchAddress2, final ArrayList<String> arrForSearchCity2, final ArrayList<String> arrForSearchState2, final ArrayList<String> arrForSearchZip2){	    	
	    AQUtility.debug("render setup");
	    lvItemLayout = R.layout.nearby_a_store_list_items;
		ArrayAdapter<String> aa = new ArrayAdapter<String>(this, lvItemLayout,arrForSearchTitle2 ){				
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, lvItemLayout, parent);
					}	
					Log.i("position", ""+position);							
					final AQuery aq2 = new AQuery(convertView);
					
					
					if(arrForSearchTitle2.get(position)!=null){
						
						String expStoreTitle = arrForSearchTitle2.get(position);		
						String expStoreAddress1 = arrForSearchAddress1.get(position);		
						String expStoreAddress2 = arrForSearchAddress2.get(position);
						String expStoreCity = arrForSearchCity2.get(position);
						String expStoreState = arrForSearchState2.get(position);
						String expStoreZip = arrForSearchZip2.get(position);
					
						String expStoreDis = arrForDistance2.get(position);		
						if(!expStoreDis.equals("")){
							expStoreDis = new DecimalFormat("##.##").format(Double.parseDouble(arrForDistance2.get(position)));		
						}
					
						TextView txtSearchTitle = (TextView)convertView.findViewById(R.id.txtSearchTitle);
						txtSearchTitle.setTextSize((float)(0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						aq2.id(R.id.txtSearchTitle).text(expStoreTitle);	
						
						
						if(!expStoreAddress2.equals("")){
							expStoreAddress2 = expStoreAddress2+"<br>";
						}
						Spanned address = Html.fromHtml(expStoreAddress1+"<br>"+expStoreAddress2+expStoreCity+", "+expStoreState+" "+expStoreZip);
						aq2.id(R.id.txtvSearchAddress).text(address);
						aq2.id(R.id.txtvDistanceVal).text(expStoreDis+"  miles");
						
					}								
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForGoolgeMapFindAStoreLv   |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(NearbystoreActivity.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}
	
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(NearbystoreActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(NearbystoreActivity.this,errorMsg);
			}			
		}
	 
		@Override
		protected void onResume() {
			try{
				super.onResume();
				if(Common.isAppBackgrnd){
					new Common().storeChangeLogResultFromServer(NearbystoreActivity.this);			
					Common.isAppBackgrnd = false;
				}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(NearbystoreActivity.this,errorMsg);
			}
		}
}
