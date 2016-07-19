package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashMap;


public class UserOrder implements Serializable {
	private static final long serialVersionUID = 2L;
	private int id;	
	private String orderTotal;
	private  ArrayList<HashMap<String, String>> arrPdInfoForCartList = new ArrayList<HashMap<String,String>>();
	private  ArrayList<HashMap<String, String>> arrClientInfoForCartList = new ArrayList<HashMap<String,String>>();

	private String salesTax;
	private String salesTaxValue;
	private  ArrayList<String> arrForClientIds = new ArrayList<String>();
	private String userShipId;
	private String address1;
	private String address2;
	private String city;
	private String state;
	private String zip;
	private String country;
	private String currentDateandTime;
	
	public UserOrder() {
	}

	public UserOrder(int id) {
		this.setId(id);
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getOrderTotal() {
		return orderTotal;
	}

	public void setOrderTotal(String orderTotal) {
		this.orderTotal = orderTotal;
	}
	
	public void setCartInfoList( ArrayList<HashMap<String, String>> arrPdInfoForCartList) {
		this.arrPdInfoForCartList = arrPdInfoForCartList;
	}
	
	public ArrayList<HashMap<String, String>> getCartInfoList() {
		return arrPdInfoForCartList;
	}
	
	public void setClientIds(ArrayList<String> arrForClientIds) {
		// TODO Auto-generated method stub
		this.arrForClientIds =  arrForClientIds;
		
	}
	
	public ArrayList<String>  getClientIds() {
		return arrForClientIds;
	}
	
	
	
	public void setCartClientInfoList(ArrayList<HashMap<String, String>> arrClientInfoForCartList) {
		this.arrClientInfoForCartList = arrClientInfoForCartList;
	}
	
	public ArrayList<HashMap<String, String>> getCartClientInfoList() {
		return arrClientInfoForCartList;
	}
	
	public String getSalesTax() {
		return salesTax;
	}

	public void setSalesTax(String salesTax) {
		this.salesTax = salesTax;
	}

	public void setSalesTaxValue(String salesTaxValue) {
		this.salesTaxValue = salesTaxValue;
	}
	public String getSalesTaxValue() {
		return salesTaxValue;
	}

	public void setUserShipId(String  userShipId) {
		// TODO Auto-generated method stub
		this.userShipId =  userShipId;
	}
	public String getUserShipId() {
		return userShipId;
	}


	public void setAddress1(String address1) {
		// TODO Auto-generated method stub
		this.address1 =  address1;
	}
	public String getAddress1() {
		return address1;
	}

	public void setAddress2(String address2) {
		// TODO Auto-generated method stub
		this.address2 =  address2;
	}
	public String getAddress2() {
		return address2;
	}
	

	public void setCity(String city) {
		// TODO Auto-generated method stub
		this.city =  city;
	}
	public String getCity() {
		return city;
	}

	public void setState(String state) {
		// TODO Auto-generated method stub
		this.state =  state;
	}
	public String getState() {
		return state;
	}

	public void setZip(String zip) {
		this.zip =  zip;
	}
	public String getZip() {
		return zip;
	}
	public void setCountry(String country) {
		this.country =  country;
		
	}
	public String getCountry() {
		return country;
	}
	public void setCreatedDate(String currentDateandTime) {
		this.currentDateandTime = currentDateandTime;
	}
	public String getCreatedDate() {
		return currentDateandTime;
	}
	
	
	 public static class OrderByID implements Comparator<UserOrder> {

	        @Override
			public int compare(UserOrder lhs, UserOrder rhs) {
	        		//return rhs.offerDiscountValue.compareTo(lhs.offerDiscountValue);
	        		// return lhs.getId() <  lhs.getId() ? -1 :  (lhs.getId() >  lhs.getId() ? 1 : 0);
	        	   int returnVal = 0;

	        	    if(lhs.getId() < rhs.getId()){
	        	        returnVal =  -1;
	        	    }else if(lhs.getId() > rhs.getId()){
	        	        returnVal =  1;
	        	    }else if(lhs.getId() == rhs.getId()){
	        	        returnVal =  0;
	        	    }
	        	    return returnVal;

	        	    }
	        	
			}
	    
/*
	public JSONObject getJsonObjForShippingAddress() {
		return jsonObjForShippingAddress;
	}
	

	public void setJsonObjForShippingAddress(JSONObject jsonObjForShippingAddress) {
		this.jsonObjForShippingAddress = jsonObjForShippingAddress;
	}

	public String getOrderTotal() {
		return orderTotal;
	}

	public void setOrderTotal(String orderTotal) {
		this.orderTotal = orderTotal;
	}

	
	public String getSalesTax() {
		return salesTax;
	}

	public void setSalesTax(String salesTax) {
		this.salesTax = salesTax;
	}

	public void setSalesTaxValue(String salesTaxValue) {
		this.salesTaxValue = salesTaxValue;
	}
	public String getSalesTaxValue() {
		return salesTaxValue;
	}

*/

	
	
	/**/
}
