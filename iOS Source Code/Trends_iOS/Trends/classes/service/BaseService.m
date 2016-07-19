//
//  AbstractService.m
//  Seemore
//
//  Created by Vishal Patel on 06/11/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import "BaseService.h"
#import "SBJsonParser.h"

@implementation BaseService
@synthesize session;

UIAlertView *errorAlert;

- (void) loadSession {
    @try {
        session = [SessionManager getInstance];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - loadSession" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (id)init {
    @try {
        self = [super init];
        if (self) {
            [self loadSession];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - init" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
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
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - hasValue" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) currentTimeStamp {
    @try {
        NSTimeInterval timeStamp = [[NSDate date] timeIntervalSince1970];
        return [[NSNumber numberWithUnsignedInteger:timeStamp] stringValue];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - currentTimeStamp" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSMutableURLRequest *)createRequest:(NSString *)requestUrl  {
    @try {
        NSLog(@"%@",requestUrl);
        if([requestUrl rangeOfString:@"?"].location == NSNotFound)
            requestUrl = [requestUrl stringByAppendingString:@"?"];
        else
            requestUrl = [requestUrl stringByAppendingString:@"&"];
        
        requestUrl = [requestUrl stringByAppendingFormat:@"timeStamp=%@",[self currentTimeStamp]];
        
        NSString *version = [[NSBundle mainBundle] objectForInfoDictionaryKey:(NSString *)kCFBundleVersionKey];
        requestUrl = [requestUrl stringByAppendingFormat:@"&version=%@",version];
        
        NSURL *url = [[NSURL alloc] initWithString:requestUrl];
        NSMutableURLRequest *urlRequest = [NSMutableURLRequest requestWithURL:url 
                                                                  cachePolicy:NSURLRequestReturnCacheDataElseLoad 
                                                              timeoutInterval:300];
        return urlRequest;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - createRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *)createResponse:(NSMutableURLRequest *)urlRequest  {
    @try {
        NSString *response;
        NSData *urlData;
        NSURLResponse *urlResponse;
        NSError * __autoreleasing error;
        
        urlData = [NSURLConnection sendSynchronousRequest:urlRequest
                                        returningResponse:&urlResponse
                                                    error:&error];
        
        response = [[NSString alloc] initWithData:urlData encoding:NSUTF8StringEncoding];
        return response;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - createResponse" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) serviceResponse:(NSString *)requestUrl {
    @try {
        NSMutableURLRequest *urlRequest = [self createRequest: requestUrl];
        NSString *response = [self createResponse: urlRequest];
        return response;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - serviceResponse" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) serviceResponse:(NSString *)requestUrl httpMethod:(NSString *)httpMethod body:(NSString *)body {
    @try {
        NSLog(@"Request = %@\n%@\n%@",requestUrl, httpMethod, body);
        NSMutableURLRequest *urlRequest = [self createRequest: requestUrl];
        [urlRequest setValue:@"application/json" forHTTPHeaderField: @"Content-Type"];
        [urlRequest setHTTPMethod:httpMethod];
        
        NSMutableData *bodyData = [NSMutableData data];
        [bodyData appendData:[[NSString stringWithFormat:@"%@", body] dataUsingEncoding:NSUTF8StringEncoding]];
        [urlRequest setHTTPBody:bodyData];
        
        NSString *response = [self createResponse: urlRequest];
    //    NSLog(@"Response = %@",response);
        return response;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - serviceResponse httpMethod" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSDictionary *) json: (NSString *)jsonStr {
    @try {
        SBJsonParser *parser = [[SBJsonParser alloc] init];
        NSDictionary *json = [parser objectWithString:jsonStr];
    //    NSLog(@"JSON = %@",json);
        return json;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - json" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) fileContent:(NSString *)fileName fileType:(NSString *)fileType {
    @try {
        NSString *filePath = [[NSBundle mainBundle] pathForResource:fileName ofType:fileType];
        NSStringEncoding encoding;
        NSError * __autoreleasing error;
        NSString *fileContent = [[NSString alloc] initWithContentsOfFile:filePath
                                                             usedEncoding:&encoding
                                                                    error:&error];
        return fileContent;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Base Service - fileContent" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
