// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef _AS_TYPEDEFS_H_
#define _AS_TYPEDEFS_H_

#include <cassert>
#include <metaioSDK/ColorFormat.h>
#include <string>
#include <math.h>

#if !defined(AS_USE_METAIOSDKDLL)
#define METAIO_DLL_API	// we don't have a dll file
#else
#ifdef AS_METAIOSDKDLL_EXPORTS
#define METAIO_DLL_API __declspec(dllexport)
#else
#define METAIO_DLL_API __declspec(dllimport)
#endif
#endif

namespace metaio
{
/**
 * Screen rotations
 *
 * This enumeration is used to inform Mobile SDK about
 * camera image rotationm needed to compensate for current screen
 * rotation w.r.t. it's natural rotation.
 *
 * For example if the screen is naturally set to Landscape orientation,
 * and device is rotated to Portrait inverted orientation, then an angle
 * of 90 degrees is required to compensate this.
 */
enum ESCREEN_ROTATION
{
	/// No rotation, natural screen orientation (Landscape (Left) by default)
	ESCREEN_ROTATION_0,

	/// 90 degrees, by default Portrait Inverted orientation
	ESCREEN_ROTATION_90,

	/// 180 degrees, by default Landscape Inverted (Right) orientation
	ESCREEN_ROTATION_180,

	/// 270 degrees, by default Portrait orientation
	ESCREEN_ROTATION_270,
};

/** @brief Structure that defines a 2D vector */
struct Vector2d
{
	float x;	///< x component of the vector
	float y;	///< y component of the vector

	Vector2d() : x(0.0f), y(0.0f) {};

	/**
	* \brief Structure that defines a 2D vector
	* \param _n x and y components of the vector
	*/
	explicit Vector2d(float _n) : x(_n), y(_n) {};

	/**
	* \brief Structure that defines a 2D vector
	* \param _x x component of the vector
	* \param _y y component of the vector
	*/
	Vector2d(float _x, float _y) : x(_x), y(_y) {};

	/** Computes the coefficient-wise sum of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Result of the operation
	 */
	Vector2d operator +(const Vector2d& rhs) const
	{
		return Vector2d(x + rhs.x, y + rhs.y);
	}

	/** Computes the coefficient-wise difference of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Result of the operation
	 */
	Vector2d operator -(const Vector2d& rhs) const
	{
		return Vector2d(x - rhs.x, y - rhs.y);
	}

	/** Computes the coefficient-wise product of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Result of the operation
	 */
	Vector2d cwiseProduct(const Vector2d& rhs) const
	{
		return Vector2d(x * rhs.x, y * rhs.y);	
	}

	/** Computes the coefficient-wise quotient of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Result of the operation
	 */
	Vector2d cwiseQuotient(const Vector2d& rhs) const
	{
		assert(fabs(rhs.x) > 0.0f && fabs(rhs.y) > 0.0f);
		return Vector2d(x / rhs.x, y / rhs.y);	
	}

	/** Scale the vector by a scalar
	 *
	 * \param[in] rhs Scalar which scales the vector
	 * \return Scaled vector;
	 */
	Vector2d operator *(float rhs) const
	{
		return Vector2d(x * rhs, y * rhs);	
	}

	/** Computes the in-place coefficient-wise sum of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Reference to *this
	 */
	Vector2d& operator +=(const Vector2d& rhs)
	{
		x += rhs.x;
		y += rhs.y;
		return *this;
	}

	/** Computes the in-place coefficient-wise difference of two vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Reference to *this
	 */
	Vector2d& operator -=(const Vector2d& rhs)
	{
		x -= rhs.x;
		y -= rhs.y;
		return *this;
	}

	/** Scale the vector in-place by a given scalar
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Reference to *this
	 */
	Vector2d& operator *=(float rhs)
	{
		x *= rhs;
		y *= rhs;
		return *this;
	}

	/** Scale the vector in-place by a given scalar
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Reference to *this
	 */
	Vector2d& operator /=(float rhs)
	{
		assert(fabs(rhs) > 0.0f);
		x /= rhs;
		y /= rhs;
		return *this;
	}

