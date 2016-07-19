//
//  MiramaxViewController.m
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "MiramaxViewController.h"
#import "ShareViewController_iPhone.h"
#import "ShareViewController_iPad.h"
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
#import "MiramaxAppDelegate.h"
#import <CoreLocation/CoreLocation.h>

enum ScanMode {
    imageMarkers,
    metaioMarkers,
    barCodes
};
@interface MiramaxViewController () <FBLoginViewDelegate>

@property (strong, nonatomic) IBOutlet FBProfilePictureView *profilePic;
@property (strong, nonatomic) IBOutlet UIButton *buttonPostStatus;
@property (strong, nonatomic) IBOutlet UIButton *buttonPostPhoto;
@property (strong, nonatomic) IBOutlet UIButton *buttonPickFriends;
@property (strong, nonatomic) IBOutlet UIButton *buttonPickPlace;
@property (strong, nonatomic) IBOutlet UILabel *labelFirstName;
@property (strong, nonatomic) id<FBGraphUser> loggedInUser;

- (IBAction)postStatusUpdateClick:(UIButton *)sender;
- (IBAction)postPhotoClick:(UIButton *)sender;
- (IBAction)pickFriendsClick:(UIButton *)sender;
- (IBAction)pickPlaceClick:(UIButton *)sender;

- (void)showAlert:(NSString *)message
           result:(id)result
            error:(NSError *)error;


@end
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
@synthesize homeView = _homeView;
@synthesize webURLView = _webURLView;
@synthesize webURLWebView = _webURLWebView;
@synthesize welcomeMoreView = _welcomeMoreView;
@synthesize shareOptionButton = _shareOptionButton;
@synthesize videoShareView = _videoShareView;
@synthesize moviePlayer;

//Facebook related declaration
@synthesize buttonPostStatus = _buttonPostStatus;
@synthesize buttonPostPhoto = _buttonPostPhoto;
@synthesize buttonPickFriends = _buttonPickFriends;
@synthesize buttonPickPlace = _buttonPickPlace;
@synthesize labelFirstName = _labelFirstName;
@synthesize loggedInUser = _loggedInUser;
@synthesize profilePic = _profilePic;


static CGSize originalShapeSize;
static CGFloat currentScale = 1.0f;
static CGPoint currentShapeCenterPoint;
static CGPoint startPanGesturePoint;

UIAlertView *errorAlert;

CGRect screenBound;
CGSize screenSize;
CGFloat screenWidth;
CGFloat screenHeight;
CGFloat playerStopButtonPositionX;
CGFloat playerStopButtonPositionY;
CGFloat playerPauseButtonPositionX;
CGFloat playerPauseButtonPositionY;
CGFloat playerPlayButtonPositionX;
CGFloat playerPlayButtonPositionY;
CGFloat playerButtonSizeX;
CGFloat playerButtonSizeY;

static NSString *currentVideoUrl;
static int remainingSeconds;
static int currentMarkerId;
static BOOL isFirstTime = YES;
static BOOL didNotSwipe = TRUE;

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
-(void)playMovie: (NSString *)movieUrl
{
    self.shareOptionButton.hidden = NO;
    //NSString *filepath = [[NSBundle mainBundle] pathForResource:@"vid" ofType:@"mp4"];
    //NSString *videoUrl = @"https://s3.amazonaws.com/seemore-cms/cms/Videos/SeeMore_Brand_Video.mp4";
    
    NSURL * url = [[NSURL alloc]initWithString:movieUrl];
    currentVideoUrl = movieUrl;

    //NSLog(@"Vikram: Video URL: %@",currentVideoUrl);
    //Create our moviePlayer
    self.moviePlayer = [[MPMoviePlayerController alloc]init];
    [self.moviePlayer setContentURL:url];

    
    [self.moviePlayer prepareToPlay];
    
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    CGFloat playerWidth = (screenWidth*0.65);
    CGFloat playerHeight = (screenHeight*0.33);
    
    NSLog(@"Vikram: playerWidth: %f",playerWidth);
    NSLog(@"Vikram: playerHeight: %f",playerHeight);
    NSLog(@"Vikram: Playerpositionwidth: %f",(screenWidth*0.2));
    NSLog(@"Vikram: PlayerpositionHeight: %f",(screenHeight*0.3));
    
    CGRect videoHolderFrame = CGRectMake((screenWidth*0.2), (screenHeight*0.3), playerWidth, playerHeight);
    videoHolderView = [[UIView alloc] initWithFrame: videoHolderFrame];
    [videoHolderView setBackgroundColor: [UIColor clearColor]];
    [self.view addSubview: videoHolderView];
    
    NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
   [notificationCenter addObserver:self selector:@selector(onMediaPlayerDone:) name:MPMoviePlayerPlaybackDidFinishNotification object:self.moviePlayer];
  
    [self.moviePlayer setControlStyle:MPMovieControlStyleNone];
    //[self.moviePlayer setMovieSourceType:MPMovieSourceTypeStreaming];
    [self.moviePlayer.view setFrame: CGRectMake(0, 0, playerWidth, playerHeight)];
    [videoHolderView addSubview:self.moviePlayer.view];
    
    [self.moviePlayer play];
    
    playerPlayButtonPositionX = (screenWidth*0.3);
    playerPlayButtonPositionY = (screenHeight*0.6045);
    
    playerPauseButtonPositionX = (screenWidth*0.3);
    playerPauseButtonPositionY = (screenHeight*0.6045);
    
    playerStopButtonPositionX = (screenWidth*0.7);
    playerStopButtonPositionY = (screenHeight*0.6045);
    
    playerButtonSizeX = 25.0;
    playerButtonSizeY = 25.0;
    
    [self createStopButtonCustom];
    [self createPlayButtonCustom];
    
}
#pragma mark - MPMoviePlayerController
-(void)playMovie_remove: (NSString *)movieUrl
{
    moviePlayer = nil;
   if (movieUrl==nil || [movieUrl isEqualToString:@""]) {
        NSLog(@"Please Provide Movie URL or Local Path");
        return;
    }
    NSURL * url = [[NSURL alloc]initWithString:movieUrl];
    NSLog(@"Vikram: Video URL: %@",movieUrl);
    //Create our moviePlayer
    moviePlayer = [[MPMoviePlayerController alloc]init];
    [moviePlayer setContentURL:url];
    
    //[moviePlayer setInitialPlaybackTime:-1.f];
    
    screenBound = [[UIScreen mainScreen] bounds];
    screenSize = screenBound.size;
    screenWidth = screenSize.width;
    screenHeight = screenSize.height;
    CGFloat playerWidth = (screenWidth*0.65);
    CGFloat playerHeight = (screenHeight*0.33);
    
    NSLog(@"Vikram: playerWidth: %f",playerWidth);
    NSLog(@"Vikram: playerHeight: %f",playerHeight);
    NSLog(@"Vikram: Playerpositionwidth: %f",(screenWidth*0.2));
    NSLog(@"Vikram: PlayerpositionHeight: %f",(screenHeight*0.3));
    
    CGRect videoHolderFrame = CGRectMake((screenWidth*0.2), (screenHeight*0.3), playerWidth, playerHeight);
    videoHolderView = [[UIView alloc] initWithFrame: videoHolderFrame];
    [videoHolderView setBackgroundColor: [UIColor clearColor]];
    [self.view addSubview: videoHolderView];
    
    [moviePlayer.view setFrame:CGRectMake(0.0, 0.0, playerWidth, playerHeight)];
    NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
    
    [notificationCenter addObserver:self selector:@selector(onMediaPlayerDone:) name:MPMoviePlayerPlaybackDidFinishNotification object:moviePlayer];
    [notificationCenter addObserver:self selector:@selector(onNaturalSize:) name:MPMovieNaturalSizeAvailableNotification object:moviePlayer];
    [notificationCenter addObserver:self selector:@selector(onWillExitScreen:) name:MPMoviePlayerWillExitFullscreenNotification object:moviePlayer];
    [notificationCenter addObserver:self selector:@selector(moviePlayBackStateDidChange:) name:MPMoviePlayerPlaybackStateDidChangeNotification object:moviePlayer];
    
    [videoHolderView addSubview:moviePlayer.view];
    //Some additional customization
    moviePlayer.fullscreen = NO;
    moviePlayer.allowsAirPlay = YES;
    moviePlayer.shouldAutoplay = YES;
    moviePlayer.controlStyle = MPMovieControlStyleNone;
    //moviePlayer.movieSourceType = MPMovieSourceTypeStreaming;
    
    playerPlayButtonPositionX = (screenWidth*0.3);
    playerPlayButtonPositionY = (screenHeight*0.595);
    
    playerPauseButtonPositionX = (screenWidth*0.3);
    playerPauseButtonPositionY = (screenHeight*0.595);
    
    playerStopButtonPositionX = (screenWidth*0.7);
    playerStopButtonPositionY = (screenHeight*0.595);
    
    playerButtonSizeX = 30.0;
    playerButtonSizeY = 30.0;
    
    [self createStopButtonCustom];
    [self createPlayButtonCustom];

}
-(void)createPlayButtonCustom{
    [self removePauseButtonCustom];
    
    playerButtonImage = [UIImage imageNamed:@"playPlayerButton.png"];
    playerPlay = [UIButton buttonWithType:UIButtonTypeCustom];
    playerPlay.frame = CGRectMake(playerPlayButtonPositionX, playerPlayButtonPositionY, playerButtonSizeX,playerButtonSizeY);
    [playerPlay setImage:playerButtonImage forState:UIControlStateNormal];
    [playerPlay addTarget:self action:@selector(playVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:playerPlay];
}

-(void)createPauseButtonCustom{
    [self removePlayButtonCustom];
    
    pauseButtonImage = [UIImage imageNamed:@"pausePlayerButton.png"];
    playerPause = [UIButton buttonWithType:UIButtonTypeCustom];
    playerPause.frame = CGRectMake(playerPauseButtonPositionX, playerPauseButtonPositionY, playerButtonSizeX,playerButtonSizeY);
    [playerPause setImage:pauseButtonImage forState:UIControlStateNormal];
    [playerPause addTarget:self action:@selector(pauseVideoCustom:) forControlEvents:UIControlEventTouchUpInside];
    [self.view addSubview:playerPause];
}
-(void)createStopButtonCustom{
    [self removePauseButtonCustom];
    NSLog(@"Vikram: stop x: %f",playerStopButtonPositionX);
    NSLog(@"Vikram: stop Y: %f",playerStopButtonPositionY);
    stopButtonImage = [UIImage imageNamed:@"stopPlayerButton.png"];
    playerStop = [UIButton buttonWithType:UIButtonTypeCustom];
    playerStop.frame = CGRectMake(playerStopButtonPositionX, playerStopButtonPositionY, playerButtonSizeX,playerButtonSizeY);
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
    [moviePlayer stop];
    [self closeMediaPlayerController];
}
-(void)playVideoCustom:(UIButton *)sender
{
    [moviePlayer play];
    [self createPauseButtonCustom];
    [playerPlay removeFromSuperview];
    playerButtonImage = nil;
}
-(void)pauseVideoCustom:(UIButton *)sender
{
    [moviePlayer pause];
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
    moviePlayer.fullscreen = NO;
    [self closeMediaPlayerController];
}



- (void)closeMediaPlayerController{
     self.shareOptionButton.hidden = YES;
    [videoHolderView removeFromSuperview];
    [self.moviePlayer setFullscreen:NO];
    [self.moviePlayer stop];
    NSNotificationCenter *notificationCenter = [NSNotificationCenter defaultCenter];
    
    [notificationCenter removeObserver:self name:MPMoviePlayerPlaybackDidFinishNotification object:self.moviePlayer];
    
    [self.moviePlayer.view removeFromSuperview];
    
    self.moviePlayer = nil;
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
        
        [moviePlayer stop];
        moviePlayer = nil;
    }
}


