//
//  PosterMarkerService.m
//  Miramax
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

+ (PosterMarkerService *) getInstance {
	if (instance == nil) {
        instance = [[PosterMarkerService alloc] init];
    }
    return instance;
}

- (NSString *) dummyClientServiceResponse {
    return 0;;
}

- (NSArray *) getPosterData {
    NSMutableArray *catalogData = [[NSMutableArray alloc] initWithObjects: nil];
    NSDictionary *catalogsFromService = [self getDataFromService];
    
    NSEnumerator *catalogs = [catalogsFromService objectEnumerator];
    [[[SessionManager getInstance] markerDataArray] removeAllObjects];
    for(NSDictionary *catalog in catalogs) {
        Poster *poster = [[Poster alloc] init];
        [poster setPosterImage:[catalog objectForKey: @"image"]];
        [poster setTitle:[catalog objectForKey: @"title"]];
        [poster setPosterId:[[catalog objectForKey: @"id"] longValue]];
        NSMutableArray *catalogPageItems = [[NSMutableArray alloc] init];
        NSDictionary *jsonCatalogPageMarker = [catalog objectForKey:@"marker"];
            Marker *marker = [[Marker alloc] init];
            [marker setMarkerId:[[jsonCatalogPageMarker objectForKey:@"id"]longValue]];
            [marker setActive:[[jsonCatalogPageMarker objectForKey:@"active"]boolValue]];
            [marker setHeight:[[jsonCatalogPageMarker objectForKey:@"height"]longValue]];
            [marker setWidth:[[jsonCatalogPageMarker objectForKey:@"width"]longValue]];
            [marker setMarkerImage:[jsonCatalogPageMarker objectForKey:@"image"]];
            [marker setMarkerTitle:[jsonCatalogPageMarker objectForKey:@"title"]];
            [catalogPageItems addObject: marker];
        [poster setPosterMarkers:catalogPageItems];
        if ([[jsonCatalogPageMarker objectForKey:@"active"]boolValue]) {
            [catalogData addObject:poster];
            [[SessionManager getInstance] storeMarkerDataIntoArray:catalog];
        }
    }
    
    return [[NSArray alloc] initWithArray:catalogData];
}

- (NSDictionary *) getDataFromService {
    NSString *serviceResponse = nil;
    NSString *appVersion = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleVersion"];
    NSString *systemVersion = [[UIDevice currentDevice] systemVersion];
    NSString *serviceRequest = [[NSString alloc] initWithFormat:@"%@?system=iOS&systemVersion=%@&appVersion=%@",SERVICE_URL, systemVersion, appVersion];
    
    serviceResponse =[super serviceResponse:serviceRequest];
    return [super json:serviceResponse];
}

@end
