package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.Comparator;


public class UserCloset implements Serializable {
	private static final long serialVersionUID = 2L;
	private long id;
	private String image;
	private String imageFile;
	private String imageName;	
	private String title;
	private int pid;
	private String prodPrice;
	private String prodName;
	private String discriminator;
	private String proimageFile;
	private int clientId;
	private String clientName;
	public UserCloset() {
	}

	public UserCloset(long id) {
		this.setId(id);
	}

	public UserCloset(long id, long markerId) {
		this(id);
		
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

	
	public String getDiscriminator() {
		return discriminator;
	}

	public void setDiscriminator(String discriminator) {
		this.discriminator = discriminator;
	}

	public void setImageName(String imageName) {
		this.imageName = imageName;
	}
	public String getImageName() {
		return imageName;
	}
	public int getProductId() {
		return pid;
	}

	public void setProductId(int pid) {
		this.pid = pid;
	}
	

	public String getProImageFile() {
		return proimageFile;
	}

	public void setProImageFile(String imageFile) {
		this.proimageFile = imageFile;
	}
	public int getClientId() {
		return clientId;
	}

	public void setClientId(int id) {
		this.clientId = id;
	}
	
	public String getClientName() {
		return clientName;
	}

	public void setClientName(String clientName) {
		this.clientName = clientName;
	}
	public void setProductPrice(String prodPrice) {
		this.prodPrice = prodPrice;
	}
	public String getProductPrice() {
		return prodPrice;
	}

	public void setProductName(String prodName) {
		this.prodName = prodName;
	}
	public String getProductName() {
		return prodName;
	}

	private String clientLogo;
	public void setClientLogo(String clientLogo) {
		this.clientLogo = clientLogo;
	}
	public String getClientLogo() {
		
		return clientLogo;
	}

	private String clientBackgroundImage;
	public void setClientBackgroundImage(String clientBackgroundImage) {
		this.clientBackgroundImage = clientBackgroundImage;
	}
	public String getClientBackgroundImage() {
		
		return clientBackgroundImage;
	}

	private String clientBackgroundColor;
	public void setClientBackgroundColor(String clientBackgroundColor) {
		this.clientBackgroundColor = clientBackgroundColor;
	}
	public String getClientBackgroundColor() {
		
		return clientBackgroundColor;
	}
	private String productShortDesc;
	public void setProductShortDesc(String productShortDesc) {
		this.productShortDesc = productShortDesc;
	}
	public String getProductShortDesc() {
		
		return productShortDesc;
	}
	private String productDesc;
	public void setProductDesc(String productDesc) {
		this.productDesc = productDesc;
	}
	public String getProductDesc() {
		
		return productDesc;
	}
	private String prodTapForDetailsImg;
	public void setProdTapForDetailsImg(String prodTapForDetailsImg) {
		this.prodTapForDetailsImg = prodTapForDetailsImg;
	}
	public String getProdTapForDetailsImg() {
		
		return prodTapForDetailsImg;
	}
	private String prodTapForDetailsImgId;
	public void setProdTapForDetailsImgId(String prodTapForDetailsImgId) {
		this.prodTapForDetailsImgId = prodTapForDetailsImgId;
	}
	public String getProdTapForDetailsImgId() {
		
		return prodTapForDetailsImgId;
	}
	private String prodTapForDetailsImgPdId;
	public void setProdTapForDetailsImgPdId(String prodTapForDetailsImgPdId) {
		this.prodTapForDetailsImgPdId = prodTapForDetailsImgPdId;
	}
	public String getProdTapForDetailsImgPdId() {
		
		return prodTapForDetailsImgPdId;
	}
	private String productUrl;
	public void setProductUrl(String productUrl) {
		this.productUrl = productUrl;
	}
	public String getProductUrl() {
		
		return productUrl;
	}
	private String clientUrl;
	public void setClientUrl(String clientUrl) {
		this.clientUrl = clientUrl;
	}
	public String getClientUrl() {
		return clientUrl;
	}
	private int prodIsTryOn;
	public void setProdIsTryOn(int prodIsTryOn) {
		this.prodIsTryOn = prodIsTryOn;
	}
	public int getProdIsTryOn() {
		return prodIsTryOn;
	}

	private String clientLightColor;
	public void setClientLightColor(String clientLightColor) {
		this.clientLightColor = clientLightColor;
	}
	public String getClientLightColor() {
		return clientLightColor;
	}

	private String clientDarkColor;
	public void setClientDarkColor(String clientDarkColor) {
		this.clientDarkColor = clientDarkColor;
	}
	public String getClientDarkColor() {
		return clientDarkColor;
	}
	
	private String closetSelectionStatus;
	public void setClosetSelectionStatus(String closetSelectionStatus) {
		this.closetSelectionStatus = closetSelectionStatus;
	}
	public String getClosetSelectionStatus() {
		return closetSelectionStatus;
	}
	private Boolean closetFlag;
	private String prodIds;
	public void setClosetFlag(Boolean closetFlag) {
		this.closetFlag = closetFlag;
	}
	
	public void setProductRelatedId(String prodIds) {
		this.prodIds = prodIds;
	}
	public String getProductRelatedId() {
		return prodIds;
	}

	public Boolean getClosetFlag() {
		return closetFlag;
	}
	
	 public static class OrderByBrand implements Comparator<UserCloset> {

			@Override
			public int compare(UserCloset arg0, UserCloset arg1) {
				return arg0.clientName.compareTo(arg1.clientName);
			}
	    }
}
