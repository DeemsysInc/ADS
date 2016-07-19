//
//  MiramaxViewController.m
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//
#import "ShareViewController_iPhone.h"
#import "ShareViewController_iPad.h"
#import "MiramaxViewController.h"
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
#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>

enum ScanMode {
    imageMarkers,
    metaioMarkers,
    barCodes
};

@implementation MiramaxViewController {
    ScanMode scanMode;
}

@synthesize gestureView = _gestureView;
@synthesize shapeImageView = _shapeImageView;
@synthesize captureButton = _captureButton;
@synthesize videoButton = _videoButton;
@synthesize productLinkButton = _productLinkButton;
@synthesize triggerButton = _triggerButton;
@synthesize characterTimer = _characterTimer;
@synthesize shapeSlider = _shapeSlider;
@synthesize posterData = _posterData;
@synthesize actionSheet = _actionSheet;
@synthesize viewFrame = _viewFrame;
@synthesize aboutView = _aboutView;
@synthesize aboutScrollView = _aboutScrollView;
@synthesize aboutContentView = _aboutContentView;
@synthesize termsOfUseView = _termsOfUseView;
@synthesize termsOfUseContentViewOne = _termsOfUseContentViewOne;
@synthesize termsOfUseContentViewTwo = _termsOfUseContentViewTwo;
@synthesize termsOfUseScrollView = _termsOfUseScrollView;
@synthesize tutorialView = _tutorialView;
@synthesize tutorialContentView = _tutorialContentView;
@synthesize tutorialScrollView = _tutorialScrollView;
@synthesize animationFlag = _animationFlag;
@synthesize helpMessageLabel = _helpMessageLabel;
@synthesize optionView = _optionView;
@synthesize optionOneButton = _optionOneButton;
@synthesize optionTwoButton = _optionTwoButton;

@synthesize creditAppView = _creditAppView;
@synthesize customCell = _customCell;
@synthesize tableView = _tableView;
@synthesize dealsDetailBackButton = _dealsDetailBackButton;

static CGSize originalShapeSize;
static CGFloat currentScale = 1.0f;
static CGPoint currentShapeCenterPoint;
static CGPoint startPanGesturePoint;

CGRect screenBound;
CGSize screenSize;
CGFloat screenWidth;
CGFloat screenHeight;

static int remainingSeconds;
static int currentMarkerId;
static BOOL isFirstTime = YES;
 NSString * modelTextureURL;
static BOOL isModelAnimate = NO;

static int totRows = 0;
NSInteger diceNow;
NSInteger diceNowForMax;
int startRow;
int endRow;
NSInteger lockedRow;
NSInteger maxScrollRow = 20;
NSInteger rotateTimes = 0; 

static MPMoviePlayerViewController *moviePlayer;

MBProgressHUD *hud;

- (id)init
{
    self = [super init];
    if (self) {
    }
    return self;
}

void main_sync(dispatch_block_t block) {
    if (dispatch_get_current_queue() == dispatch_get_main_queue()) {
        block();
    } else {
        dispatch_sync(dispatch_get_main_queue(), block);
    }
}

