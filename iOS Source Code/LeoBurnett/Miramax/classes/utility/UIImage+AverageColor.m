/*
 UIImage+AverageColor.m
 
 Copyright (c) 2010, Mircea "Bobby" Georgescu
 All rights reserved.
 
 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright
 notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
 notice, this list of conditions and the following disclaimer in the
 documentation and/or other materials provided with the distribution.
 * Neither the name of the Mircea "Bobby" Georgescu nor the
 names of its contributors may be used to endorse or promote products
 derived from this software without specific prior written permission.
 
 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL Mircea "Bobby" Georgescu BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

#import "UIImage+AverageColor.h"

@implementation UIImage (AverageColor)

- (UIColor *)averageColor {
    CFDataRef pixelData = CGDataProviderCopyData(CGImageGetDataProvider(self.CGImage));
    const UInt8* data = CFDataGetBytePtr(pixelData);
    UInt8 alpha;
    int index, count=0;
    long redTotal=0, greenTotal=0, blueTotal=0;
    
    int width = self.size.width;
    int height = self.size.height;
    
    for(int w=0; w<width; w++) {
        for(int h=0; h<height; h++) {
            index = w * h * 4;
            alpha = data[index+3];
            if(alpha) {
                redTotal += data[index];
                greenTotal += data[index+1];
                blueTotal += data[index+2];
                count++;
            }
        }
    }
    
    CFRelease(pixelData);
    
    return [UIColor colorWithRed:(redTotal/count)/255.0f green:(greenTotal/count)/255.0f blue:(blueTotal/count)/255.0f alpha:1.0f];
}

@end