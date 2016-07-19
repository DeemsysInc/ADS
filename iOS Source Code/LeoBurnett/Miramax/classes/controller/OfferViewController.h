//
//  OfferViewController.h
//  Seemore
//
//  Created by Shardul on 22/01/13.
//  Copyright (c) 2013 Prayatna Intellect. All rights reserved.
//

#import "BaseViewController.h"
//#import "LoginViewController.h"
//#import "Offer.h"

@class DimBackgroundView;

@protocol OfferViewDelegate;

@interface OfferViewController : BaseViewController     {
    
    DimBackgroundView *backgroundView;
    
    id <OfferViewDelegate> delegate;
    //Offer *currentOffer;
    
    IBOutlet UIImageView *offerProductImage;
    IBOutlet UIButton *acceptButton;
    IBOutlet UIButton *closeButton;
    
    //LoginViewController *loginViewController;
    BOOL shouldSaveOfferAfterLogin;
}

@property (nonatomic, strong) id <OfferViewDelegate> delegate;
@property (nonatomic, retain) UIImageView *offerProductImage;
@property (nonatomic, retain) UIButton *acceptButton;
@property (nonatomic, retain) UIButton *closeButton;

//- (id)initWithOffer:(Offer *)offer;

//- (void)renderOffer:(Offer *)offer;
- (void)showOnView:(UIView *)parentView animated:(BOOL)animated;
- (void)removeViewWithAnimation:(BOOL)animated;

- (void)saveCurrentProductOfferInWishListAndRemoveView:(BOOL)animated;
//- (void)saveProductOffer:(Offer *)offer;

@end



// Delegate Methods
@protocol OfferViewDelegate <NSObject>

@optional
- (void) offerViewDidPressAcceptButton:(OfferViewController *)offerController;

@required
- (void) offerViewDidPressCloseButton:(OfferViewController *)offerController;
- (void) offerViewDidRemove:(OfferViewController *)offerController;

@end