#pragma mark - MPMoviePlayerController
-(void)playMovie: (NSString *)movieUrl
{
    if (movieUrl==nil || [movieUrl isEqualToString:@""]) {
        NSLog(@"Please Provide Movie URL or Local Path");
        return;
    }
    //NSLog(@"movieUrl: %@",movieUrl);
    NSString*thePath=[[NSBundle mainBundle] pathForResource:movieUrl ofType:@"mp4" inDirectory:@"assets/Videos"];
    NSURL *movieFileURL=[NSURL fileURLWithPath:thePath];
    
    //NSURL *movieFileURL = [NSURL URLWithString:movieUrl];
    moviePlayer = [[MPMoviePlayerViewController alloc] initWithContentURL:movieFileURL];
    if (moviePlayer != nil){
        
        //Create our moviePlayer
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        //NSLog(@"Screen width: %f",(screenWidth*0.6));
        // NSLog(@"Screen Height: %f",(screenHeight*0.3));
        //NSLog(@"Screen size: %f",screenSize);
        [moviePlayer.moviePlayer.view setFrame:CGRectMake((screenWidth*0.2), (screenHeight*0.3), (screenWidth*0.65), (screenHeight*0.33))];
        
        
        [moviePlayer.moviePlayer prepareToPlay];
        
        
        NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
        
        [notificationCenter addObserver:self selector:@selector(onMediaPlayerDone:) name:MPMoviePlayerPlaybackDidFinishNotification object:moviePlayer.moviePlayer];
        [notificationCenter addObserver:self selector:@selector(onNaturalSize:) name:MPMovieNaturalSizeAvailableNotification object:moviePlayer.moviePlayer];
        [notificationCenter addObserver:self selector:@selector(onWillExitScreen:) name:MPMoviePlayerWillExitFullscreenNotification object:moviePlayer.moviePlayer];
        
        //Some additional customization
        //moviePlayer.moviePlayer.fullscreen = NO;
        moviePlayer.moviePlayer.allowsAirPlay = YES;
        moviePlayer.moviePlayer.shouldAutoplay = YES;
        moviePlayer.moviePlayer.controlStyle = MPMovieControlStyleNone;
        moviePlayer.moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
        [self.view addSubview:moviePlayer.moviePlayer.view];
        
        [self createStopButtonCustom];
        [self createPlayButtonCustom];
        
    } else {
        NSLog(@"ERROR: Failed to instantiate the movie player.");
    }
}
-(void)createPlayButtonCustom{
    [self removePauseButtonCustom];
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    
    playerButtonImage = [UIImage imageNamed:@"playPlayerButton.png"];
    playerPlay = [UIButton buttonWithType:UIButtonTypeCustom];
    playerPlay.frame = CGRectMake((screenWidth*0.3), (screenHeight*0.6), 27,27);
    [playerPlay setImage:playerButtonImage forState:UIControlStateNormal];
    [playerPlay addTarget:self action:@selector(playVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:playerPlay];
}

-(void)createPauseButtonCustom{
    [self removePlayButtonCustom];
    CGFloat pauseButtonScreenWidth = screenWidth;
    CGFloat pauseButtonScreenHeight = screenHeight;
    
    pauseButtonImage = [UIImage imageNamed:@"pausePlayerButton.png"];
    playerPause = [UIButton buttonWithType:UIButtonTypeCustom];
    playerPause.frame = CGRectMake((pauseButtonScreenWidth*0.3), (pauseButtonScreenHeight*0.6), 27,27);
    [playerPause setImage:pauseButtonImage forState:UIControlStateNormal];
    [playerPause addTarget:self action:@selector(pauseVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:playerPause];
}
-(void)createStopButtonCustom{
    [self removePauseButtonCustom];
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    
    stopButtonImage = [UIImage imageNamed:@"stopPlayerButton.png"];
    playerStop = [UIButton buttonWithType:UIButtonTypeCustom];
    playerStop.frame = CGRectMake((screenWidth*0.7), (screenHeight*0.6), 27,27);
    [playerStop setImage:stopButtonImage forState:UIControlStateNormal];
    [playerStop addTarget:self action:@selector(stopVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:playerStop];
}
-(void)removeStopButtonCustom{
    stopButtonImage = nil;
    [playerStop removeFromSuperview];
    playerStop.hidden = YES;
}
-(void)removePlayButtonCustom{
    playerButtonImage = nil;
    [playerPlay removeFromSuperview];
    playerPlay.hidden = YES;
}
-(void)removePauseButtonCustom{
    pauseButtonImage = nil;
    [playerPause removeFromSuperview];
    playerPause.hidden = YES;
}
-(void)stopVideoCustom:(UIButton *)sender
{
    [moviePlayer.moviePlayer stop];
    [self closeMediaPlayerController];
}
-(void)playVideoCustom:(UIButton *)sender
{
    [moviePlayer.moviePlayer play];
    [self createPauseButtonCustom];
    [playerPlay removeFromSuperview];
    playerButtonImage = nil;
}
-(void)pauseVideoCustom:(UIButton *)sender
{
    [moviePlayer.moviePlayer pause];
    [self createPlayButtonCustom];
    [playerPause removeFromSuperview];
    pauseButtonImage = nil;
}

//-(void)playMovie: (NSString *)movieUrl
//{
//    if (movieUrl==nil || [movieUrl isEqualToString:@""]) {
//        NSLog(@"Please Provide Movie URL or Local Path");
//        return;
//    }
//
//    NSURL *movieFileURL = [NSURL URLWithString:movieUrl];
//    moviePlayer = [[MPMoviePlayerViewController alloc] initWithContentURL:movieFileURL];
//    if (moviePlayer != nil){
//
//        [moviePlayer.moviePlayer setControlStyle:MPMovieControlStyleDefault];
//        moviePlayer.moviePlayer.scalingMode = MPMovieScalingModeAspectFit;
//        CGRect frame;
//        if(self.interfaceOrientation ==UIInterfaceOrientationPortrait)
//            frame = CGRectMake(20, 69, 280, 170);
//        else if(self.interfaceOrientation ==UIInterfaceOrientationLandscapeLeft || self.interfaceOrientation ==UIInterfaceOrientationLandscapeRight)
//            frame = CGRectMake(20, 61, 210, 170);
//        [moviePlayer.moviePlayer.view setFrame:frame];  // player's frame must match parent's
//        [self.view addSubview: moviePlayer.moviePlayer.view];
//        [self.view bringSubviewToFront:moviePlayer.moviePlayer.view];
//
//        [[NSNotificationCenter defaultCenter] addObserver:self
//                                                 selector:@selector(moviePlayBackDidFinish:)
//                                                     name:MPMoviePlayerPlaybackDidFinishNotification
//                                                   object:moviePlayer.moviePlayer];
//
//        [moviePlayer.moviePlayer prepareToPlay];
//        [moviePlayer.moviePlayer play];
//    } else {
//        NSLog(@"ERROR: Failed to instantiate the movie player.");
//    }
//}
//- (void) moviePlayBackDidFinish:(NSNotification*)notification {
//    MPMoviePlayerController *player = [notification object];
//    [[NSNotificationCenter defaultCenter]
//     removeObserver:self
//     name:MPMoviePlayerPlaybackDidFinishNotification
//     object:moviePlayer.moviePlayer];
//
//    if ([player
//         respondsToSelector:@selector(setFullscreen:animated:)])
//    {
//        //self.navigationController.navigationBarHidden = YES;
//        [player.view removeFromSuperview];
//    }
//    [self clearOptions:nil];
//}
- (void)onMediaPlayerDone:(NSNotification *)notification {
    [self closeMediaPlayerController];
    
}

- (void)onWillExitScreen:(NSNotification *)notification {
    //NSLog(@"Vikram: onWillExitScreen function.");
    moviePlayer.moviePlayer.fullscreen = NO;
}



- (void)closeMediaPlayerController {
    //NSLog(@"Vikram: CloseMediaPlayerController function.");
    NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
    [notificationCenter removeObserver:self name:MPMoviePlayerPlaybackDidFinishNotification object:moviePlayer.moviePlayer];
    [moviePlayer.moviePlayer.view removeFromSuperview];
    [moviePlayer.moviePlayer stop];
    moviePlayer = nil;
    
    [self removePauseButtonCustom];
    [self removePlayButtonCustom];
    [self removeStopButtonCustom];
    
    [self clearOptions:nil];
}


- (void) stopPlayingVideo:(NSNotification*)aNotification    {
    
    if (moviePlayer != nil){
        
        // Remove Notifications
        [[NSNotificationCenter defaultCenter]
         removeObserver:self
         name:MPMoviePlayerPlaybackDidFinishNotification
         object:moviePlayer];
        [[NSNotificationCenter defaultCenter]
         removeObserver:self
         name:MPMoviePlayerDidExitFullscreenNotification
         object:moviePlayer];
        
        // Hide Trailer View
        if (self.optionView.hidden==NO) {
            [self optionViewToggleButtonClicked:nil];
        }
        
        [moviePlayer.moviePlayer stop];
        [moviePlayer dismissModalViewControllerAnimated:YES];
        moviePlayer = nil;
    }
}


#pragma mark - button clicked
- (IBAction)captureImageButtonClicked:(id)sender {
    [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    UIImage *screenShot = [[super getScreenshotImage] fixOrientation];
    [self pushShareView:[self mergeCharacterWith:screenShot]];
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

- (IBAction)videoButtonClicked:(id)sender {
    
    if (currentSelectedOption!=nil) {
        //This is play function
        [self playMovie:currentSelectedOption.videoURL];
    }
}

- (IBAction)productLinkButtonClicked:(id)sender {
    if (currentSelectedOption!=nil) {
        if(currentSelectedOption.productURL && [currentSelectedOption.productURL length]>0) {
            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:currentSelectedOption.productURL]];
        }
    }
}

- (void) triggerMarker:(int)markerId {
    if (currentMarkerId!=markerId) {
        
        currentMarkerId = markerId;
        
        if (self.optionView.hidden) {
            [self optionViewToggleButtonClicked:nil];
            
        }
    }
    if(TIMER_ENABLE) [self scheduleCharacterTimer];
}

//- (IBAction)triggerImageButtonClicked:(id)sender {
//    [self triggerMarker:1];
//}

- (IBAction)sliderValueChanged:(UISlider *)sender {
    CGFloat currentValue =[sender value];
    currentScale = self.viewFrame.size.width/originalShapeSize.width*(currentValue/100);
    [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
}

- (IBAction)backTutorialButtonClicked:(id)sender
{
    [self.view addSubview:self.tutorialView];
    self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                         self.tutorialView.superview.frame.size.height,
                                         self.tutorialView.frame.size.width,
                                         self.tutorialView.frame.size.height);
    
    [UIView animateWithDuration:0.5 animations:^{
        self.tutorialView.center = self.tutorialView.superview.center;
    }];
}
- (IBAction)creditAppBackClicked:(id)sender
{
    self.helpMessageLabel.hidden = NO;
    [UIView animateWithDuration:0.6 animations:^{
        self.creditAppView.frame = CGRectMake(self.creditAppView.frame.origin.x,
                                             self.creditAppView.superview.frame.size.height,
                                             self.creditAppView.frame.size.width,
                                             self.creditAppView.frame.size.height);
        [self performSelector:@selector(removeCreditAppView) withObject:nil afterDelay:0.6];
    }];
    
    [self loadOrCheckMarkersFromService];
}

- (IBAction)dealAppBackClicked:(id)sender
{
    self.helpMessageLabel.hidden = NO;
    [UIView animateWithDuration:0.6 animations:^{
        self.dealsView.frame = CGRectMake(self.dealsView.frame.origin.x,
                                              self.dealsView.superview.frame.size.height,
                                              self.dealsView.frame.size.width,
                                              self.dealsView.frame.size.height);
        [self performSelector:@selector(removeDealsView) withObject:nil afterDelay:0.6];
    }];
    
    [self loadOrCheckMarkersFromService];
}

- (IBAction)dealDetailBackClicked:(id)sender {
    self.helpMessageLabel.hidden = NO;
    [UIView animateWithDuration:0.6 animations:^{
        self.dealsDetailView.frame = CGRectMake(self.dealsDetailView.frame.origin.x,
                                                self.dealsDetailView.superview.frame.size.height,
                                                self.dealsDetailView.frame.size.width,
                                                self.dealsDetailView.frame.size.height);
        [self performSelector:@selector(removeDealsDetailView) withObject:nil afterDelay:0.6];
    }];
    
    [self loadOrCheckMarkersFromService];
}

- (IBAction)aboutButtonClicked:(id)sender
{
    [self.view addSubview:self.aboutView];
    self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                      self.aboutView.superview.frame.size.height,
                                      self.aboutView.frame.size.width,
                                      self.aboutView.frame.size.height);
    
    [UIView animateWithDuration:0.5 animations:^{
        self.aboutView.center = self.aboutView.superview.center;
    }];
}

- (IBAction)continueAboutClicked:(id)sender
{
    [UIView animateWithDuration:0.6 animations:^{
        self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                          self.aboutView.superview.frame.size.height,
                                          self.aboutView.frame.size.width,
                                          self.aboutView.frame.size.height);
        [self performSelector:@selector(removeAboutView) withObject:nil afterDelay:0.6];
    }];
}

- (IBAction)termsOfUseButtonClicked:(id)sender
{
    [self.view addSubview:self.termsOfUseView];
    self.termsOfUseView.frame = CGRectMake(self.termsOfUseView.frame.origin.x,
                                           self.termsOfUseView.superview.frame.size.height,
                                           self.termsOfUseView.frame.size.width,
                                           self.termsOfUseView.frame.size.height);
    
    [UIView animateWithDuration:0.5 animations:^{
        self.termsOfUseView.center = self.termsOfUseView.superview.center;
    }];
}

- (IBAction)continueTermsOfUseClicked:(id)sender
{
    [UIView animateWithDuration:0.6 animations:^{
        self.termsOfUseView.frame = CGRectMake(self.termsOfUseView.frame.origin.x,
                                               self.termsOfUseView.superview.frame.size.height,
                                               self.termsOfUseView.frame.size.width,
                                               self.termsOfUseView.frame.size.height);
        [self performSelector:@selector(removeTermsOfUseView) withObject:nil afterDelay:0.6];
    }];
}

- (IBAction)continueTutorialClicked:(id)sender
{
    //    [self hideCharacterSystem];
        self.helpMessageLabel.hidden = NO;
    //    currentMarkerId = 0;
    [UIView animateWithDuration:0.6 animations:^{
        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                             self.tutorialView.superview.frame.size.height,
                                             self.tutorialView.frame.size.width,
                                             self.tutorialView.frame.size.height);
        [self performSelector:@selector(removeTutorialView) withObject:nil afterDelay:0.6];
    }];
    
    [self loadOrCheckMarkersFromService];
}

