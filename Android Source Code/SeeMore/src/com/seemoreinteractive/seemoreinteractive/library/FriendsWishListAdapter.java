package com.seemoreinteractive.seemoreinteractive.library;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;

import android.content.Context;
import android.graphics.Bitmap;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.AjaxStatus;
import com.androidquery.callback.BitmapAjaxCallback;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;

public class FriendsWishListAdapter extends BaseAdapter {
    private Context mContext;
    private List<String> plotsText;
    private LayoutInflater mInflater;
    JSONParser getWishListArray;
    List<NameValuePair> userParams = null;
    String id;
 
    // Constructor
    public FriendsWishListAdapter(Context c, ArrayList<String> dataList){
        mContext = c;
        plotsText =dataList;
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
       
    	ViewFriendsListHolder holder;
        if (convertView == null) {
			holder = new ViewFriendsListHolder();
			convertView = mInflater.inflate(
					R.layout.listview_friends_productlist, null);
        }
        else {
			holder = (ViewFriendsListHolder) convertView.getTag();
		}
			holder.txtView = (TextView)convertView.findViewById(R.id.txtWishListName);
			String[] itemArray =plotsText.get(position).split(",");
			holder.txtView.setText(itemArray[0]);
			holder.imageview = (ImageView) convertView.findViewById(R.id.imgProduct);
			holder.txtProductPrice = (TextView) convertView.findViewById(R.id.txtProductPrice);
			holder.txtProductPrice.setText(itemArray[2]);
			holder.txtClientName = (TextView) convertView.findViewById(R.id.txtClientName);
			holder.txtClientName.setText(itemArray[3]);
			holder.hdnId = (TextView) convertView.findViewById(R.id.hdnId);
			holder.hdnId.setText(itemArray[4]+","+itemArray[5]);
		       AQuery aq = new AQuery(convertView);
		       //Log.i("imagesList.get(position)", ""+imagesUrlArrList.get(position));
		       String imageUrl = itemArray[1];

		               aq.id(holder.imageview).progress(this).image(imageUrl, true, true, 0, 0, new BitmapAjaxCallback(){
		                       @Override
		                       public void callback(String url, ImageView iv, Bitmap bm, AjaxStatus status)
		                       {
		                               iv.setImageBitmap(bm);
		                               iv.setScaleType(ImageView.ScaleType.CENTER_INSIDE);
		                       }
		               });
			convertView.setTag(holder);
			
		
		//holder.check.setId(position);
		//holder.imageview.setId(position);
		return convertView;
    }

}
class ViewFriendsListHolder {
	public TextView hdnId;
	public TextView txtClientName;
	public TextView txtProductPrice;
	public TextView txtView;
	ImageView imageview;
	int id;
}