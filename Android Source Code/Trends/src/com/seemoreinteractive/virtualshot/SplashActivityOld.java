package com.seemoreinteractive.virtualshot;

import android.app.Activity;
import android.os.Bundle;
import android.widget.Toast;

public class SplashActivityOld extends Activity{
	@Override
	public void onCreate(Bundle savedInstanceState) {
		try{
			super.onCreate(savedInstanceState);
			setContentView(R.layout.splash);
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: SplashActivity onCreate.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	
	@Override
	public void onStart(){
		try{
			super.onStart();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: SplashActivity onStart.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	
	@Override
	public void onResume(){
		try{
			super.onResume();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: SplashActivity onResume.", Toast.LENGTH_LONG).show();
			e.printStackTrace();
		}
	}
	
}