- (void)optionViewToggleButtonClicked:(id)sender {
    
    self.helpMessageLabel.hidden = self.optionView.hidden;
    if(currentMarkerId>=0) self.helpMessageLabel.hidden = YES;
    if(currentMarkerId>0) {
        [self loadCharacter:currentMarkerId];
    }
}

- (void)optionButtonClicked:(id)sender {
    
    UIButton *btn = (UIButton *)sender;
    int optionIndex = [btn tag] - 100;
    
    [self loadCharacter:currentMarkerId];
    [self optionViewToggleButtonClicked:nil];
}

- (IBAction)clearOptions:(id)sender     {
    [self hideCharacterSystem];
       NSLog(@"Vikram: inside clearoptions");
    [self reSetImageMarkersAndVisualsForDownload];
 
}

- (void)testButtonClicked:(id)sender {
    NSLog(@"testButtonClicked...");
    [self triggerMarker:1];
}

#pragma mark - About & Tutorial Views.
- (void)removeAboutView
{
    [self.aboutView removeFromSuperview];
}

- (void)removeTermsOfUseView
{
    [self.termsOfUseView removeFromSuperview];
}
- (void)removeCreditAppView
{
    [self.creditAppView removeFromSuperview];
}
-(void)removeDealsView
{
    [self.dealsView removeFromSuperview];
}
-(void)removeDealsDetailView
{
    [self.dealsDetailView removeFromSuperview];
}
- (void)removeTutorialView
{
    [self.aboutView removeFromSuperview];
}

- (IBAction)seemoreLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.seemoreinteractive.com"]];
}

- (IBAction)metaioLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.metaio.com/imprint/"]];
}
- (IBAction)miramaxLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.ultimatesoftware.com"]];
}
- (IBAction)fbMiramaxLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.facebook.com/Miramax"]];
}
- (IBAction)twitterLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://twitter.com/Miramax"]];
}

#pragma mark - Supporting Methods
- (void) initGestureView {
    UIPinchGestureRecognizer *pinchGesture = [[UIPinchGestureRecognizer alloc] initWithTarget:self action:@selector(handlePinch:)];
    [self.gestureView addGestureRecognizer:pinchGesture];
    UIPanGestureRecognizer *panRecognizer = [[UIPanGestureRecognizer alloc] initWithTarget:self action:@selector(move:)];
	panRecognizer.minimumNumberOfTouches = 1;
	panRecognizer.maximumNumberOfTouches = 1;
	[self.gestureView addGestureRecognizer:panRecognizer];
}

#pragma mark - gesture
-(void)handlePinch:(UIPinchGestureRecognizer*)sender {
    if(TIMER_ENABLE==NO || (TIMER_ENABLE==YES && remainingSeconds>0)) {
        CGFloat scale = [sender scale];
        if (sender.state == UIGestureRecognizerStateEnded) {
            currentScale = currentScale*scale;
            if(currentScale>2.0f) currentScale = 2.0f;
        } else {
            [self renderShape:scale*currentScale centerPoint:currentShapeCenterPoint];
            self.shapeSlider.value = scale*currentScale*originalShapeSize.width/self.viewFrame.size.width*100;
        }
    }
}

-(void)move:(id)sender {
    if(TIMER_ENABLE==NO || (TIMER_ENABLE==YES && remainingSeconds>0)) {
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

- (void) renderShape:(CGFloat)scale centerPoint:(CGPoint)centerPoint{
    if(scale>2.0f) scale = 2.0f;
    //    if(0<centerPoint.x && centerPoint.x<self.viewFrame.size.width && 0<centerPoint.y && centerPoint.y<self.viewFrame.size.height) {
    self.shapeImageView.frame = CGRectMake(centerPoint.x-originalShapeSize.width*scale/2,
                                           centerPoint.y-originalShapeSize.height*scale/2,
                                           originalShapeSize.width*scale,
                                           originalShapeSize.height*scale
                                           );
    //    }
}

#pragma mark - capture
- (void) pushShareView:(UIImage *)image {
    if([[SessionManager getInstance] iPhoneContext]) {
        ShareViewController_iPhone *viewController = [[ShareViewController_iPhone alloc] init];
        viewController.image = image;
        [self.navigationController pushViewController:viewController animated:YES];
    }else{
        ShareViewController_iPad *viewController = [[ShareViewController_iPad alloc] init];
        viewController.image = image;
        [self.navigationController pushViewController:viewController animated:YES];
    }
}

-(UIImage *) mergeCharacterWith:(UIImage *)image {
    
    CGFloat scale = [UIScreen mainScreen].scale;
    CGRect image2Rect = self.shapeImageView.frame;
    
    float shapeImageOffsetX = 0.0;
    if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone) {
        shapeImageOffsetX = 20.0;
    }
    
    image2Rect = CGRectMake((shapeImageOffsetX+image2Rect.origin.x)*scale, image2Rect.origin.y*scale, image2Rect.size.width*scale, image2Rect.size.height*scale);
    return [self mergeImages:image image1Size:image.size image2:self.shapeImageView.image image2Rect:image2Rect image3:[UIImage imageNamed:@"watermark.png"]];
}

- (UIImage *) mergeImages:(UIImage *)image1 image1Size:(CGSize)image1Size image2:(UIImage *)image2 image2Rect:(CGRect)image2Rect image3:(UIImage *)image3{
    UIGraphicsBeginImageContext(image1Size);
    [image1 drawInRect:CGRectMake(0,0,image1Size.width,image1Size.height)];
    [image2 drawInRect:image2Rect];
    //    [image3 drawInRect:CGRectMake(50,image1Size.height-image3.size.height-50,image3.size.width,image3.size.height)];
    UIImage *mergedImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return mergedImage;
}

#pragma mark -
#pragma mark Character Timer Logic
- (void) scheduleCharacterTimer	{
    [self unscheduleCharacterTimer];
	self.characterTimer = [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(updateElapsedTime:) userInfo:nil repeats:YES];
    remainingSeconds = TIME_TO_REMOVE_CHARACTER;
}

- (void) unscheduleCharacterTimer    {
    if (self.characterTimer != nil) {
        [self.characterTimer invalidate];
        self.characterTimer = nil;
    }
}

-(void) triggerAfterFinishCharacterTimer    {
    [self unscheduleCharacterTimer];
    [self hideCharacterSystem];
    self.helpMessageLabel.hidden = NO;
}

// Update the timer once a second.
- (void) updateElapsedTime:(NSTimer *)timer
{
    remainingSeconds--;
    if (remainingSeconds <= 0) {
        [self triggerAfterFinishCharacterTimer];
    }
}

#pragma mark - Metaio Delegate Callbacks
- (void) onTrackingEvent: (std::vector<metaio::Pose>) poses {
    if(isFirstTime) {
        isFirstTime = NO;
        return;
    }
    if(!poses.empty()) {
        metaio::Pose pose = poses.front();
        int cosId = pose.cosID;
        //NSLog(@"Vikram: quality - %f",pose.quality);
        if (pose.quality > 0.5) {
            self.helpMessageLabel.hidden = YES;
            //NSLog(@"Vikram: CosID: %d",cosId);
            [self triggerMarker:cosId];
        }else{
            //NSLog(@"Vikram: animation video status %d",isModelAnimate);
            if (scanMode!=1){
                if (cosId!=2){
                    [self clearOptions:nil];
                    self.helpMessageLabel.hidden = NO;
                }
            }
        }
    }
}


- (void) deleteMetaioDirectory {
    [[NSFileManager defaultManager] removeItemAtPath:[self getMetaioPath] error: nil];
}

- (void) createMetaioDirectory {
    [[NSFileManager defaultManager] createDirectoryAtPath:[self getMetaioPath] withIntermediateDirectories:YES attributes:nil error:nil];
}

-(NSString *) buildMarkerFile:(NSArray *) postersData {
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
    
    NSString *fileName = [path stringByAppendingPathComponent:@"markers.xml"];
    [contents writeToFile:fileName atomically:NO encoding:NSUTF8StringEncoding error:nil];
    return fileName;
}

-(NSString *) getMetaioPath {
    NSString *cacheDirectory = [NSSearchPathForDirectoriesInDomains
                                (NSCachesDirectory, NSUserDomainMask, YES) objectAtIndex:0];
    return [[NSString alloc] initWithFormat:@"%@%@", cacheDirectory, @"/Metaio/MarkerData"];
}

- (void) downloadPosterMarkers: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
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
- (void) downloadPosterMarkersVisual: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
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

- (void) downloadImagesOnPosterMarker:(Poster *) poster toPath:(NSString *) path {
    NSString *imageUrl;
    for (Marker *marker in [poster posterMarkers]) {
        imageUrl = [marker markerImage];
    }
    NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
    NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
    if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
        data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
    }
    NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
    [data writeToFile:cacheFilePath atomically:YES];
}

