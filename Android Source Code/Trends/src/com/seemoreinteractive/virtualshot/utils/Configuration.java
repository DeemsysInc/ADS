package com.seemoreinteractive.virtualshot.utils;


public abstract class Configuration 
{
	public static final String signature = "iKXKnjffWgpHhMe8AsqDtRnpQS2XK6HI1JeJKqf7br4=";

	public abstract class Camera
	{
		public static final long resolutionX = 480; //480  	
		public static final long resolutionY = 320; //320
		/*
		 * 0: normal camera
		 * 1: front facing camera
		 */
		public static final int deviceId = 0;
	}

}
