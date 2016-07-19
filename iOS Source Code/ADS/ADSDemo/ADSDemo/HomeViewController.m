//
//  FirstViewController.m
//  ADSDemo
//
//  Created by Vikram Nerasu on 06/10/14.
//  Copyright (c) 2014 SeeMore Interactive. All rights reserved.
//

#import "HomeViewController.h"

@interface HomeViewController ()

@end

@implementation HomeViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
    [self.tabBarController setDelegate:self];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)triggerSeeMore:(id)sender {
    BOOL isAppInstalled=[[UIApplication sharedApplication] canOpenURL: [NSURL URLWithString:@"seemore://"]];
    if(isAppInstalled) {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"seemore://"]];
    }else {
        UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Not found" message:@"SeeMore app not found on your device. Please install SeeMore app." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        alert.alertViewStyle = UIAlertViewStyleDefault;
        [alert show];
    }
    
}
- (void)tabBarController:(UITabBarController *)tabBarController didSelectViewController:(UIViewController *)viewController{
//    NSLog(@"Vikram: tabBarController %ld",[tabBarController selectedIndex]);
    NSUInteger s = [tabBarController selectedIndex];
    if (s==3) {
        [self triggerSeeMore:nil];
        [tabBarController setSelectedIndex:0];
    }
}
@end
