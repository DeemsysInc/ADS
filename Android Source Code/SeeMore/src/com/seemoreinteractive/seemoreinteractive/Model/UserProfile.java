package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;


public class UserProfile implements Serializable {
	private static final long serialVersionUID = 2L;
	private long id;
	private String pid;
	private int clientId;
	private String wishListName;
	private String pdName;
	private String pdImage;
	private String pdTryon;
	private String wishListCreatedDate;
	private String username;
	private String firstName;
	private String lastname;
	private String dateofbirth;
	private String phone;
	private String email;
	private String address1;
	private String address2;
	private String city;
	private String state;
	private String country;
	private String gender;
	private String zip;
	
	public UserProfile() {
	}

	public UserProfile(long id) {
		this.setId(id);
	}

	public UserProfile(long id, long markerId) {
		this(id);
		
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getUserName() {
		return username;
	}

	public void setUserName(String username) {
		this.username = username;
	}
	
	public String getFirstName() {
		return firstName;
	}

	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}
	public void setLastname(String lastname) {
		this.lastname = lastname;	
	}
	public String getLastname() {
		// TODO Auto-generated method stub
		return lastname;
	}
	
	
	public void setGender(String gender) {
		// TODO Auto-generated method stub
		this.gender = gender;
	}
	
	public String getGender() {
		// TODO Auto-generated method stub
		return gender;
	}
	
	public void setDateofBirth(String dateofbirth) {
		// TODO Auto-generated method stub
		this.dateofbirth = dateofbirth;
	}
	
	public String getDateofBirth() {
		// TODO Auto-generated method stub
		return dateofbirth;
	}

	public void setPhone(String phone) {
		// TODO Auto-generated method stub
		this.phone = phone;
	}
	public String getPhone() {
		// TODO Auto-generated method stub
		return phone;
	}
	
	public void setEmail(String email) {
		// TODO Auto-generated method stub
		this.email = email;
	}
	public String getEmail() {
		
		// TODO Auto-generated method stub
		return email;
	}
	public void setAddress1(String address1) {
		// TODO Auto-generated method stub
		this.address1 = address1;
	}

	public String getAddress1() {
		// TODO Auto-generated method stub
		return address1;
	}
	
	public void setAddress2(String address2) {
		// TODO Auto-generated method stub
		this.address2 = address2;
	}

	public String getAddress2() {
		// TODO Auto-generated method stub
		return address2;
	}
	
	public void setCity(String city) {
		// TODO Auto-generated method stub
		this.city = city;
	}
	public String getCity() {
		// TODO Auto-generated method stub
		return city;
	}
	
	public void setState(String state) {
		// TODO Auto-generated method stub
		this.state = state;
	}
	public String getState() {
		// TODO Auto-generated method stub
		return state;
	}
	public void setCountry(String country) {
		// TODO Auto-generated method stub
		this.country = country;
	}
	public String getCountry() {
		// TODO Auto-generated method stub
		return country;
	}
	
	public void setZip(String zip) {
		// TODO Auto-generated method stub
		this.zip = zip;
	}
	
	public String getZip() {
		// TODO Auto-generated method stub
		return zip;
	}
	
}


