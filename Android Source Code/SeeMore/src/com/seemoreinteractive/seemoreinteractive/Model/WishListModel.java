package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import android.util.Log;


public class WishListModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserWishList> wishlist;

	public WishListModel() {
		wishlist = new ArrayList<UserWishList>();
	}

	public void add(UserWishList userWishList) {
		wishlist.add(userWishList);
	}

	public UserWishList getUserWishList(long id) {
		for (UserWishList userWishList : wishlist) {
			if (userWishList.getId() == id)
				return userWishList;
		}
		return null;
	}

	
	public UserWishList getUserWishlistById(int wishlistId) {
		for (UserWishList userWishList : wishlist) {
			if (userWishList.getId() != 0
					&& userWishList.getId() == wishlistId)
				return userWishList;
		}
		return null;
	}
	
	
	
	public List<UserWishList>  getUserWishlistByName(String wishlistname) {
		List<UserWishList> list = new ArrayList<UserWishList>();
		for (UserWishList userWishList : wishlist) {		
			if (userWishList.getWishListName().equals(wishlistname))
					list.add(userWishList);
			}
		return list;
	
	}
	
	public List<UserWishList> getWishlistDesc() {	
		ArrayList<String> wishlistResArrays = new ArrayList<String>();
		List<UserWishList> list = new ArrayList<UserWishList>();
	//	Collections.sort(wishlist, new UserWishList.OrderByDate());
		Collections.sort(wishlist, new UserWishList.OrderByWishlist());	
		for (UserWishList userWishList : wishlist) {	
			if(!wishlistResArrays.contains(userWishList.getWishListName())){
				list.add(userWishList);
				wishlistResArrays.add(userWishList.getWishListName());
			}
			}
		
		
		
		
		return list;
	}
	
	
	public List<UserWishList> getAllWishlistDesc() {	
		ArrayList<String> wishlistResArrays = new ArrayList<String>();
		List<UserWishList> list = new ArrayList<UserWishList>();
	//	Collections.sort(wishlist, new UserWishList.OrderByDate());
		Collections.sort(wishlist, new UserWishList.OrderByWishlist());	
		for (UserWishList userWishList : wishlist) {				
				list.add(userWishList);		
			}
		return list;
	}
	
	
public List<UserWishList> getAllWishlist() {	

		ArrayList<String> wishlistResArrays = new ArrayList<String>();
		List<UserWishList> list = new ArrayList<UserWishList>();
		Collections.sort(wishlist, new UserWishList.OrderByDate());
	
		for (UserWishList userWishList : wishlist) {
			Log.e("name",userWishList.getWishListName());
			if(!wishlistResArrays.contains(userWishList.getWishListName())){
				list.add(userWishList);
				wishlistResArrays.add(userWishList.getWishListName());
			}
		}
		
		return list;
	}
	
	public int size() {
		return wishlist.size();
	}

	public List<UserWishList> list() {
		return wishlist;
	}

	
	public void removeAll() {
		wishlist.clear();
	}

	public UserWishList getProductDetails(String pdid) {
		
		for (UserWishList userWishList : wishlist) {
			if (!userWishList.getProductId().equals("0") && userWishList.getProductId().equals(pdid)){
				return userWishList;
			}
		}
		return null;
	}
	


}
