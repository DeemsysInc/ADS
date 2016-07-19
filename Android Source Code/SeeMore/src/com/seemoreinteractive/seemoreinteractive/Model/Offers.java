package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import android.util.Log;

public class Offers  implements Serializable{
	
private static final long serialVersionUID = 5L;
	
	List<UserOffers> UserOffers;
	List<UserMyOffers> UserMyOffers;
	public Offers() {
		UserOffers = new ArrayList<UserOffers>();
		UserMyOffers= new ArrayList<UserMyOffers>();
	}
	public void add(UserOffers userOffers) {
		UserOffers.add(userOffers);
	}
	public UserOffers getUserOffers(int offerId) {
		for (UserOffers userOffers : UserOffers) {
			if (userOffers.getOfferId() == offerId)
				return userOffers;
		}
		return null;
	}
	public List<UserOffers> getAllOffers() {	
		
		List<UserOffers> list = new ArrayList<UserOffers>();
		for (UserOffers userOffers : UserOffers) {			
				list.add(userOffers);
			}
		
		return list;
	}
	
	public List<UserOffers> getAllOffersByBrand() {	
		
		List<UserOffers> list = new ArrayList<UserOffers>();
		Collections.sort(UserOffers, new UserOffers.OrderByBrand());
		for (UserOffers userOffers : UserOffers) {				
				list.add(userOffers);
			}
		
		return list;
	}

	public List<UserOffers> getAllOffersByValue() {		
		List<UserOffers> list = new ArrayList<UserOffers>();
		Collections.sort(UserOffers, new UserOffers.OrderByValue());
		for(int j = UserOffers.size() - 1; j >= 0; j--){			
			list.add(UserOffers.get(j));
	    }	
		return list;
	}
	
	public List<UserOffers> getAllOffersByExpiration() {		
		List<UserOffers> list = new ArrayList<UserOffers>();
		Collections.sort(UserOffers, new UserOffers.OrderByDate());
		for (UserOffers userOffers : UserOffers) {				
				list.add(userOffers);
			}		
		return list;
	}
	
	public void removeItem(UserOffers userOffers) {
		if (userOffers != null)
			UserOffers.remove(userOffers);
		return;
	}
	
	
	public List<UserOffers> getUserRedeemOffers(long offerId) {
		List<UserOffers> list = new ArrayList<UserOffers>();
		for (UserOffers userOffers : UserOffers) {
			if (userOffers.getOfferId() == offerId)
				list.add(userOffers);
		}
		return list;
	}
	public UserOffers getTriggers() {
		for (UserOffers userOffers : UserOffers) {			
				return userOffers;
		}
		return null;
	}
	public int size() {
		return UserOffers.size();
	}
	public List<UserOffers> list() {
		return UserOffers;
	}

	public void mergeWithOffers(Offers userOffers) {
		List<UserOffers> oldOffers = new ArrayList<UserOffers>(UserOffers);
		oldOffers.addAll(userOffers.list());
		UserOffers = oldOffers;		
	}

	public void addUserMyOffers(UserMyOffers usermyOffers) {
		UserMyOffers.add(usermyOffers);
	}
	
	public int msize() {
		return UserMyOffers.size();
	}
	public List<UserMyOffers> mlist() {
		return UserMyOffers;
	}
	public void mergeWithMyOffers(Offers userMyOffers) {
		List<UserMyOffers> oldOffers = new ArrayList<UserMyOffers>(UserMyOffers);
		oldOffers.addAll(userMyOffers.mlist());
		UserMyOffers = oldOffers;
		
	}
	public UserMyOffers getUserMyOffers(int offerId) {
		for (UserMyOffers usermyOffers : UserMyOffers) {
			if (usermyOffers.getOfferId() == offerId)
				return usermyOffers;
		}
		return null;
	}
	public List<UserMyOffers> getAllMyOffers() {		
		List<UserMyOffers> list = new ArrayList<UserMyOffers>();
		for (UserMyOffers userMyOffers : UserMyOffers) {			
				list.add(userMyOffers);
			}
		
		return list;
	}
	public List<UserMyOffers> getAllMyOffersByBrand() {			
		List<UserMyOffers> list = new ArrayList<UserMyOffers>();
		Collections.sort(UserMyOffers, new UserMyOffers.OrderByBrand());
		for (UserMyOffers userMyOffers : UserMyOffers) {				
				list.add(userMyOffers);
			}
		
		return list;
	}

	
	
	public List<UserOffers> getRelatedOffers(String offerIds) {
		List<UserOffers> list = new ArrayList<UserOffers>();
		String [] arrPdid = offerIds.split(",");
		
		for (UserOffers userOffers : UserOffers) {
			Log.e("userOffers.getOfferId() model",""+userOffers.getOfferId());
			for(int i=0;i<arrPdid.length;i++){
			if (userOffers.getOfferId() != 0	&& userOffers.getOfferId() == Integer.parseInt(arrPdid[i]))
				list.add(userOffers);
			}
				
		}
		return list;
	}
	
	
	public List<UserMyOffers> getAllMyOffersByValue() {		
		List<UserMyOffers> list = new ArrayList<UserMyOffers>();
		Collections.sort(UserMyOffers, new UserMyOffers.OrderByValue());
		for(int j = UserMyOffers.size() - 1; j >= 0; j--){			
			list.add(UserMyOffers.get(j));
	    }	
		return list;
	}
	
	public List<UserMyOffers> getAllMyOffersByExpiration() {		
		List<UserMyOffers> list = new ArrayList<UserMyOffers>();
		Collections.sort(UserMyOffers, new UserMyOffers.OrderByDate());
		for (UserMyOffers userOffers : UserMyOffers) {				
				list.add(userOffers);
			}		
		return list;
	}
	
	
	public void removeOffer(UserMyOffers userMyOffer) {
		if (userMyOffer != null)
			UserMyOffers.remove(userMyOffer);
		return;
		
	}

/*
	public  List<UserOffers> removeofferById(long offerId) {
		// TODO Auto-generated method stub
		for (UserOffers userOffers : UserOffers) {
		if (userOffers.getOfferId() == offerId)			
				removeItem(userOffers);
		}
		return UserOffers;
	}*/


	public void updateOffers(List<UserOffers> userOffers) {
		UserOffers = userOffers;
		
	}

	public void updateMyOffers(List<UserMyOffers> userMyOffers) {
		UserMyOffers = userMyOffers;
		
	}
	
	public List<UserMyOffers> getAllMyOffersByClient(String clientId) {		
		List<UserMyOffers> list = new ArrayList<UserMyOffers>();
		for (UserMyOffers userMyOffers : UserMyOffers) {	
			if(clientId.equals(userMyOffers.getOfferClientId())){
				list.add(userMyOffers);
			}
		}		
		return list;
	}

}
