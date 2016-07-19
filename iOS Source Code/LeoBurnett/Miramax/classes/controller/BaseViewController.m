//
//  BaseViewController.m
//  Seemore
//
//  Created by Vishal Patel on 05/06/12.
//  Copyright (c) 2012 Pillar Technologies. All rights reserved.
//

#import "BaseViewController.h"

@implementation BaseViewController
@synthesize session = _session;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        self.session = [SessionManager getInstance];
        //self.userDefault = [UserDefaultManager getInstance];
    }
    return self;
}

- (BOOL) hasValue: (id) field {
    if(field!=nil && (NSNull *)field != [NSNull null]){
        if([field isKindOfClass:[NSString class]]) {
            if([field length]>0) {
                return YES;
            }
        } else {
            return YES;
        }
    }
    return NO;
}

-(MBProgressHUD *) mbProgressHUD {
    MBProgressHUD *HUD = [[MBProgressHUD alloc] initWithView:self.navigationController.view];
    [self.navigationController.view addSubview:HUD];
    HUD.delegate = self;
    return HUD;
}

- (void) releaseView: (UIView *)view {
    if([self hasValue:view]) {
        [view removeFromSuperview];
    }
}

- (MiramaxAppDelegate *) appDelegate {
    return (MiramaxAppDelegate *) [[UIApplication sharedApplication] delegate];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    // Enable Notification
   // [self startNotifications];
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
}

- (void) viewWillDisappear:(BOOL)animated   {
    
    [super viewWillDisappear:animated];
    
    // Disable Notification
    [self resignNotifications];
}

- (void) loadImageWithURL:(NSString *)imageUrl andCallBlock:(void(^)(UIImage *image))block {
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
        //NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
        //UIImage *image = [[UIImage alloc] initWithData: data];
        //if (block && image.CGImage) {
            //block(image);
        //}
    });
}

//- (void) loadProductImage:(NSDictionary *)parameters  {
//    @autoreleasepool {
//        Product *product = [parameters objectForKey:@"product"];
//        UIImageView *imageView = [parameters objectForKey:@"imageView"];
//        
//        if (product.imageView.image) {
//            imageView.image = product.imageView.image;
//        } else {
//            NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:product.imageUrl];
//            UIImage *image = [[UIImage alloc] initWithData: data];
//            if (image.CGImage) {
//                imageView.image = image;
//                product.imageView.image = image;
//            }
//        }
//    
//    }
//}
//
//- (void) renderInstructions: (enum INSTRUCTION_PAGE) instructionPage parentView:(UIView *)parentView {
//    self.instructionViewController = [[InstructionsViewController alloc] init];
//    if([self.instructionViewController isInstructionAvailable:instructionPage]) {
//        [parentView addSubview:self.instructionViewController.view];
//        [self.instructionViewController renderInstruction];
//        [[InstructionsManager getInstance] actionDisplayedInstruction:self.instructionViewController.instruction];
//    }
//}

//- (void) removeInstruction {
//    if(self.instructionViewController && self.instructionViewController.view) {
//        [self.instructionViewController removeInstruction];
//    }
//}

- (IBAction) logoClicked:(id)sender {
   // [[SessionManager getInstance] showSeemoreLogoClickActions];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

#pragma mark - Cached Image Download Method

//- (void)startNotifications  {
//    
//    // Register Notification when MWPhoto Image is available after Downloading
//    [[NSNotificationCenter defaultCenter] addObserver:self
//											 selector:@selector(MWPhotoImageDownloaded:)
//												 name:MWPHOTO_LOADING_DID_END_NOTIFICATION
//											   object:nil];
//}
//
//- (void)resignNotifications {
//    
//    // Resign MWPhoto Image Available Notification
//    [[NSNotificationCenter defaultCenter] removeObserver:self
//                                                    name:MWPHOTO_LOADING_DID_END_NOTIFICATION
//                                                  object:nil];
//}
//
//// This method is loaded when MWPhoto underlying Image is Available
//- (void)MWPhotoImageDownloaded:(NSNotification *)notification {
//    
//    MWPhoto *photo = (MWPhoto *) [notification object];
//    
//    // Assign Image to appropriate objects
//    if (photo.underlyingImage!=nil && photo.underlyingImage.CGImage) {
//        
//        id object = [photo.parameters objectForKey:@"ImageViewReference"];
//        if ([object isKindOfClass:[UIImageView class]]) {
//            UIImageView *imageView = (UIImageView *) object;
//            imageView.image = photo.underlyingImage;
//        }
//        else if ([object isKindOfClass:[UIButton class]])   {
//            UIButton *button = (UIButton *) object;
//            button.imageView.contentMode = UIViewContentModeScaleAspectFit;
//            [button setImage:photo.underlyingImage forState:UIControlStateNormal];
//        }
//    }
//    
//    // Cleanup Photo Content
//    [photo unloadUnderlyingImage];
//    photo.parameters = nil;
//    photo = nil;
//}

@end
