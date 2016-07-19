package com.seemoreinteractive.seemoreinteractive.Model;
import java.io.Serializable;

public class Model implements Serializable{
	
	private static final long serialVersionUID = 3L;
	private long id;
	private String image;
	private String imageFile;
	private String material;
	private String texture;
	

	public Model() {}
	
	
	public Model(long id) {
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

	public String getModelFile() {
		return imageFile;
	}

	public void setModelFile(String imageFile) {
		this.imageFile = imageFile;
	}

	public String getTexture() {
		return texture;
	}

	public void setTexture(String texture) {
		this.texture = texture;
	}
	public String getMaterial() {
		return material;
	}

	public void setMaterial(String material) {
		this.material = material;
	}
	

}