	/** Computes the inner product (or scalar-product, dot-product) of two
	 *  vectors
	 *
	 * \param[in] rhs Right-hand-side of the operation
	 * \return Result of the operation
	 */
	float dot(const Vector2d& rhs) const
	{
		return x * rhs.x + y * rhs.y;
	}

	/** Computes the euclidean-norm (L2-norm) of this vector 
	 *
	 * \return Result of the operation
	 */
	float norm() const
	{
		return sqrtf(squaredNorm());
	}

	/** Computes the squared-norm of this vector
	 *
	 * \return Result of the operation
	 */
	float squaredNorm() const
	{
		return x*x + y*y;
	}

	/**
	* \brief Determine if the vector is null
	* \return true if null vector, else false
	*/
	bool isNull() const
	{
		return (x == 0.0f && y == 0.0f);
	}

	/** Checks if two vectors are coefficient-wise equal
	 *
	 * \param[in] lhs Left-hand-side of this operation
	 * \param[in] rhs Right-hand-side of this operation
	 * \return true if lhs and rhs are coefficient-wise equal, otherwise false.
	 */
	friend bool operator ==(const Vector2d& lhs, const Vector2d& rhs)
	{
		return lhs.x == rhs.x && lhs.y == rhs.y;
	}

	/** Checks if two vectors differ in at least one coefficient
	 *
	 * \param[in] lhs Left-hand-side of this operation
	 * \param[in] rhs Right-hand-side of this operation
	 * \return true if lhs and rhs are unequal, otherwise false.
	 */
	friend bool operator !=(const Vector2d& lhs, const Vector2d& rhs)
	{
		return lhs.x != rhs.x || lhs.y != rhs.y;
	}

	/** Checks if a vector is lexicographically smaller than another vector
	 *
	 * \param[in] lhs Left-hand-side of this operation
	 * \param[in] rhs Right-hand-side of this operation
	 * \return true if lhs is smaller than rhs, otherwise false.
	 */
	friend bool operator <(const Vector2d& lhs, const Vector2d& rhs)
	{
		return lhs.x < rhs.x || (lhs.x == rhs.x && lhs.y < rhs.y);
	}
};

/** @brief Structure that defines an integer 2D vector */
struct Vector2di
{
	int x;	///< x component of the vector
	int y;	///< y component of the vector

	Vector2di() : x(0), y(0) {};

	/**
	* \brief Structure that defines a 2D vector
	* \param _n x and y components of the vector
	*/
	Vector2di(int _n) : x(_n), y(_n) {};

	/**
	* \brief Structure that defines an integer 2D vector
	* \param _x x component of the vector
	* \param _y y component of the vector
	*/
	Vector2di(int _x, int _y) : x(_x), y(_y) {};

	/**
	* \brief Determine if the vector is null
	* \return true if null vector, else false
	*/
	bool isNull() const
	{
		return (x == 0 && y == 0);
	}

};

/** @brief Structure that defines a 3D vector */
struct Vector3d
{
	float x;	///< x component of the vector
	float y;	///< y component of the vector
	float z;	///< z component of the vector

	Vector3d() : x(0.0f), y(0.0f), z(0.0f) {};

	/**
	* \brief Structure that defines a 3D vector
	* \param _n x, y and z components of the vector
	*/
	Vector3d(float _n) : x(_n), y(_n), z(_n) {};

	/**
	* \brief Structure that defines a 3D vector
	* \param _x x component of the vector
	* \param _y y component of the vector
	* \param _z z component of the vector
	*/
	Vector3d(float _x, float _y, float _z) : x(_x), y(_y), z(_z) {};

	/** Sets all coefficients to 0
	 *
	 * \post norm() == 0
	 */
	void setZero()
	{
		x = 0.0f;
		y = 0.0f;
		z = 0.0f;
	}

	/** Gives the negative of this vector
	 *
	 * \return Result of the operation
	 */
	Vector3d operator -() const
	{
		return Vector3d(-x, -y, -z);
	}

