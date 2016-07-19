//
//  AppDelegate.m
//  ADSDemo
//
//  Created by Vikram Nerasu on 06/10/14.
//  Copyright (c) 2014 SeeMore Interactive. All rights reserved.
//

#import "AppDelegate.h"

@implementation AppDelegate

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    // Override point for customization after application launch.
    [self loadCustomTabImages];

    return YES;
}
							
- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

-(void)loadCustomTabImages{
    
    
    UITabBarController *tabBarController = (UITabBarController *)self.window.rootViewController;
    UITabBar *tabBar = tabBarController.tabBar;
    UITabBarItem *tabBarItem1 = [tabBar.items objectAtIndex:0];
    UITabBarItem *tabBarItem2 = [tabBar.items objectAtIndex:1];
    UITabBarItem *tabBarItem3 = [tabBar.items objectAtIndex:2];
    UITabBarItem *tabBarItem4 = [tabBar.items objectAtIndex:3];
    
    tabBarItem1.title = @"Home";
    tabBarItem2.title = @"Products";
    tabBarItem3.title = @"Cart";
    tabBarItem4.title = @"SeeMore";

    
    // also repeat for every tab
    tabBarItem1.image = [[UIImage imageNamed:@"home_icon.png"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal ];
    tabBarItem1.selectedImage = [[UIImage imageNamed:@"home_icon.png"]imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal];
    
    
    // also repeat for every tab
    tabBarItem2.image = [[UIImage imageNamed:@"products_icon.png"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal ];
    tabBarItem2.selectedImage = [[UIImage imageNamed:@"products_icon.png"]imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal];
    
    
    // also repeat for every tab
    tabBarItem3.image = [[UIImage imageNamed:@"cart_icon.png"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal ];
    tabBarItem3.selectedImage = [[UIImage imageNamed:@"cart_icon.png"]imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal];
    
    // also repeat for every tab
    tabBarItem4.image = [[UIImage imageNamed:@"seemore_icon.png"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal ];
    tabBarItem4.selectedImage = [[UIImage imageNamed:@"seemore_icon.png"]imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal];
}
@end
