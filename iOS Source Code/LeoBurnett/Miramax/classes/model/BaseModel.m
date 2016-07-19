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

@end
