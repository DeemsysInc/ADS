//
//  AbstractService.h
//  Seemore
//
//  Created by Vishal Patel on 06/11/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "SessionManager.h"

@interface BaseService : NSObject {
    SessionManager *session;
}

@property (nonatomic, strong) SessionManager *session;

- (void) loadSession;
- (BOOL) hasValue: (id) field;

- (NSString *) serviceResponse:(NSString *)requestUrl;
- (NSString *) serviceResponse:(NSString *)requestUrl httpMethod:(NSString *)httpMethod body:(NSString *)body;

- (NSDictionary *) json: (NSString *)jsonStr;

- (NSString *) fileContent:(NSString *)fileName fileType:(NSString *)fileType;

@end
