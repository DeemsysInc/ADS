//
//  UnifeyeMobileViewController.h
//  metaio Mobile SDK
//
//  Copyright 2012 metaio, GmbH. All rights reserved.
//

#import <UIKit/UIKit.h>

#import <UnifeyeSDKMobile/AS_IUnifeyeMobileIPhone.h>

namespace metaio
{
    class IUnifeyeMobileIPhone;     // forward declaration
    class IUnifeyeMobileGeometry;   // forward declaration
    class ISensorsComponent;        // forward declaration
}

@class EAGLView;        // forward declaration
@class EAGLContext;     // forward declaration
@class CADisplayLink;   // forward declaration

@interface UnifeyeMobileViewController : UIViewController <UnifeyeMobileDelegate>
{	
    metaio::IUnifeyeMobileIPhone*	unifeyeMobile;			//!< Reference to  unifeye
    metaio::ISensorsComponent*		m_sensors;			//!< Pointer to our sensors manager
    
    
    // OpenGL related members (directly taken from XCode4 OpenGL example)
    EAGLContext *context;               // our OpenGL Context
    BOOL animating;                     // are we currently animating
    NSInteger animationFrameInterval;   // refresh interval
    CADisplayLink *__weak displayLink;         // pointer to our displayLink

    EAGLView *glView;                   // our OpenGL View
}



@property (nonatomic, strong) IBOutlet EAGLView *glView;
@property (readonly, nonatomic, getter=isAnimating) BOOL animating;
@property (nonatomic) NSInteger animationFrameInterval;

/** Close the current view again */
- (IBAction)onBtnClosePushed:(id)sender;

/** Start the OpenGL Animation */
- (void)startAnimation;

/** Stop the OpenGL Animation */
- (void)stopAnimation;

/** Called on every frame */
- (void)drawFrame;

/** Get the current screen content */
- (UIImage*) getScreenshotImage;

@end

