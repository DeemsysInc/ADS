//
//  Poster.h
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "BaseModel.h"

typedef enum {
    TOP_LEFT_CORNER = 0,
    TOP_CENTER = 1,
    TOP_RIGHT_CORNER = 2,
    BOTTOM_LEFT_CORNER = 3,
    BOTTOM_CENTER = 4,
    BOTTOM_RIGHT_CORNER = 5
} LegalImagePosition;

@interface Poster : BaseModel <NSCoding>

@property long posterId;
@property (nonatomic, strong) NSString *title;
@property (nonatomic, strong) NSString *posterImage;
@property (nonatomic, strong) NSString *localPosterImagePath;
@property (nonatomic, strong) NSMutableArray *posterMarkers;

@property (nonatomic) BOOL legalContentSwitch;
@property (nonatomic, strong) NSString *legalImageURL;
@property (nonatomic) LegalImagePosition legalImagePosition;

- (void) setLegalImagePositionFromString:(NSString *)imagePosition;

@end
