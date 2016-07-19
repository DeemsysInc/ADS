//
//  TrendsViewController.m
//  Trends
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//
#import "ShareViewController_iPhone.h"
#import "ShareViewController_iPad.h"
#import "TrendsViewController.h"
#import <QuartzCore/QuartzCore.h>
#import <AVFoundation/AVFoundation.h>
#import "UIImage+Resize.h"
#import "ShareViewController.h"
#import "Poster.h"
#import "Marker.h"
#import "SessionManager.h"
#import "Constant.h"
#import "Reachability.h"
#import "CacheManager.h"
#import "EAGLView.h"

enum ScanMode {
    imageMarkers,
    metaioMarkers,
    barCodes
};

@implementation TrendsViewController {
    ScanMode scanMode;
}

@synthesize gestureView = _gestureView;
@synthesize shapeImageView = _shapeImageView;
@synthesize captureButton = _captureButton;
@synthesize triggerButton = _triggerButton;
@synthesize characterTimer = _characterTimer;
@synthesize shapeSlider = _shapeSlider;
@synthesize posterData = _posterData;
@synthesize actionSheet = _actionSheet;
@synthesize viewFrame = _viewFrame;
@synthesize aboutView = _aboutView;
@synthesize tutorialView = _tutorialView;
@synthesize aboutViewiPhone5 = _aboutViewiPhone5;
@synthesize tutorialViewiPhone5 = _tutorialViewiPhone5;
@synthesize animationFlag = _animationFlag;
@synthesize helpMessageLabel = _helpMessageLabel;
@synthesize testImageView = _testImageView;

static CGSize originalShapeSize;
static CGFloat currentScale=1.0f;
static CGPoint currentShapeCenterPoint;
static CGPoint startPanGesturePoint;

CGRect screenBound;
CGSize screenSize;
CGFloat screenWidth;
CGFloat screenHeight;

UIAlertView *errorAlert;
static int remainingSeconds;
static int currentMarkerId;
static BOOL isFirstTime=YES;
MBProgressHUD *hud;

int demoMarkerID =0;
- (id)init
{
    self = [super init];
    if (self) {
    }
    return self;
}

