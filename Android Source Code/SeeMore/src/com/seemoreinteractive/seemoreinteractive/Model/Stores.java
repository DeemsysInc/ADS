package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class Stores implements Serializable {
	private static final long serialVersionUID = 8L;
	
	List<ClientStores> ClientStores;

	public Stores() {
		ClientStores = new ArrayList<ClientStores>();
	}

	
	public void add(ClientStores clientStores) {
		ClientStores.add(clientStores);
	}

	public ClientStores getClientStores(String clientId) {
		for (ClientStores clientStores : ClientStores) {
			if (clientStores.getClientId() == clientId)
				return clientStores;
		}
		return null;
	}
	
	public ClientStores getClientStoreDetails(long id) {
		for (ClientStores clientStores : ClientStores) {
			if (clientStores.getId() == id)
				return clientStores;
		}
		return null;
	}
	
	public List<ClientStores> getAllClientStores(String clientId) {	
		
		List<ClientStores> list = new ArrayList<ClientStores>();
		for (ClientStores clientStores : ClientStores) {		
			if (clientStores.getClientId().equals(clientId)){
				list.add(clientStores);
			}
			}
		
		return list;
	}

	public ClientStores getStores() {
		for (ClientStores clientStores : ClientStores) {			
				return clientStores;
		}
		return null;
	}
	public int size() {
		return ClientStores.size();
	}
	public List<ClientStores> list() {
		return ClientStores;
	}

	public void mergeWithStores(Stores newStores) {
		// TODO Auto-generated method stub
		List<ClientStores> oldStores = new ArrayList<ClientStores>(ClientStores);

		/*for (Visual oldVisual : oldVisuals) {			
				newVisuals.add(oldVisual);				
			}*/
		
		oldStores.addAll(newStores.list());
		
		//visuals = newVisuals.list();
		ClientStores = oldStores;
		
	}

}
