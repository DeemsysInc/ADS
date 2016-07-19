package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;

public class UserChangeLog implements Serializable {
	private static final long serialVersionUID = 2L;
	private int id;	
	private int clientId;
	private int prodId;
	private int offerId;
	private int triggerId;
	private int visualId;
	private int triggerVisualId;
	private String createdDate;
	
	public UserChangeLog() {
	}

	public UserChangeLog(int id) {
		this.setId(id);
	}

	

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public int getClientId() {
		return clientId;
	}
	public void setClientId(int clientId) {
		this.clientId = clientId;
	}
	

	public int getProdtId() {
		return prodId;
	}
	public void setProdtId(int prodId) {
		this.prodId = prodId;
	}	

	public int getTriggerVisualId() {
		return triggerVisualId;
	}
	public void setTriggerVisualId(int triggerVisualId) {
		this.triggerVisualId = triggerVisualId;
	}
	
	public int getTriggerId() {
		return triggerId;
	}
	public void setTriggerId(int triggerId) {
		this.triggerId = triggerId;
	}
	

	public int getOfferId() {
		return offerId;
	}
	public void setOfferId(int offerId) {
		this.offerId = offerId;
	}
	

	public int getVisulaId() {
		return visualId;
	}
	public void setVisualId(int visualId) {
		this.visualId = visualId;
	}
	
	public String getCreatedDate() {
		return createdDate;
	}
	public void setCreatedDate(String createdDate) {
		this.createdDate = createdDate;
	}

}