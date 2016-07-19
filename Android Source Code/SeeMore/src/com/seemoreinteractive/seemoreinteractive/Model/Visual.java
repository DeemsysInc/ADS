package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;


public class Visual implements Serializable {
	private static final long serialVersionUID = 2L;
	private long id;
	private String image;
	private String imageFile;
	private String imageName;	
	private String title;
	private int x;
	private int y;
	private int pid;
	private String prodPrice;
	private String prodName;
	private Marker marker;
	private boolean legalSwitch;
	//private LegalPosition legalPosition;
	private String legalImage;
	private String legalImageFile;
	private String discriminator;
	private String visualUrl;
	private String proimageFile;
	private int clientId;
	private int offer;
	private int triggerId;
	private float RotationX;
	private float RotationY;
	private float RotationZ;
	private float Scale;
	private int AnimateOnRecognition;
	private ArrayList<String> list;
	private ArrayList<String> button = new ArrayList<String>();
	private int modelCount;
	private int productHideImage;
	private String productBgColor;
	private String offerPurchaseUrl;
	private int clientVerticalId;
	private String buyButtonName;
	private String productButtonName;
	private String offerButtonName;
	private String buyButtonUrl;
	private String offerDiscountType;
	private String offerDiscountValue;
	private int visualId; 
	
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
	
	public long getVisualId() {
		return visualId;
	}

