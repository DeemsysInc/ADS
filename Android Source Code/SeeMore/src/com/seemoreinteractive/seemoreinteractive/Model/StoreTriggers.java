package com.seemoreinteractive.seemoreinteractive.Model;
import java.io.Serializable;
import java.util.Date;

public class StoreTriggers implements Serializable{
	
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
	private Date createdDate;
	private Date updatedDate;
	private int noofvisits;
	

	public StoreTriggers() {}
	
	
	public StoreTriggers(long id) {
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
	
	
	public void setCreatedDate(Date createdDate) {
		// TODO Auto-generated method stub
		this.createdDate = createdDate;
	}
	public Date getCreatedDate() {
		return createdDate;
	}
	
	public void setUpdatedDate(Date updatedDate) {
		// TODO Auto-generated method stub
		this.updatedDate = updatedDate;
	}
	public Date getUpdatedDate() {
		return updatedDate;
	}
	
	
	public void setVisits(int noofvisits) {
		// TODO Auto-generated method stub
		this.noofvisits = noofvisits;
	}
	public int getVisits() {
		return noofvisits;
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
}
