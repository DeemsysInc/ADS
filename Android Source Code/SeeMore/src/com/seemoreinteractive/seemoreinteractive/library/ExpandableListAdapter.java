package com.seemoreinteractive.seemoreinteractive.library;

import java.util.List;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.ExpandableListView;
import android.widget.ExpandableListView.OnGroupCollapseListener;
import android.widget.ExpandableListView.OnGroupExpandListener;
import android.widget.ImageView;
import android.widget.TextView;

import com.seemoreinteractive.seemoreinteractive.R;

public class ExpandableListAdapter extends BaseExpandableListAdapter {

	private Context context;
	private ExpandableListView expandableListView;
	private List<GroupEntity> parentCollection;
	private int[] parentClickStatus;

	public ExpandableListAdapter(Context pContext,
			ExpandableListView pExpandableListView,
			List<GroupEntity> pGroupCollection) {
		context = pContext;
		parentCollection = pGroupCollection;
		expandableListView = pExpandableListView;
		parentClickStatus = new int[parentCollection.size()];

		setListEvent();
	}

	private void setListEvent() {

		expandableListView
				.setOnGroupExpandListener(new OnGroupExpandListener() {

					@Override
					public void onGroupExpand(int arg0) {
						parentClickStatus[arg0] = 1;
					}
				});

		expandableListView
				.setOnGroupCollapseListener(new OnGroupCollapseListener() {

					@Override
					public void onGroupCollapse(int arg0) {
						parentClickStatus[arg0] = 0;
					}
				});
	}

	@Override
	public String getChild(int arg0, int arg1) {
		return parentCollection.get(arg0).GroupItemCollection.get(arg1).ProdName;
	}

	@Override
	public long getChildId(int arg0, int arg1) {
		return 0;
	}

	ChildHolder childHolder;
	ShippingChildHolder shippingChildHolder;
	@Override
	public View getChildView(int arg0, int arg1, boolean arg2, View arg3,
			ViewGroup arg4) {
		try{
			Log.e("arg0", ""+arg0+" "+arg1+" "+arg2+" "+arg3);
	
			//Log.e("arg0", ""+parentCollection.get(arg0).GroupItemCollection.get(arg1).ProdName);
			if (arg3 == null) {
				if(arg2==true && parentCollection.get(arg0).GroupItemCollection.get(arg1).ProdName.equals("newLayout")){
					arg3 = LayoutInflater.from(context).inflate(R.layout.explistview_order_conf_shipping_layout, null);
	
					shippingChildHolder = new ShippingChildHolder();
	
					shippingChildHolder.title = (TextView) arg3.findViewById(R.id.txtvShippingMethodVal);
					shippingChildHolder.price = (TextView) arg3.findViewById(R.id.txtvCartTotalPrice);
					arg3.setTag(shippingChildHolder);
				} else {
					arg3 = LayoutInflater.from(context).inflate(R.layout.explistview_order_conf_child_layout, null);
		
					childHolder = new ChildHolder();
		
					childHolder.title = (TextView) arg3.findViewById(R.id.txtvExpProductName);
					arg3.setTag(childHolder);
				}
			} else {
				childHolder = (ChildHolder) arg3.getTag();
				shippingChildHolder = (ShippingChildHolder) arg3.getTag();
			}
			if(arg2==true && parentCollection.get(arg0).GroupItemCollection.get(arg1).ProdName.equals("newLayout")){
				shippingChildHolder.title.setText("General Shipping");
				shippingChildHolder.price.setText("$2000.99");
			} else {
				childHolder.title.setText(parentCollection.get(arg0).GroupItemCollection.get(arg1).ProdName);
			}
		} catch(Exception e){
			e.printStackTrace();
		}
		return arg3;
	}

	@Override
	public int getChildrenCount(int arg0) {
		return parentCollection.get(arg0).GroupItemCollection.size();
	}

	@Override
	public Object getGroup(int arg0) {
		return parentCollection.get(arg0);
	}

	@Override
	public int getGroupCount() {
		return parentCollection.size();
	}

	@Override
	public long getGroupId(int arg0) {
		// TODO Auto-generated method stub
		return arg0;
	}

	@Override
	public View getGroupView(int arg0, boolean arg1, View arg2, ViewGroup arg3) {
		// TODO Auto-generated method stub
		GroupHolder groupHolder;
		if (arg2 == null) {
			arg2 = LayoutInflater.from(context).inflate(
					R.layout.explistview_order_conf_head_layout, null);
			groupHolder = new GroupHolder();

			groupHolder.arrowImg = (ImageView) arg2
					.findViewById(R.id.imgvExpArrowIcon);
			groupHolder.parentNameTxt = (TextView) arg2
					.findViewById(R.id.txtvExpGroupHeadClientName);

			groupHolder.parentSubTxt = (TextView) arg2
					.findViewById(R.id.txtvExpSubTotalPrice);

			arg2.setTag(groupHolder);
		} else {
			groupHolder = (GroupHolder) arg2.getTag();
		}

		if (parentClickStatus[arg0] == 0) {
			groupHolder.arrowImg.setImageResource(R.drawable.arrow_up);
		} else {
			groupHolder.arrowImg.setImageResource(R.drawable.arrow_down);
		}
		groupHolder.parentNameTxt.setText(parentCollection.get(arg0).Name);

		return arg2;
	}

	class GroupHolder {
		ImageView arrowImg;
		TextView parentNameTxt;
		TextView parentSubTxt;
	}

	class ChildHolder {
		TextView title;
	}
	class ShippingChildHolder {
		TextView title;
		TextView price;
	}

	@Override
	public boolean hasStableIds() {
		return true;
	}

	@Override
	public boolean isChildSelectable(int arg0, int arg1) {
		return true;
	}

}
