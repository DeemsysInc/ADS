package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;


public class ProfileModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserProfile> profile;

	public ProfileModel() {
		profile = new ArrayList<UserProfile>();
	}

	public void add(UserProfile userProfile) {
		profile.add(userProfile);
	}

	public UserProfile getUserProfile() {
		for (UserProfile userProfile : profile) {			
				return userProfile;
		}
		return null;
	}

	public int size() {
		return profile.size();
	}

	public List<UserProfile> list() {
		return profile;
	}

	
	public void removeAll() {
		profile.clear();
	}

}
