//
//  CustomCell.m
//  wheelofdeals
//
//  Created by Vikram Nerasu on 10/06/13.
//  Copyright (c) 2013 Digital Imperia. All rights reserved.
//

#import "CustomCell.h"

@implementation CustomCell

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}
+ (NSString *)reuseIdentifier {
    return @"CustomCellIdentifier";
}
- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
