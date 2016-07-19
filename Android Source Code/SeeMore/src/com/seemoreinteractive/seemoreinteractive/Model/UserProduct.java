package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.Comparator;


public class UserProduct implements Serializable {
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
	private int offer;
	private String clientName;
	public UserProduct() {
	}

	public UserProduct(long id) {
		this.setId(id);
	}

	public UserProduct(long id, long markerId) {
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
		// TODO Auto-generated method stub
		this.prodPrice = prodPrice;
	}
	public String getProductPrice() {
		// TODO Auto-generated method stub
		return prodPrice;
	}

	public void setProductName(String prodName) {
		// TODO Auto-generated method stub
		this.prodName = prodName;
	}
	public String getProductName() {
		// TODO Auto-generated method stub
		return prodName;
	}

	private String clientLogo;
	public void setClientLogo(String clientLogo) {
		// TODO Auto-generated method stub
		this.clientLogo = clientLogo;
	}
	public String getClientLogo() {
		// TODO clientLogo-generated method stub
		return clientLogo;
	}

	private String clientBackgroundImage;
	public void setClientBackgroundImage(String clientBackgroundImage) {
		// TODO Auto-generated method stub
		this.clientBackgroundImage = clientBackgroundImage;
	}
	public String getClientBackgroundImage() {
		// TODO clientLogo-generated method stub
		return clientBackgroundImage;
	}

	private String clientBackgroundColor;
	public void setClientBackgroundColor(String clientBackgroundColor) {
		// TODO Auto-generated method stub
		this.clientBackgroundColor = clientBackgroundColor;
	}
	public String getClientBackgroundColor() {
		// TODO clientLogo-generated method stub
		return clientBackgroundColor;
	}
	private String productShortDesc;
	public void setProductShortDesc(String productShortDesc) {
		// TODO Auto-generated method stub
		this.productShortDesc = productShortDesc;
	}
	public String getProductShortDesc() {
		// TODO clientLogo-generated method stub
		return productShortDesc;
	}
	private String productDesc;
	public void setProductDesc(String productDesc) {
		// TODO Auto-generated method stub
		this.productDesc = productDesc;
	}
	public String getProductDesc() {
		// TODO clientLogo-generated method stub
		return productDesc;
	}
	private String prodTapForDetailsImg;
	public void setProdTapForDetailsImg(String prodTapForDetailsImg) {
		// TODO Auto-generated method stub
		this.prodTapForDetailsImg = prodTapForDetailsImg;
	}
	public String getProdTapForDetailsImg() {
		// TODO clientLogo-generated method stub
		return prodTapForDetailsImg;
	}
	private String prodTapForDetailsImgId;
	public void setProdTapForDetailsImgId(String prodTapForDetailsImgId) {
		// TODO Auto-generated method stub
		this.prodTapForDetailsImgId = prodTapForDetailsImgId;
	}
	public String getProdTapForDetailsImgId() {
		// TODO clientLogo-generated method stub
		return prodTapForDetailsImgId;
	}
	private String prodTapForDetailsImgPdId;
	public void setProdTapForDetailsImgPdId(String prodTapForDetailsImgPdId) {
		// TODO Auto-generated method stub
		this.prodTapForDetailsImgPdId = prodTapForDetailsImgPdId;
	}
	public String getProdTapForDetailsImgPdId() {
		// TODO clientLogo-generated method stub
		return prodTapForDetailsImgPdId;
	}
	private String productUrl;
	public void setProductUrl(String productUrl) {
		// TODO Auto-generated method stub
		this.productUrl = productUrl;
	}
	public String getProductUrl() {
		// TODO clientLogo-generated method stub
		return productUrl;
	}
	private String clientUrl;
	public void setClientUrl(String clientUrl) {
		// TODO Auto-generated method stub
		this.clientUrl = clientUrl;
	}
	public String getClientUrl() {
		// TODO clientLogo-generated method stub
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
	private String offerIds;
	private String buttonName;
	private String locationBased;
	public void setClosetFlag(Boolean closetFlag) {
		this.closetFlag = closetFlag;
	}
	
	public void setProductRelatedId(String prodIds) {
		// TODO Auto-generated method stub
		this.prodIds = prodIds;
	}
	public String getProductRelatedId() {
		// TODO Auto-generated method stub
		return prodIds;
	}
	public void setOfferRelatedId(String offerIds) {
		this.offerIds = offerIds;
	}
	public String getOfferRelatedId() {
		return offerIds;
	}
	public void setButtonName(String buttonName) {
		this.buttonName = buttonName;
	}
	public String getButtonName() {
		return buttonName;
	}
	public void setClientLocationBased(String locationBased) {
		this.locationBased = locationBased;
		
	}
	public String getClientLocationBased() {
		return locationBased;
	}

	 public static class OrderByBrand implements Comparator<UserProduct> {

			@Override
			public int compare(UserProduct arg0, UserProduct arg1) {
				// TODO Auto-generated method stub
				return arg0.clientName.compareTo(arg1.clientName);
			}
	    }

	 public static class OrderByPId implements Comparator<UserProduct> {
	        @Override
			public int compare(UserProduct lhs, UserProduct rhs) {
	        		//return rhs.offerDiscountValue.compareTo(lhs.offerDiscountValue);
	        	 //if(!lhs.pid.equals("") && !rhs.prodIds.equals(""))
	        		// return Integer.parseInt(lhs.prodIds) <  Integer.parseInt(rhs.prodIds) ? -1 : (Integer.parseInt(lhs.prodIds) >  Integer.parseInt(rhs.prodIds) ? 1 : 0);
	        	if(lhs.pid != 0  && rhs.pid != 0 )
	        		return lhs.pid <  rhs.pid ? -1 : (lhs.pid > rhs.pid ? 1 : 0);
	        	 else 
	        		 return -1;
			}
	    }
	



}
