package com.seemoreinteractive.seemoreinteractive.library;
import java.util.ArrayList;
import java.util.HashMap;

import android.app.Activity;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;

import com.androidquery.AQuery;
import com.seemoreinteractive.seemoreinteractive.R;

public class CustomRenderCoverFlowArrayAdapter extends ArrayAdapter<HashMap<String, String>> {
	
    AQuery aq;
    private final ArrayList<HashMap<String, String>> arrListHashMapForClientInfo2;
    Activity act;
    int layoutResource;

    public CustomRenderCoverFlowArrayAdapter(Activity a, int resource,
            ArrayList<HashMap<String, String>> arrListHashMapForClientInfo2,
            ArrayList<HashMap<String, String>> arrListHashMapProdsWithClient2) {
        super(a, resource, arrListHashMapForClientInfo2);
    	act = a;
    	layoutResource = resource;
        this.arrListHashMapForClientInfo2 = arrListHashMapForClientInfo2;
        Log.e("arrListHashMapForClientInfo2 top", ""+this.arrListHashMapForClientInfo2.size()+" "+this.arrListHashMapForClientInfo2);
        aq = new AQuery(act);
    }
    @Override
	public int getCount() {
        return arrListHashMapForClientInfo2.size();
    }

    @Override
	public HashMap<String, String> getItem(int position) {
        return arrListHashMapForClientInfo2	.get(position);
    }

