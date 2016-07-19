//
//  RequestManager.h
//  Seemore
//
//  Created by Vishal Patel on 9/9/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface CacheManager : NSObject

+ (CacheManager *) sharedInstance;

- (NSData*) invokeSynchronousCachedRequest: (NSString*) url;
- (void) clearCache;
- (void) printCacheStats;
- (NSData*) getCachedDataForRequest: (NSURLRequest*) request;

@end