	/** Adds two vectors coefficient-wise
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector3d operator +(const Vector3d& rhs) const
	{
		return Vector3d(x + rhs.x, y + rhs.y, z + rhs.z);
	}

	/** Subtracts two vectors coefficient-wise
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector3d operator -(const Vector3d& rhs) const
	{
		return Vector3d(x - rhs.x, y - rhs.y, z - rhs.z);
	}

	/** Multiplies a scalar coefficient-wise with the vector
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector3d operator *(float rhs) const
	{
		return Vector3d(x * rhs, y * rhs, z * rhs);
	}

	/** Divide coefficient-wise with the vector by a scalar
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 * \pre rhs != 0
	 */
	Vector3d operator /(float rhs) const
	{
		assert(fabs(rhs) > 1e-8f);
		return Vector3d(x / rhs, y / rhs, z / rhs);
	}

	/** Adds coefficient-wise two vector in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector3d& operator +=(const Vector3d& rhs)
	{
		x += rhs.x;
		y += rhs.y;
		z += rhs.z;
		return *this;
	}

	/** Subtracts coefficient-wise two vector in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector3d& operator -=(const Vector3d& rhs)
	{
		x -= rhs.x;
		y -= rhs.y;
		z -= rhs.z;
		return *this;
	}

	/** Multiplies the vector with a scalar in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector3d& operator *=(float rhs)
	{
		x *= rhs;
		y *= rhs;
		z *= rhs;
		return *this;
	}


	/** Divides the vector by a scalar
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 * \pre rhs != 0
	 */
	Vector3d& operator /=(float rhs)
	{
		assert(fabs(rhs) > 1e-8f);
		x /= rhs;
		y /= rhs;
		z /= rhs;
		return *this;
	}

	/** Computes the dot product (a.k.a. scalar product, inner product)
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	float dot(const Vector3d& rhs) const
	{
		return x * rhs.x + y * rhs.y + z * rhs.z;
	}

	/** Gives the squared norm of the vector
	 *
	 * \return Result of the operation
	 */
	float squaredNorm() const
	{
		return x * x + y * y + z * z;
	}

	/** Returns the euclidean norm of the vector
	 *
	 * \return Result of the operation
	 * \post norm() == sqrt(squaredNorm())
	 */
	float norm() const
	{
		return sqrtf(squaredNorm());
	}

	/**
	* \brief Determine if the vector is null
	* \return true if null vector, else false
	*/
	bool isNull() const
	{
		return (x == 0.0f && y == 0.0f && z == 0.0f);
	}

};

/** Structure that defines a 4D vector */
struct Vector4d
{
	float x;	///< x component of the vector
	float y;	///< y component of the vector
	float z;	///< z component of the vector
	float w;	///< w component of the vector

	Vector4d() : x(0.0f), y(0.0f), z(0.0f), w(1.0f) {};
	/** Structure that defines a 4D vector
	* \param _x x component of the vector
	* \param _y y component of the vector
	* \param _z z component of the vector
	* \param _w w component of the vector
	*/
	Vector4d(float _x, float _y, float _z, float _w) :
		x(_x), y(_y), z(_z), w(_w) {};


	/** Sets all coefficients to 0
	 *
	 * \post norm() == 0
	 */
	void setZero()
	{
		x = 0.0f;
		y = 0.0f;
		z = 0.0f;
		w = 0.0f;
	}

	/** Gives the negative of this vector
	 *
	 * \return Result of the operation
	 */
	Vector4d operator -() const
	{
		return Vector4d(-x, -y, -z, -w);
	}

	/** Adds two vectors coefficient-wise
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector4d operator +(const Vector4d& rhs) const
	{
		return Vector4d(x + rhs.x, y + rhs.y, z + rhs.z, w + rhs.w);
	}

	/** Subtracts two vectors coefficient-wise
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector4d operator -(const Vector4d& rhs) const
	{
		return Vector4d(x - rhs.x, y - rhs.y, z - rhs.z, w - rhs.w);
	}

	/** Multiplies a scalar coefficient-wise with the vector
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	Vector4d operator *(float rhs) const
	{
		return Vector4d(x * rhs, y * rhs, z * rhs, w * rhs);
	}

	/** Divide coefficient-wise with the vector by a scalar
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 * \pre rhs != 0
	 */
	Vector4d operator /(float rhs) const
	{
		assert(fabs(rhs) > 1e-8f);

		return Vector4d(x / rhs, y / rhs, z / rhs, w / rhs);
	}

