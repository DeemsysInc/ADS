//
//  UnifeyeMobileViewController.m
//  metaio Mobile SDK
//
//  Copyright metaio, GmbH 2012. All rights reserved.
//

#import <QuartzCore/QuartzCore.h>
#import <UnifeyeSDKMobile/AS_IUnifeyeMobileGeometry.h>
#import "EAGLView.h"
#import "UnifeyeMobileViewController.h"



// Define your License here
// for more information, please visit http://docs.metaio.com
//#define UNIFEYE_LICENSE "jxuOgUi4Ia7cX43sHojDBQvFxVnaj6g/nxW1+yYmkqQ="
#define UNIFEYE_LICENSE "q4xYUVZA9h8feanqNVlwaEebbLqVUDPwAJ8RwiLETGk="
#if !defined (UNIFEYE_LICENSE)
#error Please provide the license string for your application
#endif


// Make sure that we're building this with an iOS SDK that at least supports iOS5
#ifndef __IPHONE_5_0
#error  Please update to an iOS SDK that supports at least iOS5. iOS applications should always be built with the latest SDK
#endif


@interface UnifeyeMobileViewController ()
@property (nonatomic, strong) EAGLContext *context;
@property (nonatomic, weak) CADisplayLink *displayLink;
@end



@implementation UnifeyeMobileViewController
@synthesize glView;
@synthesize animating, context, displayLink;


#pragma mark - UIViewController lifecycle

- (void)dealloc
{
    // Tear down context.
    if ([EAGLContext currentContext] == context)
        [EAGLContext setCurrentContext:nil];
    
    // delete unifeye instance
    if( unifeyeMobile )
    {
        delete unifeyeMobile;
        unifeyeMobile = NULL;
    }
    
    // delete our sensors component
    if( m_sensors )
    {
        delete m_sensors;
        m_sensors = NULL;
    }
    
    
}


- (void) viewDidLoad
{
    [super viewDidLoad];
    
    
    if( !context )
    {
        EAGLContext *aContext = [[EAGLContext alloc] initWithAPI:kEAGLRenderingAPIOpenGLES2];
        
        if (!aContext)
            NSLog(@"Failed to create ES context");
        else if (![EAGLContext setCurrentContext:aContext])
            NSLog(@"Failed to set ES context current");
        
        self.context = aContext;
    }
    
    
    // set the openGL context
    [glView setContext:context];
    [glView setFramebuffer];
    animating = FALSE;
    self.displayLink = nil;
    
    // limit OpenGL framerate to 30FPS, as the camera has a maximum of 30FPS anyway
    animationFrameInterval = 2;
    
    
    // create unifeye instance
    unifeyeMobile = metaio::CreateUnifeyeMobileIPhone(UNIFEYE_LICENSE);
    if( !unifeyeMobile )
    {
        NSLog(@"Unifeye instance could not be created. Please verify the signature string");
        return;
    }
    
    m_sensors = metaio::CreateSensorsComponent();
    if( !m_sensors )
    {
        NSLog(@"Could not create the sensors interface.");
        return;
    }
    unifeyeMobile->registerSensorsComponent( m_sensors );
    
    
    // Create our Unifeye instance
    float scaleFactor = [UIScreen mainScreen].scale;	
    if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad)
    {
        // multiply with scale factor for iPad 3rd gen
        unifeyeMobile->initializeRenderer(768 * scaleFactor, 1024 * scaleFactor);
    }
    else
    {
        // Note: the dimensions of the EAGLView should match the dimensions below (360x480)
        // the camera image has an aspect ratio of 360/480, the screen has an aspect ratio of 320/480
        unifeyeMobile->initializeRenderer(360 * scaleFactor, 480 * scaleFactor);
    }
    
    // register our callback method for animations
    unifeyeMobile->registerDelegate(self);
    
}



- (void)viewWillAppear:(BOOL)animated
{	
    [super viewWillAppear:animated];
    
	// if the renderer appears we start rendering and capturing the camera
    [self startAnimation]; 
    if( unifeyeMobile )
    {
        unifeyeMobile->activateCamera(0, 480, 360);
    }
    
    
    // if we start up in landscape mode after having portrait before, we want to make sure that the renderer is rotated correctly
    UIInterfaceOrientation interfaceOrientation = self.interfaceOrientation;    
    [self willAnimateRotationToInterfaceOrientation:interfaceOrientation duration:0];
}



- (void) viewDidAppear:(BOOL)animated
{	
	[super viewDidAppear:animated];
    
}


- (void)viewWillDisappear:(BOOL)animated
{
	// as soon as the view disappears, we stop rendering and stop the camera
    [self stopAnimation];	
    if( unifeyeMobile )
    {
        unifeyeMobile->stopCamera();
    }
    
    [super viewWillDisappear:animated];	
}


- (void)viewDidUnload
{
    // Release any retained subviews of the main view.
    [self setGlView:nil];
    [super viewDidUnload];
}


#pragma mark - Rotation handling



- (BOOL) shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
    // allow rotation in all directions
    return YES;
}


