//
//  WebViewViewController.h
//  Junaio
//
//  Created by Stefan Misslinger on 10/5/09.
//  Copyright 2009 metaio, Inc.. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MetaioViewControllerClosingCallback.h"

typedef void (^WebViewViewControllerCallbackBlock)(void);

@interface WebViewViewController : UIViewController<MetaioViewControllerClosingCallback,UIWebViewDelegate, UIPopoverControllerDelegate> {

    UIWebView*		webView;
    UIButton*		btnClose;
	
    UIActivityIndicatorView *           activityIndicator;
    UIInterfaceOrientation              m_currentInterfaceOrientation;
}
@property (nonatomic, copy) MetaioActionBlock viewDidDisappearAction;

@property (nonatomic, retain) IBOutlet UIActivityIndicatorView *activityIndicator;

@property (nonatomic, retain) IBOutlet UIWebView*		webView;
@property (nonatomic, retain) IBOutlet UIButton*		btnClose;
@property (retain, nonatomic) IBOutlet UIButton *btnShare;
@property (nonatomic, retain) NSURL*			url;


- (IBAction) buttonClose;
- (IBAction)buttonSharePushed:(id)sender;

-(id) initializeWithURL: (NSString*) url;

@end
