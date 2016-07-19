package com.seemoreinteractive.seemoreinteractive;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;

import org.json.JSONArray;
import org.json.JSONObject;
import org.w3c.dom.Text;

import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.seemoreinteractive.seemoreinteractive.Model.OrderModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserOrder;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.fancycoverflow.FancyCoverFlow;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class OrderConfirmation extends Activity {

	String className = this.getClass().getSimpleName();
	AQuery aq;
	String[] expProdCartArray = null;
	FancyCoverFlow fancyCoverFlowForCart;
	HashMap<String, String> hashMapProdsWithClient, hashMapClientInfo, hashMapProdInfo;
	char priceSymbol;
	ArrayList<HashMap<String, String>> arrListHashMapProdsWithClient = new ArrayList<HashMap<String,String>>();
	int countFlagForClient = 0, countFlagForClientPds = 0;
	public static ArrayList<String> arrListForProdInfo;
	public static ArrayList<String> arrForClientIdsUnique = new ArrayList<String>();
	public static HashSet<String> hashSetForClientIdsUnique = new HashSet<String>();

	ArrayList<String> arrFinalAllClients = new ArrayList<String>();
	ArrayList<String> arrFinalClientIds = new ArrayList<String>();
	ArrayList<String> arrFinalClientsProducts = new ArrayList<String>();
	public  ArrayList<String> arrListForProdCount = new ArrayList<String>();
	//public static ArrayList<JSONObject> arrListJsonObjMySavedOrders = new ArrayList<JSONObject>();
	public static ArrayList<String> arrListJsonObjMySavedOrders = new ArrayList<String>();
	JSONObject jsonObjMySavedOrders = new JSONObject();
	SessionManager session;
	TextView txtvMessgae ;
	RelativeLayout shippingLayout;
	public  String shopFlag ="null",editOrderId="0",addressFlag="true"; 
	FileTransaction file;
	public static ArrayList<HashMap<String, String>> arrPdInfoForCartEditList,arrListHashMapForEditClientInfo;
	public static ArrayList<String> arrForClientIds;
	public static ArrayList<String> arrObjForShippingAddress;
	
	public static String offerDiscountValue= "null",offerDiscountValuType = "null",clientId ="null",offerName="null",offerId="null";	
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN) @Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_order_confirmation);
		try{
			session = new SessionManager(getApplicationContext());
			aq = new AQuery(OrderConfirmation.this);
			
			txtvMessgae		 = (TextView) findViewById(R.id.txtvMessgae);
			shippingLayout   = (RelativeLayout)findViewById(R.id.shippingLayout);
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Order Confirmation", "");
			new Common().headerAndFooterModules(OrderConfirmation.this);
			Intent getIntent = getIntent();
			if(getIntent.getExtras() != null){
				shopFlag	 = getIntent.getStringExtra("shopFlag");
				editOrderId  = getIntent.getStringExtra("editOrderId");
				Bundle bundle=getIntent.getExtras();

				 arrPdInfoForCartEditList 		 =  (ArrayList<HashMap<String, String>>) bundle.getSerializable("arrPdInfoForCartList");
				 arrListHashMapForEditClientInfo =  (ArrayList<HashMap<String, String>>) bundle.getSerializable("arrListHashMapForClientInfo");
				 arrForClientIds 				 =  (ArrayList<String>) bundle.getSerializable("arrForClientIds");				
				 file     = new FileTransaction();
			}
			
			for (String item : ProductDetails.arrForClientIds) {
				if (!hashSetForClientIdsUnique.contains(item)) {
					arrForClientIdsUnique.add(item);
					hashSetForClientIdsUnique.add(item);
			    }
			}
			
			if(shopFlag.equals("Edit")){
				if(arrPdInfoForCartEditList.size()>0){
					for(int i=0; i<arrPdInfoForCartEditList.size(); i++){
						hashMapProdsWithClient = new HashMap<String, String>();
						hashMapProdsWithClient.put(arrPdInfoForCartEditList.get(i).get("ClientId"), arrPdInfoForCartEditList.get(i).toString());
						arrListHashMapProdsWithClient.add(hashMapProdsWithClient);
						arrListForProdCount.add(arrPdInfoForCartEditList.get(i).get("ClientId"));
						arrObjForShippingAddress = getIntent().getStringArrayListExtra("arrObjForShippingAddress");
						//shippingAddressInfoShowingOnLayout(arrObjForShippingAddress, json.getString("statetax"), "");
						Log.e("arrObjForShippingAddress",""+arrObjForShippingAddress);
						basedOnShippingAddressInfoGetStateTax(arrObjForShippingAddress, "");
						
					}
				fancyCoverFlowForShoppingCartInfo(arrListHashMapForEditClientInfo, arrListHashMapProdsWithClient);
				}
			}else{
				Log.e("ProductDetails.arrListHashMapForClientInfo",""+ProductDetails.arrListHashMapForClientInfo.size());
				if(ProductDetails.arrPdInfoForCartList.size()>0){
					for(int i=0; i<ProductDetails.arrPdInfoForCartList.size(); i++){
						hashMapProdsWithClient = new HashMap<String, String>();
						hashMapProdsWithClient.put(ProductDetails.arrPdInfoForCartList.get(i).get("ClientId"), ProductDetails.arrPdInfoForCartList.get(i).toString());
						arrListHashMapProdsWithClient.add(hashMapProdsWithClient);	
						arrListForProdCount.add(ProductDetails.arrPdInfoForCartList.get(i).get("ClientId"));
					}
					fancyCoverFlowForShoppingCartInfo(ProductDetails.arrListHashMapForClientInfo, arrListHashMapProdsWithClient);
				}else{
					shippingAddressArrListVals = new ArrayList<String>();
					shippingLayout.setVisibility(View.INVISIBLE);
					txtvShippingAddressChange = (Button) findViewById(R.id.btnShippingAddressChange);
					txtvShippingAddressChange.setVisibility(View.INVISIBLE);
					txtvMessgae.setText("There are no products in your cart."); 
				}
			}
						
			getDynamicViewForLayout();
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate   |   " +e.getMessage();
			Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
		}
	}

	private void fancyCoverFlowForShoppingCartInfo(ArrayList<HashMap<String, String>> arrListHashMapForClientInfo2, ArrayList<HashMap<String, String>> arrListHashMapProdsWithClient2) {
		
		try{
			fancyCoverFlowForCart = (FancyCoverFlow) findViewById(R.id.fancyCoverFlowForCart);
			RelativeLayout.LayoutParams rlFancyCoverFlowCart = (RelativeLayout.LayoutParams) fancyCoverFlowForCart.getLayoutParams();
			rlFancyCoverFlowCart.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlFancyCoverFlowCart.height = (int) (0.5943 * Common.sessionDeviceHeight);
			fancyCoverFlowForCart.setLayoutParams(rlFancyCoverFlowCart);
			ArrayList<String> arrNewList = new ArrayList<String>();
			arrNewList.add(arrListHashMapForClientInfo2.toString());
			ArrayList<HashMap<String, String>> arrListHashMapForClientInfo = new ArrayList<HashMap<String, String>>();
			for(int j = arrListHashMapForClientInfo2.size() - 1; j >= 0; j--){			
				arrListHashMapForClientInfo.add(arrListHashMapForClientInfo2.get(j));
			}
			//Log.e("arrListHashMapForClientInfo new ",""+arrListHashMapForClientInfo);
			fancyCoverFlowForCart.setAdapter(renderCoverFlowForShoppingCart(arrListHashMapForClientInfo, arrListHashMapProdsWithClient2));
			fancyCoverFlowForCart.setSpacing(-(int)(0.2 * Common.sessionDeviceWidth));
			fancyCoverFlowForCart.setMaxRotation(45);
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | fancuCoverFlowForShoppingCartInfo  |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(OrderConfirmation.this,errorMsg);
		}
	}
	TextView txtvSalesTaxPrice, txtvOrderTotalPrice, txtvSubOrderTotalPrice, txtvShippingAddress1, txtvShippingAddress2;
	Button txtvShippingAddressChange; 
	private void getDynamicViewForLayout() {
		// TODO Auto-generated method stub
		try{
			
			
			TextView txtShippingAddressTitle = (TextView) findViewById(R.id.txtShippingAddressTitle);
			txtShippingAddressTitle.setTextSize((float)(0.04167 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForShippingAddressTitle = (RelativeLayout.LayoutParams) txtShippingAddressTitle.getLayoutParams();
			rlForShippingAddressTitle.leftMargin = (int) (0.025 * Common.sessionDeviceWidth);
			//rlForShippingAddressTitle.topMargin = (int) (0.0154 * Common.sessionDeviceHeight);
			//rlForShippingAddressTitle.topMargin = (int) (0.0286 * Common.sessionDeviceHeight);
			txtShippingAddressTitle.setLayoutParams(rlForShippingAddressTitle);

			txtvShippingAddress1 = (TextView) findViewById(R.id.txtvShippingAddress1);
			txtvShippingAddress1.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForShippingAddress1 = (RelativeLayout.LayoutParams) txtvShippingAddress1.getLayoutParams();
			rlForShippingAddress1.width = (int) (0.5 * Common.sessionDeviceWidth);
			//rlForShippingAddress1.height = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;
			//rlForShippingAddress1.leftMargin = (int) (0.075 * Common.sessionDeviceWidth);
			txtvShippingAddress1.setLayoutParams(rlForShippingAddress1);

			txtvShippingAddress2 = (TextView) findViewById(R.id.txtvShippingAddress2);
			txtvShippingAddress2.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForShippingAddress2 = (RelativeLayout.LayoutParams) txtvShippingAddress2.getLayoutParams();
			rlForShippingAddress2.width = (int) (0.5 * Common.sessionDeviceWidth);
			//rlForShippingAddress2.height = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;
			//rlForShippingAddress2.leftMargin = (int) (0.04 * Common.sessionDeviceWidth);
			txtvShippingAddress2.setLayoutParams(rlForShippingAddress2);
			
			if(ProductDetails.arrPdInfoForCartList.size()>0){
				shippingButtonText();
			}
			txtvShippingAddressChange = (Button) findViewById(R.id.btnShippingAddressChange);
			RelativeLayout.LayoutParams rlFortxtvShippingAddressChange = (RelativeLayout.LayoutParams) txtvShippingAddressChange.getLayoutParams();
			//rlFortxtvShippingAddressChange.leftMargin = (int) (0.0984 * Common.sessionDeviceWidth);
			rlFortxtvShippingAddressChange.width = (int) (0.1667 * Common.sessionDeviceWidth);
			rlFortxtvShippingAddressChange.height = (int) (0.031 * Common.sessionDeviceHeight);
			txtvShippingAddressChange.setLayoutParams(rlFortxtvShippingAddressChange);
			txtvShippingAddressChange.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			txtvShippingAddressChange.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub 
					try{
						//showShippingAddressInfoAsListView();
						txtvShippingAddressChange.setEnabled(false);
						JSONObject jsonObj = new JSONObject();
						jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
						Map<String, Object> params = new HashMap<String, Object>();
					    params.put("json", jsonObj);			
					    params.put("userid", Common.sessionIdForUserLoggedIn); 
						
						String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/usershipping";
						aq.ajax(productUrl, params, JSONArray.class, new AjaxCallback<JSONArray>(){			
							@Override
							public void callback(String url, JSONArray json, AjaxStatus status) {
								try{
			    					
			    					txtvShippingAddressChange.setEnabled(true);
			    					if(json != null){
					    				if(json.length() > 0){
											Intent intent = new Intent(getApplicationContext(), ShippingListInfo.class);
											intent.putExtra("userShipId", userShipIdForEdit);
											if(shopFlag.equals("Edit")){
												intent.putExtra("editOrderId", editOrderId);
											}
											intent.putExtra("jsonArray", json.toString());
											int requestCode = 0;
											startActivityForResult(intent, requestCode);
											overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_right);
					    				} else {
					    					Intent intent = new Intent(getApplicationContext(), ShippingAddressForm.class);
											intent.putExtra("userShipId", userShipIdForEdit);
											int requestCode = 0;
											startActivityForResult(intent, requestCode);
											overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_right);
					    				}
			    					}
								} catch(Exception e){
									e.printStackTrace();
						    		String errorMsg = className+" | aq.ajax showShippingAddressInfoAsListView callback  click  |   " +e.getMessage();
									Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
								}
							}
						});
						
						
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | shippingAddressChangeAdd onclick   |   " +e.getMessage();
						Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
					}
				}
			});
			
			
			
			TextView txtvSalesTaxTitle = (TextView) findViewById(R.id.txtvSalesTaxTitle);
			txtvSalesTaxTitle.setTextSize((float)(0.04167 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			//RelativeLayout.LayoutParams rlFortxtvSalesTaxTitle = (RelativeLayout.LayoutParams) txtvSalesTaxTitle.getLayoutParams();
			///rlForOrderTotalTitle.bottomMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			//rlFortxtvSalesTaxTitle.topMargin = (int) (0.115 * Common.sessionDeviceHeight);
			//txtvSalesTaxTitle.setLayoutParams(rlFortxtvSalesTaxTitle);

			txtvSalesTaxPrice = (TextView) findViewById(R.id.txtvSalesTaxPrice);
			txtvSalesTaxPrice.setTextSize((float)(0.04167 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));

			TextView txtvOrderTotalTitle = (TextView) findViewById(R.id.txtvOrderTotalTitle);
			txtvOrderTotalTitle.setTextSize((float)(0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForOrderTotalTitle = (RelativeLayout.LayoutParams) txtvOrderTotalTitle.getLayoutParams();
			//rlForOrderTotalTitle.bottomMargin = (int) (0.0205 * Common.sessionDeviceHeight);
			//rlForOrderTotalTitle.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			//txtvOrderTotalTitle.setLayoutParams(rlForOrderTotalTitle);
			txtvSubOrderTotalPrice  = (TextView) findViewById(R.id.txtvSubOrderTotalPrice);
			txtvOrderTotalPrice = (TextView) findViewById(R.id.txtvOrderTotalPrice);
			txtvOrderTotalPrice.setTextSize((float)(0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			RelativeLayout.LayoutParams rlForOrderTotalPrice = (RelativeLayout.LayoutParams) txtvOrderTotalPrice.getLayoutParams();
			rlForOrderTotalPrice.width = (int) (0.4334 * Common.sessionDeviceWidth);
			rlForOrderTotalPrice.height = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;
			//rlForOrderTotalPrice.rightMargin = (int) (0.075 * Common.sessionDeviceWidth);
			txtvOrderTotalPrice.setLayoutParams(rlForOrderTotalPrice);
			
			Button btnCheckout = (Button) findViewById(R.id.btnCheckout);
			RelativeLayout.LayoutParams rlForBtnCheckout = (RelativeLayout.LayoutParams) btnCheckout.getLayoutParams();
			rlForBtnCheckout.width = (int) (0.25 * Common.sessionDeviceWidth);
			rlForBtnCheckout.height = (int) (0.0615 * Common.sessionDeviceHeight);
			//rlForBtnCheckout.topMargin = (int) (0.0052 * Common.sessionDeviceHeight);
		//	rlForBtnCheckout.bottomMargin = (int) (0.0052 * Common.sessionDeviceHeight);
			//rlForBtnCheckout.rightMargin = (int) (0.0417 * Common.sessionDeviceWidth);
			btnCheckout.setLayoutParams(rlForBtnCheckout);
			btnCheckout.setTextSize((float)(0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			btnCheckout.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub 
					try{
						if(Common.isNetworkAvailable(OrderConfirmation.this)){
						if(!session.isLoggedIn()){
							new Common().getLoginDialog(OrderConfirmation.this, OrderConfirmation.class, "OrderConfirmation", new ArrayList<String>());
						} else {							
							final ArrayList<String> arrFinalAll = new ArrayList<String>();
							if(shippingAddressArrListVals.size()==0){
								Toast.makeText(getApplicationContext(), "Please add your shipping address and then click on Checkout.", Toast.LENGTH_LONG).show();
							} else {
								PackageManager manager = OrderConfirmation.this.getPackageManager();
								PackageInfo info = manager.getPackageInfo(OrderConfirmation.this.getPackageName(), 0);
	
								final String sessionId = Common.randomString(40);
								final JSONObject jsonObjForAnalytics = new JSONObject();
								jsonObjForAnalytics.put("session_id", sessionId);
								jsonObjForAnalytics.put("device_type", android.os.Build.MODEL);
								jsonObjForAnalytics.put("device_os_version", android.os.Build.VERSION.RELEASE);
								jsonObjForAnalytics.put("device_os", "ANDROID");
								jsonObjForAnalytics.put("device_brand", android.os.Build.BRAND);
								jsonObjForAnalytics.put("lat_long", Common.lat+","+Common.lng);
								jsonObjForAnalytics.put("device_bundle_version", info.versionName+" ("+info.versionCode+")");
								
							
								
								JSONObject jsonObj = new JSONObject();
								jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
								//jsonObj.put("orderId", 0);
								if(shopFlag.equals("null")){	
									if(arrListJsonObjMySavedOrders.size()==0){
										jsonObj.put("orderId", 0);
									}else{
										jsonObj.put("orderId", arrListJsonObjMySavedOrders.get(0));
									}
								}else{
									jsonObj.put("orderId", editOrderId);
								}
								jsonObj.put("orderTotal", txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
								jsonObj.put("salesTax", txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
								jsonObj.put("salesTaxValue", txtvSalesTaxPrice.getTag());
								jsonObj.put("shippingAddress", jsonObjForShippingAddress);
								jsonObj.put("clients", jsonArrayForClients);
								jsonObj.put("analytics", jsonObjForAnalytics);
								jsonObj.put("session_id", sessionId);
								jsonObj.put("userShipId", userShipIdForEdit);		
								arrFinalAll.add(jsonObj.toString().replace("\\", ""));
								
	
								Map<String, Object> params = new HashMap<String, Object>();
							    params.put("json", arrFinalAll.get(0));			
							    params.put("userid", Common.sessionIdForUserLoggedIn);  
							    if(Common.sessionIdForUserLoggedIn!=0){
									String orderCreateUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/create/";
									aq.ajax(orderCreateUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
										@Override
										public void callback(String url, JSONObject json, AjaxStatus status) {
											try{
												
												if(json!=null){
													
													if(json.getString("msg").equals("success")){
														arrFinalAll.clear();
														JSONObject jsonObj = new JSONObject();
														jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
														jsonObj.put("orderId", json.getString("orderID"));
														jsonObj.put("orderTotal", txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
														SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
														String currentDateandTime = dateFormat.format(new Date());
														jsonObj.put("orderDate", currentDateandTime);
														jsonObj.put("salesTax", txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
														jsonObj.put("salesTaxValue", txtvSalesTaxPrice.getTag());
														jsonObj.put("shippingAddress", jsonObjForShippingAddress);
														jsonObj.put("clients", jsonArrayForClients);
														jsonObj.put("analytics", jsonObjForAnalytics);
														jsonObj.put("session_id", sessionId);
														arrFinalAll.add(jsonObj.toString().replace("\\", ""));
														
														
														/*jsonObjMySavedOrders.put("orderId", json.getString("orderID"));
														jsonObjMySavedOrders.put("orderTotal", txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
														jsonObjMySavedOrders.put("orderDate", currentDateandTime);
														arrListJsonObjMySavedOrders.add(jsonObjMySavedOrders);
														*/
														
														if(arrListJsonObjMySavedOrders.size() == 0){
															arrListJsonObjMySavedOrders.add(json.getString("orderID"));
														}
														
														Intent intent = new Intent(getApplicationContext(), ProductsCheckout.class);
														int requestCode = 0;
														intent.putExtra("orderId", json.getString("orderID"));
														intent.putExtra("finalMsg", json.getJSONObject("payment").getString("saveOrderMsg"));
														intent.putStringArrayListExtra("arrFinalAll", arrFinalAll);
														intent.putExtra("shopFlag", shopFlag);
														startActivityForResult(intent, requestCode);
													}
													//Toast.makeText(getApplicationContext(), "Successfully saved to your Order.", Toast.LENGTH_LONG).show();
													/*Intent intent = new Intent(ProductsCheckout.this, ThankYou.class);
													intent.putExtra("finalMsg", json.getJSONObject("payment").getString("saveOrderMsg"));
													startActivity(intent);
													finish();
												    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);*/
												}
											} catch(Exception e){
												e.printStackTrace();
												String errorMsg = className+" | btnCheckout callback  |   " +e.getMessage();
												Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
											}
										}
									});	
							    }
							}
						}
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | Checkout button onclick   |   " +e.getMessage();
						Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
					}
				}
			});			
			
			

			Button btnShop = (Button) findViewById(R.id.btnShop);
			RelativeLayout.LayoutParams rlForBtnShop= (RelativeLayout.LayoutParams) btnShop.getLayoutParams();
			rlForBtnShop.width = (int) (0.25 * Common.sessionDeviceWidth);
			rlForBtnShop.height = (int) (0.0615 * Common.sessionDeviceHeight);
			btnShop.setLayoutParams(rlForBtnShop);
			btnShop.setTextSize((float)(0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			btnShop.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					Intent intent = new Intent(OrderConfirmation.this, Products.class);
					if(shopFlag.equals("Edit")){	
						Products.prds.finish();						
						intent.putExtra("shopFlag", "Edit");
						intent.putExtra("orderId", editOrderId);
					}else{
						intent.addFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);					
					}
					startActivity(intent);
				    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
					finish();
				}
			});
			
			Button btnSaveOrder = (Button) findViewById(R.id.btnSaveOrder);
			RelativeLayout.LayoutParams rlForbtnSaveOrder= (RelativeLayout.LayoutParams) btnSaveOrder.getLayoutParams();
			rlForbtnSaveOrder.width = (int) (0.25 * Common.sessionDeviceWidth);
			rlForbtnSaveOrder.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlForbtnSaveOrder.bottomMargin =  (int) (0.0103 * Common.sessionDeviceHeight);
			btnSaveOrder.setLayoutParams(rlForbtnSaveOrder);
			btnSaveOrder.setTextSize((float)(0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			btnSaveOrder.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
						try{
							
							if(Common.isNetworkAvailable(OrderConfirmation.this)){
						
							PackageManager manager = OrderConfirmation.this.getPackageManager();
							PackageInfo info = manager.getPackageInfo(OrderConfirmation.this.getPackageName(), 0);
							final ArrayList<String> arrFinalAll = new ArrayList<String>();
							final String sessionId = Common.randomString(40);
							final JSONObject jsonObjForAnalytics = new JSONObject();
							jsonObjForAnalytics.put("session_id", sessionId);
							jsonObjForAnalytics.put("device_type", android.os.Build.MODEL);
							jsonObjForAnalytics.put("device_os_version", android.os.Build.VERSION.RELEASE);
							jsonObjForAnalytics.put("device_os", "ANDROID");
							jsonObjForAnalytics.put("device_brand", android.os.Build.BRAND);
							jsonObjForAnalytics.put("lat_long", Common.lat+","+Common.lng);
							jsonObjForAnalytics.put("device_bundle_version", info.versionName+" ("+info.versionCode+")");
							
							
							
							JSONObject jsonObj = new JSONObject();
							jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
							if(shopFlag.equals("null"))
								if(arrListJsonObjMySavedOrders.size()==0){
									jsonObj.put("orderId", 0);
								}else{
									jsonObj.put("orderId", arrListJsonObjMySavedOrders.get(0));
								}
							else
								jsonObj.put("orderId", editOrderId);
							jsonObj.put("orderTotal", txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
							jsonObj.put("salesTax", txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
							jsonObj.put("salesTaxValue", txtvSalesTaxPrice.getTag());
							jsonObj.put("shippingAddress", jsonObjForShippingAddress);
							jsonObj.put("userShipId", userShipIdForEdit);							
							jsonObj.put("clients", jsonArrayForClients);
							jsonObj.put("analytics", jsonObjForAnalytics);
							jsonObj.put("session_id", sessionId);
							jsonObj.put("paymentMethod", "Save Order");
							jsonObj.put("paymentMethodId", "1");
		
							arrFinalAll.add(jsonObj.toString().replace("\\", ""));
							
							Map<String, Object> params = new HashMap<String, Object>();
						    params.put("json", arrFinalAll.get(0));			
						    params.put("userid", Common.sessionIdForUserLoggedIn);  
						    Log.e("params",""+params);
						    if(Common.sessionIdForUserLoggedIn!=0){
						    	if(shippingAddressArrListVals.size()==0){
						    		Toast.makeText(getApplicationContext(), "Please add your shipping address and then save order.", Toast.LENGTH_LONG).show();
						    	}else{
									String orderCreateUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/create/";
									aq.ajax(orderCreateUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
										@Override
										public void callback(String url, JSONObject json, AjaxStatus status) {
											try{
												
												if(json!=null){													
													if(json.getString("msg").equals("success")){
														file = new FileTransaction();
														OrderModel getOrders = file.getOrder();
														
														int orderID    = Integer.parseInt(json.getString("orderID").toString());
														UserOrder existUserOrder = getOrders.getUserOrder(orderID);
															Log.e("order client info",""+ProductDetails.arrListHashMapForClientInfo);
														if(existUserOrder == null){
															UserOrder userOrder =  new UserOrder();
															userOrder.setId(orderID);
															userOrder.setCartClientInfoList(ProductDetails.arrListHashMapForClientInfo);
															userOrder.setCartInfoList(ProductDetails.arrPdInfoForCartList);
															userOrder.setClientIds(ProductDetails.arrForClientIds);
															userOrder.setUserShipId(jsonObjForShippingAddress.get("userShipId").toString());
															userOrder.setAddress1(jsonObjForShippingAddress.get("addr1").toString());
															userOrder.setAddress2(jsonObjForShippingAddress.get("addr2").toString());
															userOrder.setCity(jsonObjForShippingAddress.get("city").toString());
															userOrder.setState(jsonObjForShippingAddress.get("state").toString());
															userOrder.setZip(jsonObjForShippingAddress.get("zip").toString());
															userOrder.setCountry(jsonObjForShippingAddress.get("country").toString());														
															//userOrder.setJsonObjForShippingAddress(jsonObjForShippingAddress);
															userOrder.setOrderTotal(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
															userOrder.setSalesTax(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
															userOrder.setSalesTaxValue(txtvSalesTaxPrice.getTag().toString());
															SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
															String currentDateandTime = dateFormat.format(new Date());
															userOrder.setCreatedDate(currentDateandTime);
															OrderModel orderModel = new OrderModel();
															orderModel.add(userOrder);															
															FileTransaction file = new FileTransaction();														
															getOrders.mergeWith(orderModel);
															file.setOrder(getOrders);
														}else{
															existUserOrder.setId(orderID);
															existUserOrder.setCartClientInfoList(arrListHashMapForEditClientInfo);
															existUserOrder.setCartInfoList(arrPdInfoForCartEditList);
															existUserOrder.setClientIds(arrForClientIds);
															existUserOrder.setUserShipId(jsonObjForShippingAddress.get("userShipId").toString());
															existUserOrder.setAddress1(jsonObjForShippingAddress.get("addr1").toString());
															existUserOrder.setAddress2(jsonObjForShippingAddress.get("addr2").toString());
															existUserOrder.setCity(jsonObjForShippingAddress.get("city").toString());
															existUserOrder.setState(jsonObjForShippingAddress.get("state").toString());
															existUserOrder.setZip(jsonObjForShippingAddress.get("zip").toString());
															existUserOrder.setCountry(jsonObjForShippingAddress.get("country").toString());														
															//userOrder.setJsonObjForShippingAddress(jsonObjForShippingAddress);
															existUserOrder.setOrderTotal(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""));
															existUserOrder.setSalesTax(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
															existUserOrder.setSalesTaxValue(txtvSalesTaxPrice.getTag().toString());
															SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
															String currentDateandTime = dateFormat.format(new Date());
															existUserOrder.setCreatedDate(currentDateandTime);
															getOrders.update(existUserOrder);
															FileTransaction file = new FileTransaction();											
															file.setOrder(getOrders);
														}
														if(shopFlag.equals("null")){
															ProductDetails.prdsDetail.finish();														
															ProductDetails.arrPdInfoForCartList.clear();
															ProductDetails.arrForClientIds.clear();
															ProductDetails.arrListHashMapForClientInfo.clear();
															offerDiscountValue= "null";
															offerDiscountValuType = "null";
															offerName= "null";
															offerId ="null";
															clientId ="null";
														}else{
															SaveOrderInformation.saveOrder.finish();
														}
									                    Products.prds.finish();
									                    OrderConfirmation.arrListJsonObjMySavedOrders.clear();
														Intent intent = new Intent(OrderConfirmation.this, ThankYou.class);
														intent.putExtra("finalMsg", json.getJSONObject("payment").getString("saveOrderMsg"));
														startActivity(intent);													 
														finish();													
													    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
													} else {
														Toast.makeText(OrderConfirmation.this, "Opps! Your order updation failed. \nPlease contact us for more details.", Toast.LENGTH_SHORT).show();
													}
												}
										} catch(Exception e){
											e.printStackTrace();
											String errorMsg = className+" | btnCheckoutConfirm order update callback  |   " +e.getMessage();
											Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
										}
									}
								});	
						    	}
						}
							}
						}catch(Exception e){
							e.printStackTrace();
						}
					}
				});
			
			
			 
			if(shippingAddressArrListVals.size()>0){
				if(shopFlag.equals("null")){
					basedOnShippingAddressInfoGetStateTax(shippingAddressArrListVals, "");
				}
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getDynamicViewForLayout  |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(OrderConfirmation.this,errorMsg);
		}
	}
	
	public void shippingButtonText(){
		try{
			txtvShippingAddressChange = (Button) findViewById(R.id.btnShippingAddressChange);
			JSONObject jsonObj = new JSONObject();
			jsonObj.put("userId", ""+Common.sessionIdForUserLoggedIn);
			Map<String, Object> params = new HashMap<String, Object>();
		    params.put("json", jsonObj);			
		    params.put("userid", Common.sessionIdForUserLoggedIn); 
			String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/usershipping";
			aq.ajax(productUrl, params, JSONArray.class, new AjaxCallback<JSONArray>(){			
				@Override
				public void callback(String url, JSONArray json, AjaxStatus status) {
					try{
    					
	    				if(json!=null){
			    				if(json.length() > 0){
			    					txtvShippingAddressChange.setText("Change");
				    				if(shippingAddressArrListVals.size()==0){				    					
				    					for(int i=0; i<json.length(); i++){		
				    						JSONObject jsonObjForShippingInfo = json.getJSONObject(i);
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_addr_id").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_addr1").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_addr2").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_city").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_state").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_zip").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_country").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_ship_status").toString());
				    						shippingAddressArrListVals.add(jsonObjForShippingInfo.get("user_id").toString());										
				    					}
				    					basedOnShippingAddressInfoGetStateTax(shippingAddressArrListVals, "");
				    				}
			    				}
			    				else{ 
			    					shippingAddressArrListVals = new ArrayList<String>();
			    					double txtvSubOrderTotalPriceVal = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, "")) - Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
			    					txtvSalesTaxPrice.setText("$0.00");
			    					txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(txtvSubOrderTotalPriceVal));
			    					txtvShippingAddressChange.setText("Add");
			    					txtvShippingAddress1.setText("");
			    					txtvShippingAddress2.setText("");
			    				  }
	    				}
					} catch(Exception e){
						e.printStackTrace();
			    		String errorMsg = className+" | aq.ajax showShippingAddressInfoAsListView callback  click  |   " +e.getMessage();
						Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
					}
				}
			});
			
		}catch(Exception e){
			e.printStackTrace();
		}
	}

	public HashMap<String, String> convertToHashMap(String arrayString) {
        HashMap<String, String> myHashMap = new HashMap<String, String>();
        try {
            String str = arrayString.replace("{", "").replace("}", "");
            String strNull = "";
        	String[] strArr = str.split(", ");
        	for (String str1 : strArr) {
                String[] splited = str1.split("=");
                if(splited[1].isEmpty()){
                	strNull = "";
                } else {
                	strNull = splited[1].trim();
                }
                myHashMap.put(splited[0], strNull);
            }
        } catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | convertToHashMap  |   " +e.getMessage();
			Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
		}
        return myHashMap;
    }
	JSONObject jsonObjForClients;
	JSONObject jsonObjForClientsProducts;
	JSONArray jsonArrayForClientsProducts = new JSONArray(), jsonArrayForClients = new JSONArray();
	double clientWiseTotalPrice = 0;
	LinearLayout listViewForProds;
	int gridItemLayout = 0;    
    int countForFlagArrAdp = 0;
    ArrayList<String> newListViewArrPdsInfo;
    String tempClientId = "";
    String tempClientIdForPds = "";
    String tempClientIdForClients = "";
    String tempClientIdForClientsIds = "";
    ArrayAdapter<HashMap<String, String>> aa;
    float cartTotalPriceValues = 0;
    String cartTotalPriceForAdd = "";
	Spinner spinner;
	ArrayList<String> spinnerArray, spinnerShipMethodValsArray;
	ArrayAdapter<String> spinnerArrayAdapter;
	int countForFlagArrAdpNew=0;	
	String shippingMethodName = "null", shippingMethodDetails = "null", 
			shippingMethodRateForTitle = "null", shippingMethodRate = "null";
	public ArrayAdapter<HashMap<String, String>> renderCoverFlowForShoppingCart(final ArrayList<HashMap<String, String>> arrListHashMapForClientInfo2, 
			final ArrayList<HashMap<String, String>> arrListHashMapProdsWithClient2){
	    gridItemLayout = R.layout.coverflow_order_conf_pds_layout;
	    priceSymbol = '$';
	    final ArrayList<String>  arrClientProdList = new ArrayList<String>();
	    cartTotalPriceForAdd = "";
		aa = new ArrayAdapter<HashMap<String, String>>(this, gridItemLayout, arrListHashMapForClientInfo2){
			@Override
			public View getView(final int position,  View convertView, ViewGroup parent) {
				try {
					 Boolean shippingMethodFlag =false;
					 float cartTotalPrice = 0;
					
					if(convertView == null){
						convertView = getLayoutInflater().inflate(gridItemLayout, parent, false);
					}
					final View convertViewId = convertView;
					
					countForFlagArrAdp = position;
					if(position==countForFlagArrAdp){
						final AQuery aq2 = new AQuery(convertView);
						
						RelativeLayout coverflowLlayoutImage = (RelativeLayout)convertView.findViewById(R.id.coverflowLlayoutImage);
						LinearLayout.LayoutParams rlForCoverflowLlayoutImage = (LinearLayout.LayoutParams) coverflowLlayoutImage.getLayoutParams();
						rlForCoverflowLlayoutImage.width = (int)(0.75 * Common.sessionDeviceWidth);
						rlForCoverflowLlayoutImage.height = (int) (0.522* Common.sessionDeviceHeight);	
						coverflowLlayoutImage.setLayoutParams(rlForCoverflowLlayoutImage);
												
						
						RelativeLayout bigImageWithName = (RelativeLayout)convertView.findViewById(R.id.bigImageWithName);
						RelativeLayout.LayoutParams rlForBigImageWithName = (RelativeLayout.LayoutParams) bigImageWithName.getLayoutParams();
						rlForBigImageWithName.width = (int)(0.667 * Common.sessionDeviceWidth);
						rlForBigImageWithName.height = (int) (0.461* Common.sessionDeviceHeight);	
						rlForBigImageWithName.bottomMargin = (int) (0.0205* Common.sessionDeviceHeight);	
						bigImageWithName.setLayoutParams(rlForBigImageWithName);
												
						
						ScrollView scrollViewForPdList =(ScrollView)convertView.findViewById(R.id.scrollViewForPdList);
						RelativeLayout.LayoutParams rlForScrollViewForPdList = (RelativeLayout.LayoutParams) scrollViewForPdList.getLayoutParams();
						rlForScrollViewForPdList.width = (int)(0.667 * Common.sessionDeviceWidth);
						rlForScrollViewForPdList.height = (int) (0.235* Common.sessionDeviceHeight);	
						rlForScrollViewForPdList.topMargin = (int) (0.0102* Common.sessionDeviceHeight);
						scrollViewForPdList.setLayoutParams(rlForScrollViewForPdList);
						
						listViewForProds = (LinearLayout) convertView.findViewById(R.id.listViewForProds);
						ScrollView.LayoutParams rlForListViewForProds = (ScrollView.LayoutParams) listViewForProds.getLayoutParams();
						rlForListViewForProds.height = (int) (0.153* Common.sessionDeviceHeight);	
						rlForListViewForProds.topMargin = (int) (0.0102* Common.sessionDeviceHeight);
						rlForListViewForProds.bottomMargin = (int) (0.0102* Common.sessionDeviceHeight);
						rlForListViewForProds.leftMargin = (int) (0.0167* Common.sessionDeviceWidth);
						rlForListViewForProds.rightMargin = (int) (0.0167* Common.sessionDeviceWidth);
						listViewForProds.setLayoutParams(rlForListViewForProds);
						
						
						View separator2 =(View)convertView.findViewById(R.id.separator2);
						RelativeLayout.LayoutParams rlForSeparator2 = (RelativeLayout.LayoutParams) separator2.getLayoutParams();
						rlForSeparator2.width = (int)(0.75 * Common.sessionDeviceWidth);
						rlForSeparator2.height = (int) (0.001* Common.sessionDeviceHeight);	
						rlForSeparator2.topMargin =  (int) (0.00205* Common.sessionDeviceHeight);	
						separator2.setLayoutParams(rlForSeparator2);
						
						TextView txtvCartTotalTitle = (TextView)convertView.findViewById(R.id.txtvCartTotalTitle);
						RelativeLayout.LayoutParams rlForTxtvCartTotalTitle = (RelativeLayout.LayoutParams) txtvCartTotalTitle.getLayoutParams();
						rlForListViewForProds.topMargin = (int) (0.0102* Common.sessionDeviceHeight);
						txtvCartTotalTitle.setLayoutParams(rlForTxtvCartTotalTitle);
						txtvCartTotalTitle.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtvCartTotalPrice = (TextView)convertView.findViewById(R.id.txtvCartTotalPrice);
						RelativeLayout.LayoutParams rlForTxtvCartTotalPrice = (RelativeLayout.LayoutParams) txtvCartTotalPrice.getLayoutParams();
						rlForTxtvCartTotalPrice.rightMargin = (int) (0.0334 * Common.sessionDeviceWidth);
						txtvCartTotalPrice.setLayoutParams(rlForTxtvCartTotalPrice);
						txtvCartTotalPrice.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						/*TextView txtvShippingMethodTitle = (TextView)convertView.findViewById(R.id.txtvShippingMethodTitle);
						RelativeLayout.LayoutParams rlForTxtvShippingMethodTitle= (RelativeLayout.LayoutParams) txtvShippingMethodTitle.getLayoutParams();
						rlForTxtvShippingMethodTitle.bottomMargin = (int) (0.0102* Common.sessionDeviceHeight);
						rlForTxtvShippingMethodTitle.leftMargin = (int) (0.0167* Common.sessionDeviceWidth);
						txtvShippingMethodTitle.setLayoutParams(rlForTxtvShippingMethodTitle);
						txtvShippingMethodTitle.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));*/
						
						TextView txtvShippingCostTitle = (TextView)convertView.findViewById(R.id.txtvShippingCostTitle);
						/*RelativeLayout.LayoutParams rlForTxtvShippingCostTitle= (RelativeLayout.LayoutParams) txtvShippingCostTitle.getLayoutParams();
						rlForTxtvShippingCostTitle.rightMargin = (int) (0.0667* Common.sessionDeviceWidth);
						txtvShippingCostTitle.setLayoutParams(rlForTxtvShippingCostTitle);*/
						txtvShippingCostTitle.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtvShippingSubTotalTitle = (TextView)convertView.findViewById(R.id.txtvShippingSubTotalTitle);
						txtvShippingSubTotalTitle.setTextSize((float)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtvShippingCostPrice = (TextView)convertView.findViewById(R.id.txtvShippingCostPrice);
						RelativeLayout.LayoutParams rlFortxtvShippingCostPrice = (RelativeLayout.LayoutParams) txtvShippingCostPrice.getLayoutParams();
						rlFortxtvShippingCostPrice.rightMargin = (int) (0.0334 * Common.sessionDeviceWidth);
						txtvShippingCostPrice.setLayoutParams(rlFortxtvShippingCostPrice);
						txtvShippingCostPrice.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtvShippingSubTotalPrice = (TextView)convertView.findViewById(R.id.txtvShippingSubTotalPrice);
						RelativeLayout.LayoutParams rlFortxtvShippingSubTotalPrice = (RelativeLayout.LayoutParams) txtvShippingSubTotalPrice.getLayoutParams();
						rlFortxtvShippingSubTotalPrice.rightMargin = (int) (0.0334 * Common.sessionDeviceWidth);
						txtvShippingSubTotalPrice.setLayoutParams(rlFortxtvShippingSubTotalPrice);
						txtvShippingSubTotalPrice.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtvShippingDelivery = (TextView)convertView.findViewById(R.id.txtvShippingDelivery);
						RelativeLayout.LayoutParams rlForTxtvShippingDelivery= (RelativeLayout.LayoutParams) txtvShippingDelivery.getLayoutParams();
						rlForTxtvShippingDelivery.leftMargin = (int) (0.01667* Common.sessionDeviceWidth);
						txtvShippingDelivery.setLayoutParams(rlForTxtvShippingDelivery);
						txtvShippingDelivery.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						TextView txtofferDiscountCost = (TextView)convertView.findViewById(R.id.txtofferDiscountCost);
						RelativeLayout.LayoutParams rlFortxtofferDiscountCost = (RelativeLayout.LayoutParams) txtofferDiscountCost.getLayoutParams();
						rlFortxtofferDiscountCost.rightMargin = (int) (0.0334 * Common.sessionDeviceWidth);
						txtofferDiscountCost.setLayoutParams(rlFortxtofferDiscountCost);
						txtofferDiscountCost.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						final TextView txtvofferName = (TextView)convertView.findViewById(R.id.txtvofferName);
						RelativeLayout.LayoutParams rlForTxtvofferName = (RelativeLayout.LayoutParams) txtvofferName.getLayoutParams();
						rlForTxtvofferName.width = (int) (0.2 * Common.sessionDeviceWidth);						
						txtvofferName.setLayoutParams(rlForTxtvofferName);
						txtvofferName.setTextSize((float)(0.026667 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						final TextView txtvofferVal = (TextView)convertView.findViewById(R.id.txtvofferVal);
						/*RelativeLayout.LayoutParams rlForTxtvofferVal = (RelativeLayout.LayoutParams) txtvofferVal.getLayoutParams();
						rlForTxtvofferVal.width = (int) (0.2 * Common.sessionDeviceWidth);			*/			
					//	txtvofferVal.setLayoutParams(rlForTxtvofferName);
						txtvofferVal.setTextSize((float)(0.026667 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						final ImageView imgClose = (ImageView)convertView.findViewById(R.id.imgClose);
						RelativeLayout.LayoutParams rlForImgClose = (RelativeLayout.LayoutParams) imgClose.getLayoutParams();
						rlForImgClose.width  = (int) (0.05 * Common.sessionDeviceWidth);
						rlForImgClose.height = (int) (0.03074 * Common.sessionDeviceWidth);		
						imgClose.setLayoutParams(rlForImgClose);
						
						TextView txtofferDiscountTitle = (TextView)convertView.findViewById(R.id.txtofferDiscountTitle);
						txtofferDiscountTitle.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
						
						
						
						/*listViewForProds.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {
								// TODO Auto-generated method stub
								Intent intent = new Intent(OrderConfirmation.this, ProductDetails.class);
								setResult(1, intent);
								finish();
							    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
							}
						});*/
						if(!arrListHashMapForClientInfo2.get(position).isEmpty()){
							try{
							
							final HashMap<String, String> hashMapValues = arrListHashMapForClientInfo2.get(position);
							TextView btnClientLogoOrName = (TextView) convertView.findViewById(R.id.btnClientLogoOrName);
							RelativeLayout.LayoutParams rlpForBtnClientLogo = (RelativeLayout.LayoutParams) btnClientLogoOrName.getLayoutParams();
							rlpForBtnClientLogo.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
							rlpForBtnClientLogo.width = (int) (0.417 * Common.sessionDeviceWidth);
							rlpForBtnClientLogo.height = (int) (0.041 * Common.sessionDeviceHeight);
							btnClientLogoOrName.setText(hashMapValues.get("ClientName"));
							btnClientLogoOrName.setTextSize((float) (0.0417 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));							
							btnClientLogoOrName.setLayoutParams(rlpForBtnClientLogo);
							shippingMethodName         = hashMapValues.get("ShippingMethodName");
							shippingMethodDetails      = hashMapValues.get("ShippingMethodDetails");
							shippingMethodRateForTitle = hashMapValues.get("ShippingMethodRateTitle");
							shippingMethodRate         = hashMapValues.get("ShippingMethodRate");
							Log.e("hashMapValues.getofferId)",hashMapValues.get("offer_id"));
							if(!hashMapValues.get("offer_id").equals("null")){
								offerId = hashMapValues.get("offer_id");
								offerName = hashMapValues.get("offer_name");
								offerDiscountValue = hashMapValues.get("offer_discount");								
								offerDiscountValuType = hashMapValues.get("offer_discount_type");
								clientId = hashMapValues.get("ClientId");
								Log.e("offerDiscountValue",offerDiscountValue);
							}
							
							final HashMap<String, String> hashMapValuesNew = hashMapValues;
							
							LayoutInflater inflater = (LayoutInflater) getBaseContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
							String prodID = "null"; 
							
							int l=0;
							for(int c=0; c<arrListHashMapProdsWithClient2.size(); c++){
								
								//Log.e("c",""+c);
								
								final int arrIndex = c;
								HashMap<String, String> hashMapValuesForListView = arrListHashMapProdsWithClient2.get(c);
								
								if(hashMapValuesForListView.get(hashMapValues.get("ClientId"))!=null){									
									
									final HashMap<String, String> hashMapValuesForListViewNew = convertToHashMap(hashMapValuesForListView.get(hashMapValues.get("ClientId")));									
									arrClientProdList.add(hashMapValuesForListViewNew.get("ClientId"));
									
									
									final int arrClientIndex = l;
									View child = inflater.inflate(R.layout.listview_order_conf_pds_layout_new, null);
									AQuery aq3 = new AQuery(child);
									prodID = hashMapValuesForListViewNew.get("ProdId");
									TextView txtvExpProductName = (TextView)child.findViewById(R.id.txtvExpProductName);
									TableRow.LayoutParams rlForTxtvExpProductName = (TableRow.LayoutParams) txtvExpProductName.getLayoutParams();
									rlForTxtvExpProductName.width = (int)(0.2167 * Common.sessionDeviceWidth);
									txtvExpProductName.setLayoutParams(rlForTxtvExpProductName);
									txtvExpProductName.setTextSize((float) (0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));		
									
									
									
									TextView txtvExpProdQuantityWithPrice = (TextView)child.findViewById(R.id.txtvExpProdQuantityWithPrice);
									TableRow.LayoutParams rlForTxtvExpProdQuantityWithPrice = (TableRow.LayoutParams) txtvExpProdQuantityWithPrice.getLayoutParams();
									rlForTxtvExpProdQuantityWithPrice.width = (int)(0.2167 * Common.sessionDeviceWidth);
								//	rlForTxtvExpProdQuantityWithPrice.rightMargin = (int)(0.00834 * Common.sessionDeviceWidth);
									txtvExpProdQuantityWithPrice.setLayoutParams(rlForTxtvExpProdQuantityWithPrice);
									txtvExpProdQuantityWithPrice.setTextSize((float) (0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));		
									
																		
									TextView txtvExpProdPrice = (TextView)child.findViewById(R.id.txtvExpProdPrice);
									TableRow.LayoutParams rlForTxtvExpProdPrice = (TableRow.LayoutParams) txtvExpProdPrice.getLayoutParams();
									rlForTxtvExpProdPrice.width = (int)(0.2167 * Common.sessionDeviceWidth);
									txtvExpProdPrice.setLayoutParams(rlForTxtvExpProdPrice);
									txtvExpProdPrice.setTextSize((float) (0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));		
										
									ImageView imgvExpProdImage = (ImageView)child.findViewById(R.id.imgvExpProdImage);
									LinearLayout.LayoutParams rlForImgvExpProdImage = (LinearLayout.LayoutParams) imgvExpProdImage.getLayoutParams();
									rlForImgvExpProdImage.width = (int)(0.1 * Common.sessionDeviceWidth);
									rlForImgvExpProdImage.height = (int)(0.0615 * Common.sessionDeviceHeight);
									imgvExpProdImage.setLayoutParams(rlForImgvExpProdImage);
									
									RelativeLayout llForColoSize = (RelativeLayout)child.findViewById(R.id.llForColoSize);
									LinearLayout.LayoutParams rlForllForColoSize = (LinearLayout.LayoutParams) llForColoSize.getLayoutParams();
									rlForllForColoSize.leftMargin = (int)(0.01667 * Common.sessionDeviceWidth);
									rlForllForColoSize.topMargin = (int)(0.01513 * Common.sessionDeviceHeight);									
									llForColoSize.setLayoutParams(rlForllForColoSize);
									
									Button btnExpColorCode =(Button)child.findViewById(R.id.btnExpColorCode);
									RelativeLayout.LayoutParams rlForBtnExpColorCode = (RelativeLayout.LayoutParams) btnExpColorCode.getLayoutParams();
									rlForBtnExpColorCode.width = (int)(0.0416 * Common.sessionDeviceWidth);
									rlForBtnExpColorCode.height = (int)(0.0256 * Common.sessionDeviceHeight);									
									btnExpColorCode.setLayoutParams(rlForBtnExpColorCode);
									
									
									TextView txtvExpSizeLabel = (TextView)child.findViewById(R.id.txtvExpSizeLabel);
									RelativeLayout.LayoutParams rlForTxtvExpSizeLabel = (RelativeLayout.LayoutParams) txtvExpSizeLabel.getLayoutParams();
									rlForTxtvExpSizeLabel.topMargin = (int)(0.01513 * Common.sessionDeviceHeight);									
									txtvExpSizeLabel.setLayoutParams(rlForTxtvExpSizeLabel);
									txtvExpSizeLabel.setTextSize((float) (0.025 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
									
									TextView txtvExpSize = (TextView)child.findViewById(R.id.txtvExpSize);
									RelativeLayout.LayoutParams rlForTxtvExpSize = (RelativeLayout.LayoutParams) txtvExpSize.getLayoutParams();
									rlForTxtvExpSize.leftMargin = (int)(0.0134 * Common.sessionDeviceWidth);									
									txtvExpSize.setLayoutParams(rlForTxtvExpSize);
									txtvExpSize.setTextSize((float) (0.025 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
								
									
									LinearLayout llForProdActions = (LinearLayout)child.findViewById(R.id.llForProdActions);
									TableRow.LayoutParams rlForllForProdActions = (TableRow.LayoutParams) llForProdActions.getLayoutParams();
									rlForllForProdActions.rightMargin = (int)(0.0334 * Common.sessionDeviceWidth);
									rlForllForProdActions.topMargin = (int)(0.0102 * Common.sessionDeviceHeight);									
									llForProdActions.setLayoutParams(rlForllForProdActions);
									
									TextView imgvEditIcon = (TextView)child.findViewById(R.id.imgvEditIcon);
									LinearLayout.LayoutParams rlForImgvEditIcon = (LinearLayout.LayoutParams) imgvEditIcon.getLayoutParams();
									rlForImgvEditIcon.rightMargin = (int)(0.05 * Common.sessionDeviceWidth);									
									imgvEditIcon.setLayoutParams(rlForImgvEditIcon);
									imgvEditIcon.setTextSize((float) (0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
									
									TextView imgvRemoveIcon = (TextView)child.findViewById(R.id.imgvRemoveIcon);
									LinearLayout.LayoutParams rlForImgvRemoveIcon = (LinearLayout.LayoutParams) imgvRemoveIcon.getLayoutParams();
									rlForImgvRemoveIcon.height = (int)(0.031 * Common.sessionDeviceHeight);
									rlForImgvRemoveIcon.rightMargin = (int)(0.05 * Common.sessionDeviceWidth);	
									rlForImgvRemoveIcon.topMargin = (int)(0.0102 * Common.sessionDeviceHeight);
									rlForImgvRemoveIcon.bottomMargin = (int)(0.0102 * Common.sessionDeviceHeight);
									imgvRemoveIcon.setLayoutParams(rlForImgvRemoveIcon);
									imgvRemoveIcon.setTextSize((float) (0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
								
										
									aq3.id(R.id.txtvExpProductName).text(hashMapValuesForListViewNew.get("ProdName"));
									aq3.id(R.id.imgvExpProdImage).image(hashMapValuesForListViewNew.get("ProdImage"));
									if(!hashMapValuesForListViewNew.get("Size").equals("null")){
										aq3.id(R.id.txtvExpSize).visibility(View.VISIBLE);
										aq3.id(R.id.txtvExpSize).text(hashMapValuesForListViewNew.get("Size"));
									} else {
										
										aq3.id(R.id.txtvExpSizeLabel).visibility(View.GONE);	
										aq3.id(R.id.txtvExpSize).visibility(View.GONE);						
									}
									if(!hashMapValuesForListViewNew.get("Color").equals("null")){
										aq3.id(R.id.btnExpColorCode).visibility(View.VISIBLE);
										aq3.id(R.id.btnExpColorCode).backgroundColor(Color.parseColor(hashMapValuesForListViewNew.get("Color")));
									} else {
										aq3.id(R.id.btnExpColorCode).visibility(View.GONE);
									}
									aq3.id(R.id.txtvExpProdQuantityWithPrice).text("("+hashMapValuesForListViewNew.get("Quantity")+" X  $"+(Common.roundTwoDecimalsByStringCommon(hashMapValuesForListViewNew.get("ProdPrice")))+")");
									aq3.id(R.id.txtvExpProdPrice).text("$"+hashMapValuesForListViewNew.get("ProdPrice"));
									int convertQunatity = 1;
									if(hashMapValuesForListViewNew.get("Quantity").equals("null")){
										convertQunatity = 1;
									} else {
										convertQunatity  = Integer.parseInt(hashMapValuesForListViewNew.get("Quantity"));
									}
									double convertProdPrice = 0;
									if(hashMapValuesForListViewNew.get("Quantity").equals("null")){
										convertProdPrice = 1;
									} else {
										convertProdPrice  = Common.roundTwoDecimalsByStringCommon(hashMapValuesForListViewNew.get("ProdPrice"));
									}
									double totalPrice = (convertQunatity)*(convertProdPrice);
									aq3.id(R.id.txtvExpProdPrice).text("$"+Common.roundTwoDecimalsByStringCommon(""+totalPrice));

									imgvEditIcon.setOnClickListener(new OnClickListener() {
										@Override
										public void onClick(View v) {
											// TODO Auto-generated method stub
											ProductDetails.editAction = true;
											Intent intent = new Intent(OrderConfirmation.this, ProductDetails.class);																					
											if(shopFlag.equals("null")){
											//Bundle bundle = new Bundle();  
											//bundle.putParcelableArrayList("list", (ArrayList<? extends Parcelable>) arrListHashMapProdsWithClient2.get(arrIndex));
											//intent.putExtras(bundle);
											//intent.putExtra("list", arrListHashMapProdsWithClient2.get(arrIndex));
											intent.putExtra("list", hashMapValuesForListViewNew);
											intent.putExtra("pos", ""+arrIndex);											
											setResult(1, intent);											
											finish();
										    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
											}else{
												//String[] prodFinalArray = 
		    			        				ArrayList<String> prodResArrays = new ArrayList<String>();
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdImage").replaceAll(" ", "%20"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ClientId"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdId"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdName"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ClosetSeleStatus"));// closet selection status value
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdIsTryOn"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("BgColorCode"));
		    				    					if(!hashMapValuesForListViewNew.get("ClientLogo").equals("")){
		    				    						prodResArrays.add(hashMapValuesForListViewNew.get("ClientLogo"));
		    				    					} else{
		    				    						prodResArrays.add("");
		    				    					}
		    				    					prodResArrays.add(hashMapValuesForListViewNew.get("LightBgColorCode"));
		    				    					prodResArrays.add(hashMapValuesForListViewNew.get("DarkBgColorCode"));
		    			    					
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdButtonName"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ProdPrice"));
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("ClientName"));		    			    					
		    			    					prodResArrays.add("0");
		    			    					prodResArrays.add("null");
		    			    					prodResArrays.add("null");
		    			    					prodResArrays.add(hashMapValuesForListViewNew.get("isSeemoreCart"));
		    			    					String[] prodFinalArray = new String[prodResArrays.size()];
		    			    					prodFinalArray = prodResArrays.toArray(prodFinalArray);
		    			    					intent = new Intent(OrderConfirmation.this, ProductDetails.class);		    			    					
		    			    					intent.putExtra("prodStrArr",  prodFinalArray);
		    			    					intent.putExtra("editAction",  "true");
		    			    					intent.putExtra("quantity",  hashMapValuesForListViewNew.get("Quantity"));		    			    					
		    			    					intent.putExtra("orderId",  editOrderId);
		    			    					intent.putExtra("pos", ""+arrIndex);
		    			    					startActivityForResult(intent, 1);
		    			    					overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
											}
											
										}
									});
									imgvRemoveIcon.setOnClickListener(new OnClickListener() {
										@Override
										public void onClick(View v) {
											try{												
												LinearLayout listViewForProds = (LinearLayout) convertViewId.findViewById(R.id.listViewForProds);
												listViewForProds.removeViewAt(arrClientIndex);
												arrListHashMapProdsWithClient2.remove(arrIndex);
												arrClientProdList.remove(hashMapValues.get("ClientId"));
												arrListForProdCount.remove(hashMapValues.get("ClientId"));
												if(shopFlag.equals("null")){
													ProductDetails.arrPdInfoForCartList.remove(arrIndex);		
													Log.e("arrListHashMapForClientInfo2", arrIndex+" "+arrListHashMapForClientInfo2.size()+" "+arrListHashMapForClientInfo2);
													Log.e("ProductDetails.arrListHashMapForClientInfo", arrIndex+" "+ProductDetails.arrListHashMapForClientInfo.size()+" "+ProductDetails.arrListHashMapForClientInfo);
												}else{
													
													arrPdInfoForCartEditList.remove(arrIndex);
												}
												
												//if(!arrClientProdList.contains(hashMapValues.get("ClientId"))){
												if(!arrListForProdCount.contains(hashMapValues.get("ClientId"))){												
													int i=0;
													for(int n= arrListHashMapForClientInfo2.size() - 1; n>=0; n--){															 
														HashMap<String, String> hashMapValuesForClient = arrListHashMapForClientInfo2.get(n);
														//HashMap<String, String> hashMapValuesForClientInfo = ProductDetails.arrListHashMapForClientInfo.get(n);														
														if(hashMapValuesForClient.get("ClientId").equals(hashMapValues.get("ClientId"))){															
															arrListHashMapForClientInfo2.remove(n);
															if(shopFlag.equals("null")){
																ProductDetails.arrForClientIds.remove(hashMapValues.get("ClientId"));
																ProductDetails.arrListHashMapForClientInfo.remove(i);
															}else{
																arrListHashMapForEditClientInfo.remove(i);
																arrForClientIds.remove(hashMapValues.get("ClientId"));
															}
															
															}
														i++;
														/*if(hashMapValuesForClientInfo.get("ClientId").equals(hashMapValues.get("ClientId"))){
															ProductDetails.arrListHashMapForClientInfo.remove(n);
														}*/
													}
												}
												
												/*int i=0;
												for(int n= arrListHashMapForClientInfo2.size() - 1; n>=0; n--){															 
													HashMap<String, String> hashMapValuesForClient = arrListHashMapForClientInfo2.get(n);
													HashMap<String, String> hashMapValuesForClientInfo = ProductDetails.arrListHashMapForClientInfo.get(n);	
													HashMap<String, String> hashMapValuesForListNewView = arrListHashMapProdsWithClient2.get(i);
													if(!hashMapValuesForListNewView.containsKey(hashMapValues.get("ClientId"))){														
														if(hashMapValuesForClient.get("ClientId").equals(hashMapValues.get("ClientId"))){
															Log.e("hashMapValuesForClient.get(",""+hashMapValuesForClient.get("ClientId"));
															Log.e("n",""+hashMapValuesForClient.get("ClientId"));
															arrListHashMapForClientInfo2.remove(n);
															ProductDetails.arrForClientIds.remove(hashMapValues.get("ClientId"));
															ProductDetails.arrListHashMapForClientInfo.remove(i);
															break;
															}
														i++;*/
												
											
												if(arrListHashMapProdsWithClient2.size()==0){
													if(shopFlag.equals("null")){
														ProductDetails.arrPdInfoForCartList.clear();
														 offerDiscountValue= "null";
														 offerDiscountValuType = "null";
														 offerName ="null";
														 offerId ="null";
														 clientId ="null";
													}
													arrListHashMapForClientInfo2.clear();
													Intent intent = new Intent(OrderConfirmation.this, ProductDetails.class);
													setResult(1, intent);
													finish();
												    overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
												}
												
												clientWiseTotalPrice =0.00;
												countForFlagArrAdpNew =0;
												fancyCoverFlowForCart.setAdapter(renderCoverFlowForShoppingCart(arrListHashMapForClientInfo2, arrListHashMapProdsWithClient2));
												countForFlagArrAdp =0;
												
												
											} catch(Exception e){
												e.printStackTrace();
											}
										}
									});
									
									cartTotalPrice += Common.roundTwoDecimalsByStringCommon(aq3.id(R.id.txtvExpProdPrice).getText().toString().replace(""+priceSymbol, ""));
									cartTotalPriceValues += Common.roundTwoDecimalsByStringCommon(aq3.id(R.id.txtvExpProdPrice).getText().toString().replace(""+priceSymbol, ""));
									
									listViewForProds.addView(child);
									
	
									JSONObject jsonObjForAttribus = new JSONObject();
									if(!hashMapValuesForListViewNew.get("Size").equals("null")){
										jsonObjForAttribus.put("size", hashMapValuesForListViewNew.get("Size"));
									}
									if(!hashMapValuesForListViewNew.get("Color").equals("null")){
										jsonObjForAttribus.put("color", hashMapValuesForListViewNew.get("Color"));
									}
									jsonObjForClientsProducts = new JSONObject();
									jsonObjForClientsProducts.put("prodId", hashMapValuesForListViewNew.get("ProdId"));
									jsonObjForClientsProducts.put("prodName", hashMapValuesForListViewNew.get("ProdName"));
									jsonObjForClientsProducts.put("prodPrice", hashMapValuesForListViewNew.get("ProdPrice"));
									jsonObjForClientsProducts.put("prodQuantity", hashMapValuesForListViewNew.get("Quantity"));
									jsonObjForClientsProducts.put("attribs", jsonObjForAttribus);
									arrFinalClientsProducts.add(jsonObjForClientsProducts.toString().replace("\\", ""));
									jsonArrayForClientsProducts.put(jsonObjForClientsProducts);
									Log.e("jsonArrayForClientsProducts",""+jsonArrayForClientsProducts);
									l++;
								}
							}
							cartTotalPriceForAdd = "$"+cartTotalPrice; 
							aq2.id(R.id.txtvCartTotalPrice).text("$"+Common.roundTwoDecimalsByStringCommon(""+cartTotalPrice));
							String onlyPriceValForCartTotalPrice ="0.00";
							String onlyPriceValForShippingCostPrice ="0.00";
							
							Button btnUserOffers = (Button) convertView.findViewById(R.id.btnUserOffers);
							RelativeLayout.LayoutParams rlForBtnUserOffers= (RelativeLayout.LayoutParams) btnUserOffers.getLayoutParams();
							rlForBtnUserOffers.width = (int) (0.1667* Common.sessionDeviceWidth);
							rlForBtnUserOffers.height = (int) (0.0308* Common.sessionDeviceHeight);
							rlForBtnUserOffers.leftMargin = (int) (0.035* Common.sessionDeviceWidth);
							rlForBtnUserOffers.topMargin = (int) (0.01025* Common.sessionDeviceHeight);
							btnUserOffers.setLayoutParams(rlForBtnUserOffers);
							btnUserOffers.setTextSize((float)(0.03 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
							btnUserOffers.setOnClickListener(new OnClickListener() {
								@Override
								public void onClick(View v) {									
									try{
										Intent intent = new Intent(getApplicationContext(), DiscountOffer.class);
										intent.putExtra("clientId", ""+hashMapValues.get("ClientId"));
										startActivityForResult(intent, 1);									
									} catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | ChangeShippingMethod   |   " +e.getMessage();
										Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
									}
								}
							});
							
							
							
							if(!shippingMethodName.equals("null")){							
								
								aq2.id(R.id.txtvShippingMethodVal).text(shippingMethodName);
								aq2.id(R.id.txtvShippingDelivery).text(shippingMethodDetails);
								if(shippingMethodRateForTitle.equals("A")){									
									aq2.id(R.id.txtvShippingCostPrice).text("$"+Common.roundTwoDecimalsByStringCommon(shippingMethodRate));							
								} else if(shippingMethodRateForTitle.equals("P")){
									double shippingCostForMethod = cartTotalPrice * (Common.roundTwoDecimalsByStringCommon(shippingMethodRate)/100);									
									aq2.id(R.id.txtvShippingCostPrice).text("$"+shippingCostForMethod);							
								}
								onlyPriceValForShippingCostPrice = aq2.id(R.id.txtvShippingCostPrice).getText().toString().replace(""+priceSymbol, "");								
								onlyPriceValForCartTotalPrice = cartTotalPriceForAdd.replace(""+priceSymbol, "");
								
								
							spinner = (Spinner) convertView.findViewById(R.id.spinnerForShippingMethod);							
							if(shopFlag == "null"){								
								if(ProductDetails.jsonShippingMethodAttriButes.length()>0){									
									spinnerArray = new ArrayList<String>();
									spinnerShipMethodValsArray = new ArrayList<String>();
	    							for(int s=0; s<ProductDetails.jsonShippingMethodAttriButes.length(); s++){
	    								if(!ProductDetails.jsonShippingMethodAttriButes.optString(s).equals("")){
		    								JSONObject jsonShippingMethod = new JSONObject(ProductDetails.jsonShippingMethodAttriButes.getString(s));
		    								if(!jsonShippingMethod.optString("shipMethodName").equals("")){
		    									spinnerArray.add(jsonShippingMethod.getString("shipMethodName")+" ("+jsonShippingMethod.getString("shipMethodDetails")+")");
		    								}
		    								if(!jsonShippingMethod.optString("shipMethodRate").equals("")){
		    									JSONObject jsonShippingMethodRate = new JSONObject(jsonShippingMethod.getString("shipMethodRate"));		    									
		    									if(jsonShippingMethodRate.optString("A").equals("")){
		    										//spinnerShipMethodValsArray.clear();
		    										double shippingCostForMethod = cartTotalPrice * (Common.roundTwoDecimalsByStringCommon(jsonShippingMethodRate.getString("P"))/100);			    									
		    										spinnerShipMethodValsArray.add(""+Common.roundTwoDecimalsCommon(shippingCostForMethod));
		    									} else if(jsonShippingMethodRate.optString("P").equals("")){
		    										//spinnerShipMethodValsArray.clear();
		    										spinnerShipMethodValsArray.add(""+Common.roundTwoDecimalsCommon(Double.parseDouble(jsonShippingMethodRate.getString("A"))));
		    									}		    									
		    								}
	    								}
	    							}
								}
								spinnerArrayAdapter = new ArrayAdapter<String>(convertView.getContext(), android.R.layout.simple_spinner_dropdown_item, spinnerArray);
    						    spinnerArrayAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
								spinner.setAdapter(spinnerArrayAdapter);
	    						spinner.setSelection(0);
							}else{
								final View newConvertView = convertView;
								final float newcartTotalPrice = cartTotalPrice;
								String productUrl = Constants.Live_Url+"mobileapps/ios/public/stores/products/"+prodID;
								aq.ajax(productUrl, JSONObject.class, new AjaxCallback<JSONObject>(){			
									@Override
									public void callback(String url, JSONObject json, AjaxStatus status) {
										try{
											if(json!=null && !json.optString("shippingMethods").equals("")){
												JSONArray jsonShippingMethodAttriButes = new JSONArray(json.getString("shippingMethods"));
												if(jsonShippingMethodAttriButes.length()>0){													
													spinnerArray = new ArrayList<String>();
													spinnerShipMethodValsArray = new ArrayList<String>();
					    							for(int s=0; s<jsonShippingMethodAttriButes.length(); s++){
					    								if(!jsonShippingMethodAttriButes.optString(s).equals("")){
						    								JSONObject jsonShippingMethod = new JSONObject(jsonShippingMethodAttriButes.getString(s));
						    								if(!jsonShippingMethod.optString("shipMethodName").equals("")){
						    									spinnerArray.add(jsonShippingMethod.getString("shipMethodName")+" ("+jsonShippingMethod.getString("shipMethodDetails")+")");
						    								}
						    								if(!jsonShippingMethod.optString("shipMethodRate").equals("")){
						    									JSONObject jsonShippingMethodRate = new JSONObject(jsonShippingMethod.getString("shipMethodRate"));
						    									Log.e("jsonShippingMethodRate", ""+jsonShippingMethodRate);
						    									if(jsonShippingMethodRate.optString("A").equals("")){
						    										//spinnerShipMethodValsArray.clear();
						    										double shippingCostForMethod = newcartTotalPrice * (Common.roundTwoDecimalsByStringCommon(jsonShippingMethodRate.getString("P"))/100);
							    									//Log.e("shippingCostForMethod", cartTotalPrice+" "+shippingCostForMethod+" "+Common.roundTwoDecimalsCommon(shippingCostForMethod));
						    										spinnerShipMethodValsArray.add(""+Common.roundTwoDecimalsCommon(shippingCostForMethod));
						    									} else if(jsonShippingMethodRate.optString("P").equals("")){
						    										//spinnerShipMethodValsArray.clear();
						    										spinnerShipMethodValsArray.add(""+Common.roundTwoDecimalsCommon(Double.parseDouble(jsonShippingMethodRate.getString("A"))));
						    									}
						    									
						    								}
					    								}
					    							}
					    						
					    						    spinnerArrayAdapter = new ArrayAdapter<String>(newConvertView.getContext(), android.R.layout.simple_spinner_dropdown_item, spinnerArray);
					    						    spinnerArrayAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
													spinner.setAdapter(spinnerArrayAdapter);
						    						spinner.setSelection(0);
												}
											}
										}catch(Exception e){
											e.printStackTrace();
										}
									}
									});
								
							}
    							
    						   
							
							ImageView btnChangeShippingMethod = (ImageView) convertView.findViewById(R.id.btnChangeShippingMethod);
							RelativeLayout.LayoutParams rlForBtnChangeShippingMethod= (RelativeLayout.LayoutParams) btnChangeShippingMethod.getLayoutParams();
							rlForBtnChangeShippingMethod.width = (int) (0.0667* Common.sessionDeviceWidth);
							rlForBtnChangeShippingMethod.height = (int) (0.041* Common.sessionDeviceHeight);
							btnChangeShippingMethod.setLayoutParams(rlForBtnChangeShippingMethod);
							
							btnChangeShippingMethod.setOnClickListener(new OnClickListener() {
								@Override
								public void onClick(View v) {									
									try{
										spinner.performClick();										
									} catch(Exception e){
										e.printStackTrace();
										String errorMsg = className+" | ChangeShippingMethod   |   " +e.getMessage();
										Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
									}
								}
							});
							
							
							
							spinner.setOnItemSelectedListener(new OnItemSelectedListener() {
							    @Override
							    public void onItemSelected(AdapterView<?> arg0, View arg1,
							            int position, long arg3) {	
							    	try{
							    		double clientWiseTotalPricePrev = Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, ""));
							    		String s =" (";
										String q =")";
										String w ="";
										String strReplaceSymbol = spinner.getSelectedItem().toString().replace(s, "|").replace(q, w);										
										
										String[] expShippingSpinnerArray = strReplaceSymbol.split("\\|");										
						                
										aq2.id(R.id.txtvShippingMethodVal).text(expShippingSpinnerArray[0]);
										aq2.id(R.id.txtvShippingDelivery).text(expShippingSpinnerArray[1]);										
										aq2.id(R.id.txtvShippingCostPrice).text("$"+spinnerShipMethodValsArray.get(position));

										String onlyPriceValForShippingCostPrice = aq2.id(R.id.txtvShippingCostPrice).getText().toString().replace(""+priceSymbol, "");
										//String onlyPriceValForCartTotalPrice = cartTotalPriceForAdd.replace(""+priceSymbol, "");
										String onlyPriceValForCartTotalPrice = aq2.id(R.id.txtvCartTotalPrice).getText().toString().replace(""+priceSymbol, "");
										//Log.e(" if onlyPriceValForCartTotalPrice",""+onlyPriceValForCartTotalPrice);
										onlyPriceValForShippingCostPrice = onlyPriceValForShippingCostPrice.isEmpty() ? "0" : onlyPriceValForShippingCostPrice;
										onlyPriceValForCartTotalPrice = onlyPriceValForCartTotalPrice.isEmpty() ? "0" : onlyPriceValForCartTotalPrice;
										//Log.e(" if onlyPriceValForCartTotalPrice",""+onlyPriceValForCartTotalPrice);
										Double discountPrice =0.0;
										 double discountAmt= 0.0;
										 Log.e("offerDiscountValue if",offerDiscountValue);
										if(!offerDiscountValue.equals("null") && !offerDiscountValuType.equals("null") && clientId.equals(hashMapValues.get("ClientId"))){
												if(offerDiscountValuType.equals("A")){
													Log.e("offerDiscountValue spinner",offerDiscountValue);
													aq2.id(R.id.txtofferDiscountCost).text("$"+Common.roundTwoDecimalsByStringCommon(offerDiscountValue));													
													aq2.id(R.id.txtvofferVal).text("( $"+Common.roundTwoDecimalsByStringCommon(offerDiscountValue)+" off )");
													discountAmt = Double.parseDouble(offerDiscountValue);
													discountPrice = Double.parseDouble(onlyPriceValForCartTotalPrice) -discountAmt ;
												}else{
													Log.e("offerDiscountValue spinner",offerDiscountValue);
													//aq2.id(R.id.txtofferDiscountCost).text(offerDiscountValue+ "% Off");
													 discountAmt =  (Double.parseDouble(onlyPriceValForCartTotalPrice) *Double.parseDouble(offerDiscountValue))/100;
													Log.e("discountAmt spinner",""+discountAmt);
													aq2.id(R.id.txtofferDiscountCost).text("$"+Common.roundTwoDecimalsCommon(discountAmt));	
													aq2.id(R.id.txtvofferVal).text("( "+offerDiscountValue+"% off )");
													discountPrice = Double.parseDouble(onlyPriceValForCartTotalPrice) - discountAmt;									
												}
												txtvofferName.setVisibility(View.VISIBLE);
												txtvofferVal.setVisibility(View.VISIBLE);
												imgClose.setVisibility(View.VISIBLE);
												aq2.id(R.id.txtvofferName).text(""+offerName+"");
												//hashMapValuesNew = hashMapValues; 
												hashMapValuesNew.put("offer_id",offerId);
												hashMapValuesNew.put("offer_name",offerName);
												hashMapValuesNew.put("offer_discount",""+discountAmt);
												hashMapValuesNew.put("offer_discount_type",""+offerDiscountValuType);
												
												//arrPdInfoForCartList.set(listPos, hMapForPdInfo);
													int i=0;
													for(int n= arrListHashMapForClientInfo2.size() - 1; n>=0; n--){															 
														HashMap<String, String> hashMapValuesForClient = arrListHashMapForClientInfo2.get(n);
														//HashMap<String, String> hashMapValuesForClientInfo = ProductDetails.arrListHashMapForClientInfo.get(n);														
														if(hashMapValuesForClient.get("ClientId").equals(hashMapValues.get("ClientId"))){															
															arrListHashMapForClientInfo2.set(n, hashMapValuesNew);
															if(shopFlag.equals("null")){																
																ProductDetails.arrListHashMapForClientInfo.set(i, hashMapValuesNew);
															}else{
																arrListHashMapForEditClientInfo.set(i, hashMapValuesNew);																
															}															
															}
														i++;														
													}
												
										}else{
											discountPrice = Double.parseDouble(onlyPriceValForCartTotalPrice);
										}
										
										final double finalDiscountAmt = discountAmt;
										
										double finalPdsTotal = Common.roundTwoDecimalsByStringCommon(onlyPriceValForShippingCostPrice) + (discountPrice);
										//Log.e(" if finalPdsTotal",""+finalPdsTotal);
										aq2.id(R.id.txtvShippingSubTotalPrice).text(""+priceSymbol+Common.roundTwoDecimalsCommon(finalPdsTotal));
										double clientWiseTotalPriceNew = Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, ""));

										if(!txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, "").equals("0.00")){
											double OrderTotalPricePrev = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))- Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, "")) - clientWiseTotalPricePrev;
											
											double convertToDouble = Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getTag().toString());											
											double calculateSalesTaxVal = Common.roundTwoDecimalsCommon(finalPdsTotal * (convertToDouble/100));
											
											txtvSalesTaxPrice.setText(""+priceSymbol+calculateSalesTaxVal);
											double OrderTotalPrice = OrderTotalPricePrev + clientWiseTotalPriceNew + Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
											//txtvSubOrderTotalPrice.setText(""+OrderTotalPrice);
											double subTotalPrice = OrderTotalPricePrev + clientWiseTotalPriceNew;
											txtvSubOrderTotalPrice.setText(""+subTotalPrice);											
											txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPrice));
										}else{
											double OrderTotalPricePrev = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))- clientWiseTotalPricePrev;
											double OrderTotalPrice = OrderTotalPricePrev + clientWiseTotalPriceNew + Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
											double subTotalPrice = OrderTotalPricePrev + clientWiseTotalPriceNew;
											txtvSubOrderTotalPrice.setText(""+OrderTotalPrice);											
											txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPrice));
										}
										
										imgClose.setOnClickListener(new OnClickListener() {							
											@Override
											public void onClick(View v) {
												try{
													txtvofferVal.setText("");
													txtvofferName.setText("");
													txtvofferName.setVisibility(View.INVISIBLE);
													txtvofferVal.setVisibility(View.INVISIBLE);
													imgClose.setVisibility(View.INVISIBLE);
													aq2.id(R.id.txtofferDiscountCost).text("$0.0");
													aq2.id(R.id.txtvofferName).text("");													
													double clientWiseTotalPriceNew = Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
													double OrderTotalPricePrev = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
													double subTotalPrice = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
													txtvSubOrderTotalPrice.setText(""+subTotalPrice);											
													txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPricePrev));
													aq2.id(R.id.txtvShippingSubTotalPrice).text(""+priceSymbol+Common.roundTwoDecimalsCommon(clientWiseTotalPriceNew));
												}catch(Exception e){
													e.printStackTrace();
												}
												
											}
										});
										
							    	}catch(Exception e){
							    		e.printStackTrace();
							    	}
							    }
							    @Override
							    public void onNothingSelected(AdapterView<?> arg0) {                
							    }
							});
						}else{
							    onlyPriceValForShippingCostPrice ="0";
							    onlyPriceValForCartTotalPrice = ""+cartTotalPrice;
//								convertView.findViewById(R.id.txtvShippingMethodTitle).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingDelivery).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.btnChangeShippingMethod).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingCostTitle).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingCostPrice).setVisibility(View.INVISIBLE);
								
								/*convertView.findViewById(R.id.txtvShippingMethodVal).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingDelivery).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingCostPrice).setVisibility(View.INVISIBLE);
								convertView.findViewById(R.id.txtvShippingMethodTitle).setVisibility(View.INVISIBLE);		*/						
							}
							double discountAmt =0.0;
						
							Double discountPrice =0.0;
							Log.e("offerDiscountValue else",offerDiscountValue);
							if(!offerDiscountValue.equals("null") && !offerDiscountValuType.equals("null") && clientId.equals(hashMapValues.get("ClientId"))){
									if(offerDiscountValuType.equals("A")){
										Log.e("offerDiscountValue render",offerDiscountValue);
										discountAmt = Double.parseDouble(offerDiscountValue);
										aq2.id(R.id.txtofferDiscountCost).text("$"+Common.roundTwoDecimalsByStringCommon(offerDiscountValue));
										discountPrice = (double) ((int)cartTotalPrice - Double.parseDouble(offerDiscountValue));
										aq2.id(R.id.txtvofferVal).text("("+Common.roundTwoDecimalsByStringCommon(offerDiscountValue)+" off )");
									}else{
										Log.e("offerDiscountValue render",offerDiscountValue);
										//aq2.id(R.id.txtofferDiscountCost).text(offerDiscountValue+ "% Off");
										 discountAmt = (double) (cartTotalPrice * Integer.parseInt(offerDiscountValue)/100);
										Log.e("discountAmt render",""+discountAmt);
										aq2.id(R.id.txtofferDiscountCost).text("$"+Common.roundTwoDecimalsCommon(discountAmt));
										aq2.id(R.id.txtvofferVal).text("( "+offerDiscountValue+"% off )");
										discountPrice = (double) (cartTotalPrice - (((double)cartTotalPrice *(Integer.parseInt(offerDiscountValue)))/100));									
									}
									txtvofferName.setVisibility(View.VISIBLE);
									txtvofferVal.setVisibility(View.VISIBLE);
									imgClose.setVisibility(View.VISIBLE);
									aq2.id(R.id.txtvofferName).text(""+offerName+"");
									txtvofferName.setVisibility(View.VISIBLE);
									txtvofferVal.setVisibility(View.VISIBLE);
									imgClose.setVisibility(View.VISIBLE);
									//hashMapValuesNew = hashMapValues; 
									txtvofferName.setVisibility(View.VISIBLE);
									txtvofferVal.setVisibility(View.VISIBLE);
									imgClose.setVisibility(View.VISIBLE);
									//hashMapValuesNew = hashMapValues; 
									hashMapValuesNew.put("offer_id",offerId);
									hashMapValuesNew.put("offer_name",offerName);
									hashMapValuesNew.put("offer_discount",""+discountAmt);
									hashMapValuesNew.put("offer_discount_type",""+offerDiscountValuType);
									//arrPdInfoForCartList.set(listPos, hMapForPdInfo);
										int i=0;
										for(int n= arrListHashMapForClientInfo2.size() - 1; n>=0; n--){															 
											HashMap<String, String> hashMapValuesForClient = arrListHashMapForClientInfo2.get(n);
											//HashMap<String, String> hashMapValuesForClientInfo = ProductDetails.arrListHashMapForClientInfo.get(n);														
											if(hashMapValuesForClient.get("ClientId").equals(hashMapValues.get("ClientId"))){															
												arrListHashMapForClientInfo2.set(n, hashMapValuesNew);
												if(shopFlag.equals("null")){																
													ProductDetails.arrListHashMapForClientInfo.set(i, hashMapValuesNew);
												}else{
													arrListHashMapForEditClientInfo.set(i, hashMapValuesNew);																
												}															
												}
											i++;														
										}
							}else{
								discountPrice = (double) cartTotalPrice;
							}
							final double finalDiscountAmt = discountAmt;
							aq2.id(R.id.txtvCartTotalPrice).text("$"+Common.roundTwoDecimalsByStringCommon(""+cartTotalPrice));
							
							double finalPdsTotal = Common.roundTwoDecimalsByStringCommon(onlyPriceValForShippingCostPrice) + discountPrice;							
							aq2.id(R.id.txtvShippingSubTotalPrice).text(""+priceSymbol+Common.roundTwoDecimalsCommon(finalPdsTotal));
							
							imgClose.setOnClickListener(new OnClickListener() {							
								@Override
								public void onClick(View v) {
									try{
										txtvofferVal.setText("");
										txtvofferName.setText("");
										txtvofferName.setVisibility(View.INVISIBLE);
										txtvofferVal.setVisibility(View.INVISIBLE);
										imgClose.setVisibility(View.INVISIBLE);
										aq2.id(R.id.txtofferDiscountCost).text("$0.0");
										aq2.id(R.id.txtvofferName).text("");													
										double clientWiseTotalPriceNew = Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
										double OrderTotalPricePrev = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
										double subTotalPrice = Common.roundTwoDecimalsByStringCommon(txtvOrderTotalPrice.getText().toString().replace(""+priceSymbol, ""))+ finalDiscountAmt;
										txtvSubOrderTotalPrice.setText(""+subTotalPrice);											
										txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPricePrev));
										aq2.id(R.id.txtvShippingSubTotalPrice).text(""+priceSymbol+Common.roundTwoDecimalsCommon(clientWiseTotalPriceNew));
									}catch(Exception e){
										e.printStackTrace();
									}
									
								}
							});
							
							
							if(countForFlagArrAdpNew == position){
								clientWiseTotalPrice += Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, ""));							
								double OrderTotalPrice = clientWiseTotalPrice + Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));														
								txtvSubOrderTotalPrice.setText(""+clientWiseTotalPrice);
								txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPrice));
								jsonObjForClients = new JSONObject();
								jsonObjForClients.put("clientId", hashMapValues.get("ClientId"));
								jsonObjForClients.put("clientName", hashMapValues.get("ClientName"));
								jsonObjForClients.put("clientTotal", Common.roundTwoDecimalsByStringCommon(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, "")));
								jsonObjForClients.put("cartTotal", Common.roundTwoDecimalsCommon(cartTotalPrice));
								jsonObjForClients.put("shippingMethod", shippingMethodDetails);
								jsonObjForClients.put("shippingCost", onlyPriceValForShippingCostPrice);
								jsonObjForClients.put("offer_id",hashMapValuesNew.get("offer_id"));											
								jsonObjForClients.put("offer_discount",""+hashMapValuesNew.get("offer_discount"));
								jsonObjForClients.put("products", jsonArrayForClientsProducts);
								jsonArrayForClients.put(jsonObjForClients);
								arrFinalClientIds.add(jsonObjForClients.toString().replace("\\", ""));
								arrFinalClientsProducts.clear();
							}
							
							jsonArrayForClientsProducts = new JSONArray();
							
							
						
							}catch(Exception e){
								e.printStackTrace();
							}
							countForFlagArrAdpNew++;
							}
						//countForFlagArrAdpNew++;
						Log.e("jsonObjForClients rendre",""+jsonObjForClients);
					}
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderCoverFlowForShoppingCart getView  |   " +e.getMessage();
			       	Common.sendCrashWithAQuery(OrderConfirmation.this,errorMsg);
				}
				return convertView;					
			}

			
		};
		return aa;
	}
	
	public static ArrayList<String> shippingAddressArrListVals = new ArrayList<String>();
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		try{
			
			if(resultCode==2){
				if(data!=null){
					shippingAddressArrListVals = data.getStringArrayListExtra("arrListForShippingAddressValues");
					if(data.getStringExtra("editOrderId") != null){
						editOrderId = data.getStringExtra("editOrderId");
						shopFlag="Edit";
					}
					basedOnShippingAddressInfoGetStateTax(shippingAddressArrListVals, "");			
				}
			}
			
			if(requestCode==1){				 
				    if(resultCode == 3 ){				       
						clientWiseTotalPrice=0;
						txtvSalesTaxPrice.setText("$0.00");
						txtvSubOrderTotalPrice.setText("$0.00");
						txtvOrderTotalPrice.setText("$0.00");						
						editOrderId = data.getStringExtra("editOrderId");						
						shopFlag="Edit";						
						arrPdInfoForCartEditList = ProductDetails.arrPdInfoForCartList;						
						countForFlagArrAdp =0;
						countForFlagArrAdpNew=0;
						
						arrListHashMapProdsWithClient = new ArrayList<HashMap<String,String>>();
						if(arrPdInfoForCartEditList.size()>0){
							for(int i=0; i<arrPdInfoForCartEditList.size(); i++){
								hashMapProdsWithClient = new HashMap<String, String>();
								hashMapProdsWithClient.put(arrPdInfoForCartEditList.get(i).get("ClientId"), arrPdInfoForCartEditList.get(i).toString());
								arrListHashMapProdsWithClient.add(hashMapProdsWithClient);
								arrListForProdCount.add(arrPdInfoForCartEditList.get(i).get("ClientId"));
								/*ArrayList<String> arrObjForShippingAddress = getIntent().getStringArrayListExtra("arrObjForShippingAddress");
								//shippingAddressInfoShowingOnLayout(arrObjForShippingAddress, json.getString("statetax"), "");
								Log.e("arrObjForShippingAddress",""+arrObjForShippingAddress);
								basedOnShippingAddressInfoGetStateTax(arrObjForShippingAddress, "");*/
								shippingAddressArrListVals =arrObjForShippingAddress;
								basedOnShippingAddressInfoGetStateTax(arrObjForShippingAddress, "");
							}
							
							fancyCoverFlowForShoppingCartInfo(arrListHashMapForEditClientInfo, arrListHashMapProdsWithClient);
						}

				    }
			}
			
			
			if(resultCode == 4){
				if(data.getStringExtra("offerDiscountValue") != null){
					clientWiseTotalPrice=0;
					txtvSalesTaxPrice.setText("$0.00");
					txtvSubOrderTotalPrice.setText("$0.00");
					txtvOrderTotalPrice.setText("$0.00");	
					countForFlagArrAdp =0;
					countForFlagArrAdpNew=0;
					offerDiscountValue = data.getStringExtra("offerDiscountValue");
					offerDiscountValuType = data.getStringExtra("offerDiscountValuType");	
					offerName =  data.getStringExtra("offerName");	
				    clientId = data.getStringExtra("clientId");
				    offerId = data.getStringExtra("offerId");
				    jsonArrayForClients =  new JSONArray();
					Log.e("offerDiscountValue",offerDiscountValue);
					Log.e("offerDiscountValuType",offerDiscountValuType);
					
					fancyCoverFlowForShoppingCartInfo(ProductDetails.arrListHashMapForClientInfo, arrListHashMapProdsWithClient);
					if(shippingAddressArrListVals.size()>0){
						if(shopFlag.equals("null")){
							basedOnShippingAddressInfoGetStateTax(shippingAddressArrListVals, "");
						}
					}
				}
									
			}
			
			shippingButtonText();
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onActivityResult   |   " +e.getMessage();
			Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
		}
	}

	private void basedOnShippingAddressInfoGetStateTax(final ArrayList<String> shippingAddressArrListVals2, final String strFlagForTax) {		
		try{
			JSONObject jsonObj = new JSONObject();
			jsonObj.put("state", shippingAddressArrListVals2.get(4).toString());
		    jsonObj.put("country", shippingAddressArrListVals2.get(6).toString());
		    jsonObj.put("zip", shippingAddressArrListVals2.get(5).toString());    
			Map<String, Object> params = new HashMap<String, Object>();
		    params.put("json", jsonObj.toString());		
		    if(shopFlag.equals("null"))
		    	params.put("state", shippingAddressArrListVals.get(4).toString());    
		    else
		    	params.put("state", shippingAddressArrListVals2.get(4).toString());
			String stateTaxUrl = Constants.Live_Url+"mobileapps/ios/public/stores/statetax/";
			aq.ajax(stateTaxUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
				@Override
				public void callback(String url, JSONObject json, AjaxStatus status) {
					try{						
						if(json!=null){
							shippingAddressInfoShowingOnLayout(shippingAddressArrListVals2, json.getString("statetax"), strFlagForTax);
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | basedOnShippingAddressInfoGetStateTax aq.ajax callback   |   " +e.getMessage();
						Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
					}
				}
			});	
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | basedOnShippingAddressInfoGetStateTax   |   " +e.getMessage();
			Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
		}
	}

	public static int userShipIdForEdit = 0;
	JSONObject jsonObjForShippingAddress;
	ArrayList<String> arrFinalShippingAddress;
	protected void shippingAddressInfoShowingOnLayout(
			ArrayList<String> shippingAddressArrListVals2, String stateTaxByState, String strFlagForTax) {
		// TODO Auto-generated method stub
		try{
			arrFinalShippingAddress = new ArrayList<String>();
			double txtvSubOrderTotalPriceVal = Common.roundTwoDecimalsByStringCommon(txtvSubOrderTotalPrice.getText().toString().replace(""+priceSymbol, "")) - Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));			
			double convertToDouble = Common.roundTwoDecimalsByStringCommon(stateTaxByState);			
			double calculateSalesTaxVal = Common.roundTwoDecimalsByStringCommon(txtvSubOrderTotalPrice.getText().toString()) * (convertToDouble/100);
			
			
			txtvSalesTaxPrice.setText(""+priceSymbol+Common.roundTwoDecimalsByStringCommon(""+calculateSalesTaxVal));
			txtvSalesTaxPrice.setTag(Double.parseDouble(stateTaxByState));
			
			TextView txtvShippingSubTotalPrice = (TextView) findViewById(R.id.txtvShippingSubTotalPrice);
			//Log.e("shipping total", ""+txtvShippingSubTotalPrice.getText().toString()+" "+txtvOrderTotalPrice.getText().toString());
			//double OrderTotalPrice = Common.roundTwoDecimalsByStringCommon(txtvShippingSubTotalPrice.getText().toString().replace(""+priceSymbol, "")) + Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
			double OrderTotalPrice =  Common.roundTwoDecimalsByStringCommon(txtvSubOrderTotalPrice.getText().toString()) + Common.roundTwoDecimalsByStringCommon(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
			
			
			txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPrice));
			
			
			String address1 = (shippingAddressArrListVals2.get(1).isEmpty() ? "" : shippingAddressArrListVals2.get(1));
			String address2 = (shippingAddressArrListVals2.get(2).isEmpty() ? "" : ""+shippingAddressArrListVals2.get(2));
			String city = (shippingAddressArrListVals2.get(3).isEmpty() ? "" : shippingAddressArrListVals2.get(3));
			String state = (shippingAddressArrListVals2.get(4).isEmpty() ? "" : ""+shippingAddressArrListVals2.get(4));
			String zipcode = (shippingAddressArrListVals2.get(5).isEmpty() ? "" : ""+shippingAddressArrListVals2.get(5));
			String country = (shippingAddressArrListVals2.get(6).isEmpty() ? "" : ""+shippingAddressArrListVals2.get(6));
			
			
			String address = address2.equals("") ? address1 :address1+"\n"+address2;
			
			txtvShippingAddress1.setText(Common.sessionIdForUserLoggedFname +" "+Common.sessionIdForUserLoggedLname+"\n"+address);
			txtvShippingAddress2.setText(city+", "+state+" "+zipcode+"\n"+country);

			
			if(Integer.parseInt(shippingAddressArrListVals2.get(0))!=0){
				userShipIdForEdit = Integer.parseInt(shippingAddressArrListVals2.get(0));
			}
			
			
			jsonObjForShippingAddress = new JSONObject();
			jsonObjForShippingAddress.put("userShipId", userShipIdForEdit);
			jsonObjForShippingAddress.put("addr1", address1);
			jsonObjForShippingAddress.put("addr2", address2);
			jsonObjForShippingAddress.put("city", city);
			jsonObjForShippingAddress.put("state", state);
			jsonObjForShippingAddress.put("zip", zipcode);
			jsonObjForShippingAddress.put("country", country);
			arrFinalShippingAddress.add(jsonObjForShippingAddress.toString());
			
			
			JSONObject jsonObjForAddAddress = new JSONObject();
			jsonObjForAddAddress.put("userId", ""+Common.sessionIdForUserLoggedIn);
			jsonObjForAddAddress.put("userShipId", userShipIdForEdit);
			jsonObjForAddAddress.put("shippingAddress", jsonObjForShippingAddress);
			Map<String, Object> params = new HashMap<String, Object>();
		    params.put("json", jsonObjForAddAddress.toString());			
		    params.put("userid", Common.sessionIdForUserLoggedIn);    
	
		  
		    				
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | shippingAddressInfoShowingLayout   |   " +e.getMessage();
			Common.sendCrashWithAQuery(OrderConfirmation.this, errorMsg);
		}
	}
	
}