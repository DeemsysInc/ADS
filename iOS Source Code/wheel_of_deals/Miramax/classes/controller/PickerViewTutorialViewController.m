//
//  PickerViewTutorialViewController.m
//  PickerViewTutorial
//
//  Created by kent franks on 8/3/11.
//  Copyright 2011 TheAppCodeBlog. All rights reserved.
//

#import "PickerViewTutorialViewController.h"

@implementation PickerViewTutorialViewController

@synthesize pickerView;

- (void)dealloc
{
    //[super dealloc];
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

#pragma mark - View lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
  
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    CGFloat screenWidth = screenSize.width;
    CGFloat screenHeight = screenSize.height;
    NSLog(@"'Vikram: screenHeight %f",screenHeight);
    
    pickerView = [[UIPickerView alloc] initWithFrame:CGRectMake(0.0, 0.0, 320.0, 216.0)];

    [self.view addSubview:pickerView];
    


}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

#pragma mark -
#pragma mark picker view methods
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView;
{
    return 1;
}

- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    NSLog(@"the %d row was selected in the %d component", row, component);
}

- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component;
{
    return 5;
}

- (CGFloat)pickerView:(UIPickerView *)pickerView rowHeightForComponent:(NSInteger)component {
    return 66;
}

//- (NSString *)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component;
//{
//    return @"Row Name";
//}
- (UIView *)pickerView:(UIPickerView *)pickerView viewForRow:(NSInteger)row
          forComponent:(NSInteger)component reusingView:(UIView *)view
{
    UIImage *img = [UIImage imageNamed:@"playPlayerButton.png"];
    UIImageView *temp = [[UIImageView alloc] initWithImage:img];
    temp.frame = CGRectMake(-70, 10, 60, 100);
    
    UILabel *channelLabel = [[UILabel alloc] initWithFrame:CGRectMake(50, -5, 80, 100)];
    channelLabel.text = @"persona y";
    channelLabel.textAlignment = UITextAlignmentLeft;
    channelLabel.backgroundColor = [UIColor clearColor];
    
    UIView *tmpView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 110, 100)];
    [tmpView insertSubview:temp atIndex:0];
    [tmpView insertSubview:channelLabel atIndex:1];
    
    return tmpView;
}


@end
