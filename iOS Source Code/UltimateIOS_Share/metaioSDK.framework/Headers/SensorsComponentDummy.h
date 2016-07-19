// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef __AS_SENSORSCOMPONENTWIN32_H__
#define __AS_SENSORSCOMPONENTWIN32_H__

#include <metaioSDK/MobileStructs.h>
#include <metaioSDK/ISensorsComponent.h>

#include <Common/AS_Matrix.h>


namespace metaio
{

/**
 * \brief Interface for sensors (Location, Accelerometer and Compass
 *
 * \anchor ISensorsComponentDummy
 */
class SensorsComponentDummy: virtual public ISensorsComponent
{
public:

	/**
	 * @brief Default constructor.
	 */
	SensorsComponentDummy();

	/**
	 * @brief Destructor.
	 */
	virtual ~SensorsComponentDummy();

	virtual int start(int sensors);
	virtual int stop(int sensors = SENSOR_ALL);

	virtual LLACoordinate getLocation();
	virtual Vector3d getGravity();
	virtual float getHeading();
	virtual SensorValues getSensorValues();
	virtual std::map<double, metaio::Vector3d> getUserAcceleration();

	/**
	* \brief: sets a simulated location
	*
	* \param coordinate LLA Coordinate
	*/
	void setLocation(LLACoordinate& coordinate);

	/**
	* \brief: Sets a simulated gravity vector
	*
	* \param vector the gravity vector
	* \return
	*/
	void setGravity(Vector3d& vector);

	/**
	* \brief: Sets a simulated device heading (compass direction)
	*
	* \param heading The compass direction
	*/
	void setHeading(float heading);

	/**	Computes the camera pose in the metaio camera coordinate system based on stored
	*	gravity, GPS and compass values relative to the given location of origin. The height
	*	of the handheld device/camera is approxiamted to 1800.0 millimeters.
	*
	*	\param originLat	latitute of the origin location to which the cameras pose will
	*						be computed
	*	\param originLong	longitute of the origin location to which the cameras pose will
	*						be computed
	*	\param outPose		4x4 matrix in which the resulting camera pose will be stored
	*
	*	\return false if no gravity value is stored, otherwise true
	*/
	bool computePoseInMetaioCameraCOS(double originLat, double originLong,
	                                  core::Matrix<float>& outPose);

	void setManualLocation(const metaio::LLACoordinate location);
	
	void resetManualLocation();
	
	
private:

	float m_heading;
	Vector3d m_gravity;
	metaio::LLACoordinate m_location;
	metaio::LLACoordinate m_manualLocation;
	int m_activeSensors;
};

}

#endif
