//
//  MiramaxViewController.m
//  Miramax
//
//  Created by Admin on 08/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//
#import "ShareViewController_iPhone.h"
#import "ShareViewController_iPad.h"
#import "MiramaxViewController.h"
#import <QuartzCore/QuartzCore.h>
#import <AVFoundation/AVFoundation.h>
#import "UIImage+Resize.h"
#import "ShareViewController.h"
#import "Poster.h"
#import "Marker.h"
#import "SessionManager.h"
#import "Constant.h"
#import "Reachability.h"
#import "CacheManager.h"

enum ScanMode {
    imageMarkers,
    metaioMarkers,
    barCodes
};

@implementation MiramaxViewController {
    ScanMode scanMode;
}

@synthesize gestureView = _gestureView;
@synthesize shapeImageView = _shapeImageView;
@synthesize captureButton = _captureButton;
@synthesize triggerButton = _triggerButton;
@synthesize characterTimer = _characterTimer;
@synthesize shapeSlider = _shapeSlider;
@synthesize posterData = _posterData;
@synthesize actionSheet = _actionSheet;
@synthesize viewFrame = _viewFrame;
@synthesize aboutView = _aboutView;
@synthesize tutorialView = _tutorialView;
@synthesize animationFlag = _animationFlag;
@synthesize helpMessageLabel = _helpMessageLabel;

static CGSize originalShapeSize;
static CGFloat currentScale=1.0f;
static CGPoint currentShapeCenterPoint;
static CGPoint startPanGesturePoint;

static int remainingSeconds;
static int currentMarkerId;
static BOOL isFirstTime=YES;
MBProgressHUD *hud;
- (id)init
{
    self = [super init];
    if (self) {
    }
    return self;
}

void main_sync(dispatch_block_t block) {
    if (dispatch_get_current_queue() == dispatch_get_main_queue()) {
        block();
    } else {
        dispatch_sync(dispatch_get_main_queue(), block);
    }
}


#pragma mark - button clicked
- (IBAction)captureImageButtonClicked:(id)sender {
    [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    UIImage *screenShot = [[super getScreenshotImage] fixOrientation];
    [self hideCharacterSystem];
    [self pushShareView:[self mergeCharacterWith:screenShot]];
    [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
}

- (void) triggerMarker:(int)markerId {
    if (!(remainingSeconds > 0) || currentMarkerId!=markerId) {
        [self loadCharacter: markerId];
    }
    [self scheduleCharacterTimer];
}

- (IBAction)triggerImageButtonClicked:(id)sender {
    [self triggerMarker:1];
}

- (IBAction)sliderValueChanged:(UISlider *)sender {
    CGFloat currentValue =[sender value];
    currentScale = self.viewFrame.size.width/originalShapeSize.width*(currentValue/100);
    [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
}
- (IBAction)backTutorialButtonClicked:(id)sender
{
    [self.view addSubview:self.tutorialView];
    self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                         self.tutorialView.superview.frame.size.height,
                                         self.tutorialView.frame.size.width,
                                         self.tutorialView.frame.size.height);
    
    [UIView animateWithDuration:0.5 animations:^{
        self.tutorialView.center = self.tutorialView.superview.center;
    }];
}
- (IBAction)aboutButtonClicked:(id)sender
{
    [self.view addSubview:self.aboutView];
    self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                      self.aboutView.superview.frame.size.height,
                                      self.aboutView.frame.size.width,
                                      self.aboutView.frame.size.height);
    
    [UIView animateWithDuration:0.5 animations:^{
        self.aboutView.center = self.aboutView.superview.center;
    }];
}
- (IBAction)continueAboutClicked:(id)sender
{
    [UIView animateWithDuration:0.6 animations:^{
        self.aboutView.frame = CGRectMake(self.aboutView.frame.origin.x,
                                          self.aboutView.superview.frame.size.height,
                                          self.aboutView.frame.size.width,
                                          self.aboutView.frame.size.height);
        [self performSelector:@selector(removeAboutView) withObject:nil afterDelay:0.6];
    }];
}

- (IBAction)continueTutorialClicked:(id)sender
{
    [self hideCharacterSystem];
    self.helpMessageLabel.hidden = NO;
    currentMarkerId = 0;
    [UIView animateWithDuration:0.6 animations:^{
        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                             self.tutorialView.superview.frame.size.height,
                                             self.tutorialView.frame.size.width,
                                             self.tutorialView.frame.size.height);
        [self performSelector:@selector(removeTutorialView) withObject:nil afterDelay:0.6];
    }];
    [self loadOrCheckMarkersFromService];
}

