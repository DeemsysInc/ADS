package com.seemoreinteractive.virtualshot.model;

import java.io.Serializable;

import com.seemoreinteractive.virtualshot.utils.LegalPosition;

public class Visual implements Serializable {
	private static final long serialVersionUID = 2L;
	private long id;
	private String image;
	private String imageFile;
	private String title;
	private int x;
	private int y;
	private Marker marker;
	private boolean legalSwitch;
	private LegalPosition legalPosition;
	private String legalImage;
	private String legalImageFile;

	public Visual() {
	}

	public Visual(long id) {
		this.setId(id);
	}

	public Visual(long id, long markerId) {
		this(id);
		this.setMarker(new Marker(markerId));
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getImage() {
		return image;
	}

	public void setImage(String image) {
		this.image = image;
	}

	public String getImageFile() {
		return imageFile;
	}

	public void setImageFile(String imageFile) {
		this.imageFile = imageFile;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public int getX() {
		return x;
	}

	public void setX(int x) {
		this.x = x;
	}

	public int getY() {
		return y;
	}

	public void setY(int y) {
		this.y = y;
	}

	public Marker getMarker() {
		return marker;
	}

	public void setMarker(Marker marker) {
		this.marker = marker;
	}

	public boolean isLegalSwitch() {
		return legalSwitch;
	}

	public void setLegalSwitch(boolean legalSwitch) {
		this.legalSwitch = legalSwitch;
	}

	public LegalPosition getLegalPosition() {
		return legalPosition;
	}

	public void setLegalPosition(LegalPosition legalPosition) {
		this.legalPosition = legalPosition;
	}

	public String getLegalImage() {
		return legalImage;
	}

	public void setLegalImage(String legalImage) {
		this.legalImage = legalImage;
	}

	public String getLegalImageFile() {
		return legalImageFile;
	}

	public void setLegalImageFile(String legalImageFile) {
		this.legalImageFile = legalImageFile;
	}

}
