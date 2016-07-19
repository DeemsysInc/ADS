package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
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
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.seemoreinteractive.seemoreinteractive.Model.OrderModel;
import com.seemoreinteractive.seemoreinteractive.Model.UserOrder;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

@TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
public class SaveOrderInformation extends Activity {

	
	public static Activity saveOrder;
	AQuery aq;
	String className = this.getClass().getSimpleName();
	FileTransaction file;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_save_order_information);
		aq = new AQuery(this);
		
		try{
			saveOrder = this;
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "My Orders", "");	

			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);			
			
			 file = new FileTransaction();
			
	    	
	    	TextView txtvShop = (TextView) findViewById(R.id.txtvShop);      
	    	txtvShop.setText("");
	    	
	    	ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);      
	    	imgvBtnShare.setImageAlpha(0);
	    	
	    	
	    	ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);
			imgvBtnCloset.setImageAlpha(0);
			
			
	    	ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{		
					     finish();
					     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);	
					} catch (Exception e) {
						e.printStackTrace();
						//Toast.makeText(getApplicationContext(), "Error: SeeMore Login imgvBtnBack.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | Oncreate imgvBtnBack click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(SaveOrderInformation.this,errorMsg);
					}
				}
			});
	    	
			ImageView imgFooterMiddle = (ImageView) findViewById(R.id.imgvFooterMiddle);
			imgFooterMiddle.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
						Intent intent = new Intent(getApplicationContext(), MenuOptions.class);
						int requestCode = 0;
						startActivityForResult(intent, requestCode);
						//hideInstruction(view);
						overridePendingTransition(R.anim.slide_in_from_bottom, R.anim.slide_out_to_bottom);
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | Oncreate imgFooterMiddle click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(SaveOrderInformation.this,errorMsg);
					}
					
				}
			});
		//	displaySavingMyOrdersInfo();
			displaySavingMyOrdersInfoFile();
			
		} catch(Exception e)
		{
			e.printStackTrace();
			String errorMsg = className+" | onCreate     |   " +e.getMessage();
			Common.sendCrashWithAQuery(SaveOrderInformation.this,errorMsg);
		} 
	}
	 ListView listViewForSaveOrder;
	ArrayList<String> arrListForSavingMyOrders = new ArrayList<String>();
	String[] strArrSavingMyOrder = null;
	private void displaySavingMyOrdersInfo() {
		try{
			
			JSONObject jsonObjFinal = new JSONObject();
			jsonObjFinal.put("userId", Common.sessionIdForUserLoggedIn);
			Map<String, Object> params = new HashMap<String, Object>();
			params.put("json", jsonObjFinal);			
		    params.put("userid", Common.sessionIdForUserLoggedIn); 
			String orderUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/saved";
			//Log.e("param",""+params);
			aq.ajax(orderUrl, params, JSONArray.class, new AjaxCallback<JSONArray>(){			
				@Override
				public void callback(String url, JSONArray json, AjaxStatus status) {
					try{
						
						if(json!=null){
							if(json.length() > 0){
								strArrSavingMyOrder = new String[json.length()];							
								  for(int i=0;i<json.length();i++)
								  {
									    JSONObject json_obj = json.getJSONObject(i);											
										
										StringBuilder strBuilder = new StringBuilder();
										strBuilder.append(json_obj.getString("user_order_id"));
										strBuilder.append("|");
										strBuilder.append(json_obj.getString("user_order_total"));
										strBuilder.append("|");
										strBuilder.append(json_obj.getString("user_order_created_date"));
										
										arrListForSavingMyOrders.add(strBuilder.toString());
										strArrSavingMyOrder[i] = arrListForSavingMyOrders.toString();
								  }
								  
								    listViewForSaveOrder = (ListView) findViewById(R.id.listViewForSaveOrder);
									listViewForSaveOrder.setAdapter(renderForSavingMyOrdersListView(arrListForSavingMyOrders));
									listViewForSaveOrder.setOnItemClickListener(new OnItemClickListener() {
										@Override
										public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
												long arg3) {											
											Intent intent = new Intent(SaveOrderInformation.this, OrderConfirmation.class);
											intent.putExtra("shopFlag", "Edit");
											startActivity(intent);
										    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);				
										}
									});
						}
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnCheckoutConfirm order update callback  |   " +e.getMessage();
						Common.sendCrashWithAQuery(SaveOrderInformation.this, errorMsg);
					}
				}
			});	
		
			
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | displaySavingMyOrdersInfo method  |   " +e.getMessage();
			Common.sendCrashWithAQuery(SaveOrderInformation.this, errorMsg);
		}
		
	}
	
	private void displaySavingMyOrdersInfoFile() {
		try{
			OrderModel orderModel= file.getOrder();			
			List<UserOrder> userOrderList=orderModel.getAllMyOffersByID();
			int i=0;
			if(userOrderList.size() > 0){				
				strArrSavingMyOrder = new String[userOrderList.size()];
				for (final UserOrder userOrder : userOrderList) {
					
					StringBuilder strBuilder = new StringBuilder();
					strBuilder.append(userOrder.getId());
					strBuilder.append("|");
					strBuilder.append(userOrder.getOrderTotal());
					strBuilder.append("|");
					strBuilder.append(userOrder.getCreatedDate());
					
					
					arrListForSavingMyOrders.add(strBuilder.toString());
					strArrSavingMyOrder[i] = arrListForSavingMyOrders.toString();
					i++;
				}
								  
			    listViewForSaveOrder = (ListView) findViewById(R.id.listViewForSaveOrder);
				listViewForSaveOrder.setAdapter(renderForSavingMyOrdersListView(arrListForSavingMyOrders));
				
						
			}
		
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | displaySavingMyOrdersInfo method  |   " +e.getMessage();
			Common.sendCrashWithAQuery(SaveOrderInformation.this, errorMsg);
		}
		
	}
	int listviewLayout = 0;    
	 ArrayAdapter<String> aa;
	 JSONObject jsonObjForShippingAddress;
	
	private ArrayAdapter<String> renderForSavingMyOrdersListView(final ArrayList<String> arrListForSavingMyOrders2){	    	
	    AQUtility.debug("render setup");
	    listviewLayout = R.layout.listview_order_saving_my_orders;	
		aa = new ArrayAdapter<String>(this, listviewLayout, arrListForSavingMyOrders2){				
			@Override
			public View getView(final int position, View convertView, ViewGroup parent) {
				try {
					if(convertView == null){
						convertView = aq.inflate(convertView, listviewLayout, parent);
					}	
					final View indexView = convertView;
					AQuery aq2 = new AQuery(convertView);
					
					String[] expSavedInfoArray = arrListForSavingMyOrders2.get(position).split("\\|");
					/*for(int s1=0; s1<expSavedInfoArray.length; s1++){
						Log.e("expSavedInfoArray", ""+expSavedInfoArray[s1].trim());
					}*/
					
					
					TextView txtOrderIdTitle = (TextView)convertView.findViewById(R.id.txtOrderIdTitle);
					txtOrderIdTitle.setTextSize((float) (0.0467 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					
					TextView txtvOrderIdVal = (TextView)convertView.findViewById(R.id.txtvOrderIdVal);
					txtvOrderIdVal.setTextSize((float) (0.0467 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					
					TextView txtOrderTotalTitle = (TextView)convertView.findViewById(R.id.txtOrderTotalTitle);
					txtOrderTotalTitle.setTextSize((float) (0.0467 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));

					TextView txtvOrderTotalPrice = (TextView)convertView.findViewById(R.id.txtvOrderTotalPrice) ;
					txtvOrderTotalPrice.setTextSize((float) (0.0467 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					
					TextView txtvOrderDateVal = (TextView)convertView.findViewById(R.id.txtvOrderDateVal) ;
					txtvOrderDateVal.setTextSize((float) (0.0334* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					
					
					TextView txtOrderDateTitle = (TextView)convertView.findViewById(R.id.txtOrderDateTitle) ;
					txtOrderDateTitle.setTextSize((float) (0.0334* Common.sessionDeviceWidth/Common.sessionDeviceDensity));
					
					
					
					final String orderId = expSavedInfoArray[0].trim();
					aq2.id(R.id.txtvOrderIdVal).text(expSavedInfoArray[0].trim());
					aq2.id(R.id.txtvOrderTotalPrice).text("$"+expSavedInfoArray[1].trim());
					aq2.id(R.id.txtvOrderDateVal).text(expSavedInfoArray[2].trim());
					OrderModel orderModel= file.getOrder();
					
					final UserOrder userOrder = orderModel.getUserOrder(Integer.parseInt(expSavedInfoArray[0].trim()));											
					final ArrayList<HashMap<String, String>> arrPdInfoForCartList	      = userOrder.getCartInfoList();
					final  ArrayList<HashMap<String, String>> arrListHashMapForClientInfo = userOrder.getCartClientInfoList();
					final ArrayList<String>  arrForClientIds  = userOrder.getClientIds();
					final ArrayList<String> arrObjForShippingAddress = new ArrayList<String>();					     
				    arrObjForShippingAddress.add(userOrder.getUserShipId());
				    arrObjForShippingAddress.add(userOrder.getAddress1());
				    arrObjForShippingAddress.add(userOrder.getAddress2());
				    arrObjForShippingAddress.add(userOrder.getCity());
				    arrObjForShippingAddress.add(userOrder.getState());
				    arrObjForShippingAddress.add(userOrder.getZip());
				    arrObjForShippingAddress.add(userOrder.getCountry());
					
					RelativeLayout mainRlForMySavedOrder = (RelativeLayout)convertView.findViewById(R.id.mainRlForMySavedOrder);
					mainRlForMySavedOrder.setOnClickListener(new OnClickListener() {
						
						@Override
						public void onClick(View v) {
							// TODO Auto-generated method stub
							Log.e("arrListHashMapForClientInfo",""+arrListHashMapForClientInfo);
							Intent intent = new Intent(SaveOrderInformation.this, OrderConfirmation.class);							
							Bundle bundle = new Bundle();  
							bundle.putSerializable("arrPdInfoForCartList", arrPdInfoForCartList);
							bundle.putSerializable("arrListHashMapForClientInfo", arrListHashMapForClientInfo);						
							bundle.putSerializable("arrForClientIds", arrForClientIds);
							intent.putExtras(bundle);
							intent.putExtra("shopFlag", "Edit");
							intent.putExtra("editOrderId", ""+userOrder.getId());							
							intent.putStringArrayListExtra("arrObjForShippingAddress", arrObjForShippingAddress);
							startActivity(intent);
						    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
						}
					});
					ImageView imgvRightForMSO = (ImageView) convertView.findViewById(R.id.imgvRightForMSO);
					RelativeLayout.LayoutParams rlpForImgvRightForMSO = (RelativeLayout.LayoutParams) imgvRightForMSO.getLayoutParams();
					rlpForImgvRightForMSO.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
					rlpForImgvRightForMSO.width = (int) (0.0834 * Common.sessionDeviceWidth);
					rlpForImgvRightForMSO.height = (int) (0.0513 * Common.sessionDeviceHeight);
					imgvRightForMSO.setLayoutParams(rlpForImgvRightForMSO);
					imgvRightForMSO.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {							
							Intent intent = new Intent(SaveOrderInformation.this, OrderConfirmation.class);							
							Bundle bundle = new Bundle();  
							bundle.putSerializable("arrPdInfoForCartList", arrPdInfoForCartList);
							bundle.putSerializable("arrListHashMapForClientInfo", arrListHashMapForClientInfo);						
							intent.putExtras(bundle);
							intent.putExtra("shopFlag", "Edit");
							intent.putExtra("editOrderId", ""+userOrder.getId());							
							intent.putStringArrayListExtra("arrObjForShippingAddress", arrObjForShippingAddress);
							startActivity(intent);
						    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);
						}
					});
					ImageView imgvCloseForMSO = (ImageView) convertView.findViewById(R.id.imgvCloseForMSO);
					RelativeLayout.LayoutParams rlpForImgvCloseForMSO = (RelativeLayout.LayoutParams) imgvCloseForMSO.getLayoutParams();
					rlpForImgvCloseForMSO.addRule(RelativeLayout.CENTER_HORIZONTAL, RelativeLayout.TRUE);
					rlpForImgvCloseForMSO.width = (int) (0.0834 * Common.sessionDeviceWidth);
					rlpForImgvCloseForMSO.height = (int) (0.0513 * Common.sessionDeviceHeight);
					imgvCloseForMSO.setLayoutParams(rlpForImgvCloseForMSO);
					imgvCloseForMSO.setOnClickListener(new OnClickListener() {
						@Override
						public void onClick(View v) {
							 AlertDialog.Builder alertDialog = new AlertDialog.Builder(SaveOrderInformation.this);							 
						     alertDialog.setTitle("Confirm Delete...");
						     alertDialog.setMessage("Are you sure you want delete this?");
						     alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
						            @Override
									public void onClick(DialogInterface dialog,int which) {
										HashMap<String, String> params = new HashMap<String, String>();
									    params.put("orderId", orderId);
									    
									    JSONObject jsonObject1 = new JSONObject(params);		
										Map<String, String> jsonParams = new HashMap<String, String>();
										jsonParams.put("json", jsonObject1.toString());
										jsonParams.put("userid", ""+Common.sessionIdForUserLoggedIn);
										
										String shippingAddressUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/saved/delete";
										aq.ajax(shippingAddressUrl, jsonParams, JSONObject.class, new AjaxCallback<JSONObject>(){			
											@Override
											public void callback(String url, JSONObject json, AjaxStatus status) {
												try{
													if(json!=null){
														FileTransaction file = new FileTransaction();
								    			        OrderModel order = file.getOrder();
								    			        order.remove(Integer.parseInt(orderId));
								    			        order.updateOrder(order.getUserOrderList());
											    		file.setOrder(order);
														arrListForSavingMyOrders.remove(position);
														listViewForSaveOrder.removeViewInLayout(indexView);															
														((BaseAdapter) listViewForSaveOrder.getAdapter()).notifyDataSetChanged();
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
						            dialog.cancel();
						            }
						        });						 
						        alertDialog.show();
							
						}
					});
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForSavingMyOrdersListView  getview   |   " +e.getMessage();
					Common.sendCrashWithAQuery(SaveOrderInformation.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		return aa;
	}	
}
