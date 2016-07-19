package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.Comparator;
import java.util.Date;

import android.net.ParseException;


public class UserWishList implements Serializable {
	private static final long serialVersionUID = 2L;
	private long id;
	private String pid;
	private int clientId;
	private String wishListName;
	private String pdName;
	private String pdImage;
	private String pdTryon;
	private String wishListCreatedDate;
	private String clientName;
	private String prodPrice;
	
	public UserWishList() {
	}

	public UserWishList(long id) {
		this.setId(id);
	}

	public UserWishList(long id, long markerId) {
		this(id);
		
	}

	public long getId() {
		return id;
	}

	public void setId(long id) {
		this.id = id;
	}

	public String getProductId() {
		return pid;
	}

	public void setProductId(String pdid) {
		this.pid = pdid;
	}
	
	public String getWishListName() {
		return wishListName;
	}

	public void setWishListName(String wishListName) {
		this.wishListName = wishListName;
	}
	public void setWishListCreatedDate(String wishListdate) {
		this.wishListCreatedDate = wishListdate;
	}
	
	public String getWishListCreatedDate() {
		return wishListCreatedDate;
	}
	public void setClientId(int clientId) {
		// TODO Auto-generated method stub
		this.clientId = clientId;
	}	
	public int getClientId() {
		return clientId;
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
	public void setProductName(String pdName) {
		this.pdName = pdName;
	}
	public String getProductName() {
		return pdName;
	}
	
	public void setProductImage(String pdImage) {
		this.pdImage = pdImage;
	}
	public String getProductImage() {
		return pdImage;
	}
	public void setProductIsTryon(String pdTryon) {
		this.pdTryon = pdTryon;
	}
	public String getProductIsTryon() {
		return pdTryon;
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
	private String clientBackgroundColor;
	public void setClientBackgroundColor(String clientBackgroundColor) {
		this.clientBackgroundColor = clientBackgroundColor;
	}
	public String getClientBackgroundColor() {
		return clientBackgroundColor;
	}
	
	private String clientLogo;
	public void setClientLogo(String clientLogo) {
		this.clientLogo = clientLogo;
	}
	public String getClientLogo() {
		return clientLogo;
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
	public static class OrderByDate implements Comparator<UserWishList> {
		    SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
		    @Override
			public int compare(UserWishList ord1, UserWishList ord2) {
		        Date d1 = null;
		        Date d2 = null;
		        
		            try {
					d1 =  sdf.parse(ord1.getWishListCreatedDate());				
		            d2 =  sdf.parse(ord2.getWishListCreatedDate());
		        } catch (ParseException e) {
		            // TODO Auto-generated catch block
		            e.printStackTrace();
		        }catch (java.text.ParseException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

		       // return (d1.getTime() > d2.getTime() ? -1 : 1);     //descending
		      return (d1.getTime() > d2.getTime() ? 1 : -1);     //ascending
		    }
		};
		public static class OrderByWishlist implements Comparator<UserWishList> {
			@Override
			public int compare(UserWishList arg0, UserWishList arg1) {
				// TODO Auto-generated method stub
				return	(arg0.id >  arg1.id )? -1: (arg0.id > arg1.id) ? 1:0;	        	
			}
		}
		public static class OrderByWishlistASC implements Comparator<UserWishList> {
			@Override
			public int compare(UserWishList arg0, UserWishList arg1) {
				// TODO Auto-generated method stub
				return	(arg0.id >  arg1.id )? 1: (arg0.id > arg1.id) ?-1:0;	        	
			}
		}

}


