//
//  RequestManager.m
//  Seemore
//
//  Created by Vishal Patel on 9/9/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "CacheManager.h"

@implementation CacheManager

static CacheManager *instance = nil;
const int SIXTY_MB = 62914560;
const int EIGHT_MB = 8388608;

+ (CacheManager *) sharedInstance {
    @synchronized(self) {
        if (instance == nil) {
            instance = [[self alloc] init];
            NSURLCache *cache = [NSURLCache sharedURLCache];
            [cache setDiskCapacity:SIXTY_MB];
            [cache setMemoryCapacity:EIGHT_MB];
        }
    }
    return instance;
}

- (NSData*) invokeSynchronousCachedRequest: (NSString*) urlString {
    NSURL *url = [[NSURL alloc] initWithString:urlString];
    NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url cachePolicy:NSURLCacheStorageAllowed timeoutInterval:30];
    NSData *data = [self getCachedDataForRequest:request];
    if(data != nil) {
        return data;
    }
    return [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
}

- (NSData*) getCachedDataForRequest: (NSURLRequest*) request {
    NSURLCache *cache = [NSURLCache sharedURLCache];
    NSCachedURLResponse *cachedResponse = [cache cachedResponseForRequest:request];
    if(cachedResponse != nil) {
        return [cachedResponse data];
    }
    return nil;
}

- (void) clearCache {
    NSURLCache *cache = [NSURLCache sharedURLCache];
    [cache removeAllCachedResponses];
}

- (void) printCacheStats {
    NSURLCache *cache = [NSURLCache sharedURLCache];
    int freeCacheDisk = [cache diskCapacity] - [cache currentDiskUsage];
    int freeMemory = [cache memoryCapacity] - [cache currentMemoryUsage];
    NSLog(@"Available Disk: %u", freeCacheDisk);
    NSLog(@"Available Memory: %u", freeMemory);
}

@end
