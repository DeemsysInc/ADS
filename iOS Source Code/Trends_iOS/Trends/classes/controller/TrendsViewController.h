//
//  TrendsViewController.h
//  Trends
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "MetaioSDKViewController.h"
#import "MBProgressHUD.h"
#import <metaioSDK/GestureHandlerIOS.h>

@interface TrendsViewController : MetaioSDKViewController <UIActionSheetDelegate,UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIScrollViewDelegate>{
    IBOutlet UIButton *tryNowButton;
}

@property (nonatomic, strong) UIImageView *imageView;
@property (nonatomic, strong) IBOutlet UIView *aboutView;
@property (nonatomic, strong) IBOutlet UIView *aboutViewiPhone5;
@property (nonatomic, strong) IBOutlet UIView *tutorialView;
@property (nonatomic, strong) IBOutlet UIView *tutorialViewiPhone5;
@property (nonatomic, weak) NSTimer *characterTimer;
@property (nonatomic, weak) IBOutlet UIButton *captureButton,*triggerButton, *questionButton;
@property (nonatomic, weak) IBOutlet UIView *gestureView;
@property (nonatomic, weak) IBOutlet UILabel *helpMessageLabel;
@property (nonatomic, weak) IBOutlet UIImageView *shapeImageView;
@property (nonatomic) BOOL shoulddisplayLegalImage;
@property (nonatomic, retain) NSString *legalImageFilePath;
@property (nonatomic, weak) IBOutlet UISlider *shapeSlider;
@property (nonatomic, strong) NSArray *posterData;
@property (nonatomic, strong) UIActionSheet *actionSheet;
@property CGRect viewFrame;
@property BOOL animationFlag;

- (IBAction)captureImageButtonClicked:(id)sender;
- (IBAction)triggerImageButtonClicked:(id)sender;
- (IBAction)backTutorialButtonClicked:(id)sender;
- (IBAction)aboutButtonClicked:(id)sender;
- (IBAction)sliderValueChanged:(id)sender;
- (IBAction)seemoreLinkClicked:(id)sender;
- (IBAction)metaioLinkClicked:(id)sender;
- (IBAction)trendsLinkClicked:(id)sender;
- (IBAction)fbTrendsLinkClicked:(id)sender;
- (IBAction)twitterLinkClicked:(id)sender;
- (void)setViewFrameCustom:(CGRect)viewFrame;
- (void)checkProgressIndicator;

@property (nonatomic, weak) IBOutlet UIImageView *bgiPhone;
@property (nonatomic, strong) IBOutlet UIImageView *testImageView;
- (IBAction)tryItNow:(id)sender;
@end
