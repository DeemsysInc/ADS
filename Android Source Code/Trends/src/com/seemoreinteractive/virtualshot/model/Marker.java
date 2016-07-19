package com.seemoreinteractive.virtualshot.model;

import java.io.Serializable;

public class Marker implements Serializable{
	
	private static final long serialVersionUID = 3L;
	private long id;
	private String image;
	private String imageFile;
	private String title;
	private int width;
	private int height;
	private int index;

	public Marker() {}
	
	public Marker(Long id, String image, String title, int width, int height, int index) {
		this.id = id;
		this.image = image;
		this.title = title;
		this.width = width;
		this.height = height;
		this.index = index;
	}
	
	public Marker(long id) {
		this.setId(id);
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

	public int getWidth() {
		return width;
	}

	public void setWidth(int width) {
		this.width = width;
	}

	public int getHeight() {
		return height;
	}

	public void setHeight(int height) {
		this.height = height;
	}
	
	public int getIndex() {
		return index;
	}

	public void setIndex(int index) {
		this.index = index;
	}

}
