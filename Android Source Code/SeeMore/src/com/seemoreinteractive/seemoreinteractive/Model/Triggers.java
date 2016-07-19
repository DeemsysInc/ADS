package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class Triggers implements Serializable {
	private static final long serialVersionUID = 3L;
	
	List<ClientTriggers> ClientTriggers;

	public Triggers() {
		ClientTriggers = new ArrayList<ClientTriggers>();
	}

	
	public void add(ClientTriggers clientTriggers) {
		ClientTriggers.add(clientTriggers);
	}

	public ClientTriggers getClientTrigger(long clientId) {
		for (ClientTriggers clientTriggers : ClientTriggers) {
			if (clientTriggers.getId() == clientId)
				return clientTriggers;
		}
		return null;
	}
	
	public ClientTriggers getClientDetails(long id) {
		for (ClientTriggers clientTriggers : ClientTriggers) {
			if (clientTriggers.getId() == id)
				return clientTriggers;
		}
		return null;
	}
	
	public List<ClientTriggers> getAllTrigger() {	
		
		List<ClientTriggers> list = new ArrayList<ClientTriggers>();
		for (ClientTriggers clientTriggers : ClientTriggers) {			
				list.add(clientTriggers);
			}
		
		return list;
	}

	public ClientTriggers getTriggers() {
		for (ClientTriggers clientTriggers : ClientTriggers) {			
				return clientTriggers;
		}
		return null;
	}
	public int size() {
		return ClientTriggers.size();
	}
	public List<ClientTriggers> list() {
		return ClientTriggers;
	}

	public void mergeWithTriggers(Triggers newTriggers) {
		// TODO Auto-generated method stub
		List<ClientTriggers> oldTriggers = new ArrayList<ClientTriggers>(ClientTriggers);

		/*for (Visual oldVisual : oldVisuals) {			
				newVisuals.add(oldVisual);				
			}*/
		
		oldTriggers.addAll(newTriggers.list());
		
		//visuals = newVisuals.list();
		ClientTriggers = oldTriggers;
		
	}
	public ArrayList<String> getClientGrpIds(String clientIds)
	{
		ArrayList<String> clientGrpIds = new ArrayList<String>();
		String[] arrClientIds = clientIds.split(",");
		for(int i=0;i<arrClientIds.length;i++){
			if(!arrClientIds[i].equals("") || arrClientIds[i] != null){				
				for (ClientTriggers clientTriggers : ClientTriggers){
					if (clientTriggers.getClientId().equals(arrClientIds[i])){
						clientGrpIds.add(""+clientTriggers.getId());
					}						
				}
			}
		}
		return clientGrpIds;
	}

}
