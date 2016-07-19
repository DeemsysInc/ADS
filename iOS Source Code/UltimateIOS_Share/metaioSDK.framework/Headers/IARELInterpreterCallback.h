// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef _IAREL_INTERPRETER_CALLBACK_H_
#define _IAREL_INTERPRETER_CALLBACK_H_

#include <string>


namespace metaio
{

/**
 * Interface to receive the callbacks form the ARELInterpreter
 */
class IARELInterpreterCallback
{
public:

	/**
	* Destructor.
	*/
	virtual ~IARELInterpreterCallback() {};

	/** 
	 * The implementation should play a video from a given URL
	 * \param videoURL the url to the video
	 * \return true if handled by the callback, false to use the default implementation
	 */
	virtual bool playVideo(const std::string& videoURL) {return false;}

	/**
	 * Open the URL
	 * \param url the url to the website
	 * \param openInExternalApp true to open in external app, false otherwise
	 * \return true if handled by the callback, false to use the default implementation
	 */
	virtual bool openWebsite(const std::string& url, bool openInExternalApp = false){return false;}

	/**
	 * This is triggered as soon as the SDK is ready, e.g. splash screen is finished.
	 */
	virtual void onSDKReady() {};
	
	/**
	 * This is triggered as soon as the AREL is ready, including the loading of XML geometries. 
	 */
	virtual void onSceneReady() {};

};
}

#endif
