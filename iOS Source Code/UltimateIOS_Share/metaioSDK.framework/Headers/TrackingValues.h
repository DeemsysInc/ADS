// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef _AS_TRACKINGVALUES_H_
#define _AS_TRACKINGVALUES_H_

#include <metaioSDK/MobileStructs.h>
#include <metaioSDK/Rotation.h>


namespace metaio
{
/** \brief enum describing state of a trackingValues object */
enum TrackingValuesState
{
    //ETVS_NOTTRACKING
    TrackingValuesStateNotTracking = 0,
    TrackingValuesStateLost,
    TrackingValuesStateExtrapolated,
    TrackingValuesStateFound,
    TrackingValuesStateTracking,
};


/** \brief This class contains the values provided by a tracking system
 */
class METAIO_DLL_API TrackingValues
{
public:

	/// Constructor for the TrackingValues class.
	TrackingValues();

	/** Constructor for TrackingValues that define translation and rotation.
	 *
	 * \param tx Translation in x direction
	 * \param ty Translation in y direction
	 * \param tz Translation in z direction
	 * \param q1 First component of the rotation quaternion
	 * \param q2 Second component of the rotation quaternion
	 * \param q3 Third component of the rotation quaternion
	 * \param q4 Fourth component of the rotation quaternion
	 * \param quality Value between 0 and 1 defining the tracking
	 * \param cosID the coordinate system ID
	 *	quality. (1=tracking, 0=not tracking)
	 * \param cosName Name of the coordinate system.
	 */
	TrackingValues(float tx, float ty, float tz, float q1, float q2,
	               float q3, float q4, float quality, int cosID, const std::string& cosName);


	TrackingValuesState     state; ///< The state of the tracking values (found or lost)

	Vector3d                translation; ///< Translation component of the pose
	Rotation                rotation;  ///< Rotation component of the pose
	LLACoordinate           llaCoordinate;	///< if we have gloval coordinate frame;

	/** Value between 0 and 1 defining the tracking quality.
	 * A higher value means better tracking results. More specifically:
	 * - 1 means the system is tracking perfectly.
	 * - 0 means that we are not tracking at all.
	 *
	 * In the case of marker-based tracking, the quality values behave a bit
	 * different:
	 * - If tracking is unsuccessful, the quality will be 0.
	 * - If tracking results are coming from a fuser, the quality will be 0.5.
	 * - If the system is tracking successfully, the quality will be between
	 *   0.5 and 1.
	 */
	float           quality;


	float           timeElapsed;				///< Time elapsed (in ms) since last state change of the tracking system

	int             coordinateSystemID;			///< The ID of the coordinate system
	std::string     cosName;                    ///< The name of the coordinate system (configured via Connection/COS/Name)
	std::string     additionalValues;           ///< Extra space for information provided by a sensor that cannot be expressed with translation and rotation properly.

	std::string     sensor;                     ///< The sensor that provided the values

	/** Helper method to keep backwards compatibility
	 *
	 * \return an equivalent Pose structure
	 */
	metaio::Pose getPoseStruct() const;

	/** Determine if a state means that its tracking or not
	*
	* \return true, if the current state represents a state, that is tracking
	*/
	bool isTrackingState() const
	{
		return isTrackingState(state);
	};

	/** Determine if a state means that its tracking or not
	*
	* \param state the state to check for
	* \return true, if the state represents a state, that is tracking
	*/
	static inline bool isTrackingState(TrackingValuesState state)
	{
		return (state == TrackingValuesStateFound)
		       || (state == TrackingValuesStateTracking)
		       || (state == TrackingValuesStateExtrapolated);
	}
};
}


#endif
