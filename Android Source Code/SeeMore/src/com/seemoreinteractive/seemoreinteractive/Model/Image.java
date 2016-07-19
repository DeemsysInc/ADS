package com.seemoreinteractive.seemoreinteractive.Model;

import android.graphics.Bitmap;

public class Image {
	static Bitmap characterBitmap = null;
	static Bitmap cameraBitmap = null;
	static Bitmap combinedBitmap = null;
	static Bitmap legalBitmap = null;

	public static Bitmap getCharacterBitmap() {
		return characterBitmap;
	}

	public static void setCharacterBitmap(Bitmap characterBitmap) {
		Image.characterBitmap = characterBitmap;
	}

	public static Bitmap getCameraBitmap() {
		return cameraBitmap;
	}

	public static void setCameraBitmap(Bitmap cameraBitmap) {
		Image.cameraBitmap = cameraBitmap;
	}

	public static Bitmap getCombinedBitmap() {
		return combinedBitmap;
	}

	public static void setCombinedBitmap(Bitmap combinedBitmap) {
		Image.combinedBitmap = combinedBitmap;
	}

	public static Bitmap getLegalBitmap() {
		return legalBitmap;
	}

	public static void setLegalBitmap(Bitmap legalBitmap) {
		Image.legalBitmap = legalBitmap;
	}
	
}
