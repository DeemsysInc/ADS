package com.seemoreinteractive.seemoreinteractive;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.content.Context;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.google.analytics.tracking.android.EasyTracker;
import com.quantcast.measurement.service.QuantcastClient;
import com.seemoreinteractive.seemoreinteractive.Model.ProfileModel;
import com.seemoreinteractive.seemoreinteractive.Model.SessionManager;
import com.seemoreinteractive.seemoreinteractive.Model.UserProfile;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.Utils.FileTransaction;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.library.UserListAdapter;

public class AccountSettings extends Activity {

	final Context context = this;
	SessionManager session;
	String userName="",userFName="",userLName="",userDetailId="";
	ArrayList<String> basicArrayList,contactArrayList,addressArrayList;
	ArrayList<HashMap<String, String>> basicList,contactList,addressList;
	String strFirstName="",strLastName="",strDOB="",strGender="",strEmail="",strPhone="",strCountry="",strState="",strCity="",strAddress1="",strAddress2="",strZip="",strFbid="";
	EditText eFirstName,eLastName,eDob,eGender,ePhone,eEmail,eAddress1,eAddress2,eCity,eCountry,eZip,ePassword,eConfirmPassword;
	UserListAdapter basicAdapter,contactAdapter,addressAdapter;
	static final int DATE_DIALOG_ID = 0;
	private int mYear;
	private int mMonth;
	private int mDay;
	Spinner sp;
	String[] stateArray;
	ArrayList<String> stateArrayList;
	int pos=0;
	AQuery aq;
	ImageView edit_basic,edit_contact,edit_password,edit_address;
	int editxtPadding,editxtMarginTop ,btnPaddingBottom , btnWidth , btnCancelMarLeft , btnUpdateMarLeft, 
	btnUpdateMarRight, fontSize ;   
	String className=this.getClass().getSimpleName();
	 
	@TargetApi(Build.VERSION_CODES.JELLY_BEAN)
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		try{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_account_settings);

