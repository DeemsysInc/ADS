//
//  Option.h
//  Miramax
//
//  Created by Shardul on 07/11/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "BaseModel.h"

@interface Option : BaseModel

@property (nonatomic, strong) NSString *visualImageName;
@property (nonatomic, strong) NSString *videoURL;
@property (nonatomic, strong) NSString *productURL;
@property (nonatomic, strong) NSString *getTriggerType;
@property (nonatomic, strong) NSString *get3DModel;
@property (nonatomic, strong) NSString *getWebURL;

@end
