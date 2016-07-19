// Copyright 2007-2012 metaio GmbH. All rights reserved.


#ifndef __Example__IGestureHandlerCallback__
#define __Example__IGestureHandlerCallback__

#include <metaioSDK/IMetaioSDK.h>
#include <vector>

namespace metaio
{
	enum EGESTURE_STATE
	{
		EGE_TRANSLATING_START,
		EGE_TRANSLATING_END,
		EGE_ON_TRANSLATED,
		
		EGE_SCALING_START,
		EGE_SCALING_END,
		EGE_ON_SCALED,

		EGE_ROTATING_START,
		EGE_ROTATING_END,
		EGE_ON_ROTATED,
		
		EGE_NONE
	};

	enum ETOUCH_STATE
	{
		ETS_TOUCH_DOWN,
		ETS_TOUCH_UP
	};
	
	
	/** Interface for handling gesturehandler callbacks
	 */
	class IGestureHandlerCallback
	{
	public:
		virtual ~IGestureHandlerCallback(){};
		
		
		/** Informs the callback about a gesture that is happening
		 * \param gesture_state the gesture state
		 * \param geometries involved geometries
		 * \param groupID the groupID of the geometries
		 */
		virtual void onGestureEvent(EGESTURE_STATE gesture_state , const std::vector<metaio::IGeometry*> geometries, int groupID) = 0;
		
		/** Informs the callback that a geometry has been picked
		 * \param state picked on touch down or touch up
		 * \param geometry the geometry that has been picked
		 */
		virtual void onGeometryPicked(ETOUCH_STATE state, metaio::IGeometry* geometry ) = 0;
	};

} // namespace metaio

#endif /* defined(__Example__IGestureHandlerCallback__) */