- (void) downloadImagesOnPosterVisual:(Poster *) poster toPath:(NSString *) path {
    NSString *imageUrl= [poster posterImage];
    NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
    NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
    if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
        data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
    }
    NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
    [[SessionManager getInstance]downloadedImageURL:cacheFilePath];
    [data writeToFile:cacheFilePath atomically:YES];
}

#pragma mark - Metaio
- (void)loadTrackingDataFromUserDefault {
    //NSLog(@"loadTrackingDataFromUserDefault");
    NSLog(@"Vikram: loadTrackingDataFromUserDefault %u",scanMode);
    
    [self loadMarkers:scanMode progressIndicator:nil];
//    [[SessionManager getInstance].posterVisual removeAllObjects];
//    if([self noMarkerAvailableLocally]) {
//        [self loadDefaultMarker];
//    } else {
//        NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
//        [SessionManager getInstance].posterVisual = data;
//        NSString *trackingDataFile = [[self getMetaioPath] stringByAppendingPathComponent:@"markers.xml"];
//        main_sync(^{
//            isFirstTime = YES;
//            unifeyeMobile->setTrackingData([trackingDataFile UTF8String]);
//        });
//    }
}

- (void) hideCharacterSystem {
    self.captureButton.hidden = YES;
    self.openOptionsButton.hidden = YES;
    self.shapeImageView.hidden = YES;
    self.videoButton.hidden = YES;
    // Hide Trailer View
    if (self.optionView.hidden==NO) {
        [self optionViewToggleButtonClicked:nil];
    }
    self.productLinkButton.hidden = YES;
    self.shapeSlider.hidden = YES;
    currentMarkerId=0;
    currentSelectedOption = nil;
}

- (void) unHideCharacterSystem {
    self.shapeImageView.hidden = NO;
    self.openOptionsButton.hidden = NO;
    self.captureButton.hidden = NO;
    self.videoButton.hidden = NO;
    self.productLinkButton.hidden = NO;
    self.shapeSlider.hidden = YES; // It's hidden for all the cases.
}

- (IBAction)activateImageScanAlgorithm:(id)sender   {
    NSLog(@"Vikram: activeimagescanalgorithm");
    [self loadMarkers:imageMarkers progressIndicator:nil];
    
}
- (IBAction)activateTagScanAlgorithm:(id)sender   {
    NSLog(@"Vikram: activatetagscanalgorithm");
    [self loadMarkers:metaioMarkers progressIndicator:nil];
}


- (void) loadCharacter:(int)markerId {
    self.helpMessageLabel.hidden = YES;
    NSLog(@"beginning of loadcharacter function %d scanmode: %d",markerId, scanMode);
    
    self.helpMessageLabel.hidden = YES;
    self.shapeSlider.value = 60;
    int optionIndex = markerId-1;
    
    switch (scanMode) {
        case imageMarkers: {
            NSLog(@"Vikram: Inside imagemarkers case of loadcharacter");
            //NSLog(@"currentMarkerId = %d", currentMarkerId);
            if(currentMarkerId>0) {
                //NSLog(@"before markerinfo");
                NSDictionary *markerInfo = [[SessionManager getInstance] markerInfo:1];
                if(markerInfo) {
                    //NSLog(@"after markerinfo if condition");
                    
                    currentSelectedOption = [[Option alloc] init];
                    
                    //Get Trigger type and Check what type of trigger
                    NSArray *triggerTypes = [markerInfo objectForKey:@"Type"];
                    NSString *getTriggerType = [triggerTypes objectAtIndex:optionIndex];
                    currentSelectedOption.getTriggerType = getTriggerType;
                    
                    NSLog(@"Vikram: Before triggertype %@",getTriggerType);
                    if ([getTriggerType isEqualToString:@"VIDEO"]){
                        // Get Video URL
                        NSArray *videos = [markerInfo objectForKey:@"VideoURL"];
                        NSString *movieURLString = [videos objectAtIndex:optionIndex];
                        currentSelectedOption.videoURL = movieURLString;
                        [self playMovie:currentSelectedOption.videoURL];
                    }else if ([getTriggerType isEqualToString:@"3DMODEL"]){
                        [self load3DModelStatic:markerId];
                    }else if ([getTriggerType isEqualToString:@"WEBURL"]){
                        [self displayCreditAppView:markerId];
                    }else if ([getTriggerType isEqualToString:@"OFFER"]){
                        //[self displayTutorialView];
                        [self displayDealView];
                        //[self displayCreditAppView:nil];
                    }else{
                        NSLog(@"Trigger type is not valid");
                    }
                }
            }
            break;
        }
        case metaioMarkers: {
            NSLog(@"Vikram: Inside metaiomarkers case of loadcharacter");
            [self loadMetaioModel:markerId withTriggerId:markerId];
            break;
        }
        case barCodes:
            break;
    }
    
}



- (void) loadWebURL:(NSString *)WebURL{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString: WebURL]];
}
- (void) load3DModelStatic:(int)markerId {
    NSLog(@"Vikram: load3DModelStatic %d",markerId);
    switch (markerId) {
        case 1: {
            [self loadObjModel:[[NSArray alloc] initWithObjects: @"comb_blueRing_base", @"comb_blueRing_diamonds",@"comb_blueRing_frame",@"comb_blueRing_jewel",@"comb_silverRing_base",@"comb_silverRing_diamonds",@"comb_silverRing_frame", nil] scale:4.0 cos:markerId];
            break;
        }
        case 9: {
            [self loadMd2Model:[[NSArray alloc] initWithObjects: @"card05", @"card06", @"wall02",@"ribbon",@"wall03",@"card01",@"card02",@"bottom",@"lid",@"card03",@"wall04",@"wall01",@"card04",@"cardMain3", nil] scale:85.0 rotateX:1.57 rotateY:0.0 rotateZ:1.57 cos:markerId];
            break;

        }
        case 11:
            // Load 3D Blue Ring
            [self loadObjModel:[[NSArray alloc] initWithObjects:@"blueRing_base_0208", @"blueRing_diamonds_0208", @"blueRing_frame_0208", @"blueRing_jewel_0208", nil] scale:10.0 cos:11];
        break;
    }
}

- (void) loadWebURLStatic:(int)markerId {
    
    switch (markerId) {
        case 2: {
            break;
        }
        case 3: {
            
            break;
        }
        case 4:
            
            break;
        case 5:
            
            break;
        case 8:
            
            break;
        case 12:
            
            break;
            
    }
}
- (void) reSetImageMarkersAndVisualsForDownload {
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterVisualPath"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterData"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"lastDateKey"];
    [self loadTrackingDataFromUserDefault];
    self.helpMessageLabel.hidden = NO;
}

- (void)loadMarkers:(ScanMode)newScanMode progressIndicator:(void(^)(int current, int count))progressIndicator {
    scanMode = newScanMode;
    NSLog(@"Vikram: loadMarkers: %u",scanMode);
    
    switch (scanMode) {
        case imageMarkers: {
            NSLog(@"Vikram: Inside imagemarkers case");
            [self loadDefaultMarker];
            break;
        }
        case metaioMarkers: {
            NSLog(@"Vikram: Inside metaiomarkers case");
            [self loadMetaioMarkers];
            break;
        }
        case barCodes:
            break;
    }

}
#pragma mark - lifecycle
- (BOOL) noMarkerAvailableLocally {
	// Check any marker available locally in user defaults.
    BOOL isNoMarkerAvailable;
    NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
    if ([data count])
        isNoMarkerAvailable = NO;
    else
        isNoMarkerAvailable = YES;
    return isNoMarkerAvailable;
}

- (void) loadDefaultMarker {
	// Load default Hobbit marker in metaio
    //NSLog(@"loadDefaultMarker");
    NSString *trackingDefaultFile = [[NSBundle mainBundle] pathForResource:@"TrackingDefault_Marker" ofType:@"xml" inDirectory:@"assets"];
    NSString *data = [[NSString alloc] initWithUTF8String:[trackingDefaultFile UTF8String]];
    NSLog(@"Tracking Image Data File = %@",data);
    main_sync(^{
        isFirstTime = YES;
        [self unloadModels];
        unifeyeMobile->setTrackingData([trackingDefaultFile UTF8String]);
    });
	[self updateMarkersFromServiceFlag:YES];
}

