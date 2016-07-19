package com.seemoreinteractive.seemoreinteractive;

import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.json.JSONArray;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class FriendsListActivity extends Activity {
	public boolean isBackPressed = false;
	String id = null;
	JSONParser getWishListArray;
	List<NameValuePair> userParams = null, onlyUserIdParams = null;
	JSONArray wishlistJsonArray; 
	List<HashMap<String,String>> aList;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_friends_list);
		try{
			
		/*ImageView imgvBtnCart = (ImageView)findViewById(R.id.imgvBtnCart);
		imgvBtnCart.setVisibility(View.GONE);*/

		new Common().showDrawableImageFromAquery(this, R.drawable.camera_icon_1, R.id.imgvBtnCart);

		ImageView imgvBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
		imgvBtnCart.setOnClickListener(new OnClickListener() {			
			@Override
			public void onClick(View v) {				
				//new Common().clickingOnBackButtonWithAnimationWithBackPressed(FriendsListActivity.this, ARDisplayActivity.class, "0");
				Intent intent = new Intent(getApplicationContext(), ARDisplayActivity.class);
				intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);						
				startActivity(intent); // Launch the HomescreenActivity
				finish(); 
				overridePendingTransition(R.xml.slide_in_left, R.xml.slide_out_left);
			}
		});
		
		/*Intent getIntVals = getIntent();
		id = getIntVals.getStringExtra("loggedInUserId");
		
		getWishListArray = new JSONParser();
		userParams = new ArrayList<NameValuePair>();
		userParams.add(new BasicNameValuePair("loggedInUserId", id));     
				
		JSONObject WishListObj = getWishListArray.getJSONFromUrl(Constants.FriendsWishList_Location, userParams);
		
		
		try{				
			 wishlistJsonArray = WishListObj.getJSONArray("friendsRelatedWishListNames");
			
	        // Each row in the list stores country name, currency and flag
	         aList = new ArrayList<HashMap<String,String>>();    
	      
			
			if(wishlistJsonArray.length()>0){
				String[] wishListId = new String[wishlistJsonArray.length()];
				//wishListName = new String[userWishlistJsonArray.length()];
				for(int w=0; w<wishlistJsonArray.length(); w++)
				{
					JSONObject cj = wishlistJsonArray.getJSONObject(w);
					wishListId[w] = cj.getString("id").toString();
					String wishListName = cj.getString("name").toString();
					String itemCnt = cj.getString("count").toString();
					String username = "@"+cj.getString("username").toString()+"("+cj.getString("first_name").toString()+" " +cj.getString("last_name").toString() +")";
					HashMap<String, String> map = new HashMap<String, String>();
					map.put("Name", wishListName);      
					map.put("Count","("+itemCnt+")" ); 
					map.put("Id",wishListId[w] ); 
					map.put("Username",username ); 
					//map.put("Count", wishListName);     
		            aList.add(map);   
				}
		        
		        // Keys used in Hashmap
		       
			} 
		} catch (JSONException e) {
			// TODO: handle exception
			e.printStackTrace();
			Log.i("Select Wishlist JsonException ", ""+e.getMessage());
		}    
		     
      

		 String[] from = { "Name","Count","Id","Username"};
	        
	        // Ids of views in listview_layout
	        int[] to = { R.id.txtWishListName,R.id.txtWishListItemCnt,R.id.txtWishListId,R.id.txtUsername};    
	        
	        
	       SimpleAdapter adapter = new SimpleAdapter(getApplicationContext(), aList, R.layout.listview_friendslist, from, to);
		
	       ListView lvWishLists = (ListView) findViewById(R.id.friendsList);
	       lvWishLists.setAdapter(adapter);
	       lvWishLists.setOnItemClickListener(new OnItemClickListener() {

				@Override
				public void onItemClick(AdapterView<?> arg0,
						View arg1, final int arg2, long arg3) {
					// String wishListName = ((TextView) arg1.findViewById(R.id.txtWishListName)).getText().toString();
					String wishListId = ((TextView) arg1.findViewById(R.id.txtWishListId)).getText().toString();
					Intent intent = new Intent(getApplicationContext(), FriendsListIems.class);			                             
				    intent.putExtra("wishListId",wishListId );
				    intent.putExtra("loggedInUserId",id );
				    startActivity(intent);
	      	      	
				}
	       });
	       */
				

			new Common().clickingOnBackButtonWithAnimation(FriendsListActivity.this, WishListPage.class,"0");
		/*ImageView imgBackButton = (ImageView) findViewById(R.id.imgvBtnBack);        
        imgBackButton.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				//onBackPressed();
				try{
					 Intent returnIntent = new Intent(getApplicationContext(), WishListPage.class);
					 startActivity(returnIntent);
			    	 finish();
			    	 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
				} catch (Exception e) {
					// TODO: handle exception
					Toast.makeText(getApplicationContext(), "Error: Friends List imgBackButton.", Toast.LENGTH_LONG).show();
				}
			}
		});*/
		}catch(Exception e){
			Toast.makeText(getApplicationContext(), "Error: FriendsListActivity onCreate", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			// Log.i("Press Back", "BACK PRESSED EVENT");
			onBackPressed();
			isBackPressed = true;
		}

		// Call super code so we dont limit default interaction
		return super.onKeyDown(keyCode, event);
	}

	@Override
	public void onBackPressed() {

		
		Intent Home = new Intent(getApplicationContext(), WishListPage.class);
		startActivity(Home);
	     finish();
	     overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		super.onBackPressed();
        return;
	}
	 @Override
	public void onStart() {
	    super.onStart();
	    // The rest of your onStart() code.
	    EasyTracker.getInstance(this).activityStart(this);  // Add this method.
	}
	 @Override
	public void onStop() {
		super.onStop();
		//The rest of your onStop() code.
		EasyTracker.getInstance(this).activityStop(this);  // Add this method.	  
	}

}
