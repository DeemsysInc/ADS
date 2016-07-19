//
//  OfferViewController.m
//  Seemore
//
//  Created by Shardul on 22/01/13.
//  Copyright (c) 2013 Prayatna Intellect. All rights reserved.
//

#import "OfferViewController.h"

// Dimmed Drawing Background UIView SubClass
@interface DimBackgroundView : UIView {
}
@end

@implementation DimBackgroundView

- (void) commonInitialization   {
    [self setBackgroundColor:[UIColor clearColor]];
}

- (id) init
{
    if ((self = [super init])) {
        [self commonInitialization];
    }
    return self;
}

- (id) initWithFrame:(CGRect)frame
{
    if ((self = [super initWithFrame:frame])) {
        [self commonInitialization];
    }
    return self;
}

- (void)drawRect:(CGRect)rect {
    
    // Dim BacDrawing code
    CGContextRef context = UIGraphicsGetCurrentContext();
    //Gradient colours
    size_t gradLocationsNum = 2;
    CGFloat gradLocations[2] = {0.0f, 1.0f};
    CGFloat gradColors[8] = {0.0f,0.0f,0.0f,0.0f,0.0f,0.0f,0.0f,1.0f};
    CGColorSpaceRef colorSpace = CGColorSpaceCreateDeviceRGB();
    CGGradientRef gradient = CGGradientCreateWithColorComponents(colorSpace, gradColors, gradLocations, gradLocationsNum);
    CGColorSpaceRelease(colorSpace);
    
    //Gradient center
    CGPoint gradCenter= CGPointMake(self.bounds.size.width/2, self.bounds.size.height/2);
    //Gradient radius
    float gradRadius = MIN(self.bounds.size.width , self.bounds.size.height) ;
    //Gradient draw
    CGContextDrawRadialGradient (context, gradient, gradCenter,
                                 0, gradCenter, gradRadius,
                                 kCGGradientDrawsAfterEndLocation);
    CGGradientRelease(gradient);
}
@end




@implementation OfferViewController

#pragma mark - Synthesized Objects

@synthesize delegate;
@synthesize offerProductImage;
@synthesize acceptButton;
@synthesize closeButton;

#pragma mark - Initialization Methods

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

//- (id) initWithOffer:(Offer *)offer     {
//    
//    self = [super initWithNibName:@"OfferViewController" bundle:nil];
//    if (self) {
//        // Render Offer
//        [self renderOffer:offer];
//    }
//    return self;
//}

#pragma mark - View Life Cycle

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
  }

- (void)viewWillAppear:(BOOL)animated   {
    
    [super viewWillAppear:animated];
    
    // Render Offer
    //[self renderOffer:currentOffer];
}

#pragma mark - Supporting Methods
//
//- (void)renderOffer:(Offer *)offer  {
//    
//    [backgroundView removeFromSuperview];
//    backgroundView = [[DimBackgroundView alloc] initWithFrame:self.view.superview.bounds];
//    backgroundView.alpha = 0.85;
//    currentOffer = offer;
//    
//    if ([super hasValue:currentOffer] && [super hasValue:currentOffer.product]) {
//        
//        [self.view insertSubview:backgroundView atIndex:0];
//        
//        // Load Product Image
//        NSDictionary *parameters = [[NSDictionary alloc] initWithObjectsAndKeys:currentOffer.product, @"product",offerProductImage, @"imageView", nil];
//        [super performSelectorInBackground:@selector(loadProductImage:) withObject:parameters];
//    }
//    else    {
//        // Display Blank Image
//        offerProductImage.image = nil;
//    }
//}
//
//- (void)saveCurrentProductOfferInWishListAndRemoveView:(BOOL)animated {
//    
//    if ([super hasValue:currentOffer]) {
//        
//        // Create Parameters
//        NSDictionary *parameters = [NSDictionary dictionaryWithObjectsAndKeys:currentOffer, @"Offer", [NSNumber numberWithBool:animated], @"RemoveViewAnimated", nil];
//        
//        // Show Loading Indicator
//        MBProgressHUD *hud = [MBProgressHUD showHUDAddedTo:[self.session getAppScreen] animated:YES];
//        
//        [hud showWhileExecuting:@selector(saveProductOfferInWishListAndRemoveView:)
//                       onTarget:self
//                     withObject:parameters
//                       animated:YES];
//    }
//}
//
//- (void)saveProductOfferInWishListAndRemoveView:(NSDictionary *)parameters  {
//    
//    // Parameters
//    Offer *offer = [parameters objectForKey:@"Offer"];
//    BOOL removeViewAnimated = [[parameters objectForKey:@"RemoveViewAnimated"] boolValue];
//    
//    // Save Product Offer In "My Offers" Wish List
//    [self saveProductOffer:offer];
//    
//    // Remove View
//    [self removeViewWithAnimation:removeViewAnimated];
//}
//
//- (void)saveProductOffer:(Offer *)offer  {
//    
//    // Check if product has value
//    if ([self hasValue:offer] && [self hasValue:offer.product]) {
//        
//        [self.session saveProductOffer:offer];
//    }
//}

- (void)showOnView:(UIView *)parentView animated:(BOOL)animated     {
    
    if (![super hasValue:parentView]) {
        NSLog(@"WARNING >> Parent View is NULL");
        return;
    }
    
    if (animated) {
        
        self.view.userInteractionEnabled = NO;
        self.view.alpha = 0.0;
        [self.view removeFromSuperview];
        self.view.frame = parentView.bounds;
        [parentView addSubview:self.view];
        
        [UIView animateWithDuration:0.3
                              delay:0.0
                            options:UIViewAnimationCurveEaseIn | UIViewAnimationOptionBeginFromCurrentState
                         animations:^{
                             
                             self.view.alpha = 1.0;
                         }
                         completion:^(BOOL finished) {
                             self.view.userInteractionEnabled = YES;
                         }];
    }
    else    {
        
        [self.view removeFromSuperview];
        self.view.frame = parentView.bounds;
        [parentView addSubview:self.view];
        self.view.alpha = 1.0;
        self.view.userInteractionEnabled = YES;
    }
}

- (void)removeViewWithAnimation:(BOOL)animated  {
    
    if (animated) {
        
        self.view.userInteractionEnabled = NO;
        
        [UIView animateWithDuration:0.3
                              delay:0.0
                            options:UIViewAnimationCurveEaseIn | UIViewAnimationOptionBeginFromCurrentState
                         animations:^{
                             
                             self.view.alpha = 0.0;
                         }
                         completion:^(BOOL finished) {
                             [self.view removeFromSuperview];
                             self.view.userInteractionEnabled = YES;
                             
                             if (delegate) {
                                 [delegate offerViewDidRemove:self];
                             }
                         }];
    }
    else    {
        
        self.view.alpha = 0.0;
        [self.view removeFromSuperview];
        self.view.userInteractionEnabled = YES;
        
        if (delegate) {
            [delegate offerViewDidRemove:self];
        }
    }
}

#pragma mark - Button Click Events

- (IBAction)acceptButtonClicked:(id)sender   {
    
    
        
        if ([delegate respondsToSelector:@selector(offerViewDidPressAcceptButton:)]) {
            [delegate offerViewDidPressAcceptButton:self];
        }
        
    
}

- (IBAction)closeButtonClicked:(id)sender   {
    
    if (delegate) {
        [delegate offerViewDidPressCloseButton:self];
    }
}

#pragma mark - LoginDelegate
- (void) closeLoginViewController {
     
        shouldSaveOfferAfterLogin = NO;
        [self acceptButtonClicked:acceptButton];
   
}

#pragma mark - Memory Management

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
