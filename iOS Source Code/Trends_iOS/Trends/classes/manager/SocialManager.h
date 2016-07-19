//
//  SocialManager.h
//  Seemore
//
//  Created by Vishal Patel on 24/07/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "FBConnect.h"
#import "Facebook.h"
#import "ObjectiveFlickr.h"

@interface SocialManager : NSObject

@property(nonatomic, strong) Facebook *facebook;
@property(nonatomic, strong) NSArray *fbPermissions;
@property BOOL isFBConnected;
@property(nonatomic, strong) id<FBRequestDelegate> fbRequestDelegate;
@property(nonatomic, strong) id<FBSessionDelegate> fbSessionDelegate;

+ (SocialManager *) getInstance;

- (void)fbCheckForPreviouslySavedAccessTokenInfo;
- (void)fbSaveAccessTokenKeyInfo;
- (void)fbRemoveAccessTokenKeyInfo;
- (void)initFacebook;

@end
