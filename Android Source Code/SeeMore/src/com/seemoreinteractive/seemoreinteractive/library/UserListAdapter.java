package com.seemoreinteractive.seemoreinteractive.library;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;

import android.app.Activity;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.Model.JSONParser;

public class UserListAdapter extends BaseAdapter {
    private Activity mContext;
    private List<HashMap<String, String>> plotsText;
    private LayoutInflater mInflater;
    JSONParser getWishListArray;
    List<NameValuePair> userParams = null;
    String type;
    AQuery aq;
    int label =0;
    int value =0;
    int layout = 0;
    String strLabel,strValue;
    // Constructor
    public UserListAdapter(Activity c, ArrayList<HashMap<String, String>> basicList, String type){
    	
        mContext = c;
        plotsText =basicList;
        this.type=type;
        mInflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        Log.i("type",""+type);
        if(type.equals("basic")){
        	label = R.id.blabel;
        	value = R.id.bvalue;
        	layout =R.layout.listview_basicprofile;
        	strLabel ="bLabel";
        	strValue ="bValue";
        }else if(type.equals("contact")){
        	label = R.id.clabel;
        	value = R.id.cvalue;
        	layout =R.layout.listview_contactprofile;
        	strLabel ="cLabel";
        	strValue ="cValue";
        }else if(type.equals("address")){
        	label = R.id.alabel;
        	value = R.id.avalue;
        	layout =R.layout.listview_addressprofile;
        	strLabel ="aLabel";
        	strValue ="aValue";
        }
        
        
        
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
       
		ViewUserListHolder holder;
		holder = new ViewUserListHolder();
		if(plotsText.get(position).get(strValue).toString().equals("-")){
			convertView = mInflater.inflate(
					R.layout.listview_updatelayout, null);
			holder.txtUpdate  = (TextView) convertView.findViewById(R.id.txtUpdate);
			holder.txtUpdate.setText("Update "+plotsText.get(position).get(strLabel).toString());
			
			
		}else{
       /* if (convertView == null) {
			holder = new ViewUserListHolder();
			convertView = mInflater.inflate(layout, null);
        }
        else {
			holder = (ViewUserListHolder) convertView.getTag();
		}*/
			
			//holder = new ViewUserListHolder();
			convertView = mInflater.inflate(layout, null);       
			holder.txtLabel = (TextView) convertView.findViewById(label);
			holder.txtValue = (TextView) convertView.findViewById(value);
			holder.txtLabel.setText(plotsText.get(position).get(strLabel).toString());
			holder.txtValue.setText(plotsText.get(position).get(strValue).toString());
			convertView.setTag(holder);
			
		}
		
		//holder.check.setId(position);
		//holder.imageview.setId(position);
		return convertView;
    }

}
class ViewUserListHolder {
	public TextView txtLabel;
	public TextView txtValue;
	public TextView txtUpdate;
	
	
}