	/** Adds coefficient-wise two vector in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector4d& operator +=(const Vector4d& rhs)
	{
		x += rhs.x;
		y += rhs.y;
		z += rhs.z;
		w += rhs.w;
		return *this;
	}

	/** Subtracts coefficient-wise two vector in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector4d& operator -=(const Vector4d& rhs)
	{
		x -= rhs.x;
		y -= rhs.y;
		z -= rhs.z;
		w -= rhs.w;
		return *this;
	}

	/** Multiplies the vector with a scalar in place
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 */
	Vector4d& operator *=(float rhs)
	{
		x *= rhs;
		y *= rhs;
		z *= rhs;
		w *= rhs;
		return *this;
	}


	/** Divides the vector by a scalar
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Reference to *this
	 * \pre rhs != 0
	 */
	Vector4d& operator /=(float rhs)
	{
		assert(fabs(rhs) > 1e-8f);
		x /= rhs;
		y /= rhs;
		z /= rhs;
		w /= rhs;
		return *this;
	}

	/** Computes the dot product (a.k.a. scalar product, inner product)
	 *
	 * \param[in] rhs Right hand side of the operation
	 * \return Result of the operation
	 */
	float dot(const Vector4d& rhs) const
	{
		return x * rhs.x + y * rhs.y + z * rhs.z + w * rhs.w;
	}

	/** Gives the squared norm of the vector
	 *
	 * \return Result of the operation
	 */
	float squaredNorm() const
	{
		return x * x + y * y + z * z + w * w;
	}

	/** Returns the euclidean norm of the vector
	 *
	 * \return Result of the operation
	 * \post norm() == sqrt(squaredNorm())
	 */
	float norm() const
	{
		return sqrtf(squaredNorm());
	}

	/**
	* \brief Determine if the vector is null
	* \return true if null vector, else false
	*/
	bool isNull() const
	{
		return (x == 0.f && y == 0.f && z == 0.f && w == 0.f);
	}
};

/** \brief Struct for corresponding 2D and 3D points */
struct Correspondence2D3D
{
	Correspondence2D3D() {}

	/**
	 * \brief Sets the initial coordinates of the correspondence
	 *
	 * \param[in] observed_x Coordinate in x of the 2D point
	 * \param[in] observed_y Coordinate in y of the 2D point
	 * \param[in] reference_x Coordinate in x of the 3D point
	 * \param[in] reference_y Coordinate in y of the 3D point
	 * \param[in] reference_z Coordinate in z of the 3D point
	 */
	Correspondence2D3D(float observed_x, float observed_y, float reference_x, float reference_y, float reference_z)
		: observedPoint(observed_x, observed_y),
		  referencePoint(reference_x, reference_y, reference_z) {}

	/**
	 * \brief Sets the initial coordinates of the correspondence
	 *
	 * \param[in] observed Observed point in 2D
	 * \param[in] reference Reference point in 3D
	 */
	Correspondence2D3D(const Vector2d& observed, const Vector3d& reference) : observedPoint(observed), referencePoint(reference) {}

	Vector2d observedPoint; ///< observed 2D point (e.g. on the image screen)
	Vector3d referencePoint; ///< reference 3D point (e.g. point in world coordinates)
};

/** @brief Structure that defines LLA coordinates */
struct LLACoordinate
{
	double latitude;	///< The latitude
	double longitude;	///< The longitude
	double altitude;	///< altitude
	double accuracy;	///< the accuracy of the GPS position
	double timestamp;	///< timestamp when the coordinates were valid

	LLACoordinate() : latitude(0.0), longitude(0.0), altitude(0.0), accuracy(0.0), timestamp(0.0)
	{
	};

	/**
	* \brief Structure that defines LLA coordinates
	* \note The altitude is ignored, if you want adjust the height use IUnifeyeMobileGeometry::setTranslation
	* \param _lat	latitude component
	* \param _long	longitude component
	* \param _alt	altitude component
	* \param _acc	accuracy component
	*/
	LLACoordinate(double _lat, double _long, double _alt, double _acc) :
		latitude(_lat), longitude(_long), altitude(_alt), accuracy(_acc)
	{
	};

