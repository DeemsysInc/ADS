//
//  MiramaxViewController.h
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "UnifeyeMobileViewController.h"
#import "MBProgressHUD.h"

@interface MiramaxViewController : UnifeyeMobileViewController <UIActionSheetDelegate,UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIScrollViewDelegate>

@property (nonatomic, strong) UIImageView *imageView;
@property (nonatomic, strong) IBOutlet UIView *aboutView;
@property (nonatomic, strong) IBOutlet UIView *tutorialView;
@property (nonatomic, weak) NSTimer *characterTimer;
@property (nonatomic, weak) IBOutlet UIButton *captureButton,*triggerButton;
@property (nonatomic, weak) IBOutlet UIView *gestureView;
@property (nonatomic, weak) IBOutlet UILabel *helpMessageLabel;
@property (nonatomic, weak) IBOutlet UIImageView *shapeImageView;
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
- (IBAction)miramaxLinkClicked:(id)sender;
- (IBAction)fbMiramaxLinkClicked:(id)sender;
- (IBAction)twitterLinkClicked:(id)sender;
- (void)setViewFrameCustom:(CGRect)viewFrame;
- (void)checkProgressIndicator;

@end
