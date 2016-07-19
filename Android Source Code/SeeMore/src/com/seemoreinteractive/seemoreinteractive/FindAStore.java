package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.List;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Address;
import android.location.Criteria;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
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
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.AQUtility;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FindAStore extends Activity implements LocationListener {

	private GoogleMap mMap;
	double lat =0.0;
	double lng =0.0;
	Geocoder geocoder; 
	List<Address> current_address;
	Button btnSearchIcon;
	EditText eTxtSearchFindaStore; 
	Spinner spSearchFindaStore;
	Context mContext = this;
	AQuery aq;
	ArrayList<String> arrForSearchTitle, arrForSearchAddress, arrForSearchType, arrForSearchLocLat, arrForSearchLocLng,arrForSearchLocRef;
	String searchAddress="null",searchName="",searchType="";
	ArrayList<String> arrForSearchDistance, arrForSearchDuration;
	String placesSearchStr ="";
	ListView lvForSearchNearByPlaces;
	String className = this.getClass().getSimpleName();
	LinearLayout.LayoutParams mapViewParameters;
	@TargetApi(Build.VERSION_CODES.HONEYCOMB)
	@SuppressLint("NewApi")
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_find_a_store);
		try{
			final Intent getIntent = getIntent();
			Log.i("main","before notify");
			Log.i("main",""+getIntent.getExtras());
			if(getIntent.getExtras()!=null){
				lat =Double.parseDouble(getIntent.getStringExtra("latitude"));
				lng = Double.parseDouble(getIntent.getStringExtra("longitude"));
				searchName = getIntent.getStringExtra("search_name");
				searchType = getIntent.getStringExtra("search_type");				
				Spinner spinner = (Spinner) findViewById(R.id.spinnerFindAStore);
				ArrayAdapter myAdap = (ArrayAdapter) spinner.getAdapter(); //cast to an ArrayAdapter
				int spinnerPosition = myAdap.getPosition(searchType);
				//sespinnert the default according to value
				spinner.setSelection(spinnerPosition);
			}else{
				 getLatLng();
			}
			
			LocationManager locationManager = (LocationManager) getSystemService(LOCATION_SERVICE);
			locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 1000L,1.0f, this);
			boolean isGPS = locationManager.isProviderEnabled (LocationManager.GPS_PROVIDER);			
			
			if(!isGPS){
				AlertDialog.Builder alertDialog = new AlertDialog.Builder(FindAStore.this);				      
		        alertDialog.setTitle("GPS");	
		        alertDialog.setMessage("Please enable GPS in settings");	
		        alertDialog.setPositiveButton("Settings", new DialogInterface.OnClickListener() {
		            @Override
					public void onClick(DialogInterface dialog,int which) {			           
						try{
							startActivityForResult(new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS), 1);
						} catch (Exception e) {
							e.printStackTrace();		
							String errorMsg = className+" | GPS dialog click  |   " +e.getMessage();
				       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
						}
		            }
		        });
				 
		        // Setting Negative "NO" Button
		        alertDialog.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
		            @Override
					public void onClick(DialogInterface dialog, int which) {			         
		            dialog.cancel();
		            }
		        });
		 
		        // Showing Alert Message
		        alertDialog.show();
				
				
			}
			
			aq = new AQuery(mContext);
			mMap = ((MapFragment) getFragmentManager().findFragmentById(R.id.map)).getMap();
		   /* mapViewParameters = (LinearLayout.LayoutParams) findViewById(
		            R.id.map).getLayoutParams();*/
			RelativeLayout.LayoutParams rlpForMap = (RelativeLayout.LayoutParams) findViewById(R.id.map).getLayoutParams();
			rlpForMap.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlpForMap.height = (int) (0.41 * Common.sessionDeviceHeight);
			rlpForMap.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			findViewById(R.id.map).setLayoutParams(rlpForMap);

			mMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
			if(Common.isNetworkAvailable(FindAStore.this))
			{
				getGoogleMapLocationInMapView("");
			}else{
				new Common().instructionBox(FindAStore.this,R.string.title_case7,R.string.instruction_case7);
			}
			
			lvForSearchNearByPlaces = (ListView) findViewById(R.id.lvForMapView);
			RelativeLayout.LayoutParams rlpForLv = (RelativeLayout.LayoutParams) lvForSearchNearByPlaces.getLayoutParams();
			rlpForLv.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlpForLv.height = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;;
			rlpForLv.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			lvForSearchNearByPlaces.setLayoutParams(rlpForLv);
			
			RelativeLayout rl1 = (RelativeLayout) findViewById(R.id.relativeLayout1);
			RelativeLayout.LayoutParams rlpForRl1 = (RelativeLayout.LayoutParams) rl1.getLayoutParams();
			rlpForRl1.width = android.view.ViewGroup.LayoutParams.MATCH_PARENT;
			rlpForRl1.height = (int) (0.062 * Common.sessionDeviceHeight);
			rl1.setLayoutParams(rlpForRl1);
			
			ImageView imgvBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCameraIcon);
			RelativeLayout.LayoutParams rlpForCameraIcon = (RelativeLayout.LayoutParams) imgvBtnCameraIcon.getLayoutParams();
			rlpForCameraIcon.width = (int) (0.1334 * Common.sessionDeviceWidth);
			rlpForCameraIcon.height = (int) (0.082 * Common.sessionDeviceHeight);
			rlpForCameraIcon.rightMargin = (int) (0.017 * Common.sessionDeviceWidth);
			imgvBtnCameraIcon.setLayoutParams(rlpForCameraIcon);
			imgvBtnCameraIcon.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						Constants.ARFlag = true;
						Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
	    				intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
	    				startActivity(intent); // Launch the HomescreenActivity
	    				finish(); 
	    				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
	    				
						//new Common().clickingOnBackButtonWithAnimationWithBackPressed(FindAStore.this, ARDisplayActivity.class, "0");
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | imgvBtnCameraIcon click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
					}
				}
			});

			TextView txtHeadTitle = (TextView) findViewById(R.id.txtvHeaderTitle);
			txtHeadTitle.setTextSize((float)(0.06 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			
			eTxtSearchFindaStore = (EditText) findViewById(R.id.etxtFindaStore);
			eTxtSearchFindaStore.setPadding((int) (0.017 * Common.sessionDeviceWidth), 0, 0, 0);
			eTxtSearchFindaStore.setTextSize((float)(0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
			RelativeLayout.LayoutParams rlpForTxtSearch = (RelativeLayout.LayoutParams) eTxtSearchFindaStore.getLayoutParams();
			rlpForTxtSearch.width = (int) (0.5 * Common.sessionDeviceWidth);
			rlpForTxtSearch.height = (int) (0.062 * Common.sessionDeviceHeight);
			rlpForTxtSearch.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
			rlpForTxtSearch.topMargin = (int) (0.0103 * Common.sessionDeviceHeight);
			eTxtSearchFindaStore.setLayoutParams(rlpForTxtSearch);
			eTxtSearchFindaStore.setText(searchName);
			
			spSearchFindaStore = (Spinner) findViewById(R.id.spinnerFindAStore);
			spSearchFindaStore.setPadding((int) (0.017 * Common.sessionDeviceWidth), 0, 0, 0);
			RelativeLayout.LayoutParams rlpForSpSearch = (RelativeLayout.LayoutParams) spSearchFindaStore.getLayoutParams();
			rlpForSpSearch.width = android.view.ViewGroup.LayoutParams.WRAP_CONTENT;
			rlpForSpSearch.height = (int) (0.062 * Common.sessionDeviceHeight);
			rlpForSpSearch.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
			spSearchFindaStore.setLayoutParams(rlpForSpSearch);
			
			btnSearchIcon = (Button) findViewById(R.id.btnSearchIcon);
			RelativeLayout.LayoutParams rlpForBtnSearch = (RelativeLayout.LayoutParams) btnSearchIcon.getLayoutParams();
			rlpForBtnSearch.width = (int) (0.1 * Common.sessionDeviceWidth);
			rlpForBtnSearch.height = (int) (0.0615 * Common.sessionDeviceHeight);
			rlpForBtnSearch.leftMargin = (int) (0.017 * Common.sessionDeviceWidth);
			rlpForBtnSearch.rightMargin = (int) (0.017 * Common.sessionDeviceWidth);
			btnSearchIcon.setLayoutParams(rlpForBtnSearch);

			btnSearchIcon.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(FindAStore.this))
						{
							String searchingVal = "";
							searchName ="";
							searchType="";						
							if(eTxtSearchFindaStore.getText().toString().equals("")){
								//searchingVal = spSearchFindaStore.getSelectedItem().toString();
								searchType = spSearchFindaStore.getSelectedItem().toString();
							} else {
								searchingVal = eTxtSearchFindaStore.getText().toString()+" "+spSearchFindaStore.getSelectedItem().toString();
								searchName = eTxtSearchFindaStore.getText().toString();
								searchType = spSearchFindaStore.getSelectedItem().toString();
							
							}						
							getGoogleMapLocationInMapView(searchingVal);
						}else{
							new Common().instructionBox(FindAStore.this,R.string.title_case7,R.string.instruction_case7);
						}
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | btnSearchIcon click  |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
					}
				}
			});
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onCreate   |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		}
	}

	ArrayList<String> findAStoreResArrays;
	String[] findAStorFinalArray;
	Location location;
	public void getGoogleMapLocationInMapView(String searchingVal){
		try{
			if(Common.isNetworkAvailable(FindAStore.this))
			{
				//Log.e("values",lat+","+lng+"&types="+searchType+"&name="+searchName);
				LatLng latLng = new LatLng(lat, lng);
				mMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
				if(searchName.equals("")){
					LatLng latLng1 = new LatLng(lat, lng);
					mMap.addMarker(new MarkerOptions()
		                .position(latLng1)
		                .title("You are here")
		                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_VIOLET)));
			}else{
					LatLng latLng1 = new LatLng(lat, lng);		
				    mMap.clear();
					mMap.addMarker(new MarkerOptions()
		                .position(latLng1)
		                .title("You are here")
		                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_VIOLET)));
					if(lvForSearchNearByPlaces != null)
						lvForSearchNearByPlaces.setAdapter(null);
					
				//String placesSearchStr = "https://maps.googleapis.com/maps/api/place/nearbysearch/xml?location=17.4098431,78.4500884&types=store&name=axis&sensor=true&key=AIzaSyByr3_eB-xj20q2DcrKWXJw3YxAwiCRgo4&rankby=distance";
				 placesSearchStr = "https://maps.googleapis.com/maps/api/place/nearbysearch/xml?location="+lat+","+lng+"&types="+searchType+"&name="+searchName+"&sensor=true&key="+Constants.GOOGLE_API_KEY+"&rankby=distance";
					Log.i("places url", ""+placesSearchStr);
					aq.ajax(placesSearchStr, XmlDom.class, new AjaxCallback<XmlDom>(){			
						@Override
						public void callback(String url, XmlDom xml, AjaxStatus status) {
							try{
								Log.i("XmlDom", ""+xml);
			    				if(xml!=null){
			    					List<XmlDom> xmlLv = xml.tags("result");

			    					findAStorFinalArray = new String[xmlLv.size()];
			    					if(xmlLv.size()>0){
			    						arrForSearchTitle = new ArrayList<String>();
			    						arrForSearchAddress = new ArrayList<String>();
			    						arrForSearchType = new ArrayList<String>();
			    						arrForSearchLocLat = new ArrayList<String>();
			    						arrForSearchLocLng = new ArrayList<String>();
			    						arrForSearchLocRef = new ArrayList<String>();
			    						int c=0;
										for(XmlDom lvResultsInXml: xmlLv){
							        		if(lvResultsInXml.tag("name")!=null){
							        			//findAStoreResArrays = new ArrayList<String>();
							        			arrForSearchTitle.add(lvResultsInXml.text("name").toString());
							        			arrForSearchAddress.add(lvResultsInXml.text("vicinity").toString());
							        			arrForSearchType.add(lvResultsInXml.text("type").toString());
							        			arrForSearchLocLat.add(lvResultsInXml.tag("geometry").tag("location").text("lat").toString());
							        			arrForSearchLocLng.add(lvResultsInXml.tag("geometry").tag("location").text("lng").toString());
							        			arrForSearchLocRef.add(lvResultsInXml.text("reference").toString());
							        			
							        			Log.i("name", ""+lvResultsInXml.text("name").toString());
							        			Log.i("vicinity", ""+lvResultsInXml.text("vicinity").toString());
							        			Log.i("type", ""+lvResultsInXml.text("type").toString());
							        			Log.i("lat", ""+lvResultsInXml.tag("geometry").tag("location").text("lat").toString());
							        			Log.i("lng", ""+lvResultsInXml.tag("geometry").tag("location").text("lng").toString());
							        			Log.i("reference", ""+lvResultsInXml.text("reference").toString());
							        			//findAStorFinalArray[c] = findAStoreResArrays.toString();
					    		    			//c++;
											} 
										}
										if(arrForSearchTitle.size()>0){
											lvForSearchNearByPlaces.setAdapter(renderForGoolgeMapFindAStoreLv(arrForSearchTitle,arrForSearchAddress,arrForSearchType,arrForSearchLocLat,arrForSearchLocLng,arrForSearchLocRef));
											lvForSearchNearByPlaces.setOnItemClickListener(new OnItemClickListener() {
												@Override
												public void onItemClick(
														AdapterView<?> arg0, View arg1,
														int arg2, long arg3) {		
													String searchTitle = ((TextView) arg1.findViewById(R.id.txtSearchTitle)).getText().toString();
													String searchAddress = ((TextView) arg1.findViewById(R.id.txtvSearchAddress)).getText().toString();
													String searchReference = ((TextView) arg1.findViewById(R.id.txtSearchTitle)).getTag(R.string.reference_tag).toString();
													String searchDistance = ((TextView) arg1.findViewById(R.id.txtvDistanceVal)).getText().toString();
													//Log.e("searchTitle",searchTitle);
													//Log.e("searchAddress",searchAddress);
													//Log.e("searchReference",searchReference);
													//Log.e("searchDistance",searchDistance);
													Intent intent = new Intent(FindAStore.this, FindAStoreItemDetails.class);	
													intent.putExtra("storeTitle", searchTitle);
													intent.putExtra("storeAddress", searchAddress);
													intent.putExtra("storeReference", searchReference);
													intent.putExtra("storeDistance", searchDistance);
													intent.putExtra("lat", ""+lat);
													intent.putExtra("lng", ""+lng);
													startActivity(intent);
													//finish();
													overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
													 /*searchAddress ="";
													 String searchTitle = ((TextView) arg1.findViewById(R.id.txtSearchTitle)).getText().toString();
													 searchAddress = ((TextView) arg1.findViewById(R.id.txtvSearchAddress)).getText().toString();
													 getGoogleMapLocationInMapView(searchTitle);*/
												}
											});

											for(int i=0;i<arrForSearchTitle.size();i++){
												Double latValue = Double.parseDouble(arrForSearchLocLat.get(i));
												Double lngValue = Double.parseDouble(arrForSearchLocLng.get(i));
											LatLng latLng = new LatLng(latValue, lngValue);
											mMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
											mMap.addMarker(new MarkerOptions()
									                .position(latLng)
									                .title(arrForSearchTitle.get(i))
									                .snippet(arrForSearchAddress.get(i))
									                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_RED)));
									     
										
											}
										}
			    					}
			    				}
							} catch (Exception e){
								e.printStackTrace();
								String errorMsg = className+" | getGoogleMapLocationInMapView  callback |   " +e.getMessage();
					       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
							}
						}
					});
			}
			mMap.setMyLocationEnabled(true);	        
			mMap.getUiSettings().setCompassEnabled(true);
			mMap.getUiSettings().setZoomControlsEnabled(true);
	        mMap.animateCamera(CameraUpdateFactory.newLatLngZoom(latLng, 10));
			
			}else{
				new Common().instructionBox(FindAStore.this,R.string.title_case7,R.string.instruction_case7);
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | getGoogleMapLocationInMapView   |   " +e.getMessage();
       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		}
	}

	int bigImageLinearLayoutWidth = 0, bigImageLinearLayoutHeight = 0;
    int lvItemLayout = 0;
	private ArrayAdapter<String> renderForGoolgeMapFindAStoreLv(final ArrayList<String> arrForSearchTitle2, final ArrayList<String> arrForSearchAddress2, final ArrayList<String> arrForSearchType2, final ArrayList<String> arrForSearchLocLat2,final ArrayList<String> arrForSearchLocLng2, final ArrayList<String> arrForSearchLocRef2){	    	
	    AQUtility.debug("render setup");
	    lvItemLayout = R.layout.find_a_store_list_items;
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
						String expStoreAddress = arrForSearchAddress2.get(position);		
						String expStoreType = arrForSearchType2.get(position);		
						String expStoreLocationLat = arrForSearchLocLat2.get(position);		
						String expStoreLocationLong =  arrForSearchLocLng2.get(position);		
						String expStoreReference =  arrForSearchLocRef2.get(position);
						
						TextView txtSearchTitle =(TextView) convertView.findViewById(R.id.txtSearchTitle);
						txtSearchTitle.setTextSize((float) (0.05 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
						
						aq2.id(R.id.txtSearchTitle).text(expStoreTitle);
						txtSearchTitle.setTag(R.string.reference_tag, expStoreReference);
						
						TextView txtvSearchAddress =(TextView) convertView.findViewById(R.id.txtvSearchAddress);
                        
						aq2.id(R.id.txtvSearchAddress).text(expStoreAddress);
						
						//build distance query string				
					/*	String distanceUrl = "http://maps.googleapis.com/maps/api/distancematrix/xml?" +
								"origins="+expStoreLocationLat+","+expStoreLocationLong+"" +
								"&units=imperial&destinations="+expStoreLocationLat+","+expStoreLocationLong+"" +
								"&sensor=false&language=en";
					*/	
						String distanceUrl = "http://maps.googleapis.com/maps/api/distancematrix/xml?" +
								"origins="+lat+","+lng+"" +
								"&units=imperial&destinations="+arrForSearchLocLat2.get(position)+","+arrForSearchLocLng2.get(position)+"" +
								"&sensor=false&language=en";
						
						Log.i("distanceUrl url", ""+distanceUrl);
						aq.ajax(distanceUrl, XmlDom.class, new AjaxCallback<XmlDom>(){			
							@Override
							public void callback(String url, XmlDom xml, AjaxStatus status) {
								try{
									Log.i("XmlDom", ""+xml);
				    				if(xml!=null){
				    					List<XmlDom> xmlLv = xml.tags("row");
				    					if(xmlLv.size()>0){
				    						arrForSearchDistance = new ArrayList<String>();
				    						arrForSearchDuration = new ArrayList<String>();
											for(XmlDom lvResultsInXml: xmlLv){
								        		if(lvResultsInXml.tag("element")!=null){
								        			Log.i("lvResultsInXml", ""+lvResultsInXml);
								        			arrForSearchDistance.add(lvResultsInXml.tag("element").tag("distance").text("text").toString());
								        			arrForSearchDuration.add(lvResultsInXml.tag("element").tag("duration").text("text").toString());
								        			Log.i("duration", ""+lvResultsInXml.tag("element").tag("duration").text("text").toString());
								        			Log.i("distance", ""+lvResultsInXml.tag("element").tag("distance").text("text").toString());
													
								        			if(lvResultsInXml.tag("element").tag("distance").text("text").toString()!=null || lvResultsInXml.tag("element").tag("distance").text("text").toString().equals("")){
								        				aq2.id(R.id.txtvDistanceVal).text(lvResultsInXml.tag("element").tag("distance").text("text").toString());
								        			} else {
								        				aq2.id(R.id.txtvDistanceVal).text(" -- ");
								        			}
								        			if(lvResultsInXml.tag("element").tag("duration").text("text").toString()!=null || lvResultsInXml.tag("element").tag("duration").text("text").toString().equals("")){
								        				aq2.id(R.id.txtvDurationVal).text(lvResultsInXml.tag("element").tag("duration").text("text").toString());
								        			} else {
								        				aq2.id(R.id.txtvDurationVal).text(" -- ");
								        			}
												}
											}
				    					}
				    				}
								} catch (Exception e){
									e.printStackTrace();
									String errorMsg = className+" | renderForGoolgeMapFindAStoreLv callback  |   " +e.getMessage();
						       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
								}
							}
						});		
					}								
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForGoolgeMapFindAStoreLv   |   " +e.getMessage();
		       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		//aq.id(R.id.grid_view).adapter(aa);
		return aa;
	}
	final int RQS_GooglePlayServices = 1;
	
	
	 @Override
		public void onStart() {
		 try{
			    super.onStart();
			    Tracker easyTracker = EasyTracker.getInstance(this);
				easyTracker.set(Fields.SCREEN_NAME, "/findastore");
				easyTracker.send(MapBuilder
				    .createAppView()
				    .build()
				);
				 String[] segments = new String[1];
				 segments[0] = "Find a store"; 
				 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStart  |   " +e.getMessage();
		       	 Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
			 }
		}
		 @Override
		public void onStop() {
		 try{
			 super.onStop();
			 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	
			 QuantcastClient.activityStop();
		 }catch(Exception e){
			 e.printStackTrace();
			 String errorMsg = className+" | onStop  |   " +e.getMessage();
	       	 Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		 }
		}	
	
	
	@Override
	protected void onResume() {
		try{
			super.onResume();		 
			int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(getApplicationContext());		  
			if (resultCode == ConnectionResult.SUCCESS){
				//Toast.makeText(getApplicationContext(), "isGooglePlayServicesAvailable SUCCESS", Toast.LENGTH_LONG).show();
			}else{
				GooglePlayServicesUtil.getErrorDialog(resultCode, this, RQS_GooglePlayServices);
			}	
			
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(FindAStore.this);			
				Common.isAppBackgrnd = false;
			}
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onResume   |   " +e.getMessage();
	        Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		}
	}
	
	@Override
	protected void onPause() 
	{
		try{
			super.onPause();
			Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(FindAStore.this);
			if(appInBackgrnd){
				 Common.isAppBackgrnd = true;
			}					
		}catch (Exception e) {		
			e.printStackTrace();
			String errorMsg = className+" | onPause | " +e.getMessage();
        	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		}		
	}	
	@Override
	public void onLocationChanged(Location location) {	
		try{
		lat = location.getLatitude();
		lng = location.getLongitude();
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | onLocationChanged   |   " +e.getMessage();
	        Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
		}		
	}
	@Override
	public void onStatusChanged(String provider, int status, Bundle extras) {
		
	}
	@Override
	public void onProviderEnabled(String provider) {
		
	}
	@Override
	public void onProviderDisabled(String provider) {
		
	}
	@Override
    protected void onActivityResult(int requestCode, int resultCode, final Intent data) {
        try{
                super.onActivityResult(requestCode, resultCode, data);
                if(requestCode==1){
                        Log.i("requestCode",""+requestCode);
                        getGoogleMapLocationInMapView("");
                }
        }catch (Exception e){
                e.printStackTrace();
                Toast.makeText(getApplicationContext(), "Error:  onActivityResult", Toast.LENGTH_LONG).show();
                String errorMsg = className+" | onActivityResult   |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(FindAStore.this,errorMsg);
        }        
    }
	
	public static LocationManager locManager;
	private static boolean gps_enabled = false;
	private static boolean network_enabled = false;
	public  LocationListener locListener = new myLocationListener();	
	public  void getLatLng(){
		try{
			
		    	 locManager = (LocationManager) mContext.getSystemService(Context.LOCATION_SERVICE);
		 	    try {
		 	        gps_enabled = locManager
		 	                .isProviderEnabled(LocationManager.GPS_PROVIDER);
		 	    } catch (Exception ex) {
		 	    }
		 	    try {
		 	        network_enabled = locManager
		 	                .isProviderEnabled(LocationManager.NETWORK_PROVIDER);
		 	    } catch (Exception ex) {
		 	    }
		 	    if (gps_enabled) {
		 	        locManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0,
		 	                0, locListener);
		 	    }
		 	    if (network_enabled) {
		 	        locManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,
		 	                0, 0, locListener);
		 	    }
		 	
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	private  class myLocationListener implements LocationListener {
	    @Override
	    public void onLocationChanged(Location location) {
	    	try{	
		        if (location != null) {
		            locManager.removeUpdates(locListener);
		            lng = location.getLongitude();
		            lat = location.getLatitude();
		            String longitude = "Longitude: " + location.getLongitude();
		            String latitude = "Latitude: " + location.getLatitude();
		          //  Log.v("Debug",  "Latt: " + latitude + " Long: " + longitude);		          
		            
		        }
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	    }

	    @Override
	    public void onProviderDisabled(String provider) {
	    }

	    
	    @Override
	    public void onProviderEnabled(String provider) {
	    }

	    

		@Override
		public void onStatusChanged(String provider, int status, Bundle extras) {
			
			
		}
	}

}
