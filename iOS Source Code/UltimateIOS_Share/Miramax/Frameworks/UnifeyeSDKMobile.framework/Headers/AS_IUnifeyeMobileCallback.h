#ifndef __AS_UNIFEYE_CALLBACK__
#define __AS_UNIFEYE_CALLBACK__

#include <string> 
#include <vector>

namespace metaio
{
	// forward declarations
	class IUnifeyeMobileGeometry;

	/**
	* The Unifeye SDK Mobile Callback interface. 
	* 
	* These functions should be implemented for handling events triggered by the mobile SDK. 
	*/
	class IUnifeyeMobileCallback 
	{
    public:

        /** Virtual destructor */
        virtual ~IUnifeyeMobileCallback() {};
    
        
        /**
         * \brief This function will be triggered, if an animation has ended
         * \param geometry the geometry which has finished animating
         * \param animationName the name of the just finished animation or in case of movie-playback the filename of the movie
         * \return void
         */
        virtual void onAnimationEnd( metaio::IUnifeyeMobileGeometry* geometry, std::string animationName) = 0;

		/**
         * \brief This function will be triggered, if an animation/movietexture-playback has ended
         * \param geometry the geometry which has finished animating/movie-playback
         * \param animationName the name of the just finished animation or in case of movie-playback the filename of the movie
         * \return void
         */
        virtual void onMovieEnd( metaio::IUnifeyeMobileGeometry* geometry, std::string movieName) = 0;
        
        /**
		* \brief Callback that delivers the next camera image.
		*
		* The image will have the dimensions of the current capture resolution.
        * To request this callback, call requestCameraFrame()
		*
        * \param cameraFrame the latest camera image
        * 
		* \note you must copy the ImageStuct::buffer, if you need it for later. 
        */
        virtual void onNewCameraFrame( metaio::ImageStruct*  cameraFrame) = 0;


		/**
		* \brief Callback that notifies that camera image has been saved
		*
        * To request this callback, call requestCameraFrame(filepath, width, height)
		*
        * \param filepath File path in which image is written, or empty string in case of a failure
        * 
        */
		virtual void onCameraImageSaved( const std::string& filepath ) = 0;

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
		virtual void onTrackingEvent(std::vector<metaio::Pose> poses) = 0;
	};


}


#endif