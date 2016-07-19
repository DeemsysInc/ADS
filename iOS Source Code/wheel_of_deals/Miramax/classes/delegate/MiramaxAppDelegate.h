//
//  MiramaxAppDelegate.h
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"
#import "Facebook.h"
#import "SocialManager.h"

@class MiramaxViewController;

@interface MiramaxAppDelegate : UIResponder <UIApplicationDelegate,UINavigationControllerDelegate, FBSessionDelegate>

@property (strong, nonatomic) IBOutlet UIWindow *window;

+ (MiramaxAppDelegate *)sharedDelegate;

@end
