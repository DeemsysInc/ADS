package com.seemoreinteractive.seemoreinteractive.library;


import android.content.Context;
import android.graphics.Bitmap;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidquery.AQuery;
import com.androidquery.callback.ImageOptions;
import com.seemoreinteractive.seemoreinteractive.R;
import com.seemoreinteractive.seemoreinteractive.ScratchOffer;
import com.seemoreinteractive.seemoreinteractive.helper.Common;

	public class ScracthListAdapter extends BaseAdapter{   
	    String [] result;
	    Context context;
	    int [] imageId;
	    private static LayoutInflater inflater=null;
	    public ScracthListAdapter(Context context, String[] scratchFinalArray) {
	    	try{
	      
	        result=scratchFinalArray;
	        this.context=context;
	        inflater = ( LayoutInflater )context.
	                 getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	    }
	    
		@Override
	    public int getCount() {
	        // TODO Auto-generated method stub
	        return result.length;
	    }

	    @Override
	    public Object getItem(int position) {
	        // TODO Auto-generated method stub
	        return position;
	    }

	    @Override
	    public long getItemId(int position) {
	        // TODO Auto-generated method stub
	        return position;
	    }

	    public class Holder
	    {
	        TextView txtvOfferName;
	        TextView offerDiscount;	        
	        ImageView imgOffer,check;
	        LinearLayout ll;
	    }
	    @Override
	    public View getView(final int position, View convertView, ViewGroup parent) {	    	
	        Holder holder=new Holder();
	        if(convertView == null){
				convertView = inflater.inflate(R.layout.listview_scratch_layout, null);
			}	
	        try{
	        if(result[position]!=null){
				String s ="[";
				String q ="]";
				String w ="";
				String strReplaceSymbol = String.valueOf(result[position]).replace(s, w).replace(q, w);					
				String[] expClosetArray = strReplaceSymbol.split(",");
				
				
				String expImageUrl   = expClosetArray[0].trim();		
				String expOfferId    = expClosetArray[1].trim();		
				String expOfferName  = expClosetArray[2].trim();		
				String expDiscount   = expClosetArray[3].trim();		
				String expDisclaimer = expClosetArray[4].trim();		
			
	            holder.txtvOfferName=(TextView) convertView.findViewById(R.id.txtvOfferName);	             
	            holder.offerDiscount=(TextView) convertView.findViewById(R.id.offerDiscount);
	            /* RelativeLayout.LayoutParams rlpofferDiscount = (RelativeLayout.LayoutParams)  holder.offerDiscount.getLayoutParams();
	             rlpofferDiscount.bottomMargin = (int) (0.041 * Common.sessionDeviceHeight);
	             //rlpofferDiscount.leftMargin = (int) (0.048333 * Common.sessionDeviceWidth);
	 			 holder.offerDiscount.setLayoutParams(rlpofferDiscount);
	 			 */
	 			 
	 			
	 			 holder.ll=(LinearLayout) convertView.findViewById(R.id.thumbnail);
	 			 RelativeLayout.LayoutParams rlpimgOffer = (RelativeLayout.LayoutParams)   holder.ll.getLayoutParams();
	             rlpimgOffer.width = (int) (0.6334 * Common.sessionDeviceWidth);
	             rlpimgOffer.height = (int) (0.19468 * Common.sessionDeviceHeight);
	             rlpimgOffer.leftMargin = (int) (0.06667 * Common.sessionDeviceWidth);
	 			 holder.ll.setLayoutParams(rlpimgOffer);
	 			 
	             holder.imgOffer=(ImageView) convertView.findViewById(R.id.imgOffer);
	            /* LinearLayout.LayoutParams rlpimgOffer = (LinearLayout.LayoutParams)   holder.imgOffer.getLayoutParams();
	             rlpimgOffer.width = (int) (0.3334 * Common.sessionDeviceWidth);
	             rlpimgOffer.height = (int) (0.205 * Common.sessionDeviceHeight);
	 			 holder.imgOffer.setLayoutParams(rlpimgOffer);
	 			 */
	             holder.txtvOfferName.setText(expOfferName);
	             RelativeLayout.LayoutParams rlptxtvOfferName = (RelativeLayout.LayoutParams)   holder.txtvOfferName.getLayoutParams();
	            // rlptxtvOfferName.leftMargin = (int) (0.048333 * Common.sessionDeviceWidth);
	             holder.txtvOfferName.setLayoutParams(rlptxtvOfferName);
	             
	             RelativeLayout.LayoutParams rlpOfferDiscount= (RelativeLayout.LayoutParams)   holder.offerDiscount.getLayoutParams();
	             //rlpOfferDiscount.leftMargin = (int) (0.048333 * Common.sessionDeviceWidth);
	             rlpOfferDiscount.topMargin = (int) (0.01025 * Common.sessionDeviceWidth);
	             holder.offerDiscount.setLayoutParams(rlpOfferDiscount);
	             holder.offerDiscount.setText(expDiscount);
	             
	             holder.check =(ImageView) convertView.findViewById(R.id.imgcheck);	             
	             RelativeLayout.LayoutParams rlCheck= (RelativeLayout.LayoutParams)   holder.check.getLayoutParams();
	             rlCheck.leftMargin = (int) (0.151667 * Common.sessionDeviceWidth);
	             holder.check.setLayoutParams(rlCheck);
	             
	             
	            
	             
	             AQuery aq2 = new AQuery(convertView);
				 Bitmap bitmap1 = aq2.getCachedImage(expImageUrl);
	    			if(bitmap1==null) {
	    				aq2.cache(expImageUrl, 144000);
	    			}
			       			       
					ImageOptions options = new ImageOptions();
					options.fileCache = true;
					options.memCache = true;
					options.targetWidth = 0;
					options.fallback = 0;
					options.preset = null;
					options.ratio = 0;
					options.animation = com.androidquery.util.Constants.FADE_IN;	
	    			aq2.id(R.id.imgOffer).image(expImageUrl, options);					
	          //   holder.imgOffer.setImageResource(imageId[position]);         
	    			convertView.setOnClickListener(new OnClickListener() {            
		            @Override
		            public void onClick(View v) {
		                // TODO Auto-generated method stub
		               // Toast.makeText(context, "You Clicked "+result[position], Toast.LENGTH_LONG).show();
		            }
	        });   
	        }
	    	}catch(Exception e){
	    		e.printStackTrace();
	    	}
	        return convertView;
	    }

	}

