package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.Comparator;
import java.util.Date;

public class UserMyOffers implements Serializable {

	private static final long serialVersionUID = 5L;
	
	private int id;
	private String offerName;
	private String clientName;
	private String offerClientId;
	private String offerDiscountValue;
	private String offerValidDate;
	private String offerDiscountType;
	private String symbol;
	
	public UserMyOffers() {}
	
	/*
	public UserMyOffers(long id) {
		this.setOfferId(id);
	}*/

	public int getOfferId() {
		return id;
	}

	public void setOfferId(int id) {
		this.id = id;
	}
	
	public void setOfferName(String offerName) {
		this.offerName = offerName;
	}
	public String getOfferName() {
		return offerName;
	}
	public void setOfferClientName(String clientName) {
		this.clientName = clientName;
	}
	public String getOfferClientName() {
		return clientName;
	}
	public String getOfferClientId() {
		return offerClientId;
	}
	public void setOfferClientId(String offerClientId) {
		this.offerClientId = offerClientId;
	}
	public void setOfferDiscountValue(String offerDiscountValue) {
		this.offerDiscountValue = offerDiscountValue;
	}
	public String getOfferDiscountValue() {
		return offerDiscountValue;
	}
	public void setOfferValidDate(String offerValidDate) {
		// TODO Auto-generated method stub
		this.offerValidDate = offerValidDate;
	}
	public String getOfferValidDate() {
		return offerValidDate;
	}
	public void setOfferDiscountType(String offerDiscountType) {
		this.offerDiscountType = offerDiscountType;
	}
	public String getOfferDiscountType() {
		return offerDiscountType;
	}	
	public void setCurrencySymbol(String symbol) {
		this.symbol = symbol;
	}
	public String getCurrencySymbol() {
		return symbol;
	}
	public static class OrderByBrand implements Comparator<UserMyOffers> {

			@Override
			public int compare(UserMyOffers arg0, UserMyOffers arg1) {
				return arg0.clientName.compareTo(arg1.clientName);
			}
	    }
	 
	 public static class OrderByValue implements Comparator<UserMyOffers> {

	        @Override
			public int compare(UserMyOffers lhs, UserMyOffers rhs) {
	        		//return rhs.offerDiscountValue.compareTo(lhs.offerDiscountValue);
	        	 if(!lhs.offerDiscountValue.equals("") && !rhs.offerDiscountValue.equals(""))
	        		 return Integer.parseInt(lhs.offerDiscountValue) <  Integer.parseInt(rhs.offerDiscountValue) ? -1 : (Integer.parseInt(lhs.offerDiscountValue) >  Integer.parseInt(rhs.offerDiscountValue) ? 1 : 0);
	        	 else 
	        		 return -1;
			}
	    }
	
	 public static final class OrderByDate implements Comparator<UserMyOffers> {
		    SimpleDateFormat sdf = new SimpleDateFormat("MM/dd/yyyy");

		    @Override
			public int compare(UserMyOffers ord1, UserMyOffers ord2) {
		        Date d1 = null;
		        Date d2 = null;
		        try {
		            d1 = sdf.parse(ord1.getOfferValidDate());
		            d2 = sdf.parse(ord2.getOfferValidDate());
		        } catch (Exception e) {
		            // TODO Auto-generated catch block
		            e.printStackTrace();
		        }


		        return (d1.getTime() > d2.getTime() ? -1 : 1);     //descending
		    //  return (d1.getTime() > d2.getTime() ? 1 : -1);     //ascending
		    }
		};
}