- (void) loadMetaioMarkers {
    NSString *trackingMetaioFile = [[NSBundle mainBundle] pathForResource:@"TrackingData_Marker" ofType:@"xml" inDirectory:@"assets"];
    NSString *data = [[NSString alloc] initWithUTF8String:[trackingMetaioFile UTF8String]];
    NSLog(@"Tracking Metaio Data File = %@",data);
    main_sync(^{
        isFirstTime = YES;
        [self unloadModels];
        unifeyeMobile->setTrackingData([trackingMetaioFile UTF8String]);
    });
    [self updateMarkersFromServiceFlag:YES];
    
}
- (void)unloadModels {
    std::vector<metaio::IUnifeyeMobileGeometry *> models = unifeyeMobile->getLoadedGeometries();
    for (std::vector<metaio::IUnifeyeMobileGeometry *>::const_iterator i = models.begin(); i != models.end(); i++) {
        unifeyeMobile->unloadGeometry(*i);
    }

}
- (metaio::IUnifeyeMobileGeometry *)loadModel:(NSString *)name type:(NSString *)type scale:(metaio::Vector3d)scale translation:(metaio::Vector3d)translation cos:(int)cos {
    NSString *modelFile;
    if (cos==1){
        modelFile = [[NSBundle mainBundle] pathForResource:name ofType:type inDirectory:@"assets/3DModel/CombinedRing"];
      
        
    } else if(cos==9){
        isModelAnimate = YES;
        modelFile = [[NSBundle mainBundle] pathForResource:name ofType:type inDirectory:@"assets/3DModel/giftbox"];
        NSString *modelTexture;
        modelTextureURL = NULL;
        if ([name isEqualToString:@"card05"] || [name isEqualToString:@"card06"] || [name isEqualToString:@"card01"] || [name isEqualToString:@"card02"] || [name isEqualToString:@"card03"] || [name isEqualToString:@"card04"] || [name isEqualToString:@"cardMain3"]){
            modelTexture = @"cards";
        }else if ([name isEqualToString:@"wall02"] || [name isEqualToString:@"wall03"] || [name isEqualToString:@"wall04"] || [name isEqualToString:@"wall01"]){
            modelTexture = @"greenBoxWall";
        }else if ([name isEqualToString:@"ribbon"] || [name isEqualToString:@"lid"]){
            modelTexture = @"greenBoxLid_newRibbon";
        }else if ([name isEqualToString:@"bottom"]){
            modelTexture = @"greenBoxBottom";
        }
        modelTextureURL = [[NSBundle mainBundle] pathForResource:modelTexture ofType:@"png" inDirectory:@"assets/3DModel/giftbox"];
        
    }else{
        modelFile = [[NSBundle mainBundle] pathForResource:name ofType:type inDirectory:@"assets/3DModel"];
    }
      //NSLog(@"Vikram: modelfile : %@ name: %@",modelFile, name);
    return [self loadModelFromPath:modelFile scale:scale translation:translation cos:cos];
}
- (metaio::IUnifeyeMobileGeometry *)loadModelFromPath:(NSString *)filepath scale:(metaio::Vector3d)scale translation:(metaio::Vector3d)translation cos:(int)cos {
    metaio::IUnifeyeMobileGeometry *model = NULL;
	if (filepath) {
        model = unifeyeMobile->loadGeometry([filepath UTF8String]);
        if (model) {
            model->setMoveScale(scale);
            model->setMoveTranslation(translation);
            model->setCos(cos);
            if (modelTextureURL){
                model->setTexture([modelTextureURL UTF8String]);
                if (isModelAnimate){
                model->setAnimationSpeed(96.0);
                double delayInSeconds = 0.5;
                dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, delayInSeconds * NSEC_PER_SEC);
                dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
                    std::vector<metaio::IUnifeyeMobileGeometry *> mods = unifeyeMobile->getLoadedGeometries();
                    for (std::vector<metaio::IUnifeyeMobileGeometry *>::const_iterator i = mods.begin(); i != mods.end(); i++) {
                        metaio::IUnifeyeMobileGeometry *model = *i;
                        std::vector<std::string> animations = model->getAnimationNames();
                        if (animations.size() > 0) {
                                model->startAnimation(*animations.begin(), false);
                        }
                               
                    }
                });
                    
                }
            }
            //NSLog(@"Vikram: modelTextureURL: %@",modelTextureURL);
            modelTextureURL = NULL;
        }
    }
    return model;
}
- (void) onAnimationEnd: (metaio::IUnifeyeMobileGeometry*) geometry  andName:(NSString*) animationName
{
    if (geometry->getName() == "cardMain3") {
        NSLog(@"Vikram: End of Animation of cardMain3");
        isModelAnimate = NO;
    }
 //[self displayOffer];
[self displayTutorialView];
}
- (void) loadObjModel:(NSArray *)models scale:(double)scale cos:(int)cos {
    
    for (NSString *model in models) {
        NSLog(@"Vikram: loadobjmodel: %@",model);
        metaio::IUnifeyeMobileGeometry *object = [self loadModel:model type:@"obj" scale:metaio::Vector3d(scale, scale, scale) translation:metaio::Vector3d(0.0, 0.0, 0.0) cos:cos];
        object->setMoveRotation(metaio::Vector3d(M_PI_2, 0.0, 0.0), true);
        object->setName([model UTF8String]);
     }
}

