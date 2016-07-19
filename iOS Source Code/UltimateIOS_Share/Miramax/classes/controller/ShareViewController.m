//
//  ShareViewController.m
//  Miramax
//
//  Created by Admin on 09/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "ShareViewController.h"
#import "Constant.h"
#import "SocialManager.h"
#import "MiramaxAppDelegate.h"

@interface ShareViewController ()

@end

@implementation ShareViewController
@synthesize imageView = _imageView;
@synthesize image = _image;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    return self;
}

#pragma mark - button clicked
- (IBAction)backButtonClicked:(id)sender {
    NSLog(@"backButtonClicked");
    [self.navigationController popViewControllerAnimated:YES];
}

- (IBAction)saveImageButtonClicked:(id)sender {
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

- (IBAction)imButtonClicked:(id)sender {
    NSLog(@"imButtonClicked");
    NSString *smsBody = [[NSString alloc] initWithFormat:@"Miramax"];
    [self sendSMS:smsBody recipientList:[[NSArray alloc] init]];
}

- (IBAction)emailButtonClicked:(id)sender {
    NSLog(@"emailButtonClicked");
    [self sendMail];
}

//- (IBAction)facebookButtonClicked:(id)sender {
//    NSLog(@"facebookButtonClicked");
//    SocialManager *socialManager = [SocialManager getInstance];
//    [socialManager fbCheckForPreviouslySavedAccessTokenInfo];
//    if(![socialManager isFBConnected]) {
//        [[socialManager facebook] authorize:[socialManager fbPermissions]];
//    } else {
//        [self preparePostToFacebookAlertView];
//    }
//}

- (IBAction)twitterButtonClicked:(id)sender {
    NSLog(@"twitterButtonClicked");
    [self postToTwitter];
}

#pragma mark - send sms
- (void)sendSMS:(NSString *)bodyOfMessage recipientList:(NSArray *)recipients
{
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

- (void)messageComposeViewController:(MFMessageComposeViewController *)controller didFinishWithResult:(MessageComposeResult)result
{
    [self dismissModalViewControllerAnimated:YES];
    
    if (result == MessageComposeResultCancelled)
        NSLog(@"Message cancelled");
    else if (result == MessageComposeResultSent)
        NSLog(@"Message sent");
    else
        NSLog(@"Message failed");
}

#pragma mark - send email
-(void)sendMail
{
    Class mailClass = (NSClassFromString(@"MFMailComposeViewController"));
    if (mailClass != nil && [mailClass canSendMail]) {
            [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
            MFMailComposeViewController *picker = [[MFMailComposeViewController alloc] init];
            picker.mailComposeDelegate = self;
            
            picker.subject = [[NSString alloc] initWithFormat:@"Ultimate Software"];
            
            // Set up the recipients.
            NSArray *toRecipients = [[NSArray alloc] init];
            NSArray *ccRecipients = [[NSArray alloc] init];
            NSArray *bccRecipients = [[NSArray alloc] init];
            
            picker.toRecipients = toRecipients;
            picker.ccRecipients = ccRecipients;
            picker.bccRecipients = bccRecipients;
            
            // NSData *imageData = UIImagePNGRepresentation(self.image);
            NSData *imageData = UIImageJPEGRepresentation(self.image, 1);
            [picker addAttachmentData:imageData mimeType:@"image/jpg" fileName:@"ultimate.jpg"];
            
            // Fill out the email body text.
            NSString *emailBody = [[NSString alloc] initWithFormat:@"Being part of Ultimate Software is truly special. Hear about it  from our people firsthand, in their own words!<br><br><a href='http://www.ultimatesoftware.com'>Ultimate Software</a>"];
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

- (void)mailComposeController:(MFMailComposeViewController *)controller
          didFinishWithResult:(MFMailComposeResult)result
                        error:(NSError *)error
{
    [self dismissModalViewControllerAnimated:YES];
}

#pragma mark - Alert view
- (void) preparePostToFacebookAlertView {
    UIAlertView * alert = [[UIAlertView alloc] initWithTitle:@"Facebook" message:@"Want to post photo on your wall?" delegate:self cancelButtonTitle:@"Cancel" otherButtonTitles:@"Post", nil];
    [alert setDelegate:self];
    alert.tag = 100;
    [alert show];
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    if (alertView.tag == 100){
        if (buttonIndex == 0){ // Cancel button
            return;
        }
        if (buttonIndex == 1) { // OK button
            [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
           // [[SocialManager getInstance] setFbRequestDelegate:self];
            [self postToFacebook];
        }
    }
}

#pragma mark - Facebook actions
- (void) postToFacebook {
//    NSString *message = [[NSString alloc] initWithFormat:@"Celebrating 20 years of Tarantino – http://www.TarantinoXX.com \nGet the Miramax Augmented Reality app at the Apple Store http://bit.ly/SyDGrb or Google Play http://bit.ly/TOTt8I"];
//    NSData *imageData = UIImageJPEGRepresentation(self.image, 1.0);
//    
//    NSMutableDictionary *params = [[NSMutableDictionary alloc] initWithObjectsAndKeys:
//                                   message, @"message",
//                                   imageData,@"source",
//                                   nil];
//    
//    [[[SocialManager getInstance] facebook] requestWithGraphPath:@"me/photos" andParams:params andHttpMethod:@"POST" andDelegate:self];
    
  
    
    NSString *filePath = [[NSBundle mainBundle] pathForResource:@"sample" ofType:@"mov"];
    NSData *videoData = [NSData dataWithContentsOfFile:filePath];
    NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
                                   videoData, @"video.mov",
                                   @"video/quicktime", @"contentType",
                                   @"Video Test Title", @"title",
                                   @"Video Test Description", @"description",
								   nil];
//	[[[SocialManager getInstance] facebook]  requestWithGraphPath:@"me/videos"
//                         andParams:params
//                     andHttpMethod:@"POST"
//                       andDelegate:self];

}

#pragma mark - Twitter actions
- (IBAction)postToTwitter {
    if ([TWTweetComposeViewController canSendTweet]) {
        [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        TWTweetComposeViewController *tweetComposeViewController = [[TWTweetComposeViewController alloc] init];
        
        NSString *message = [[NSString alloc] initWithFormat:@".@UltimateHCM"];
        
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

#pragma mark - Facebook RequestDelegate
/**
 * Called just before the request is sent to the server.
 */
- (void)requestLoading:(FBRequest *)request {
    NSLog(@"requestLoading = %@",request);
}

/**
 * Called when the server responds and begins to send back data.
 */
- (void)request:(FBRequest *)request didReceiveResponse:(NSURLResponse *)response {
    NSLog(@"didReceiveResponse = %@",response);
}

/**
 * Called when an error prevents the request from completing successfully.
 */
- (void)request:(FBRequest *)request didFailWithError:(NSError *)error {
    NSLog(@"didFailWithError");
    UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Error"
                                                        message:[NSString stringWithFormat:@"%@",error]
                                                       delegate:self
                                              cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil];
    [alertView show];
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

/**
 * Called when a request returns and its response has been parsed into
 * an object.
 *
 * The resulting object may be a dictionary, an array, a string, or a number,
 * depending on thee format of the API response.
 */
- (void)request:(FBRequest *)request didLoad:(id)result {
    NSLog(@"Facebook request.didLoad");
    NSLog(@"Result = %@", result);
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

/**
 * Called when a request returns a response.
 *
 * The result object is the raw response from the server of type NSData
 */
- (void)request:(FBRequest *)request didLoadRawResponse:(NSData *)data {
    
    NSLog(@"request = %@", data);
}

#pragma mark - Lifecycle
- (void)viewDidLoad
{
    [super viewDidLoad];
    [self.imageView setImage:self.image];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(postFaceBookAlert:)
                                                 name:@"PostFaceBookAlert" object:nil];
    
    CGRect screenBound = [[UIScreen mainScreen] bounds];
    CGSize screenSize = screenBound.size;
    //CGFloat screenWidth = screenSize.width;
    CGFloat screenHeight = screenSize.height;
    
    if (screenHeight==568){
        CGRect videoShareButtonsViewFrame = [videoShareButtonsView frame];
        videoShareButtonsViewFrame.origin.y = 440;  // change the location
        [videoShareButtonsView setFrame:videoShareButtonsViewFrame];
        
    }
}

- (void) postFaceBookAlert:(NSNotification *)notification{
    [self preparePostToFacebookAlertView];
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
