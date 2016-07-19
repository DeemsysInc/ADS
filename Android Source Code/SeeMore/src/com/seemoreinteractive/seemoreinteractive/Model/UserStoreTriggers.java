package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class UserStoreTriggers implements Serializable {
	private static final long serialVersionUID = 8L;
	
	List<StoreTriggers> StoreTriggers;

	public UserStoreTriggers() {
		StoreTriggers = new ArrayList<StoreTriggers>();
	}

	
	public void add(StoreTriggers storeTriggers) {
		StoreTriggers.add(storeTriggers);
	}

	public StoreTriggers getClientStores(String clientId) {
		for (StoreTriggers storeTriggers : StoreTriggers) {
			if (storeTriggers.getClientId() == clientId)
				return storeTriggers;
		}
		return null;
	}
	
	public StoreTriggers getClientStoreList(String storeCode) {
		for (StoreTriggers storeTriggers : StoreTriggers) {
			if(storeTriggers.getStoreCode().equals(storeCode))
				return storeTriggers;
		}
		return null;
	}
	
	public List<StoreTriggers> getAllUserStoresList(String storeCode) {	
		
		List<StoreTriggers> list = new ArrayList<StoreTriggers>();
		for (StoreTriggers storeTriggers : StoreTriggers) {		
			if (storeTriggers.getStoreCode().equals(storeCode)){
				list.add(storeTriggers);
			}
			}
		
		return list;
	}

	public StoreTriggers getStores() {
		for (StoreTriggers storeTriggers : StoreTriggers) {			
				return storeTriggers;
		}
		return null;
	}
	public int size() {
		return StoreTriggers.size();
	}
	public List<StoreTriggers> list() {
		return StoreTriggers;
	}

	public void mergeWithStores(UserStoreTriggers newStores) {
		// TODO Auto-generated method stub
		List<StoreTriggers> oldStores = new ArrayList<StoreTriggers>(StoreTriggers);

		/*for (Visual oldVisual : oldVisuals) {			
				newVisuals.add(oldVisual);				
			}*/
		
		oldStores.addAll(newStores.list());
		
		//visuals = newVisuals.list();
		StoreTriggers = oldStores;
		
	}
	
	

}
