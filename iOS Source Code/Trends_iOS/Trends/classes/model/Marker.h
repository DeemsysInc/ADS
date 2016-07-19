//
//  Marker.h
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "BaseModel.h"

@interface Marker : BaseModel <NSCoding>

@property long markerId;
@property BOOL active;
@property long height;
@property long width;
@property (nonatomic, strong) NSString *markerTitle;
@property (nonatomic, strong) NSString *markerImage;

@end
