//
//  PickerViewTutorialViewController.h
//  PickerViewTutorial
//
//  Created by kent franks on 8/3/11.
//  Copyright 2011 TheAppCodeBlog. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface PickerViewTutorialViewController : UIViewController <UIPickerViewDelegate, UIPickerViewDataSource>
{
	
	UIPickerView *pickerView;
    NSMutableArray *pickerArray;
    
}

@property (nonatomic, retain) IBOutlet UIPickerView *pickerView;

@end
