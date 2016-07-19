package com.seemoreinteractive.seemoreinteractive.Utils;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.telephony.PhoneStateListener;
import android.telephony.SignalStrength;
import android.telephony.TelephonyManager;

public class NetworkUtil {
	
	public static int TYPE_WIFI = 1;
	public static int TYPE_MOBILE = 2;
	public static int TYPE_NOT_CONNECTED = 0;
	
	
	public static int getConnectivityStatus(Context context) {
		ConnectivityManager cm = (ConnectivityManager) context
				.getSystemService(Context.CONNECTIVITY_SERVICE);

		NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
		if (null != activeNetwork) {
			if(activeNetwork.getType() == ConnectivityManager.TYPE_WIFI)
				return TYPE_WIFI;
			
			if(activeNetwork.getType() == ConnectivityManager.TYPE_MOBILE)
				return TYPE_MOBILE;
		} 
		return TYPE_NOT_CONNECTED;
	}
	
	public static Boolean getConnectivityStatusString(Context context) {
		int conn = NetworkUtil.getConnectivityStatus(context);
		Boolean status = false;
		if (conn == NetworkUtil.TYPE_WIFI) {
			WifiManager wifi = (WifiManager) context.getSystemService(Context.WIFI_SERVICE);
            WifiInfo w = wifi.getConnectionInfo();
			int linkSpeed = w.getLinkSpeed();
            if (linkSpeed < 5) {
            	status = false;	        	
	        }else{
	        	status = true;
	        }
		} else if (conn == NetworkUtil.TYPE_MOBILE) {
			ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
			NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
			int type = activeNetwork.getType();
			int subType = activeNetwork.getSubtype();
			status = isConnectionFast(type,subType);
		} else if (conn == NetworkUtil.TYPE_NOT_CONNECTED) {
			status = false;
		}
		return status;
	}
	
	
	
	public static boolean isConnectionFast(int type, int subType){
		
        if(type==ConnectivityManager.TYPE_WIFI){
            return true;
        }else if(type==ConnectivityManager.TYPE_MOBILE){
            switch(subType){
            case TelephonyManager.NETWORK_TYPE_1xRTT:
                return false; // ~ 50-100 kbps
            case TelephonyManager.NETWORK_TYPE_CDMA:
                return false; // ~ 14-64 kbps
            case TelephonyManager.NETWORK_TYPE_EDGE:
                return false; // ~ 50-100 kbps
            case TelephonyManager.NETWORK_TYPE_EVDO_0:
                return true; // ~ 400-1000 kbps
            case TelephonyManager.NETWORK_TYPE_EVDO_A:
                return true; // ~ 600-1400 kbps
            case TelephonyManager.NETWORK_TYPE_GPRS:
                return false; // ~ 100 kbps
            case TelephonyManager.NETWORK_TYPE_HSDPA:
                return true; // ~ 2-14 Mbps
            case TelephonyManager.NETWORK_TYPE_HSPA:
                return true; // ~ 700-1700 kbps
            case TelephonyManager.NETWORK_TYPE_HSUPA:
                return true; // ~ 1-23 Mbps
            case TelephonyManager.NETWORK_TYPE_UMTS:
                return true; // ~ 400-7000 kbps
            /*
             * Above API level 7, make sure to set android:targetSdkVersion 
             * to appropriate level to use these
             */
            case TelephonyManager.NETWORK_TYPE_EHRPD: // API level 11 
                return true; // ~ 1-2 Mbps
            case TelephonyManager.NETWORK_TYPE_EVDO_B: // API level 9
                return true; // ~ 5 Mbps
            case TelephonyManager.NETWORK_TYPE_HSPAP: // API level 13
                return true; // ~ 10-20 Mbps
            case TelephonyManager.NETWORK_TYPE_IDEN: // API level 8
                return false; // ~25 kbps 
            case TelephonyManager.NETWORK_TYPE_LTE: // API level 11
                return true; // ~ 10+ Mbps
            // Unknown
            case TelephonyManager.NETWORK_TYPE_UNKNOWN:
            default:
                return false;
            }
        }else{
            return false;
        }
    }

	
}
class MyPhoneStateListener extends PhoneStateListener{
    public int singalStenths =0; 
     @Override
     public void onSignalStrengthsChanged(SignalStrength signalStrength){
        super.onSignalStrengthsChanged(signalStrength);
        int singalStrength  = signalStrength.getGsmSignalStrength();
        singalStenths = signalStrength.getGsmSignalStrength();
           // System.out.println("----- gsm strength" + singalStrength );
            //System.out.println("----- gsm strength" + singalStenths );
        	
            /*if(singalStenths > 30)
            {
                signalstrength.setText("Signal Str : Good");
                signalstrength.setTextColor(getResources().getColor(R.color.good));
           	 return singalStenths;
            }
            else if(singalStenths > 20 && singalStenths < 30)
            {
           	 signalstrength.setText("Signal Str : Average");
                signalstrength.setTextColor(getResources().getColor(R.color.average));
            }
            else if(singalStenths < 20)
            {
                signalstrength.setText("Signal Str : Weak");
                signalstrength.setTextColor(getResources().getColor(R.color.weak));
            }*/
     }

        };
