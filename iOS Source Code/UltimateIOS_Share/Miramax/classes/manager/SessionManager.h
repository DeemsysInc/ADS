//
//  SessionManager.h
//  Seemore
//
//  Created by Vishal Patel on 11/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <Foundation/Foundation.h>


@interface SessionManager : NSObject

@property(nonatomic, strong) NSMutableArray *posterVisual;
@property(nonatomic, strong) NSMutableArray *markerDataArray;
@property(nonatomic, strong) NSMutableArray *markersFromPList;
@property BOOL iPhoneContext;

+ (SessionManager *) getInstance;

- (BOOL) hasValue: (id) field;
- (NSArray *) getPosterMarkerData;
- (NSArray *) getPosterMarkerArray;
- (NSArray *) getPosterVisualImagePath;
- (void) downloadedImageURL:(NSString *)visualImageUrl;
- (void) storeMarkerDataIntoArray:(NSDictionary *)dictionary;
- (NSDictionary *) markerInfo: (int)markerId;

@end
