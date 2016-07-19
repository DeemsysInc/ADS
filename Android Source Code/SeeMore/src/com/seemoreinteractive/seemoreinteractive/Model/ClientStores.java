package com.seemoreinteractive.seemoreinteractive.Model;
import java.io.Serializable;

public class ClientStores implements Serializable{
	
	private static final long serialVersionUID = 7L;
	private long id;
	private String storeCode;
	private String clientId;
	private String storeName;
	private String searchType;
	private double latitude;
	private double longitude;
	private String address1;
	private String address2;
	private String city;
	private String state;
	private String zip;
	private String email;
	private String phone;
	private String storeNotifyMsg;
	private String storeTriggerThreshold;
	private String clientName;
	private long storeTriggerUpdate;
	

	public ClientStores() {}
	
	
	public ClientStores(long id) {
		this.setId(id);
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getStoreCode() {
		return storeCode;
	}

	public void setStoreCode(String storeCode) {
		this.storeCode = storeCode;
	}


	public void setClientId(String clientId) {
		// TODO Auto-generated method stub
		this.clientId = clientId;
	}
	public String getClientId() {
		return clientId;
	}
	
	public void setStoreName(String storeName) {
		// TODO Auto-generated method stub
		this.storeName = storeName;
	}
	public String getStoreName() {
		return storeName;
	}
	
	
	public void setStoreSearchType(String searchType) {
		// TODO Auto-generated method stub
		this.searchType = searchType;
	}
	public String getStoreSearchType() {
		return searchType;
	}
	public void setLatitude(double latitude) {
		// TODO Auto-generated method stub
		this.latitude = latitude;
	}
	public double getLatitude() {
		return latitude;
	}
	public void setLongitude(double longitude) {
		// TODO Auto-generated method stub
		this.longitude = longitude;
	}
	public double getLongitude() {
		return longitude;
	}
	
	
	public void setAddress1(String address1) {
		// TODO Auto-generated method stub
		this.address1 = address1;
	}
	public String getAddress1() {
		return address1;
	}
	
	public void setAddress2(String address2) {
		// TODO Auto-generated method stub
		this.address2 = address2;
	}
	public String getAddress2() {
		return address2;
	}
	
	
	public void setCity(String city) {
		// TODO Auto-generated method stub
		this.city = city;
	}
	public String getCity() {
		return city;
	}
	
	
	
	public void setState(String state) {
		// TODO Auto-generated method stub
		this.state = state;
	}
	public String getState() {
		return state;
	}
	
	
	public void setZip(String zip) {
		// TODO Auto-generated method stub
		this.zip = zip;
	}
	public String getZip() {
		return zip;
	}
	public void setEmail(String email) {
		// TODO Auto-generated method stub
		this.email = email;
	}
	public String getEmail() {
		return email;
	}
	
	
	public void setPhone(String phone) {
		// TODO Auto-generated method stub
		this.phone = phone;
	}
	public String getPhone() {
		return phone;
	}
	public void setStoreNotifyMsg(String storeNotifyMsg) {
		// TODO Auto-generated method stub
		this.storeNotifyMsg = storeNotifyMsg;
	}
	public String getStoreNotifyMsg() {
		return storeNotifyMsg;
	}
	
	public void setStoreTriggerThreshold(String storeTriggerThreshold) {
		// TODO Auto-generated method stub
		this.storeTriggerThreshold = storeTriggerThreshold;
	}
	public String getStoreTriggerThreshold() {
		return storeTriggerThreshold;
	}
	
	public void setStoreTriggerUpdate(long storeTriggerUpdate) {
		// TODO Auto-generated method stub
		this.storeTriggerUpdate = storeTriggerUpdate;
	}
	public long getStoreTriggerUpdate() {
		return storeTriggerUpdate;
	}
	public void setStoreClientName(String clientName) {
		// TODO Auto-generated method stub
		this.clientName = clientName;
	}
	public String getStoreClientName() {
		return clientName;
	}
}
