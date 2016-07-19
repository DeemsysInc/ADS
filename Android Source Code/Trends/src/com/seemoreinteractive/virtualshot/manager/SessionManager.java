package com.seemoreinteractive.virtualshot.manager;

import com.seemoreinteractive.virtualshot.utils.LegalPosition;

public class SessionManager {
	private static SessionManager instance;
	private LegalPosition legalPosition;

	private SessionManager() {
	}

	public static SessionManager getInstance() {
		if (instance == null) {
			instance = new SessionManager();
		}
		return instance;
	}

	@Override
	public Object clone() throws CloneNotSupportedException {
		throw new CloneNotSupportedException();
	}

	public LegalPosition getLegalPosition() {
		return legalPosition;
	}

	public void setLegalPosition(LegalPosition legalPosition) {
		this.legalPosition = legalPosition;
	}

}