// We will keep the renderer always in the same orientation
// However, if the interface changes, we need to compensate the UI rotation by applying an inverse rotation
// This method is also being called on viewWillAppear
//
// If you don't want your interface to rotate, just return 'NO' in shouldAutorotateToInterfaceOrientation
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation duration:(NSTimeInterval)duration
{
    // adjust the rotation based on the interface orientation
    switch (interfaceOrientation) {
        case UIInterfaceOrientationPortrait:
            self.glView.transform = CGAffineTransformMakeRotation(0);
            break;
            
        case UIInterfaceOrientationPortraitUpsideDown:
            self.glView.transform = CGAffineTransformMakeRotation(M_PI);
            break;
            
        case UIInterfaceOrientationLandscapeLeft:  
            self.glView.transform = CGAffineTransformMakeRotation(M_PI_2);
            break;
            
        case UIInterfaceOrientationLandscapeRight:
            self.glView.transform = CGAffineTransformMakeRotation(-M_PI_2);
            break;
    }   
    
    // make sure the screen bounds are set correctly
    CGRect mainBounds = [UIScreen mainScreen].bounds;
    
    if( UIInterfaceOrientationIsLandscape(interfaceOrientation) )
    {
        int width = mainBounds.size.width;
        int height = mainBounds.size.height;
        mainBounds.size.width = height;
        mainBounds.size.height = width;
    }
    
    if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone)
    {
        // for iPhone the aspect ratio does not fit, so let's correct it
        if( UIInterfaceOrientationIsLandscape(interfaceOrientation) )
        {
            mainBounds.size.height = 360;   // because our renderer is a bit larger
            mainBounds.origin.y = -20;
        }
        else
        {
            mainBounds.size.width = 360;   // because our renderer is a bit larger
            mainBounds.origin.x = -20;            
        }
    }
    
    self.glView.frame = mainBounds;
}




#pragma mark - Handling Touches

- (void) touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    // Implement if you need to handle touches
}

- (void) touchesMoved:(NSSet *)touches withEvent:(UIEvent *)event
{
    // Implement if you need to handle touches
}

- (void) touchesEnded:(NSSet *)touches withEvent:(UIEvent *)event
{
    // Implement if you need to handle touches
}




#pragma mark - @protocol UnifeyeMobileDelegate

- (void) onAnimationEnd: (metaio::IUnifeyeMobileGeometry*) geometry  andName:(NSString*) animationName
{
    // Implement if you want to react to animation callbacks
}


- (void) onNewCameraFrame:(metaio::ImageStruct *)cameraFrame
{
    // implement if you want to react to this event
    // (request an image using unifeye->requestCameraImage)
}

- (void) onCameraImageSaved: (NSString*) filepath
{
}

- (void) onTrackingEvent: (std::vector<metaio::Pose>) poses
{
}


#pragma mark - OpenGL related
//
// You usually don't have to change anything for the methods below
//

- (NSInteger)animationFrameInterval
{
    return animationFrameInterval;
}

- (void)setAnimationFrameInterval:(NSInteger)frameInterval
{
    /*
	 Frame interval defines how many display frames must pass between each time the display link fires.
	 The display link will only fire 30 times a second when the frame internal is two on a display that refreshes 60 times a second. The default frame interval setting of one will fire 60 times a second when the display refreshes at 60 times a second. A frame interval setting of less than one results in undefined behavior.
	 */
    if (frameInterval >= 1) {
        animationFrameInterval = frameInterval;
        
        if (animating) {
            [self stopAnimation];
            [self startAnimation];
        }
    }
}

- (void)startAnimation
{
    if (!animating) {
        CADisplayLink *aDisplayLink = [[UIScreen mainScreen] displayLinkWithTarget:self selector:@selector(drawFrame)];
        [aDisplayLink setFrameInterval:animationFrameInterval];
        [aDisplayLink addToRunLoop:[NSRunLoop currentRunLoop] forMode:NSDefaultRunLoopMode];
        self.displayLink = aDisplayLink;
        
        animating = TRUE;
    }
}

- (void)stopAnimation
{
    if (animating) {
        if([self.displayLink respondsToSelector:@selector(invalidate)])
            [self.displayLink invalidate];
        self.displayLink = nil;
        animating = FALSE;
    }
}

- (void)drawFrame
{
    [glView setFramebuffer];
    
    // tell unifeye to render
    if( unifeyeMobile )
    {
        unifeyeMobile->render();    
    }
    
    [glView presentFramebuffer];
}



#pragma mark - Screenshot taking



- (UIImage*) getScreenshotImage
{
	bool rendererIsRunningNow = [self isAnimating];
	
	// stop it, if it is animating
	if( rendererIsRunningNow )
		[self stopAnimation];
	
	UIImage* image =  [glView getScreenshotImage];	
    
	// restart it again, if it was not animating
	if( rendererIsRunningNow)
		[self startAnimation];
	
	return image;
}



- (IBAction)onBtnClosePushed:(id)sender 
{
    [self dismissModalViewControllerAnimated:YES];
}

@end