#pragma mark - button clicked
- (IBAction)scanTestimonialsClicked:(id)sender {
    [self.homeView removeFromSuperview];
    [self loadAfterHomeView];
    [self displayTutorialView];
}
- (IBAction)moreButtonClicked:(id)sender {
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"moreViewFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"moreViewFlag"];
        //[self.view addSubview:self.welcomeMoreView];
        self.welcomeMoreView.frame = CGRectMake(self.welcomeMoreView.frame.origin.x,
                                         self.welcomeMoreView.superview.frame.size.height,
                                         self.welcomeMoreView.frame.size.width,
                                         self.welcomeMoreView.frame.size.height);
        [UIView animateWithDuration:0.0
                              delay:0
                            options:UIViewAnimationOptionTransitionNone
                         animations:^{
                             [self swipeleft];

                            // self.welcomeMoreView.frame = CGRectMake(self.welcomeMoreView.frame.origin.x, 0,self.welcomeMoreView.frame.size.width,self.welcomeMoreView.frame.size.height);
                             } completion:^(BOOL finished) {
                         }];
//        
//        [UIView animateWithDuration:0.5 animations:^{
//            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
//                                                 0,
//                                                 self.tutorialView.frame.size.width,
//                                                 self.tutorialView.frame.size.height);
//        }];
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        //CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        if (screenHeight==568){
            CGRect moreViewFrame = [self.welcomeMoreView frame];
            moreViewFrame.size.height = 568;  // change the location
            [self.welcomeMoreView setFrame:moreViewFrame];
            
            CGRect additionalCloseViewFrame = [additionalCloseButton frame];
            additionalCloseViewFrame.origin.y = 520;  // change the location
            [additionalCloseButton setFrame:additionalCloseViewFrame];
           
            CGRect additionalBackgroundImageFrame = [additionalBackgroundImage frame];
            additionalBackgroundImageFrame.origin.y = 20;  // change the location
           additionalBackgroundImageFrame.size.height = 568;  // change the location
            [additionalBackgroundImage setFrame:additionalBackgroundImageFrame];
            additionalBackgroundImage.image = [UIImage imageNamed:@"bg_iPhone_5_additional.png"];
            
        }
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}
- (IBAction)moreBackButtonClicked:(id)sender {
    [self swipeRight];
}
- (IBAction)shareEmailButtonClicked:(id)sender {
    [self sendMail];
}
- (IBAction)shareCancelButtonClicked:(id)sender {
    [self.videoShareView removeFromSuperview];
}
- (IBAction)shareFacebookButtonClicked:(id)sender {
    NSLog(@"shareFacebookButtonClicked");
    
    [self postPhotoClick:nil];
    //[self postStatusUpdateClick:nil];
//    SocialManager *socialManager = [SocialManager getInstance];
//    [socialManager fbCheckForPreviouslySavedAccessTokenInfo];
//    if(![socialManager isFBConnected]) {
//        [[socialManager facebook] authorize:[socialManager fbPermissions]];
//    } else {
//        [self preparePostToFacebookAlertView];
//    }

}
- (IBAction)shareTwitterButtonClicked:(id)sender {
    [self postToTwitter];
}
- (void) pushShareView:(NSString *)videoURL {
    if([[SessionManager getInstance] iPhoneContext]) {
        ShareViewController_iPhone *viewController = [[ShareViewController_iPhone alloc] init];
        //viewController.image = videoURL;
        [self.navigationController pushViewController:viewController animated:YES];
    }else{
        ShareViewController_iPad *viewController = [[ShareViewController_iPad alloc] init];
        //viewController.image = videoURL;
        [self.navigationController pushViewController:viewController animated:YES];
    }
}
-(void) fbLoginView{
   // if (FBSession.activeSession.state == FBSessionStateCreatedTokenLoaded) {
        NSLog(@"Vikrma: fbLoginView session is open");
        // Session is open
    //} else {
         NSLog(@"Vikrma: fbLoginView session is closed");
      // Create Login View so that the app will be granted "status_update" permission.
        FBLoginView *loginview = [[FBLoginView alloc] init];
        
        loginview.frame = CGRectOffset(loginview.frame, 5, 5);
        loginview.delegate = self;
        loginview.readPermissions = @[@"basic_info", @"email"];
        [videoShareFacebookLoginView addSubview:loginview];
        
        [loginview sizeToFit];
        // Session is closed
   // }
}
- (IBAction)videoShareButtonClicked:(id)sender {
    NSLog(@"Vikram: videoShareButtonClicked function");
    //[self fbLoginView];

     //[self closeMediaPlayerController];
    self.shareOptionButton.hidden = NO;
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"videoShareViewFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"videoShareViewFlag"];
        [self.view addSubview:self.videoShareView];
        self.videoShareView.frame = CGRectMake(self.videoShareView.frame.origin.x,
                                               self.videoShareView.superview.frame.size.height,
                                               self.videoShareView.frame.size.width,
                                               self.videoShareView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.videoShareView.frame = CGRectMake(self.videoShareView.frame.origin.x,
                                                   0,
                                                   self.videoShareView.frame.size.width,
                                                   self.videoShareView.frame.size.height);
        }];
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        //CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        if (screenHeight==568){
            CGRect videoShareView = [_videoShareView frame];
            videoShareView.size.height = 568;  // change the location
            [_videoShareView setFrame:videoShareView];
            
            CGRect videoShareButtonsViewFrame = [videoShareButtonsView frame];
            videoShareButtonsViewFrame.origin.y = 440;  // change the location
            [videoShareButtonsView setFrame:videoShareButtonsViewFrame];
            
        }
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
    //[self pushShareView:@"Testing link....."];

}

