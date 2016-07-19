//
//  HockeyDemoViewController.m
//  HockeyDemo
//
//  Created by Andreas Linde on 8/22/10.
//  Copyright Andreas Linde 2010. All rights reserved.
//

#import "HockeyDemoViewController.h"
#import "HockeyDemoSettingsViewController.h"

#if !defined (CONFIGURATION_AppStore_Distribution)
#import "BWHockeyManager.h"
#endif

@implementation HockeyDemoViewController

- (void)viewDidLoad {
  [super viewDidLoad];
  
  self.navigationItem.leftBarButtonItem =  [[[UIBarButtonItem alloc] initWithTitle:@"Settings"
                                                                             style:UIBarButtonItemStyleBordered
                                                                            target:self
                                                                            action:@selector(showSettings)] autorelease];
}

- (void)showSettings {
  HockeyDemoSettingsViewController *hockeySettingsViewController = [[[HockeyDemoSettingsViewController alloc] init] autorelease];
  UINavigationController *navController = [[[UINavigationController alloc] initWithRootViewController:hockeySettingsViewController] autorelease];
  navController.modalTransitionStyle = UIModalTransitionStyleCoverVertical;
  [self presentModalViewController:navController animated:YES];
}

- (IBAction)openUpdateView {
#if !defined (CONFIGURATION_AppStore_Distribution)
  [[BWHockeyManager sharedHockeyManager] showUpdateView];
#endif
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
  if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad) {
    return YES;
  }else {
    return toInterfaceOrientation != UIInterfaceOrientationPortraitUpsideDown;
  }
}

@end
