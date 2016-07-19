package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.Comparator;
import java.util.Date;

public class UserOffers implements Serializable {

	private static final long serialVersionUID = 5L;
	
	private int id;
	private String image;
	private String offerName;
	private String offerImage;
	private String offerDiscountValue;
	private String offerDiscountType;
	private String offerPurchaseUrl;
	private String offerValidDate;
	private String clientName;
	private String offerDescription;
	private String clientVerticalId;
	private String clientBgColor = "null";
	private String clientBgLightColor = "null";
	private String clientBgDarkColor = "null";

	private String offerButtonName;
	private String offerClientId;
	private String offerRelatedlId;
	private String prodRelatedlId;

	private String symbol;

	private String locationbased;

	private String offerIsSharable;

	private String offerBackImage;

	private String offerMultiRedeem;
	
	public UserOffers() {}
	
	
/*	public UserOffers(long id) {
		this.setOfferId(id);
	}
*/
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
	
	
	public void setOfferImage(String offerImage) {
		this.offerImage = offerImage;
	}
	public String getOfferImage() {
		return offerImage;
	}
	

	public void setOfferClientName(String clientName) {
		this.clientName = clientName;
	}
	public String getOfferClientName() {
		return clientName;
	}
	
	public void setOfferDiscountValue(String offerDiscountValue) {
		this.offerDiscountValue = offerDiscountValue;
	}
	public String getOfferDiscountValue() {
		return offerDiscountValue;
	}
	public void setOfferDiscountType(String offerDiscountType) {
		this.offerDiscountType = offerDiscountType;
	}
	public String getOfferDiscountType() {
		return offerDiscountType;
	}
	
	public void setOfferPurchaseUrl(String offerPurchaseUrl) {
		this.offerPurchaseUrl = offerPurchaseUrl;
	}
	public String getofferPurchaseUrl() {
		return offerPurchaseUrl;
	}
	

	public void setOfferClientBgColor(String clientBgColor) {
		this.clientBgColor = clientBgColor;
	}
	public String getOfferClientBgColor() {
		return clientBgColor;
	}
	public void setOfferClientBgLightColor(String clientBgLightColor) {
		this.clientBgLightColor = clientBgLightColor;
	}
	public String getOfferClientBgLightColor() {
		return clientBgLightColor;
	}
	public void setOfferClientBgDarkColor(String clientBgDarkColor) {
		this.clientBgDarkColor = clientBgDarkColor;
	}
	public String getOfferClientBgDarkColor() {
		return clientBgDarkColor;
	}

	public void setOfferValidDate(String offerValidDate) {
		this.offerValidDate = offerValidDate;
	}
	public String getOfferValidDate() {
		return offerValidDate;
	}
	
	public String getOfferDescription() {
		return offerDescription;
	}
	public void setOfferDescription(String offerDescription) {
		this.offerDescription = offerDescription;
	}
	public String getClientVerticalId() {
		return clientVerticalId;
	}
	public void setClientVerticalId(String clientVerticalId) {
		this.clientVerticalId = clientVerticalId;
	}
	public String getOfferButtonName() {
		return offerButtonName;
	}
	public void setOfferButtonName(String offerButtonName) {
		this.offerButtonName = offerButtonName;
	}
	public void setCurrencySymbol(String symbol) {
		this.symbol = symbol;
	}
	public String getCurrencySymbol() {
		return symbol;
	}
	public String getOfferClientId() {
		return offerClientId;
	}
	public void setOfferClientId(String offerClientId) {
		this.offerClientId = offerClientId;
	}

	public void setOfferRelatedId(String offerRelatedlId) {
		this.offerRelatedlId = offerRelatedlId;
	}
	public String getOfferRelatedId() {
		return offerRelatedlId;
	}
	
	public void setProdRelatedId(String prodRelatedlId) {
		this.prodRelatedlId = prodRelatedlId;
	}
	public String getProdRelatedId() {
		return prodRelatedlId;
	}

   public void setClientLocationBased(String locationbased) {			
		 this.locationbased = locationbased;
	}
	 public String getClientLocationBased() {
			return locationbased;
	}	 
	 public void setOfferIsSharable(String offerIsSharable) {
		 this.offerIsSharable = offerIsSharable;
			
	  }
	 public String getOfferIsSharable() {
			return offerIsSharable;
	}
	 public static class OrderByBrand implements Comparator<UserOffers> {
			@Override
			public int compare(UserOffers arg0, UserOffers arg1) {
				return arg0.clientName.compareTo(arg1.clientName);
			}
	    }
	

	/* public static class OrderByValue implements Comparator<UserOffers> {

	        @Override
			public int compare(UserOffers lhs, UserOffers rhs) {
	        	Log.e("lhs.offerDiscountValue",""+lhs.offerDiscountValue);
	        	Log.e("rhs.offerDiscountValue",""+rhs.offerDiscountValue);
	        	 if(!lhs.offerDiscountValue.equals("") && !rhs.offerDiscountValue.equals(""))
	        		return	(Integer.parseInt(lhs.offerDiscountValue) >  Integer.parseInt(rhs.offerDiscountValue)) ? -1: (Integer.parseInt(lhs.offerDiscountValue) > Integer.parseInt(rhs.offerDiscountValue)) ? 1:0;
	        	else
	        		return 0;
	        	
	        	return lhs.offerDiscountValue.compareTo(rhs.offerDiscountValue);
				// TODO Auto-generated method stub
				// return Integer.parseInt(lhs.offerDiscountValue) > Integer.parseInt(rhs.offerDiscountValue) ? 1 : (Integer.parseInt(lhs.offerDiscountValue) < Integer.parseInt(rhs.offerDiscountValue) ? -1 : 0);
			}
	    }*/

	
	 public static class OrderByValue implements Comparator<UserOffers> {

	        @Override
			public int compare(UserOffers lhs, UserOffers rhs) {
	        		//return rhs.offerDiscountValue.compareTo(lhs.offerDiscountValue);
	        	 if(!lhs.offerDiscountValue.equals("") && !rhs.offerDiscountValue.equals(""))
	        		 return Integer.parseInt(lhs.offerDiscountValue) <  Integer.parseInt(rhs.offerDiscountValue) ? -1 : (Integer.parseInt(lhs.offerDiscountValue) >  Integer.parseInt(rhs.offerDiscountValue) ? 1 : 0);
	        	 else 
	        		 return -1;
			}
	    }
	
	 public static final class OrderByDate implements Comparator<UserOffers> {
		    SimpleDateFormat sdf = new SimpleDateFormat("MM/dd/yyyy");

		    @Override
			public int compare(UserOffers ord1, UserOffers ord2) {
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
		}

	public void setOfferBackImage(String offerBackImage) {		
		 this.offerBackImage = offerBackImage;
	}
	
	 public String getOfferBackImage() {
			return offerBackImage;
	}


	public void setOfferMultiRedeem(String offerMultiRedeem) {
		this.offerMultiRedeem = offerMultiRedeem;		
	}
	
	public String getOfferMultiRedeem() {
		return offerMultiRedeem;
     }
	


}
