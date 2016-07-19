//
//  SocialManager.m
//  Seemore
//
//  Created by Vishal Patel on 24/07/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import "SocialManager.h"

@implementation SocialManager
@synthesize facebook = _facebook;
@synthesize fbPermissions = _fbPermissions;
@synthesize isFBConnected = _isFBConnected;
@synthesize fbSessionDelegate = _fbSessionDelegate;
@synthesize fbRequestDelegate = _fbRequestDelegate;

static SocialManager *socialInstance = nil;

+ (SocialManager *) getInstance {
    @synchronized(self) {
        if (socialInstance == nil) {
            socialInstance = [[self alloc] init];
        }
    }
    return socialInstance;
}


- (void) initialize {
}

- (id)init {
    self = [super init];
    if (self) {
        [self initialize];
    }
    return self;
}

- (void)fbCheckForPreviouslySavedAccessTokenInfo {
    // Initially set the isConnected value to NO.
    NSLog(@"checkForPreviouslySavedAccessTokenInfo");
    self.isFBConnected = NO;
    
    // Check if there is a previous access token key in the user defaults file.
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults objectForKey:@"FBAccessTokenKey"] &&
        [defaults objectForKey:@"FBExpirationDateKey"]) {
        self.facebook.accessToken = [defaults objectForKey:@"FBAccessTokenKey"];
        self.facebook.expirationDate = [defaults objectForKey:@"FBExpirationDateKey"];
        
        // Check if the facebook session is valid.
        // If it’s not valid clear any authorization and mark the status as not connected.
        if (![self.facebook isSessionValid]) {
            [self.facebook authorize:nil];
            self.isFBConnected = NO;
        }
        else {
            self.isFBConnected = YES;
        }
    }
    NSLog(@"isFBConnected=%d",self.isFBConnected);
}

- (void)fbSaveAccessTokenKeyInfo {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setObject:[self.facebook accessToken] forKey:@"FBAccessTokenKey"];
    [defaults setObject:[self.facebook expirationDate] forKey:@"FBExpirationDateKey"];
    [defaults synchronize];
}

- (void)fbRemoveAccessTokenKeyInfo {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults removeObjectForKey:@"FBAccessTokenKey"];
    [defaults removeObjectForKey:@"FBExpirationDateKey"];
    [defaults synchronize];
}

- (void)initFacebook {
    // Set the permissions. Without specifying permissions the access to Facebook is imposibble.
    self.fbPermissions = [[NSArray alloc] initWithObjects:
                          @"user_about_me",
                          @"user_hometown",
                          @"user_location",
                          @"read_stream",
                          @"publish_stream",
                          @"photo_upload",
                          @"read_friendlists",
                          @"friends_about_me",
                          @"email",
                          nil];
    
    // Set the Facebook object we declared. We’ll use the declared object from the application delegate
    self.facebook = [[Facebook alloc] initWithAppId:FACEBOOK_APP_ID andDelegate:self.fbSessionDelegate];
    
    // Check if there is a stored access token.
    [self fbCheckForPreviouslySavedAccessTokenInfo];
}

@end
