package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import android.util.Log;

public class ProductModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserProduct> product;

	public ProductModel() {
		product = new ArrayList<UserProduct>();
	}

	public void add(UserProduct userProduct) {
		product.add(userProduct);
	}

	public UserProduct getUserProduct(long id) {
		for (UserProduct userProduct : product) {
			if (userProduct.getId() == id)
				return userProduct;
		}
		return null;
	}

	
	public UserProduct getUserProductById(int id) {
		for (UserProduct userProduct : product) {
			if (userProduct.getProductId() == id)
				return userProduct;
		}
		return null;
	}
	
	public UserProduct getProductByClientId(int clientId) {
		for (UserProduct userProduct : product) {
			if (userProduct.getClientId() != 0
					&& userProduct.getClientId() == clientId)
				return userProduct;
		}
		return null;
	}	
	
	public List<UserProduct> getRelatedProduct(String pdids) {
		List<UserProduct> list = new ArrayList<UserProduct>();		
		Collections.sort(product, new UserProduct.OrderByPId());
		String [] arrPdid = pdids.split(",");		
		for (UserProduct userProduct : product) {			
	      for(int i=0;i<arrPdid.length;i++){
	    	 if(arrPdid[i] !=""){
	    	   	if (userProduct.getProductId() != 0	&& userProduct.getProductId() == Integer.parseInt(arrPdid[i]))			    	   					    	   			
	    	   		list.add(userProduct);	
	    	   	}
	        }
		 }
		return list;
	}
	
	public int size() {
		return product.size();
	}

	public List<UserProduct> list() {
		return product;
	}

	public void remove(int id) {
		UserProduct userProduct = getUserProduct(id);
		if (userProduct != null)
			product.remove(userProduct);
		return;
	}

	public void removeAll() {
		product.clear();
	}

	public void mergeWith(ProductModel newProduct) {
		List<UserProduct> oldProduct = new ArrayList<UserProduct>(product);

		/*for (Visual oldVisual : oldVisuals) {			
				newVisuals.add(oldVisual);				
			}*/
		
		oldProduct.addAll(newProduct.list());
		
		//visuals = newVisuals.list();
		product = oldProduct;
	}

	

	public void removeItem(UserProduct userproduct) {
		if (userproduct != null)
			product.remove(userproduct);
		return;
	}
}
