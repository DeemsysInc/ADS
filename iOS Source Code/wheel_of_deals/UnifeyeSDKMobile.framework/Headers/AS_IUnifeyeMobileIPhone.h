/*
 *  UnifeyeSDKMobile
 *
 *  Copyright 2012 metaio GmbH. All rights reserved.
 *
 */
#ifndef ___AS_IUNIFYEMOBILEIPHONE_H_INCLUDED___
#define ___AS_IUNIFYEMOBILEIPHONE_H_INCLUDED___


#include "AS_IUnifeyeMobile.h"
#import <CoreGraphics/CoreGraphics.h>

template class std::vector<metaio::Pose>;   // instantiate template class

@class AVCaptureVideoPreviewLayer;
@class IUnifeyeMobileGeometry;
@class NSString;
@class NSObject;

/** Set to of functions to handle Unifeye SDK Mobile callbacks
*/
@protocol UnifeyeMobileDelegate

@optional

/** This function will be triggered, when an animation has ended
 * \param geometry the geometry which has finished animating
 * \param animationName the name of the just finished animation
 * \return void
 */
- (void) onAnimationEnd: (metaio::IUnifeyeMobileGeometry*) geometry  andName:(NSString*) animationName;


/**
 * \brief This function will be triggered, if a movietexture-playback has ended
 * \param geometry the geometry which has finished animating/movie-playback
 * \param movieName the filename of the movie
 * \return void
 */
- (void) onMovieEnd: (metaio::IUnifeyeMobileGeometry*) geometry  andName:(NSString*) movieName;

/**
 * \brief Request a callback that delivers the next camera image.
 *
 * The image will have the  dimensions of the current capture resolution.
 * To request this callback, call requestCameraFrame()
 *
 * \param cameraFrame the latest camera image
 * 
 * \note you must copy the ImageStuct::buffer, if you need it for later. 
 */
- (void) onNewCameraFrame: ( metaio::ImageStruct*)  cameraFrame;

/**
 * \brief Callback that notifies that camera image has been saved
 *
 * To request this callback, call requestCameraFrame(filepath, width, height)
 *
 * \param filepath File path in which image is written, or empty string in case of a failure
 * 
 */
- (void) onCameraImageSaved: (NSString*) filepath; 


/**
 * \brief Callback that informs new pose states (tracked, detected or lost)
 *
 * This is called automatically as soon as poses have been updated. The vector
 * contains all the valid poses. 
 * The invalid pose is only returned for first frame as soon as target is lost 
 * to inform this event.
 * Note that this function is called in rendering thread, thus it would block
 * rendering. It should be returned as soon as possible wihout any expensive 
 * processing.
 * 
 * \param poses current valid poses
 * 
 */
- (void) onTrackingEvent: (std::vector<metaio::Pose>) poses;


@end


namespace metaio
{

	/** 
	 * \brief Specialized interface for iPhone.
	 * 
	 */
	class IUnifeyeMobileIPhone : public virtual IUnifeyeMobile
	{
	public:
		
        virtual ~IUnifeyeMobileIPhone() {};
        
        /**
         * \brief Initialize the renderer
         *
         * \relates IUnifeyeMobileIPhone
         * \param width width of the renderer
         * \param height height of the renderer
         * \param renderSystem specify which renderer should be used
         */
        virtual void initializeRenderer( int width, int height,  const ERENDER_SYSTEM renderSystem=ERENDER_SYSTEM_OPENGL_ES_2_0) = 0;
        
        /** \brief Register the delegate object that will receive callbacks
         * \param delegate the object
         * \return void
         */
        virtual void registerDelegate( NSObject<UnifeyeMobileDelegate>* delegate ) = 0;
        
        /**
		 * \brief Get a camera preview layer from the active camera session
		 *
		 * Use this to get a pointer to a AVCaptureVideoPreviewLayer that 
		 * is created based on the current camera session. You can use this 
		 * to draw the camera image in the background and add a transparent
		 * EAGLView on top of this. To prevent Unifeye from drawing the 
		 * background in OpenGL you can activate the see-through mode.
		 *
		 * \code 	
		 *			[glView setBackgroundColor:[UIColor clearColor]];
		 *			unifeyeMobile->setSeeThrough(1);
		 *
		 *			AVCaptureVideoPreviewLayer* previewLayer = 
		 *					glView.unifeyeMobile->getCameraPreviewLayer();
		 *			previewLayer.frame = myUIView.bounds;
		 *			[myUIView.layer addSublayer:previewLayer];
		 * \endcode
		 *
		 * \sa Set Unifeye to see through mode using setSeeThrough ( 1 )
		 * \sa Start capturing using activateCamera ( index )
		 * \sa You can deactivate the capturing again with stopCamera()
		 *
		 * \note Only available on iOS >= 4.0. If you call this on 3.x nothing will happen.
		 * \note Not available on iPhone Simulator.
		 */
		virtual AVCaptureVideoPreviewLayer* getCameraPreviewLayer() = 0;    
        
        /**
         * @brief Specialized function for iPhone
         *
         * @param textureName name that should be assigned to the texture 
         *	(for reuse).
         * @param image image to set
         * @return pointer to geometry
         */
        virtual IUnifeyeMobileGeometry* loadImageBillboard( const std::string& textureName, CGImageRef image ) = 0;
    };

    /** Provides access to raw image data of a CGImage.
     * This is e.g. needed when setting an MD2 texture from memory.
     *
	 * \relates IUnifeyeMobileIPhone
	 *
     * \code
     * ImageStruct imageContent;
     * CGContextRef context = nil;
     * 
     * beginGetDataForCGImage(image, &imageContent, &context);
     * 
     *  // use data
     *  // ....
     * endGetData(&context);
     *
     * \endcode
     *
     * \param image the source image
     * \param[out] imageContent after the call this will point to a struct containing the image content
     * \param[out] context after the call this will point to the created CGContext. This has to be deleted again by calling endGetData
     * 
     * \sa endGetData to delegate the context again
     */
     void beginGetDataForCGImage(CGImage* image, ImageStruct* imageContent, CGContextRef* context);
    
    
    /** Frees the image context that was created with beginGetDataForCGImage
     * 
	 * \relates IUnifeyeMobileIPhone
     * \param context the context to free
     * 
     * \sa beginGetDataForCGImage to get data from a CGImage
     */
    void endGetData(CGContextRef* context);


	/**
	* \brief Create an ARMobileSystem instance
	*
	* \relates IUnifeyeMobileIPhone
	* \param signature The signature of the application identifier
	* \return a pointer to an ARMobileSystem instance
	*/
	IUnifeyeMobileIPhone* CreateUnifeyeMobileIPhone( const std::string& signature ); 




} //namespace metaio


#endif //___AS_IUNIFYEMOBILEIPHONE_H_INCLUDED___
