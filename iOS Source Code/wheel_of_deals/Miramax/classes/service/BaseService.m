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

- (void) loadSession {
    session = [SessionManager getInstance];
}

- (id)init {
    self = [super init];
    if (self) {
        [self loadSession];
    }
    return self;
}

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

- (NSString *) currentTimeStamp {
    NSTimeInterval timeStamp = [[NSDate date] timeIntervalSince1970];
    return [[NSNumber numberWithUnsignedInteger:timeStamp] stringValue];
}

- (NSMutableURLRequest *)createRequest:(NSString *)requestUrl  {
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

- (NSString *)createResponse:(NSMutableURLRequest *)urlRequest  {
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

- (NSString *) serviceResponse:(NSString *)requestUrl {
	NSMutableURLRequest *urlRequest = [self createRequest: requestUrl];
    NSString *response = [self createResponse: urlRequest];
    return response;
}

- (NSString *) serviceResponse:(NSString *)requestUrl httpMethod:(NSString *)httpMethod body:(NSString *)body {
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

- (NSDictionary *) json: (NSString *)jsonStr {
    SBJsonParser *parser = [[SBJsonParser alloc] init];
    NSDictionary *json = [parser objectWithString:jsonStr];
//    NSLog(@"JSON = %@",json);
    return json;
}

- (NSString *) fileContent:(NSString *)fileName fileType:(NSString *)fileType {
    NSString *filePath = [[NSBundle mainBundle] pathForResource:fileName ofType:fileType];
    NSStringEncoding encoding;
    NSError * __autoreleasing error;
    NSString *fileContent = [[NSString alloc] initWithContentsOfFile:filePath
                                                         usedEncoding:&encoding
                                                                error:&error];
    return fileContent;    
}

@end
