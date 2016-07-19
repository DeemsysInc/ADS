package com.seemoreinteractive.seemoreinteractive;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.analytics.tracking.android.EasyTracker;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;
import com.seemoreinteractive.seemoreinteractive.library.FriendsWishListAdapter;

public class FriendsListIems extends Activity {
	public boolean isBackPressed = false;
	String id = null,wishListId=null;
	JSONParser getWishListArray;
	List<NameValuePair> userParams = null, onlyUserIdParams = null;
	JSONArray wishlistJsonArray; 

    ArrayList<String> aList;
	//List<HashMap<String,String>> aList;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_friends_list_iems);
		try{
			
			ImageView imgvBtnCart = (ImageView)findViewById(R.id.imgvBtnCart);
			imgvBtnCart.setVisibility(View.GONE);	
			
		Intent getIntVals = getIntent();
		id = getIntVals.getStringExtra("loggedInUserId");
		wishListId= getIntVals.getStringExtra("wishListId");
		getWishListArray = new JSONParser();
		userParams = new ArrayList<NameValuePair>();
		userParams.add(new BasicNameValuePair("loggedInUserId", id));     
		userParams.add(new BasicNameValuePair("wishListId", wishListId)); 
		JSONObject WishListObj = getWishListArray.getJSONFromUrl(Constants.FriendsProductWishList_Url, userParams);
		
		
		try{				
			 wishlistJsonArray = WishListObj.getJSONArray("prodWishList");
			
	        // Each row in the list stores country name, currency and flag
	        // aList = new ArrayList<HashMap<String,String>>();    
	      
			 aList =  new ArrayList<String>();
			if(wishlistJsonArray.length()>0){
				String[] wishListId = new String[wishlistJsonArray.length()];
				//wishListName = new String[userWishlistJsonArray.length()];
				for(int w=0; w<wishlistJsonArray.length(); w++)
				{
					JSONObject cj = wishlistJsonArray.getJSONObject(w);
					wishListId[w] = cj.getString("id").toString();
					String wishListName = cj.getString("title").toString();
					String image = cj.getString("image").toString();
					String price = cj.getString("price").toString();
					String clientName = cj.getString("client_name").toString();

					String productId = cj.getString("prodId").toString();
					String clientId = cj.getString("client_id").toString();
					/*HashMap<String, String> map = new HashMap<String, String>();
					map.put("Name", wishListName);      				
					map.put("Image", image);     */
		            aList.add(wishListName+","+image+","+price+","+clientName+","+productId+","+clientId);   
				}
		        
				
				
		        // Keys used in Hashmap
		       
			} 
		} catch (JSONException e) {
			// TODO: handle exception
			e.printStackTrace();
			Log.i("Select Wishlist JsonException ", ""+e.getMessage());
		}    
		     
      

		 /*String[] from = { "Name","Image"};
	        
	        // Ids of views in listview_layout
	        int[] to = { R.id.txtWishListName,R.id.imgProduct};    
	        */
	        
	     //  SimpleAdapter adapter = new SimpleAdapter(getApplicationContext(), aList, R.layout.listview_friends_productlist, from, to);

	        
	        
	       ListView lvWishLists = (ListView) findViewById(R.id.friendsListItem);
	       FriendsWishListAdapter adapter = new FriendsWishListAdapter(this,  aList);
	       lvWishLists.setAdapter(adapter);
	       lvWishLists.setOnItemClickListener(new OnItemClickListener() {

				@Override
				public void onItemClick(AdapterView<?> arg0,
						View arg1, final int arg2, long arg3) {
							                   
					String[] hdnID = ((TextView) arg1.findViewById(R.id.hdnId)).getText().toString().split(",");
					ImageView im = (ImageView) arg1.findViewById(R.id.imgProduct);
					BitmapDrawable test = (BitmapDrawable) im.getDrawable();
					Bitmap bitmap = test.getBitmap();
					ByteArrayOutputStream baos = new ByteArrayOutputStream();
					bitmap.compress(Bitmap.CompressFormat.PNG, 0, baos);
					byte[] b = baos.toByteArray();
					Intent intent = new Intent(getApplicationContext(), ProductInfo.class);	
					intent.putExtra("productId",hdnID[0] );
					intent.putExtra("clientId", hdnID[1]);
					intent.putExtra("tapOnImage", false);		
					intent.putExtra("image",b );
					startActivity(intent);
	      	      	
				}
	       });

			new Common().clickingOnBackButtonWithAnimation(FriendsListIems.this, FriendsListActivity.class,"0");
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

		new Common().clickingOnBackButtonWithAnimationWithBackPressed(this, FriendsListActivity.class, "0");
		/*Intent returnIntent = new Intent(getApplicationContext(), FriendsListActivity.class);
		 startActivity(returnIntent);
		 finish();
		 overridePendingTransition(R.xml.slide_in_right,R.xml.slide_out_right);
		super.onBackPressed();
        return;*/
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
