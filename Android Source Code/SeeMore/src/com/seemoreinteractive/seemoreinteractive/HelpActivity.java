package com.seemoreinteractive.seemoreinteractive;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;

import com.seemoreinteractive.seemoreinteractive.helper.Common;


public class HelpActivity extends Activity {
	String className ="HelpActivity";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);	
		try{
		setContentView(R.layout.activity_help);
		String screen_name = getIntent().getStringExtra("screen_name");
		ImageView screenImage = (ImageView) findViewById(R.id.imgvHelpScreen);
		screenImage.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				try{
					finish();
				}catch(Exception e){
					e.printStackTrace();
					String errorMsg = className+" | onCreate   screenImage |   " +e.getMessage();
               	 	Common.sendCrashWithAQuery(HelpActivity.this,errorMsg);
				}
			}
		});
		
		if(screen_name.equals("closet")){
			screenImage.setImageResource(R.drawable.help_mycloset);
		}else if(screen_name.equals("product")){
			screenImage.setImageResource(R.drawable.help_shop);
		}else if(screen_name.equals("share")){
			screenImage.setImageResource(R.drawable.help_share);
		}else if(screen_name.equals("wishlist")){
			screenImage.setImageResource(R.drawable.help_wishlist);
		}
	}catch(Exception e){
		e.printStackTrace();
		String errorMsg = className+" | onCreate  |   " +e.getMessage();
   	 	Common.sendCrashWithAQuery(HelpActivity.this,errorMsg);		
	}
	}
}
