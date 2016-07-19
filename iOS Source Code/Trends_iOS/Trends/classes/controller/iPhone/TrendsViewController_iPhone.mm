//
//  TrendsViewController_iPhone.m
//  Trends
//
//  Created by Vishal on 30/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "TrendsViewController_iPhone.h"
#import "ShareViewController_iPhone.h"

@interface TrendsViewController_iPhone ()

@end

@implementation TrendsViewController_iPhone


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super setViewFrameCustom:self.view.frame];
    [self.shapeSlider setThumbImage:[UIImage imageNamed:@"slider_ball_small.png"] forState:UIControlStateNormal];
    UIImage *sliderLeftTrackImage = [[UIImage imageNamed: @"slider_small.png"] stretchableImageWithLeftCapWidth: 6 topCapHeight:6];
    UIImage *sliderRightTrackImage = [[UIImage imageNamed: @"slider_small.png"] stretchableImageWithLeftCapWidth: 6 topCapHeight: 6];
    [self.shapeSlider setMinimumTrackImage: sliderLeftTrackImage forState: UIControlStateNormal];
    [self.shapeSlider setMaximumTrackImage: sliderRightTrackImage forState: UIControlStateNormal];
    [super viewDidLoad];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

@end