	/**
	* \brief Structure that defines LLA coordinates
	* \note The altitude is ignored, if you want adjust the height use IUnifeyeMobileGeometry::setTranslation
	* \param _lat	latitude component
	* \param _long	longitude component
	* \param _alt	altitude component
	* \param _acc	accuracy component
	* \param _tms	timestamp
	*/
	LLACoordinate(double _lat, double _long, double _alt, double _acc, double _tms) :
		latitude(_lat), longitude(_long), altitude(_alt), accuracy(_acc), timestamp(_tms)
	{
	};

	/**
	* \brief Determine if location is invalid (null)
	* \return true if invalid, else false
	*/
	bool isNull() const
	{
		return (latitude == 0.0 && longitude == 0.0 && altitude == 0.0 && accuracy <= 0.0);
	}

	/**
	 * \brief Determine if the two LLACoordinates are equal, this does not compare
	 * timestamps
	 *
	 * \param rhs LLACoordinates to compare with
	 * \return true if they are equal
	 */
	bool operator==(const LLACoordinate& rhs)const
	{
		return ((timestamp == rhs.timestamp) &&
				(latitude == rhs.latitude) &&
				(longitude == rhs.longitude) &&
				(altitude == rhs.altitude) &&
				(accuracy == rhs.accuracy)
				);
	}
};

/**
 * @brief Structure that defines a 3D pose
 */
struct Pose
{

	/// Constructor for the Pose structure.
	Pose()
	{
		//Set translation.
		translation.x = 0.0f;
		translation.y = 0.0f;
		translation.z = 0.0f;
		//Set rotation.
		rotation.x = 0.0f;
		rotation.y = 0.0f;
		rotation.z = 0.0f;
		rotation.w = 1.0f;
		//Set quality value.
		quality = 0.0f;
		timeElapsed = 0.0f;
		//Set the coordinate system ID
		cosID = 0;
		cosName = "empty";
		//Set LLA coordinate
		llaCoordinate.latitude = 0.0;
		llaCoordinate.longitude = 0.0;
		llaCoordinate.altitude = 0.0;
		llaCoordinate.accuracy = 0.0;
		// init additionalValues with an empty string
		additionalValues = "empty";
	}

	/**
	* \brief Constructor for a pose that defines translation and
	* rotation.
	*
	* \param _tx Translation in x direction
	* \param _ty Translation in y direction
	* \param _tz Translation in z direction
	* \param _q1 First component of the rotation quaternion
	* \param _q2 Second component of the rotation quaternion
	* \param _q3 Third component of the rotation quaternion
	* \param _q4 Fourth component of the rotation quaternion
	* \param qual Value between 0 and 1 defining the tracking
	* \param _cosID the coordinate system ID
	*	quality. (1=tracking, 0=not tracking)
	* \param _cosName Name of the coordinate system.
	*/
	Pose(float _tx, float _ty, float _tz, float _q1, float _q2,
	     float _q3, float _q4, float qual, int _cosID, const std::string& _cosName)
	{
		//Set translation.
		translation.x = _tx;
		translation.y = _ty;
		translation.z = _tz;
		//Set rotation.
		rotation.x = _q1;
		rotation.y = _q2;
		rotation.z = _q3;
		rotation.w = _q4;
		//Set quality value.
		quality =  qual;
		timeElapsed = 0.0f;
		//Set the coordinate system id
		cosID = _cosID;
		cosName = _cosName;
		// init additionalValues with an empty string
		additionalValues = "empty";
	}

	/**
	 * \brief Determine if this pose's target is just lost
	 */
	bool isLost() const
	{
		return (quality == 0.0f && timeElapsed == 0.0f);
	}

	/**
	 * \brief Determine if this pose's target is just detected
	 */
	bool isDetected() const
	{
		return (quality > 0.f && timeElapsed == 0.0);
	}

