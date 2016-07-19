//
//  Poster.h
//  Miramax
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "BaseModel.h"

@interface Poster : BaseModel 

@property long posterId;
@property (nonatomic, strong) NSString *title;
@property (nonatomic, strong) NSString *posterImage;
@property (nonatomic, strong) NSMutableArray *posterMarkers;

@end
