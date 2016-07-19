//
//  BaseModel.h
//  Seemore
//
//  Created by Vishal Patel on 06/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface BaseModel : NSObject {
    NSString *error;
}

@property (strong, nonatomic) NSString *error;

- (BOOL) hasValue: (id) field;

@end
