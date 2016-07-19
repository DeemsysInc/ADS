//
//  SessionManager.m
//  Seemore
//
//  Created by Vishal Patel on 11/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import "SessionManager.h"
#import "PosterMarkerService.h"

@implementation SessionManager

@synthesize posterVisual = _posterVisual;
@synthesize markerDataArray = _markerDataArray;
@synthesize markersFromPList = _markersFromPList;
@synthesize iPhoneContext = _iPhoneContext;

static SessionManager *sessionInstance = nil;

+ (SessionManager *) getInstance {
    @synchronized(self) {
        if (sessionInstance == nil) {
            sessionInstance = [[self alloc] init];
        }
    }
    return sessionInstance;
}

- (void) initDataFromProperList {
    NSString* plistPath = [[NSBundle mainBundle] pathForResource:@"Miramax-Data" ofType:@"plist"];
    NSDictionary *root = [NSDictionary dictionaryWithContentsOfFile:plistPath];
    self.markersFromPList = [root objectForKey:@"Markers"];
}

- (void) initialize {
    self.posterVisual = [[NSMutableArray alloc]init];
    self.markerDataArray = [[NSMutableArray alloc]init];
    [self initDataFromProperList];
}

- (id)init {
    self = [super init];
    if (self) {
        [self initialize];
    }
    return self;
}

- (BOOL) hasValue: (id) field {
    if(field!=nil && (NSNull *)field != [NSNull null]){
        if([field isKindOfClass:[NSString class]]) {
            if([field length]>0) {
                return YES;
            }
        } else {
            return YES;
        }
    }
    return NO;
}

-(NSArray *) getPosterMarkerData {
    return [[PosterMarkerService getInstance] getPosterData];
}

- (NSArray *) getPosterVisualImagePath {
    return self.posterVisual;
}

- (NSArray *) getPosterMarkerArray {
    return self.markerDataArray;
}

- (void) downloadedImageURL:(NSString *)visualImageUrl {
    [self.posterVisual addObject:visualImageUrl];
}

- (void) storeMarkerDataIntoArray:(NSDictionary *)dictionary {
    [self.markerDataArray addObject:dictionary];
}

- (void) loadImageWithURL:(NSString *)imageUrl andCallBlock:(void(^)(UIImage *image))block {
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
        UIImage *image = [[UIImage alloc] initWithData: 
                          [[NSData alloc] initWithContentsOfURL: 
                           [[NSURL alloc] initWithString:imageUrl]]];
        if (block && image.CGImage) {
            block(image);
        }
    });
}

- (NSDictionary *) markerInfo: (int)markerId {
    if([self.markersFromPList count] >= markerId) {
        return [self.markersFromPList objectAtIndex:markerId-1];
    }
    return nil;
}

@end