-(void)swipeleft
{
    if (didNotSwipe) {
        didNotSwipe = FALSE;
        [self.optionView removeFromSuperview];
        CATransition *animation = [CATransition animation];
        [animation setDelegate:self];
        [animation setType:kCATransitionPush];
        [animation setSubtype:kCATransitionFromRight];
        [animation setDuration:0.5];
        [animation setTimingFunction:
         [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionLinear]];
        //[self.tutorialView.layer addAnimation:animation forKey:kCATransition];
        [self.tutorialView addSubview:self.welcomeMoreView];
        //[self.overlayView setFrame:CGRectMake(0,0,self.overlayView.frame.size.width,self.overlayView.frame.size.height)];
    }
}
-(void)swipeRight
{
    if(!didNotSwipe){
        didNotSwipe = TRUE;
        CATransition *animation = [CATransition animation];
        [animation setDelegate:self];
        [animation setType:kCATransitionPush];
        [animation setSubtype:kCATransitionFromLeft];
        [animation setDuration:0.40];
        [animation setTimingFunction:
         [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
        //[self.tutorialView.layer addAnimation:animation forKey:kCATransition];
        [self.welcomeMoreView removeFromSuperview];
    }
}
- (IBAction)podcastButtonClicked:(id)sender{
      NSString *sendURLToView = @"http://m.ultimatesoftware.com/ContactForm/70160000000V5h8?from=mobile_enterprise";
    [self displayWebURLView:sendURLToView];
    
}
- (IBAction)webcastsButtonClicked:(id)sender{
    NSString *sendURLToView = @"http://m.ultimatesoftware.com/Events_Webcasts";
    [self displayWebURLView:sendURLToView];
    
}
- (IBAction)HCMButtonClicked:(id)sender{
    NSString *sendURLToView = @"http://m.ultimatesoftware.com/HCMAcademy";
    [self displayWebURLView:sendURLToView];
    
}
- (IBAction)contactUsClicked:(id)sender {
    //[[UIApplication sharedApplication] openURL:[NSURL URLWithString: @"http://m.ultimatesoftware.com/ContactUs"]];
    NSString *sendURLToView = @"http://m.ultimatesoftware.com/ContactUs";
    [self displayWebURLView:sendURLToView];
    
}

- (IBAction)backToHomeView:(id)sender {
    [self.webURLView removeFromSuperview];
}

- (IBAction)eventButtonClicked:(id)sender {
    NSString *sendURLToView = @"http://m.ultimatesoftware.com/ContentPage.aspx?p=20015";
    [self displayWebURLView:sendURLToView];
}

- (IBAction)homeAboutButtonClicked:(id)sender {
    //NSString *sendURLToView = @"http://m.ultimatesoftware.com/ContentPage.aspx?p=20016";
    //[self displayWebURLView:sendURLToView];
    [self aboutButtonClicked:nil];
}

- (IBAction)termsButtonClicked:(id)sender {
    //NSString *sendURLToView = @"http://m.ultimatesoftware.com/ContactUs";
    //[self displayWebURLView:sendURLToView];
    [self termsOfUseButtonClicked:nil];
}

- (IBAction)captureImageButtonClicked:(id)sender {
    [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    //UIImage *screenShot = [[super getScreenshotImage] fixOrientation];
    //[self pushShareView:[self mergeCharacterWith:screenShot]];
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
        
        //if (self.optionView.hidden) {
            NSLog(@"Vikram: triggermarker: %d",markerId);
            [self optionViewToggleButtonClicked:nil];
            
        //}
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
//    [self.view addSubview:self.homeView];
//    self.homeView.frame = CGRectMake(self.homeView.frame.origin.x,
//                                     self.homeView.superview.frame.size.height,
//                                     self.homeView.frame.size.width,
//                                     self.homeView.frame.size.height);
//    
//    [UIView animateWithDuration:0.5 animations:^{
//        self.homeView.center = self.homeView.superview.center;
//    }];
    [self displayTutorialView];
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
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    //CGFloat screenWidth = screenSize.width;
    CGFloat screenHeight = screenSize.height;
    
    if (screenHeight==568){
        CGRect tutorialViewFrame = [_aboutView frame];
        tutorialViewFrame.size.height = 568;  // change the location
        tutorialViewFrame.origin.y = 0;  // change the location
        [_aboutView setFrame:tutorialViewFrame];
        
    }
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
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    //CGFloat screenWidth = screenSize.width;
    CGFloat screenHeight = screenSize.height;
    
    if (screenHeight==568){
        CGRect tutorialViewFrame = [_termsOfUseView frame];
        tutorialViewFrame.size.height = 568;  // change the location
        tutorialViewFrame.origin.y = 0;  // change the location
        [_termsOfUseView setFrame:tutorialViewFrame];
        
        CGRect termsOfUseContentViewOneFrame = [_termsOfUseContentViewOne frame];
        termsOfUseContentViewOneFrame.origin.y = 0;  // change the location
        [_termsOfUseContentViewOne setFrame:termsOfUseContentViewOneFrame];
        
    }
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
    //    self.helpMessageLabel.hidden = NO;
    //    currentMarkerId = 0;
    
    [self.tutorialView removeFromSuperview];
    
    //    [UIView animateWithDuration:0.6 animations:^{
    //        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
    //                                             self.tutorialView.superview.frame.size.height,
    //                                             self.tutorialView.frame.size.width,
    //                                             self.tutorialView.frame.size.height);
    //        [self performSelector:@selector(removeTutorialView) withObject:nil afterDelay:0.6];
    //    }];
    //
    //    [self loadOrCheckMarkersFromService];
}

- (void)optionViewToggleButtonClicked:(id)sender {
    
    self.helpMessageLabel.hidden = self.optionView.hidden;
    if(currentMarkerId>=0) self.helpMessageLabel.hidden = YES;
    if(currentMarkerId>0) {
        [self loadCharacter:currentMarkerId];
    }
}

- (void)optionButtonClicked:(id)sender {
    
    //UIButton *btn = (UIButton *)sender;
    
    [self loadCharacter:currentMarkerId];
    [self optionViewToggleButtonClicked:nil];
}

- (IBAction)clearOptions:(id)sender     {
    [self hideCharacterSystem];
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
- (void) onTrackingEvent: (std::vector<metaio::TrackingValues>) poses {
    //NSLog(@"Vikram: ontrackingevent: %ld",poses.size());
    if(poses.size()==1){
        int cosId = poses[0].coordinateSystemID;
        [self triggerMarker:cosId];
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
    [[SessionManager getInstance].posterVisual removeAllObjects];
    if([self noMarkerAvailableLocally]) {
        [self loadDefaultMarker];
    } else {
        NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
        [SessionManager getInstance].posterVisual = data;
        NSString *trackingDataFile = [[self getMetaioPath] stringByAppendingPathComponent:@"markers.xml"];
        main_sync(^{
            isFirstTime = YES;
            m_metaioSDK->setTrackingConfiguration([trackingDataFile UTF8String]);
        });
    }
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

- (void) loadCharacter:(int)markerId {
    //NSLog(@"beginning of loadcharacter function");
    self.helpMessageLabel.hidden = YES;
    self.shapeSlider.value = 60;
    int optionIndex = currentMarkerId-1;
    // NSLog(@"currentMarkerId = %d", currentMarkerId);
    if(currentMarkerId>0) {
        //NSLog(@"before markerinfo");
        NSDictionary *markerInfo = [[SessionManager getInstance] markerInfo:1];
        if(markerInfo) {
            //NSLog(@"after markerinfo if condition");
            
            currentSelectedOption = [[Option alloc] init];
            
            // Get Video URL
            NSArray *videos = [markerInfo objectForKey:@"VideoURL"];
            NSString *movieURLString = [videos objectAtIndex:optionIndex];
            currentSelectedOption.videoURL = movieURLString;
            
        }
    }
    [self playMovie:currentSelectedOption.videoURL];
    
    
}

- (void) reSetImageMarkersAndVisualsForDownload {
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterVisualPath"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterData"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"lastDateKey"];
    [self loadTrackingDataFromUserDefault];
    self.helpMessageLabel.hidden = NO;
}

- (void)loadMarkers:(ScanMode)newScanMode progressIndicator:(void(^)(int current, int count))progressIndicator {
    //NSLog(@"loadMarkers");
    scanMode = newScanMode;
    switch (scanMode) {
        case imageMarkers: {
            NSArray *postersData = [self retrieveServiceMarkers];
            NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
            if ([postersData count]){
                //NSLog(@"Posters count = %d",[postersData count]);
                [self deleteMetaioDirectory];
                [self createMetaioDirectory];
                [self downloadPosterMarkers: postersData progressIndicator:progressIndicator];
                [self downloadPosterMarkersVisual: postersData progressIndicator:progressIndicator];
                [[NSUserDefaults standardUserDefaults]setValue:[SessionManager getInstance].posterVisual forKey:@"PosterVisualPath"];
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
    NSString *trackingDataFile = [[NSBundle mainBundle] pathForResource:@"TrackingDefault_Marker" ofType:@"xml" inDirectory:@"assets"];
    //NSString *data = [[NSString alloc] initWithUTF8String:[trackingDataFile UTF8String]];
    //NSLog(@"Tracking Data File = %@",data);
    main_sync(^{
        isFirstTime = YES;
        m_metaioSDK->setTrackingConfiguration([trackingDataFile UTF8String]);
    });
	[self updateMarkersFromServiceFlag:YES];
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
    self.shareOptionButton.hidden = NO;
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"tutorialFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"tutorialFlag"];
        [self.view addSubview:self.tutorialView];
        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                             self.tutorialView.superview.frame.size.height,
                                             self.tutorialView.frame.size.width,
                                             self.tutorialView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                 0,
                                                 self.tutorialView.frame.size.width,
                                                 self.tutorialView.frame.size.height);
        }];
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        //CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        if (screenHeight==568){
            CGRect tutorialViewFrame = [_tutorialView frame];
            tutorialViewFrame.size.height = 568;  // change the location
            [_tutorialView setFrame:tutorialViewFrame];
            
        }
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}

#pragma mark - lifecycle
- (void)displayHomeView {
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"homeViewFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"homeViewFlag"];
        [self.view addSubview:self.homeView];
        self.homeView.frame = CGRectMake(self.homeView.frame.origin.x,
                                         self.homeView.superview.frame.size.height,
                                         self.homeView.frame.size.width,
                                         self.homeView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.homeView.frame = CGRectMake(self.homeView.frame.origin.x,
                                             0,
                                             self.homeView.frame.size.width,
                                             self.homeView.frame.size.height);
        }];
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        //CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        if (screenHeight==568){
            CGRect homeViewFrame = [_homeView frame];
            homeViewFrame.size.height = 568;  // change the location
            [_homeView setFrame:homeViewFrame];
            
        }
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}

#pragma mark - lifecycle
- (void)displayWebURLView:(NSString *) webURL {
    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"webURLFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"webURLFlag"];
        [self.view addSubview:self.webURLView];
        self.webURLView.frame = CGRectMake(self.webURLView.frame.origin.x,
                                           self.webURLView.superview.frame.size.height,
                                           self.webURLView.frame.size.width,
                                           self.webURLView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            self.webURLView.frame = CGRectMake(self.webURLView.frame.origin.x,
                                               0,
                                               self.webURLView.frame.size.width,
                                               self.webURLView.frame.size.height);
        }];
        
        CGRect screenBound = [[UIScreen mainScreen] bounds];
        CGSize screenSize = screenBound.size;
        //CGFloat screenWidth = screenSize.width;
        CGFloat screenHeight = screenSize.height;
        if (screenHeight==568){
            CGRect webURLViewFrame = [_webURLView frame];
            webURLViewFrame.size.height = 568;  // change the location
            [_webURLView setFrame:webURLViewFrame];
            
            CGRect webURLWebViewFrame = [_webURLWebView frame];
            webURLWebViewFrame.size.height = 390;  // change the location
            webURLWebViewFrame.size.width = 320;  // change the location
            [_webURLWebView setFrame:webURLWebViewFrame];
            
            CGRect webUrlBackButtonFrame = [webUrlBackButton frame];
            webUrlBackButtonFrame.origin.y= 520;  // change the location
            [webUrlBackButton setFrame:webUrlBackButtonFrame];
            
        }
        
        // _webURLWebView=[[UIWebView alloc]initWithFrame:CGRectMake(0, 50, 1024,768)];
        NSString *url;
        if (webURL){
            url  =webURL;
        }else{
            url  =CLIENT_DEFAULT_URL;
        }
        NSURL *nsurl=[NSURL URLWithString:url];
        NSURLRequest *nsrequest=[NSURLRequest requestWithURL:nsurl];
        [_webURLWebView loadRequest:nsrequest];
        [self.webURLView addSubview:_webURLWebView];
        [_webURLWebView.window makeKeyAndVisible];
        
        
    } else {
        self.helpMessageLabel.hidden = NO;
    }
}

