package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ShippingListInfo extends Activity {
	String className = this.getClass().getSimpleName();
	AQuery aq;
	String strJsonArray = "";
	String userShipId,editOrderId="null";	
	JSONArray jsonArrForShippingInfo;
	String[] strMarkerArray = null;
	ArrayList<String> arrGetShippingValues;
	ListView lvSelectWishLists; 
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN) @Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.select_wish_list);
		try{
			aq = new AQuery(this);
			/*new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Shipping Address List", "");
			new Common().headerAndFooterModules(ShippingListInfo.this);*/
			   lvSelectWishLists = (ListView) findViewById(R.id.selectWishListItems);
			   RelativeLayout.LayoutParams rlpLists = (RelativeLayout.LayoutParams) lvSelectWishLists.getLayoutParams();
			   rlpLists.width = (int) (0.897 * Common.sessionDeviceWidth);
			   rlpLists.height = (int) (0.732 * Common.sessionDeviceHeight);
			   rlpLists.topMargin = (int) (0.0139 * Common.sessionDeviceHeight);
			   rlpLists.leftMargin = (int) (0.058 * Common.sessionDeviceWidth);
			   lvSelectWishLists.setLayoutParams(rlpLists);
			   
			   TextView txtAlertBack = (TextView) findViewById(R.id.txtSelectWishListBack);
			   RelativeLayout.LayoutParams rlpAlertBack = (RelativeLayout.LayoutParams) txtAlertBack.getLayoutParams();
			   rlpAlertBack.leftMargin = (int) (0.11 * Common.sessionDeviceWidth);
			   rlpAlertBack.topMargin = (int) (0.112 * Common.sessionDeviceHeight);
				txtAlertBack.setLayoutParams(rlpAlertBack);
				txtAlertBack.setTextSize((float)((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
				txtAlertBack.setOnClickListener(new OnClickListener() {								
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					finish();
					//overridePendingTransition(R.anim.slide_in_from_top, R.anim.slide_out_to_bottom);
				}
			});
			TextView txtAlertAdd = (TextView) findViewById(R.id.txtSelectWishListAdd);
			RelativeLayout.LayoutParams rlpAlertAdd = (RelativeLayout.LayoutParams) txtAlertAdd.getLayoutParams();
			rlpAlertAdd.rightMargin = (int) (0.105 * Common.sessionDeviceWidth);
			rlpAlertAdd.width = (int) (0.0667 * Common.sessionDeviceWidth);
			rlpAlertAdd.height = (int) (0.0413 * Common.sessionDeviceHeight);
			txtAlertAdd.setLayoutParams(rlpAlertAdd);
			txtAlertAdd.setTextSize((float)((0.041 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			txtAlertAdd.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					if(Common.isNetworkAvailable(ShippingListInfo.this))
					{
						Intent intent = new Intent(getApplicationContext(), ShippingAddressForm.class);
						intent.putExtra("userShipId", userShipId);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
					}
				}
			});
			if(getIntent().getExtras()!=null){
				strJsonArray = getIntent().getStringExtra("jsonArray");
				editOrderId = getIntent().getStringExtra("editOrderId");
				//userShipId = getIntent().getIntExtra("userShipId", 0);
				Log.e("strJsonArray", ""+strJsonArray);
				Log.e("editOrderId", ""+editOrderId);
				createShippingList(strJsonArray);
				
			}
			/*lvSelectWishLists.setOnItemClickListener(new OnItemClickListener() {

				@Override
				public void onItemClick(AdapterView<?> parent, View view,
						int position, long id) {
					// TODO Auto-generated method stub
					
				}
			});*/
			  
		} catch(Exception e){
			e.printStackTrace();
	    	String errorMsg = className+" | onCreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(this, errorMsg);
		}
	}
	
	
	public void createShippingList(String strJsonArray){
		
		try{
			lvSelectWishLists.setAdapter(null);
		jsonArrForShippingInfo = new JSONArray(strJsonArray);
		strMarkerArray = new String[jsonArrForShippingInfo.length()];
		for(int i=0; i<jsonArrForShippingInfo.length(); i++){
			JSONObject jsonObjForShippingInfo = jsonArrForShippingInfo.getJSONObject(i);
			Log.e("jsonObjForShippingInfo "+i, ""+jsonObjForShippingInfo);	
			 arrGetShippingValues = new ArrayList<String>();
			 
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_addr_id").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_addr1").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_addr2").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_city").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_state").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_zip").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_country").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_ship_status").toString());
			 arrGetShippingValues.add(jsonObjForShippingInfo.get("user_id").toString());
			 strMarkerArray[i] = arrGetShippingValues.toString();
		}