    @Override
	public long getItemId(int position) {
        return position;
    }
    LinearLayout listViewForProds;
	@Override
    public View getView(final int position, View convertView, ViewGroup parent) {
       
        if (convertView == null) {
        	LayoutInflater vi = (LayoutInflater) act.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = vi.inflate(layoutResource, null);
        }
        Log.e("arrListHashMapForClientInfo2 top", ""+arrListHashMapForClientInfo2.size()+" "+arrListHashMapForClientInfo2);
        AQuery aq2 = new AQuery(convertView);
		listViewForProds = (LinearLayout) convertView.findViewById(R.id.listViewForProds);
		
		/*if(!arrListHashMapForClientInfo2.get(position).isEmpty()){
			HashMap<String, String> hashMapValues = arrListHashMapForClientInfo2.get(position);
			Button btnClientLogoOrName = (Button) convertView.findViewById(R.id.btnClientLogoOrName);
			if(hashMapValues.get("ClientLogo").equals("") || hashMapValues.get("ClientLogo").equals("null")){
				btnClientLogoOrName.setText(hashMapValues.get("ClientName"));
				btnClientLogoOrName.setTextSize((float) (0.05 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			} else{
				Bitmap clientLogoBmp = aq2.getCachedImage(hashMapValues.get("ClientLogo"));
				if(clientLogoBmp==null){
					URL url1 = new URL(hashMapValues.get("ClientLogo").replaceAll(" ", "%20"));   
					clientLogoBmp = BitmapFactory.decodeStream(url1.openStream());
                    aq2.cache(hashMapValues.get("ClientLogo"), 14400000);
				}
				BitmapDrawable d = new BitmapDrawable(clientLogoBmp);
				btnClientLogoOrName.setBackgroundDrawable(d);
				btnClientLogoOrName.setLayoutParams(new RelativeLayout.LayoutParams((int) (0.417 * Common.sessionDeviceWidth), (int) (0.082 * Common.sessionDeviceHeight)));
				
			}
			Log.e("arrListHashMapProdsWithClient2.size() ", ""+arrListHashMapProdsWithClient2.size());

			float cartTotalPrice = 0;
			String cartTotalPriceForAdd = "";
			float cartTotalPriceValues = 0;
			View convertView2 = null;
			View v = null;
			String shippingMethodName = "null", shippingMethodDetails = "null", 
					shippingMethodRateForTitle = "null", shippingMethodRate = "null";
			LayoutInflater inflater = (LayoutInflater) getBaseContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			for(int c=0; c<arrListHashMapProdsWithClient2.size(); c++){
				HashMap<String, String> hashMapValuesForListView = arrListHashMapProdsWithClient2.get(c);
				if(hashMapValuesForListView.get(hashMapValues.get("ClientId"))!=null){
					HashMap<String, String> hashMapValuesForListViewNew = convertToHashMap(hashMapValuesForListView.get(hashMapValues.get("ClientId")));
					View child = inflater.inflate(R.layout.listview_order_conf_pds_layout, null);
		           
					AQuery aq3 = new AQuery(child);
					
					aq3.id(R.id.txtvExpProductName).text(hashMapValuesForListViewNew.get("ProdName"));
					aq3.id(R.id.txtvExpProdPrice).text("$"+hashMapValuesForListViewNew.get("ProdPrice"));
					aq3.id(R.id.imgvExpProdImage).image(hashMapValuesForListViewNew.get("ProdImage"));
					if(!hashMapValuesForListViewNew.get("Size").equals("null")){
						aq3.id(R.id.txtvExpSize).visibility(View.VISIBLE);
						aq3.id(R.id.txtvExpSize).text(hashMapValuesForListViewNew.get("Size"));
					} else {
						aq3.id(R.id.txtvExpSize).visibility(View.GONE);						
					}
					if(!hashMapValuesForListViewNew.get("Color").equals("null")){
						aq3.id(R.id.btnExpColorCode).visibility(View.VISIBLE);
						aq3.id(R.id.btnExpColorCode).backgroundColor(Color.parseColor(hashMapValuesForListViewNew.get("Color")));
					} else {
						aq3.id(R.id.btnExpColorCode).visibility(View.GONE);
					}
					aq3.id(R.id.txtvExpProdQuantityWithPrice).text("("+hashMapValuesForListViewNew.get("Quantity")+" X "+(Float.parseFloat(hashMapValuesForListViewNew.get("ProdPrice")))+")");
					aq3.id(R.id.txtvExpProdTotalPrice).text("$"+((Integer.parseInt(hashMapValuesForListViewNew.get("Quantity")))*(Float.parseFloat(hashMapValuesForListViewNew.get("ProdPrice")))));
					
					cartTotalPrice += Float.parseFloat(aq3.id(R.id.txtvExpProdTotalPrice).getText().toString().replace(""+priceSymbol, ""));
					
					Log.e("cartTotalPrice", ""+cartTotalPrice);
					cartTotalPriceValues += Float.parseFloat(aq3.id(R.id.txtvExpProdTotalPrice).getText().toString().replace(""+priceSymbol, ""));
					Log.e("cartTotalPriceValues", ""+cartTotalPriceValues);
					
					listViewForProds.addView(child);
					shippingMethodName = hashMapValuesForListViewNew.get("ShippingMethodName");
					shippingMethodDetails = hashMapValuesForListViewNew.get("ShippingMethodDetails");
					shippingMethodRateForTitle = hashMapValuesForListViewNew.get("ShippingMethodRateTitle");
					shippingMethodRate = hashMapValuesForListViewNew.get("ShippingMethodRate");
				}
			}
			cartTotalPriceForAdd = "$"+cartTotalPrice; 
			
			aq2.id(R.id.txtvShippingMethodVal).text(shippingMethodName);
			aq2.id(R.id.txtvShippingDelivery).text(shippingMethodDetails);
			if(shippingMethodRateForTitle.equals("A")){
				aq2.id(R.id.txtvShippingCostPrice).text("$"+Float.parseFloat(shippingMethodRate));							
			} else if(shippingMethodRateForTitle.equals("P")){
				float shippingCostForMethod = cartTotalPrice * Float.parseFloat(shippingMethodRate);
				aq2.id(R.id.txtvShippingCostPrice).text("$"+shippingCostForMethod);							
			}
			
			aq2.id(R.id.txtvCartTotalPrice).text("$"+cartTotalPrice);
			
			String onlyPriceValForShippingCostPrice = aq2.id(R.id.txtvShippingCostPrice).getText().toString().replace(""+priceSymbol, "");
			
			String onlyPriceValForCartTotalPrice = cartTotalPriceForAdd.replace(""+priceSymbol, "");
			
			double finalPdsTotal = Float.parseFloat(onlyPriceValForShippingCostPrice) + Float.parseFloat(onlyPriceValForCartTotalPrice);
			aq2.id(R.id.txtvShippingSubTotalPrice).text(""+priceSymbol+Common.roundTwoDecimalsCommon(finalPdsTotal));
			
			double OrderTotalPrice = Float.parseFloat(aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString().replace(""+priceSymbol, "")) + Float.parseFloat(txtvSalesTaxPrice.getText().toString().replace(""+priceSymbol, ""));
			txtvOrderTotalPrice.setText(""+priceSymbol+Common.roundTwoDecimalsCommon(OrderTotalPrice));
			
			clientWiseTotalPrice += aq2.id(R.id.txtvShippingSubTotalPrice).getText().toString();
			Log.e("clientWiseTotalPrice", ""+clientWiseTotalPrice);
		}*/
		return convertView;
    }

}