- (void)removeAboutView
{
    [self.aboutView removeFromSuperview];
}

- (void)removeTutorialView
{
    [self.aboutView removeFromSuperview];
}

- (IBAction)seemoreLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.seemoreinteractive.com"]];
}

- (IBAction)metaioLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.metaio.com/imprint/"]];
}
- (IBAction)miramaxLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.miramax.com"]];
}
- (IBAction)fbMiramaxLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.facebook.com/Miramax"]];
}
- (IBAction)twitterLinkClicked:(id)sender
{
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://twitter.com/Miramax"]];
}


#pragma mark - Supporting Methods
- (void) initGestureView {
    UIPinchGestureRecognizer *pinchGesture = [[UIPinchGestureRecognizer alloc] initWithTarget:self action:@selector(handlePinch:)];
    [self.gestureView addGestureRecognizer:pinchGesture];
    UIPanGestureRecognizer *panRecognizer = [[UIPanGestureRecognizer alloc] initWithTarget:self action:@selector(move:)];
	panRecognizer.minimumNumberOfTouches = 1;
	panRecognizer.maximumNumberOfTouches = 1;
	[self.gestureView addGestureRecognizer:panRecognizer];
}

#pragma mark - gesture
-(void)handlePinch:(UIPinchGestureRecognizer*)sender {
    if(remainingSeconds > 0) {
        CGFloat scale = [sender scale];
        if (sender.state == UIGestureRecognizerStateEnded) {
            currentScale = currentScale*scale;
        } else {
            [self renderShape:scale*currentScale centerPoint:currentShapeCenterPoint];
            self.shapeSlider.value = scale*currentScale*originalShapeSize.width/self.viewFrame.size.width*100;
        }
    }
}

-(void)move:(id)sender {
    if(remainingSeconds > 0) {
        CGPoint translatedPoint = [(UIPanGestureRecognizer*)sender translationInView:self.gestureView];
        if([(UIPanGestureRecognizer*)sender state] == UIGestureRecognizerStateBegan) {
            startPanGesturePoint = [[sender view] center];
        }
        CGPoint centerPoint = CGPointMake(currentShapeCenterPoint.x + translatedPoint.x, currentShapeCenterPoint.y + translatedPoint.y);
        [self renderShape:currentScale centerPoint:centerPoint];
        
        if([(UIPanGestureRecognizer*)sender state] == UIGestureRecognizerStateEnded) {
            currentShapeCenterPoint = centerPoint;
        }
    }
}

- (void) renderShape:(CGFloat)scale centerPoint:(CGPoint)centerPoint{
    if(0<centerPoint.x && centerPoint.x<self.viewFrame.size.width && 0<centerPoint.y && centerPoint.y<self.viewFrame.size.height) {
        self.shapeImageView.frame = CGRectMake(centerPoint.x-originalShapeSize.width*scale/2,
                                               centerPoint.y-originalShapeSize.height*scale/2,
                                               originalShapeSize.width*scale,
                                               originalShapeSize.height*scale
                                               );
    }
}

#pragma mark - capture
- (void) pushShareView:(UIImage *)image {
    int width = self.viewFrame.size.width;
    if (width <= 320) {
        ShareViewController_iPhone *viewController = [[ShareViewController_iPhone alloc] init];
        viewController.image = image; 
        [self.navigationController pushViewController:viewController animated:YES];
    }else{
        ShareViewController_iPad *viewController = [[ShareViewController_iPad alloc] init];
        viewController.image = image;
        [self.navigationController pushViewController:viewController animated:YES];
    }
}

