//
//  TrendsAppDelegate.m
//  Trends
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "TrendsAppDelegate.h"
#import "SocialManager.h"
#import "TrendsViewController.h"
#import "Constant.h"

@implementation TrendsAppDelegate

@synthesize window = _window;
@synthesize navController = _navController;
@synthesize flickrUserName = _flickrUserName;
@synthesize flickrRequest = _flickrRequest;
@synthesize flickrContext = _flickrContext;
//@synthesize socialManager = _socialManager;

- (id)init
{
    self = [super init];
    if (self) {
        [self setupFlickrContext];
        [self setupFlickrRequest];
    }
    return self;
}

- (void) initFacebook {
    SocialManager *socialManager = [SocialManager getInstance];
    [socialManager setFbSessionDelegate:self];
    [socialManager initFacebook];
    
    NSLog(@"AppId = %@",FACEBOOK_APP_ID);
    if (!FACEBOOK_APP_ID) {
        UIAlertView *alertView = [[UIAlertView alloc]
                                  initWithTitle:@"Setup Error"
                                  message:@"Missing app ID. You cannot run the app until you provide this in the code."
                                  delegate:self
                                  cancelButtonTitle:@"OK"
                                  otherButtonTitles:nil,
                                  nil];
        [alertView show];
    } else {
        // Now check that the URL scheme fb[app_id]://authorize is in the .plist and can
        // be opened, doing a simple check without local app id factored in here
        NSString *url = [[NSString alloc] initWithFormat:@"fb%@://authorize",FACEBOOK_APP_ID];
        BOOL bSchemeInPlist = NO; // find out if the sceme is in the plist file.
        NSArray* aBundleURLTypes = [[NSBundle mainBundle] objectForInfoDictionaryKey:@"CFBundleURLTypes"];
        if ([aBundleURLTypes isKindOfClass:[NSArray class]] &&
            ([aBundleURLTypes count] > 0)) {
            NSDictionary* aBundleURLTypes0 = [aBundleURLTypes objectAtIndex:0];
            if ([aBundleURLTypes0 isKindOfClass:[NSDictionary class]]) {
                NSArray* aBundleURLSchemes = [aBundleURLTypes0 objectForKey:@"CFBundleURLSchemes"];
                if ([aBundleURLSchemes isKindOfClass:[NSArray class]] &&
                    ([aBundleURLSchemes count] > 0)) {
                    NSString *scheme = [aBundleURLSchemes objectAtIndex:0];
                    if ([scheme isKindOfClass:[NSString class]] &&
                        [url hasPrefix:scheme]) {
                        bSchemeInPlist = YES;
                    }
                }
            }
        }
        // Check if the authorization callback will work
        BOOL bCanOpenUrl = [[UIApplication sharedApplication] canOpenURL:[[NSURL alloc] initWithString: url]];
        if (!bSchemeInPlist || !bCanOpenUrl) {
            UIAlertView *alertView = [[UIAlertView alloc]
                                      initWithTitle:@"Facebook Setup Error"
                                      message:@"Invalid or missing URL scheme. You cannot run the app until you set up a valid URL scheme in your .plist."
                                      delegate:self
                                      cancelButtonTitle:@"OK"
                                      otherButtonTitles:nil,
                                      nil];
            [alertView show];
        }
    }
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
//    self.window = [[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]];
//    TrendsViewController *homeViewController = [[TrendsViewController alloc] init];
//    self.navController = [[UINavigationController alloc] initWithRootViewController:self.window.rootViewController];
//    self.navController.delegate = self;
//    [self.navController setNavigationBarHidden:YES animated:NO];
//    self.window.rootViewController = self.navController;
//    [self.window makeKeyAndVisible];
    
//    [self flickrLogin];
    [NSThread sleepForTimeInterval:2];
    [self initFacebook];
    
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
    [[[SocialManager getInstance] facebook] extendAccessTokenIfNeeded];
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

#pragma mark - flickr
+ (TrendsAppDelegate *)sharedDelegate
{
    return (TrendsAppDelegate *)[[UIApplication sharedApplication] delegate];
}

- (OFFlickrAPIRequest *)setupFlickrRequest
{
	if (!self.flickrRequest) {
		self.flickrRequest = [[OFFlickrAPIRequest alloc] initWithAPIContext:self.flickrContext];
		self.flickrRequest.delegate = self;
	}
	return self.flickrRequest;
}

NSDictionary *OFExtractURLQueryParameter(NSString *inQuery)
{
    if (![inQuery length]) {
        return nil;
    }
    
    NSArray *params = [inQuery componentsSeparatedByString:@"&"];
    
    NSMutableDictionary *dict = [NSMutableDictionary dictionary];
    for (NSString *p in params) {
        NSArray *kv = [p componentsSeparatedByString:@"="];
        if ([kv count] != 2) {
            return nil;
        }
        
        [dict setObject:[kv objectAtIndex:1] forKey:[kv objectAtIndex:0]];
    }
    return dict;
}

NSDictionary *OFExtractOAuthCallbackCustom(NSURL *inReceivedURL, NSURL *inBaseURL)
{
    NSString *ruStr = [inReceivedURL absoluteString];
    NSString *buStr = [[inBaseURL absoluteString] stringByAppendingString:@"?"];
    
    if (![ruStr hasPrefix:buStr]) {
        return nil;
    }
    
    NSString *query = [ruStr substringFromIndex:[buStr length]];
    if (![query length]) {
        return nil;
    }
    
    NSDictionary *dict = OFExtractURLQueryParameter(query);
    return dict;
}

- (BOOL)application:(UIApplication *)application openURL:(NSURL *)url sourceApplication:(NSString *)sourceApplication annotation:(id)annotation {
    NSRange range = [url.absoluteString rangeOfString:@"oauth_token" options:NSCaseInsensitiveSearch];
    if(range.location != NSNotFound) {
        if ([self flickrRequest].sessionInfo) {
            NSLog(@"Already running some other request");
            } else {
                NSDictionary *dict = OFExtractOAuthCallbackCustom(url, [NSURL URLWithString:kTrendsCallbackURLBaseString]);
                NSString *token = [dict objectForKey:@"oauth_token"];
                NSString *verifier = [dict objectForKey:@"oauth_verifier"];
                NSLog(@"AbsoluteURL: %@", [url absoluteString]);
                if (token.length==0 && verifier.length==0) {
                    NSLog(@"Cannot obtain token/secret from URL: %@", [url absoluteString]);
                    return NO;
                    }
                
                [self flickrRequest].sessionInfo = kGetAccessTokenStep;
                [self.flickrRequest fetchOAuthAccessTokenWithRequestToken:token verifier:verifier];
                
                [self setAndStoreFlickrAuthToken:token secret:verifier];
                }
        } else {
            [[NSNotificationCenter defaultCenter] postNotificationName:@"PostFaceBookAlert" object:nil];
            return [[[SocialManager getInstance] facebook] handleOpenURL:url];
            }
   
    return YES;
}

- (void) flickrLogin {
    if ([self.flickrContext.OAuthToken length]) {
		[self flickrRequest].sessionInfo = kCheckTokenStep;
		[self.flickrRequest callAPIMethodWithGET:@"flickr.test.login" arguments:nil];
	}
}

- (void)cancelAction
{
	[self.flickrRequest cancel];
	[self setAndStoreFlickrAuthToken:nil secret:nil];
	[[NSNotificationCenter defaultCenter] postNotificationName:kTrendsShouldUpdateAuth object:self];
}

- (void)setAndStoreFlickrAuthToken:(NSString *)inAuthToken secret:(NSString *)inSecret
{
	if (![inAuthToken length] || ![inSecret length]) {
		self.flickrContext.OAuthToken = nil;
        self.flickrContext.OAuthTokenSecret = nil;
		[[NSUserDefaults standardUserDefaults] removeObjectForKey:kStoredAuthTokenKeyName];
        [[NSUserDefaults standardUserDefaults] removeObjectForKey:kStoredAuthTokenSecretKeyName];
        
	}
	else {
		self.flickrContext.OAuthToken = inAuthToken;
        self.flickrContext.OAuthTokenSecret = inSecret;
		[[NSUserDefaults standardUserDefaults] setObject:inAuthToken forKey:kStoredAuthTokenKeyName];
		[[NSUserDefaults standardUserDefaults] setObject:inSecret forKey:kStoredAuthTokenSecretKeyName];
	}
}

- (OFFlickrAPIContext *) setupFlickrContext
{
    if (!self.flickrContext) {
        self.flickrContext = [[OFFlickrAPIContext alloc] initWithAPIKey:kFlickrAPIKey sharedSecret:kFlickrSecretKey];
        
        NSString *authToken = [[NSUserDefaults standardUserDefaults] objectForKey:kStoredAuthTokenKeyName];
        NSString *authTokenSecret = [[NSUserDefaults standardUserDefaults] objectForKey:kStoredAuthTokenSecretKeyName];
        
        if (([authToken length] > 0) && ([authTokenSecret length] > 0)) {
            self.flickrContext.OAuthToken = authToken;
            self.flickrContext.OAuthTokenSecret = authTokenSecret;
        }
    }
    
    return self.flickrContext;
}

#pragma mark OFFlickrAPIRequest delegate methods
- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didObtainOAuthAccessToken:(NSString *)inAccessToken secret:(NSString *)inSecret userFullName:(NSString *)inFullName userName:(NSString *)inUserName userNSID:(NSString *)inNSID
{
    [self setAndStoreFlickrAuthToken:inAccessToken secret:inSecret];
    self.flickrUserName = inUserName;
    
	[[NSNotificationCenter defaultCenter] postNotificationName:kTrendsShouldUpdateAuth object:self];
    [self flickrRequest].sessionInfo = nil;
}

- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didCompleteWithResponse:(NSDictionary *)inResponseDictionary
{
    NSLog(@"Flickr didCompleteWithResponse=%@", inResponseDictionary);
    if (inRequest.sessionInfo == kCheckTokenStep) {
		self.flickrUserName = [inResponseDictionary valueForKeyPath:@"user.username._text"];
	}
	
	[[NSNotificationCenter defaultCenter] postNotificationName:kTrendsShouldUpdateAuth object:self];
    [self flickrRequest].sessionInfo = nil;
}

- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didFailWithError:(NSError *)inError
{
    NSLog(@"inRequest=%@", inRequest.JSONRepresentation);
    NSLog(@"inRequest.sessionInfo=%@", inRequest.sessionInfo);
    NSLog(@"inError=%@", inError);
	if (inRequest.sessionInfo == kGetAccessTokenStep) {
	}
	else if (inRequest.sessionInfo == kCheckTokenStep) {
		[self setAndStoreFlickrAuthToken:nil secret:nil];
	}
	
	[[[UIAlertView alloc] initWithTitle:@"API Failed" message:[inError description] delegate:nil cancelButtonTitle:@"Dismiss" otherButtonTitles:nil] show];
	[[NSNotificationCenter defaultCenter] postNotificationName: kTrendsShouldUpdateAuth object:self];
}

#pragma mark - 
/**
 * Called when the user successfully logged in.
 */
- (void)fbDidLogin {
    NSLog(@"fbDidLogin.");
    [[SocialManager getInstance] fbSaveAccessTokenKeyInfo];
}

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
    [[SocialManager getInstance] fbRemoveAccessTokenKeyInfo];
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