- (void) loadMd2Model:(NSArray *)models scale:(double)scale rotateX:(double)rX rotateY:(double)rY rotateZ:(double)rZ cos:(int)cos {
    for (NSString *model in models) {
        NSLog(@"Vikram: loadmd2model %@",model);
        metaio::IUnifeyeMobileGeometry *object = [self loadModel:model type:@"md2" scale:metaio::Vector3d(scale, scale, scale) translation:metaio::Vector3d(0.0, 0.0, 0.0) cos:cos];
        object->setMoveRotation(metaio::Vector3d(rX, rY, rZ), true);
        object->setName([model UTF8String]);
    }
}
//- (metaio::IUnifeyeMobileGeometry *)loadModel:(NSString *)name type:(NSString *)type scale:(metaio::Vector3d)scale translation:(metaio::Vector3d)translation cos:(int)cos {
//    NSString *modelFile = [[NSBundle mainBundle] pathForResource:name ofType:type inDirectory:@"Assets"];
//    return [self loadModelFromPath:modelFile scale:scale translation:translation cos:cos];
//}
//
//- (metaio::IUnifeyeMobileGeometry *)loadModelFromPath:(NSString *)filepath scale:(metaio::Vector3d)scale translation:(metaio::Vector3d)translation cos:(int)cos {
//    metaio::IUnifeyeMobileGeometry *model = NULL;
//	if (filepath) {
//        model = unifeyeMobile->loadGeometry([filepath UTF8String]);
//        if (model) {
//            model->setMoveScale(scale);
//            model->setMoveTranslation(translation);
//            model->setCos(cos);
//        }
//    }
//    return model;
//}
- (void)loadMetaioModel:(int)cos withTriggerId:(int)triggerId  {
    

        NSLog(@"Metaio Marker COS = %i",cos);
        
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        dispatch_after(dispatch_time(DISPATCH_TIME_NOW, 0.01 * NSEC_PER_SEC), dispatch_get_main_queue(), ^{
            
            switch (cos) {
                    
                case 6: {
                    // Load 3D Bag
                    [self loadMd2Model:[[NSArray alloc] initWithObjects: @"strap", @"bag_1", @"bagInterior_1", nil] scale:1.0 rotateX:0 rotateY:0 rotateZ:0.0 cos:6];
                    break;
                }
                    
                case 8: {
                    // Load 3D Bag
                    [self loadMd2Model:[[NSArray alloc] initWithObjects: @"strap", @"bag_2", @"bagInterior_2", nil] scale:4.0 rotateX:1.57 rotateY:0 rotateZ:0.0 cos:8];
                    break;
                }
                    
                case 9: {
                    // Load 3D Bag
                    [self loadMd2Model:[[NSArray alloc] initWithObjects: @"strap", @"bag_3", @"bagInterior_3", nil] scale:4.0 rotateX:1.57 rotateY:0 rotateZ:0.0 cos:9];
                    break;
                }
                    
                case 4: {
                    // Load Jewel's Ring
                    [self loadMd2Model:[[NSArray alloc] initWithObjects: @"redRing", @"jewels", nil] scale:4.0 rotateX:M_PI_2 rotateY:0.0 rotateZ:0.0 cos:4];
                    break;
                }
                    
                case 5: {
                    // Load Walk
                   // NSString *moviePath = [[NSBundle mainBundle] pathForResource:@"walk" ofType:@"3g2" inDirectory:@"Assets"];
                   // [self loadVideoTextureOnPath:moviePath withModalPath:nil transparent:YES X:0.0 Y:0.0 Scale:100.0 cos:5];
                    break;
                }
                    
                case 1: {
                    // Load 3D Car
                    NSLog(@"Load 3D Car");
                    [self loadObjModel:[[NSArray alloc] initWithObjects: @"mksBlue", @"mksTires", nil] scale:3.0 cos:1];
                    break;
                }
                    
                case 7: {
                    // Load 3D Car
                    [self loadObjModel:[[NSArray alloc] initWithObjects: @"mksRed", @"mksTires", nil] scale:3.0 cos:7];
                    break;
                }
                    
                case 2: {
                    // Load 3D Car
                    [self loadObjModel:[[NSArray alloc] initWithObjects: @"mksGreen", @"mksTires", nil] scale:3.0 cos:2];
                    break;
                }
                    
                case 3: {
                    // Load 3D Car
                    [self loadObjModel:[[NSArray alloc] initWithObjects: @"mksBase", @"mksTires", nil] scale:3.0 cos:3];
                    break;
                }
                    
                case 10: {
                    // Load 3D Car
                    [self loadObjModel:[[NSArray alloc] initWithObjects: @"body", @"mirrors", @"wipers", @"tires", nil] scale:2.0 cos:10];
                    break;
                }
                    
                case 11: {
                    // Load 3D Blue Ring
                   [self loadObjModel:[[NSArray alloc] initWithObjects:@"blueRing_base_0208", @"blueRing_diamonds_0208", @"blueRing_frame_0208", @"blueRing_jewel_0208", nil] scale:3.0 cos:11];
                    break;
                }
                    
                case 12: {
                    // Load 3D Silver Ring
                    [self loadObjModel:[[NSArray alloc] initWithObjects:@"silverRing_base_cutaway", @"silverRing_diamonds_cutaway", @"silverRing_frame_cutaway", nil] scale:3.0 cos:12];
                    break;
                }
                    
                case 13: {
                    // Load Zales Box Cards
                    [self loadObjModel:[[NSArray alloc] initWithObjects:@"zalesRing_base_cut", @"zalesRing_diamonds_cut", @"zalesRing_frame_cut", @"zalesRing_jewel_cut", @"zalesRing_lattice_cut", nil] scale:4.0 cos:13];
                    break;
                }
                    
                    
                case 14: {
                    // Load 3D Blue Ring
                    [self loadObjModel:[[NSArray alloc] initWithObjects:@"blueRing_base_0208", @"blueRing_diamonds_0208", @"blueRing_frame_0208", @"blueRing_jewel_0208", nil] scale:3.0 cos:14];
                    break;
                }
                    
                case 15: {
                    // Load 3D Silver Ring
                    [self loadObjModel:[[NSArray alloc] initWithObjects:@"silverRing_base_cutaway", @"silverRing_diamonds_cutaway", @"silverRing_frame_cutaway", nil] scale:3.0 cos:15];
                    break;
                }
                    
                case 16: {
                    // Load 3D Ruby Ring
                    [self loadObjModel:[[NSArray alloc] initWithObjects:@"redRing_base_cutaway", @"redRing_diamonds", @"redRing_frame", @"redRing_jewel", nil] scale:3.3 cos:16];
                    break;
                }
                    
                case 17: {
                    // Load 3D Watch
                   [self loadObjModel:[[NSArray alloc] initWithObjects:@"watchFinal", nil] scale:6.25 cos:17];
                    break;
                }
                    
                default:    {
                    // Load Blue Jewel
                    //                  [self loadObjModel:[[NSArray alloc] initWithObjects:@"blueJewel", @"diamonds", @"ringBase", @"ringMiddle", nil] scale:4.0 cos:5];
                    
                    // Load Silver Jewel
                    //                  [self loadObjModel:[[NSArray alloc] initWithObjects:@"silverRing", @"silverRing_Diamonds", @"silverRing_Frame", nil] scale:4.0 cos:6];
                    break;
                }
            }
            
            [MBProgressHUD hideAllHUDsForView:self.navigationController.view animated:YES];
        });
  //  }
}

- (BOOL) isLoadMarkersFromService {
	// Check User Default variable "updateMarkersFromService" YES or NO
    return [[NSUserDefaults standardUserDefaults] boolForKey:@"updateMarkersFromService"];
}

- (void) updateMarkersFromServiceFlag: (BOOL)isNeedToUpdate {
	// Set "updateMarkersFromService" as per the given isNeedToUpdate flag
    if(!DYNAMIC_MARKERS)
        isNeedToUpdate = NO; // For static version, don't update marker from service. Ever.
    [[NSUserDefaults standardUserDefaults] setBool:isNeedToUpdate forKey:@"updateMarkersFromService"];
}

- (BOOL) isInternetAvailable {
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

-(NSArray*) retrieveServiceMarkers {
	// Return markers array by calling the service
    return [[SessionManager getInstance] getPosterMarkerData];;
}

- (void) loadMarkers {
    NSLog(@"Vikram: Loadmarkers no parameters");
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

- (BOOL) isTimeForCheckLatestMarkersFromService {
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

- (void) updatedTimeForCheckLatestMarkersFromService {
    [[NSUserDefaults standardUserDefaults] setObject:[NSDate date] forKey:@"lastDateKey"];
}

- (void) checkLatestMarkersFromService {
	if([self isTimeForCheckLatestMarkersFromService] && [self isInternetAvailable] && DYNAMIC_MARKERS) {
		[self retrieveServiceMarkers];
        NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
        NSArray *userDefaultPosterArray = (NSArray*)[NSKeyedUnarchiver unarchiveObjectWithData:
                                                     (NSData*)[[NSUserDefaults standardUserDefaults] dataForKey:@"PosterData"]];
        [self updateMarkersFromServiceFlag:![posterArray isEqualToArray:userDefaultPosterArray]];
        [self updatedTimeForCheckLatestMarkersFromService];
    }
}

#pragma mark - lifecycle
- (void)displayTutorialView {
     if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"tutorialFlag"] length]==0) {
         [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"tutorialFlag"];
         [self.view addSubview:self.tutorialView];
         //myPickerView.delegate = self;
         //myPickerView.showsSelectionIndicator = YES;
       // [self.navigationController pushViewController:myPickerView animated:YES];
     }
//    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"tutorialFlag"] length]==0) {
//
//        CGRect screenBound = [[UIScreen mainScreen] bounds];
//        CGSize screenSize = screenBound.size;
//        CGFloat screenWidth = screenSize.width;
//        CGFloat screenHeight = screenSize.height;
//        
//        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"tutorialFlag"];
//        [self.view addSubview:self.tutorialView];
//        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
//                                             self.tutorialView.superview.frame.size.height,
//                                             self.tutorialView.frame.size.width,
//                                             self.tutorialView.frame.size.height);
//        
//        [UIView animateWithDuration:0.5 animations:^{
//            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
//                                                 0,
//                                                 self.tutorialView.frame.size.width,
//                                                 self.tutorialView.frame.size.height);
//        }];
//        
//        
//        CGRect offerImageFrame = [offerImage frame];
//        offerImageFrame.origin.y = (screenHeight*0.3);  // change the location
//        [offerImage setFrame:offerImageFrame];
//        
//        CGRect offerAddToOffersFrame = [offerAddToOffers frame];
//        offerAddToOffersFrame.origin.y = (screenHeight*0.6);  // change the location
//        [offerAddToOffers setFrame:offerAddToOffersFrame];
//        
//        CGRect offerNoThanksFrame = [offerNoThanks frame];
//        offerNoThanksFrame.origin.y = (screenHeight*0.6);  // change the location
//        [offerNoThanks setFrame:offerNoThanksFrame];
//        
//
//        
//         self.helpMessageLabel.hidden = YES;
//    } else {
//        self.helpMessageLabel.hidden = NO;
//    }
}
- (void)displayDealView{
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"dealFlag"] length]==0) {
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"dealFlag"];
        [self.view addSubview:self.dealsView];
        self.dealsView.frame = CGRectMake(self.dealsView.frame.origin.x,
                                              self.dealsView.superview.frame.size.height,
                                              self.dealsView.frame.size.width,
                                              self.dealsView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.dealsView.frame = CGRectMake(self.dealsView.frame.origin.x,
                                                  0,
                                                  self.dealsView.frame.size.width,
                                                  self.dealsView.frame.size.height);
        }];
      

        startRow = 6;
        endRow = 15;
        diceNow = [self genRandom:startRow Ending:endRow];
        diceNowForMax = [self genRandom:(maxScrollRow-3) Ending:(maxScrollRow+2)];
        
        NSLog(@"Vikram: generate random: %d",diceNow);
        [self.view addSubview:self.dealsView];
         NSIndexPath *indexPathInitial = [NSIndexPath indexPathForRow:lockedRow inSection:0];
        [self tableView:_tableView didDeselectRowAtIndexPath:indexPathInitial];
         [_tableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:diceNowForMax inSection:0] atScrollPosition:UITableViewScrollPositionMiddle animated:NO];
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, 0.01 * NSEC_PER_SEC), dispatch_get_main_queue(), ^{
               
                //[_tableView selectRowAtIndexPath:indexPathInitial animated:NO scrollPosition:UITableViewScrollPositionNone];
                  
                [self scrollDirection:diceNowForMax Ending:diceNow direction:@"up"];
            });
     
               
        if (screenHeight==568){
            CGRect offerImageFrameHeight = [offerImage frame];
            offerImageFrameHeight.size.height = 568;  // change the location
            offerImageFrameHeight.origin.y = 0;
            [offerImage setFrame:offerImageFrameHeight];
        }
        
        self.helpMessageLabel.hidden = YES;
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}
#pragma mark - lifecycle
- (void)displayCreditAppView:(int)markerid{
    NSLog(@"Vikram: marker id in displayCreditAppView : %d",markerid);
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"creditFlag"] length]==0) {
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"creditFlag"];
        [self.view addSubview:self.creditAppView];
        self.creditAppView.frame = CGRectMake(self.creditAppView.frame.origin.x,
                                             self.creditAppView.superview.frame.size.height,
                                             self.creditAppView.frame.size.width,
                                             self.creditAppView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.creditAppView.frame = CGRectMake(self.creditAppView.frame.origin.x,
                                                 0,
                                                 self.creditAppView.frame.size.width,
                                                 self.creditAppView.frame.size.height);
        }];
        
        UIImage *creditBackgroundImage;
        
        if (markerid==9){ //Image for Kelloggs Special marker
            creditBackgroundImage = [UIImage imageNamed:@"nutrician_facts.png"];
            [offerImage setImage:creditBackgroundImage];
        }else  if (markerid==9001){
            creditBackgroundImage = [UIImage imageNamed:@"offer10_detail.png"];
            [offerImage setImage:creditBackgroundImage];
        }else{
            creditBackgroundImage = [UIImage imageNamed:@"creditapplication.png"];
            [offerImage setImage:creditBackgroundImage];
        }
        
        if (screenHeight==568){
            CGRect offerImageFrameHeight = [offerImage frame];
            offerImageFrameHeight.size.height = 568;  // change the location
            offerImageFrameHeight.origin.y = 0;
            [offerImage setFrame:offerImageFrameHeight];
        }
        
        self.helpMessageLabel.hidden = YES;
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}