		new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
				this, Common.sessionClientBgColor,
				Common.sessionClientBackgroundLightColor,
				Common.sessionClientBackgroundDarkColor,
				Common.sessionClientLogo, "Account Settings", "");

		ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
		imgBtnCart.setImageAlpha(0);
		//new Common().pageHeaderTitle(AccountSettings.this, "Account Settings");
		session = new SessionManager(context);
        if(session.isLoggedIn()){     	               
	        new Common().clickingOnBackButtonWithAnimation(AccountSettings.this, SettingAcitivty.class,"1");    		    	
	        aq = new AQuery(AccountSettings.this);
	        
	        //applying values to list views
	        if(Common.isNetworkAvailable(AccountSettings.this)){
	        	getUserDetailsFromServer();
	        }else{
	        	getUserDetailsFromFile();
	        }
	        fontSize = (int) ((0.034 * Common.sessionDeviceWidth)/Common.sessionDeviceDensity);
	        
	         editxtPadding = (int) (0.034 * Common.sessionDeviceWidth);
		     editxtMarginTop = (int) (0.021 * Common.sessionDeviceHeight);
		     btnPaddingBottom = (int) (0.021 * Common.sessionDeviceHeight);
		     btnWidth = (int) (0.334 * Common.sessionDeviceWidth);
		     btnCancelMarLeft = (int) (0.034 * Common.sessionDeviceWidth);
		     btnUpdateMarRight = (int) (0.034 * Common.sessionDeviceWidth);
			  
			 edit_basic = (ImageView) findViewById(R.id.edit_basic);		    	
			 edit_basic.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
						 if(Common.isNetworkAvailable(AccountSettings.this)){
							 basicEditForm();
					        }else{
					        	 new Common().instructionBox(AccountSettings.this, R.string.title_case7, R.string.instruction_case7);
					        }
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | edit_basic  |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
						}
					  
					}
			 });        				
			 
			 edit_contact = (ImageView) findViewById(R.id.edit_contact);		    	
			 edit_contact.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {	
						try{
						 if(Common.isNetworkAvailable(AccountSettings.this)){
							 contactEditForm();
					        }else{
					        	 new Common().instructionBox(AccountSettings.this, R.string.title_case7, R.string.instruction_case7);
					        }
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | edit_contact  |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
						}
					}        			  
				});			 
			 
			 edit_address = (ImageView) findViewById(R.id.edit_address);		    	
			 edit_address.setOnClickListener(new OnClickListener() {			
				@Override
				public void onClick(View v) {
					try{
						if(Common.isNetworkAvailable(AccountSettings.this)){
							addressEditForm();	
						}else{
				        	 new Common().instructionBox(AccountSettings.this, R.string.title_case7, R.string.instruction_case7);
				        }
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | edit_address  |   " +e.getMessage();
				       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);					
					}				   
				}        			  
				});
			 			 
			 edit_password = (ImageView) findViewById(R.id.edit_password);		    	
			 edit_password.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
					  if(Common.isNetworkAvailable(AccountSettings.this)){
						  	passwordEditForm();
					      }	else{
					        	 new Common().instructionBox(AccountSettings.this, R.string.title_case7, R.string.instruction_case7);
					        }
						}catch(Exception e){
							e.printStackTrace();
							String errorMsg = className+" | edit_password  |   " +e.getMessage();
					       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
						}
					}
			 });
			 			 
			RelativeLayout password_list = (RelativeLayout)findViewById(R.id.password_list);
			password_list.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View view) {
					try{
					 if(Common.isNetworkAvailable(AccountSettings.this)){
						 passwordEditForm();
					      }else{
					        	 new Common().instructionBox(AccountSettings.this, R.string.title_case7, R.string.instruction_case7);
					        }
					}catch(Exception e){
						e.printStackTrace();
						String errorMsg = className+" | password_list  |   " +e.getMessage();
				       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
					}
				}
			});
			 
	      }
		}catch(Exception e){
			e.printStackTrace();			 
			String errorMsg = className+" | onCreate  |   " +e.getMessage();
	       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
		}
        
	}
	   private void getUserDetailsFromServer() {
		   try{
				 basicArrayList = new ArrayList<String>();
			     contactArrayList = new ArrayList<String>();
			     addressArrayList = new ArrayList<String>();
	      
		        final FileTransaction file = new FileTransaction();
			    final ProfileModel getProfileModel = file.getProfile();
		        if(getProfileModel.size() >0){
		        	getProfileModel.removeAll();
		        }
	        
	       final ProfileModel profileModel =  new ProfileModel();
		   aq.ajax(Constants.userURL+"/"+Common.sessionIdForUserLoggedIn, XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try{
	        			if(xml!=null){
	        				List<XmlDom> xmlMsg = xml.tags("resultXml");
	        				for(XmlDom xmlMsg1 : xmlMsg){		        					
	        					userDetailId =xmlMsg1.text("user_details_id").toString();
	        					basicArrayList.add("Name"+","+xmlMsg1.text("user_firstname").toString()+" "+xmlMsg1.text("user_lastname").toString());
	        					strFirstName = xmlMsg1.text("user_firstname").toString();
	        					strLastName = xmlMsg1.text("user_lastname").toString();
	        					if(xmlMsg1.text("user_details_gender").toString() !=""){
	        						strGender = xmlMsg1.text("user_details_gender").toString();
	        						basicArrayList.add("Gender"+","+xmlMsg1.text("user_details_gender").toString());
	        					   }else{basicArrayList.add("Gender"+","+"-");}
	        					
	        					if(!xmlMsg1.text("user_details_dob").toString().equals("0000-00-00 00:00:00")){
	        						String date = xmlMsg1.text("user_details_dob").toString();
	        						   SimpleDateFormat sdf = new SimpleDateFormat("yyyy-mm-dd");
	        					        Date testDate = null;
	        					        try {
	        					            testDate =  sdf.parse(date);
	        					        }catch(Exception ex){
	        					            ex.printStackTrace();
	        					        }
	        					        SimpleDateFormat formatter = new SimpleDateFormat("dd/mm/yyyy");
	        					        String newFormat = formatter.format(testDate);
	        					        basicArrayList.add("Date of Birth"+","+newFormat);
	        					    	strDOB = newFormat;
	        						}else{basicArrayList.add("Date of Birth"+","+"-");}
	        					if(xmlMsg1.text("user_details_phone").toString() !=""){
	        						contactArrayList.add("Phone"+","+xmlMsg1.text("user_details_phone").toString());
	        						strPhone = xmlMsg1.text("user_details_phone").toString();
	        						}else{contactArrayList.add("Phone"+","+"-");}
	        					if(xmlMsg1.text("user_email_id").toString() !=""){
	        						contactArrayList.add("Email"+","+xmlMsg1.text("user_email_id").toString());
	        						strEmail = xmlMsg1.text("user_email_id").toString();
        						}else{contactArrayList.add("Email"+","+"-");}
	        					
	        					if(xmlMsg1.text("user_register_through").toString().equals("1")){
		        					if(xmlMsg1.text("user_details_fbid").toString() !=""){
		        						contactArrayList.add("Facebook Id"+","+xmlMsg1.text("user_details_fbid").toString());
		        						strFbid = xmlMsg1.text("user_details_fbid").toString();
	        						}else{contactArrayList.add("Facebook Id"+","+"-");}
	        					}
	        					if(xmlMsg1.text("user_details_address1").toString() !=""){
	        						addressArrayList.add("Address 1"+","+xmlMsg1.text("user_details_address1").toString());
	        						strAddress1 = xmlMsg1.text("user_details_address1").toString();
        						}else{addressArrayList.add("Address 1"+","+"-");}
	        					if(xmlMsg1.text("user_details_address2").toString() !=""){
	        						addressArrayList.add("Address 2"+","+xmlMsg1.text("user_details_address2").toString());
	        						strAddress2 = xmlMsg1.text("user_details_address2").toString();
	        						}else{addressArrayList.add("Address 2"+","+"-");}
	        					if(xmlMsg1.text("user_details_city").toString() !=""){
	        						addressArrayList.add("City"+","+xmlMsg1.text("user_details_city").toString());
	        						strCity = xmlMsg1.text("user_details_city").toString();
	        						}else{addressArrayList.add("City"+","+"-");}
	        					if(xmlMsg1.text("user_details_state").toString() !=""){
	        						addressArrayList.add("State"+","+xmlMsg1.text("user_details_state").toString());
	        						strState = xmlMsg1.text("user_details_state").toString();		        						
	        						}else{addressArrayList.add("State"+","+"-");}
	        					if(xmlMsg1.text("user_details_country").toString() !=""){
	        						addressArrayList.add("Country"+","+xmlMsg1.text("user_details_country").toString());
	        						strCountry = xmlMsg1.text("user_details_country").toString();
	        						}else{addressArrayList.add("Country"+","+"-");}
	        					if(xmlMsg1.text("user_details_zip").toString() !=""){
	        						addressArrayList.add("Zip"+","+xmlMsg1.text("user_details_zip").toString());
	        						strZip = xmlMsg1.text("user_details_zip").toString();
	        						}else{addressArrayList.add("Zip"+","+"-");}
	        					
	        					UserProfile userProfile = new UserProfile();
	        					userProfile.setId(Long.parseLong(xmlMsg1.text("user_details_id").toString()));
	        					userProfile.setFirstName(xmlMsg1.text("user_firstname").toString());
	        					userProfile.setLastname(xmlMsg1.text("user_lastname").toString());
	        					userProfile.setGender(xmlMsg1.text("user_details_gender").toString());
	        					userProfile.setDateofBirth(xmlMsg1.text("user_details_dob").toString());
	        					userProfile.setPhone(xmlMsg1.text("user_details_phone").toString());
	        					userProfile.setEmail(xmlMsg1.text("user_email_id").toString());
	        					userProfile.setAddress1(xmlMsg1.text("user_details_address1").toString());
	        					userProfile.setAddress2(xmlMsg1.text("user_details_address2").toString());
	        					userProfile.setCity(xmlMsg1.text("user_details_city").toString());
	        					userProfile.setState(xmlMsg1.text("user_details_state").toString());
	        					userProfile.setCountry(xmlMsg1.text("user_details_country").toString());
	        					userProfile.setZip(xmlMsg1.text("user_details_zip").toString());
	        					profileModel.add(userProfile);		
	        					
	        					}
	        				file.setProfile(profileModel);
	        			
	        				if(basicArrayList.size() >0)
	        				{
			        		      basicList = new ArrayList<HashMap<String,String>>();   	        
			        		        for(int i=0;i<basicArrayList.size();i++){   	    		        		        	
				        				HashMap<String, String> basicmap = new HashMap<String, String>();  
				        				String []basicArr = basicArrayList.get(i).split(",");		        				
				        				basicmap.put("bLabel", basicArr[0]);
				        				basicmap.put("bValue", basicArr[1]);  
				        				basicList.add(basicmap);      			        		
			        		        }
				        		    ListView basic_lv= (ListView) findViewById(R.id.basic_list);
				        	        basicAdapter = new UserListAdapter(AccountSettings.this, basicList, "basic");
				        	        basic_lv.setAdapter(basicAdapter);
				        	        basic_lv.setOnItemClickListener(new OnItemClickListener() {
				        				@Override
				        				public void onItemClick(AdapterView<?> arg0,
				        						View arg1, final int arg2, long arg3) {				        					
				        					 if(Common.isNetworkAvailable(AccountSettings.this)){
				        						 basicEditForm();
				    					      }	
				        				}
				        			});
			        				}
			        		        if(contactArrayList.size() >0)
			        		        {
				        				contactList = new ArrayList<HashMap<String,String>>();   	        
				        		        for(int i=0;i<contactArrayList.size();i++){				        		        	
					        				HashMap<String, String> contactmap = new HashMap<String, String>();  
					        				String []contactArr = contactArrayList.get(i).split(",");
					        				contactmap.put("cLabel", contactArr[0]);
					        				contactmap.put("cValue", contactArr[1]);  
					        				contactList.add(contactmap);
				        		        }
				        			  
				        		        ListView contact_lv= (ListView) findViewById(R.id.contact_list);
					        	        contactAdapter = new UserListAdapter(AccountSettings.this, contactList, "contact");
					        	        contact_lv.setAdapter(contactAdapter);
					        	        contact_lv.setOnItemClickListener(new OnItemClickListener() {
					        				@Override
					        				public void onItemClick(AdapterView<?> arg0,
					        						View arg1, final int arg2, long arg3) {
					        					 if(Common.isNetworkAvailable(AccountSettings.this)){
					        						 contactEditForm();
					    					      }	
					        				}
					        			});
				        		      }	
			        		          if(addressArrayList.size() >0)
				        		      {
					        				 addressList = new ArrayList<HashMap<String,String>>();   	        
					        		        for(int i=0;i<addressArrayList.size();i++){ 
						        				HashMap<String, String> addressmap = new HashMap<String, String>();  
						        				String []addressArr = addressArrayList.get(i).split(",");
						        				addressmap.put("aLabel", addressArr[0]);
						        				addressmap.put("aValue", addressArr[1]);  
						        				addressList.add(addressmap);					        			    
					        		        }						        		        
					        		       
					        		        ListView address_lv= (ListView) findViewById(R.id.address_list);
					        		        addressAdapter = new UserListAdapter(AccountSettings.this, addressList, "address");
						        	        address_lv.setAdapter(addressAdapter);
						        	        address_lv.setOnItemClickListener(new OnItemClickListener() {
						        				@Override
						        				public void onItemClick(AdapterView<?> arg0,
						        						View arg1, final int arg2, long arg3) {
						        					if(Common.isNetworkAvailable(AccountSettings.this)){
						        						 addressEditForm();
						    					      }	
						        				}
						        			});
				        		        } 
			        			}
			        			
			        		}catch(Exception e){
			        			e.printStackTrace();
			        			String errorMsg = className+" | getUserDetailsFromServer ajax call  |   " +e.getMessage();
			     		       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
			        		}
			        	}
			        	});
		   }catch(Exception e){
			   e.printStackTrace();
			   String errorMsg = className+" | getUserDetailsFromServer  |   " +e.getMessage();
		       	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
			   
		   }
	}

	   
	   private void getUserDetailsFromFile() {
		 try{
			  basicArrayList = new ArrayList<String>();
		      contactArrayList = new ArrayList<String>();
		      addressArrayList = new ArrayList<String>();
		      FileTransaction file = new FileTransaction();
		      ProfileModel profilemodel = file.getProfile();
		      if(profilemodel.size() >0){
					UserProfile profile = profilemodel.getUserProfile();
					userDetailId =""+profile.getId();
					basicArrayList.add("Name"+","+profile.getFirstName()+" "+profile.getLastname());
					strFirstName = profile.getFirstName();
					strLastName = profile.getLastname();
					if(profile.getGender() !=""){
						strGender =profile.getGender();
						basicArrayList.add("Gender"+","+profile.getGender());
					   }else{basicArrayList.add("Gender"+","+"-");}
					
					if(!profile.getDateofBirth().equals("0000-00-00 00:00:00")){
						String date = profile.getDateofBirth();
						   SimpleDateFormat sdf = new SimpleDateFormat("yyyy-mm-dd");
					        Date testDate = null;
					        try {
					            testDate =  sdf.parse(date);
					        }catch(Exception ex){
					            ex.printStackTrace();
					        }
					        SimpleDateFormat formatter = new SimpleDateFormat("dd/mm/yyyy");
					        String newFormat = formatter.format(testDate);
					        basicArrayList.add("Date of Birth"+","+newFormat);
					    	strDOB = newFormat;
						}else{basicArrayList.add("Date of Birth"+","+"-");}
					if(profile.getPhone()!=""){
						contactArrayList.add("Phone"+","+profile.getPhone());
						strPhone = profile.getPhone();
						}else{contactArrayList.add("Phone"+","+"-");}
					if(profile.getEmail() !=""){
						contactArrayList.add("Email"+","+profile.getEmail());
						strEmail = profile.getEmail();
						}else{contactArrayList.add("Email"+","+"-");}
					if(profile.getAddress1() !=""){
						addressArrayList.add("Address 1"+","+profile.getAddress1());
						strAddress1 = profile.getAddress1();
						}else{addressArrayList.add("Address 1"+","+"-");}
					if(profile.getAddress2() !=""){
						addressArrayList.add("Address 2"+","+(profile.getAddress2()));
						strAddress2 =profile.getAddress2();
						}else{addressArrayList.add("Address 2"+","+"-");}
					if(profile.getCity()  !=""){
						addressArrayList.add("City"+","+profile.getCity());
						strCity = profile.getCity();
						}else{addressArrayList.add("City"+","+"-");}
					if(profile.getState() !=""){
						addressArrayList.add("State"+","+profile.getState() );
						strState = profile.getState() ;		        						
						}else{addressArrayList.add("State"+","+"-");}
					if(profile.getCountry() !=""){
						addressArrayList.add("Country"+","+profile.getCountry() );
						strCountry = profile.getCountry() ;
						}else{addressArrayList.add("Country"+","+"-");}
					if(profile.getZip()  !=""){
						addressArrayList.add("Zip"+","+profile.getZip());
						strZip = profile.getZip();
						}else{addressArrayList.add("Zip"+","+"-");}
					
					if(basicArrayList.size() >0)
    				{
	        		      basicList = new ArrayList<HashMap<String,String>>();   	        
	        		        for(int i=0;i<basicArrayList.size();i++){   	    		        		        	
		        				HashMap<String, String> basicmap = new HashMap<String, String>();  
		        				String []basicArr = basicArrayList.get(i).split(",");		        				
		        				basicmap.put("bLabel", basicArr[0]);
		        				if(basicArr[1].length() > 1)
		        					basicmap.put("bValue", basicArr[1]);  
		        				else
		        					basicmap.put("bValue", "");  
		        				basicList.add(basicmap);      			        		
	        		        }
		        			
		        		    ListView basic_lv= (ListView) findViewById(R.id.basic_list);
		        	        basicAdapter = new UserListAdapter(AccountSettings.this, basicList, "basic");
		        	        basic_lv.setAdapter(basicAdapter);		        	        
	        				}
	        		        if(contactArrayList.size() >0)
	        		        {
		        				contactList = new ArrayList<HashMap<String,String>>();   	        
		        		        for(int i=0;i<contactArrayList.size();i++){				        		        	
			        				HashMap<String, String> contactmap = new HashMap<String, String>();  
			        				String []contactArr = contactArrayList.get(i).split(",");
			        				
			        				contactmap.put("cLabel", contactArr[0]);
			        				if(contactArr.length >1)
			        					contactmap.put("cValue", contactArr[1]);  
			        				else
			        					contactmap.put("cValue", "");  
			        				contactList.add(contactmap);
		        		        }
		        			     
		        		        ListView contact_lv= (ListView) findViewById(R.id.contact_list);
			        	        contactAdapter = new UserListAdapter(AccountSettings.this, contactList, "contact");
			        	        contact_lv.setAdapter(contactAdapter);
			        	       
		        		      }	
	        		          if(addressArrayList.size() >0)
		        		      {
			        				 addressList = new ArrayList<HashMap<String,String>>();   	        
			        		        for(int i=0;i<addressArrayList.size();i++){ 
				        				HashMap<String, String> addressmap = new HashMap<String, String>();  
				        				String []addressArr = addressArrayList.get(i).split(",");
				        				Log.e("addressArr",""+addressArr);
				        				addressmap.put("aLabel", addressArr[0]);
				        				if(addressArr.length >1)
				        					addressmap.put("aValue", addressArr[1]);  
				        				else
				        					addressmap.put("aValue", "");
				        				addressList.add(addressmap);					        			    
			        		        }						        		        
			        		       ListView address_lv= (ListView) findViewById(R.id.address_list);
			        		        addressAdapter = new UserListAdapter(AccountSettings.this, addressList, "address");
				        	        address_lv.setAdapter(addressAdapter);
				        	        
		        		        } 
	        			}
	        		
			   }catch(Exception e){
				   e.printStackTrace();
			   }
		}

	// updates the date in the TextView
    private void updateDisplay() {
    	try{
    	eDob.setText(getString(R.string.strSelectedDate,
            new StringBuilder()
                    // Month is 0 based so add 1
                    //.append(mMonth + 1).append("/")
                    //.append(mDay).append("/")
    	 			.append(mDay<10?"0"+mDay:mDay).append("/")
			    	.append(mMonth<10?(mMonth+1):mMonth + 1).append("/")		       
                    .append(mYear).append("")));
    	
    	}catch(Exception e){
    		e.printStackTrace();
    	}
    }

    // the callback received when the user "sets" the date in the dialog
    private DatePickerDialog.OnDateSetListener mDateSetListener =
            new DatePickerDialog.OnDateSetListener() {

                @Override
				public void onDateSet(DatePicker view, int year, 
                                      int monthOfYear, int dayOfMonth) {
                    mYear = year;
                    mMonth = monthOfYear;
                    mDay = dayOfMonth;
                    updateDisplay();
                }
            };
            
	@Override
	protected Dialog onCreateDialog(int id) {
		switch (id) {
		case DATE_DIALOG_ID:
			return new DatePickerDialog(this, mDateSetListener, mYear, mMonth,
					mDay);
		}
		return null;
	}
	
	public void basicEditForm(){
		
		try{
		    edit_basic.setEnabled(false);
		    ListView basic_lv= (ListView) findViewById(R.id.basic_list);
		    basic_lv.setVisibility(View.GONE);
		   
		    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.basic_layout);
		    RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		    params.addRule(RelativeLayout.ALIGN_PARENT_LEFT, RelativeLayout.TRUE);
		    params.addRule(RelativeLayout.BELOW, R.id.basic_div);
		    params.addRule(RelativeLayout.ABOVE, R.id.contact_info);
		    
		    View mBasicView = View.inflate(AccountSettings.this, R.layout.listview_basicform, null);
		    mRelativeLayout.addView(mBasicView, params);
		    
		    RelativeLayout mRelativeForm = (RelativeLayout)findViewById(R.id.basic_form);
		    mRelativeForm.setPadding(editxtPadding, editxtPadding, editxtPadding, btnPaddingBottom);
		    
		     eFirstName = (EditText) mBasicView.findViewById(R.id.etxtFirstName);
		     eLastName = (EditText) mBasicView.findViewById(R.id.etxtLastName);
		     eDob = (EditText) mBasicView.findViewById(R.id.etxtDOB); 
		     eGender = (EditText) mBasicView.findViewById(R.id.etxtGender);
		     
		     RelativeLayout.LayoutParams rlpFirstName = (RelativeLayout.LayoutParams) eFirstName.getLayoutParams();
		     rlpFirstName.topMargin = editxtMarginTop;
		     eFirstName.setLayoutParams(rlpFirstName);
		     eFirstName.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpLastName = (RelativeLayout.LayoutParams) eLastName.getLayoutParams();
		     rlpLastName.topMargin = editxtMarginTop;
		     eLastName.setLayoutParams(rlpLastName);
		     eLastName.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpGender = (RelativeLayout.LayoutParams) eGender.getLayoutParams();
		     rlpGender.topMargin = editxtMarginTop;
		     eGender.setLayoutParams(rlpGender);
		     eGender.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpDOB = (RelativeLayout.LayoutParams) eDob.getLayoutParams();
		     rlpDOB.topMargin = editxtMarginTop;
		     eDob.setLayoutParams(rlpDOB);
		     eDob.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     
		     eFirstName.setText(strFirstName);
			 eLastName.setText(strLastName);
			 eGender.setText(strGender);	 
			 eDob.setText(strDOB);
			 eDob.setOnTouchListener(new OnTouchListener() {       	    			
				@Override
				public boolean onTouch(View arg0, MotionEvent arg1) {
					// TODO Auto-generated method stub
					//showDialog(DATE_DIALOG_ID);
					try{
			           /* DatePickerDialog dpd = new DatePickerDialog(AccountSettings.this, mDateSetListener, mYear, mMonth,mDay);
			            dpd.show();*/
						showDialog(DATE_DIALOG_ID);
					}catch(Exception e){
						e.printStackTrace();
					}
  				return false;
				}
  		});        	    		
	        // get the current date
	        final Calendar c = Calendar.getInstance();
	        mYear = c.get(Calendar.YEAR);
	        mMonth = c.get(Calendar.MONTH);
	        mDay = c.get(Calendar.DAY_OF_MONTH);
		    
		    Button btnCancel = (Button) mBasicView.findViewById(R.id.btnCancel);
		    RelativeLayout.LayoutParams rlpCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
		    rlpCancel.topMargin = editxtMarginTop;
		    rlpCancel.leftMargin = btnCancelMarLeft;
		    rlpCancel.width =btnWidth;
		    btnCancel.setLayoutParams(rlpCancel);
		    btnCancel.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		    btnCancel.setTextSize(fontSize);
			btnCancel.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						edit_basic.setEnabled(true);
						 	ListView basic_lv= (ListView) findViewById(R.id.basic_list);
	        			    basic_lv.setVisibility(View.VISIBLE);
	        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.basic_form);
	        			   //mRelativeLayout.setVisibility(View.GONE);
	        			    
	        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.basic_layout);
	        			    mRelativeLayout.removeView(bRelativeLayout);
					}
			 });
			 
			  Button btnUpdate = (Button) mBasicView.findViewById(R.id.btnUpdate);
			  RelativeLayout.LayoutParams rlpUpdate = (RelativeLayout.LayoutParams) btnUpdate.getLayoutParams();
			
			  rlpUpdate.rightMargin = btnUpdateMarRight;
			  rlpUpdate.width =btnWidth;
			  btnUpdate.setLayoutParams(rlpUpdate);
			  btnUpdate.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		      btnUpdate.setTextSize(fontSize);
			  btnUpdate.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
				
	        			   if(eFirstName.getText().toString().equals("")){
	        				  eFirstName.setError("Please enter firstname.");
	        				  eFirstName.requestFocus();
       						Toast.makeText(AccountSettings.this, "Please enter firstname.", Toast.LENGTH_LONG).show();
       						return;
       					}
       					if(eLastName.getText().toString().equals("")){
       						eLastName.setError("Please enter lastname.");
       						eLastName.requestFocus();
       						Toast.makeText(AccountSettings.this, "Please enter lastname.", Toast.LENGTH_LONG).show();
       						return;
       					}
       					if(!eDob.getText().toString().equals("")){
       						SimpleDateFormat sdf = new SimpleDateFormat("dd/mm/yyyy");
    				        Date givenDate = null;
    				        Date currentDate = new Date();
    				        try {
    				        	givenDate =  sdf.parse(eDob.getText().toString());
    				        	currentDate =  sdf.parse(sdf.format(currentDate));
    				        	 Calendar a = getCalendar(givenDate);
     						    Calendar b = getCalendar(currentDate);
     						    int diff = b.get(Calendar.YEAR) - a.get(Calendar.YEAR);
     						    if (a.get(Calendar.MONTH) > b.get(Calendar.MONTH) || 
     						        (a.get(Calendar.MONTH) == b.get(Calendar.MONTH) && a.get(Calendar.DATE) > b.get(Calendar.DATE))) {
     						        diff--;
     						    }Log.i("diff",""+diff);
     						    if(diff <= 17){	     							   
     							  eDob.setError("You are not eligible to use this app.");
     							  eDob.requestFocus();
           						  Toast.makeText(AccountSettings.this, "You are not eligible to use this app.", Toast.LENGTH_LONG).show();
           						  return;
         				      }else{
         				    	 final Map<String, String> regParams = new HashMap<String, String>();
            						regParams.put("user_id", ""+Common.sessionIdForUserLoggedIn);
            						regParams.put("first_name", eFirstName.getText().toString());
            						regParams.put("last_name", eLastName.getText().toString());
            						regParams.put("user_details_gender", eGender.getText().toString());
            						regParams.put("user_details_dob", eDob.getText().toString());									
            						

            				        aq = new AQuery(AccountSettings.this);
            				        aq.ajax(Constants.updateUserURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
            				        	@Override
            							public void callback(String url, XmlDom xml, AjaxStatus status) {
            				        		try{
            				        			if(xml!=null){
            				        				Log.i("callback",""+status);
            				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
            				        				for(XmlDom xmlMsg1 : xmlMsg){
            				        					
            				        					Log.i("msg",xmlMsg1.text("msg").toString());
            				        					if(xmlMsg1.text("msg").toString().equals("success")){
            					        					//Toast.makeText(activityThis, "Successfully registered.", Toast.LENGTH_LONG).show();
            				        						
            				        						edit_basic.setEnabled(true);
            				        						getUserDetailsFromServer();
            		         							 	ListView basic_lv= (ListView) findViewById(R.id.basic_list);
            		         		        			    basic_lv.setVisibility(View.VISIBLE); 	        		         		        			    
            		         		        				
            		         		        				RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.basic_form);
            		         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.basic_layout);
            		         		        			    mRelativeLayout.removeView(bRelativeLayout);
            		         		        			    
            					        				} else {
            												Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
            											}
            				        				}
            				        			}
            				        		} catch(Exception e){
            				        			e.printStackTrace();
            				        		}
            				        	}
            				        });
         				      }
     						  
    				        }catch(Exception ex){
    				            ex.printStackTrace();
    				        }
    				        return;
           					
       					} 				
       					
       					else{
       						
       						final Map<String, String> regParams = new HashMap<String, String>();
       						regParams.put("user_id", ""+Common.sessionIdForUserLoggedIn);
       						regParams.put("first_name", eFirstName.getText().toString());
       						regParams.put("last_name", eLastName.getText().toString());
       						regParams.put("user_details_gender", eGender.getText().toString());
       						regParams.put("user_details_dob", eDob.getText().toString());	
       						
       				        aq = new AQuery(AccountSettings.this);
       				        aq.ajax(Constants.updateUserURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
       				        	@Override
       							public void callback(String url, XmlDom xml, AjaxStatus status) {
       				        		try{
       				        			if(xml!=null){
       				        				Log.i("callback",""+status);
       				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
       				        				for(XmlDom xmlMsg1 : xmlMsg){       				        					
       				        					if(xmlMsg1.text("msg").toString().equals("success")){       					        					
       				        						edit_basic.setEnabled(true);
       				        						getUserDetailsFromServer();
       		         							 	ListView basic_lv= (ListView) findViewById(R.id.basic_list);
       		         		        			    basic_lv.setVisibility(View.VISIBLE); 	        		         		        			    
       		         		        				
       		         		        				RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.basic_form);
       		         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.basic_layout);
       		         		        			    mRelativeLayout.removeView(bRelativeLayout);
       		         		        			    
       					        				} else {
       												Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
       											}
       				        				}
       				        			}
       				        		} catch(Exception e){
       				        			e.printStackTrace();
       				        		}
       				        	}
       				        });       			   
					   }
					}
			 });
			  }catch(Exception e){
				  e.printStackTrace();
			  }
		
		}
		
	public void contactEditForm(){		
		try{
		   edit_contact.setEnabled(false);
		   ListView contact_lv= (ListView) findViewById(R.id.contact_list);
		   contact_lv.setVisibility(View.GONE);       			         			    
		   
		    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.contact_layout);
		    RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		    params.addRule(RelativeLayout.ALIGN_PARENT_LEFT, RelativeLayout.TRUE);
		    params.addRule(RelativeLayout.BELOW, R.id.contact_div);
		    params.addRule(RelativeLayout.ABOVE, R.id.address_info);
		    
		    View mContactView = View.inflate(AccountSettings.this, R.layout.listview_contactform, null);
		    mRelativeLayout.addView(mContactView, params);
		    
		    RelativeLayout mRelativeForm = (RelativeLayout)findViewById(R.id.contact_form);
		    mRelativeForm.setPadding(editxtPadding, editxtPadding, editxtPadding, btnPaddingBottom);
		    
		    
		     ePhone = (EditText) mContactView.findViewById(R.id.etxtPhone);
		     eEmail = (EditText) mContactView.findViewById(R.id.etxtEmail);
		     ePhone.setText(strPhone);
		     eEmail.setText(strEmail);       			     
		     
		     
		     RelativeLayout.LayoutParams rlpPhone = (RelativeLayout.LayoutParams) ePhone.getLayoutParams();
		     rlpPhone.topMargin = editxtMarginTop;
		     ePhone.setLayoutParams(rlpPhone);
		     ePhone.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		             			     
		     RelativeLayout.LayoutParams rlpEmail = (RelativeLayout.LayoutParams) eEmail.getLayoutParams();
		     rlpEmail.topMargin = editxtMarginTop;
		     eEmail.setLayoutParams(rlpEmail);
		     eEmail.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);        			     
			 	        			    
			 Button btnCancel = (Button) mContactView.findViewById(R.id.btnCancel);
			 RelativeLayout.LayoutParams rlpCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
		     rlpCancel.topMargin = editxtMarginTop;
		     rlpCancel.leftMargin = btnCancelMarLeft;
		     rlpCancel.width =btnWidth;
		     btnCancel.setLayoutParams(rlpCancel);
		     btnCancel.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     btnCancel.setTextSize(fontSize);
			    
			 btnCancel.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						    edit_contact.setEnabled(true);
						 	ListView contact_lv= (ListView) findViewById(R.id.contact_list);
						 	contact_lv.setVisibility(View.VISIBLE);
	        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.contact_form);
	        			    
	        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.contact_layout);
	        			    mRelativeLayout.removeView(bRelativeLayout);
					}
			 });
			 Button btnUpdate = (Button) mContactView.findViewById(R.id.btnUpdate);
			 RelativeLayout.LayoutParams rlpUpdate = (RelativeLayout.LayoutParams) btnUpdate.getLayoutParams();

			 rlpUpdate.rightMargin = btnUpdateMarRight;
			 rlpUpdate.width =btnWidth;
			 btnUpdate.setLayoutParams(rlpUpdate);
			 btnUpdate.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     btnUpdate.setTextSize(fontSize);
		  	 btnUpdate.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						final Map<String, String> regParams = new HashMap<String, String>();
     					regParams.put("user_id", ""+Common.sessionIdForUserLoggedIn);
     					regParams.put("user_details_phone", ePhone.getText().toString());
     					regParams.put("email", eEmail.getText().toString());	
     					if(eEmail.getText().toString().equals("")){
     						eEmail.setError("Please enter email id.");
     						eEmail.requestFocus();
     						Toast.makeText(getApplicationContext(), "Please enter email id.", Toast.LENGTH_LONG).show();
     						return;
     					}
     					String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
     				   
     					if(!(eEmail.getText().toString().matches(emailPattern)))
     					{
     						eEmail.setError("Invalid email id!");
     						eEmail.requestFocus();
     						Toast.makeText(getApplicationContext(), "Invalid email id!", Toast.LENGTH_LONG).show();
     						return;
     					}else{

     				       aq = new AQuery(AccountSettings.this);
     				        aq.ajax(Constants.updateUserURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
     				        	@Override
     							public void callback(String url, XmlDom xml, AjaxStatus status) {
     				        		try{
     				        			if(xml!=null){
     				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
     				        				for(XmlDom xmlMsg1 : xmlMsg){
     				        					
     				        					Log.i("msg",xmlMsg1.text("msg").toString());
     				        					if(xmlMsg1.text("msg").toString().equals("success")){
     					        					//Toast.makeText(activityThis, "Successfully registered.", Toast.LENGTH_LONG).show();
     				        						
     				        						edit_contact.setEnabled(true);
     				        						getUserDetailsFromServer();
     		         							 	ListView conatct_lv= (ListView) findViewById(R.id.contact_list);
     		         							 	conatct_lv.setVisibility(View.VISIBLE);
     		         		        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.contact_form);
     		         		        			   //mRelativeLayout.setVisibility(View.GONE);
     		         		        			    
     		         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.contact_layout);
     		         		        			    mRelativeLayout.removeView(bRelativeLayout);
     		         		        			    
     					        				} else {
     												Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
     											}
     				        				}
     				        			}
     				        		} catch(Exception e){
     				        			e.printStackTrace();
     				        		}
     				        	}
     				        });
     			   
     					}
					}
			 });
		}catch(Exception e){
			e.printStackTrace();
		}
		   }
	
	public void addressEditForm(){
		try{
			 edit_address.setEnabled(false);
			 ListView address_lv= (ListView) findViewById(R.id.address_list);
			 address_lv.setVisibility(View.GONE);			         			    
		   
		     RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.address_layout);
		     RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		     params.addRule(RelativeLayout.ALIGN_PARENT_LEFT, RelativeLayout.TRUE);
		     params.addRule(RelativeLayout.BELOW, R.id.address_div);
		    
		     View mAddressView = View.inflate(AccountSettings.this, R.layout.listview_addressform, null);
		     mRelativeLayout.addView(mAddressView, params);
		    
		     RelativeLayout mRelativeForm = (RelativeLayout)findViewById(R.id.address_form);
		     mRelativeForm.setPadding(editxtPadding, editxtPadding, editxtPadding, btnPaddingBottom);
		    
		    
		     eAddress1 = (EditText) mAddressView.findViewById(R.id.etxtAddress1);
		     eAddress2 = (EditText) mAddressView.findViewById(R.id.etxtAddress2);
		     eCity = (EditText) mAddressView.findViewById(R.id.etxtCity);
		     eCountry = (EditText) mAddressView.findViewById(R.id.etxtCountry);
		     eZip = (EditText) mAddressView.findViewById(R.id.etxtZip);
		     sp = (Spinner) findViewById(R.id.etxtState);
		     
		     RelativeLayout.LayoutParams rlpAddress1 = (RelativeLayout.LayoutParams) eAddress1.getLayoutParams();
		     rlpAddress1.topMargin = editxtMarginTop;
		     eAddress1.setLayoutParams(rlpAddress1);
		     eAddress1.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     
		     RelativeLayout.LayoutParams rlpAddress2 = (RelativeLayout.LayoutParams) eAddress2.getLayoutParams();
		     rlpAddress2.topMargin = editxtMarginTop;
		     eAddress2.setLayoutParams(rlpAddress2);
		     eAddress2.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpCity = (RelativeLayout.LayoutParams) eCity.getLayoutParams();
		     rlpCity.topMargin = editxtMarginTop;
		     eCity.setLayoutParams(rlpCity);
		     eCity.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpCountry = (RelativeLayout.LayoutParams) eCountry.getLayoutParams();
		     rlpCountry.topMargin = editxtMarginTop;
		     eCountry.setLayoutParams(rlpCountry);
		     eCountry.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     
		     RelativeLayout.LayoutParams rlpZip = (RelativeLayout.LayoutParams) eZip.getLayoutParams();
		     rlpZip.topMargin = editxtMarginTop;
		     eZip.setLayoutParams(rlpZip);
		     eZip.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpState = (RelativeLayout.LayoutParams) sp.getLayoutParams();
		     rlpState.topMargin = editxtMarginTop;
		     sp.setLayoutParams(rlpState);
		     sp.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     		     
		     stateArrayList = new ArrayList<String>();
			 aq.ajax(Constants.stateURL,  XmlDom.class, new AjaxCallback<XmlDom>(){
	        	@Override
				public void callback(String url, XmlDom xml, AjaxStatus status) {
	        		try{
	        			int i=0;
	        			if(xml!=null){
	        				List<XmlDom> xmlMsg = xml.tags("resultXml");
	        				for(XmlDom xmlMsg1 : xmlMsg){				        					
	        					stateArrayList.add(xmlMsg1.text("state_code").toString());
	        					 if(strState.equals(xmlMsg1.text("state_code").toString())){
						            	pos=i;
						            }
	        					i++;				        					
	        					}
	        				if(xmlMsg.size() ==stateArrayList.size()){
							 stateArray = stateArrayList.toArray(new String[stateArrayList.size()]);										 
				             ArrayAdapter<String> adapter = 
				                new ArrayAdapter<String> (AccountSettings.this, android.R.layout.simple_spinner_item, stateArray);      
				            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
				            sp.setAdapter(adapter);
				            sp.setSelection(pos);								          
	        			}
	        			}
	        		}catch(Exception e){
	        			e.printStackTrace();
	        		}
	        	}
			 });
		     eAddress1.setText(strAddress1);
		     eAddress2.setText(strAddress2);
		     eCity.setText(strCity);
		     eCountry.setText(strCountry);
		     eZip.setText(strZip);
			 	        			    
			 Button btnCancel = (Button) mAddressView.findViewById(R.id.btnCancel);
			 RelativeLayout.LayoutParams rlpCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
		     rlpCancel.topMargin = editxtMarginTop;
		     rlpCancel.leftMargin = btnCancelMarLeft;
		     rlpCancel.width =btnWidth;
		     btnCancel.setLayoutParams(rlpCancel);
		     btnCancel.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     btnCancel.setTextSize(fontSize);
			 btnCancel.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
						    edit_address.setEnabled(true);
						 	ListView address_lv= (ListView) findViewById(R.id.address_list);
						 	address_lv.setVisibility(View.VISIBLE);
	        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.address_form);
	        			   //mRelativeLayout.setVisibility(View.GONE);
	        			    
	        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.address_layout);
	        			    mRelativeLayout.removeView(bRelativeLayout);
						}catch(Exception e){
							e.printStackTrace();
						}
					}
			 });
			 
			 Button btnUpdate = (Button) mAddressView.findViewById(R.id.btnUpdate);
			 RelativeLayout.LayoutParams rlpUpdate = (RelativeLayout.LayoutParams) btnUpdate.getLayoutParams();
			  	// rlpUpdate.topMargin = editxtMarginTop;
			  	rlpUpdate.rightMargin = btnUpdateMarRight;
			  	 rlpUpdate.width =btnWidth;
			  	 btnUpdate.setLayoutParams(rlpUpdate);
			  	 btnUpdate.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
			     btnUpdate.setTextSize(fontSize);
		  	     btnUpdate.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
						final Map<String, String> regParams = new HashMap<String, String>();
    					regParams.put("user_id", ""+Common.sessionIdForUserLoggedIn);
    					regParams.put("user_details_address1", eAddress1.getText().toString());
    					regParams.put("user_details_address2", eAddress2.getText().toString());	
    					regParams.put("user_details_city", eCity.getText().toString());
    					regParams.put("user_details_state", ""+sp.getSelectedItem());
    					regParams.put("user_details_country", eCountry.getText().toString());
    					regParams.put("user_details_zip", eZip.getText().toString());
					    aq = new AQuery(AccountSettings.this);
				        aq.ajax(Constants.updateUserURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
				        	@Override
							public void callback(String url, XmlDom xml, AjaxStatus status) {
				        		try{
				        			if(xml!=null){
				        				List<XmlDom> xmlMsg = xml.tags("resultXml");
				        				for(XmlDom xmlMsg1 : xmlMsg){
				        					
				        					Log.i("msg",xmlMsg1.text("msg").toString());
				        					if(xmlMsg1.text("msg").toString().equals("success")){
					        					
				        						edit_address.setEnabled(true);
				        						getUserDetailsFromServer();
		         							 	ListView address_lv= (ListView) findViewById(R.id.address_list);
		         							 	address_lv.setVisibility(View.VISIBLE);
		         		        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.address_form);
		         		        			   //mRelativeLayout.setVisibility(View.GONE);
		         		        			    
		         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.address_layout);
		         		        			    mRelativeLayout.removeView(bRelativeLayout);
		         		        			    
					        				} else {
												Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
											}
				        				}
				        			}
				        		} catch(Exception e){
				        			e.printStackTrace();
				        		}
				        	}
				        });
						} catch(Exception e){
		        			e.printStackTrace();
		        		}
			   
					}
				
		 });
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	public void passwordEditForm(){		
		try{		
		   edit_password.setEnabled(false);
		   RelativeLayout password_lv= (RelativeLayout) findViewById(R.id.password_list);
		   password_lv.setVisibility(View.GONE);
		   
		    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.password_layout);
		    RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		    params.addRule(RelativeLayout.ALIGN_PARENT_LEFT, RelativeLayout.TRUE);
		    params.addRule(RelativeLayout.BELOW, R.id.password_div);
		    
		    View mPasswordView = View.inflate(AccountSettings.this, R.layout.listview_passwordform, null);
		    mRelativeLayout.addView(mPasswordView, params);
		    
		    RelativeLayout mRelativeForm = (RelativeLayout)findViewById(R.id.password_form);
		    mRelativeForm.setPadding(editxtPadding, editxtPadding, editxtPadding, btnPaddingBottom);
		    
		    
		    ePassword = (EditText) mPasswordView.findViewById(R.id.etxtPassword);
		    eConfirmPassword = (EditText) mPasswordView.findViewById(R.id.etxtConfirmPassword);
		        
		     RelativeLayout.LayoutParams rlpPassword = (RelativeLayout.LayoutParams) ePassword.getLayoutParams();
		     rlpPassword.topMargin = editxtMarginTop;
		     ePassword.setLayoutParams(rlpPassword);
		     ePassword.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     
		     RelativeLayout.LayoutParams rlpConfirmPassword = (RelativeLayout.LayoutParams) eConfirmPassword.getLayoutParams();
		     rlpConfirmPassword.topMargin = editxtMarginTop;
			     eConfirmPassword.setLayoutParams(rlpConfirmPassword);
			     eConfirmPassword.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
	     
		    Button btnCancel = (Button) mPasswordView.findViewById(R.id.btnCancel);
		    RelativeLayout.LayoutParams rlpCancel = (RelativeLayout.LayoutParams) btnCancel.getLayoutParams();
			    rlpCancel.topMargin = editxtMarginTop;
			    rlpCancel.leftMargin = btnCancelMarLeft;
			    rlpCancel.width =btnWidth;
			    btnCancel.setLayoutParams(rlpCancel);
			    btnCancel.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
			    btnCancel.setTextSize(fontSize);
			    btnCancel.setOnClickListener(new OnClickListener() {			
					@Override
					public void onClick(View v) {
						try{
							edit_password.setEnabled(true);
	 					    RelativeLayout password_lv= (RelativeLayout) findViewById(R.id.password_list);
	        			    password_lv.setVisibility(View.VISIBLE);
	        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.password_form);
	        			        		        			    
	        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.password_layout);
	        			    mRelativeLayout.removeView(bRelativeLayout);
						}catch(Exception e){
							e.printStackTrace();
						}
					}
			 });
			 
			 Button btnUpdate = (Button) mPasswordView.findViewById(R.id.btnUpdate);
			 RelativeLayout.LayoutParams rlpUpdate = (RelativeLayout.LayoutParams) btnUpdate.getLayoutParams();
		  	 //rlpUpdate.topMargin = editxtMarginTop;
		  	 rlpUpdate.rightMargin = btnUpdateMarRight;
		  	 rlpUpdate.width =btnWidth;
		  	 btnUpdate.setLayoutParams(rlpUpdate);
		  	 btnUpdate.setPadding(editxtPadding, editxtPadding, editxtPadding, editxtPadding);
		     btnUpdate.setTextSize(fontSize);
			 btnUpdate.setOnClickListener(new OnClickListener() {			
				 @Override
				 public void onClick(View v) {
					try{
						if(ePassword.getText().toString().equals("")){
							ePassword.setError("Please enter password.");
							ePassword.requestFocus();
						 Toast.makeText(getApplicationContext(), "Please enter password.", Toast.LENGTH_LONG).show();					
						 return;
					    }
						if(ePassword.getText().toString().length()<4){
							ePassword.setError("Password should not be less than 4 characters.");
							ePassword.requestFocus();
							Toast.makeText(getApplicationContext(), "Password should not be less than 4 characters.", Toast.LENGTH_LONG).show();
							
							return;
						}
					   if(eConfirmPassword.getText().toString().equals("")){
						eConfirmPassword.setError("Please enter confirm password.");
						eConfirmPassword.requestFocus();
						Toast.makeText(getApplicationContext(), "Please enter confirm password.", Toast.LENGTH_LONG).show();						
						return;
					   }
					if(!ePassword.getText().toString().equals(eConfirmPassword.getText().toString())){
						eConfirmPassword.setError("Both passwords are not same!");
						eConfirmPassword.requestFocus();
						Toast.makeText(getApplicationContext(), "Both passwords are not same!", Toast.LENGTH_LONG).show();
						return;
					}else{
						
	  					final Map<String, String> regParams = new HashMap<String, String>();
						regParams.put("user_id", ""+Common.sessionIdForUserLoggedIn);
						regParams.put("password", ePassword.getText().toString());   	        						
					    aq = new AQuery(AccountSettings.this);
					    aq.ajax(Constants.updateUserURL, regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
					        	@Override
								public void callback(String url, XmlDom xml, AjaxStatus status) {
					        		try{
					        			if(xml!=null){
					        				Log.i("callback",""+status);
					        				List<XmlDom> xmlMsg = xml.tags("resultXml");
					        				for(XmlDom xmlMsg1 : xmlMsg){
					        					
					        					Log.i("msg",xmlMsg1.text("msg").toString());
					        					if(xmlMsg1.text("msg").toString().equals("success")){
						        					//Toast.makeText(activityThis, "Successfully registered.", Toast.LENGTH_LONG).show();
					        						
					        						edit_password.setEnabled(true);
					        						//getUserDetailsFromServer();
	
							       					RelativeLayout password_lv= (RelativeLayout) findViewById(R.id.password_list);
							              			password_lv.setVisibility(View.VISIBLE);
			         		        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.password_form);
			         		        			   //mRelativeLayout.setVisibility(View.GONE);
			         		        			    
			         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.password_layout);
			         		        			    mRelativeLayout.removeView(bRelativeLayout);
			         		        			    
						        				} else {
													Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
												}
					        				}
					        			}
					        		} catch(Exception e){
					        			e.printStackTrace();
					        		}
					        	}
					       	});	    
						    Log.e("updateUserURL 2", Constants.Live_Url+"mobileapps/android/user_details/update/");
						    aq.ajax(Constants.Live_Url+"mobileapps/android/user_details/update/", regParams, XmlDom.class, new AjaxCallback<XmlDom>(){
					        	@Override
								public void callback(String url, XmlDom xml, AjaxStatus status) {
					        		try{
					        			if(xml!=null){
					        				Log.i("callback",""+status);
					        				List<XmlDom> xmlMsg = xml.tags("resultXml");
					        				for(XmlDom xmlMsg1 : xmlMsg){
					        					
					        					Log.e("msg 2",xmlMsg1.text("msg").toString());
					        					if(xmlMsg1.text("msg").toString().equals("success")){
						        					//Toast.makeText(activityThis, "Successfully registered.", Toast.LENGTH_LONG).show();
					        						
					        						edit_password.setEnabled(true);
					        						getUserDetailsFromServer();
		
							       					RelativeLayout password_lv= (RelativeLayout) findViewById(R.id.password_list);
							              			password_lv.setVisibility(View.VISIBLE);
			         		        			    RelativeLayout bRelativeLayout = (RelativeLayout)findViewById(R.id.password_form);
			         		        			   //mRelativeLayout.setVisibility(View.GONE);
			         		        			    
			         		        			    RelativeLayout mRelativeLayout = (RelativeLayout)findViewById(R.id.password_layout);
			         		        			    mRelativeLayout.removeView(bRelativeLayout);
			         		        			    
						        				} else {
													Toast.makeText(AccountSettings.this, " Failed. Please try agian!", Toast.LENGTH_LONG).show();
												}
					        				}
					        			}
					        		} catch(Exception e){
					        			e.printStackTrace();
					        		}
					        	}
					       	});	        			   
						}
					} catch(Exception e){
	        			e.printStackTrace();
	        		}
				}
		 	});
		}catch(Exception e){
			e.printStackTrace();
			String errorMsg = className+" | passwordEditForm ajax call  |   " +e.getMessage();
		    Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
		}
	}
	
	public static Calendar getCalendar(Date date) {
	    Calendar cal = Calendar.getInstance(Locale.US);
	    cal.setTime(date);
	    return cal;
	}
	
	 @Override
		public void onStart() {
			 try{			 
				 super.onStart();
				 EasyTracker.getInstance(this).activityStart(this);  // Add this method.
				 String[] segments = new String[1];
				 segments[0] = "User Profile"; 
				 QuantcastClient.activityStart(this, Constants.Quantcast_Key, Common.sessionIdForUserAnalytics,segments);
			 }catch(Exception e){
				 e.printStackTrace();
				 String errorMsg = className+" | onStart      |   " +e.getMessage();
				 Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
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
				 String errorMsg = className+" | onStop      |   " +e.getMessage();
				 Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
			 }
		}
		 
		 @Override
			protected void onPause() 
			{
				try{
					super.onPause();
					Boolean appInBackgrnd = new Common().isApplicationBroughtToBackground(AccountSettings.this);
					if(appInBackgrnd){
						 Common.isAppBackgrnd = true;
					}					
				}catch (Exception e) {		
					e.printStackTrace();
					String errorMsg = className+" | onPause | " +e.getMessage();
		        	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
				}				
			}
		 
		 @Override
			protected void onResume() 
			{
				try{
					super.onResume();					
					if(Common.isAppBackgrnd){
						new Common().storeChangeLogResultFromServer(AccountSettings.this);			
						Common.isAppBackgrnd = false;
					}
					
				}catch (Exception e) 
				{		
					e.printStackTrace();
					String errorMsg = className+" | onResume | " +e.getMessage();
		        	Common.sendCrashWithAQuery(AccountSettings.this,errorMsg);
				
				}
				
			}
}
