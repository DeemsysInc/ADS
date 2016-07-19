//
//  CustomCell.h
//  wheelofdeals
//
//  Created by Vikram Nerasu on 10/06/13.
//  Copyright (c) 2013 Digital Imperia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CustomCell : UITableViewCell

+ (NSString *) reuseIdentifier;
@property (strong, nonatomic) IBOutlet UILabel *topLabel;
@end
