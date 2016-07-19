//
//  MiramaxViewController_iPhone.m
//  Miramax
//
//  Created by Vishal on 30/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "MiramaxViewController_iPhone.h"
#import "ShareViewController_iPhone.h"
#import "SessionManager.h"

@interface MiramaxViewController_iPhone ()

@end

@implementation MiramaxViewController_iPhone


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
    [[SessionManager getInstance] setIPhoneContext:YES];
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
