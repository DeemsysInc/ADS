// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef _AS_IBILLBOARDGROUP_H_
#define _AS_IBILLBOARDGROUP_H_

#include <set>
#include <map>
#include <list>

namespace metaio
{
// forward declarations
class IGeometry;

//! Takes a set of billboards and reorders them in space.
/** All billboards within the set are placed in space relative to each other.
*	First the billboard distance to the global origin (3d camera position)
*	is adjusted and then the billboards are arranged in clip space that they
*	don't overlap anymore
*/
class IBillboardGroup
{
public:
	//! creates a new billboard group
	//IBillboardGroup(float nearValue, float farValue) = 0;

	//! destroys a billboard group
	virtual ~IBillboardGroup() {};

	//! Adds a new billboard
	/**
	* \param billboard The billboard which is added
	* \return bool True if the billboard could be added else false.
	*/
	virtual bool addBillboard(IGeometry* billboard) = 0;

	//! Removes a billboard
	/**
	* \param billboard The billboard which is removed
	* \return bool True if the billboard could be removed else false.
	*/
	virtual bool removeBillboard(IGeometry* billboard) = 0;


	//! Sets the minimum and maximum distance of the billboards
	/** All visible billboards are placed relative to each other
	*	away from the camera in the range [nearValue, farValue].
	*	The distance between each poi within this range is linear.
	*	\param nearValue Minimum poi to camera distance
	*	\param farValue Maximum poi to camera distance
	*/
	virtual void setViewCompressionValues(float nearValue, float farValue) = 0;

	//! Sets the distance weight factor.
	/** As higher the distance weight, as closer come billboards which are far
	*	away from the screen. The formula where the weight is used is
	*	pow( billboardDistance/maximumBillboardDistance, weight). Usually a value
	*	around 10 gives good results. default is 10.
	*	\param weight The distance weight factor
	*/
	virtual void setDistanceWeightFactor(int weight) = 0;

	//! Sets the billboard expand factor.
	/** The billboard expand factors influence how much the billboards are expands
	*	relative to the center of the camera view.
	*	\param expand A value which should range from [0...1]. It defines how much the
	*		billboards are expanded as they come closer to the center of the camera view.
	*		Fully expanded is 1 and 0 is no expansion at all. The default value is 0.8.
	*	\param strength The strength factor defines how much the billboards around the center
	*		are influenced by the expansion. A low value will allow billboards at the edges
	*		of the screen to expand a bit, whereas a high value will only allow
	*		the billboards wich are close to the camera view center to expand. The default
	*       value is 5.
	*	\param maxOverlap If the number of billboards exceeds this number, the distance
	*		between them decreases drastically. This prevents high stacks. The default value
	*       is 10.
	*/
	virtual void setBillboardExpandFactors(float expand, int strength, int maxOverlap = 10) = 0;



};
}

#endif //_AS_IBILLBOARDGROUP_H_