package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class ClosetModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserCloset> closet;

	public ClosetModel() {
		closet = new ArrayList<UserCloset>();
	}

	public void add(UserCloset userCloset) {
		closet.add(userCloset);
	}

	public UserCloset getUserCloset(long id) {
		for (UserCloset userCloset : closet) {
			if (userCloset.getId() == id)
				return userCloset;
		}
		return null;
	}

	
	public UserCloset getClosetByClientId(int clientId) {
		for (UserCloset userCloset : closet) {
			if (userCloset.getClientId() != 0
					&& userCloset.getClientId() == clientId)
				return userCloset;
		}
		return null;
	}
	public UserCloset getProductDetails(int pdid) {
		for (UserCloset userCloset : closet) {
			if (userCloset.getProductId() != 0
					&& userCloset.getProductId() == pdid)
				return userCloset;
		}
		return null;
	}
	
	public List<UserCloset> getProductByBrands(String pdids) {
		List<UserCloset> list = new ArrayList<UserCloset>();
		String [] arrPdid = pdids.split(",");
		for (UserCloset userCloset : closet) {
			for(int i=0;i<arrPdid.length;i++){
			if (userCloset.getProductId() != 0	&& userCloset.getProductId() == Integer.parseInt(arrPdid[i]) && userCloset.getClosetFlag() == true)
				list.add(userCloset);
			}
				
		}
		return list;
	}
	
	public List<UserCloset> getProductsByBrand() {	
		
		List<UserCloset> list = new ArrayList<UserCloset>();
		Collections.sort(closet, new UserCloset.OrderByBrand());
		for (UserCloset userCloset : closet) {	
			if(userCloset.getClosetFlag() == true)
				list.add(userCloset);
			}
		
		return list;
	}
	public List<UserCloset>  getClosetList() {
		List<UserCloset> list = new ArrayList<UserCloset>();
		for (UserCloset usercloset : closet) {		
				if(usercloset.getClosetFlag() == true)
					list.add(usercloset);
			}
		return list;
	
	}
	public List<UserCloset>  getClosetListByFlag(String selectionStatus) {
		List<UserCloset> list = new ArrayList<UserCloset>();
		for (UserCloset usercloset : closet) {		
			if (!selectionStatus.equals("0") && usercloset.getClosetSelectionStatus().equals(selectionStatus) && usercloset.getClosetFlag() == true ){
					list.add(usercloset);
			}
			}
		return list;
	
	}
	
	public int size() {
		return closet.size();
	}
	public List<UserCloset> list() {
		return closet;
	}
	public void remove(int id) {
		UserCloset userCloset = getUserCloset(id);
		if (userCloset != null)
			closet.remove(userCloset);
		return;
	}
	public void removeAll() {
		closet.clear();
	}

	public void mergeWith(ClosetModel newCloset) {
		List<UserCloset> oldCloset = new ArrayList<UserCloset>(closet);		
		oldCloset.addAll(newCloset.list());
		closet = oldCloset;
	}
}
