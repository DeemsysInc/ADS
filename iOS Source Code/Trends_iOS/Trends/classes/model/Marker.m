//
//  Marker.m
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "Marker.h"

@implementation Marker

@synthesize active, markerId, markerImage, markerTitle, height, width;

UIAlertView *errorAlert;

- (id)init {
    @try {
        self = [super init];
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Marker - init" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - NSCoding Protocols

- (void)encodeWithCoder:(NSCoder *)encoder {
    @try {
        [encoder encodeBool:active forKey:@"active"];
        [encoder encodeInt32:markerId forKey:@"markerId"];
        [encoder encodeObject:self.markerImage forKey:@"markerImage"];
        [encoder encodeObject:self.markerTitle forKey:@"markerTitle"];
        [encoder encodeInt32:height forKey:@"height"];
        [encoder encodeInt32:width forKey:@"width"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Marker - encodeWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (id)initWithCoder:(NSCoder *)decoder {
    @try {
        if (self = [super init]) {
            
            self.active = [decoder decodeBoolForKey:@"active"];
            self.markerId = [decoder decodeInt32ForKey:@"markerId"];
            self.markerImage = [decoder decodeObjectForKey:@"markerImage"];
            self.markerTitle = [decoder decodeObjectForKey:@"markerTitle"];
            self.height = [decoder decodeInt32ForKey:@"height"];
            self.width = [decoder decodeInt32ForKey:@"width"];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Marker - initWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}


@end
