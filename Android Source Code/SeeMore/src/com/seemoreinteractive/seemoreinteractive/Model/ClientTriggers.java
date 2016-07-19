package com.seemoreinteractive.seemoreinteractive.Model;
import java.io.Serializable;

public class ClientTriggers implements Serializable{
	
	private static final long serialVersionUID = 4L;
	private long id;
	private String image;
	private String clientId;
	private String status;
	private String triggerId;

	public ClientTriggers() {}
	
	
	public ClientTriggers(long id) {
		this.setId(id);
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getClientImage() {
		return image;
	}

	public void setClientImage(String image) {
		this.image = image;
	}


	public void setClientId(String clientId) {
		// TODO Auto-generated method stub
		this.clientId = clientId;
	}
	public String getClientId() {
		return clientId;
	}
	
	public void setClientTriggerStatus(String status) {
		// TODO Auto-generated method stub
		this.status = status;
	}
	public String getClientTriggerStatus() {
		return status;
	}

	public void setTriggerId(String triggerId) {		
		this.triggerId = triggerId;
	}
	public String getTriggerId() {
		return triggerId;
	}
}