- (void)displayDealsDetailView:(NSString *)offerName{
    NSLog(@"Vikram:  offerID : %@",offerName);
    
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"dealDetailsFlag"] length]==0) {
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"dealDetailsFlag"];
        [self.view addSubview:self.dealsDetailView];
        self.dealsDetailView.frame = CGRectMake(self.dealsDetailView.frame.origin.x,
                                              self.dealsDetailView.superview.frame.size.height,
                                              self.dealsDetailView.frame.size.width,
                                              self.dealsDetailView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.dealsDetailView.frame = CGRectMake(self.dealsDetailView.frame.origin.x,
                                                  0,
                                                  self.dealsDetailView.frame.size.width,
                                                  self.dealsDetailView.frame.size.height);
        }];
        
        UIImage *dealDetailsBackgroundImage;
        
        if ([offerName isEqualToString:@"offer5.png"]){ 
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offer5_detail.png"];
        }else  if ([offerName isEqualToString:@"offer10.png"]){
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offer10_detail.png"];
        }else  if ([offerName isEqualToString:@"offer20.png"]){
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offer20_detail.png"];
        }else  if ([offerName isEqualToString:@"offerbuy1get1.png"]){
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offerbuy1get1_detail.png"];
        }else  if([offerName isEqualToString:@"offerFree.png"]){
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offerFree_detail.png"];
        }else  if ([offerName isEqualToString:@"offershare.png"]){
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offershare_detail.png"];
        }else{
            dealDetailsBackgroundImage = [UIImage imageNamed:@"offerFree_detail.png"];
        }
        NSString *combinedImageName;
        if ((screenWidth==320) || (screenWidth==640)){
             combinedImageName = [NSString stringWithFormat: @"iphone_detail_%@",offerName];
            dealDetailsBackgroundImage = [UIImage imageNamed:combinedImageName];
        }
        NSLog(@"Vikram: dealDetailsBackgroundImage %@",combinedImageName);
         [DealOfferImage setImage:dealDetailsBackgroundImage];
        
//        playerButtonImage = [UIImage imageNamed:@"playPlayerButton.png"];
//        _dealsDetailBackButton = [UIButton buttonWithType:UIButtonTypeCustom];
//        _dealsDetailBackButton.frame = CGRectMake((screenWidth*0.3), (screenHeight*0.6), 27,27);
//        [_dealsDetailBackButton setImage:playerButtonImage forState:UIControlStateNormal];
//        [_dealsDetailBackButton addTarget:self action:@selector(playVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
        
        
        if (screenHeight==568){
            CGRect offerImageFrameHeight = [offerImage frame];
            offerImageFrameHeight.size.height = 568;  // change the location
            offerImageFrameHeight.origin.y = 0;
            [offerImage setFrame:offerImageFrameHeight];
        }
        
        self.helpMessageLabel.hidden = YES;
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}

- (void)displayWelcomeView{

    
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"welcomeFlag"] length]==0) {
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"welcomeFlag"];
        [self.view addSubview:self.welcomeView];
        self.welcomeView.frame = CGRectMake(self.welcomeView.frame.origin.x,
                                                self.welcomeView.superview.frame.size.height,
                                                self.welcomeView.frame.size.width,
                                                self.welcomeView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.welcomeView.frame = CGRectMake(self.welcomeView.frame.origin.x,
                                                    0,
                                                    self.welcomeView.frame.size.width,
                                                    self.welcomeView.frame.size.height);
        }];
        

        self.helpMessageLabel.hidden = YES;
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}


