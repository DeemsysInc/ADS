package com.seemoreinteractive.seemoreinteractive;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.List;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.util.AQUtility;
import com.google.analytics.tracking.android.EasyTracker;
import com.google.analytics.tracking.android.Fields;
import com.google.analytics.tracking.android.MapBuilder;
import com.google.analytics.tracking.android.Tracker;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class RecentlyScanned extends Activity {

	AQuery aq;
	String[] strMarkerArray = null;
	ArrayList<String> arrGetMarkerValues;
	ListView lvRecentlyScanned;
	TextView txtEmptyResults;
	String className = this.getClass().getSimpleName();
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN) @Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_recently_scanned);
		aq = new AQuery(this);
		
		try{
	        new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Recently Scanned", "");	
	        
			txtEmptyResults = (TextView) findViewById(R.id.txtEmptyResults);
			lvRecentlyScanned = (ListView) findViewById(R.id.lvRecentlyScanned);
	        
			new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);
			ImageView imgBtnCameraIcon = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCameraIcon.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{	
						new Common().clickingOnBackButtonWithAnimationWithBackPressed(RecentlyScanned.this, ARDisplayActivity.class, "0");
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | onCreate   imgBtnCameraIcon click |   " +e.getMessage();
			       	 	Common.sendCrashWithAQuery(RecentlyScanned.this, errorMsg);
					}
				}
			});
	    	
	    	ImageView imgvBtnCloset = (ImageView) findViewById(R.id.imgvBtnCloset);      
	    	//imgvBtnCloset.setImageAlpha(0);
			new Common().showDrawableImageFromAquery(this, R.drawable.btn_trash, R.id.imgvBtnCloset);
			imgvBtnCloset.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{
						if(Common.sessionTriggerScannedVals != null){
						if(Common.sessionTriggerScannedVals.size()>0){
							AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(RecentlyScanned.this);				 
								// set title
								alertDialogBuilder.setTitle("Confirm!");				 
								// set dialog message
								alertDialogBuilder
									.setMessage("Are you sure you want to clear all recently scanned list?")
									.setCancelable(false)
									.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
										@Override
										public void onClick(DialogInterface dialog,int id) {
											// if this button is clicked, close
											// current activity		
											try{
												Common.sessionTriggerScannedVals.clear();
												Arrays.fill(strMarkerArray, null);
												arrGetMarkerValues.clear();
												arrRemovedDuplicates.clear();
												ARDisplayActivity.multiHMapForAllMarkerInfo.clear();
												ARDisplayActivity.finalArrForAllMarkerInfo.clear();
												SessionManager.storedScannedVals3.clear();
												lvRecentlyScanned.setAdapter(null);
												txtEmptyResults.setText("There are no results.");
												txtEmptyResults.setVisibility(View.VISIBLE);
												
											} catch(Exception e){
												e.printStackTrace();
												String errorMsg = className+" | Oncreate dialog yes click     |   " +e.getMessage();
												Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
											}
										}
									  })
									.setNegativeButton("No",new DialogInterface.OnClickListener() {
										@Override
										public void onClick(DialogInterface dialog,int id) {
											// if this button is clicked, just close
											// the dialog box and do nothing
											dialog.cancel();
										}
									});				 
							// create alert dialog
							AlertDialog alertDialog = alertDialogBuilder.create();				 
							// show it
							alertDialog.show();
						}
						}
					} catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | Oncreate imgvBtnCloset click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
					}
				}
			});
			
	    	ImageView imgvBtnShare = (ImageView) findViewById(R.id.imgvBtnShare);      
	    	imgvBtnShare.setImageAlpha(0);
	    	
	    	ImageView imgvBtnBack = (ImageView) findViewById(R.id.imgvBtnBack);   
			imgvBtnBack.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					try{		
				    	 Intent Home = new Intent(getApplicationContext(), ARDisplayActivity.class);
				         setResult(1,Home);
					     finish();
					     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);	
					} catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
						//Toast.makeText(getApplicationContext(), "Error: SeeMore Login imgvBtnBack.", Toast.LENGTH_LONG).show();
						String errorMsg = className+" | Oncreate imgvBtnBack click     |   " +e.getMessage();
						Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
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
						Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
					}
					
				}
			});
			
			listviewForRecentlyScanned();
		} catch(Exception e)
		{
			e.printStackTrace();
			String errorMsg = className+" | onCreate     |   " +e.getMessage();
			Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
		} 
	}
	List<String> arrRemovedDuplicates;
    @TargetApi(Build.VERSION_CODES.JELLY_BEAN) 
    private void listviewForRecentlyScanned() {	
		try{	
			if(Common.sessionTriggerScannedVals!=null && Common.sessionTriggerScannedVals.size()>0)
			{				
			    arrRemovedDuplicates = new ArrayList<String>(Common.sessionTriggerScannedVals);
				Collections.sort(arrRemovedDuplicates);
				Collections.reverse(arrRemovedDuplicates);
								
				strMarkerArray = new String[arrRemovedDuplicates.size()];
			    
				for(int i=0; i<arrRemovedDuplicates.size(); i++){
				    arrGetMarkerValues = new ArrayList<String>();
					if(Common.sessionTriggerScannedVals.get(i)!=null){
						String[] splitSessValuesWithPipe = arrRemovedDuplicates.get(i).split("\\|");
						
						arrGetMarkerValues.add((splitSessValuesWithPipe[0]!=null) ? splitSessValuesWithPipe[0] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[1]!=null) ? splitSessValuesWithPipe[1] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[2]!=null) ? splitSessValuesWithPipe[2] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[3]!=null) ? splitSessValuesWithPipe[3] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[4]!=null) ? splitSessValuesWithPipe[4] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[5]!=null) ? splitSessValuesWithPipe[5] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[6]!=null) ? splitSessValuesWithPipe[6] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[7]!=null) ? splitSessValuesWithPipe[7] : "");
						arrGetMarkerValues.add((splitSessValuesWithPipe[8]!=null) ? splitSessValuesWithPipe[8] : "");
												
						strMarkerArray[i] = arrGetMarkerValues.toString();
					}
				}
				if(strMarkerArray.length>0){
			        // Assign adapter to ListView
					lvRecentlyScanned.setAdapter(renderForRecentlyScannedListView(strMarkerArray));
				}
				txtEmptyResults.setVisibility(View.INVISIBLE);
				
				lvRecentlyScanned.setOnItemClickListener(new OnItemClickListener() {
					@Override
					public void onItemClick(AdapterView<?> parent, View view,
							int position, long arg3) {
						 // TODO Auto-generated method stub
						try{
						String s ="[";
						String q ="]";
						String w ="";
						String strReplaceSymbol = String.valueOf(parent.getItemAtPosition(position)).replace(s, w).replace(q, w);
						
						String[] expRecentlyScannedArray = strReplaceSymbol.split(",");

						String expCurrentDate = expRecentlyScannedArray[0].trim();		
						String expTriggerId = expRecentlyScannedArray[1].trim();		
						String expClientName = expRecentlyScannedArray[2].trim();		
						String expMarkerName = expRecentlyScannedArray[3].trim();		
						String expCosId = expRecentlyScannedArray[4].trim();		
						String expProductId = expRecentlyScannedArray[5].trim();		
						String expProductName = expRecentlyScannedArray[6].trim();		
						String expOfferId = expRecentlyScannedArray[7].trim();		
						String expOfferName = expRecentlyScannedArray[8].trim();	
						
				    	 Intent intent = new Intent(getApplicationContext(), TopMenuInfo.class);
				    	 intent.putExtra("triggerId", expTriggerId);
				    	 intent.putExtra("cosId", expCosId);
				    	 intent.putExtra("productId", expProductId);
				    	 intent.putExtra("offerId", expOfferId);
				         setResult(2,intent);
					     finish();
					     overridePendingTransition(R.xml.slide_in_left,R.xml.slide_out_right);
				    	
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | listviewForRecentlyScanned    lvRecentlyScanned onclick  |   " +e.getMessage();
							Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
						}
					}
				});
			} else {
				txtEmptyResults.setText("There are no results.");
				txtEmptyResults.setVisibility(View.VISIBLE);
			}
		} catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | listviewForRecentlyScanned     |   " +e.getMessage();
			Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
		}
	}
    StringBuilder strAppendValues1;
	int listviewLayout = 0;    
	ArrayAdapter<String> aa;
	private ArrayAdapter<String> renderForRecentlyScannedListView(final String[] strMarkerArray2){	    	
	    AQUtility.debug("render setup");
	    listviewLayout = R.layout.listview_for_recently_scanned;	
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
						String[] expRecentlyScannedArray = strReplaceSymbol.split(",");

						String expCurrentDate = expRecentlyScannedArray[0].trim();		
						String expTriggerId = expRecentlyScannedArray[1].trim();		
						String expClientName = expRecentlyScannedArray[2].trim();		
						String expMarkerName = expRecentlyScannedArray[3].trim();		
						String expCosId = expRecentlyScannedArray[4].trim();		
						String expProductId = expRecentlyScannedArray[5].trim();		
						String expProductName = expRecentlyScannedArray[6].trim();		
						String expOfferId = expRecentlyScannedArray[7].trim();		
						String expOfferName = expRecentlyScannedArray[8].trim();
						TextView txtClientNameRS = (TextView) convertView.findViewById(R.id.txtClientNameRS);
						txtClientNameRS.setText(expClientName);
						TextView txtSymbolRS = (TextView) convertView.findViewById(R.id.txtSymbolRS);
						txtSymbolRS.setText(" - ");
						TextView txtMarkerNameRS = (TextView) convertView.findViewById(R.id.txtMarkerNameRS);					
						txtMarkerNameRS.setText(expMarkerName);
						TextView txtMarkerScannedDate = (TextView) convertView.findViewById(R.id.txtMarkerScannedDate);					
						txtMarkerScannedDate.setText(expCurrentDate);
						TextView txtProductName = (TextView) convertView.findViewById(R.id.txtProductName);
						if (expProductName.equals("null")
								|| expProductName.equals("")
								|| expProductName == null) {
							txtProductName.setText(expOfferName);
						} else if (expOfferName.equals("null")
								|| expOfferName.equals("")
								|| expOfferName == null) {
							txtProductName.setText(expProductName);
						} else {
							txtProductName.setText("");
						}
						
						ImageView imgvCloseEachRecord = (ImageView) convertView.findViewById(R.id.imgvCloseRS);
						LinearLayout.LayoutParams llForRsImgClose = (LinearLayout.LayoutParams) imgvCloseEachRecord.getLayoutParams();
						llForRsImgClose.width = (int) (0.05 * Common.sessionDeviceWidth);
						llForRsImgClose.height = (int) (0.031 * Common.sessionDeviceHeight);
						imgvCloseEachRecord.setLayoutParams(llForRsImgClose);
						imgvCloseEachRecord.setOnClickListener(new OnClickListener() {
							@Override
							public void onClick(View v) {								
								try{
									Common.sessionTriggerScannedVals.remove(position);									
									listviewForRecentlyScanned();
									if(Common.sessionTriggerScannedVals.size()==0){
										Common.sessionTriggerScannedVals.clear();
										Arrays.fill(strMarkerArray, null);
										Arrays.fill(strMarkerArray2, null);
										arrGetMarkerValues.clear();
										arrRemovedDuplicates.clear();
										SessionManager.storedScannedVals3.clear();
										ARDisplayActivity.finalArrForAllMarkerInfo.clear();
										ARDisplayActivity.multiHMapForAllMarkerInfo.clear();
										lvRecentlyScanned.setAdapter(null);
										txtEmptyResults.setText("There are no results.");
										txtEmptyResults.setVisibility(View.VISIBLE);
									} else {										
										String s ="[";
										String q ="]";
										String w ="";
										String strReplaceSymbol1 = String.valueOf(strMarkerArray2[position]).replace(s, w).replace(q, w);					
										String[] expRecentlyScannedArray1 = strReplaceSymbol1.split(",");
										strAppendValues1 = new StringBuilder();
										for(int r=0; r<expRecentlyScannedArray1.length; r++){
											
											if(strAppendValues1.length()>0){
												strAppendValues1.append("|");
											}
											strAppendValues1.append(expRecentlyScannedArray1[r].trim());
										}	
										String expCurrentDate1 = expRecentlyScannedArray1[0].trim();		
										ARDisplayActivity.multiHMapForAllMarkerInfo.remove(expCurrentDate1);
										ARDisplayActivity.finalArrForAllMarkerInfo.remove(strAppendValues1);
										SessionManager.storedScannedVals3.remove(strAppendValues1);
									}
								} catch(Exception e){
									e.printStackTrace();
									String errorMsg = className+" | renderForRecentlyScannedListView    imgvCloseEachRecord onclick   |   " +e.getMessage();
									Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
								}
							}
						});
						
					}
					
				} catch (Exception e){
					e.printStackTrace();
					String errorMsg = className+" | renderForRecentlyScannedListView    getview   |   " +e.getMessage();
					Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
				}
				return convertView;					
			}			
		};			
		return aa;
	}	

	public boolean isBackPressed = false;
	
	@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
		try{
	        if (keyCode == KeyEvent.KEYCODE_BACK) {
	            //Log.i("Press Back", "BACK PRESSED EVENT");
	            onBackPressed();
	            isBackPressed = true;
	        }
	        // Call super code so we dont limit default interaction
	        return super.onKeyDown(keyCode, event);
		} catch (Exception ex) {
			Toast.makeText(getApplicationContext(), "Error: HowTo onKeyDown.", Toast.LENGTH_LONG).show();
			return false;
		}
    }
    @Override
	public void onBackPressed() {
    	try{
    		finish();
    		overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
    	} catch (Exception ex) {
    		String errorMsg = className+" | onBackPressed     |   " +ex.getMessage();
			Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
			//Toast.makeText(getApplicationContext(), "Error: HowTo onBackPressed.", Toast.LENGTH_LONG).show();
		}
        return;
    }
	
	 @Override
		public void onStart() {
			try{
			    super.onStart();
			    Tracker easyTracker = EasyTracker.getInstance(this);
				easyTracker.set(Fields.SCREEN_NAME, " /recentlyscanned/?=recentlyscanned");
				easyTracker.send(MapBuilder
				    .createAppView()
				    .build()
				);
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStart     |   " +e.getMessage();
				Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
			 }
		}
		 @Override
		public void onStop() {
			 try{
				 super.onStop();
				 EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStop     |   " +e.getMessage();
				 Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
			 }
		} 
	 
	 
	 @Override
		protected void onPause() 
		{
			try{
				super.onPause();
				Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(RecentlyScanned.this);
				if(appInBackgrnd){
					 Common.isAppBackgrnd = true;
				}					
			}catch (Exception e) {		
				e.printStackTrace();
				String errorMsg = className+" | onPause | " +e.getMessage();
	        	Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
			}
			
		}
	 
		@Override
		protected void onResume() {
			try{
			super.onResume();
			if(Common.isAppBackgrnd){
				new Common().storeChangeLogResultFromServer(RecentlyScanned.this);			
				Common.isAppBackgrnd = false;
			}
			}catch(Exception e){
				e.printStackTrace();
				String errorMsg = className+" | onResume     |   " +e.getMessage();
	       	 	Common.sendCrashWithAQuery(RecentlyScanned.this,errorMsg);
			}
		}

}