-(UIImage *) mergeCharacterWith:(UIImage *)image {
    CGFloat scale = [UIScreen mainScreen].scale;
    CGRect image2Rect = self.shapeImageView.frame;
    image2Rect = CGRectMake((20+image2Rect.origin.x)*scale, image2Rect.origin.y*scale, image2Rect.size.width*scale, image2Rect.size.height*scale);
    return [self mergeImages:image image1Size:image.size image2:self.shapeImageView.image image2Rect:image2Rect image3:[UIImage imageNamed:@"watermark.png"]];
}

- (UIImage *) mergeImages:(UIImage *)image1 image1Size:(CGSize)image1Size image2:(UIImage *)image2 image2Rect:(CGRect)image2Rect image3:(UIImage *)image3{
    UIGraphicsBeginImageContext(image1Size);
    [image1 drawInRect:CGRectMake(0,0,image1Size.width,image1Size.height)];
    [image2 drawInRect:image2Rect];
    [image3 drawInRect:CGRectMake(50,image1Size.height-image3.size.height-50,image3.size.width,image3.size.height)];
    UIImage *mergedImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return mergedImage;
}

#pragma mark -
#pragma mark Character Timer Logic
- (void) scheduleCharacterTimer	{
    [self unscheduleCharacterTimer];
	self.characterTimer = [NSTimer scheduledTimerWithTimeInterval:1.0 target:self selector:@selector(updateElapsedTime:) userInfo:nil repeats:YES];
    remainingSeconds = 30;
}

- (void) unscheduleCharacterTimer    {
    if (self.characterTimer != nil) {
        [self.characterTimer invalidate];
        self.characterTimer = nil;
    }
}

-(void) triggerAfterFinishCharacterTimer    {
    [self unscheduleCharacterTimer];
    [self hideCharacterSystem];
    self.helpMessageLabel.hidden = NO;
}

// Update the timer once a second.
- (void) updateElapsedTime:(NSTimer *)timer
{
    remainingSeconds--;
    if (remainingSeconds <= 0) {
        [self triggerAfterFinishCharacterTimer];
    }
}

#pragma mark - Metaio Delegate Callbacks
- (void) onTrackingEvent: (std::vector<metaio::Pose>) poses {
    if(isFirstTime) {
        isFirstTime = NO;
        return;
    }
    if(!poses.empty()) {
        metaio::Pose pose = poses.front();
        int cosId = pose.cosID;
        [self triggerMarker:cosId];
    }
}


- (void) deleteMetaioDirectory {
    [[NSFileManager defaultManager] removeItemAtPath:[self getMetaioPath] error: nil];
}

- (void) createMetaioDirectory {
    [[NSFileManager defaultManager] createDirectoryAtPath:[self getMetaioPath] withIntermediateDirectories:YES attributes:nil error:nil];
}

