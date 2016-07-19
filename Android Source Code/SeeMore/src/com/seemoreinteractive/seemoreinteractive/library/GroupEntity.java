package com.seemoreinteractive.seemoreinteractive.library;

import java.util.ArrayList;
import java.util.List;

public class GroupEntity {
	public String Name;
	public List<GroupItemEntity> GroupItemCollection;

	public GroupEntity()
	{
		GroupItemCollection = new ArrayList<GroupItemEntity>();
	}

	public class GroupItemEntity
	{
		public String ProdName, ProdQuantity, ProdPrice, ProdImage, ProdColor, ProdSize;
	}
}
