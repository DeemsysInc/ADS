package com.seemoreinteractive.seemoreinteractive;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Map;
import java.util.Set;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.paypal.android.sdk.payments.PayPalAuthorization;
import com.paypal.android.sdk.payments.PayPalConfiguration;
import com.paypal.android.sdk.payments.PayPalFuturePaymentActivity;
import com.paypal.android.sdk.payments.PayPalItem;
import com.paypal.android.sdk.payments.PayPalOAuthScopes;
import com.paypal.android.sdk.payments.PayPalPayment;
import com.paypal.android.sdk.payments.PayPalPaymentDetails;
import com.paypal.android.sdk.payments.PayPalProfileSharingActivity;
import com.paypal.android.sdk.payments.PayPalService;
import com.paypal.android.sdk.payments.PaymentActivity;
import com.paypal.android.sdk.payments.PaymentConfirmation;
import com.paypal.android.sdk.payments.ShippingAddress;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class ProductsCheckout extends Activity {

	AQuery aq;
	public static ArrayList<String> arrFinalAllSavingToOrder;
	JSONObject jsonObjFinal;
	RadioButton radioGrpButton; 
	RadioGroup radioGroup1;
	String className = this.getClass().getSimpleName();
	String strOrderID = "",shopFlag="";
	String finalMsg = "";

    private static final String TAG = "PaypalPayment";
    /**
     * - Set to PaymentActivity.ENVIRONMENT_PRODUCTION to move real money.
     * 
     * - Set to PaymentActivity.ENVIRONMENT_SANDBOX to use your test credentials
     * from https://developer.paypal.com
     * 
     * - Set to PayPalConfiguration.ENVIRONMENT_NO_NETWORK to kick the tires
     * without communicating to PayPal's servers.
     */
    private static final String CONFIG_ENVIRONMENT = PayPalConfiguration.ENVIRONMENT_NO_NETWORK;

    // note that these credentials will differ between live & sandbox environments.
    //private static final String CONFIG_CLIENT_ID = "AWxZtxBOag2kOYBL8nXWTpouV6OA7VAZpaWBtFL_qCpTGOpdvpzqYkokGBtD";
    //private static final String CONFIG_CLIENT_ID = "Ac74ZRDeXywJuwuorUyyzjW4l94NOArkUbnT5qPVSy6wyd5VvW-IjBAxjZHa";
    
    //dev credentials 
    //private static final String CONFIG_CLIENT_ID = "Afvb8BBqVaJ5qjvW8eG9o36i11jQAgaMZiN3IU4YBTFCubKIdGj-0oH1Fkb8";
    
    //Live Credentials
    private static final String CONFIG_CLIENT_ID = "AejVNRA8PFS4f8H0BlsnvdTQyxfUPVaRjaKPAHw7ou54k8rV4fjV34O-lSVG";
    
    

    private static final int REQUEST_CODE_PAYMENT = 1;
    private static final int REQUEST_CODE_FUTURE_PAYMENT = 2;
    private static final int REQUEST_CODE_PROFILE_SHARING = 3;

    private static PayPalConfiguration config = new PayPalConfiguration()
            .environment(CONFIG_ENVIRONMENT)
            .clientId(CONFIG_CLIENT_ID)
            // The following are only used in PayPalFuturePaymentActivity.
            .merchantName("SeeMore")
            .merchantPrivacyPolicyUri(Uri.parse("http://www.seemoreinteractive.com/home/privacy"))
            .merchantUserAgreementUri(Uri.parse("http://www.seemoreinteractive.com/home/terms"));

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_products_checkout);
		try{
	        Intent intent = new Intent(this, PayPalService.class);
	        intent.putExtra(PayPalService.EXTRA_PAYPAL_CONFIGURATION, config);
	        startService(intent);
	        Log.e("onCreate","onCreate");
			aq = new AQuery(this);
			arrFinalAllSavingToOrder = new ArrayList<String>();
			if(getIntent().getExtras()!=null){
				Log.e("getIntent().getExtras()", ""+getIntent().getExtras());
				strOrderID = getIntent().getStringExtra("orderId");
				finalMsg = getIntent().getStringExtra("finalMsg");
				arrFinalAllSavingToOrder = getIntent().getStringArrayListExtra("arrFinalAll");
				shopFlag = getIntent().getStringExtra("orderId");
				jsonObjFinal = new JSONObject(arrFinalAllSavingToOrder.get(0));
				Log.e("jsonObjFinal",""+jsonObjFinal);
			}			
			RelativeLayout container = (RelativeLayout)findViewById(R.id.container);
			RelativeLayout.LayoutParams rlForContainer = (RelativeLayout.LayoutParams) container.getLayoutParams();
			rlForContainer.width     = (int) (0.8334 * Common.sessionDeviceWidth);
			rlForContainer.height    = (int) (0.513 * Common.sessionDeviceHeight);
			rlForContainer.topMargin = (int) (0.177 * Common.sessionDeviceHeight);
			container.setLayoutParams(rlForContainer);	
			
			
			Button btnCancel = (Button) findViewById(R.id.btnCancel);
			RelativeLayout.LayoutParams rlForbtnCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
			rlForbtnCancel.width     = (int) (0.5 * Common.sessionDeviceWidth);
			rlForbtnCancel.height    = (int) (0.082 * Common.sessionDeviceHeight);
			rlForbtnCancel.topMargin = (int) (0.0246 * Common.sessionDeviceHeight);
			btnCancel.setLayoutParams(rlForbtnCancel);	
			btnCancel.setTextSize((float)((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			btnCancel.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						finish();
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnCheckoutConfirm onclick  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
					}
				}
			});
			
			Button btnPaypal = (Button) findViewById(R.id.btnPaypal);
			RelativeLayout.LayoutParams rlForbtnPaypall = (RelativeLayout.LayoutParams) btnPaypal.getLayoutParams();
			rlForbtnPaypall.width     = (int) (0.5 * Common.sessionDeviceWidth);
			rlForbtnPaypall.height    = (int) (0.082 * Common.sessionDeviceHeight);
			rlForbtnPaypall.topMargin = (int) (0.1507 * Common.sessionDeviceHeight);
			btnPaypal.setLayoutParams(rlForbtnPaypall);	
			btnPaypal.setTextSize((float)((0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
			btnPaypal.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						Log.e("paypal"," btn");
						Map<String, Object> params = new HashMap<String, Object>();
					    params.put("json", jsonObjFinal);			
					    params.put("userid", Common.sessionIdForUserLoggedIn); 
					    Log.e("params",""+params);
						String orderUpdateUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/update/";
						aq.ajax(orderUpdateUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
							@Override
							public void callback(String url, JSONObject json, AjaxStatus status) {
								try{			
									Log.e("json", ""+json);
									if(json!=null){										
										if(json.getString("msg").equals("success")){			
											Log.e("json"," json");
										        PayPalPayment thingToBuy = getThingToBuyBulkAmount(PayPalPayment.PAYMENT_INTENT_SALE);
										        Intent intent = new Intent(ProductsCheckout.this, PaymentActivity.class);
										        intent.putExtra(PaymentActivity.EXTRA_PAYMENT, thingToBuy);
										        startActivityForResult(intent, REQUEST_CODE_PAYMENT);
											}
										} else {
											Toast.makeText(ProductsCheckout.this, "Opps! Your order updation failed. \nPlease contact us for more details.", Toast.LENGTH_SHORT).show();
										}
									
								} catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | btnCheckoutConfirm order update callback  |   " +e.getMessage();
									Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
								}
							}
						});
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnPaypal order update callback  |   " +e.getMessage();
						Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
					}
				}
				
			});
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | oncreate  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
		}
	}
    private PayPalPayment getThingToBuy(String paymentIntent) {
    	try{    
    		PayPalPayment payment = null;
	    	if(jsonObjFinal.length()>0){
	    		payment = new PayPalPayment(new BigDecimal(jsonObjFinal.getString("orderTotal")), "USD", "SeeMore Interactive", paymentIntent);
	    	}
	    	return payment;
    	}catch(Exception e){
    		e.printStackTrace();
    		return null;
    	}
    }

    private PayPalPayment getThingToBuyBulkAmount(String paymentIntent) {
    	PayPalPayment payment = null;
    	BigDecimal shipping = null, subtotal = null, tax = null;
    	try{
    		Log.e("jsonObjFinal.length",""+jsonObjFinal.length());
	    	if(jsonObjFinal.length()>0){
	    		
	    		tax = new BigDecimal(jsonObjFinal.getString("salesTax"));
	    		
	    		if(!jsonObjFinal.optJSONArray("clients").equals("")){
					JSONArray jsonArrClients = jsonObjFinal.getJSONArray("clients");		    		
					for(int c=0; c<jsonArrClients.length(); c++){
						if(!jsonArrClients.optString(c).equals("")){
			    			JSONObject jsonObjForClients1 = jsonArrClients.getJSONObject(c);				    		
			    			shipping = new BigDecimal(jsonObjForClients1.getString("shippingCost"));				    		
			 		        subtotal = new BigDecimal(jsonObjForClients1.getString("clientTotal"));				    		
						}
			        }
	    		}			
	    		Log.e("getThingToBuyBulkAmount",""+shipping+"subtotal"+subtotal+tax);
		        PayPalPaymentDetails paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);
		        payment = new PayPalPayment(new BigDecimal(jsonObjFinal.getString("orderTotal")), "USD", "SeeMore Interactive", paymentIntent);
		        payment.paymentDetails(paymentDetails);
		        //payment.items(items).paymentDetails(paymentDetails);
		        
	    	}
	      
	    	return payment;
    	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getThingToBuyBulkAmount  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
			return null;
		}
    }
    
	ArrayList<PayPalItem> multiItem = new ArrayList<PayPalItem>();
	PayPalItem[] singleItemArr = {};
    private PayPalPayment getThingToBuyRemove(String paymentIntent) {
    	try{
	        if(jsonObjFinal.length()>0){
				if(!jsonObjFinal.optJSONArray("clients").equals("")){
					JSONArray jsonArrClients = jsonObjFinal.getJSONArray("clients");
					for(int c=0; c<jsonArrClients.length(); c++){
						if(!jsonArrClients.optString(c).equals("")){
			    			JSONObject jsonObjForClients1 = jsonArrClients.getJSONObject(c);
			    			if(!jsonObjForClients1.optJSONArray("products").equals("")){
			    				JSONArray jsonArrForPds1 = jsonObjForClients1.getJSONArray("products");
			    				if(jsonArrForPds1.length()==1){
			    					if(!jsonArrForPds1.optString(0).equals("")){
				    					JSONObject jsonObjPds1 = jsonArrForPds1.getJSONObject(0);
					    				if(!jsonObjPds1.optString("prodName").equals("")){
					    					/*singleItem = new PayPalItem(jsonObjPds1.getString("prodName"), 
					    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
					    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
					    							jsonObjPds1.getString("prodName")+"-"+jsonObjPds1.getString("prodId"));*/
					    					singleItemArr = new PayPalItem[]{new PayPalItem(jsonObjPds1.getString("prodName"), 
					    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
					    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
					    							jsonObjPds1.getString("prodName")+"-"+jsonObjPds1.getString("prodId"))};
					    				}			    					
				    				}
			    			        BigDecimal subtotal = PayPalItem.getItemTotal(singleItemArr);
			    			        BigDecimal shipping = new BigDecimal(jsonObjForClients1.getString("shippingCost"));
			    			        BigDecimal tax = new BigDecimal(jsonObjFinal.getString("salesTax"));
			    			        paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);
			    			        BigDecimal amount = subtotal.add(shipping).add(tax);
			    			        payment = new PayPalPayment(amount, "USD", "SeeMore", paymentIntent);
			    			        payment.items(singleItemArr).paymentDetails(paymentDetails);
			    				} else {
		    						for(int p=0; p<jsonArrForPds1.length(); p++){
					    				if(!jsonArrForPds1.optString(p).equals("")){
					    					JSONObject jsonObjPds1 = jsonArrForPds1.getJSONObject(p);
						    				if(!jsonObjPds1.optString("prodName").equals("")){
						    					multiItem.add(new PayPalItem(jsonObjPds1.getString("prodName"), 
						    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
						    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
						    							jsonObjPds1.getString("prodName")+"-"+jsonObjPds1.getString("prodId")));
						    					
						    					/*PayPalItem[] items =
					    			            {
				    			                    new PayPalItem(jsonObjPds1.getString("prodName"), 
						    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
						    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
						    							jsonObjPds1.getString("prodName")+"-"+jsonObjPds1.getString("prodId"))
					    			            };*/
					    						singleItemArr = new PayPalItem[]{multiItem.get(p)};
					    						
						    				}			    					
					    				}
				    				}
		    						
			    				}
			    			}
						}
					}
				}
	        }
	        //--- set other optional fields like invoice_number, custom field, and soft_descriptor
	        payment.custom("This is text that will be associated with the payment that the app can use.");
	
	        return payment;
    	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getThingToBuy |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
			return null;
		}
    }
    
    /* 
     * This method shows use of optional payment details and item list.
     */
    PayPalItem[] items = new PayPalItem[]{};
    //BigDecimal subtotal, shipping, tax, amount;
    PayPalPaymentDetails paymentDetails;
    PayPalPayment payment;
    double subTotalForAllPds = 0, shippingCostForAllClients = 0;
    private PayPalPayment getStuffToBuy(String paymentIntent) {
        //--- include an item list, payment amount details
    	try{
    		
	        if(jsonObjFinal.length()>0){
				if(!jsonObjFinal.optJSONArray("clients").equals("")){
					JSONArray jsonArrClients = jsonObjFinal.getJSONArray("clients");
					//Log.e("jsonArrClients", jsonArrClients.length()+" "+jsonArrClients);
					//Log.e("jsonArrClients vals", ""+jsonArrClients.getString(0));
					for(int c=0; c<jsonArrClients.length(); c++){
						if(!jsonArrClients.optString(c).equals("")){
			    			JSONObject jsonObjForClients1 = jsonArrClients.getJSONObject(c);
			    			//Log.e("jsonObjForClients1 vals", ""+jsonObjForClients1);
			    			if(!jsonObjForClients1.optJSONArray("products").equals("")){
			    				JSONArray jsonArrForPds1 = jsonObjForClients1.getJSONArray("products");
			    				//Log.e("jsonArrForPds1 vals", jsonArrForPds1.length()+" "+jsonArrForPds1);
			    				
			    				items = new PayPalItem[jsonArrForPds1.length()];
	    						for(int p=0; p<jsonArrForPds1.length(); p++){
				    				if(!jsonArrForPds1.optString(p).equals("")){
				    					JSONObject jsonObjPds1 = jsonArrForPds1.getJSONObject(p);
					    				//Log.e("jsonObjPds1 vals", ""+jsonObjPds1);
					    				if(!jsonObjPds1.optString("prodName").equals("")){
					    					//Log.e("jsonObjPds1 vals", ""+jsonObjPds1.getString("prodName"));
					    					/*strPdItems[p] = jsonObjPds1.getString("prodName")+", "+
					    					Integer.parseInt(jsonObjPds1.getString("prodQuantity"))+", "+
					    							new BigDecimal(jsonObjPds1.getString("prodPrice"))+
					    							", USD, "+jsonObjPds1.getString("prodName")+"-"+
					    							jsonObjPds1.getString("prodId");
					    					Log.e("strPdItems[p] "+p, ""+strPdItems[p]);*/
					    					PayPalItem eachItem = new PayPalItem(jsonObjPds1.getString("prodName"), 
					    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
					    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
					    							jsonObjPds1.getString("prodName")+"-"+jsonObjPds1.getString("prodId"));
					    				
					    					if(p==0){
					    						
					    					}
					    				
					    					items[p] = eachItem;
					    					
					    				}			    					
				    				}
			    				}
			    				
			    				subTotalForAllPds += Double.parseDouble(PayPalItem.getItemTotal(items).toString());			    				
			    				shippingCostForAllClients += Double.parseDouble(jsonObjForClients1.getString("shippingCost"));
			    				
			    			}
						}
					}
					
					BigDecimal subtotal = new BigDecimal(subTotalForAllPds);
			        BigDecimal shipping = new BigDecimal(shippingCostForAllClients);
			        BigDecimal tax = new BigDecimal(jsonObjFinal.getString("salesTax"));
    				
			        paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);    				
    				BigDecimal amount = subtotal.add(shipping).add(tax);    				
			        payment = new PayPalPayment(amount, "USD", "SeeMore", paymentIntent);    				
    		        payment.items(items).paymentDetails(paymentDetails);
    				
				}
	        }
	            /*{
	                    new PayPalItem("old jeans with holes", 2, new BigDecimal("87.50"), "USD",
	                            "sku-12345678"),
	                    new PayPalItem("free rainbow patch", 1, new BigDecimal("0.00"),
	                            "USD", "sku-zero-price"),
	                    new PayPalItem("long sleeve plaid shirt (no mustache included)", 6, new BigDecimal("37.99"),
	                            "USD", "sku-33333") 
	            };*/
	        /*BigDecimal subtotal = PayPalItem.getItemTotal(items);
	        BigDecimal shipping = new BigDecimal("7.21");
	        BigDecimal tax = new BigDecimal("4.67");
	        PayPalPaymentDetails paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);
	        BigDecimal amount = subtotal.add(shipping).add(tax);
	        PayPalPayment payment = new PayPalPayment(amount, "USD", "hipster jeans", paymentIntent);
	        payment.items(items).paymentDetails(paymentDetails);*/
	
	        //--- set other optional fields like invoice_number, custom field, and soft_descriptor
	        payment.custom("This is text that will be associated with the payment that the app can use.");
	        return payment;
    	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getStuffToBuy  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
			return null;
		}
    }
    private PayPalPayment getStuffToBuyOld2(String paymentIntent) {
        //--- include an item list, payment amount details
        PayPalItem[] items =
            {
                    new PayPalItem("old jeans with holes", 2, new BigDecimal("87.50"), "USD",
                            "sku-12345678"),
                    new PayPalItem("free rainbow patch", 1, new BigDecimal("0.00"),
                            "USD", "sku-zero-price"),
                    new PayPalItem("long sleeve plaid shirt (no mustache included)", 6, new BigDecimal("37.99"),
                            "USD", "sku-33333") 
            };
        BigDecimal subtotal = PayPalItem.getItemTotal(items);
        BigDecimal shipping = new BigDecimal("7.21");
        BigDecimal tax = new BigDecimal("4.67");
        PayPalPaymentDetails paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);
        BigDecimal amount = subtotal.add(shipping).add(tax);
        PayPalPayment payment = new PayPalPayment(amount, "USD", "hipster jeans", paymentIntent);
        payment.items(items).paymentDetails(paymentDetails);

        //--- set other optional fields like invoice_number, custom field, and soft_descriptor
        payment.custom("This is text that will be associated with the payment that the app can use.");

        return payment;
    }
    private PayPalPayment getStuffToBuyOld(String paymentIntent) {
        //--- include an item list, payment amount details
    	PayPalPayment payment = null;
    	try{
	    	PayPalItem[] items = {}; 
	    	BigDecimal shipping = null, tax = null;
	    	PayPalPaymentDetails paymentDetails = null;
			if(arrFinalAllSavingToOrder.size()>0){
				JSONObject jsonObj1 = new JSONObject(arrFinalAllSavingToOrder.get(0));
				
				tax = new BigDecimal(jsonObj1.getString("salesTax"));
				if(!jsonObj1.optJSONArray("clients").equals("")){
					JSONArray jsonArrClients = jsonObj1.getJSONArray("clients");
					
					for(int c=0; c<jsonArrClients.length(); c++){
						if(!jsonArrClients.optString(c).equals("")){
			    			JSONObject jsonObjForClients1 = jsonArrClients.getJSONObject(c);
			    			
			    	        shipping = new BigDecimal(jsonObjForClients1.getString("shippingCost"));
			    			if(!jsonObjForClients1.optJSONArray("products").equals("")){
			    				JSONArray jsonArrForPds1 = jsonObjForClients1.getJSONArray("products");
			    				
			    				items = new PayPalItem[jsonArrForPds1.length()];
			    				for(int p=0; p<jsonArrForPds1.length(); p++){
				    				if(!jsonArrForPds1.optString(p).equals("")){
				    					JSONObject jsonObjPds1 = jsonArrForPds1.getJSONObject(p);
					    				
					    				if(!jsonObjPds1.optString("prodName").equals("")){
					    					
					    					items[p] = new PayPalItem(jsonObjPds1.getString("prodName"), 
					    							Integer.parseInt(jsonObjPds1.getString("prodQuantity")), 
					    							new BigDecimal(jsonObjPds1.getString("prodPrice")), "USD",
					                                "sku-12345678");

					    			        BigDecimal subtotal = PayPalItem.getItemTotal(items);
					    			       
					    			        //BigDecimal shipping = new BigDecimal("7.21");
					    			        //BigDecimal tax = new BigDecimal("4.67");
					    			        paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);					    			        
					    			        BigDecimal amount = subtotal.add(shipping).add(tax);					    			        
					    			        payment = new PayPalPayment(amount, "USD", jsonObjPds1.getString("prodName"), paymentIntent);
					    				}			    					
				    				}
			    				}
			    			}
						}
					}
				}
			}
			/*
	        PayPalItem[] items =
	            {
	                    new PayPalItem("old jeans with holes", 2, new BigDecimal("87.50"), "USD",
	                            "sku-12345678"),
	                    new PayPalItem("free rainbow patch", 1, new BigDecimal("0.00"),
	                            "USD", "sku-zero-price"),
	                    new PayPalItem("long sleeve plaid shirt (no mustache included)", 6, new BigDecimal("37.99"),
	                            "USD", "sku-33333") 
	            };*/
	        /*BigDecimal subtotal = PayPalItem.getItemTotal(items);
	        Log.e("bigdecimal", "shipping:"+shipping+" tax:"+tax+" subtotal:"+subtotal);
	        //BigDecimal shipping = new BigDecimal("7.21");
	        //BigDecimal tax = new BigDecimal("4.67");
	        PayPalPaymentDetails paymentDetails = new PayPalPaymentDetails(shipping, subtotal, tax);
	        Log.e("paymentDetails", ""+paymentDetails);
	        BigDecimal amount = subtotal.add(shipping).add(tax);
	        Log.e("amount", ""+amount);
	        payment = new PayPalPayment(amount, "USD", "SeeMore", paymentIntent);
	        payment.items(items).paymentDetails(paymentDetails);*/
			payment.items(items).paymentDetails(paymentDetails);
	        //--- set other optional fields like invoice_number, custom field, and soft_descriptor
	        payment.custom("This is text that will be associated with the payment that the app can use.");
    	} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getStuffToBuy  |   " +e.getMessage();
			Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
		}
        return payment;
    }
    
    /*
     * Add app-provided shipping address to payment
     */
    private void addAppProvidedShippingAddress(PayPalPayment paypalPayment) {
        ShippingAddress shippingAddress =
                new ShippingAddress().recipientName("Mom Parker").line1("52 North Main St.")
                        .city("Austin").state("TX").postalCode("78729").countryCode("US");
        paypalPayment.providedShippingAddress(shippingAddress);
    }
    
    /*
     * Enable retrieval of shipping addresses from buyer's PayPal account
     */
    private void enableShippingAddressRetrieval(PayPalPayment paypalPayment, boolean enable) {
        paypalPayment.enablePayPalShippingAddressesRetrieval(enable);
    }

    public void onFuturePaymentPressed(View pressed) {
        Intent intent = new Intent(ProductsCheckout.this, PayPalFuturePaymentActivity.class);

        startActivityForResult(intent, REQUEST_CODE_FUTURE_PAYMENT);
    }

    public void onProfileSharingPressed(View pressed) {
        Intent intent = new Intent(ProductsCheckout.this, PayPalProfileSharingActivity.class);
        intent.putExtra(PayPalProfileSharingActivity.EXTRA_REQUESTED_SCOPES, getOauthScopes());
        startActivityForResult(intent, REQUEST_CODE_PROFILE_SHARING);
    }

    private PayPalOAuthScopes getOauthScopes() {
        /* create the set of required scopes
         * Note: see https://developer.paypal.com/docs/integration/direct/identity/attributes/ for mapping between the
         * attributes you select for this app in the PayPal developer portal and the scopes required here.
         */
        Set<String> scopes = new HashSet<String>(
                Arrays.asList(PayPalOAuthScopes.PAYPAL_SCOPE_EMAIL, PayPalOAuthScopes.PAYPAL_SCOPE_ADDRESS) );
        return new PayPalOAuthScopes(scopes);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
    	try{
        if (requestCode == REQUEST_CODE_PAYMENT) {
            if (resultCode == Activity.RESULT_OK) {
                PaymentConfirmation confirm =
                        data.getParcelableExtra(PaymentActivity.EXTRA_RESULT_CONFIRMATION);
                if (confirm != null) {
                    try {
                        
                        JSONObject jsonObjForConfirmResponse = new JSONObject(confirm.toJSONObject().toString(4));                        
                        JSONObject jsonObjForConfirmPayment = new JSONObject(confirm.getPayment().toJSONObject().toString(4));
                        
						jsonObjFinal.put("paymentAmount", jsonObjForConfirmPayment.getString("amount"));
						jsonObjFinal.put("paymentRespId", jsonObjForConfirmResponse.getJSONObject("response").getString("id"));
						jsonObjFinal.put("paymentStatus", "success");
						if(!jsonObjForConfirmPayment.optJSONObject("details").equals("")){
							jsonObjFinal.put("paymentShippingCost", jsonObjForConfirmPayment.getJSONObject("details").getString("shipping"));
							jsonObjFinal.put("paymentSubTotal", jsonObjForConfirmPayment.getJSONObject("details").getString("subtotal"));
							jsonObjFinal.put("paymentTax", jsonObjForConfirmPayment.getJSONObject("details").getString("tax"));							
						}
						jsonObjFinal.put("paymentMethod", "Paypal");
						jsonObjFinal.put("paymentMethodId", "2");
						
						
						/*jsonObjStaticFinalPdDetails = new JSONObject(jsonObjFinal.toString());
						Log.e("jsonObjStaticFinalPdDetails", ""+jsonObjStaticFinalPdDetails);*/
						Map<String, Object> params = new HashMap<String, Object>();
					    params.put("json", jsonObjFinal);			
					    params.put("userid", Common.sessionIdForUserLoggedIn); 
						String orderFinalUrl = Constants.Live_Url+"mobileapps/ios/public/stores/order/final/";
						aq.ajax(orderFinalUrl, params, JSONObject.class, new AjaxCallback<JSONObject>(){			
							@Override
							public void callback(String url, JSONObject json, AjaxStatus status) {
								try{
									
									if(json!=null){										
										if(json.getString("msg").equals("success")){
					                        /**
					                         *  TODO: send 'confirm' (and possibly confirm.getPayment() to your server for verification
					                         * or consent completion.
					                         * See https://developer.paypal.com/webapps/developer/docs/integration/mobile/verify-mobile-payment/
					                         * for more details.
					                         *
					                         * For sample mobile backend interactions, see
					                         * https://github.com/paypal/rest-api-sdk-python/tree/master/samples/mobile_backend
					                         */
					                        //Toast.makeText(getApplicationContext(),"PaymentConfirmation info received from PayPal", Toast.LENGTH_LONG).show();
					                        ProductDetails.arrPdInfoForCartList.clear();
					                        ProductDetails.arrForClientIds.clear();
					                        ProductDetails.arrListHashMapForClientInfo.clear();
					                        OrderConfirmation.arrListJsonObjMySavedOrders.clear();        
					                        /*Intent intent = new Intent(ProductsCheckout.this, Products.class);
											//intent.putExtra("finalMsg", json.getJSONObject("payment").getString("saveOrderMsg"));
											startActivity(intent);
											finish();*/
					                        ProductDetails.prdsDetail.finish();
											Products.prds.finish();
											SaveOrderInformation.saveOrder.finish();
											
					                        Intent intent = new Intent(ProductsCheckout.this, ThankYou.class);
											intent.putExtra("finalMsg", finalMsg);
											startActivity(intent);
											finish();
										    overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_left);					                        
										} else {
											Toast.makeText(ProductsCheckout.this, "Opps! Your order updation failed. \nPlease contact us for more details.", Toast.LENGTH_SHORT).show();
										}
									}
								} catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | btnCheckoutConfirm order update callback  |   " +e.getMessage();
									Common.sendCrashWithAQuery(ProductsCheckout.this, errorMsg);
								}
							}
						});	     
                    } catch (JSONException e) {
                        Log.e(TAG, "an extremely unlikely failure occurred: ", e);
                    }
                }
            } else if (resultCode == Activity.RESULT_CANCELED) {
                Log.i(TAG, "The user canceled.");
            } else if (resultCode == PaymentActivity.RESULT_EXTRAS_INVALID) {
                Log.i(
                        TAG,
                        "An invalid Payment or PayPalConfiguration was submitted. Please see the docs.");
            }
        } else if (requestCode == REQUEST_CODE_FUTURE_PAYMENT) {
            if (resultCode == Activity.RESULT_OK) {
                PayPalAuthorization auth =
                        data.getParcelableExtra(PayPalFuturePaymentActivity.EXTRA_RESULT_AUTHORIZATION);
                if (auth != null) {
                    try {
                        Log.i("FuturePaymentExample", auth.toJSONObject().toString(4));

                        String authorization_code = auth.getAuthorizationCode();
                        Log.i("FuturePaymentExample", authorization_code);

                        sendAuthorizationToServer(auth);
                        Toast.makeText(
                                getApplicationContext(),
                                "Future Payment code received from PayPal", Toast.LENGTH_LONG)
                                .show();

                    } catch (JSONException e) {
                        Log.e("FuturePaymentExample", "an extremely unlikely failure occurred: ", e);
                    }
                }
            } else if (resultCode == Activity.RESULT_CANCELED) {
                Log.i("FuturePaymentExample", "The user canceled.");
            } else if (resultCode == PayPalFuturePaymentActivity.RESULT_EXTRAS_INVALID) {
                Log.i(
                        "FuturePaymentExample",
                        "Probably the attempt to previously start the PayPalService had an invalid PayPalConfiguration. Please see the docs.");
            } 
        } else if (requestCode == REQUEST_CODE_PROFILE_SHARING) {
            if (resultCode == Activity.RESULT_OK) {
                PayPalAuthorization auth =
                        data.getParcelableExtra(PayPalProfileSharingActivity.EXTRA_RESULT_AUTHORIZATION);
                if (auth != null) {
                    try {
                        Log.i("ProfileSharingExample", auth.toJSONObject().toString(4));

                        String authorization_code = auth.getAuthorizationCode();
                        Log.i("ProfileSharingExample", authorization_code);

                        sendAuthorizationToServer(auth);
                        Toast.makeText(
                                getApplicationContext(),
                                "Profile Sharing code received from PayPal", Toast.LENGTH_LONG)
                                .show();

                    } catch (JSONException e) {
                        Log.e("ProfileSharingExample", "an extremely unlikely failure occurred: ", e);
                    }
                }
            } else if (resultCode == Activity.RESULT_CANCELED) {
                Log.i("ProfileSharingExample", "The user canceled.");
            } else if (resultCode == PayPalFuturePaymentActivity.RESULT_EXTRAS_INVALID) {
                Log.i(
                        "ProfileSharingExample",
                        "Probably the attempt to previously start the PayPalService had an invalid PayPalConfiguration. Please see the docs.");
            }
        }
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }

    private void sendAuthorizationToServer(PayPalAuthorization authorization) {

        /**
         * TODO: Send the authorization response to your server, where it can
         * exchange the authorization code for OAuth access and refresh tokens.
         * 
         * Your server must then store these tokens, so that your server code
         * can execute payments for this user in the future.
         * 
         * A more complete example that includes the required app-server to
         * PayPal-server integration is available from
         * https://github.com/paypal/rest-api-sdk-python/tree/master/samples/mobile_backend
         */

    }

    public void onFuturePaymentPurchasePressed(View pressed) {
        // Get the Application Correlation ID from the SDK
        String correlationId = PayPalConfiguration.getApplicationCorrelationId(this);

        Log.i("FuturePaymentExample", "Application Correlation ID: " + correlationId);

        // TODO: Send correlationId and transaction details to your server for processing with
        // PayPal...
        Toast.makeText(
                getApplicationContext(), "App Correlation ID received from SDK", Toast.LENGTH_LONG)
                .show();
    }

    @Override
    public void onDestroy() {
        // Stop service when done
        stopService(new Intent(this, PayPalService.class));
        super.onDestroy();
    }

    
}
