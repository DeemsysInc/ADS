//
//  MiramaxViewController.h
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "UnifeyeMobileViewController.h"
#import "MBProgressHUD.h"
#import "Option.h"

#import <UIKit/UIKit.h>
#import "CustomCell.h"


@interface MiramaxViewController : UnifeyeMobileViewController <UIActionSheetDelegate,UINavigationControllerDelegate,UIScrollViewDelegate,UITableViewDataSource, UITableViewDelegate>  {
    
    Option *currentSelectedOption;
    IBOutlet UIButton *playerStop;
    IBOutlet UIButton *playerPlay;
    IBOutlet UIButton *playerPause;
    UIImage *playerButtonImage;
    UIImage *pauseButtonImage;
    UIImage *stopButtonImage;
    BOOL isOfferVisible;
    IBOutlet UIImageView *offerImage;

    IBOutlet UIImageView *DealOfferImage;

    IBOutlet UIImageView *welcomeImage;
    IBOutlet UIButton *offerNoThanks;
    IBOutlet UIButton *offerAddToOffers;
    NSArray *myDataArray;
    IBOutlet UILabel *myLabel;
    IBOutlet UITableView *myTableView;
    IBOutlet UIButton *welcomeSnapButton;
    IBOutlet UIImageView *snapImageView;
}
@property (assign, nonatomic) IBOutlet CustomCell *customCell;
@property (strong, nonatomic) IBOutlet UITableView *tableView;
- (IBAction)welcomeSnapButtonClicked:(id)sender;
@property (strong, nonatomic) IBOutlet UIView *welcomeView;

@property (strong, nonatomic) IBOutlet UIView *creditAppView;
@property (strong, nonatomic) IBOutlet UIView *dealsView;
@property (strong, nonatomic) IBOutlet UIView *dealsDetailView;


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
@property (strong, nonatomic) IBOutlet UIButton *creditbackbutton;
@property (strong, nonatomic) IBOutlet UIButton *dealsDetailBackButton;

- (IBAction)captureImageButtonClicked:(id)sender;
- (IBAction)videoButtonClicked:(id)sender;
- (IBAction)productLinkButtonClicked:(id)sender;
- (IBAction)triggerImageButtonClicked:(id)sender;
- (IBAction)backTutorialButtonClicked:(id)sender;
- (IBAction)aboutButtonClicked:(id)sender;
- (IBAction)termsOfUseButtonClicked:(id)sender;
- (IBAction)sliderValueChanged:(id)sender;
- (IBAction)seemoreLinkClicked:(id)sender;
- (IBAction)metaioLinkClicked:(id)sender;
- (IBAction)miramaxLinkClicked:(id)sender;
- (IBAction)fbMiramaxLinkClicked:(id)sender;

- (IBAction)twitterLinkClicked:(id)sender;
- (void)setViewFrameCustom:(CGRect)viewFrame;
- (void)checkProgressIndicator;
- (IBAction)clearOptions:(id)sender;
- (void)closeMediaPlayerController;
- (void) stopPlayingVideo:(NSNotification*)aNotification;
- (IBAction)activateImageScanAlgorithm:(id)sender;
- (IBAction)activateTagScanAlgorithm:(id)sender;
- (IBAction)continueTutorialClicked:(id)sender;

- (IBAction)optionViewToggleButtonClicked:(id)sender;
- (IBAction)optionButtonClicked:(id)sender;
- (IBAction)testButtonClicked:(id)sender;

- (IBAction)creditAppBackClicked:(id)sender;
- (IBAction)dealAppBackClicked:(id)sender;

- (IBAction)dealDetailBackClicked:(id)sender;

@end
