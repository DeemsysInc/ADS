package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;


public class OrderModel implements Serializable {
	private static final long serialVersionUID = 1L;
	List<UserOrder> order;

	public OrderModel() {
		order = new ArrayList<UserOrder>();
	}

	public void add(UserOrder userOrder) {
		order.add(userOrder);
	}

	public UserOrder getChangeLog() {
		for (UserOrder userOrder : order) {			
				return userOrder;
		}
		return null;
	}
	public UserOrder getUserOrder(int id) {
		for (UserOrder useOrder : order) {
			if (useOrder.getId() == id)
				return useOrder;
		}
		return null;
	}
	public List<UserOrder> getUserOrderList() {
		List<UserOrder> list = new ArrayList<UserOrder>();
		for (UserOrder userOrder : order) {			
			list.add(userOrder);
		}
		return list;
	}
	public List<UserOrder> list() {
		return order;
	}
	public void mergeWith(OrderModel neworder) {
		List<UserOrder> oldOrders = new ArrayList<UserOrder>(order);		
		oldOrders.addAll(neworder.list());
		order = oldOrders;
	}
	public void remove(int id) {
		UserOrder userOrder = getUserOrder(id);
		if (userOrder != null)
			order.remove(userOrder);
		return;
	}
	public void updateOrder(List<UserOrder> userOrder) {
		order = userOrder;
		
	}

	public void update(UserOrder existUserOrder) {
		// TODO Auto-generated method stub
		UserOrder userOrder = getUserOrder(existUserOrder.getId());
		if (userOrder != null)
			order.remove(userOrder);
			order.add(existUserOrder);
		
	}
	public List<UserOrder> getAllMyOffersByID() {		
		Collections.sort(order, new UserOrder.OrderByID());		
		return order;
	}
	
}
