//
//  MiramaxViewController.h
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//
#import <UIKit/UIKit.h>
#import "MetaioSDKViewController.h"
#import "MBProgressHUD.h"
#import "Option.h"
#import <MediaPlayer/MediaPlayer.h>
#import <MessageUI/MFMessageComposeViewController.h>
#import <MessageUI/MFMailComposeViewController.h>
#import "SocialManager.h"
//#import "FBConnect.h"
//#import "Facebook.h"
#import "SocialManager.h"
#import <Twitter/Twitter.h>
#import <FacebookSDK/FacebookSDK.h>

@interface MiramaxViewController : MetaioSDKViewController <UIActionSheetDelegate,UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIScrollViewDelegate,FBRequestDelegate, MFMessageComposeViewControllerDelegate, MFMailComposeViewControllerDelegate>  {
    
    Option *currentSelectedOption;
     UIView* videoHolderView;
    IBOutlet UIButton *playerStop;
    IBOutlet UIButton *playerPlay;
    IBOutlet UIButton *playerPause;
    UIImage *playerButtonImage;
    UIImage *pauseButtonImage;
    UIImage *stopButtonImage;
    IBOutlet UIButton *additionalCloseButton;
    IBOutlet UIButton *webUrlBackButton;
    IBOutlet UIImageView *additionalBackgroundImage;
    IBOutlet UIView *videoShareButtonsView;
    IBOutlet UIView *videoShareFacebookLoginView;
}
- (IBAction)facebookButtonClicked:(id)sender;
@property(nonatomic, strong) MPMoviePlayerController * moviePlayer;
@property(nonatomic, strong) MPMoviePlayerController * moviePlayerController;
@property (nonatomic, strong) UIImageView *imageView;
@property (nonatomic, strong) IBOutlet UIView *aboutView;
@property (nonatomic, strong) IBOutlet UIScrollView *aboutScrollView;
@property (nonatomic, strong) IBOutlet UIView *aboutContentView;
@property (nonatomic, strong) IBOutlet UIView *termsOfUseView;
@property (nonatomic, strong) IBOutlet UIScrollView *termsOfUseScrollView;
@property (nonatomic, strong) IBOutlet UIView *termsOfUseContentViewOne;
@property (nonatomic, strong) IBOutlet UIView *termsOfUseContentViewTwo;
@property (nonatomic, strong) IBOutlet UIView *tutorialView;
@property (nonatomic, strong) IBOutlet UIScrollView *tutorialScrollView;
@property (nonatomic, strong) IBOutlet UIView *tutorialContentView;
@property (nonatomic, weak) NSTimer *characterTimer;
@property (nonatomic, weak) IBOutlet UIButton *captureButton,*triggerButton;
@property (nonatomic, weak) IBOutlet UIButton *videoButton, *productLinkButton;
@property (nonatomic, weak) IBOutlet UIView *gestureView;
@property (nonatomic, weak) IBOutlet UILabel *helpMessageLabel;
@property (nonatomic, weak) IBOutlet UIImageView *shapeImageView;
@property (nonatomic, weak) IBOutlet UISlider *shapeSlider;
@property (nonatomic, strong) NSArray *posterData;
@property (nonatomic, strong) UIActionSheet *actionSheet;
@property CGRect viewFrame;
@property BOOL animationFlag;
@property (nonatomic, strong) IBOutlet UIButton *openOptionsButton;
@property (nonatomic, strong) IBOutlet UIView *optionView;
@property (nonatomic, strong) IBOutlet UIButton *optionOneButton;
@property (nonatomic, strong) IBOutlet UIButton *optionTwoButton;
@property (strong, nonatomic) IBOutlet UIButton *scanTestimonials;
@property (strong, nonatomic) IBOutlet UIButton *contactTrigger;
@property (strong, nonatomic) IBOutlet UIButton *shareOptionButton;

@property (nonatomic, strong) IBOutlet UIView *homeView;
@property (nonatomic, strong) IBOutlet UIView *webURLView;
@property (strong, nonatomic) IBOutlet UIWebView *webURLWebView;
@property (strong, nonatomic) IBOutlet UIView *welcomeMoreView;
@property (strong, nonatomic) IBOutlet UIView *videoShareView;

@property BOOL didNotSwipe;

- (IBAction)scanTestimonialsClicked:(id)sender;
- (IBAction)contactUsClicked:(id)sender;
- (IBAction)backToHomeView:(id)sender;
- (IBAction)eventButtonClicked:(id)sender;
- (IBAction)aboutButtonClicked:(id)sender;
- (IBAction)termsButtonClicked:(id)sender;

- (IBAction)captureImageButtonClicked:(id)sender;
- (IBAction)videoButtonClicked:(id)sender;
- (IBAction)productLinkButtonClicked:(id)sender;
- (IBAction)triggerImageButtonClicked:(id)sender;
- (IBAction)backTutorialButtonClicked:(id)sender;
- (IBAction)homeAboutButtonClicked:(id)sender;
- (IBAction)termsOfUseButtonClicked:(id)sender;
- (IBAction)sliderValueChanged:(id)sender;
- (IBAction)seemoreLinkClicked:(id)sender;
- (IBAction)metaioLinkClicked:(id)sender;
- (IBAction)miramaxLinkClicked:(id)sender;
- (IBAction)fbMiramaxLinkClicked:(id)sender;
- (IBAction)twitterLinkClicked:(id)sender;
- (IBAction)moreButtonClicked:(id)sender;
- (IBAction)moreBackButtonClicked:(id)sender;
- (IBAction)podcastButtonClicked:(id)sender;
- (IBAction)webcastsButtonClicked:(id)sender;
- (IBAction)HCMButtonClicked:(id)sender;
- (IBAction)videoShareButtonClicked:(id)sender;
- (IBAction)shareFacebookButtonClicked:(id)sender;
- (IBAction)shareEmailButtonClicked:(id)sender;
- (IBAction)shareTwitterButtonClicked:(id)sender;
- (IBAction)shareCancelButtonClicked:(id)sender;

- (void)setViewFrameCustom:(CGRect)viewFrame;
- (void)checkProgressIndicator;
- (IBAction)clearOptions:(id)sender;
- (void)closeMediaPlayerController;
- (void) stopPlayingVideo:(NSNotification*)aNotification;

- (IBAction)optionViewToggleButtonClicked:(id)sender;
- (IBAction)optionButtonClicked:(id)sender;
- (IBAction)testButtonClicked:(id)sender;

@end
