//
//  ShareViewController.h
//  Miramax
//
//  Created by Admin on 09/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MessageUI/MFMessageComposeViewController.h>
#import <MessageUI/MFMailComposeViewController.h>
#import "SocialManager.h"
#import "MBProgressHUD.h"
//#import "FBConnect.h"
//#import "Facebook.h"
#import "SocialManager.h"
#import <Twitter/Twitter.h>

@interface ShareViewController : UIViewController <MFMessageComposeViewControllerDelegate, MFMailComposeViewControllerDelegate>{
IBOutlet UIView *videoShareButtonsView;
}

@property (nonatomic, strong) IBOutlet UIImageView *imageView;
@property (nonatomic, strong) UIImage *image;

- (IBAction)backButtonClicked:(id)sender;
- (IBAction)saveImageButtonClicked:(id)sender;
- (IBAction)facebookButtonClicked:(id)sender;
- (IBAction)twitterButtonClicked:(id)sender;
- (IBAction)imButtonClicked:(id)sender;
- (IBAction)emailButtonClicked:(id)sender;

@end