//		  ((BaseAdapter) lvSelectWishLists.getAdapter()).notifyDataSetChanged();
		   lvSelectWishLists.setAdapter(renderForShippingAddressListView(strMarkerArray));
		   
		  // lvSelectWishLists.invalidateViews();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" |  createShippingList     |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShippingListInfo.this, errorMsg);
		}
	}
	public void updateShippingList(){		
		try{
			lvSelectWishLists.setAdapter(null);
			String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/usershipping";
			JSONObject jsonObj = new JSONObject();
			jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
			Map<String, Object> params = new HashMap<String, Object>();
		    params.put("json", jsonObj);			
		    params.put("userid", Common.sessionIdForUserLoggedIn); 
			Log.e("jsonObj", ""+jsonObj);
			Log.e("params", ""+params);					    
			aq.ajax(productUrl, params, JSONArray.class, new AjaxCallback<JSONArray>(){			
				@Override
				public void callback(String url, JSONArray json, AjaxStatus status) {
					try{
						//Log.e("json", ""+json);
						if(json!=null){																
							createShippingList(json.toString());
						}else{
							lvSelectWishLists.setAdapter(null);
						}
					} catch(Exception e){
						e.printStackTrace();
			    		String errorMsg = className+" | updateShippingList callback  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShippingListInfo.this, errorMsg);
					}
				}
			});
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | updateShippingList   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShippingListInfo.this, errorMsg);
		}
	}

	
	int listviewLayout = 0;    
	ArrayAdapter<String> aa;
	ArrayList<String> arrShippingList; 
	private ArrayAdapter<String> renderForShippingAddressListView(final String[] strMarkerArray2){	    	
	    AQUtility.debug("render setup");
	    listviewLayout = R.layout.listview_for_shipping_address;	
		//Log.e("stored_values", ""+strMarkerArray2);
		aa = new ArrayAdapter<String>(this, listviewLayout, strMarkerArray2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					
					if(convertView == null){
						convertView = aq.inflate(convertView, listviewLayout, parent);
					}					
					if(strMarkerArray2[position]!=null){
						String s ="[";
						String q ="]";
						String w ="";
						String strReplaceSymbol = String.valueOf(strMarkerArray2[position]).replace(s, w).replace(q, w);					
						String[] expShippingArray = strReplaceSymbol.split(",");
						
						 arrShippingList = new ArrayList<String>();
						
						final String expShipAddId = expShippingArray[0].trim();		
						final String expAddress1 = expShippingArray[1].trim();		
						final String expAddress2 = expShippingArray[2].trim();		
						final String expCity = expShippingArray[3].trim();		
						final String expState = expShippingArray[4].trim();		
						final String expZip = expShippingArray[5].trim();		
						final String expCountry = expShippingArray[6].trim();		
						final String expStatus = expShippingArray[7].trim();	
						final String expUserID = expShippingArray[8].trim();	
						Log.e("address",expAddress1);
						userShipId = expShipAddId;
						
						arrShippingList.add(expShipAddId);
						arrShippingList.add(expAddress1);
						arrShippingList.add(expAddress2);
						arrShippingList.add(expCity);
						arrShippingList.add(expState);
						arrShippingList.add(expZip);
						arrShippingList.add(expCountry);
						arrShippingList.add(expUserID);
						final View indexView = convertView;
						
						
						RelativeLayout relativeLayoutMain = (RelativeLayout) convertView.findViewById(R.id.relativeLayout1);
						relativeLayoutMain.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {
								// TODO Auto-generated method stub
								ArrayList<String> arrListForShippingAddressValues = new ArrayList<String>(); 
								arrListForShippingAddressValues.add(expShipAddId);
								arrListForShippingAddressValues.add(expAddress1);
								arrListForShippingAddressValues.add(expAddress2);
								arrListForShippingAddressValues.add(expCity);
								arrListForShippingAddressValues.add(expState);
								arrListForShippingAddressValues.add(expZip);
								arrListForShippingAddressValues.add(expCountry);
								arrListForShippingAddressValues.add(expUserID);
								Intent intent = new Intent(getApplicationContext(), OrderConfirmation.class);									
								intent.putStringArrayListExtra("arrListForShippingAddressValues", arrListForShippingAddressValues);		
								if(editOrderId != null && !editOrderId.equals("null"))
									intent.putExtra("editOrderId", editOrderId);									
						        setResult(2,intent);
						        finish();
							}
						});
						LinearLayout thumbnail = (LinearLayout)convertView.findViewById(R.id.thumbnail);
						RelativeLayout.LayoutParams rlForThumbnail= (RelativeLayout.LayoutParams) thumbnail.getLayoutParams();
						thumbnail.setPadding((int)(0.005* Common.sessionDeviceWidth), (int)(0.003* Common.sessionDeviceHeight), (int)(0.005* Common.sessionDeviceWidth), (int)(0.003* Common.sessionDeviceHeight));
						rlForThumbnail.rightMargin =(int)(0.00834* Common.sessionDeviceWidth);
						rlForThumbnail.topMargin =(int)(0.011* Common.sessionDeviceHeight);
						thumbnail.setLayoutParams(rlForThumbnail);
						
						ImageView imgarrow = (ImageView)convertView.findViewById(R.id.imgarrow);
						LinearLayout.LayoutParams rlForImgarrow = (LinearLayout.LayoutParams) imgarrow.getLayoutParams();
						rlForImgarrow.width =(int)(0.0834* Common.sessionDeviceWidth);
						rlForImgarrow.height =(int)(0.0512* Common.sessionDeviceHeight);
						imgarrow.setLayoutParams(rlForImgarrow);
						
						
						TextView txtvName = (TextView) convertView.findViewById(R.id.txtvName);
						RelativeLayout.LayoutParams rlForTxtvName = (RelativeLayout.LayoutParams) txtvName.getLayoutParams();
						rlForTxtvName.leftMargin =(int)(0.0516 * Common.sessionDeviceWidth);
						txtvName.setLayoutParams(rlForTxtvName);
						txtvName.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						txtvName.setText(expAddress1);
						
						TextView txtvAddress = (TextView) convertView.findViewById(R.id.txtvAddress);
						RelativeLayout.LayoutParams rlForTxtvAddress = (RelativeLayout.LayoutParams) txtvAddress.getLayoutParams();
						rlForTxtvAddress.leftMargin =(int)(0.0667* Common.sessionDeviceWidth);
						txtvAddress.setLayoutParams(rlForTxtvAddress);
						txtvAddress.setText(expCity+", "+expState+" "+expZip+"\n"+expCountry);
						if(position == 0){
							
							imgarrow.setVisibility(View.VISIBLE);
						}
						TextView btnShippingFormEdit = (TextView) convertView.findViewById(R.id.btnShippingFormEdit);
						RelativeLayout.LayoutParams rlForBtnShippingFormEdit = (RelativeLayout.LayoutParams) btnShippingFormEdit.getLayoutParams();
						rlForBtnShippingFormEdit.width =(int)(0.0834* Common.sessionDeviceWidth);
						rlForBtnShippingFormEdit.height =(int)(0.05* Common.sessionDeviceHeight);
						rlForBtnShippingFormEdit.rightMargin =(int)(0.0483* Common.sessionDeviceWidth);
						btnShippingFormEdit.setLayoutParams(rlForBtnShippingFormEdit);
						btnShippingFormEdit.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						btnShippingFormEdit.setOnClickListener(new OnClickListener() {							
							@Override
							public void onClick(View v) {
								Intent intent = new Intent(getApplicationContext(), ShippingAddressForm.class);
								intent.putExtra("userShipId", userShipId);
								intent.putStringArrayListExtra("arrShippingList", arrShippingList);
								int requestCode = 0;
								startActivityForResult(intent, requestCode);
							}
						});
						LinearLayout deletelayout = (LinearLayout)convertView.findViewById(R.id.deletelayout);
						RelativeLayout.LayoutParams rlForDeletelayout= (RelativeLayout.LayoutParams) deletelayout.getLayoutParams();
						deletelayout.setPadding((int)(0.005* Common.sessionDeviceWidth), (int)(0.003* Common.sessionDeviceHeight), (int)(0.005* Common.sessionDeviceWidth), (int)(0.003* Common.sessionDeviceHeight));
						rlForDeletelayout.rightMargin =(int)(0.00834* Common.sessionDeviceWidth);
						deletelayout.setLayoutParams(rlForDeletelayout);
						
						
						ImageView imgDeleteIcon = (ImageView) convertView.findViewById(R.id.imgDeleteIcon);
						LinearLayout.LayoutParams rlForImgDeleteIcon = (LinearLayout.LayoutParams) imgDeleteIcon.getLayoutParams();
						rlForImgDeleteIcon.width =(int)(0.0667* Common.sessionDeviceWidth);
						rlForImgDeleteIcon.height =(int)(0.041* Common.sessionDeviceHeight);
						imgDeleteIcon.setLayoutParams(rlForImgDeleteIcon);
						
						imgDeleteIcon.setOnClickListener(new OnClickListener() {
							
							@Override
							public void onClick(View v) {
								 AlertDialog.Builder alertDialog = new AlertDialog.Builder(ShippingListInfo.this);
								 
							        // Setting Dialog Title
							        alertDialog.setTitle("Confirm Delete...");
							 
							        // Setting Dialog Message
							        alertDialog.setMessage("Are you sure you want delete this?");
							 
							        // Setting Icon to Dialog
							      //  alertDialog.setIcon(R.drawable.delete);
							 
							        // Setting Positive "Yes" Button
							        alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
							            @Override
										public void onClick(DialogInterface dialog,int which) {
											HashMap<String, String> params = new HashMap<String, String>();
										    params.put("userId", expUserID);
										    params.put("userShipId", ""+userShipId);
										    
										    JSONObject jsonObject1 = new JSONObject(params);		
											Map<String, String> jsonParams = new HashMap<String, String>();
											jsonParams.put("json", jsonObject1.toString());
											jsonParams.put("userid", expUserID);
											
											//Log.e("json", jsonParams.);
											String shippingAddressUrl = Constants.Live_Url+"mobileapps/ios/public/stores/usershipping/delete/";
											aq.ajax(shippingAddressUrl, jsonParams, JSONObject.class, new AjaxCallback<JSONObject>(){			
												@Override
												public void callback(String url, JSONObject json, AjaxStatus status) {
													try{
														
														Log.e("json", ""+json);
														if(json!=null){
															lvSelectWishLists.removeViewInLayout(indexView);															
															((BaseAdapter) lvSelectWishLists.getAdapter()).notifyDataSetChanged();
															updateShippingList();
														}
													}catch(Exception e){
														e.printStackTrace();
													}
												}
											});
							            }
							        });
							        // Setting Negative "NO" Button
							        alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
							            @Override
										public void onClick(DialogInterface dialog, int which) {
							            // Write your code here to invoke NO event							         
							            dialog.cancel();
							            }
							        });
							 
							        // Showing Alert Message
							        alertDialog.show();
								
							}
						});
					}
					
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForRecentlyScannedListView    getview   |   " +e.getMessage();
					Common.sendCrashWithAQuery(ShippingListInfo.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		return aa;
	}	
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			Log.e("requestCode",requestCode+"resultCode"+resultCode);
			if(resultCode == 1){
				updateShippingList();
			}
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult   |   " +e.getMessage();
			Common.sendCrashWithAQuery(ShippingListInfo.this, errorMsg);
		}
	}
}