-(NSString *) buildMarkerFile:(NSArray *) postersData {
    NSString *path = [self getMetaioPath];
    
    [[NSFileManager defaultManager] createDirectoryAtPath:path withIntermediateDirectories:YES attributes:nil error:nil];
    NSMutableString *contents = [[NSMutableString alloc] init];
    
    [contents appendString: @"<?xml version=\"1.0\"?>"];
    [contents appendString: @"<TrackingData>"];
    [contents appendString: @"<Sensors>"];
    NSString *subType =  @"fast";
    NSString *sensor = [[NSString alloc] initWithFormat:@"<Sensor type=\"FeatureBasedSensorSource\" subtype=\"%@\">", subType, nil];
    [contents appendString: sensor];
    [contents appendString: @"<SensorID>FeatureTracking1</SensorID>"];
    [contents appendString: @"<Parameters>"];
    [contents appendString: @"<FeatureBasedParameters>"];
    [contents appendString: @"</FeatureBasedParameters>"];
    [contents appendString: @"</Parameters>"];
    
    for(Poster *poster in postersData) {
        for (Marker *marker in [poster posterMarkers]) {
        [contents appendString: @"<SensorCOS>"];
        [contents appendString: @"<SensorCosID>"];
        [contents appendString: [[NSString alloc] initWithFormat:@"%@%ld", @"Patch:", [marker markerId]]];
        [contents appendString: @"</SensorCosID>"];
        [contents appendString: @"<Parameters>"];
        [contents appendString: @"<referenceImage widthMM=\""];
        [contents appendString: [[NSString alloc] initWithFormat:@"%ld", [marker width]]];
        [contents appendString: @"\" heightMM=\""];
        [contents appendString: [[NSString alloc] initWithFormat:@"%ld", [marker height]]];
        [contents appendString: @"\">"];
        [contents appendString: [[marker markerImage] lastPathComponent]];
        [contents appendString: @"</referenceImage>"];
        [contents appendString: @"</Parameters>"];
        [contents appendString: @"</SensorCOS>"];
        }
    }
    
    [contents appendString: @"</Sensor>"];
    [contents appendString: @"</Sensors>"];
    [contents appendString: @"<Connections>"];
    
    for(Poster *poster in postersData) {
        for (Marker *marker in [poster posterMarkers]) {
        [contents appendString: @"<COS>"];
        [contents appendString: @"<COSName>MarkerlessCOS</COSName>"];
        [contents appendString: @"<Fuser type=\"SmoothingFuser\">"];
        [contents appendString: @"<Parameters>"];
        [contents appendString: @"<AlphaRotation>0.5</AlphaRotation> "];
        [contents appendString: @"<AlphaTranslation>0.8</AlphaTranslation>"];
        [contents appendString: @"<KeepPoseForNumberOfFrames>0</KeepPoseForNumberOfFrames>"];
        [contents appendString: @"</Parameters>"];
        [contents appendString: @"</Fuser>"];
        [contents appendString: @"<SensorSource trigger=\"1\">"];
        [contents appendString: @"<SensorID>FeatureTracking1</SensorID>"];
        [contents appendString: @"<SensorCosID>"];
        [contents appendString: [[NSString alloc] initWithFormat:@"%@%ld", @"Patch:", [marker markerId]]];
        [contents appendString: @"</SensorCosID>"];
        [contents appendString: @"<HandEyeCalibration>"];
        [contents appendString: @"<TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset>"];
        [contents appendString: @"<RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset>"];
        [contents appendString: @"</HandEyeCalibration>"];
        [contents appendString: @"<COSOffset>"];
        [contents appendString: @"<TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset>"];
        [contents appendString: @"<RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset>"];
        [contents appendString: @"</COSOffset>"];
        [contents appendString: @"</SensorSource>"];
            [contents appendString: @"</COS>"];
        }
    }
    
    [contents appendString: @"</Connections>"];
    [contents appendString: @"</TrackingData>"];
    
    NSString *fileName = [path stringByAppendingPathComponent:@"markers.xml"];
    [contents writeToFile:fileName atomically:NO encoding:NSUTF8StringEncoding error:nil];
    return fileName;
}

-(NSString *) getMetaioPath {
    NSString *cacheDirectory = [NSSearchPathForDirectoriesInDomains
                                (NSCachesDirectory, NSUserDomainMask, YES) objectAtIndex:0];
    return [[NSString alloc] initWithFormat:@"%@%@", cacheDirectory, @"/Metaio/MarkerData"];
}

- (void) downloadPosterMarkers: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
    NSString *path = [self getMetaioPath];
    NSUInteger count = [postersData count];
    [postersData enumerateObjectsUsingBlock:^(id posterMarker, NSUInteger idx, BOOL *stop) {
        if (progressIndicator) {
            progressIndicator(idx, count);
        }
        [self downloadImagesOnPosterMarker:posterMarker toPath:path];
    }];
    
     if (progressIndicator) {
        progressIndicator(count, count);
    }
}
- (void) downloadPosterMarkersVisual: (NSArray *) postersData progressIndicator:(void(^)(int current, int count))progressIndicator {
    NSString *path = [self getMetaioPath];
    NSUInteger count = [postersData count];
    [postersData enumerateObjectsUsingBlock:^(id posterVisual, NSUInteger idx, BOOL *stop) {
        if (progressIndicator) {
            progressIndicator(idx, count);
        }
        [self downloadImagesOnPosterVisual:posterVisual toPath:path];
    }];
    if (progressIndicator) {
        progressIndicator(count, count);
    }
}

