//
//  BaseModel.m
//  Seemore
//
//  Created by Vishal Patel on 06/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import "BaseModel.h"

@implementation BaseModel

@synthesize error;

UIAlertView *errorAlert;

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
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Model - hasValue" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - NSCoding Protocols

- (void)encodeWithCoder:(NSCoder *)encoder {
    @try {
        [encoder encodeObject:self.error forKey:@"error"];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Model - encodeWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (id)initWithCoder:(NSCoder *)decoder {
    @try {
        if (self = [super init]) {
            
            self.error = [decoder decodeObjectForKey:@"error"];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Model - initWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