	Vector3d translation;			///< Translation component of the pose
	Vector4d rotation;				///< Rotation component of the pose
	LLACoordinate llaCoordinate;	///< if we have gloval coordinate frame;

	/** Value between 0 and 1 defining the 	tracking quality.
		(1=tracking, 0=not tracking, 0.5 means tracking is coming from a fuser) */
	float quality;

	// TODO: should be a float.
	double timeElapsed;				///< Time lapsed (in ms) since current tracking state (determined from quality)

	int cosID;						///< The ID of the coordinate system
	std::string cosName;			///< The name of the coordinate system (configured via SensorCOSID or COSName)
	std::string additionalValues;	///< Room for additional values provided by a sensor that cannot be expressed with translation and rotation properly.


};

/** \brief A helper struct to represent a bounding box.
*/
struct BoundingBox
{
	Vector3d min; ///< Vector containing the minimum x,y,z values
	Vector3d max; ///< Vector containing the maximum x,y,z values
};


/** @brief Structure that defines a image*/
struct ImageStruct
{
	unsigned char* buffer;				///< pointer to the pixels

	int width;							///< width component of the vector
	int height; 						///< height component of the vector
	metaio::common::ECOLOR_FORMAT colorFormat;	///< color format
	bool originIsUpperLeft;				///< true if the orgigin is in the upper left corner; false, if lower left

	double timestamp;					///< timestamp when the image was created

	/**
	 * \brief Platform-specific capturing context object
	 *
	 * On iOS, this is a CVImageBufferRef
	 * (https://developer.apple.com/library/mac/#documentation/QuartzCore/Reference/CVImageBufferRef/Reference/reference.html)
	 * which you can optionally use for faster texture upload of the camera image, for example. If
	 * you store this object yourself, don't forget to use CFRetain/CFRelease for reference
	 * counting.
	 *
	 * On other platforms, this is NULL at the moment.
	 */
	void* capturingContext;

	/**
	* \brief Constructor for image struct
	*/
	ImageStruct() : buffer(0), width(0), height(0),
		colorFormat(common::ECF_UNKNOWN), originIsUpperLeft(true), timestamp(0),
		capturingContext(0) {};


	/**
	* \brief Constructor for image struct
	*
	* \param _buffer pointer to the image data
	* \param _width width of the image
	* \param _height height of the image
	* \param _colorFormat the color format
	* \param _originIsUpperLeft true if the origin is upper left corner, false if lower left
	* \param _timestamp timestamp when the image was created
	* \param _capturingContext platform-specific capturing context object
	*/
	ImageStruct(unsigned char* _buffer, int _width, int _height,
				metaio::common::ECOLOR_FORMAT _colorFormat, bool _originIsUpperLeft, double _timestamp = 0,
				void* _capturingContext = 0):
				buffer(_buffer), width(_width), height(_height),
				colorFormat(_colorFormat), originIsUpperLeft(_originIsUpperLeft), timestamp(_timestamp),
				capturingContext(_capturingContext) {};

};

/** Helper function to allow callbacks on android*/
struct ByteBuffer
{
	unsigned char* buffer;		///< pointer to a binary buffer
	int length;					///< legnth of the buffer

	/**
	 * \brief Default constructor for ByteBuffer struct
	 */
	ByteBuffer() : buffer(0), length(0) {};

	/**
	* \brief Constructor for ByteBuffer struct
	*
	* \param _buffer pointer to the binary data
	* \param _length length of the buffer
	* \return
	*/
	ByteBuffer(unsigned char* _buffer, int _length)
		:  buffer(_buffer), length(_length) {};

};

/**
* \brief The Visual Search result from the Server.
*/
struct VisualSearchResponse
{
	/**
	* \brief Constructor
	*/
	VisualSearchResponse() : trackingDataName(""), trackingData(""), numOfTimesTracked(0), visualSearchScore(0) {}

	std::string trackingDataName; ///< The name of the found TrackingData
	std::string trackingData; ///< The tracking data which can be used to track the found pattern. (see IUnifeyeMobile::setTrackingData())
	int numOfTimesTracked; ///< TODO
	float visualSearchScore; ///< TODO
};



} // namespace metaio

#endif