void main_sync(dispatch_block_t block) {
    @try{
        if (dispatch_get_current_queue() == dispatch_get_main_queue()) {
            block();
        } else {
            dispatch_sync(dispatch_get_main_queue(), block);
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in main_sync" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString*) getScreenshotImage
{
    NSLog(@"Vikram: getScreenshotImage in metaiosdk");
    //	bool rendererIsRunningNow = [self isAnimating];
    //
    //	// stop it, if it is animating
    //	if( rendererIsRunningNow )
    //		[self stopAnimation];
    //
    //	UIImage* image =  [glView getScreenshotImage];
    //
    //	// restart it again, if it was not animating
    //	if( rendererIsRunningNow)
    //		[self startAnimation];
    
    NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString* publicDocumentsDir = [paths objectAtIndex:0];
    
    NSError* docerror;
    NSArray* files = [[NSFileManager defaultManager] contentsOfDirectoryAtPath:publicDocumentsDir error:&docerror];
    if (files == nil)
    {
        NSLog(@"Error reading contents of documents directory: %@", [docerror localizedDescription]);
    }
    
    
    NSString* timeStamp = [NSString stringWithFormat:@"%ld", (long)[[NSDate date] timeIntervalSince1970]];
    NSString* fileName = [NSString stringWithFormat:@"%@.jpg", timeStamp];
    NSString* fullPath = [publicDocumentsDir stringByAppendingPathComponent:fileName];
    
    
    m_metaioSDK->requestScreenshot([fullPath UTF8String], glView->defaultFramebuffer, glView->colorRenderbuffer);
    //    m_metaioSDK->requestScreenshot(glView->defaultFramebuffer, glView->colorRenderbuffer);
    NSLog(@"framebuffer = %d",glView->defaultFramebuffer);
    
	
	return fullPath;
}
-(void) onScreenshotSaved:(NSString*) filepath
{
	NSLog(@" onScreenshotSaved Image saved: %@", filepath);
    //self.testImageView.image = [UIImage imageWithContentsOfFile:filepath];
    Poster *currentPoster = [[SessionManager getInstance] getPosterForMarkerID:currentMarkerId];
    UIImage *legalImage = nil;
    LegalImagePosition legalImagePosition = BOTTOM_RIGHT_CORNER;
    if (currentPoster.legalContentSwitch==YES) {
        NSData *legalImageData = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:currentPoster.legalImageURL];
        legalImage = [UIImage imageWithData:legalImageData];
        legalImagePosition = currentPoster.legalImagePosition;
    }
   [self pushShareView:[self mergeCharacterWith:[UIImage imageWithContentsOfFile:filepath] legalImage:legalImage andLegalImagePosition:legalImagePosition]];
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}
#pragma mark - button clicked
- (IBAction)captureImageButtonClicked:(id)sender {
    @try {
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        //UIImage *screenShot = [[super getScreenshotImage] fixOrientation];
        NSString *screenShotURL = [self getScreenshotImage];
        [self hideCharacterSystem];
//        NSLog(@"Vikram: screenshot URL %@",screenShotURL);
//        self.testImageView.image =[UIImage imageWithContentsOfFile:screenShotURL];
    
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in captureImageButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) triggerMarker:(int)markerId {
    @try {
        if (!(remainingSeconds > 0) || currentMarkerId!=markerId) {
            [self loadCharacter: markerId];
        }
        [self scheduleCharacterTimer];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in triggerMarker" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)triggerImageButtonClicked:(id)sender {
    @try {
    [self triggerMarker:1];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in triggerImageButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)sliderValueChanged:(UISlider *)sender {
    @try {
        CGFloat currentValue =[sender value];
        currentScale = self.viewFrame.size.width/originalShapeSize.width*(currentValue/100);
        [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in sliderValueChanged" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)backTutorialButtonClicked:(id)sender
{
    @try {
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
         if (screenHeight==568){
             [self.view addSubview:self.tutorialViewiPhone5];
             self.tutorialViewiPhone5.frame = CGRectMake(self.tutorialViewiPhone5.frame.origin.x,
                                                  self.tutorialViewiPhone5.superview.frame.size.height,
                                                  self.tutorialViewiPhone5.frame.size.width,
                                                  self.tutorialViewiPhone5.frame.size.height);
             
             [UIView animateWithDuration:0.5 animations:^{
                 self.tutorialViewiPhone5.center = self.tutorialViewiPhone5.superview.center;
             }];
         }else {
            [self.view addSubview:self.tutorialView];
            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                 self.tutorialView.superview.frame.size.height,
                                                 self.tutorialView.frame.size.width,
                                                 self.tutorialView.frame.size.height);
            
            [UIView animateWithDuration:0.5 animations:^{
                self.tutorialView.center = self.tutorialView.superview.center;
            }];
         }
        [self loadOrCheckMarkersFromService];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in backTutorialButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)aboutButtonClicked:(id)sender
{
    @try {

        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        if (screenHeight==568){
            [self.view addSubview:self.aboutViewiPhone5];
            self.aboutViewiPhone5.frame = CGRectMake(self.aboutViewiPhone5.frame.origin.x,
                                              self.aboutViewiPhone5.superview.frame.size.height,
                                              self.aboutViewiPhone5.frame.size.width,
                                              self.aboutViewiPhone5.frame.size.height);
            
            [UIView animateWithDuration:0.5 animations:^{
                self.aboutViewiPhone5.center = self.aboutViewiPhone5.superview.center;
            }];
        }else{

            [self.view addSubview:self.aboutView];
            self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                              self.aboutView.superview.frame.size.height,
                                              self.aboutView.frame.size.width,
                                              self.aboutView.frame.size.height);
            
            [UIView animateWithDuration:0.5 animations:^{
                self.aboutView.center = self.aboutView.superview.center;
            }];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in aboutButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)continueAboutClicked:(id)sender
{
    @try {
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
       
        [UIView animateWithDuration:0.6 animations:^{
            self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                              self.aboutView.superview.frame.size.height,
                                              self.aboutView.frame.size.width,
                                              self.aboutView.frame.size.height);
             if (screenHeight==568){
                 [self.aboutViewiPhone5 removeFromSuperview];
             }else{
                 [self performSelector:@selector(removeAboutView) withObject:nil afterDelay:0.6];
             }
            
        }];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in continueAboutClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)continueTutorialClicked:(id)sender
{
    @try {
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        [self hideCharacterSystem];
        self.helpMessageLabel.hidden = NO;
        currentMarkerId = 0;
        [UIView animateWithDuration:0.6 animations:^{
            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                 self.tutorialView.superview.frame.size.height,
                                                 self.tutorialView.frame.size.width,
                                                 self.tutorialView.frame.size.height);
            if (screenHeight==568){
                [self.tutorialViewiPhone5 removeFromSuperview];
            }else{
                [self performSelector:@selector(removeTutorialView) withObject:nil afterDelay:0.6];
            }
        }];
        [self loadOrCheckMarkersFromService];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in continueTutorialClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)removeAboutView
{
    @try {
        [self.aboutView removeFromSuperview];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in removeAboutView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)removeTutorialView
{
    @try {
        [self.aboutView removeFromSuperview];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in removeTutorialView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)seemoreLinkClicked:(id)sender
{
    @try {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.seemoreinteractive.com"]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in seemoreLinkClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)metaioLinkClicked:(id)sender
{
    @try {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.metaio.com/imprint/"]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in metaioLinkClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)trendsLinkClicked:(id)sender
{
    @try {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.trendsinternational.com"]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in trendsLinkClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)fbTrendsLinkClicked:(id)sender
{
    @try {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.facebook.com/IntlTrends"]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in fbTrendsLinkClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)twitterLinkClicked:(id)sender
{
    @try {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://twitter.com/IntlTrends"]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in twitterLinkClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}


#pragma mark - Supporting Methods
- (void) initGestureView {
    @try {
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        UIPinchGestureRecognizer *pinchGesture = [[UIPinchGestureRecognizer alloc] initWithTarget:self action:@selector(handlePinch:)];
        [self.gestureView addGestureRecognizer:pinchGesture];
        UIPanGestureRecognizer *panRecognizer = [[UIPanGestureRecognizer alloc] initWithTarget:self action:@selector(move:)];
        panRecognizer.minimumNumberOfTouches = 1;
        panRecognizer.maximumNumberOfTouches = 1;
        [self.gestureView addGestureRecognizer:panRecognizer];
        if (screenHeight==568){
            
            CGRect viewFrame = [self.view frame];
            viewFrame.size.height = 568;  // change the location
            viewFrame.origin.y = 0;  // change the location
            [self.view setFrame:viewFrame];
            
            CGRect gestureViewFrame = [self.gestureView frame];
            gestureViewFrame.size.height = 568;  // change the location
            gestureViewFrame.origin.y = 0;  // change the location
            [self.gestureView setFrame:gestureViewFrame];
            self.helpMessageLabel.hidden = NO;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in initGestureView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - gesture
-(void)handlePinch:(UIPinchGestureRecognizer*)sender {
    @try {
        if(remainingSeconds > 0) {
            CGFloat scale = [sender scale];
            if (sender.state == UIGestureRecognizerStateEnded) {
                currentScale = currentScale*scale;
            } else {
                [self renderShape:scale*currentScale centerPoint:currentShapeCenterPoint];
                self.shapeSlider.value = scale*currentScale*originalShapeSize.width/self.viewFrame.size.width*100;
            }
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in handlePinch" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(void)move:(id)sender {
    @try {
        if(remainingSeconds > 0) {
            CGPoint translatedPoint = [(UIPanGestureRecognizer*)sender translationInView:self.gestureView];
            if([(UIPanGestureRecognizer*)sender state] == UIGestureRecognizerStateBegan) {
                startPanGesturePoint = [[sender view] center];
            }
            CGPoint centerPoint = CGPointMake(currentShapeCenterPoint.x + translatedPoint.x, currentShapeCenterPoint.y + translatedPoint.y);
            [self renderShape:currentScale centerPoint:centerPoint];
            
            if([(UIPanGestureRecognizer*)sender state] == UIGestureRecognizerStateEnded) {
                currentShapeCenterPoint = centerPoint;
            }
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in move" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) renderShape:(CGFloat)scale centerPoint:(CGPoint)centerPoint{
    @try {
        if(0<centerPoint.x && centerPoint.x<self.viewFrame.size.width && 0<centerPoint.y && centerPoint.y<self.viewFrame.size.height) {
            self.shapeImageView.frame = CGRectMake(centerPoint.x-originalShapeSize.width*scale/2,
                                                   centerPoint.y-originalShapeSize.height*scale/2,
                                                   originalShapeSize.width*scale,
                                                   originalShapeSize.height*scale
                                                   );
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in renderShape" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - capture
- (void) pushShareView:(UIImage *)image {
    @try {
        int width = self.viewFrame.size.width;
        if (width <= 320) {
            ShareViewController_iPhone *viewController = [[ShareViewController_iPhone alloc] init];
            viewController.image = image; 
            [self.navigationController pushViewController:viewController animated:YES];
        }else{
            ShareViewController_iPad *viewController = [[ShareViewController_iPad alloc] init];
            viewController.image = image;
            [self.navigationController pushViewController:viewController animated:YES];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in pushShareView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(UIImage *) mergeCharacterWith:(UIImage *)image legalImage:(UIImage *)legalImage andLegalImagePosition:(LegalImagePosition)imagePosition {
    @try {
        CGFloat scale = [UIScreen mainScreen].scale;
        CGRect image2Rect = self.shapeImageView.frame;
        image2Rect = CGRectMake((image2Rect.origin.x)*scale, image2Rect.origin.y*scale, image2Rect.size.width*scale, image2Rect.size.height*scale);
        return [self mergeImages:image image1Size:image.size image2:self.shapeImageView.image image2Rect:image2Rect image3:[UIImage imageNamed:@"watermark.png"] legalImage:legalImage legalImagePosition:imagePosition];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in mergeCharacterWith" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (UIImage *) mergeImages:(UIImage *)image1 image1Size:(CGSize)image1Size image2:(UIImage *)image2 image2Rect:(CGRect)image2Rect image3:(UIImage *)image3 legalImage:(UIImage *)legalImage legalImagePosition:(LegalImagePosition)imagePosition {
    @try {
        UIGraphicsBeginImageContext(image1Size);
        [image1 drawInRect:CGRectMake(0,0,image1Size.width,image1Size.height)];
        [image2 drawInRect:image2Rect];
        [image3 drawInRect:CGRectMake(50,image1Size.height-image3.size.height-50,image3.size.width,image3.size.height)];
        
        if (legalImage!=nil && legalImage.CGImage) {
            
            float imageOffsetX = 0.0;
            float imageOffsetY = 0.0;
            if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone) {
                imageOffsetX = 0.0;
                imageOffsetY = 0.0;
            }
            
            CGFloat X = 0.0;
            CGFloat Y = 0.0;
            
            if (imagePosition == TOP_LEFT_CORNER) {
                X = imageOffsetX;
                Y = 0.0;
            }
            else if (imagePosition == TOP_CENTER)   {
                X = ((image1Size.width-legalImage.size.width)*0.5)+imageOffsetX+(imageOffsetX/2);
                Y = 0.0;
            }
            else if (imagePosition == TOP_RIGHT_CORNER)     {
                X = image1Size.width-legalImage.size.width+(imageOffsetX*2);
                Y = 0.0;
            }
            else if (imagePosition == BOTTOM_LEFT_CORNER)   {
                X = imageOffsetX;
                Y = image1Size.height-legalImage.size.height+imageOffsetY;
            }
            else if (imagePosition == BOTTOM_CENTER)    {
                X = ((image1Size.width-legalImage.size.width)*0.5)+imageOffsetX+(imageOffsetX/2);
                Y = image1Size.height-legalImage.size.height+imageOffsetY;
            }
            else if (imagePosition == BOTTOM_RIGHT_CORNER)     {
                X = image1Size.width-legalImage.size.width+(imageOffsetX*2);
                Y = image1Size.height-legalImage.size.height+imageOffsetY;
            }
            
            [legalImage drawInRect:CGRectMake(X,
                                              Y,
                                              legalImage.size.width,
                                              legalImage.size.height)];
        }
        
        UIImage *mergedImage = UIGraphicsGetImageFromCurrentImageContext();
        UIGraphicsEndImageContext();
        return mergedImage;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in mergeImages" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark -
#pragma mark Character Timer Logic
- (void) scheduleCharacterTimer	{
    @try {
        [self unscheduleCharacterTimer];
        self.characterTimer = [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(updateElapsedTime:) userInfo:nil repeats:YES];
        remainingSeconds = 30;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in scheduleCharacterTimer" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) unscheduleCharacterTimer    {
    @try {
        if (self.characterTimer != nil) {
            [self.characterTimer invalidate];
            self.characterTimer = nil;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in unscheduleCharacterTimer" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(void) triggerAfterFinishCharacterTimer    {
    @try {
        [self unscheduleCharacterTimer];
        [self hideCharacterSystem];
        self.helpMessageLabel.hidden = NO;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in triggerAfterFinishCharacterTimer" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

// Update the timer once a second.
- (void) updateElapsedTime:(NSTimer *)timer
{
    @try {
        remainingSeconds--;
        if (remainingSeconds <= 0) {
            [self triggerAfterFinishCharacterTimer];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in updateElapsedTime" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Metaio Delegate Callbacks
- (void) onTrackingEvent: (std::vector<metaio::TrackingValues>) poses {
    @try {
        if(isFirstTime) {
            isFirstTime = NO;
            return;
        }
        if(!poses.empty()) {
            metaio::TrackingValues pose = poses.front();
            int cosId = pose.coordinateSystemID;
            [self triggerMarker:cosId];
            tryNowButton.hidden = YES;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in onTrackingEvent" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}


- (void) deleteMetaioDirectory {
    @try {
        [[NSFileManager defaultManager] removeItemAtPath:[self getMetaioPath] error: nil];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in deleteMetaioDirectory" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) createMetaioDirectory {
    @try {
        [[NSFileManager defaultManager] createDirectoryAtPath:[self getMetaioPath] withIntermediateDirectories:YES attributes:nil error:nil];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in createMetaioDirectory" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(NSString *) buildMarkerFile:(NSArray *) postersData {
    @try {
        NSString *path = [self getMetaioPath];
        
        [[NSFileManager defaultManager] createDirectoryAtPath:path withIntermediateDirectories:YES attributes:nil error:nil];
        NSMutableString *contents = [[NSMutableString alloc] init];
        
        [contents appendString: @"<?xml version=\"1.0\"?>"];
        [contents appendString: @"<TrackingData>"];
        [contents appendString: @"<Sensors>"];
        NSString *subType =  @"fast";
        NSString *sensor = [[NSString alloc] initWithFormat:@"<Sensor type=\"FeatureBasedSensorSource\" subtype=\"%@\">", subType, nil];
        [contents appendString: sensor];
        [contents appendString: @"<SensorID>FeatureTracking1</SensorID>"];
        [contents appendString: @"<Parameters>"];
        [contents appendString: @"<FeatureBasedParameters>"];
        [contents appendString: @"</FeatureBasedParameters>"];
        [contents appendString: @"</Parameters>"];
        
        for(Poster *poster in postersData) {
            for (Marker *marker in [poster posterMarkers]) {
            [contents appendString: @"<SensorCOS>"];
            [contents appendString: @"<SensorCosID>"];
            [contents appendString: [[NSString alloc] initWithFormat:@"%@%ld", @"Patch:", [marker markerId]]];
            [contents appendString: @"</SensorCosID>"];
            [contents appendString: @"<Parameters>"];
            [contents appendString: @"<referenceImage widthMM=\""];
            [contents appendString: [[NSString alloc] initWithFormat:@"%ld", [marker width]]];
            [contents appendString: @"\" heightMM=\""];
            [contents appendString: [[NSString alloc] initWithFormat:@"%ld", [marker height]]];
            [contents appendString: @"\">"];
            [contents appendString: [[marker markerImage] lastPathComponent]];
            [contents appendString: @"</referenceImage>"];
            [contents appendString: @"</Parameters>"];
            [contents appendString: @"</SensorCOS>"];
                if ([[marker markerTitle] isEqualToString: @"DemoMarker"]){
                    demoMarkerID = [marker markerId];
                }
            }
        }
        
        [contents appendString: @"</Sensor>"];
        [contents appendString: @"</Sensors>"];
        [contents appendString: @"<Connections>"];
        
        for(Poster *poster in postersData) {
            for (Marker *marker in [poster posterMarkers]) {
            [contents appendString: @"<COS>"];
            [contents appendString: @"<COSName>MarkerlessCOS</COSName>"];
            [contents appendString: @"<Fuser type=\"SmoothingFuser\">"];
            [contents appendString: @"<Parameters>"];
            [contents appendString: @"<AlphaRotation>0.5</AlphaRotation> "];
            [contents appendString: @"<AlphaTranslation>0.8</AlphaTranslation>"];
            [contents appendString: @"<KeepPoseForNumberOfFrames>0</KeepPoseForNumberOfFrames>"];
            [contents appendString: @"</Parameters>"];
            [contents appendString: @"</Fuser>"];
            [contents appendString: @"<SensorSource trigger=\"1\">"];
            [contents appendString: @"<SensorID>FeatureTracking1</SensorID>"];
            [contents appendString: @"<SensorCosID>"];
            [contents appendString: [[NSString alloc] initWithFormat:@"%@%ld", @"Patch:", [marker markerId]]];
            [contents appendString: @"</SensorCosID>"];
            [contents appendString: @"<HandEyeCalibration>"];
            [contents appendString: @"<TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset>"];
            [contents appendString: @"<RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset>"];
            [contents appendString: @"</HandEyeCalibration>"];
            [contents appendString: @"<COSOffset>"];
            [contents appendString: @"<TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset>"];
            [contents appendString: @"<RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset>"];
            [contents appendString: @"</COSOffset>"];
            [contents appendString: @"</SensorSource>"];
                [contents appendString: @"</COS>"];
            }
        }
        
        [contents appendString: @"</Connections>"];
        [contents appendString: @"</TrackingData>"];
        NSLog(@"Vikram: markers.xml: %@",contents);
        NSString *fileName = [path stringByAppendingPathComponent:@"markers.xml"];
        [contents writeToFile:fileName atomically:NO encoding:NSUTF8StringEncoding error:nil];
        return fileName;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in buildMarkerFile" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(NSString *) getMetaioPath {
    @try {
        NSString *cacheDirectory = [NSSearchPathForDirectoriesInDomains
                                    (NSCachesDirectory, NSUserDomainMask, YES) objectAtIndex:0];
        return [[NSString alloc] initWithFormat:@"%@%@", cacheDirectory, @"/Metaio/MarkerData"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in getMetaioPath" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) downloadPosterMarkers: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
    @try {
        NSString *path = [self getMetaioPath];
        NSUInteger count = [postersData count];
        [postersData enumerateObjectsUsingBlock:^(id posterMarker, NSUInteger idx, BOOL *stop) {
            if (progressIndicator) {
                progressIndicator(idx, count);
            }
            [self downloadImagesOnPosterMarker:posterMarker toPath:path];
        }];
        
         if (progressIndicator) {
            progressIndicator(count, count);
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in downloadPosterMarkers" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) downloadImagesOnPosterMarker:(Poster *) poster toPath:(NSString *) path {
    @try {
        // Download Marker Image
        NSString *imageUrl;
        for (Marker *marker in [poster posterMarkers]) {
            imageUrl = [marker markerImage];
        }
        NSLog(@"Poster Count = %i",[poster posterMarkers].count);
        NSLog(@"Poster URL = %@",imageUrl);
        NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
        NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
        if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
            data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
        }
        NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
        [data writeToFile:cacheFilePath atomically:YES];
        
        // Download Legal Image
        if (poster.legalContentSwitch) {
            imageUrl = [poster legalImageURL];
            NSLog(@"Legal URL = %@",imageUrl);
            data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in downloadImagesOnPosterMarker" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) downloadPosterMarkersVisual: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
    @try {
        NSString *path = [self getMetaioPath];
        NSUInteger count = [postersData count];
        [postersData enumerateObjectsUsingBlock:^(id posterVisual, NSUInteger idx, BOOL *stop) {
            if (progressIndicator) {
                progressIndicator(idx, count);
            }
            [self downloadImagesOnPosterVisual:posterVisual toPath:path];
        }];
        if (progressIndicator) {
            progressIndicator(count, count);
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in downloadPosterMarkersVisual" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) downloadImagesOnPosterVisual:(Poster *) poster toPath:(NSString *) path {
    @try {
        NSString *imageUrl= [poster posterImage];
        NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
        NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
        if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
            data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
        }
        NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
        NSLog(@"Poster Image Local Path = %@",cacheFilePath);
        [poster setLocalPosterImagePath:cacheFilePath];
        [data writeToFile:cacheFilePath atomically:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in downloadImagesOnPosterVisual" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Metaio
- (void)loadTrackingDataFromUserDefault {
    @try {
        NSLog(@"loadTrackingDataFromUserDefault");
        
        if([self noMarkerAvailableLocally]) {
            [self loadDefaultMarker];
        } else {
            NSData *markerData = (NSData *) [[NSUserDefaults standardUserDefaults] objectForKey:@"markerDataArray"];
            NSMutableArray *markerDataList = [NSKeyedUnarchiver unarchiveObjectWithData:markerData];
            [SessionManager getInstance].markerDataArray = markerDataList;
            NSString *trackingDataFile = [[self getMetaioPath] stringByAppendingPathComponent:@"markers.xml"];
            NSLog(@"Tracking Data file = %@",trackingDataFile);
            main_sync(^{
                isFirstTime = YES;
                m_metaioSDK->setTrackingConfiguration([trackingDataFile UTF8String]);
            });
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadTrackingDataFromUserDefault" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (void) hideCharacterSystem {
    @try {
        self.captureButton.hidden = YES;
        self.shapeImageView.hidden = YES;
        self.shapeSlider.hidden = YES;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in hideCharacterSystem" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (void) unHideCharacterSystem {
    @try {
        self.shapeImageView.hidden = NO;
        self.captureButton.hidden = NO;
        self.shapeSlider.hidden = NO;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in unHideCharacterSystem" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) loadCharacter:(int)markerId {
    @try {
        currentMarkerId = markerId;
        NSLog(@"Current Marker ID = %i",currentMarkerId);
        
        self.helpMessageLabel.hidden = YES;
        self.shapeSlider.value = 60;
        Poster *currentPoster = [[SessionManager getInstance] getPosterForMarkerID:markerId];
        if (currentPoster!=nil) {
            if (currentPoster.localPosterImagePath!=nil && ![currentPoster.localPosterImagePath isEqualToString:@""]) {
                self.shapeImageView.image = [UIImage imageWithContentsOfFile:currentPoster.localPosterImagePath];
            }
            NSLog(@"Loadcharacter - if");
        }
        else{
            NSLog(@"Loadcharacter - else");
            if (markerId == 1)
                self.shapeImageView.image = [UIImage imageNamed:@"The_Hobbit_Visual.png"];
            else if (markerId == 2)
                self.shapeImageView.image = [UIImage imageNamed:@"Monster_High_Visual.png"];
            else if (markerId == 3)
                self.shapeImageView.image = [UIImage imageNamed:@"Tink.png"];
        }
        
        if (self.shapeImageView.image.size.width>0 || self.shapeImageView.image.size.height>0) {
            [self unHideCharacterSystem];
            originalShapeSize = CGSizeMake(self.viewFrame.size.width/2, (self.viewFrame.size.width/2)*self.shapeImageView.image.size.height/self.shapeImageView.image.size.width);
            currentShapeCenterPoint = CGPointMake(self.viewFrame.size.width/2,self.viewFrame.size.height/2);
            currentScale = 1.0f;
            [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
            NSLog(@"Loading character... (%d)",markerId);
        }else {
            [self reSetImageMarkersAndVisualsForDownload];
        }
        NSLog(@"Loadcharacter - end of function");
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadCharacter" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) reSetImageMarkersAndVisualsForDownload {
    @try {
        [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"markerDataArray"];
        [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterData"];
        [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"lastDateKey"];
        [self loadTrackingDataFromUserDefault];
        self.helpMessageLabel.hidden = NO;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in reSetImageMarkersAndVisualsForDownload" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)loadMarkers:(ScanMode)newScanMode progressIndicator:(void(^)(int current, int count))progressIndicator {
    @try {
        NSLog(@"loadMarkers");
        scanMode = newScanMode;
        switch (scanMode) {
            case imageMarkers: {
                NSArray *postersData = [[SessionManager getInstance] getPosterMarkerData];
                NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
                if ([postersData count]){
                    NSLog(@"Posters count = %d",[postersData count]);
                    [self deleteMetaioDirectory];
                    [self createMetaioDirectory];
                    [self downloadPosterMarkers: postersData progressIndicator:progressIndicator];
                    [self downloadPosterMarkersVisual: postersData progressIndicator:progressIndicator];
                    NSData *markerData = [NSKeyedArchiver archivedDataWithRootObject:[SessionManager getInstance].markerDataArray];
                    [[NSUserDefaults standardUserDefaults] setObject:markerData forKey:@"markerDataArray"];
                    [[NSUserDefaults standardUserDefaults]setValue:[NSKeyedArchiver archivedDataWithRootObject:posterArray] forKey:@"PosterData"];
                    [self buildMarkerFile: postersData];
                    [self loadTrackingDataFromUserDefault];
                }
                
                break;
            }
            case metaioMarkers: {
                break;
            }
            case barCodes:
                break;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadMarkers" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
#pragma mark - viewDidLoad lifecycle
- (BOOL) noMarkerAvailableLocally {
    @try {
        // Check any marker available locally in user defaults.
        BOOL isNoMarkerAvailable;
        NSData *markerData = (NSData *) [[NSUserDefaults standardUserDefaults] objectForKey:@"markerDataArray"];
        NSMutableArray *markerDataList = [NSKeyedUnarchiver unarchiveObjectWithData:markerData];
        
        if ([markerDataList count]>0)
            isNoMarkerAvailable = NO;
        else 
            isNoMarkerAvailable = YES;
        return isNoMarkerAvailable;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in noMarkerAvailableLocally" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) loadDefaultMarker {
    @try {
        // Load default Hobbit marker in metaio
        NSLog(@"loadDefaultMarker");
        NSString *trackingDataFile = [[NSBundle mainBundle] pathForResource:@"TrackingDefault_Marker" ofType:@"xml" inDirectory:@"assets"];
        NSString *data = [[NSString alloc] initWithUTF8String:[trackingDataFile UTF8String]];
        NSLog(@"Tracking Data File = %@",data);
        main_sync(^{
            isFirstTime = YES;
            m_metaioSDK->setTrackingConfiguration([trackingDataFile UTF8String]);
        });
        [self updateMarkersFromServiceFlag:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadDefaultMarker" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL) isLoadMarkersFromService {
    @try {
        // Check User Default variable "updateMarkersFromService" YES or NO
        return [[NSUserDefaults standardUserDefaults] boolForKey:@"updateMarkersFromService"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in isLoadMarkersFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) updateMarkersFromServiceFlag: (BOOL)isNeedToUpdate {
    @try {
        // Set "updateMarkersFromService" as per the given isNeedToUpdate flag
        [[NSUserDefaults standardUserDefaults ]setBool:isNeedToUpdate forKey:@"updateMarkersFromService"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in updateMarkersFromServiceFlag" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL) isInternetAvailable {
    @try {
        // Check internet is available or not.
        BOOL isAvailable;
        Reachability* reachability = [Reachability reachabilityWithHostName:CHECK_REACHABLE_HOST];;
        NetworkStatus remoteHostStatus = [reachability currentReachabilityStatus];
        if(remoteHostStatus == NotReachable) 
            isAvailable = NO;
        else if (remoteHostStatus == ReachableViaWiFi || remoteHostStatus == ReachableViaWWAN) 
            isAvailable = YES;
        return isAvailable;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in isInternetAvailable" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

-(NSArray*) retrieveServiceMarkers {
    @try {
        // Return markers array by calling the service
        return [[SessionManager getInstance] getPosterMarkerData];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in retrieveServiceMarkers" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) loadMarkers {
    @try {
        if([self isInternetAvailable]) {
            hud= [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
            dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
                [self loadMarkers:imageMarkers progressIndicator:^(int current, int count) {
                    if (current < count) {
                        hud.mode = MBProgressHUDModeAnnularDeterminate;
                        hud.progress = (float)current / count;
                    } else {
                        hud.mode = MBProgressHUDModeIndeterminate;
                    }
                }];
                dispatch_async(dispatch_get_main_queue(), ^{
                    self.animationFlag = false;
                    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
                    self.helpMessageLabel.hidden = NO;
                });
            });
            [self updateMarkersFromServiceFlag:NO];
        } else {
            self.helpMessageLabel.hidden = NO;
            self.animationFlag = false;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadMarkers" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL) isTimeForCheckLatestMarkersFromService {
    @try {
        NSDate *lastDate = (NSDate *)[[NSUserDefaults standardUserDefaults] objectForKey:@"lastDateKey"];
        if(lastDate) {
            NSTimeInterval timeInterval = [lastDate timeIntervalSinceNow];
            if(timeInterval < CMS_SERVICE_CHECK_SEC) {
                return YES;
            }
        } else {
            [self updatedTimeForCheckLatestMarkersFromService];
        }
        return NO;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in isTimeForCheckLatestMarkersFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) updatedTimeForCheckLatestMarkersFromService {
    @try {
        [[NSUserDefaults standardUserDefaults] setObject:[NSDate date] forKey:@"lastDateKey"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in updatedTimeForCheckLatestMarkersFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) checkLatestMarkersFromService {
    @try {
        if([self isTimeForCheckLatestMarkersFromService] && [self isInternetAvailable]) {
            [self retrieveServiceMarkers];
            NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
            NSArray *userDefaultPosterArray = (NSArray*)[NSKeyedUnarchiver unarchiveObjectWithData:
                                                         (NSData*)[[NSUserDefaults standardUserDefaults] dataForKey:@"PosterData"]];
            [self updateMarkersFromServiceFlag:![posterArray isEqualToArray:userDefaultPosterArray]];
            [self updatedTimeForCheckLatestMarkersFromService];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in checkLatestMarkersFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - lifecycle
- (void)displayTutorialView {
    @try {
        
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        if (screenHeight==568){
            [self.view addSubview:self.tutorialViewiPhone5];
        }else{
              [self.view addSubview:self.tutorialView];
        }
        if ([[[NSUserDefaults standardUserDefaults ]objectForKey:@"tutorialFlag"] length]==0) {
            [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"tutorialFlag"];
            
            if (screenHeight==568){
                [self.view addSubview:self.tutorialViewiPhone5];
                self.tutorialViewiPhone5.frame = CGRectMake(self.tutorialViewiPhone5.frame.origin.x,
                                                     self.tutorialViewiPhone5.superview.frame.size.height,
                                                     self.tutorialViewiPhone5.frame.size.width,
                                                     self.tutorialViewiPhone5.frame.size.height);
                
                [UIView animateWithDuration:0.5 animations:^{
                    //self.tutorialView.center = self.tutorialView.superview.center;
                    self.tutorialViewiPhone5.frame = CGRectMake(self.tutorialViewiPhone5.frame.origin.x,
                                                         0,
                                                         self.tutorialViewiPhone5.frame.size.width,
                                                         self.tutorialViewiPhone5.frame.size.height);
                }];
            }else{
                [self.view addSubview:self.tutorialView];
                self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                     self.tutorialView.superview.frame.size.height,
                                                     self.tutorialView.frame.size.width,
                                                     self.tutorialView.frame.size.height);
                
                [UIView animateWithDuration:0.5 animations:^{
                    //self.tutorialView.center = self.tutorialView.superview.center;
                    self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                         0,
                                                         self.tutorialView.frame.size.width,
                                                         self.tutorialView.frame.size.height);
                }];
            }
        }
        [self loadOrCheckMarkersFromService];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in displayTutorialView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) loadOrCheckMarkersFromService {
    @try {
        NSLog(@"loadOrCheckMarkersFromService");
        if([self isLoadMarkersFromService]) {
            NSLog(@" -- loadMarkers");
            [self loadMarkers];
        } else {
            NSLog(@" -- checkLatestMarkersFromService");
            [self performSelectorInBackground:@selector(checkLatestMarkersFromService) withObject:nil];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in loadOrCheckMarkersFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewDidLoad
{
    @try {
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        [super viewDidLoad];
        self.navigationController.view.frame = self.viewFrame;
        CGRect frame = self.view.frame;
        frame = CGRectMake(0, 0, 320, 480);
        
        NSLog(@">>> %f, %f, %f, %f",frame.origin.x, frame.origin.y, frame.size.width, frame.size.height);
        
        
        [self initGestureView];
        
        self.shapeSlider.transform =CGAffineTransformMakeRotation(M_PI * -0.5);
        self.animationFlag =true;
        //[self loadTrackingDataFromUserDefault];
        
        //[self displayTutorialView];
        self.questionButton.hidden= NO;
         [self loadMarkers];
        //[self loadOrCheckMarkersFromService];
         self.helpMessageLabel.hidden = NO;
        if (screenHeight==568){
           
            CGRect helpMessageLabelViewFrame = [self.helpMessageLabel frame];
            helpMessageLabelViewFrame.origin.y = 260;  // change the location
            [self.helpMessageLabel setFrame:helpMessageLabelViewFrame];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in viewDidLoad" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)checkProgressIndicator
{
    @try {
        if (self.animationFlag) {
            hud = [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in checkProgressIndicator" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewDidAppear:(BOOL)animated   {
    @try {
        NSLog(@"Vikram: viewdidappear");
        [super viewDidAppear:animated];
        [self displayTutorialView];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in viewDidAppear" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewWillAppear:(BOOL)animated {
    @try {
        [super viewWillAppear:animated];
        [self.navigationController setNavigationBarHidden:YES];
        self.triggerButton.hidden = NO;
        [self hideCharacterSystem];
        NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
        if ([data count]) {
            self.helpMessageLabel.hidden = NO;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in viewWillAppear" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewWillDisappear:(BOOL)animated {
    @try {
        [super viewWillDisappear:animated];
        [self unscheduleCharacterTimer];
        remainingSeconds = 0;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in viewWillDisappear" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewDidUnload
{
    @try {
        [super viewDidUnload];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in viewDidUnload" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    @try {
        return (interfaceOrientation == UIInterfaceOrientationPortrait);
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in shouldAutorotateToInterfaceOrientation" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)setViewFrameCustom:(CGRect)viewFrame {
    @try {
        CGRect newFrame = CGRectMake(viewFrame.origin.x, 0, viewFrame.size.width, viewFrame.size.height+viewFrame.origin.y);
        self.viewFrame = newFrame;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in setViewFrameCustom" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}
- (IBAction)tryItNow:(id)sender {
    @try {
        NSLog(@"Vikram: tryitNow %d",demoMarkerID);
        [self triggerMarker:demoMarkerID];
//        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
//        self.helpMessageLabel.hidden = YES;
//        self.shapeSlider.value = 60;
//        UIImage *sampleImage = [UIImage imageNamed:@"watermark.png"];
//        NSString *path = [self getMetaioPath];
//        NSLog(@"Vikram: getmetaiopath %@ and sample image name %@",path,[[NSString alloc] initWithFormat:@"%@",sampleImage]);
//        self.shapeImageView.image =sampleImage;
//        
//        if (self.shapeImageView.image.size.width>0 || self.shapeImageView.image.size.height>0) {
//            [self unHideCharacterSystem];
//            originalShapeSize = CGSizeMake(self.viewFrame.size.width/2, (self.viewFrame.size.width/2)*self.shapeImageView.image.size.height/self.shapeImageView.image.size.width);
//            currentShapeCenterPoint = CGPointMake(self.viewFrame.size.width/2,self.viewFrame.size.height/2);
//            currentScale = 1.0f;
//            [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
//            NSLog(@"Loading character...");
//        }else {
//            [self reSetImageMarkersAndVisualsForDownload];
//        }
//        NSLog(@"Loadcharacter - end of function");
//        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in tryItNow" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
