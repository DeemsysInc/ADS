#ifndef __AS_ISENSORSCOMPONENT_H__
#define __AS_ISENSORSCOMPONENT_H__

#include <UnifeyeSDKMobile/AS_MobileStructs.h>

#define ASISensorsComponentIsLocationSensor(sensor) (sensor & metaio::ISensorsComponent::SENSOR_LOCATION)
#define ASISensorsComponentIsAccelerometerSensor(sensor) (sensor & metaio::ISensorsComponent::SENSOR_ACCELEROMETER)
#define ASISensorsComponentIsCompassSensor(sensor) (sensor & metaio::ISensorsComponent::SENSOR_ORIENTATION)

namespace metaio
{

	/**
	 * \brief Interface for sensors (Location, Accelerometer and Compass
	 *
	 * \anchor ISensorsComponent
	 */
	class ISensorsComponent
	{
	public:


		static const int SENSOR_NONE =				0<<0;		///< No sensor
		static const int SENSOR_LOCATION =			1<<0;		///< Location (or GPS) sensor
		static const int SENSOR_ACCELEROMETER =		1<<1;		///< Accelerometer sensor
		static const int SENSOR_ORIENTATION =		1<<2;		///< Orientation (or Compass or Magnetic) sensor
		
		// TODO: Add more sensor types as needed here, e.g. Light, Pressure, Temperature etc..
		
		static const int SENSOR_CAMERA =			1<<15;		///< Camera, just a define for internal use, 
																///  not really handled by sensors component
		static const int SENSOR_ALL =				0xFFFF;		///< All sensors


		/**
		 * \brief Default constructor.
		 */
		ISensorsComponent() {};

		/**
		 * \brief Destructor.
		 */
		virtual ~ISensorsComponent() {};

		/**
		 * \brief Start the given sensors
		 *
		 * \param sensors Sensors to start (see ESENSOR)
		 * \return sensors that are actually started
		 * \sa ESENSOR, stop
		 */
		virtual int start (int sensors) = 0;
			
		/**
		 * \brief Stop the given sensors
		 *
		 * \param sensors Sensors to stop (default is all sensors, i.e. SENSOR_ALL)
		 * \return sensors that are actually stopped
		 * \sa start
		 */
		virtual int stop (int sensors=SENSOR_ALL) = 0;

		/**
	 	 * \brief Get location provided by the location sensor
		 *
		 * \return location as LLA coordinates
		 */
		virtual LLACoordinate getLocation() = 0;

		/**
		 * \brief Get the reading provided by the accelerometer sensor
		 *
		 * \return Vector containing accelerometer readings
		 */
		virtual Vector3d getAccelerometerReading() = 0;


		/**
		 * \brief Get the compass angle
		 *
		 * \return Compass angle in degrees
		 */
		virtual float getCompassReading() = 0;

	};
	
	
	/**
	* \brief The factory will create a device independent ISensorsComponent 
	*/
	ISensorsComponent* CreateSensorsComponent();
}

#endif
