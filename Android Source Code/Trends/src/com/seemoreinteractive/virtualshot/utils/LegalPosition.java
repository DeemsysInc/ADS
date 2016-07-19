package com.seemoreinteractive.virtualshot.utils;

public enum LegalPosition {
	TOP_CENTER("TOP_CENTER"), TOP_LEFT("TOP_LEFT"), TOP_RIGHT("TOP_RIGHT"), BOTTOM_CENTER("BOTTOM_CENTER"), BOTTOM_LEFT("BOTTOM_LEFT"), BOTTOM_RIGHT("BOTTOM_RIGHT");

	private final String value;

	LegalPosition(String value) {
		this.value = value;
	}

	public static LegalPosition fromValue(String value) {
		if (value != null) {
			for (LegalPosition legalPosition : values()) {
				if (legalPosition.value.equals(value)) {
					return legalPosition;
				}
			}
		}
		return null;
	}
	
	@Override
	public String toString(){
		return this.value;
	}
}
