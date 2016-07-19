//
//  TrendsAppDelegate.h
//  Trends
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"
#import "Facebook.h"
#import "ObjectiveFlickr.h"
#import "SocialManager.h"

@class TrendsViewController;

@interface TrendsAppDelegate : UIResponder <UIApplicationDelegate,UINavigationControllerDelegate,OFFlickrAPIRequestDelegate, FBSessionDelegate>

@property (strong, nonatomic) IBOutlet UIWindow *window;
@property (strong, nonatomic) OFFlickrAPIContext *flickrContext;
@property (strong, nonatomic) OFFlickrAPIRequest *flickrRequest;

//@property (strong, nonatomic) TrendsViewController *viewController;
@property (strong, nonatomic) IBOutlet UINavigationController *navController;

//@property (strong, nonatomic) SocialManager *socialManager;
@property (nonatomic, retain) NSString *flickrUserName;

+ (TrendsAppDelegate *)sharedDelegate;
- (void)setAndStoreFlickrAuthToken:(NSString *)inAuthToken secret:(NSString *)inSecret;
- (OFFlickrAPIContext *) setupFlickrContext;

@end
