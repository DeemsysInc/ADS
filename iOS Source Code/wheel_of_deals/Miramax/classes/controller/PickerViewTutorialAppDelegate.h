//
//  PickerViewTutorialAppDelegate.h
//  PickerViewTutorial
//
//  Created by kent franks on 8/3/11.
//  Copyright 2011 TheAppCodeBlog. All rights reserved.
//

#import <UIKit/UIKit.h>

@class PickerViewTutorialViewController;

@interface PickerViewTutorialAppDelegate : NSObject <UIApplicationDelegate> {

}

@property (nonatomic, retain) IBOutlet UIWindow *window;

@property (nonatomic, retain) IBOutlet PickerViewTutorialViewController *viewController;

@end
