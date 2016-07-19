// Copyright 2007-2012 metaio GmbH. All rights reserved.
#ifndef _AS_COLORFORMAT_H_
#define _AS_COLORFORMAT_H_

namespace metaio
{
namespace common
{

/**
* Enumeration of the different color formats
*/
enum ECOLOR_FORMAT
{

    ECF_A1R5G5B5 = 0,	///< 16 bit color format with one alpha bit
    ECF_R5G6B5,			///< 16 bit color format.
    ECF_R8G8B8,			///< 24 bit color, no alpha channel, but 8 bit for red, green and blue.
    ECF_B8G8R8,			///< 24 bit color, no alpha channel, but 8 bit for red, green and blue.
    ECF_A8R8G8B8,		///< Default 32 bit color format. 8 bits are used for every component: red, green, blue and alpha.
    ECF_A8B8G8R8,		///< Default 32bit color format with flipped values for blue and red,  WARNING: ECF_A8B8G8R8 is not ABGR, but BGRA!

    /**
    YUV4:2:2(YCbCr)
    Bits 31-24	Bits 23-16	Bits 15-8	Bits 7-0
    	 V (Cr)	Y [n+1 pixel]	U (Cb)	Y [n pixel]
    */
    ECF_V8Y8U8Y8,
    ECF_V8A8U8Y8,			///< same as ECF_V8Y8U8Y8 except for second Y is used for 8bit alpha value
	ECF_YUV420SP,			///< 12 bit NV21 Planar YUV image format
	ECF_YV12,				///< 12 bit YV12 Planar YUV image format
    ECF_GRAY,				///< 8 bit gray image format
    ECF_HSV,                ///< HSV color format
	ECF_UNKNOWN,			///< unknown image format

};


}
}
#endif

