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
UIAlertView *errorAlert;

+ (CacheManager *) sharedInstance {
    @try {
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
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Cache Manager - sharedInstance" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSData*) invokeSynchronousCachedRequest: (NSString*) urlString {
    @try {
        NSURL *url = [[NSURL alloc] initWithString:urlString];
        NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url cachePolicy:NSURLCacheStorageAllowed timeoutInterval:30];
        NSData *data = [self getCachedDataForRequest:request];
        if(data != nil) {
            return data;
        }
        return [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Cache Manager - invokeSynchronousCachedRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSData*) getCachedDataForRequest: (NSURLRequest*) request {
    @try {
        NSURLCache *cache = [NSURLCache sharedURLCache];
        NSCachedURLResponse *cachedResponse = [cache cachedResponseForRequest:request];
        if(cachedResponse != nil) {
            return [cachedResponse data];
        }
        return nil;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Cache Manager - getCachedDataForRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) clearCache {
    @try {
        NSURLCache *cache = [NSURLCache sharedURLCache];
        [cache removeAllCachedResponses];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Cache Manager - clearCache" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) printCacheStats {
    @try {
        NSURLCache *cache = [NSURLCache sharedURLCache];
        int freeCacheDisk = [cache diskCapacity] - [cache currentDiskUsage];
        int freeMemory = [cache memoryCapacity] - [cache currentMemoryUsage];
        NSLog(@"Available Disk: %u", freeCacheDisk);
        NSLog(@"Available Memory: %u", freeMemory);
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Cache Manager - printCacheStats" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
