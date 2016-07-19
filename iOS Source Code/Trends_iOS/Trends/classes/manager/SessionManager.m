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

@synthesize markerDataArray = _markerDataArray;

static SessionManager *sessionInstance = nil;
UIAlertView *errorAlert;

+ (SessionManager *) getInstance {
    @try {
        @synchronized(self) {
            if (sessionInstance == nil) {
                sessionInstance = [[self alloc] init];
            }
        }
        return sessionInstance;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getInstance" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) initialize {
    @try {
        self.markerDataArray = [[NSMutableArray alloc]init];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - initialize" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (id)init {
    @try {
        self = [super init];
        if (self) {
            [self initialize];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - init" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL) hasValue: (id) field {
    @try {
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
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - hasValue" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Detect Retina Display
+ (BOOL) isDeviceHaveRetinaDisplay  {
    @try {
        if ([[UIScreen mainScreen] respondsToSelector:@selector(scale)] && [[UIScreen mainScreen] scale] == 2.0) {
            // Device Have Retina Screen
            return YES;
        }
        // Device Have Normal Screen
        return NO;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - isDeviceHaveRetinaDisplay" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Supporting Methods
-(NSArray *) getPosterMarkerData {
    @try {
        return [[PosterMarkerService getInstance] getPosterData];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getPosterMarkerData" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSArray *) getPosterMarkerArray {
    @try {
        return self.markerDataArray;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getPosterMarkerArray" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) storeMarkerDataIntoArray:(NSArray *)array {
    @try{
        self.markerDataArray = [NSMutableArray arrayWithArray:array];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - storeMarkerDataIntoArray" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (Poster *) getPosterWithID:(long)posterID  {
    @try {
        if (self.markerDataArray!=nil && [self.markerDataArray count]>0) {
            
            int totalObjects = [self.markerDataArray count];
            for (int count=0; count<totalObjects; count++) {
                
                Poster *posterObj = [self.markerDataArray objectAtIndex:count];
                if (posterObj.posterId == posterID) {
                    return posterObj;
                }
            }
        }
        
        return nil;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getPosterWithID" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (Marker *) getMarkerWithID:(long)markerID  {
    @try {
        if (self.markerDataArray!=nil && [self.markerDataArray count]>0) {
            
            int totalObjects = [self.markerDataArray count];
            for (int count=0; count<totalObjects; count++) {
                
                Poster *posterObj = [self.markerDataArray objectAtIndex:count];
                long totalMarkers = [posterObj.posterMarkers count];
                for (int markerCount=0; markerCount<totalMarkers; markerCount++) {
                    
                    Marker *marker = [posterObj.posterMarkers objectAtIndex:markerCount];
                    if (marker.markerId == markerID) {
                        return marker;
                    }
                }
            }
        }
        
        if (markerID == 1) {
            
            Marker *marker = [[Marker alloc] init];
            [marker setMarkerId:1];
            [marker setActive:YES];
            [marker setMarkerImage:@"The_Hobbit_Marker.png"];
    //        [marker setHeight:];
    //        [marker setWidth:];
            [marker setMarkerTitle:@"The Hobbit"];
            return marker;
        }
        else if (markerID == 2) {
            
            Marker *marker = [[Marker alloc] init];
            [marker setMarkerId:2];
            [marker setActive:YES];
            [marker setMarkerImage:@"Monster_High_Marker.png"];
    //        [marker setHeight:];
    //        [marker setWidth:];
            [marker setMarkerTitle:@"Monster High"];
            return marker;
        }
        else if (markerID == 3) {
            
            Marker *marker = [[Marker alloc] init];
            [marker setMarkerId:3];
            [marker setActive:YES];
            [marker setMarkerImage:@"Tink-Marker.jpg"];
    //        [marker setHeight:];
    //        [marker setWidth:];
            [marker setMarkerTitle:@"Tink"];
            return marker;
        }
        
        return nil;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getMarkerWithID" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (Poster *) getPosterForMarkerID:(long)markerID    {
    @try {
        if (self.markerDataArray!=nil && [self.markerDataArray count]>0) {
            
            int totalObjects = [self.markerDataArray count];
            for (int count=0; count<totalObjects; count++) {
                
                Poster *posterObj = [self.markerDataArray objectAtIndex:count];
                long totalMarkers = [posterObj.posterMarkers count];
                for (int markerCount=0; markerCount<totalMarkers; markerCount++) {
                    
                    Marker *marker = [posterObj.posterMarkers objectAtIndex:markerCount];
                    if (marker.markerId == markerID) {
                        return posterObj;
                    }
                }
            }
        }
        
        if (markerID == 1) {
            
            Poster *poster = [[Poster alloc] init];
            [poster setPosterId:markerID];
            [poster setTitle:@"The Hobbit"];
            [poster setPosterImage:@"The_Hobbit_Visual.png"];
            [poster setLocalPosterImagePath:[self mainBundlePathStringForFileName:@"The_Hobbit_Visual" ofType:@"png"]];
            [poster setLegalContentSwitch:NO];
            [poster setLegalImageURL:@""];
            [poster setLegalImagePosition:BOTTOM_RIGHT_CORNER];
            return poster;
        }
        else if (markerID == 2) {
            
            Poster *poster = [[Poster alloc] init];
            [poster setPosterId:markerID];
            [poster setTitle:@"Monster High"];
            [poster setPosterImage:@"Monster_High_Visual.png"];
            [poster setLocalPosterImagePath:[self mainBundlePathStringForFileName:@"Monster_High_Visual" ofType:@"png"]];
            [poster setLegalContentSwitch:NO];
            [poster setLegalImageURL:@""];
            [poster setLegalImagePosition:BOTTOM_RIGHT_CORNER];
            return poster;
        }
        else if (markerID == 3) {
            
            Poster *poster = [[Poster alloc] init];
            [poster setPosterId:markerID];
            [poster setTitle:@"Tink"];
            [poster setPosterImage:@"Tink.png"];
            [poster setLocalPosterImagePath:[self mainBundlePathStringForFileName:@"Tink" ofType:@"png"]];
            [poster setLegalContentSwitch:NO];
            [poster setLegalImageURL:@""];
            [poster setLegalImagePosition:BOTTOM_RIGHT_CORNER];
            return poster;
        }
        
        return nil;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - getPosterForMarkerID" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) loadImageWithURL:(NSString *)imageUrl andCallBlock:(void(^)(UIImage *image))block {
    @try {
        dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
            UIImage *image = [[UIImage alloc] initWithData: 
                              [[NSData alloc] initWithContentsOfURL: 
                               [[NSURL alloc] initWithString:imageUrl]]];
            if (block && image.CGImage) {
                block(image);
            }
        });
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - loadImageWithURL" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
        
}

#pragma mark - Path Generation Methods

- (NSString *) mainBundlePathStringForFileName:(NSString *)fileName ofType:(NSString *)type   {
    @try {
        return [[NSBundle mainBundle] pathForResource:fileName ofType:type];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - mainBundlePathStringForFileName" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSURL *) mainBundlePathURLForFileName:(NSString *)fileName ofType:(NSString *)type    {
    @try {
        NSString *filPath = [self mainBundlePathStringForFileName:fileName ofType:type];
        return [NSURL fileURLWithPath:filPath];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - mainBundlePathURLForFileName" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) pathStringForFileName:(NSString *)fileName ofType:(NSString *)type   {
    @try {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
        NSString *documentsDirectory = [paths objectAtIndex:0];
        
        // Validate File Name
        if (fileName==nil || [fileName isEqualToString:@""]) {
            return nil;
        }
        
        NSString *fullFilename = [NSString stringWithFormat:@"%@",fileName];
        
        // Validate File Extension
        if (type!=nil && ![type isEqualToString:@""]) {
            fullFilename = [fullFilename stringByAppendingFormat:@".%@",type];
        }
        
        return [documentsDirectory stringByAppendingPathComponent:fullFilename];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - pathStringForFileName" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSURL *) pathURLForFileName:(NSString *)fileName ofType:(NSString *)type	{
    @try {
        NSString *filPath = [self pathStringForFileName:fileName ofType:type];
        return [NSURL fileURLWithPath:filPath];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Session Manager - pathURLForFileName" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
