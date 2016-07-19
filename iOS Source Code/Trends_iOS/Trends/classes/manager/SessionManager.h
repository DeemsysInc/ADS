//
//  SessionManager.h
//  Seemore
//
//  Created by Vishal Patel on 11/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Poster.h"
#import "Marker.h"

@interface SessionManager : NSObject    {
    
    NSMutableArray *markerDataArray;
}

@property(nonatomic, retain) NSMutableArray *markerDataArray;

+ (SessionManager *) getInstance;

// Detect Retina Display
+ (BOOL) isDeviceHaveRetinaDisplay;

- (BOOL) hasValue: (id) field;
- (NSArray *) getPosterMarkerData;
- (NSArray *) getPosterMarkerArray;
- (void) storeMarkerDataIntoArray:(NSArray *)array;

- (Poster *) getPosterWithID:(long)posterID;
- (Marker *) getMarkerWithID:(long)markerID;
- (Poster *) getPosterForMarkerID:(long)markerID;

// Path Generation Methods
- (NSString *) mainBundlePathStringForFileName:(NSString *)fileName ofType:(NSString *)type;
- (NSURL *) mainBundlePathURLForFileName:(NSString *)fileName ofType:(NSString *)type;
- (NSString *) pathStringForFileName:(NSString *)fileName ofType:(NSString *)type;
- (NSURL *) pathURLForFileName:(NSString *)fileName ofType:(NSString *)type;

@end
