package com.seemoreinteractive.seemoreinteractive.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import android.util.Log;


public class Visuals implements Serializable {
	private static final long serialVersionUID = 1L;
	List<Visual> visuals;

	public Visuals() {
		visuals = new ArrayList<Visual>();
	}

	public void add(Visual visual) {
		visuals.add(visual);
	}

	public Visual getVisual(long visualId) {
		for (Visual visual : visuals) {
			if (visual.getId() == visualId)
				return visual;
		}
		return null;
	}

	public Visual getVisualByVID(int visualId) {
		for (Visual visual : visuals) {
			if (visual.getVisualId() == visualId)
				return visual;
		}
		return null;
	}
	
	public Visual getVisualByMarkerIndex(int index) {
		for (Visual visual : visuals) {
			if (visual.getMarker() != null
					&& visual.getMarker().getIndex() == index)
				return visual;
		}
		return null;
	}
	public Visual getVisualByClientId(int clientId) {
		for (Visual visual : visuals) {
			if (visual.getClientId() != 0
					&& visual.getClientId() == clientId)
				return visual;
		}
		return null;
	}

	public Visual getVisualByOfferId(int offerId) {
		for (Visual visual : visuals) {
			if (Integer.parseInt(visual.getOfferId()) != 0
					&& Integer.parseInt(visual.getOfferId()) == offerId)
				return visual;
		}
		return null;
	}

	
	public List<Visual> getVisualByClientDetail(int clientId) {
		
			
			List<Visual> list = new ArrayList<Visual>();
			for (Visual visual : visuals) {
				if (visual.getClientId() != 0 && visual.getClientId() == clientId)
					list.add(visual);
				}
			
			return list;
		}
	
	public List<Visual> getVisualByTriggerId(int triggerId) {		
		
		List<Visual> list = new ArrayList<Visual>();
		for (Visual visual : visuals) {
			//Log.i("visual", ""+visual.getTriggerId()+" == "+triggerId);
			if (visual.getTriggerId() != 0 && visual.getTriggerId() == triggerId)
				list.add(visual);
		}
		
		return list;
	}
		
	public int size() {
		return visuals.size();
	}

	public List<Visual> list() {
		return visuals;
	}

	public void remove(int visualId) {
		Visual visual = getVisual(visualId);
		if (visual != null)
			visuals.remove(visual);
		return;
	}

	public void removeVisual(int visualId) {
		Visual visual = getVisualByVID(visualId);
		Log.e("visual",""+visual.getProductName());
		Log.e("visuals size before remove",""+visuals.size());
		if (visual != null){
			visuals.remove(visual);
		Log.e("visuals size after remove",""+visuals.size());
		}
		return;
	}
	public List<Visual> getAllVisual() {
		
		return visuals;
	}

	public void removeAll() {
		visuals.clear();
	}

	public void mergeWith(Visuals newVisuals) {
		List<Visual> oldVisuals = new ArrayList<Visual>(visuals);

		/*for (Visual oldVisual : oldVisuals) {			
				newVisuals.add(oldVisual);				
			}*/
		
		oldVisuals.addAll(newVisuals.list());
		
		//visuals = newVisuals.list();
		visuals = oldVisuals;
	}

	public List<Visual> getEmptyImageFileVisuals() {
		List<Visual> list = new ArrayList<Visual>();
		for (Visual visual : visuals) {
			if (visual.getImageFile() == null || visual.getImageFile().length() == 0 || visual.getMarker().getImageFile() == null
					|| visual.getMarker().getImageFile().length() == 0) {
				list.add(visual);
			}
		}
		Log.i("TAG","Empty List size: "+list.size());
		return list;
	}
}
