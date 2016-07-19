//
//  ShareViewController.m
//  Trends
//
//  Created by Admin on 09/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "ShareViewController.h"
#import "Constant.h"
#import "SocialManager.h"
#import "TrendsAppDelegate.h"

@interface ShareViewController ()

@end

@implementation ShareViewController
@synthesize imageView = _imageView;
@synthesize image = _image;
@synthesize flickrRequest = _flickrRequest;

CGRect screenBound;
CGSize screenSize;
CGFloat screenWidth;
CGFloat screenHeight;

UIAlertView *errorAlert;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    @try {
        self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
        if (self) {
            [self setupFlickrRequest];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - initWithNibName" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - button clicked
- (IBAction)backButtonClicked:(id)sender {
    @try {
        NSLog(@"backButtonClicked");
        [self.navigationController popViewControllerAnimated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - backButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)saveImageButtonClicked:(id)sender {
    @try {
        NSLog(@"saveImageButtonClicked");
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        if(self.image)
            UIImageWriteToSavedPhotosAlbum(self.image, nil, nil, nil);
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
        UIAlertView *alertView = [[UIAlertView alloc]
                                  initWithTitle:@"Success"
                                  message:@"Photo has been saved successfully."
                                  delegate:self
                                  cancelButtonTitle:@"OK"
                                  otherButtonTitles:nil,
                                  nil];
        [alertView show];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - saveImageButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)imButtonClicked:(id)sender {
    @try {
        NSLog(@"imButtonClicked");
        NSString *smsBody = [[NSString alloc] initWithFormat:@"Trends International"];
        [self sendSMS:smsBody recipientList:[[NSArray alloc] init]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - imButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)emailButtonClicked:(id)sender {
    @try {
        NSLog(@"emailButtonClicked");
        [self sendMail];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - emailButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)facebookButtonClicked:(id)sender {
    @try {
        NSLog(@"facebookButtonClicked");
        SocialManager *socialManager = [SocialManager getInstance];
        [socialManager fbCheckForPreviouslySavedAccessTokenInfo];
        if(![socialManager isFBConnected]) {
            [[socialManager facebook] authorize:[socialManager fbPermissions]];
        } else {
            [self preparePostToFacebookAlertView];
    }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - facebookButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)twitterButtonClicked:(id)sender {
    @try {
        NSLog(@"twitterButtonClicked");
        [self postToTwitter];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - twitterButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (IBAction)flickrButtonClicked:(id)sender {
    @try {
        NSLog(@"flickrButtonClicked");
        NSLog(@"OAuthToken=%@",[TrendsAppDelegate sharedDelegate].flickrContext.OAuthToken);
        if ([[TrendsAppDelegate sharedDelegate].flickrContext.OAuthToken length]) {
            [self preparePostToFlickrAlertView];
        } else {
            [[TrendsAppDelegate sharedDelegate] setupFlickrContext];
            [self authorizeAction];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - flickrButtonClicked" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - send sms
- (void)sendSMS:(NSString *)bodyOfMessage recipientList:(NSArray *)recipients
{
    @try {
        MFMessageComposeViewController *controller = [[MFMessageComposeViewController alloc] init];
        if([MFMessageComposeViewController canSendText])
        {
            controller.body = bodyOfMessage;
            controller.recipients = recipients;
            controller.messageComposeDelegate = self;
            [self presentModalViewController:controller animated:YES];
        } else {
            UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Sorry" message:@"Couldn't send SMS through this device." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            alert.alertViewStyle = UIAlertViewStyleDefault;
            [alert show];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - sendSMS" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)messageComposeViewController:(MFMessageComposeViewController *)controller didFinishWithResult:(MessageComposeResult)result
{
    @try {
        [self dismissModalViewControllerAnimated:YES];
        
        if (result == MessageComposeResultCancelled)
            NSLog(@"Message cancelled");
        else if (result == MessageComposeResultSent)
            NSLog(@"Message sent");
        else
            NSLog(@"Message failed");
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - messageComposeViewController" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - send email
-(void)sendMail
{
    @try {
        Class mailClass = (NSClassFromString(@"MFMailComposeViewController"));
        if (mailClass != nil && [mailClass canSendMail]) {
                [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
                MFMailComposeViewController *picker = [[MFMailComposeViewController alloc] init];
                picker.mailComposeDelegate = self;
                
                picker.subject = [[NSString alloc] initWithFormat:@"Check out my awesome pic!"];
                
                // Set up the recipients.
                NSArray *toRecipients = [[NSArray alloc] init];
                NSArray *ccRecipients = [[NSArray alloc] init];
                NSArray *bccRecipients = [[NSArray alloc] init];
                
                picker.toRecipients = toRecipients;
                picker.ccRecipients = ccRecipients;
                picker.bccRecipients = bccRecipients;
                
               // NSData *imageData = UIImagePNGRepresentation(self.image);
                NSData *imageData = UIImageJPEGRepresentation(self.image, 1);
                [picker addAttachmentData:imageData mimeType:@"image/jpg" fileName:@"Trends.jpg"];
                
                // Fill out the email body text.
                NSString *emailBody = [[NSString alloc] initWithFormat:@"Attached by %@", @"Trends International"];
                
                [picker setMessageBody:emailBody isHTML:YES];
                [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
                // Present the mail composition interface.
                [self presentModalViewController:picker animated:YES];

        }
        else
        {
            UIAlertView *noMailAlert = [[UIAlertView alloc] initWithTitle:@"No Email Account Exist" message:@"You need to set up an email account on the Mail app in order to share photo." delegate:nil cancelButtonTitle:@"Close" otherButtonTitles:nil];
            [noMailAlert show];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - sendMail" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)mailComposeController:(MFMailComposeViewController *)controller
          didFinishWithResult:(MFMailComposeResult)result
                        error:(NSError *)error
{
    @try {
        [self dismissModalViewControllerAnimated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - mailComposeController" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Alert view
- (void) preparePostToFacebookAlertView {
    @try {
        UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Facebook" message:@"Want to post photo on your wall?" delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Post", nil];
        [alert setDelegate:self];
        alert.tag = 100;
        [alert show];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - preparePostToFacebookAlertView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) preparePostToFlickrAlertView {
    @try {
        UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Flickr" message:@"Want to post photo on photostream?" delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Post", nil];
        [alert setDelegate:self];
        alert.tag = 200;
        [alert show];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - preparePostToFlickrAlertView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    @try {
        if (alertView.tag == 100){
            if (buttonIndex == 0){ // Cancel button
                return;
            }
            if (buttonIndex == 1) { // OK button
                [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
                [[SocialManager getInstance] setFbRequestDelegate:self];
                [self postToFacebook];
            }
        }else if (alertView.tag == 200){
            if (buttonIndex == 0){ // Cancel button
                return;
            }
            if (buttonIndex == 1) { // OK button
                [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
                [self uploadToFlickr:self.image];
            }
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - alertView" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Facebook actions
- (void) postToFacebook {
    @try {
        NSString *message = [[NSString alloc] initWithFormat:@"New VirtualShot Image."];
        NSData *imageData = UIImageJPEGRepresentation(self.image, 1.0);
        
        NSMutableDictionary *params = [[NSMutableDictionary alloc] initWithObjectsAndKeys:
                                       message, @"message",
                                       imageData,@"source",
                                       nil];
        
        [[[SocialManager getInstance] facebook] requestWithGraphPath:@"me/photos" andParams:params andHttpMethod:@"POST" andDelegate:self];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - postToFacebook" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Twitter actions
- (IBAction)postToTwitter {
    @try {
        if ([TWTweetComposeViewController canSendTweet]) {
            [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
            TWTweetComposeViewController *tweetComposeViewController = [[TWTweetComposeViewController alloc] init];
            
            NSString *message = [[NSString alloc] initWithFormat:@".@IntlTrends"];
            
            BOOL isInitialText = [tweetComposeViewController setInitialText:message];
            NSLog(@"isInitialText = %d", isInitialText);
            
            [tweetComposeViewController addImage:self.image];

            [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
            [self presentModalViewController:tweetComposeViewController animated:YES];
            
        } else {
            UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Please"
                                                                message:@"Setup Twitter account in Settings."
                                                               delegate:self
                                                      cancelButtonTitle:@"OK"
                                                      otherButtonTitles:nil];
            [alertView show];
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - postToTwitter" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Facebook RequestDelegate
/**
 * Called just before the request is sent to the server.
 */
- (void)requestLoading:(FBRequest *)request {
    @try {
        NSLog(@"requestLoading");
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - requestLoading" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

/**
 * Called when the server responds and begins to send back data.
 */
- (void)request:(FBRequest *)request didReceiveResponse:(NSURLResponse *)response {
    @try {
        NSLog(@"didReceiveResponse");
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - request" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

/**
 * Called when an error prevents the request from completing successfully.
 */
- (void)request:(FBRequest *)request didFailWithError:(NSError *)error {
    @try {
        NSLog(@"didFailWithError");
        UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Error"
                                                            message:[NSString stringWithFormat:@"%@",error]
                                                           delegate:self
                                                  cancelButtonTitle:@"OK"
                                                  otherButtonTitles:nil];
        [alertView show];
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - request didFailWithError" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

/**
 * Called when a request returns and its response has been parsed into
 * an object.
 *
 * The resulting object may be a dictionary, an array, a string, or a number,
 * depending on thee format of the API response.
 */
- (void)request:(FBRequest *)request didLoad:(id)result {
    @try {
        NSLog(@"Facebook request.didLoad");
        NSLog(@"Result = %@", result);
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - request didLoad" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

/**
 * Called when a request returns a response.
 *
 * The result object is the raw response from the server of type NSData
 */
- (void)request:(FBRequest *)request didLoadRawResponse:(NSData *)data {
    
}

#pragma mark - Flickr
#pragma mark Actions
- (OFFlickrAPIRequest *)setupFlickrRequest
{
    @try {
        if (!self.flickrRequest) {
            self.flickrRequest = [[OFFlickrAPIRequest alloc] initWithAPIContext:[TrendsAppDelegate sharedDelegate].flickrContext];
            self.flickrRequest.delegate = self;
            self.flickrRequest.requestTimeoutInterval = 60.0;
        }
        
        return self.flickrRequest;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - setupFlickrRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) uploadToFlickr:(UIImage *)image
{
    @try {
        NSData *JPEGData = UIImageJPEGRepresentation(image, 1.0);
        self.flickrRequest.sessionInfo = kUploadImageStep;
        [self.flickrRequest uploadImageStream:[NSInputStream inputStreamWithData:JPEGData] suggestedFilename:@"Trends" MIMEType:@"image/jpeg" arguments:[NSDictionary dictionaryWithObjectsAndKeys:@"0", @"is_public", nil]];
        [UIApplication sharedApplication].idleTimerDisabled = YES;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - uploadToFlickr" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) authorizeAction
{
    @try {
        if ([[TrendsAppDelegate sharedDelegate].flickrContext.OAuthToken length]) {
            [[TrendsAppDelegate sharedDelegate] setAndStoreFlickrAuthToken:nil secret:nil];
        }
        
        self.flickrRequest.sessionInfo = kFetchRequestTokenStep;
        [self.flickrRequest fetchOAuthRequestTokenWithCallbackURL:[NSURL URLWithString: kTrendsCallbackURLBaseString]];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - authorizeAction" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - OFFlickrAPIRequest delegate
- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didObtainOAuthRequestToken:(NSString *)inRequestToken secret:(NSString *)inSecret
{
    @try {
        [TrendsAppDelegate sharedDelegate].flickrContext.OAuthToken = inRequestToken;
        [TrendsAppDelegate sharedDelegate].flickrContext.OAuthTokenSecret = inSecret;
        
        NSURL *authURL = [[TrendsAppDelegate sharedDelegate].flickrContext userAuthorizationURLWithRequestToken:inRequestToken requestedPermission:OFFlickrWritePermission];
        [[UIApplication sharedApplication] openURL:authURL];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - flickrAPIRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didCompleteWithResponse:(NSDictionary *)inResponseDictionary
{
    @try {
        if ([inRequest.sessionInfo isEqual: kUploadImageStep]) {
            NSLog(@"%@", inResponseDictionary);
            NSString *photoID = [[inResponseDictionary valueForKeyPath:@"photoid"] textContent];
            
            self.flickrRequest.sessionInfo = kSetImagePropertiesStep;
            [self.flickrRequest callAPIMethodWithPOST:@"flickr.photos.setMeta" arguments:[NSDictionary dictionaryWithObjectsAndKeys:photoID, @"photo_id", @"Trends International", @"title", @"Uploaded using Trends International", @"description", nil]];
        } else if ([inRequest.sessionInfo isEqual: kSetImagePropertiesStep]) {
            [UIApplication sharedApplication].idleTimerDisabled = NO;
        }
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - flickrAPIRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest didFailWithError:(NSError *)inError
{
    @try {
        NSLog(@"Flickr didFailWithError=%@",inError);
        if ([inRequest.sessionInfo isEqual: kUploadImageStep]) {
            [UIApplication sharedApplication].idleTimerDisabled = NO;
            [[[UIAlertView alloc] initWithTitle:@"API Failed" message:[inError description] delegate:nil cancelButtonTitle:@"Dismiss" otherButtonTitles:nil] show];
        } else {
            [[[UIAlertView alloc] initWithTitle:@"API Failed" message:[inError description] delegate:nil cancelButtonTitle:@"Dismiss" otherButtonTitles:nil] show];
        }
        [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - flickrAPIRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)flickrAPIRequest:(OFFlickrAPIRequest *)inRequest imageUploadSentBytes:(NSUInteger)inSentBytes totalBytes:(NSUInteger)inTotalBytes
{
    @try {
        if (inSentBytes == inTotalBytes) {
            NSLog(@"Waiting for Flickr...");
        } else {
            NSLog(@"%@",[NSString stringWithFormat:@"%u/%u (KB)", inSentBytes / 1024, inTotalBytes / 1024]);
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - flickrAPIRequest" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Lifecycle
- (void)viewDidLoad
{
    @try {
        [super viewDidLoad];
        [self.imageView setImage:self.image];
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(postFaceBookAlert:)
                                                     name:@"PostFaceBookAlert" object:nil];
        screenBound = [[UIScreen mainScreen] bounds];
        screenSize = screenBound.size;
        screenWidth = screenSize.width;
        screenHeight = screenSize.height;
        
        if (screenHeight==568){
            CGRect imageViewFrame = [self.imageView frame];
            imageViewFrame.size.height = 568;  // change the location
            imageViewFrame.origin.y = 0;  // change the location
            [self.imageView setFrame:imageViewFrame];

        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - viewDidLoad" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void) postFaceBookAlert:(NSNotification *)notification{
    @try {
        [self preparePostToFacebookAlertView];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - postFaceBookAlert" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (void)viewDidUnload
{
    @try {
        [super viewDidUnload];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - viewDidUnload" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    @try {
        return (interfaceOrientation == UIInterfaceOrientationPortrait);
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Share View - shouldAutorotateToInterfaceOrientation" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end
