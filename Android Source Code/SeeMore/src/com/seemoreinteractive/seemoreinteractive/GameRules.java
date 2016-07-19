package com.seemoreinteractive.seemoreinteractive;

import com.seemoreinteractive.seemoreinteractive.helper.Common;

import android.app.Activity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;

public class GameRules extends Activity {

	TextView txtvRules;
	ScrollView textAreaScroller;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_game_rules);
		try{
		
			
			/*RelativeLayout bgRelativeLayout = (RelativeLayout)findViewById(R.id.bgRelativeLayout);
			RelativeLayout.LayoutParams rlbgRelativeLayout  = (RelativeLayout.LayoutParams)bgRelativeLayout.getLayoutParams();
			rlbgRelativeLayout.width  = (int)(0.83334 * Common.sessionDeviceWidth);
			rlbgRelativeLayout.height = (int)(0.615 * Common.sessionDeviceHeight);		
			rlbgRelativeLayout.topMargin = (int)(0.0339 * Common.sessionDeviceHeight);	
			rlbgRelativeLayout.leftMargin = (int)(0.041667 * Common.sessionDeviceHeight);
			bgRelativeLayout.setLayoutParams(rlbgRelativeLayout);*/
			
			/*
			ImageView imgvClose  =  (ImageView)findViewById(R.id.imgvClose);
			RelativeLayout.LayoutParams rlImgvClose  = (RelativeLayout.LayoutParams)imgvClose.getLayoutParams();
			rlImgvClose.width  = (int)(0.06667 * Common.sessionDeviceWidth);
			rlImgvClose.height = (int)(0.041 * Common.sessionDeviceHeight);		
			rlImgvClose.topMargin = (int)(0.01537 * Common.sessionDeviceHeight);	
			rlImgvClose.rightMargin = (int)(0.091667 * Common.sessionDeviceWidth);	
			imgvClose.setLayoutParams(rlImgvClose);
			imgvClose.setOnClickListener(new OnClickListener() {				
				@Override
				public void onClick(View v) {
					finish();
				}
			});
			*/
			new Common().clientLogoOrTitleWithThemeColorAndBgImgByPassingColor(
					this, "ff2600",
					"ff2600",
					"ff2600",
					Common.sessionClientLogo, "Game Rules", "");
			
			ImageView imgBtnCart = (ImageView) findViewById(R.id.imgvBtnCart);
			imgBtnCart.setImageAlpha(0);
			
			
			WebView webview = (WebView)findViewById(R.id.wbvHowTo);
			webview.getSettings().setJavaScriptEnabled(true);
			webview.loadUrl(getIntent().getStringExtra("game_rules_url"));
			
			new Common().clickingOnBackButtonWithAnimation(GameRules.this, ProductList.class,"0");
			
			/*txtvRules = (TextView)findViewById(R.id.txtvRules);
			ScrollView.LayoutParams rlTxtvRules  = (ScrollView.LayoutParams)txtvRules.getLayoutParams();				
			rlTxtvRules.topMargin = (int)(0.02255 * Common.sessionDeviceHeight);	
			txtvRules.setLayoutParams(rlTxtvRules);
			txtvRules.setText(getIntent().getStringExtra("game_rules"));
			txtvRules.setTextSize((int)(0.0334 * Common.sessionDeviceWidth/Common.sessionDeviceDensity));
			
			textAreaScroller = (ScrollView)findViewById(R.id.textAreaScroller);
			ScrollView.LayoutParams rlTextAreaScroller  = (ScrollView.LayoutParams)textAreaScroller.getLayoutParams();
			rlTextAreaScroller.topMargin = (int)(0.039 * Common.sessionDeviceHeight);	
			rlTextAreaScroller.height = (int)(0.41 * Common.sessionDeviceHeight);	
			textAreaScroller.setLayoutParams(rlTextAreaScroller);*/
		}catch(Exception e){
			e.printStackTrace();
		}
		 
	}
}
