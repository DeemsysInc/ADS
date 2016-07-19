//
//  HockeyDemoAppDelegate.m
//  HockeyDemo
//
//  Created by Andreas Linde on 8/22/10.
//  Copyright Andreas Linde 2010. All rights reserved.
//

#import "HockeyDemoAppDelegate.h"
#import "HockeyDemoViewController.h"

@implementation HockeyDemoAppDelegate

@synthesize window;
@synthesize viewController;
@synthesize navigationController;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
  
  // 4.x property
  if ([window respondsToSelector:@selector(setRootViewController:)]) {
    [window setRootViewController:navigationController];
  } else {
    [window addSubview:navigationController.view];
  }
  [window makeKeyAndVisible];
  
  // This variable is available if you add "CONFIGURATION_$(CONFIGURATION)"
  // to the Preprocessor Macros in the project settings to all configurations
#if !defined (CONFIGURATION_AppStore_Distribution)
  // Add these two lines if you want to activate the authorization feature
  //    [BWHockeyManager sharedHockeyManager].requireAuthorization = YES;
  //    [BWHockeyManager sharedHockeyManager].authenticationSecret = @"ChangeThisToYourOwnSecretString";
  [BWHockeyManager sharedHockeyManager].updateURL = @"http://alpha.buzzworks.de";
  [BWHockeyManager sharedHockeyManager].delegate = self;
  
  // optionally enable logging to get more information about states.
  [BWHockeyManager sharedHockeyManager].loggingEnabled = YES;
#endif
  
  return YES;
}

- (void)dealloc {
  [viewController release];
  [window release];
  [super dealloc];
}

#pragma mark -
#pragma mark BWHockeyControllerDelegate

-(NSString *)customDeviceIdentifier {
#if !defined (CONFIGURATION_AppStore_Distribution)
  if ([[UIDevice currentDevice] respondsToSelector:@selector(uniqueIdentifier)])
    return [[UIDevice currentDevice] performSelector:@selector(uniqueIdentifier)];
#endif
  return nil;
}

- (void)connectionOpened {
  [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
}

- (void)connectionClosed {
  [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}


@end