- (void) downloadImagesOnPosterMarker:(Poster *) poster toPath:(NSString *) path {
    NSString *imageUrl;
    for (Marker *marker in [poster posterMarkers]) {
        imageUrl = [marker markerImage];
    }
    NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
    NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
    if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
        data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
    }
    NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
    [data writeToFile:cacheFilePath atomically:YES];
}
- (void) downloadImagesOnPosterVisual:(Poster *) poster toPath:(NSString *) path {
    NSString *imageUrl= [poster posterImage];
    NSData *data = [[CacheManager sharedInstance] invokeSynchronousCachedRequest:imageUrl];
    NSString *suffix = [[[[imageUrl lastPathComponent]componentsSeparatedByString:@"."] lastObject] lowercaseString];
    if ([@"jpeg" isEqualToString:suffix] && [@"png" isEqualToString:suffix]) {
        data = UIImagePNGRepresentation([[UIImage alloc] initWithData:data]);
    }
    NSString *cacheFilePath = [path stringByAppendingPathComponent: [imageUrl lastPathComponent]];
    [[SessionManager getInstance]downloadedImageURL:cacheFilePath];
    [data writeToFile:cacheFilePath atomically:YES];
}

#pragma mark - Metaio
- (void)loadTrackingDataFromUserDefault {
    NSLog(@"loadTrackingDataFromUserDefault");
    [[SessionManager getInstance].posterVisual removeAllObjects];
    if([self noMarkerAvailableLocally]) {
        [self loadDefaultMarker];
    } else {
        NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
        [SessionManager getInstance].posterVisual = data;
        NSString *trackingDataFile = [[self getMetaioPath] stringByAppendingPathComponent:@"markers.xml"];
        main_sync(^{
            isFirstTime = YES;
            unifeyeMobile->setTrackingData([trackingDataFile UTF8String]);
        });
    }
}
- (void) hideCharacterSystem {
    self.captureButton.hidden = YES;
    self.shapeImageView.hidden = YES;
    self.shapeSlider.hidden = YES;
}
- (void) unHideCharacterSystem {
    self.shapeImageView.hidden = NO;
    self.captureButton.hidden = NO;
    self.shapeSlider.hidden = NO;
}

- (void) loadCharacter:(int)markerId {
    
    currentMarkerId = markerId;
    
    self.helpMessageLabel.hidden = YES;
    self.shapeSlider.value = 60;
    NSArray *imageFilePath = [[SessionManager getInstance] getPosterVisualImagePath];
    
    if ([imageFilePath count]>0) {
        self.shapeImageView.image =[UIImage imageWithContentsOfFile:[imageFilePath objectAtIndex:markerId-1]];
    }else{
        if (markerId ==1) 
            self.shapeImageView.image = [UIImage imageNamed:@"Monster_High_Visual.png"];
        else if (markerId ==2)
            self.shapeImageView.image = [UIImage imageNamed:@"The_Hobbit_Visual.png"];
    }
    if (self.shapeImageView.image.size.width>0 || self.shapeImageView.image.size.height>0) {
        [self unHideCharacterSystem];
        originalShapeSize = CGSizeMake(self.viewFrame.size.width/2, (self.viewFrame.size.width/2)*self.shapeImageView.image.size.height/self.shapeImageView.image.size.width);
        currentShapeCenterPoint = CGPointMake(self.viewFrame.size.width/2,self.viewFrame.size.height/2);
        currentScale = 1.0f;
        [self renderShape:currentScale centerPoint:currentShapeCenterPoint];
        NSLog(@"Loading character... (%d)",markerId);
    }else {
        [self reSetImageMarkersAndVisualsForDownload];
    }
}
- (void) reSetImageMarkersAndVisualsForDownload {
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterVisualPath"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"PosterData"];
    [[NSUserDefaults standardUserDefaults]removeObjectForKey:@"lastDateKey"];
    [self loadTrackingDataFromUserDefault];
    self.helpMessageLabel.hidden = NO;
}

