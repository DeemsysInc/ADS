//
//  PosterMarkerService.m
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "PosterMarkerService.h"
#import "Poster.h"
#import "Marker.h"
#import "SessionManager.h"

@implementation PosterMarkerService

static PosterMarkerService *instance;

UIAlertView *errorAlert;

+ (PosterMarkerService *) getInstance {
    @try {
        if (instance == nil) {
            instance = [[PosterMarkerService alloc] init];
        }
        return instance;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster Marker - getInstance" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSString *) dummyClientServiceResponse {
    return 0;
}

- (NSArray *) getPosterData {
    @try {
        NSMutableArray *catalogData = [[NSMutableArray alloc] init];
        NSDictionary *catalogsFromService = [self getDataFromService];
        
        NSEnumerator *catalogs = [catalogsFromService objectEnumerator];
        [[[SessionManager getInstance] markerDataArray] removeAllObjects];
        int count = 1;
        for(NSDictionary *catalog in catalogs) {
            Poster *poster = [[Poster alloc] init];
            [poster setPosterImage:[catalog objectForKey: @"image"]];
            [poster setTitle:[catalog objectForKey: @"title"]];
            [poster setPosterId:[[catalog objectForKey: @"id"] longValue]];
            [poster setLegalContentSwitch:[[catalog objectForKey:@"legalSwitch"] boolValue]];
            NSString *legalImageURL = [catalog objectForKey:@"legalImage"];
            if (![legalImageURL isEqual:[NSNull null]] && legalImageURL!=nil && ![legalImageURL isEqualToString:@""]) {
                [poster setLegalImageURL:[catalog objectForKey:@"legalImage"]];
            }
            else    {
                [poster setLegalContentSwitch:NO];
                [poster setLegalImageURL:nil];
            }
            NSDictionary *legalPositionDict = [catalog objectForKey:@"legalPosition"];
            if (![legalImageURL isEqual:[NSNull null]] && legalPositionDict!=nil && [legalPositionDict count]>0) {
                [poster setLegalImagePositionFromString:[legalPositionDict objectForKey:@"name"]];
            }
            else    {
                [poster setLegalImagePositionFromString:@"BOTTOM_RIGHT"];
            }
            
            NSMutableArray *catalogPageItems = [[NSMutableArray alloc] init];
            NSDictionary *jsonCatalogPageMarker = [catalog objectForKey:@"marker"];
                Marker *marker = [[Marker alloc] init];
    //            [marker setMarkerId:[[jsonCatalogPageMarker objectForKey:@"id"]longValue]];
                [marker setMarkerId:count];
                count++;
                [marker setActive:[[jsonCatalogPageMarker objectForKey:@"active"]boolValue]];
                [marker setHeight:[[jsonCatalogPageMarker objectForKey:@"height"]longValue]];
                [marker setWidth:[[jsonCatalogPageMarker objectForKey:@"width"]longValue]];
                [marker setMarkerImage:[jsonCatalogPageMarker objectForKey:@"image"]];
                [marker setMarkerTitle:[jsonCatalogPageMarker objectForKey:@"title"]];
                [catalogPageItems addObject: marker];
            [poster setPosterMarkers:catalogPageItems];
            
            [catalogData addObject:poster];
        }
        
        [[SessionManager getInstance] storeMarkerDataIntoArray:[[NSArray alloc] initWithArray:catalogData]];
        
        return [[NSArray alloc] initWithArray:catalogData];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster Marker - getPosterData" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (NSDictionary *) getDataFromService {
    @try {
        NSString *serviceResponse = nil;
        NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleVersion"];
        NSString *systemVersion = [[UIDevice currentDevice] systemVersion];
        NSString *serviceRequest = [[NSString alloc] initWithFormat:@"%@?system=iOS&systemVersion=%@&appVersion=%@",SERVICE_URL, systemVersion, appVersion];
        
        serviceResponse =[super serviceResponse:serviceRequest];
        NSLog(@"response = %@",serviceResponse);
        return [super json:serviceResponse];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster Marker - getDataFromService" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