	public void setVisualId(int visualId) {
		this.visualId = visualId;
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

	public int getX() {
		return x;
	}

	public void setX(int x) {
		this.x = x;
	}
	public float getScale() {
		return Scale;
	}

	public void setScale(float f) {
		this.Scale = f;
	}
	public int getAnimateOnRecognition() {
		return AnimateOnRecognition;
	}

	public void setAnimateOnRecognition(int AnimateOnRecognition) {
		this.AnimateOnRecognition = AnimateOnRecognition;
	}
	public float getRotationX() {
		return RotationX;
	}

	public void setRotationX(float f) {
		this.RotationX = f;
	}
	public float getRotationY() {
		return RotationY;
	}

	public void setRotationY(float f) {
		this.RotationY = f;
	}
	public float getRotationZ() {
		return RotationZ;
	}

	public void setRotationZ(float f) {
		this.RotationZ = f;
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
/*
	public LegalPosition getLegalPosition() {
		return legalPosition;
	}

	public void setLegalPosition(LegalPosition legalPosition) {
		this.legalPosition = legalPosition;
	}*/

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
	public int getProductId() {
		return pid;
	}

	public void setProductId(int pid) {
		this.pid = pid;
	}
	public String getVisualUrl() {
		return visualUrl;
	}

	public void setVisualUrl(String visualUrl) {
		this.visualUrl = visualUrl;
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
	public void setModel(ArrayList<String> model) {
		this.list = model; 
	}
	public void setModelCount(int modelCount) {		
		this.modelCount = modelCount; 
		
	}
	public int getModelCount() {		
		return modelCount; 
	}
	public void setButton(String btnDetails) {
		this.button.add(btnDetails);
	}
	public ArrayList<String> getModel() {
		return list; 
	}
	public ArrayList<String> getButton() {
		return button; 
	}
	public int getTriggerId() {
		return triggerId;
	}

	public void setTriggerId(int triggerId) {
		this.triggerId = triggerId;
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
	public int getOffer() {
		return offer;
	}

	public void setOffer(int offer) {
		this.offer = offer;
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
	private String instruction;
	public void setInstruction(String instruction) {
		this.instruction = instruction;
	}
	public String getInstruction() {
		return instruction;
	}
	private String eventStartDate;
	public void setCalendarEventStartDate(String eventStartDate) {
		this.eventStartDate = eventStartDate;
	}
	public String getCalendarEventStartDate() {
		return eventStartDate;
	}
	private String eventEndDate;
	public void setCalendarEventEndDate(String eventEndDate) {
		this.eventEndDate = eventEndDate;
	}
	public String getCalendarEventEndDate() {
		return eventEndDate;
	}
	private String eventAllDay;
	public void setCalendarEventAllDay(String eventAllDay) {
		this.eventAllDay = eventAllDay;
	}
	public String getCalendarEventAllDay() {
		return eventAllDay;
	}
	private String eventHasAlarm;
	public void setCalendarEventHasAlarm(String eventHasAlarm) {
		this.eventHasAlarm = eventHasAlarm;
	}
	public String getCalendarEventHasAlarm() {
		return eventHasAlarm;
	}
	private String reminderDays;
	public void setCalendarReminderDays(String reminderDays) {
		this.reminderDays = reminderDays;
	}
	public String getCalendarReminderDays() {
		return reminderDays;
	}

	private String offerName;
	public void setOfferName(String offerName) {
		this.offerName = offerName;
	}
	public String getOfferName() {
		return offerName;
	}

	private String offerImage;
	public void setOfferImage(String offerImage) {
		this.offerImage = offerImage;
	}
	public String getOfferImage() {
		return offerImage;
	}

	private String offerId;
	public void setOfferId(String offerId) {
		this.offerId = offerId;
	}
	public String getOfferId() {
		return offerId;
	}
	private String offerCalendarBased;
	public void setOfferIsCalendarBased(String offerCalendarBased) {
		this.offerCalendarBased = offerCalendarBased;
	}
	public String getOfferIsCalendarBased() {
		return offerCalendarBased;
	}
	public void setProductHideImage(int productHideImage) {
		this.productHideImage = productHideImage;
	}
	public int getProductHideImage() {
		return productHideImage;
	}
	public void setProductBgColor(String productBgColor) {
		this.productBgColor = productBgColor;
	}
	public String getProductBgColor() {
		return productBgColor;
	}
	public void setOfferPurchaseUrl(String offerPurchaseUrl) {
		this.offerPurchaseUrl = offerPurchaseUrl;
	}
	public String getOfferPurchaseUrl() {
		return offerPurchaseUrl;
	}
	public void setClientVerticalId(int clientVerticalId) {
		this.clientVerticalId = clientVerticalId;
	}
	public int getClientVerticalId() {
		return clientVerticalId;
	}
	public void setBuyButtonName(String buyButtonName) {
		this.buyButtonName = buyButtonName;
	}
	public String getBuyButtonName() {
		return buyButtonName;
	}
	
	public void setBuyButtonUrl(String buyButtonUrl) {
		this.buyButtonUrl = buyButtonUrl;
	}
	public String getBuyButtonUrl() {
		return buyButtonUrl;
	}
	
	public void setProductButtonName(String productButtonName) {
		this.productButtonName = productButtonName;
	}
	public String getProductButtonName() {
		return productButtonName;
	}
	
	
	public void setOfferButtonName(String offerButtonName) {
		this.offerButtonName = offerButtonName;
	}
	public String getOfferButtonName() {
		return offerButtonName;
	}
	
	public void setOfferDiscountType(String offerDiscountType) {
		this.offerDiscountType = offerDiscountType;
	}
	public String getOfferDiscountType() {
		return offerDiscountType;
	}
	
	public void setOfferDiscountValue(String offerDiscountValue) {
		this.offerDiscountValue = offerDiscountValue;
	}
	public String getOfferDiscountValue() {
		return offerDiscountValue;
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
	private String offerValidFrom;
	private String offerValidTo;
	private String clientGameId;
	private String gameImage;
	private String gameRules;
	private String gameRulesUrl;
	private String gameDirectionType;
	public void setClientDarkColor(String clientDarkColor) {
		this.clientDarkColor = clientDarkColor;
	}
	public String getClientDarkColor() {
		return clientDarkColor;
	}

	/*private String country2Code;
	public void setCountry2Code(String country2Code) {
		// TODO Auto-generated method stub
		this.country2Code = country2Code;
	}
	public String getCountry2Code() {
		// TODO clientLogo-generated method stub
		return country2Code;
	}*/
	public void setOfferValidFrom(String offerValidFrom) {
		this.offerValidFrom = offerValidFrom;
	}
	public String getOfferValidFrom() {
		return offerValidFrom;
	}


	public void setOfferValidTo(String offerValidTo) {
		this.offerValidTo = offerValidTo;
	}
	public String getOfferValidTo() {
		return offerValidTo;
	}

	public void setClientGameId(String gameId) {
			this.clientGameId = gameId;
	}
	
	public String getClientGameId() {
		return clientGameId;
	}

	public void setGameImage(String gameImage) {
		this.gameImage = gameImage;
	}
	public String getGameImage() {
		return gameImage;
	}

	public void setGameRules(String gameRules) {
		this.gameRules = gameRules;
		
	}
	public String getGameRules() {
		return gameRules;
	}

	public void setGameRuleUrl(String gameRulesUrl) {
		this.gameRulesUrl = gameRulesUrl;
		
		}
	public String getGameRuleUrl() {
		return gameRulesUrl;
	}

	public void setDirectionType(String type) {
		this.gameDirectionType = type;
		
	}
	public String getDirectionType() {
		return gameDirectionType;
	}
	
	/*private List<XmlDom> listXML;
	public void setModelNew(List<XmlDom> listXML) {
		// TODO Auto-generated method stub
		this.listXML = listXML;
	}
	public List<XmlDom> getModelNew() {
		// TODO Auto-generated method stub
		return listXML;
	}*/
	
}
