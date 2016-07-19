//
//  MiramaxAppDelegate.m
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "MiramaxAppDelegate.h"
#import "SocialManager.h"
#import "MiramaxViewController.h"
#import "Constant.h"
#import <FacebookSDK/FacebookSDK.h>

@implementation MiramaxAppDelegate

@synthesize window = _window;

- (id)init
{
    self = [super init];
    return self;
}

//- (void) initFacebook {
//    SocialManager *socialManager = [SocialManager getInstance];
//    [socialManager setFbSessionDelegate:self];
//    [socialManager initFacebook];
//    
//    NSLog(@"AppId = %@",FACEBOOK_APP_ID);
//    if (!FACEBOOK_APP_ID) {
//        UIAlertView *alertView = [[UIAlertView alloc]
//                                  initWithTitle:@"Setup Error"
//                                  message:@"Missing app ID. You cannot run the app until you provide this in the code."
//                                  delegate:self
//                                  cancelButtonTitle:@"OK"
//                                  otherButtonTitles:nil,
//                                  nil];
//        [alertView show];
//    } else {
//        // Now check that the URL scheme fb[app_id]://authorize is in the .plist and can
//        // be opened, doing a simple check without local app id factored in here
//        NSString *url = [[NSString alloc] initWithFormat:@"fb%@://authorize",FACEBOOK_APP_ID];
//        BOOL bSchemeInPlist = NO; // find out if the sceme is in the plist file.
//        NSArray* aBundleURLTypes = [[NSBundle mainBundle] objectForInfoDictionaryKey:@"CFBundleURLTypes"];
//        if ([aBundleURLTypes isKindOfClass:[NSArray class]] &&
//            ([aBundleURLTypes count] > 0)) {
//            NSDictionary* aBundleURLTypes0 = [aBundleURLTypes objectAtIndex:0];
//            if ([aBundleURLTypes0 isKindOfClass:[NSDictionary class]]) {
//                NSArray* aBundleURLSchemes = [aBundleURLTypes0 objectForKey:@"CFBundleURLSchemes"];
//                if ([aBundleURLSchemes isKindOfClass:[NSArray class]] &&
//                    ([aBundleURLSchemes count] > 0)) {
//                    NSString *scheme = [aBundleURLSchemes objectAtIndex:0];
//                    if ([scheme isKindOfClass:[NSString class]] &&
//                        [url hasPrefix:scheme]) {
//                        bSchemeInPlist = YES;
//                    }
//                }
//            }
//        }
//        // Check if the authorization callback will work
//        BOOL bCanOpenUrl = [[UIApplication sharedApplication] canOpenURL:[[NSURL alloc] initWithString: url]];
//        if (!bSchemeInPlist || !bCanOpenUrl) {
//            UIAlertView *alertView = [[UIAlertView alloc]
//                                      initWithTitle:@"Facebook Setup Error"
//                                      message:@"Invalid or missing URL scheme. You cannot run the app until you set up a valid URL scheme in your .plist."
//                                      delegate:self
//                                      cancelButtonTitle:@"OK"
//                                      otherButtonTitles:nil,
//                                      nil];
//            [alertView show];
//        }
//    }
//}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    [NSThread sleepForTimeInterval:2];
    //[self initFacebook];
    [self.window makeKeyAndVisible];
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
    //[[[SocialManager getInstance] facebook] extendAccessTokenIfNeeded];
    [FBAppCall handleDidBecomeActive];
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
    [FBSession.activeSession close];
}

- (void)applicationDidFinishLaunching:(UIApplication *)application
{
    NSSetUncaughtExceptionHandler(&myExceptionHandler);
}
void myExceptionHandler(NSException *exception)
{
    NSArray *stack = [exception callStackReturnAddresses];
    NSLog(@"Stack trace: %@", stack);
}
- (BOOL)application:(UIApplication *)application openURL:(NSURL *)url sourceApplication:(NSString *)sourceApplication annotation:(id)annotation {
//    [[NSNotificationCenter defaultCenter] postNotificationName:@"PostFaceBookAlert" object:nil];
//    return [[[SocialManager getInstance] facebook] handleOpenURL:url];
    return [FBAppCall handleOpenURL:url
                  sourceApplication:sourceApplication
                    fallbackHandler:^(FBAppCall *call) {
                        NSLog(@"In fallback handler");
                    }];
}

-(BOOL)application:(UIApplication *)application handleOpenURL:(NSURL *)url {
    

}
#pragma mark -
/**
 * Called when the user successfully logged in.
 */
//- (void)fbDidLogin {
//    NSLog(@"fbDidLogin.");
//    [[SocialManager getInstance] fbSaveAccessTokenKeyInfo];
//}

/**
 * Called when the user dismissed the dialog without logging in.
 */
- (void)fbDidNotLogin:(BOOL)cancelled {
    NSLog(@"fbDidNotLogin.");
}

/**
 * Called after the access token was extended. If your application has any
 * references to the previous access token (for example, if your application
 * stores the previous access token in persistent storage), your application
 * should overwrite the old access token with the new one in this method.
 * See extendAccessToken for more details.
 */
- (void)fbDidExtendToken:(NSString*)accessToken
               expiresAt:(NSDate*)expiresAt {
    NSLog(@"fbDidExtendToken.");
}

/**
 * Called when the user logged out.
 */
- (void)fbDidLogout {
    NSLog(@"fbDidLogout.");
   // [[SocialManager getInstance] fbRemoveAccessTokenKeyInfo];
}

/**
 * Called when the current session has expired. This might happen when:
 *  - the access token expired
 *  - the app has been disabled
 *  - the user revoked the app's permissions
 *  - the user changed his or her password
 */
- (void)fbSessionInvalidated {
    NSLog(@"fbSessionInvalidated.");
}

@end
