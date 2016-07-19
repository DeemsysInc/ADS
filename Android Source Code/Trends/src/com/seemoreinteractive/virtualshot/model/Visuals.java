package com.seemoreinteractive.virtualshot.model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import android.util.Log;

import com.seemoreinteractive.virtualshot.utils.Constants;

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

	public Visual getVisualByMarkerIndex(int index) {
		
		for (Visual visual : visuals) {
			//Log.e("for Visual", ""+visual+" visual.getMarker:"+visual.getMarker()+" index: "+index+" == "+visual.getMarker().getIndex());
			if (visual.getMarker() != null
					&& visual.getMarker().getIndex() == index)
				return visual;
		}
		return null;
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

	public void removeAll() {
		visuals.clear();
	}

	public void mergeWith(Visuals newVisuals) {
		List<Visual> oldVisuals = new ArrayList<Visual>(visuals);

		for (Visual oldVisual : oldVisuals) {
			Visual match = newVisuals.getVisual(oldVisual.getId());
			if (match != null && match.getMarker() != null
					&& oldVisual.getMarker() != null) {
				if (match.getMarker().getId() == oldVisual.getMarker().getId()) {
					if(match.getImage().equalsIgnoreCase(oldVisual.getImage())){
						match.setImageFile(oldVisual.getImageFile());
					}
					if(match.getMarker().getImage().equalsIgnoreCase(oldVisual.getMarker().getImage())){
						match.getMarker().setImageFile(oldVisual.getMarker().getImageFile());
					}
					if(match.getLegalImage().equalsIgnoreCase(oldVisual.getLegalImage())){
						match.setLegalImageFile(oldVisual.getLegalImageFile());
					}
				}
			}
		}
		visuals = newVisuals.list();
	}

	public List<Visual> getEmptyImageFileVisuals() {
		List<Visual> list = new ArrayList<Visual>();
		for (Visual visual : visuals) {
			if (visual.getImageFile() == null || visual.getImageFile().length() == 0 || visual.getMarker().getImageFile() == null
					|| visual.getMarker().getImageFile().length() == 0) {
				list.add(visual);
			}
		}
		Log.i(Constants.TAG,"Empty List size: "+list.size());
		return list;
	}
}