- (void)loadMarkers:(ScanMode)newScanMode progressIndicator:(void(^)(int current, int count))progressIndicator {
    NSLog(@"loadMarkers");
    scanMode = newScanMode;
    switch (scanMode) {
        case imageMarkers: {
            NSArray *postersData = [[SessionManager getInstance] getPosterMarkerData];
            NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
            if ([postersData count]){
                NSLog(@"Posters count = %d",[postersData count]);
                [self deleteMetaioDirectory];
                [self createMetaioDirectory];
                [self downloadPosterMarkers: postersData progressIndicator:progressIndicator];
                [self downloadPosterMarkersVisual: postersData progressIndicator:progressIndicator];
                [[NSUserDefaults standardUserDefaults]setValue:[SessionManager getInstance].posterVisual forKey:@"PosterVisualPath"];
                [[NSUserDefaults standardUserDefaults]setValue:[NSKeyedArchiver archivedDataWithRootObject:posterArray] forKey:@"PosterData"];
                [self buildMarkerFile: postersData];
                [self loadTrackingDataFromUserDefault];
            }
            
            break;
        }
        case metaioMarkers: {
            break;
        }
        case barCodes:
            break;
    }
}
#pragma mark - viewDidLoad lifecycle
- (BOOL) noMarkerAvailableLocally {
	// Check any marker available locally in user defaults.
    BOOL isNoMarkerAvailable;
    NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
    if ([data count])
        isNoMarkerAvailable = NO;
    else 
        isNoMarkerAvailable = YES;
    return isNoMarkerAvailable;
}

- (void) loadDefaultMarker {
	// Load default Hobbit marker in metaio
    NSLog(@"loadDefaultMarker");
    NSString *trackingDataFile = [[NSBundle mainBundle] pathForResource:@"TrackingDefault_Marker" ofType:@"xml" inDirectory:@"assets"];
    NSString *data = [[NSString alloc] initWithUTF8String:[trackingDataFile UTF8String]];
    NSLog(@"Tracking Data File = %@",data);
    main_sync(^{
        isFirstTime = YES;
        unifeyeMobile->setTrackingData([trackingDataFile UTF8String]);
    });
	[self updateMarkersFromServiceFlag:YES];
}

- (BOOL) isLoadMarkersFromService {
	// Check User Default variable "updateMarkersFromService" YES or NO
    return [[NSUserDefaults standardUserDefaults] boolForKey:@"updateMarkersFromService"];
}

- (void) updateMarkersFromServiceFlag: (BOOL)isNeedToUpdate {
	// Set "updateMarkersFromService" as per the given isNeedToUpdate flag
    [[NSUserDefaults standardUserDefaults ]setBool:isNeedToUpdate forKey:@"updateMarkersFromService"];
}

- (BOOL) isInternetAvailable {
	// Check internet is available or not.
    BOOL isAvailable;
    Reachability* reachability = [Reachability reachabilityWithHostName:CHECK_REACHABLE_HOST];;
    NetworkStatus remoteHostStatus = [reachability currentReachabilityStatus];
    if(remoteHostStatus == NotReachable) 
        isAvailable = NO;
    else if (remoteHostStatus == ReachableViaWiFi || remoteHostStatus == ReachableViaWWAN) 
        isAvailable = YES;
    return isAvailable;
}

-(NSArray*) retrieveServiceMarkers {
	// Return markers array by calling the service
    return [[SessionManager getInstance] getPosterMarkerData];;
}

- (void) loadMarkers {
	if([self isInternetAvailable]) {
        hud= [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
            [self loadMarkers:imageMarkers progressIndicator:^(int current, int count) {
                if (current < count) {
                    hud.mode = MBProgressHUDModeAnnularDeterminate;
                    hud.progress = (float)current / count;
                } else {
                    hud.mode = MBProgressHUDModeIndeterminate;
                }
            }];
            dispatch_async(dispatch_get_main_queue(), ^{
                self.animationFlag = false;
                [MBProgressHUD hideHUDForView:self.navigationController.view animated:YES];
                self.helpMessageLabel.hidden = NO;
            });
        });
		[self updateMarkersFromServiceFlag:NO];
	} else {
        self.helpMessageLabel.hidden = NO;
        self.animationFlag = false;
    }
}

