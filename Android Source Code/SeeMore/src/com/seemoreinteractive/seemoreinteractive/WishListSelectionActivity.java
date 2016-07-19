package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.json.JSONArray;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;
import com.seemoreinteractive.seemoreinteractive.Model.UserWishList;
import com.seemoreinteractive.seemoreinteractive.Model.WishListModel;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.library.WishListAdapter;

public class WishListSelectionActivity extends Activity {
	String current;
	public boolean isBackPressed = false;
	ArrayList<Bitmap> imagesList;
	JSONArray clientsJsonArr1;
	ArrayList<String> clientProdArrListImages;
	ArrayList<String> clientchkProdArrListImages;
	String id = "",wishlistname="";
	String getProductId, getProductName, getProductPrice, getClientLogo, getClientId, getClientBackgroundImage, getClientBackgroundColor;
	public JSONArray userJsonArray = null;
	public JSONArray wishListJsonArray = null;
	public JSONArray userWishlistJsonArray = null;
	public JSONArray userAddWishValJsonArray = null;
	JSONParser getWishListArray;
	List<HashMap<String,String>> aList;
	ArrayList<String> aList1;
	View lastview =null;
	List<NameValuePair> userParams = null, onlyUserIdParams = null;
	AQuery aq;
	String className = this.getClass().getSimpleName();
	@Override
	protected void onCreate(Bundle savedInstanceState){
		try{
			super.onCreate(savedInstanceState);
			setContentView(R.layout.select_wish_list);		
    		Intent intent = getIntent();
    		id=intent.getStringExtra("id");
    		wishlistname=intent.getStringExtra("wishlistname");
    		aq = new AQuery(WishListSelectionActivity.this);
    		if(Common.isNetworkAvailable(WishListSelectionActivity.this))
			{				        			
    			getWishListSelectionResultsFromServerWithXml(Constants.WishList_Url+"select_wishlist/"+id+"/");
			}else{
				getWishListSelectionFromFile();
			}
			TextView txtAlertWishList = (TextView) findViewById(R.id.txtSelectWishList);
			txtAlertWishList.setTextSize((float)((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					
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
					if(Common.isNetworkAvailable(WishListSelectionActivity.this))
					{	
					// TODO Auto-generated methodAlert Dialog Code Start 	
		        	AlertDialog.Builder alertDialogBuilder3 = new AlertDialog.Builder(WishListSelectionActivity.this);
		        	alertDialogBuilder3.setTitle("Add Wish List"); //Set Alert dialog title here
		        	alertDialogBuilder3.setMessage("Provide name of the wish list:"); //Message here

		            // Set an EditText view to get user input 
		            final EditText input = new EditText(WishListSelectionActivity.this);
		            alertDialogBuilder3.setView(input);
			 
			        alertDialogBuilder3.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
			 
			            @Override
			            public void onClick(DialogInterface dialog, int whichButton) {
			                final String getAddWishValue = input.getText().toString();
			            
			                if(getAddWishValue.equals("")){
			                	input.setError("Please enter wish list name.");
			                	input.requestFocus();
								Toast.makeText(getApplicationContext(), "Please enter wish list name.", Toast.LENGTH_LONG).show();
								return;
			                }
			                else {    
			                	aq.ajax(Constants.WishList_Url+"addtowishlist/"+id+"/"+getAddWishValue, XmlDom.class, new AjaxCallback<XmlDom>(){
			            			
			            			@Override
									public void callback(String url, XmlDom xml, AjaxStatus status) {
			            				try{
			            					//Log.i("XmlDom", ""+xml);
				            				if(xml!=null){
							    				if(xml.text("msg").equals("already")){
							    					//Toast.makeText(getApplicationContext(), "Already have same wish list name. Please try again.", Toast.LENGTH_LONG).show();
							    					return;
							    				} else if(xml.text("msg").equals("success")){
							    					//Toast.makeText(getApplicationContext(), "Successfully added to wish list.", Toast.LENGTH_LONG).show();
							    					Intent intent = new Intent(getApplicationContext(), WishListPage.class);			                             
												    intent.putExtra("wishListName", getAddWishValue );
												    setResult(1,intent);
												    finish();
							    					return;
							    				} else {
							    					Toast.makeText(getApplicationContext(), "Add wish list name failed.", Toast.LENGTH_LONG).show();
							    					return;
							    				}				            					
				            				}
			            				} catch(Exception e){
			            					e.printStackTrace();
			            				}
			            			}			            			
			            		});			                	
				            }
			                //Log.i("tag", "Wish list name: " + getAddWishValue);
			            }
			        });							 
			         alertDialogBuilder3.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
			 
			            @Override
			            public void onClick(DialogInterface dialog, int which) {
			            	dialog.cancel();
			            }
			        });
			       //End of alert.setNegativeButton
		        	AlertDialog alertDialog3 = alertDialogBuilder3.create();
		        	alertDialog3.show();
					}
				}
			});
			
			   ListView lvSelectWishLists = (ListView) findViewById(R.id.selectWishListItems);
			   RelativeLayout.LayoutParams rlpLists = (RelativeLayout.LayoutParams) lvSelectWishLists.getLayoutParams();
			   rlpLists.width = (int) (0.897 * Common.sessionDeviceWidth);
			   rlpLists.height = (int) (0.732 * Common.sessionDeviceHeight);
			   rlpLists.topMargin = (int) (0.0139 * Common.sessionDeviceHeight);
			   rlpLists.leftMargin = (int) (0.058 * Common.sessionDeviceWidth);
			   lvSelectWishLists.setLayoutParams(rlpLists);
			
			
		}catch(Exception e){
			Toast.makeText(getApplicationContext(), "Error: WishItemSelection Activity onCreate.", Toast.LENGTH_LONG).show();
			String errorMsg = className+" | onCreate      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
		}
	}

	ArrayList<String> wishlistResArrays;
	String[] wishlistFinalArray;
	 List<UserWishList> userWishlist;
	private void getWishListSelectionFromFile() {
		// TODO Auto-generated method stub
		try{
			
			FileTransaction file = new FileTransaction();
			WishListModel wishlistmodel = file.getWishList();
			if(wishlistmodel.size() >0){

				TextView offerLabel = (TextView)findViewById(R.id.offerLabel);
				//userCloset = closetmodel.getClosetList();
				userWishlist = wishlistmodel.getWishlistDesc();
				
				
				aList = new ArrayList<HashMap<String,String>>();    
				aList1 = new ArrayList<String>();
				imagesList = new ArrayList<Bitmap>();
				int w=0;
				if(userWishlist != null){
					
					String[] wishListId = new String[userWishlist.size()]; 		
					for ( final UserWishList userWishList : userWishlist) {
							try {
							wishListId[w] = ""+userWishList.getId();
							String wishListName = userWishList.getWishListName();
							HashMap<String, String> map = new HashMap<String, String>();
							map.put("Name", wishListName);
							map.put("Delete", Integer.toString(R.drawable.trash));             
				            aList.add(map);   
				            aList1.add(wishListName);
							w++;
						} catch (Exception e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						} 
					}
				}
					 ListView lvSelectWishLists = (ListView) findViewById(R.id.selectWishListItems);				        
				     WishListAdapter adapter1 = new WishListAdapter(WishListSelectionActivity.this, aList1, id,wishlistname);
				     lvSelectWishLists.setAdapter(adapter1);
				     lvSelectWishLists.setOnItemClickListener(new OnItemClickListener() {
					 @Override
					 public void onItemClick(AdapterView<?> arg0,View arg1, final int arg2, long arg3) {
								// TODO Auto-generated method stub						
							if(lastview !=arg1 ){
								if(lastview != null){
									 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
									 lastarrowImage.setVisibility(View.INVISIBLE);
								}
								lastview = arg1;
							    //Toast.makeText(getApplicationContext(), ""+textview1, Toast.LENGTH_LONG).show();
							    TextView txtWishListName = (TextView) arg1.findViewById(R.id.txtWishListName);
							    final String wishListName = txtWishListName.getText().toString();
							    if(lastview != null){
									if(!wishlistname.equals(wishListName)){
										 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
										 lastarrowImage.setVisibility(View.INVISIBLE);
										 txtWishListName.setTextColor(Color.WHITE);
									}
							    }
								lastview = arg1;
							    //Toast.makeText(getApplicationContext(), ""+textview1, Toast.LENGTH_LONG).show()
							    txtWishListName.setTextSize((float)((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
							    ImageView arrowImage = (ImageView) arg1.findViewById(R.id.imgarrow);
							    arrowImage.setVisibility(View.VISIBLE);		
							    txtWishListName.setTextColor(Color.GREEN);
							    Intent intent = new Intent(getApplicationContext(), WishListPage.class);			                             
							    intent.putExtra("wishListName",wishListName );
				                setResult(1,intent);
				      	      	finish();
							}
							}
						});   
                
			}
			
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getWishListSelectionFromFile      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
		}
	}

	public void getWishListSelectionResultsFromServerWithXml(String getProductUrl){
		aq.ajax(getProductUrl, XmlDom.class, this, "wishListSelectionXmlResultFromServer");        
	}
	public void wishListSelectionXmlResultFromServer(String url, XmlDom xml, AjaxStatus status){
		try {
			final List<XmlDom> wishLists = xml.tags("wishLists");
			aList = new ArrayList<HashMap<String,String>>();    
			aList1 = new ArrayList<String>();
			imagesList = new ArrayList<Bitmap>();
			if(wishLists.size()>0){	 
				int w=0;
				String[] wishListId = new String[wishLists.size()]; 		
				for(final XmlDom wlXml : wishLists){
					try {
						wishListId[w] = wlXml.text("wishlist_id").toString();
						String wishListName = wlXml.text("wishlist_name").toString();
						HashMap<String, String> map = new HashMap<String, String>();
						map.put("Name", wishListName);
						map.put("Delete", Integer.toString(R.drawable.trash));             
			            aList.add(map);   
			            aList1.add(wishListName);
						w++;
					} catch (Exception e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					} 
				}
			}
			/*String[] from = { "Name","Delete"};		        
	        // Ids of views in listview_layout
	        int[] to = { R.id.txtWishListName,R.id.imgDeleteIcon};   */
	        // fill in the grid_item layout
	        ListView lvSelectWishLists = (ListView) findViewById(R.id.selectWishListItems);
	        
	        WishListAdapter adapter1 = new WishListAdapter(WishListSelectionActivity.this, aList1, id,wishlistname);
	        lvSelectWishLists.setAdapter(adapter1);
	        lvSelectWishLists.setOnItemClickListener(new OnItemClickListener() {
				@Override
				public void onItemClick(AdapterView<?> arg0,
						View arg1, final int arg2, long arg3) {
					// TODO Auto-generated method stub						
					if(lastview !=arg1 ){
						if(lastview != null){
							 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
							 lastarrowImage.setVisibility(View.INVISIBLE);
						}
						lastview = arg1;
					    //Toast.makeText(getApplicationContext(), ""+textview1, Toast.LENGTH_LONG).show();
					    TextView txtWishListName = (TextView) arg1.findViewById(R.id.txtWishListName);
					    final String wishListName = txtWishListName.getText().toString();
					    if(lastview != null){
							if(!wishlistname.equals(wishListName)){
								 ImageView lastarrowImage = (ImageView) lastview.findViewById(R.id.imgarrow);
								 lastarrowImage.setVisibility(View.INVISIBLE);
								 txtWishListName.setTextColor(Color.WHITE);
							}
					    }
						lastview = arg1;
					    //Toast.makeText(getApplicationContext(), ""+textview1, Toast.LENGTH_LONG).show()
					    txtWishListName.setTextSize((float)((0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity));
					    ImageView arrowImage = (ImageView) arg1.findViewById(R.id.imgarrow);
					    arrowImage.setVisibility(View.VISIBLE);		
					    txtWishListName.setTextColor(Color.GREEN);
					    Intent intent = new Intent(getApplicationContext(), WishListPage.class);			                             
					    intent.putExtra("wishListName",wishListName );
		                setResult(1,intent);
		      	      	finish();
					}
					}
				});   
	        
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | wishListSelectionXmlResultFromServer      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
		}
	}
	 @Override
	public void onStart() {
		 try{
			 
		 
	    super.onStart();
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
	    String[] segments = new String[1];
		segments[0] = "Displays Wishlist list" ; 
		QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
  
		 }catch(Exception e){
			 e.printStackTrace();
			String errorMsg = className+" | onStart      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
		 }
	}
	 @Override
	public void onStop() {
		 try{
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.
		QuantcastClient.activityStop();
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop      |   " +e.getMessage();
			 Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
		 }
	}
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(WishListSelectionActivity.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(WishListSelectionActivity.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(WishListSelectionActivity.this,errorMsg);
			}
		}
	
}