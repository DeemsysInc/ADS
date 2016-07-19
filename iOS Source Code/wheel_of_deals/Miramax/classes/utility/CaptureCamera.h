#import <CoreMedia/CoreMedia.h>
#import <AVFoundation/AVFoundation.h>


@interface CaptureCamera : NSObject {

}

@property (strong) AVCaptureVideoPreviewLayer *previewLayer;
@property (strong) AVCaptureSession *captureSession;
@property (strong) AVCaptureStillImageOutput *stillImageOutput;


- (void)addVideoPreviewLayer;
- (void)addVideoInput;
- (void)addStillImageOutput;

@end
