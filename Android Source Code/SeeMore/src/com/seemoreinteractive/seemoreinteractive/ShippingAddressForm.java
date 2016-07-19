package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ShippingAddressForm extends Activity {
	String className = this.getClass().getSimpleName();
	AQuery aq;
	public static ArrayList<String> arrListForShippingAddressValues = new ArrayList<String>();
	String userShipId = "0";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_shipping_address_form);		
		try{
			aq = new AQuery(ShippingAddressForm.this);
		/*	Button btnShippingAddressClose = (Button) findViewById(R.id.btnShippingAddressClose);
			btnShippingAddressClose.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					// TODO Auto-generated method stub
					try{
						finish();
					} catch(Exception e){
						e.printStackTrace();
				    	String errorMsg = className+" | btnShippingAddressClose onclick   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShippingAddressForm.this, errorMsg);
					}					
				}
			});*/
			
			RelativeLayout container = (RelativeLayout) findViewById(R.id.container);
			RelativeLayout.LayoutParams rlForContainer = (RelativeLayout.LayoutParams) container.getLayoutParams();
			rlForContainer.height = (int)(0.614 * Common.sessionDeviceHeight);
			container.setLayoutParams(rlForContainer);
		
			TableLayout bounceTableLayout1 = (TableLayout) findViewById(R.id.bounceTableLayout1);
			RelativeLayout.LayoutParams rlForbounceTableLayout1 = (RelativeLayout.LayoutParams) bounceTableLayout1.getLayoutParams();
			rlForbounceTableLayout1.width = (int)(0.8334 * Common.sessionDeviceWidth);
			rlForbounceTableLayout1.height = (int)(0.3587 * Common.sessionDeviceHeight);
			rlForbounceTableLayout1.topMargin =(int)(0.0338 * Common.sessionDeviceHeight);
			bounceTableLayout1.setLayoutParams(rlForbounceTableLayout1);
		
			
			
			
			TextView btnShippingCancel = (TextView) findViewById(R.id.btnShippingCancel);
			RelativeLayout.LayoutParams rlForBtnShippingCancel = (RelativeLayout.LayoutParams) btnShippingCancel.getLayoutParams();
			rlForBtnShippingCancel.width = (int)(0.167 * Common.sessionDeviceWidth);
			rlForBtnShippingCancel.height = (int)(0.102 * Common.sessionDeviceHeight);
			rlForBtnShippingCancel.rightMargin = (int)(0.155 * Common.sessionDeviceWidth);
			btnShippingCancel.setLayoutParams(rlForBtnShippingCancel);
			btnShippingCancel.setTextSize((float) (0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
		
			TextView txtLblAddress1 = (TextView) findViewById(R.id.txtLblAddress1);
			txtLblAddress1.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			//final EditText etxtName = (EditText) findViewById(R.id.etxtName);
		//	final EditText etxtEmailId = (EditText) findViewById(R.id.etxtEmailId);
			//final EditText etxtPhNum = (EditText) findViewById(R.id.etxtPhNum);
			final EditText etxtAddress1 = (EditText) findViewById(R.id.etxtAddress1);
			TableRow.LayoutParams rlForEtxtAddress1 = (TableRow.LayoutParams) etxtAddress1.getLayoutParams();
			rlForEtxtAddress1.width = (int)(0.5 * Common.sessionDeviceWidth);	
			etxtAddress1.setLayoutParams(rlForEtxtAddress1);
			
			
			TextView txtLblAddress2 = (TextView) findViewById(R.id.txtLblAddress2);
			txtLblAddress2.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			final EditText etxtAddress2 = (EditText) findViewById(R.id.etxtAddress2);
			TableRow.LayoutParams rlForEtxtAddress2 = (TableRow.LayoutParams) etxtAddress2.getLayoutParams();
			rlForEtxtAddress2.width = (int)(0.5 * Common.sessionDeviceWidth);	
			etxtAddress2.setLayoutParams(rlForEtxtAddress2);
			
			
			TextView txtLblCity = (TextView) findViewById(R.id.txtLblCity);
			txtLblCity.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			final EditText etxtCity = (EditText) findViewById(R.id.etxtCity);
			TableRow.LayoutParams rlForEtxtCity = (TableRow.LayoutParams) etxtCity.getLayoutParams();
			rlForEtxtCity.width = (int)(0.5 * Common.sessionDeviceWidth);	
			etxtCity.setLayoutParams(rlForEtxtCity);
			
			TextView txtLblState = (TextView) findViewById(R.id.txtLblState);
			txtLblState.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			final Spinner spDdlState = (Spinner) findViewById(R.id.spDdlState);
			TableRow.LayoutParams rlForSpDdlState = (TableRow.LayoutParams) spDdlState.getLayoutParams();
			rlForSpDdlState.width = (int)(0.5 * Common.sessionDeviceWidth);	
			spDdlState.setLayoutParams(rlForSpDdlState);
						
			TextView txtLblZipCode = (TextView) findViewById(R.id.txtLblZipCode);
			txtLblZipCode.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			
			final EditText etxtZipCode = (EditText) findViewById(R.id.etxtZipCode);
			TableRow.LayoutParams rlForEtxtZipCode = (TableRow.LayoutParams) etxtZipCode.getLayoutParams();
			rlForEtxtZipCode.width = (int)(0.5 * Common.sessionDeviceWidth);	
			etxtZipCode.setLayoutParams(rlForEtxtZipCode);
			

			TextView txtLblCountry = (TextView) findViewById(R.id.txtLblCountry);
			txtLblCountry.setTextSize((float) (0.0416 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			final Spinner spDddlCountry = (Spinner) findViewById(R.id.spDddlCountry);
			TableRow.LayoutParams rlForSpDddlCountry = (TableRow.LayoutParams) spDddlCountry.getLayoutParams();
			rlForSpDddlCountry.width = (int)(0.5 * Common.sessionDeviceWidth);	
			spDddlCountry.setLayoutParams(rlForSpDddlCountry);

			
			
			
			final TextView btnShippingSubmit = (TextView) findViewById(R.id.btnShippingSubmit);
			RelativeLayout.LayoutParams rlForBtnShippingSubmit = (RelativeLayout.LayoutParams) btnShippingSubmit.getLayoutParams();
			rlForBtnShippingSubmit.width = (int)(0.167 * Common.sessionDeviceWidth);
			rlForBtnShippingSubmit.height = (int)(0.102 * Common.sessionDeviceHeight);
			rlForBtnShippingSubmit.topMargin =(int)(0.054 * Common.sessionDeviceHeight);
			rlForBtnShippingSubmit.rightMargin = (int)(0.153 * Common.sessionDeviceWidth);
			btnShippingSubmit.setLayoutParams(rlForBtnShippingSubmit);
			btnShippingSubmit.setTextSize((float) (0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			if(getIntent().getExtras()!=null){
				userShipId = getIntent().getStringExtra("userShipId");
				arrListForShippingAddressValues = getIntent().getStringArrayListExtra("arrShippingList");
				Log.e("userShipId", ""+userShipId);
			}
			
			
			btnShippingCancel.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					// TODO Auto-generated method stub
					try{
						finish();
					} catch(Exception e){
						e.printStackTrace();
				    	String errorMsg = className+" | btnShippingCancel onclick   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShippingAddressForm.this, errorMsg);
					}					
				}
			});
			
			if(arrListForShippingAddressValues != null){
				if(arrListForShippingAddressValues.size()>0){
					//etxtName.setText(arrListForShippingAddressValues.get(0));
					//etxtEmailId.setText(arrListForShippingAddressValues.get(0));
					//etxtPhNum.setText(arrListForShippingAddressValues.get(1));
					etxtAddress1.setText(arrListForShippingAddressValues.get(1));
					etxtAddress2.setText(arrListForShippingAddressValues.get(2));
					etxtCity.setText(arrListForShippingAddressValues.get(3));
					ArrayAdapter myAdap = (ArrayAdapter) spDdlState.getAdapter();
					int spinnerPosition = myAdap.getPosition(arrListForShippingAddressValues.get(4));
					spDdlState.setSelection(spinnerPosition);
					spDddlCountry.setSelection(0);
					etxtZipCode.setText(arrListForShippingAddressValues.get(5));
				}
			}
			
			
			
			btnShippingSubmit.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View arg0) {
					// TODO Auto-generated method stub
					try{
						btnShippingSubmit.setEnabled(false);
						/*if(etxtName.getText().toString().equals("")){
							etxtName.setError("Name is required.");							
						}*/
					/*	if(etxtEmailId.getText().toString().equals("")){
							etxtEmailId.setError("Email Id is required.");							
						}
						else if (!isValidEmail(etxtEmailId.getText().toString())) {
							etxtEmailId.setError("Invalid Email.");
						}
						else if(etxtPhNum.getText().toString().equals("")){
							etxtPhNum.setError("Phone number is required.");							
						}*/
						 if(etxtAddress1.getText().toString().equals("")){
							etxtAddress1.setError("Address1 is required.");							
						}
						/*else if(etxtAddress2.getText().toString().equals("")){
							etxtAddress2.setError("Address2 is required.");							
						}*/
						else if(etxtCity.getText().toString().equals("")){
							etxtCity.setError("City is required.");							
						}
						else if(etxtZipCode.getText().toString().equals("")){
							etxtZipCode.setError("Zip code is required.");							
						} else {
							//Log.e("etxtName", ""+etxtName.getText());
							//Log.e("etxtEmailId", ""+etxtEmailId.getText());
						//	Log.e("etxtPhNum", ""+etxtPhNum.getText());
							Log.e("etxtAddress1", ""+etxtAddress1.getText());
							Log.e("etxtAddress2", ""+etxtAddress2.getText());
							Log.e("etxtAddress2", ""+etxtCity.getText());
							Log.e("spDdlState", ""+spDdlState.getSelectedItem().toString());
							Log.e("spDddlCountry", ""+spDddlCountry.getSelectedItem().toString());
							Log.e("etxtZipCode", ""+etxtZipCode.getText());
							Log.e("userShipId", ""+userShipId);
							//Log.e("arrListForShippingAddressValues.size()", ""+arrListForShippingAddressValues.size());
							//arrListForShippingAddressValues.add(etxtName.getText().toString());
							//arrListForShippingAddressValues.add(etxtEmailId.getText().toString());
							//arrListForShippingAddressValues.add(etxtPhNum.getText().toString());
							
								HashMap<String, String> params = new HashMap<String, String>();
							    params.put("country", spDddlCountry.getSelectedItem().toString());
							    params.put("addr2", etxtAddress2.getText().toString());
							    params.put("state", spDdlState.getSelectedItem().toString());
							    params.put("addr1", etxtAddress1.getText().toString());
							    params.put("city", etxtCity.getText().toString());
							    params.put("zip", etxtZipCode.getText().toString());
							    
							    Map<String,  Object> jparams = new HashMap<String, Object>();
							    JSONObject jsonObject = new JSONObject(params);	
							    
							    Log.e("jsonObject.toString()",jsonObject.toString());
							    jparams.put("shippingAddress",jsonObject);
							    if(arrListForShippingAddressValues != null){
								    if(arrListForShippingAddressValues.size() > 0 ){
								    	jparams.put("userId",arrListForShippingAddressValues.get(7));
								    	jparams.put("userShipId",arrListForShippingAddressValues.get(0));
								    }
							    }else{
							    	jparams.put("userId",Common.sessionIdForUserLoggedIn);
							    	jparams.put("userShipId","0");
							    }
							    JSONObject jsonObject1 = new JSONObject(jparams);		
								
								Log.e(" jsonFormattedString.toString().get(7)", jsonObject1.toString());
								Map<String, String> jsonParam = new HashMap<String, String>();
								jsonParam.put("userid",""+Common.sessionIdForUserLoggedIn);
								jsonParam.put("json", jsonObject1.toString());
							    String shippingAddressUrl = Constants.Live_Url+"mobileapps/ios/public/stores/usershipping/update/";
								aq.ajax(shippingAddressUrl, jsonParam, JSONObject.class, new AjaxCallback<JSONObject>(){			
									@Override
									public void callback(String url, JSONObject json, AjaxStatus status) {
										try{
											Log.e("json", ""+json);
											if(json!=null){
												btnShippingSubmit.setEnabled(true);
												setResult(1);
												finish();
												
											}
										}catch(Exception e){
											e.printStackTrace();
										}
									}
								});
							}
							/*arrListForShippingAddressValues = new ArrayList<String>();
							arrListForShippingAddressValues.add(etxtAddress1.getText().toString());
							arrListForShippingAddressValues.add(etxtAddress2.getText().toString());
							arrListForShippingAddressValues.add(etxtCity.getText().toString());
							arrListForShippingAddressValues.add(spDdlState.getSelectedItem().toString());
							arrListForShippingAddressValues.add(spDddlCountry.getSelectedItem().toString());
							arrListForShippingAddressValues.add(etxtZipCode.getText().toString());
							arrListForShippingAddressValues.add(""+userShipId);
							
							Intent intent = new Intent(getApplicationContext(), OrderConfirmation.class);									
							intent.putStringArrayListExtra("arrListForShippingAddressValues", arrListForShippingAddressValues);						
					        setResult(2,intent);
					        finish();*/
						//}
						/*Map<String, Object> params = new HashMap<String, Object>();
					    params.put("state", spDdlState.getSelectedItem().toString());
					    params.put("country", spDddlCountry.getSelectedItem().toString());
					    params.put("zip", etxtZipCode.getText().toString());
					    
						String shippingAddressUrl = Constants.Live_Url+"mobileapps/ios/public/stores/shipaddress/";
						aq.ajax(shippingAddressUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
							@Override
							public void callback(String url, JSONObject json, AjaxStatus status) {
								try{
									Log.e("json", ""+json);
									if(json!=null){
										
									}
								}catch(Exception e){
									e.printStackTrace();
								}
							}
						});*/
					    //overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
					} catch(Exception e){
						e.printStackTrace();
				    	String errorMsg = className+" | btnShippingSubmit onclick   |   " +e.getMessage();
						Common.sendCrashWithAQuery(ShippingAddressForm.this, errorMsg);
					}					
				}
			});
			
		} catch(Exception e){
			e.printStackTrace();
	    	String errorMsg = className+" | onCreate  |   " +e.getMessage();
			Common.sendCrashWithAQuery(this, errorMsg);
		}
	}
	// validating email id
	private boolean isValidEmail(String email) {
		String EMAIL_PATTERN = "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@"
				+ "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$";

		Pattern pattern = Pattern.compile(EMAIL_PATTERN);
		Matcher matcher = pattern.matcher(email);
		return matcher.matches();
	}
}