- (void) loadOrCheckMarkersFromService {
    NSLog(@"loadOrCheckMarkersFromService");
	if([self isLoadMarkersFromService]) {
        NSLog(@" -- loadMarkers");
		[self loadMarkers];
	} else {
        //NSLog(@" -- checkLatestMarkersFromService");
        [self performSelectorInBackground:@selector(checkLatestMarkersFromService) withObject:nil];
	}
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    myDataArray = [[NSArray alloc]initWithObjects:@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png",@"offer5.png",@"offer10.png",@"offerFree.png",@"offershare.png",@"offerFree.png",@"offerbuy1get1.png",@"offerFree.png",@"offer20.png", nil];
    
    // Set About Content View
    [self.aboutScrollView addSubview:self.aboutContentView];
    self.aboutScrollView.contentSize = CGSizeMake(self.aboutContentView.frame.size.width,
                                                  self.aboutContentView.frame.size.height);
    
    // Set Terms Of Use Content View
    [self.termsOfUseScrollView addSubview:self.termsOfUseContentViewOne];
    self.termsOfUseContentViewTwo.frame = CGRectMake(self.termsOfUseContentViewOne.frame.origin.x,
                                                     self.termsOfUseContentViewOne.frame.origin.x+self.termsOfUseContentViewOne.frame.size.height,
                                                     self.termsOfUseContentViewTwo.frame.size.width,
                                                     self.termsOfUseContentViewTwo.frame.size.height);
    [self.termsOfUseScrollView addSubview:self.termsOfUseContentViewTwo];
    self.termsOfUseScrollView.contentSize = CGSizeMake(self.termsOfUseContentViewOne.frame.size.width,
                                                       self.termsOfUseContentViewOne.frame.size.height+self.termsOfUseContentViewTwo.frame.size.height);
    
//    // Set Tutorial Content View
//    [self.tutorialScrollView addSubview:self.tutorialContentView];
//    self.tutorialScrollView.contentSize = CGSizeMake(self.tutorialContentView.frame.size.width,
//                                                     self.tutorialContentView.frame.size.height);
    
    // Set Button Effects
    //    self.optionOneButton.layer.cornerRadius = 5.0f;
    self.optionOneButton.layer.borderWidth = 1.0f;
    self.optionOneButton.layer.borderColor = [UIColor colorWithRed:0.803921
                                                             green:0.658823
                                                              blue:0.0
                                                             alpha:1.0].CGColor;
    self.optionOneButton.clipsToBounds = NO;
    self.optionOneButton.layer.shadowColor = [UIColor blackColor].CGColor;
    self.optionOneButton.layer.shadowOffset = CGSizeMake(0.0, 0.0);
    self.optionOneButton.layer.shadowRadius = 8.0f;
    self.optionOneButton.layer.shadowOpacity = 0.9;
    //    self.optionTwoButton.layer.cornerRadius = 5.0f;
    self.optionTwoButton.layer.borderWidth = 1.0f;
    self.optionTwoButton.layer.borderColor = [UIColor colorWithRed:0.803921
                                                             green:0.658823
                                                              blue:0.0
                                                             alpha:1.0].CGColor;
    self.optionTwoButton.clipsToBounds = NO;
    self.optionTwoButton.layer.shadowColor = [UIColor blackColor].CGColor;
    self.optionTwoButton.layer.shadowOffset = CGSizeMake(0.0, 0.0);
    self.optionTwoButton.layer.shadowRadius = 8.0f;
    self.optionTwoButton.layer.shadowOpacity = 0.9;
    
    self.navigationController.view.frame = self.viewFrame;
    self.optionView.hidden=YES;
    [self initGestureView];
    
    self.shapeSlider.transform =CGAffineTransformMakeRotation(M_PI * -0.5);
    self.animationFlag =true;
   // [self loadTrackingDataFromUserDefault];
    
    //[self displayTutorialView];
    //[self loadOrCheckMarkersFromService];
    [self hideCharacterSystem];
    [self.navigationController setNavigationBarHidden:YES];
    self.triggerButton.hidden = NO;
    
    if ([[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"]) {
        NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
        if ([data count]) {
            self.helpMessageLabel.hidden = NO;
        }
    }
    else    {
        self.helpMessageLabel.hidden = NO;
    }
}

- (void)checkProgressIndicator
{
    if (self.animationFlag) {
        hud= [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    }
}

- (void)viewDidAppear:(BOOL)animated   {
    [self stopPlayingVideo:nil];
    moviePlayer = nil;
   [self clearOptions:nil];
    [super viewDidAppear:animated];
    //[self displayDealView];
    [self displayWelcomeView];
    //[self displayDealsDetailView:@"offerFree.png"];
    
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    
    // Set Statusbar
    [[UIApplication sharedApplication] setStatusBarStyle:UIStatusBarStyleBlackOpaque animated:NO];
    [[UIApplication sharedApplication] setStatusBarHidden:NO withAnimation:UIStatusBarAnimationNone];
}

- (void)viewWillDisappear:(BOOL)animated {
    [super viewWillDisappear:animated];
    if(TIMER_ENABLE) {
        [self unscheduleCharacterTimer];
        remainingSeconds = 0;
    }
}

- (void)viewDidUnload
{
    offerImage = nil;
    offerNoThanks = nil;
    offerAddToOffers = nil;
    [self setCreditAppView:nil];
    DealOfferImage = nil;
    welcomeImage = nil;
    [self setWelcomeView:nil];
    welcomeSnapButton = nil;
    snapImageView = nil;
    [super viewDidUnload];

}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

- (void)setViewFrameCustom:(CGRect)viewFrame {
    CGRect newFrame = CGRectMake(viewFrame.origin.x, 0, viewFrame.size.width, viewFrame.size.height+viewFrame.origin.y);
    self.viewFrame = newFrame;
}
-(NSInteger)genRandom:(int)startPoint Ending:(int)endPoint
{
    float low_bound = startPoint;
    float high_bound = endPoint;
    float rndValue = (((float)arc4random()/0x100000000)*(high_bound-low_bound)+low_bound);
    int intRndValue = (int)(rndValue + 0.5);
    return intRndValue;
}
-(void)scrollDirection:(int)startPoint Ending:(int)endPoint direction:(NSString*)dir
{
    NSLog(@"Vikram: startPOint: %d",startPoint);
    NSLog(@"Vikram: endPoint: %d",endPoint);
    NSLog(@"Vikram: direction: %@",dir);
    if ([dir isEqualToString:@"down"])
    {
        for (int i=startPoint;i<endPoint;i++){
            [self animateDown:i direction:dir];
        }
    }else{
        for (int i=startPoint;i>endPoint;i--){
            [self animateDown:i direction:dir];
        }
    }
    rotateTimes++;
}
-(void)animateDown:(int)scrollToRow direction:(NSString*)dir
{
    NSLog(@"Vikram: scrolltorow: %d",scrollToRow);
    
    [UIView animateWithDuration: 6.0
                          delay:0.0
                        options: UIViewAnimationOptionBeginFromCurrentState
                     animations: ^{
                         [_tableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:scrollToRow inSection:0] atScrollPosition:UITableViewScrollPositionMiddle animated:NO];
                     }completion: ^(BOOL finished){
                         if (finished){
                              if ((scrollToRow-1) == diceNow){
                                
                                 NSLog(@"Vikram: it is done. Lock it at : %d",scrollToRow);
                                 lockedRow = scrollToRow;
                                 NSIndexPath *indexPath1 = [NSIndexPath indexPathForRow:scrollToRow inSection:0];
                                 //[myTableView selectRowAtIndexPath:indexPath1 animated:NO scrollPosition:UITableViewScrollPositionNone];
                                 [self tableView:_tableView didSelectRowAtIndexPath:indexPath1];
                                 [self removeDealsView];
                                NSString *cellImageName = [myDataArray objectAtIndex:indexPath1.row];
                                 sleep(2);
                                [self displayDealsDetailView:cellImageName];
                                 
                             }
                         }
                         
                     }
     ];
    
}
-(void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    NSLog(@"Vikram: scrollViewDidEndDecelerating");
}
-(void)animateNow:(int)scrollToRow
{
    [_tableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:scrollToRow inSection:0] atScrollPosition:UITableViewScrollPositionBottom animated:NO];
    
}

-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSLog(@"Vikram: numberOfRowsInSection");
    totRows = [myDataArray count]-1;
    return [myDataArray count];
}
-(UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    
    static NSString *cellIdentifier = @"cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
    if (cell == nil)
    {
        cell = [[UITableViewCell alloc]initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier];
    }
    NSString *cellImage = [myDataArray objectAtIndex:indexPath.row];
    //cell.textLabel.text = cellText;
    UIImageView *imageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:cellImage]];
    //[cell addSubview:imageView];
    
    CGRect offerDealImageFrame= [imageView frame];
    //NSLog(@"Vikram: screenwidth adjusted: %f",(screenWidth*0.94010416666667));
    //NSLog(@"Vikram: screenheight adjusted: %f",(screenHeight*0.3623046875));
    //NSLog(@"Vikram: origin x adjusted: %f",(screenWidth*0.02864583333333));
    
    offerDealImageFrame.size.width = (screenWidth*0.94401041666667);
    offerDealImageFrame.size.height = (screenHeight*0.3642578125);
    offerDealImageFrame.origin.x = (screenWidth*0.02604166666667);
    [imageView setFrame:offerDealImageFrame];
    [cell addSubview:imageView];

    
    //NSLog(@"Vikram: cellForRowAtIndexPath");
    return cell;
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {

    
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    CGFloat screenHeight = screenSize.height;
     
    CGFloat rowSize;
    rowSize = (screenHeight*0.3662109375);
    //NSLog(@"Vikram: height adjusted: %f",screenHeight);
    //NSLog(@"Vikram: rowSize adjusted: %f",rowSize);
    // If our cell is selected, return double height
    return  rowSize;
}
-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSLog(@"Vikram: didSelectRowAtIndexPath");
    //[_tableView selectRowAtIndexPath:indexPath animated:NO scrollPosition:UITableViewScrollPositionNone];
    _tableView.userInteractionEnabled = NO;
    _tableView.scrollEnabled = NO;
   
}

-(void)tableView:(UITableView *)tableView didDeselectRowAtIndexPath:(NSIndexPath *)indexPath
{
    //myLabel.text = [myDataArray objectAtIndex:indexPath.row];
    [_tableView deselectRowAtIndexPath:[_tableView indexPathForSelectedRow] animated:YES];
    //NSLog(@"Vikram: didDeselectRowAtIndexPath %@",indexPath);
    //NSLog(@"Vikram: didDeselectRowAtIndexPath by code %@",[_tableView indexPathForSelectedRow]);
     
}

-(void)tableView:(UITableView *)tableView didEndDisplayingCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSLog(@"Vikram: didEndDisplayingCell");
    //indexPath = [NSIndexPath indexPathForRow:20 inSection:0];
    //[tableView scrollToRowAtIndexPath:indexPath atScrollPosition:UITableViewScrollPositionMiddle animated:YES];
    // [myTableView setContentOffset:CGPointMake(0, CGFLOAT_MAX) animated:NO];
    //indexPath = [NSIndexPath indexPathForRow:11 inSection:0];
    //[tableView selectRowAtIndexPath:indexPath animated:YES scrollPosition:UITableViewScrollPositionMiddle];
}

- (IBAction)welcomeSnapButtonClicked:(id)sender {
    [self.welcomeView removeFromSuperview];
    
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    
    if (screenHeight==568){
        CGRect snapImageViewFrame = [snapImageView frame];
        snapImageViewFrame.size.height = 560;  // change the location
        snapImageViewFrame.origin.y = 0;
        [snapImageView setFrame:snapImageViewFrame];
    }
    
}
@end
