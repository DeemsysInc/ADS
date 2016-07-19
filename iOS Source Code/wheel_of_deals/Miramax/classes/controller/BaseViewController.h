//
//  BaseViewController.h
//  Seemore
//
//  Created by Vishal Patel on 05/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MiramaxAppDelegate.h"
#import "SessionManager.h"

#import "MBProgressHUD.h"

#import "Constant.h"


@interface BaseViewController : UIViewController <MBProgressHUDDelegate, UIActionSheetDelegate>

@property (nonatomic, strong) SessionManager *session;

- (IBAction) logoClicked:(id)sender;

- (MiramaxAppDelegate *) appDelegate;
- (BOOL) hasValue: (id) field;
- (MBProgressHUD *) mbProgressHUD;
- (void) releaseView: (UIView *)view;

- (void) loadProductImage:(NSDictionary *)parameters;
- (void) loadImageWithURL:(NSString *)imageUrl andCallBlock:(void(^)(UIImage *image))block;

//- (void) renderInstructions: (enum INSTRUCTION_PAGE) instructionPage parentView:(UIView *)parentView;
- (void) removeInstruction;

//- (void)startNotifications;
- (void)resignNotifications;

@end