- (BOOL) isTimeForCheckLatestMarkersFromService {
    NSDate *lastDate = (NSDate *)[[NSUserDefaults standardUserDefaults] objectForKey:@"lastDateKey"];
    if(lastDate) {
        NSTimeInterval timeInterval = [lastDate timeIntervalSinceNow];
        if(timeInterval < CMS_SERVICE_CHECK_SEC) {
            return YES;
        }
    } else {
        [self updatedTimeForCheckLatestMarkersFromService];
    }
    return NO;
}

- (void) updatedTimeForCheckLatestMarkersFromService {
    [[NSUserDefaults standardUserDefaults] setObject:[NSDate date] forKey:@"lastDateKey"];
}

- (void) checkLatestMarkersFromService {
	if([self isTimeForCheckLatestMarkersFromService] && [self isInternetAvailable]) {
		[self retrieveServiceMarkers];
        NSArray *posterArray = [[SessionManager getInstance] getPosterMarkerArray];
        NSArray *userDefaultPosterArray = (NSArray*)[NSKeyedUnarchiver unarchiveObjectWithData:
                                                     (NSData*)[[NSUserDefaults standardUserDefaults] dataForKey:@"PosterData"]];
        [self updateMarkersFromServiceFlag:![posterArray isEqualToArray:userDefaultPosterArray]];
        [self updatedTimeForCheckLatestMarkersFromService];
    }
}

#pragma mark - lifecycle
- (void)displayTutorialView {
    if ([[[NSUserDefaults standardUserDefaults ]objectForKey:@"tutorialFlag"] length]==0) {
        [[NSUserDefaults standardUserDefaults]setValue:@"true" forKey:@"tutorialFlag"];
        [self.view addSubview:self.tutorialView];
        self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                             self.tutorialView.superview.frame.size.height,
                                             self.tutorialView.frame.size.width,
                                             self.tutorialView.frame.size.height);
        
        [UIView animateWithDuration:0.5 animations:^{
            //self.tutorialView.center = self.tutorialView.superview.center;
            self.tutorialView.frame = CGRectMake(self.tutorialView.frame.origin.x,
                                                 0,
                                                 self.tutorialView.frame.size.width,
                                                 self.tutorialView.frame.size.height);
        }];
    }
}

- (void) loadOrCheckMarkersFromService {
    NSLog(@"loadOrCheckMarkersFromService");
	if([self isLoadMarkersFromService]) {
        NSLog(@" -- loadMarkers");
		[self loadMarkers];
	} else {
        NSLog(@" -- checkLatestMarkersFromService");
        [self performSelectorInBackground:@selector(checkLatestMarkersFromService) withObject:nil];
	}
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.navigationController.view.frame = self.viewFrame;
    CGRect frame = self.view.frame;
    frame = CGRectMake(0, 0, 320, 480);
    
    NSLog(@">>> %f, %f, %f, %f",frame.origin.x, frame.origin.y, frame.size.width, frame.size.height);

    
    [self initGestureView];
    
    self.shapeSlider.transform =CGAffineTransformMakeRotation(M_PI * -0.5);
    self.animationFlag =true;
    [self loadTrackingDataFromUserDefault];
    
    [self displayTutorialView];
    [self loadOrCheckMarkersFromService];
}

- (void)checkProgressIndicator
{
    if (self.animationFlag) {
        hud= [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
    }
}

- (void)viewDidAppear:(BOOL)animated   {
    [super viewDidAppear:animated];
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self.navigationController setNavigationBarHidden:YES];
    self.triggerButton.hidden = NO;
    [self hideCharacterSystem];
    NSMutableArray *data = (NSMutableArray*)[[NSUserDefaults standardUserDefaults]arrayForKey:@"PosterVisualPath"];
    if ([data count]) {
        self.helpMessageLabel.hidden = NO;
    }    
}

- (void)viewWillDisappear:(BOOL)animated {
    [super viewWillDisappear:animated];
    [self unscheduleCharacterTimer];
    remainingSeconds = 0;
}

- (void)viewDidUnload
{
    [super viewDidUnload];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
   return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

- (void)setViewFrameCustom:(CGRect)viewFrame {
    CGRect newFrame = CGRectMake(viewFrame.origin.x, 0, viewFrame.size.width, viewFrame.size.height+viewFrame.origin.y);
    self.viewFrame = newFrame;
}

@end
