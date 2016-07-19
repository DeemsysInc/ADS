//
//  GestureHandlerIOS.h
//  Example
//
//  Created by WeiLuc Liu on 11/19/12.
//  Copyright 2007-2012 metaio GmbH. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <metaioSDK/IMetaioSDK.h>

namespace metaio
{
	class GestureHandler;
}

//#ifndef _cpluscplus
//typedef void GestureHandler;
//#endif

/**
 * Specialized GestureHandler interface for iOS
 */
@interface GestureHandlerIOS : NSObject
{
    /**
     * \brief A member variable that points to the gesture handler object.
     */
    @public
    metaio::GestureHandler* m_pGestureHandler;
}


/**
 * \brief Constructor for GestureHandlerIOS.
 * \param metaioSDK A pointer to the metaioSDK.
 * \param view A pointer to the UIView.
 * \param gesture_mask The gesture mask.
 * \return instance of the class
 */
- (id) initWithSDK: (metaio::IMetaioSDK*) metaioSDK withView: (UIView*)view withGestures: (int)gesture_mask;


/**
 * \brief Release the list of geometry objects.
 */
- (void) releaseList;


/**
 * \brief Set the gesture mask.
 * \param gesture_mask The gesture mask.
 */
- (void) enableGestures: (int)gesture_mask;


/**
 * \brief Set the pickability of geometries.
 * \param pickable True if pickable, false otherwise.
 */
- (void) enablePickability: (bool)pickable;


/**
 * \brief Set the axis to which rotation applies.
 * \param axis The axis for rotation.
 */
- (void) setRotationAxis: (char)axis;


/**
 * \brief Add a geometry to the gesture handler.
 * \param geometry A pointer to the geometry to be added.
 * \param group The group number to which the goemetry belongs.
 * \param pickable True if pickable, false otherwise.
 */
- (void) addObject: (metaio::IGeometry*)geometry andGroup: (int)group andPickability: (bool)pickable;


/**
 * Select all the objects in the same group and record their initial states.
 * \param group The group number of the selected object.
 * \param x The x coordinate of the touch point.
 * \param y The y coordinate of the touch point.
 */
- (void) selectAllObjectsInGroup: (int)group andX: (int)x andY: (int)y;


/**
 * Select all the objects record their initial states.
 * \param x The x coordinate of the touch point.
 * \param y The y coordinate of the touch point.
 */
- (void) selectAllObjects: (int)x andY:(int)y;


/**
 * Go through the vector to select all touched objects if they are pickable, and select all objects if they are not.
 * \param touches A pointer to the touch object.
 * \param event A pointer to the even object.
 * \param view A pointer to the UIView.
 */
- (void) touchesBegan: (NSSet *)touches withEvent:(UIEvent *)event withView:(UIView*)view;


/**
 * Move all the objects if they are selected.
 * \param touches A pointer to the touch object.
 * \param event A pointer to the even object.
 * \param view A pointer to the UIView.
 */
- (void) touchesMoved: (NSSet *)touches withEvent:(UIEvent *)event withView:(UIView*)view;


/**
 * Reset all the objects in the vector to not selected.
 * \param touches A pointer to the touch object.
 * \param event A pointer to the even object.
 * \param view A pointer to the UIView.
 */
- (void) touchesEnded: (NSSet *)touches withEvent:(UIEvent *)event withView:(UIView*)view;

@end
