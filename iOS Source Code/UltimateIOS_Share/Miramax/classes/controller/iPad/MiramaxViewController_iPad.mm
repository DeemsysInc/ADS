//
//  MiramaxViewController_iPad.m
//  Miramax
//
//  Created by Vishal on 30/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "MiramaxViewController_iPad.h"
#import "ShareViewController_iPad.h"
#import "SessionManager.h"

@interface MiramaxViewController_iPad ()

@end

@implementation MiramaxViewController_iPad

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

#pragma mark - lifecycle
- (void)viewDidLoad
{
    [[SessionManager getInstance] setIPhoneContext:NO];
    [super setViewFrameCustom:self.view.frame];
    [self.shapeSlider setThumbImage:[UIImage imageNamed:@"slider_ball.png"] forState:UIControlStateNormal];
    UIImage *sliderLeftTrackImage = [[UIImage imageNamed: @"slider.png"] stretchableImageWithLeftCapWidth: 6 topCapHeight:6];
    UIImage *sliderRightTrackImage = [[UIImage imageNamed: @"slider.png"] stretchableImageWithLeftCapWidth: 6 topCapHeight: 6];
    [self.shapeSlider setMinimumTrackImage: sliderLeftTrackImage forState: UIControlStateNormal];
    [self.shapeSlider setMaximumTrackImage: sliderRightTrackImage forState: UIControlStateNormal];
    [super viewDidLoad];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
	return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

@end
