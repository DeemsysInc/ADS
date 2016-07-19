package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import android.util.Log;

import com.seemoreinteractive.seemoreinteractive.helper.Common;


public class ChangeLogModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserChangeLog> changeLog;

	public ChangeLogModel() {
		changeLog = new ArrayList<UserChangeLog>();
	}

	public void add(UserChangeLog userChangeLog) {
		changeLog.add(userChangeLog);
	}

	public UserChangeLog getChangeLog() {
		for (UserChangeLog userChangeLog : changeLog) {			
				return userChangeLog;
		}
		return null;
	}
	public List<UserChangeLog> getChangeLogList() {
		List<UserChangeLog> list = new ArrayList<UserChangeLog>();
		for (UserChangeLog userChangeLog : changeLog) {			
			list.add(userChangeLog);
		}
		return list;
	}
	public List<String> getChangeLogIdsbyDate() {

		List<String> list = new ArrayList<String>();
		try{
			Date d = Calendar.getInstance().getTime(); // Current time
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); // Set your date format			
			//Log.e("currentDate",Common.sessionForUserAppOpenDate);
			String date = sdf.format(d);
			Date currentDate;
			if(Common.sessionForUserAppOpenDate.equals("null") || Common.sessionForUserAppOpenDate.equals(""))
				currentDate = sdf.parse(date);
			else
				currentDate = sdf.parse(Common.sessionForUserAppOpenDate);
				
			//Log.e("currentData getChangeLogIdsbyDate",""+(currentDate));
			//Log.e("currentData changeLog",""+changeLog.size());
			for (UserChangeLog userChangeLog : changeLog) {		
				//Log.e("currentData userChangeLog",""+sdf.parse(userChangeLog.getCreatedDate()));
				if(sdf.parse(userChangeLog.getCreatedDate()).equals(currentDate)){
					list.add(""+userChangeLog.getId());
				}
			}
		}catch(Exception e){
			e.printStackTrace();
		}
		return list;
	}

	public int size() {
		return changeLog.size();
	}

	public List<UserChangeLog> list() {
		return changeLog;
	}

	
	public void removeAll() {
		changeLog.clear();
	}
	
	public void updateChangeLog(List<UserChangeLog> userChangeLog) {
		changeLog = userChangeLog;
		
	}

	public void remove(UserChangeLog userChangeLog) {
		// TODO Auto-generated method stub
		changeLog.remove(userChangeLog);
	}
	

}