- (void) loadOrCheckMarkersFromService {
    //NSLog(@"loadOrCheckMarkersFromService");
	if([self isLoadMarkersFromService]) {
        //NSLog(@" -- loadMarkers");
		[self loadMarkers];
	} else {
        //NSLog(@" -- checkLatestMarkersFromService");
        [self performSelectorInBackground:@selector(checkLatestMarkersFromService) withObject:nil];
	}
}
-(void)loadAfterHomeView{
    
    
    self.navigationController.view.frame = self.viewFrame;
    self.optionView.hidden=YES;
    [self initGestureView];
    
    self.shapeSlider.transform =CGAffineTransformMakeRotation(M_PI * -0.5);
    self.animationFlag =true;
    [self loadTrackingDataFromUserDefault];
    
    //[self displayTutorialView];
    [self loadOrCheckMarkersFromService];
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
- (void)viewDidLoad
{
    [super viewDidLoad];
    
    self.shareOptionButton.hidden = YES;
     [self.navigationController setNavigationBarHidden: YES animated:YES];
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
    
    // Set Tutorial Content View
    [self.tutorialScrollView addSubview:self.tutorialContentView];
    self.tutorialScrollView.contentSize = CGSizeMake(self.tutorialContentView.frame.size.width,
                                                     self.tutorialContentView.frame.size.height);
    
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
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    //CGFloat screenWidth = screenSize.width;
    CGFloat screenHeight = screenSize.height;
    if (screenHeight==568){
        CGRect helpMessageLabelViewFrame = [self.helpMessageLabel frame];
        helpMessageLabelViewFrame.origin.y = 280;  // change the location
        [self.helpMessageLabel setFrame:helpMessageLabelViewFrame];
        
    }

    [self displayTutorialView];
    
}

- (void)checkProgressIndicator
{
    if (self.animationFlag) {
        hud= [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    }
}

- (void)viewDidAppear:(BOOL)animated   {
    NSLog(@"Vikram: viewDidAppear");
    self.shareOptionButton.hidden = YES;
    [self closeMediaPlayerController];
    [self.navigationController setNavigationBarHidden: YES animated:YES];
    [self stopPlayingVideo:nil];
    moviePlayer = nil;
    [self clearOptions:nil];
    [super viewDidAppear:animated];
}

- (void)viewWillAppear:(BOOL)animated {
    self.shareOptionButton.hidden = YES;
    [super viewWillAppear:animated];
    NSLog(@"Vikram: viewWillAppear");
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
    //Facebook unload items
    self.buttonPickFriends = nil;
    self.buttonPickPlace = nil;
    self.buttonPostPhoto = nil;
    self.buttonPostStatus = nil;
    self.labelFirstName = nil;
    self.loggedInUser = nil;
    self.profilePic = nil;
    
    [self setScanTestimonials:nil];
    [self setContactTrigger:nil];
    [self setWebURLWebView:nil];
    [super viewDidUnload];
    NSLog(@"view did unload");
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

- (void)setViewFrameCustom:(CGRect)viewFrame {
    CGRect newFrame = CGRectMake(viewFrame.origin.x, 0, viewFrame.size.width, viewFrame.size.height+viewFrame.origin.y);
    self.viewFrame = newFrame;
}
//- (IBAction)facebookButtonClicked:(id)sender {
//    self.shareOptionButton.hidden = NO;
//    if (ALWAYS_DISPLAY_TUTORIAL || [[[NSUserDefaults standardUserDefaults ]objectForKey:@"videoShareViewFlag"] length]==0) {
//        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"videoShareViewFlag"];
//        [self.view addSubview:self.videoShareView];
//        self.videoShareView.frame = CGRectMake(self.videoShareView.frame.origin.x,
//                                             self.videoShareView.superview.frame.size.height,
//                                             self.videoShareView.frame.size.width,
//                                             self.videoShareView.frame.size.height);
//        
//        [UIView animateWithDuration:0.5 animations:^{
//            self.videoShareView.frame = CGRectMake(self.videoShareView.frame.origin.x,
//                                                 0,
//                                                 self.videoShareView.frame.size.width,
//                                                 self.videoShareView.frame.size.height);
//        }];
//        
//        CGRect screenBound = [[UIScreen mainScreen] bounds];
//        CGSize screenSize = screenBound.size;
//        CGFloat screenWidth = screenSize.width;
//        CGFloat screenHeight = screenSize.height;
//        if (screenHeight==568){
//            CGRect tutorialViewFrame = [_videoShareView frame];
//            tutorialViewFrame.size.height = 568;  // change the location
//            [_videoShareView setFrame:tutorialViewFrame];
//            
//        }
//        
//    } else {
//        self.helpMessageLabel.hidden = NO;
//    }
//}
- (void) preparePostToFacebookAlertView {
    UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Facebook" message:@"Want to post photo on your wall?" delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Post", nil];
    [alert setDelegate:self];
    alert.tag = 100;
    [alert show];
}

//- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
//    if (alertView.tag == 100){
//        if (buttonIndex == 0){ // Cancel button
//            return;
//        }
//        if (buttonIndex == 1) { // OK button
//            [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
//            [[SocialManager getInstance] setFbRequestDelegate:self];
//            [self postToFacebook];
//        }
//    }
//}

#pragma mark - Facebook actions
//- (void) postToFacebook {
//    //    NSString *message = [[NSString alloc] initWithFormat:@"Celebrating 20 years of Tarantino – http://www.TarantinoXX.com \nGet the Miramax Augmented Reality app at the Apple Store http://bit.ly/SyDGrb or Google Play http://bit.ly/TOTt8I"];
//    //    NSData *imageData = UIImageJPEGRepresentation(self.image, 1.0);
//    //
//    //    NSMutableDictionary *params = [[NSMutableDictionary alloc] initWithObjectsAndKeys:
//    //                                   message, @"message",
//    //                                   imageData,@"source",
//    //                                   nil];
//    //
//    //    [[[SocialManager getInstance] facebook] requestWithGraphPath:@"me/photos" andParams:params andHttpMethod:@"POST" andDelegate:self];
//    NSLog(@"Vikram: currentVideoUrl in facebook %@",currentVideoUrl);
//    
// 
//    NSData *videoData = nil;
//    if (currentVideoUrl){
//        NSString *currentVideoName = [currentVideoUrl stringByReplacingOccurrencesOfString:@"http://s3.amazonaws.com/seemore-cms/cms/Ultimate/"
//                                                                                withString:@""];
//        
//        currentVideoName = [currentVideoName stringByReplacingOccurrencesOfString:@".mp4"
//                                                                      withString:@""];
//         NSLog(@"Vikram: currentVideoName in facebook %@",currentVideoName);
//        NSString *filePath = [[NSBundle mainBundle] pathForResource:currentVideoName ofType:@"mp4" inDirectory:@"assets/Videos"];
//         videoData = [NSData dataWithContentsOfFile:filePath];
//   }
//   
//    NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
//                                   videoData, @"video.mov",
//                                   @"video/quicktime", @"contentType",
//                                   @"Ultimate Software", @"title",
//                                   @"Being part of Ultimate Software is truly special. Hear about it  from our people firsthand, in their own words!  Ultimate Software", @"description",
//								   nil];
//	[[[SocialManager getInstance] facebook]  requestWithGraphPath:@"me/videos"
//                                                        andParams:params
//                                                    andHttpMethod:@"POST"
//                                                      andDelegate:self];
//    
//}
#pragma mark - Facebook RequestDelegate
/**
 * Called just before the request is sent to the server.
 */
- (void)requestLoading:(FBRequest *)request {
    NSLog(@"requestLoading = %@",request);
}

/**
 * Called when the server responds and begins to send back data.
 */
- (void)request:(FBRequest *)request didReceiveResponse:(NSURLResponse *)response {
    NSLog(@"didReceiveResponse = %@",response);
}

/**
 * Called when an error prevents the request from completing successfully.
 */
- (void)request:(FBRequest *)request didFailWithError:(NSError *)error {
    NSLog(@"didFailWithError");
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Error"
                                                        message:[NSString stringWithFormat:@"%@",error]
                                                       delegate:self
                                              cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil];
    [alertView show];
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

/**
 * Called when a request returns and its response has been parsed into
 * an object.
 *
 * The resulting object may be a dictionary, an array, a string, or a number,
 * depending on thee format of the API response.
 */
- (void)request:(FBRequest *)request didLoad:(id)result {
    NSLog(@"Facebook request.didLoad");
    NSLog(@"Result = %@", result);
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

/**
 * Called when a request returns a response.
 *
 * The result object is the raw response from the server of type NSData
 */
- (void)request:(FBRequest *)request didLoadRawResponse:(NSData *)data {
    
    NSLog(@"request = %@", data);
}
- (void) postFaceBookAlert:(NSNotification *)notification{
    [self preparePostToFacebookAlertView];
}
-(void)sendMail
{
    Class mailClass = (NSClassFromString(@"MFMailComposeViewController"));
    if (mailClass != nil && [mailClass canSendMail]) {
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        MFMailComposeViewController *picker = [[MFMailComposeViewController alloc] init];
        picker.mailComposeDelegate = self;
        
        picker.subject = [[NSString alloc] initWithFormat:@"Ultimate Software"];
        
        // Set up the recipients.
        NSArray *toRecipients = [[NSArray alloc] init];
        NSArray *ccRecipients = [[NSArray alloc] init];
        NSArray *bccRecipients = [[NSArray alloc] init];
        
        picker.toRecipients = toRecipients;
        picker.ccRecipients = ccRecipients;
        picker.bccRecipients = bccRecipients;
        
        // NSData *imageData = UIImagePNGRepresentation(self.image);
        //NSData *imageData = UIImageJPEGRepresentation(self.image, 1);
        //NSString *path = [[NSBundle mainBundle] pathForResource:@"sample" ofType:@"mov"];
        //NSData *myData = [NSData dataWithContentsOfFile:path];
        //[picker addAttachmentData:myData mimeType:@"video/mp4" fileName:@"sample"];
        
       // [picker addAttachmentData:imageData mimeType:@"video/jpg" fileName:@"ultimate.jpg"];
        NSString *videoLink=@"";
        NSDictionary *markerInfo = [[SessionManager getInstance] markerInfo:1];
        if(markerInfo) {
            currentSelectedOption = [[Option alloc] init];
            NSLog(@"currentMarkerId in Email function %d",currentMarkerId);
            int optionIndex = currentMarkerId-1;
            NSArray *videos = [markerInfo objectForKey:@"VideoShortURL"];
            NSString *videoShortURL = [videos objectAtIndex:optionIndex];
            videoLink =  [[NSString alloc] initWithFormat:@"<a href='%@'>%@</a><br><br>",videoShortURL,videoShortURL];
        }
        NSLog(@"videoLink in Email function %@",videoLink);
        // Fill out the email body text.
        NSString *emailBody = [[NSString alloc] initWithFormat:@"Being part of Ultimate Software is truly special.  You can hear about it  from our people firsthand, in their own words!<br><br>Get an insider’s look at the People of Ultimate Software by viewing and sharing exclusive video content and learn why Ultimate employees love their job, the people they work with and the company they work for.<br><br>%@<a href='http://www.ultimatesoftware.com'>Ultimate Software</a>",videoLink];
        [picker setMessageBody:emailBody isHTML:YES];
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
        // Present the mail composition interface.
        [self presentModalViewController:picker animated:YES];
    }
    else
    {
        UIAlertView *noMailAlert = [[UIAlertView alloc] initWithTitle:@"No Email Account Exist" message:@"You need to set up an email account on the Mail app in order to share photo." delegate:nil cancelButtonTitle:@"Close" otherButtonTitles:nil];
		[noMailAlert show];
    }
}

- (void)mailComposeController:(MFMailComposeViewController *)controller
          didFinishWithResult:(MFMailComposeResult)result
                        error:(NSError *)error
{
    [self dismissModalViewControllerAnimated:YES];
}
- (IBAction)postToTwitter {
    NSLog(@"Vikram: currentVideoUrl %@",currentVideoUrl);
   
    if ([TWTweetComposeViewController canSendTweet]) {
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        TWTweetComposeViewController *tweetComposeViewController = [[TWTweetComposeViewController alloc] init];
        
        NSString *message = [[NSString alloc] initWithFormat:@"@UltimateHCM"];
        
        BOOL isInitialText = [tweetComposeViewController setInitialText:message];
        NSLog(@"isInitialText = %d", isInitialText);
        
        //[tweetComposeViewController addImage:self.image];

        NSString *videoLink=@"";
        NSDictionary *markerInfo = [[SessionManager getInstance] markerInfo:1];
        if(markerInfo) {
          NSLog(@"currentMarkerId in Email function %d",currentMarkerId);
            int optionIndex = currentMarkerId-1;
            NSArray *videos = [markerInfo objectForKey:@"VideoShortURL"];
            NSString *videoShortURL = [videos objectAtIndex:optionIndex];
            NSURL * url = [[NSURL alloc]initWithString:videoShortURL];
            [tweetComposeViewController addURL:url];
        }
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
	    [self presentModalViewController:tweetComposeViewController animated:YES];
      
    } else {
        UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Please"
                                                            message:@"Setup Twitter account in Settings."
                                                           delegate:self
                                                  cancelButtonTitle:@"OK"
                                                  otherButtonTitles:nil];
        [alertView show];
    }
}


- (void)loginViewShowingLoggedInUser:(FBLoginView *)loginView {
    NSLog(@"Vikram: loginViewShowingLoggedInUser");
    // first get the buttons set for login mode
    self.buttonPostPhoto.enabled = YES;
    self.buttonPostStatus.enabled = YES;
    self.buttonPickFriends.enabled = YES;
    self.buttonPickPlace.enabled = YES;
    
    // "Post Status" available when logged on and potentially when logged off.  Differentiate in the label.
    [self.buttonPostStatus setTitle:@"Post Status Update (Logged On)" forState:self.buttonPostStatus.state];
}

- (void)loginViewFetchedUserInfo:(FBLoginView *)loginView
                            user:(id<FBGraphUser>)user {
    // here we use helper properties of FBGraphUser to dot-through to first_name and
    // id properties of the json response from the server; alternatively we could use
    // NSDictionary methods such as objectForKey to get values from the my json object
    self.labelFirstName.text = [NSString stringWithFormat:@"Hello %@!", user.first_name];
    // setting the profileID property of the FBProfilePictureView instance
    // causes the control to fetch and display the profile picture for the user
    self.profilePic.profileID = user.id;
    self.loggedInUser = user;
}

- (void)loginViewShowingLoggedOutUser:(FBLoginView *)loginView {
    NSLog(@"Vikram: loginViewShowingLoggedOutUser");
    // test to see if we can use the share dialog built into the Facebook application
    FBShareDialogParams *p = [[FBShareDialogParams alloc] init];
    p.link = [NSURL URLWithString:@"http://developers.facebook.com/ios"];
#ifdef DEBUG
    [FBSettings enableBetaFeatures:FBBetaFeaturesShareDialog];
#endif
    BOOL canShareFB = [FBDialogs canPresentShareDialogWithParams:p];
    BOOL canShareiOS6 = [FBDialogs canPresentOSIntegratedShareDialogWithSession:nil];
    
    self.buttonPostStatus.enabled = canShareFB || canShareiOS6;
    self.buttonPostPhoto.enabled = NO;
    self.buttonPickFriends.enabled = NO;
    self.buttonPickPlace.enabled = NO;
    
    // "Post Status" available when logged on and potentially when logged off.  Differentiate in the label.
    [self.buttonPostStatus setTitle:@"Post Status Update (Logged Off)" forState:self.buttonPostStatus.state];
    
    self.profilePic.profileID = nil;
    self.labelFirstName.text = nil;
    self.loggedInUser = nil;
}

- (void)loginView:(FBLoginView *)loginView handleError:(NSError *)error {
    // see https://developers.facebook.com/docs/reference/api/errors/ for general guidance on error handling for Facebook API
    // our policy here is to let the login view handle errors, but to log the results
    NSLog(@"FBLoginView encountered an error=%@", error);
}

#pragma mark -

// Convenience method to perform some action that requires the "publish_actions" permissions.
- (void) performPublishAction:(void (^)(void)) action {
    NSLog(@"Vikram: performPublishAction");
    // we defer request for permission to post to the moment of post, then we check for the permission
//    if ([FBSession.activeSession.permissions indexOfObject:@"publish_actions"] == NSNotFound) {
//         NSLog(@"Vikram: publish_actions notfound");
//        // if we don't already have the permission, then we request it now
//        [FBSession.activeSession requestNewPublishPermissions:@[@"publish_actions"]
//                                              defaultAudience:FBSessionDefaultAudienceFriends
//                                            completionHandler:^(FBSession *session, NSError *error) {
//                                                if (!error) {
//                                                    NSLog(@"Vikram: publish_actions notfound not error action in place");
//                                                    action();
//                                                }
//                                                //For this example, ignore errors (such as if user cancels).
//                                            }];
//    } else {
//        NSLog(@"Vikram: if else action");
//        action();
//    }
    
    if ([[FBSession activeSession]isOpen]) {
        NSLog(@"Vikram: FBSession activeSession if condition'");
        /*
         * if the current session has no publish permission we need to reauthorize
         */
        if ([FBSession.activeSession.permissions indexOfObject:@"publish_actions"] == NSNotFound) {
            NSLog(@"Vikram: publish_actions notfound");
            // if we don't already have the permission, then we request it now
            [FBSession.activeSession requestNewPublishPermissions:@[@"publish_actions"]
                                                  defaultAudience:FBSessionDefaultAudienceFriends
                                                completionHandler:^(FBSession *session, NSError *error) {
                                                    if (!error) {
                                                        NSLog(@"Vikram: publish_actions notfound not error action in place");
                                                        action();
                                                    }
                                                    //For this example, ignore errors (such as if user cancels).
                                                }];
        } else {
            NSLog(@"Vikram: if else action");
            action();
        }
    }else{
        /*
         * open a new session with publish permission
         */
        [FBSession.activeSession closeAndClearTokenInformation];
        
        //NSArray *permissions = [NSArray arrayWithObjects:@"publish_actions, publish_stream", nil];
        
//#ifdef IOS_NEWER_OR_EQUAL_TO_6
//        permissions = nil; or NSArray *permissions = [NSArray arrayWithObjects:@"publish_actions",nil];
//#endif
        [FBSession openActiveSessionWithPublishPermissions:@[@"publish_actions"]
                                           defaultAudience:FBSessionDefaultAudienceFriends
                                              allowLoginUI:YES
                                         completionHandler:^(FBSession *session, FBSessionState status, NSError *error) {
                                             NSLog(@"Vikram: Error %@",error);
                                             NSLog(@"Vikram: status %d",status);
                                             NSLog(@"Vikram: FBSessionStateOpen %d",FBSessionStateOpen);
//                                             if (!error && status == FBSessionStateOpen) {
//                                                 // [self publishStory];
//                                                 NSLog(@"Vikram: FBSession activeSession if else condition");
//                                                 action();
//                                             }else{
//                                                 NSLog(@"Vikram: FBSession activeSession if else condition error block");
//                                                 NSLog(@"error");
//                                                
//                                             }
                                             switch (status) {
                                                 case FBSessionStateOpen:{
                                                     NSLog(@"Vikram: FBSessionStateOpen");
                                                     [[FBRequest requestForMe] startWithCompletionHandler:
                                                      ^(FBRequestConnection *connection, NSDictionary<FBGraphUser> *user, NSError *error) {
                                                          if (!error) {
                                                              //success
                                                              action();
                                                          }
                                                      }];}
                                                     break;
                                                 case FBSessionStateClosed:{}
                                                     //need to handle
                                                     NSLog(@"Vikram: FBSessionStateClosed");
                                                     break;
                                                 case FBSessionStateClosedLoginFailed:{}
                                                     //need to handle
                                                     NSLog(@"Vikram: FBSessionStateClosedLoginFailed");
                                                     break;
                                                 default:
                                                     break;
                                             }
                                         }];
    }

}

// Post Status Update button handler; will attempt different approaches depending upon configuration.
- (IBAction)postStatusUpdateClick:(UIButton *)sender {
    // Post a status update to the user's feed via the Graph API, and display an alert view
    // with the results or an error.
    
    NSURL *urlToShare = [NSURL URLWithString:@"http://developers.facebook.com/ios"];
    
    // This code demonstrates 3 different ways of sharing using the Facebook SDK.
    // The first method tries to share via the Facebook app. This allows sharing without
    // the user having to authorize your app, and is available as long as the user has the
    // correct Facebook app installed. This publish will result in a fast-app-switch to the
    // Facebook app.
    // The second method tries to share via Facebook's iOS6 integration, which also
    // allows sharing without the user having to authorize your app, and is available as
    // long as the user has linked their Facebook account with iOS6. This publish will
    // result in a popup iOS6 dialog.
    // The third method tries to share via a Graph API request. This does require the user
    // to authorize your app. They must also grant your app publish permissions. This
    // allows the app to publish without any user interaction.
    
    // If it is available, we will first try to post using the share dialog in the Facebook app
    FBAppCall *appCall = [FBDialogs presentShareDialogWithLink:urlToShare
                                                          name:@"Hello Facebook"
                                                       caption:nil
                                                   description:@"The 'Hello Facebook' sample application showcases simple Facebook integration."
                                                       picture:nil
                                                   clientState:nil
                                                       handler:^(FBAppCall *call, NSDictionary *results, NSError *error) {
                                                           if (error) {
                                                               NSLog(@"Error: %@", error.description);
                                                           } else {
                                                               NSLog(@"Success!");
                                                           }
                                                       }];
    
    if (!appCall) {
        // Next try to post using Facebook's iOS6 integration
        BOOL displayedNativeDialog = [FBDialogs presentOSIntegratedShareDialogModallyFrom:self
                                                                              initialText:nil
                                                                                    image:nil
                                                                                      url:urlToShare
                                                                                  handler:nil];
        
        if (!displayedNativeDialog) {
            // Lastly, fall back on a request for permissions and a direct post using the Graph API
            [self performPublishAction:^{
                NSString *message = [NSString stringWithFormat:@"Updating status for %@ at %@", self.loggedInUser.first_name, [NSDate date]];
                
                [FBRequestConnection startForPostStatusUpdate:message
                                            completionHandler:^(FBRequestConnection *connection, id result, NSError *error) {
                                                
                                                [self showAlert:message result:result error:error];
                                                self.buttonPostStatus.enabled = YES;
                                            }];
                
                self.buttonPostStatus.enabled = NO;
            }];
        }
    }
}

// Post Photo button handler
- (IBAction)postPhotoClick:(UIButton *)sender {
    // Just use the icon image from the application itself.  A real app would have a more
    // useful way to get an image.
   // UIImage *img = [UIImage imageNamed:@"Icon-72@2x.png"];
    NSLog(@"Vikram: postPhotoClick");

    [self performPublishAction:^{
        
        [self upload];
        self.buttonPostPhoto.enabled = NO;
    }];
}
//-(void)upload{
//    if (FBSession.activeSession.isOpen) {
//        NSString *currentVideoName=@"Belonging_To_Something_Special_042513A_1";
//        if (currentVideoUrl){
//            currentVideoName = [currentVideoUrl stringByReplacingOccurrencesOfString:@"http://s3.amazonaws.com/seemore-cms/cms/Ultimate/"
//                                                                          withString:@""];
//            
//            currentVideoName = [currentVideoName stringByReplacingOccurrencesOfString:@".mp4"
//                                                                           withString:@""];
//            NSLog(@"Vikram: currentVideoName in facebook %@",currentVideoName);
//        }
//        NSString *filePath = [[NSBundle mainBundle] pathForResource:currentVideoName ofType:@"mp4" inDirectory:@"assets/Videos"];
//        //NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:filePath isDirectory:NO];
//        NSData *videoData = [NSData dataWithContentsOfFile:filePath];
//        NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
//                                       videoData, @"video.mov",
//                                       @"video/quicktime", @"contentType",
//                                       @"Video Test Title", @"title",
//                                       @"Video Test Description", @"description",
//                                       nil];
////        [FBRequest requestWithGraphPath:@"me/videos"
////                             parameters:params
////                         HTTPMethod:@"POST"];
//        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
//        FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/videos"
//                                                        parameters:params
//                                                        HTTPMethod:@"POST"];
//        
//        [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error) {
//            if (!error)
//                NSLog(@"Done: %@", result);
//            else
//                NSLog(@"Error: %@", error.localizedDescription);
//            
//             [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
//        }];
//
//    }
//}
- (void)upload{
    @try{
    NSLog(@"Vikram: upload function %d",FBSession.activeSession.isOpen);
    if (FBSession.activeSession.isOpen) {
        NSString *currentVideoName=@"Belonging_To_Something_Special_042513A_1";
        if (currentVideoUrl){
                    currentVideoName = [currentVideoUrl stringByReplacingOccurrencesOfString:@"http://s3.amazonaws.com/seemore-cms/cms/Ultimate/"
                                                                                            withString:@""];
            
                    currentVideoName = [currentVideoName stringByReplacingOccurrencesOfString:@".mp4"
                                                                                  withString:@""];
             NSLog(@"Vikram: currentVideoName in facebook %@",currentVideoName);
        }
        NSString *filePath = [[NSBundle mainBundle] pathForResource:currentVideoName ofType:@"mp4" inDirectory:@"assets/Videos"];
        NSURL *pathURL = [[NSURL alloc]initFileURLWithPath:filePath isDirectory:NO];
        NSData *videoData = [NSData dataWithContentsOfFile:filePath];
        //NSString *filePath = [[NSBundle mainBundle] pathForResource:currentVideoName ofType:@"mp4" inDirectory:@"assets/Videos"];
        //videoData = [NSData dataWithContentsOfFile:filePath];
        NSLog(@"Vikram: pathURL in upload function %@",pathURL);
        NSDictionary *videoObject = @{
                                      @"title": @"Ultimate Software",
                                      @"description": @"Being part of Ultimate Software is truly special. Hear about it  from our people firsthand, in their own words!",
                                      [pathURL absoluteString]: videoData
                                      };
      
         [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        FBRequest *uploadRequest = [FBRequest requestWithGraphPath:@"me/videos"
                                                        parameters:videoObject
                                                        HTTPMethod:@"POST"];
        
        [uploadRequest startWithCompletionHandler:^(FBRequestConnection *connection, id result, NSError *error) {
            if (!error)
                NSLog(@"Done: %@", result);
            else
                NSLog(@"Error: %@", error.localizedDescription);

             [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
        }];
       
    }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Uploading to Facebook" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  NSLog(@"Vikram: in finally block");}
}
// Pick Friends button handler
- (IBAction)pickFriendsClick:(UIButton *)sender {
    FBFriendPickerViewController *friendPickerController = [[FBFriendPickerViewController alloc] init];
    friendPickerController.title = @"Pick Friends";
    [friendPickerController loadData];
    
    // Use the modal wrapper method to display the picker.
    [friendPickerController presentModallyFromViewController:self animated:YES handler:
     ^(FBViewController *sender, BOOL donePressed) {
         
         if (!donePressed) {
             return;
         }
         
         NSString *message;
         
         if (friendPickerController.selection.count == 0) {
             message = @"<No Friends Selected>";
         } else {
             
             NSMutableString *text = [[NSMutableString alloc] init];
             
             // we pick up the users from the selection, and create a string that we use to update the text view
             // at the bottom of the display; note that self.selection is a property inherited from our base class
             for (id<FBGraphUser> user in friendPickerController.selection) {
                 if ([text length]) {
                     [text appendString:@", "];
                 }
                 [text appendString:user.name];
             }
             message = text;
         }
         
         [[[UIAlertView alloc] initWithTitle:@"You Picked:"
                                     message:message
                                    delegate:nil
                           cancelButtonTitle:@"OK"
                           otherButtonTitles:nil]
          show];
     }];
}

// Pick Place button handler
- (IBAction)pickPlaceClick:(UIButton *)sender {
    FBPlacePickerViewController *placePickerController = [[FBPlacePickerViewController alloc] init];
    placePickerController.title = @"Pick a Seattle Place";
    placePickerController.locationCoordinate = CLLocationCoordinate2DMake(47.6097, -122.3331);
    [placePickerController loadData];
    
    // Use the modal wrapper method to display the picker.
    [placePickerController presentModallyFromViewController:self animated:YES handler:
     ^(FBViewController *sender, BOOL donePressed) {
         
         if (!donePressed) {
             return;
         }
         
         NSString *placeName = placePickerController.selection.name;
         if (!placeName) {
             placeName = @"<No Place Selected>";
         }
         
         [[[UIAlertView alloc] initWithTitle:@"You Picked:"
                                     message:placeName
                                    delegate:nil
                           cancelButtonTitle:@"OK"
                           otherButtonTitles:nil]
          show];
     }];
}

// UIAlertView helper for post buttons
- (void)showAlert:(NSString *)message
           result:(id)result
            error:(NSError *)error {
    
    NSString *alertMsg;
    NSString *alertTitle;
    if (error) {
        alertTitle = @"Error";
        if (error.fberrorShouldNotifyUser ||
            error.fberrorCategory == FBErrorCategoryPermissions ||
            error.fberrorCategory == FBErrorCategoryAuthenticationReopenSession) {
            alertMsg = error.fberrorUserMessage;
        } else {
            alertMsg = @"Operation failed due to a connection problem, retry later.";
        }
    } else {
        NSDictionary *resultDict = (NSDictionary *)result;
        alertMsg = [NSString stringWithFormat:@"Successfully posted '%@'.", message];
        NSString *postId = [resultDict valueForKey:@"id"];
        if (!postId) {
            postId = [resultDict valueForKey:@"postId"];
        }
        if (postId) {
            alertMsg = [NSString stringWithFormat:@"%@\nPost ID: %@", alertMsg, postId];
        }
        alertTitle = @"Success";
    }
    
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:alertTitle
                                                        message:alertMsg
                                                       delegate:nil
                                              cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil];
    [alertView show];
}


@end
