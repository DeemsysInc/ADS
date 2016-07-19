package com.seemoreinteractive.seemoreinteractive.library;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxCallback;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.util.XmlDom;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.WishListPage;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;
import com.seemoreinteractive.seemoreinteractive.Utils.Constants;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

public class WishListAdapter extends BaseAdapter {
    private Activity mContext;
    private List<String> plotsText;
    private LayoutInflater mInflater;
    JSONParser getWishListArray;
    List<NameValuePair> userParams = null;
    String id,wishlistname;
    AQuery aq;
    
    // Constructor
    public WishListAdapter(Activity c, ArrayList<String> dataList, String id, String wishlistname){    
        mContext = c;
        plotsText =dataList;
        this.wishlistname= wishlistname;
        this.id=id;
        mInflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }
   
	
    @Override
    public int getCount() {
        return plotsText.size();
    }
 
    @Override
    public Object getItem(int position) {
        return position;
    }
 
    @Override
    public long getItemId(int position) {
        return 0;
    }
 
	@Override
    public View getView(final int position, View convertView, ViewGroup parent) {
       
    	ViewListHolder holder;
        if (convertView == null) {
			holder = new ViewListHolder();
			convertView = mInflater.inflate(
					R.layout.listview_wishlist_layout, null);
        }
        else {
			holder = (ViewListHolder) convertView.getTag();
		}
			holder.imageview = (ImageView) convertView.findViewById(R.id.imgarrow);
			holder.imgDeleteIcon = (ImageView) convertView.findViewById(R.id.imgDeleteIcon);
			holder.txtView = (TextView)convertView.findViewById(R.id.txtWishListName);
			holder.txtView.setText(plotsText.get(position));
			convertView.setTag(holder);
			if(this.wishlistname.equals(plotsText.get(position))){
				holder.imageview.setVisibility(View.VISIBLE);
				holder.txtView.setTextColor(Color.GREEN);
			}else{
				holder.imageview.setVisibility(View.INVISIBLE);
				holder.txtView.setTextColor(Color.WHITE);
			}
			holder.imgDeleteIcon.setOnClickListener(new OnClickListener() {
					
					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
						//Toast.makeText(mContext, "delelte"+position, Toast.LENGTH_LONG).show();
						if(Common.isNetworkAvailable(mContext)){
		        			
			            	
						 AlertDialog.Builder alertDialog = new AlertDialog.Builder(mContext);
						 
					        // Setting Dialog Title
					        alertDialog.setTitle("Confirm Delete...");
					 
					        // Setting Dialog Message
					        alertDialog.setMessage("Are you sure you want delete this?");
					 
					        // Setting Icon to Dialog
					      //  alertDialog.setIcon(R.drawable.delete);
					 
					        // Setting Positive "Yes" Button
					        alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
					            @Override
								public void onClick(DialogInterface dialog,int which) {
					 
					            	// Write your code here to invoke YES event
					            	aq = new AQuery(mContext); 
				                	aq.ajax(Constants.DeleteWishList_Url+id+"/"+plotsText.get(position)+"/", XmlDom.class, new AjaxCallback<XmlDom>(){
				            			
				            			@Override
										public void callback(String url, XmlDom xml, AjaxStatus status) {
				            				try{
				            					//Log.i("XmlDom", ""+xml);
					            				if(xml!=null){
								    				if(xml.text("msg").equals("success")){
								    					//Toast.makeText(mContext, "Wish list deleted successfully.", Toast.LENGTH_LONG).show();
														 Intent intent = new Intent(mContext, WishListPage.class);			                             
														   // intent.putExtra("wishListName","My Offers" );
														// mContext.setResult(1,intent);
														
														 mContext.startActivity(intent);
														 mContext.finish();
													} else {
														Toast.makeText(mContext, "Delete failed. Please try again!", Toast.LENGTH_LONG).show();
														mContext.finish();
													}			            					
					            				}
				            				} catch(Exception e){
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
					            // Write your code here to invoke NO event							         
					            dialog.cancel();
					            }
					        });
					 
					        // Showing Alert Message
					        alertDialog.show();
					}
					}
				});
		
		
		//holder.check.setId(position);
		//holder.imageview.setId(position);
		return convertView;
    }

}
class ViewListHolder {
	public ImageView imgDeleteIcon;
	public TextView txtView;
	ImageView imageview;
	int id;
}