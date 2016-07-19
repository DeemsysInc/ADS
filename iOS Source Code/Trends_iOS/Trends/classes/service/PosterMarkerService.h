//
//  PosterMarkerService.h
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "BaseService.h"

@interface PosterMarkerService : BaseService {
    
}

+ (PosterMarkerService *) getInstance;

- (NSArray *) getPosterData;

@end
