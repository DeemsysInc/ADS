//
//  Poster.m
//  Trends
//
//  Created by Admin on 27/08/12.
//  Copyright (c) 2012 Prayatna Intellect. All rights reserved.
//

#import "Poster.h"

@implementation Poster
@synthesize posterId, title, posterImage, localPosterImagePath, posterMarkers;
@synthesize legalContentSwitch, legalImageURL, legalImagePosition;

UIAlertView *errorAlert;

- (id)init {
    @try {
        self = [super init];
        legalImagePosition = BOTTOM_RIGHT_CORNER;
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster - init" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - Setter Methods

- (void) setLegalImagePositionFromString:(NSString *)imagePosition    {
    @try {
        if ([imagePosition isEqualToString:@"TOP_LEFT"]) {
            legalImagePosition = TOP_LEFT_CORNER;
        }
        else if ([imagePosition isEqualToString:@"TOP_CENTER"])   {
            legalImagePosition = TOP_CENTER;
        }
        else if ([imagePosition isEqualToString:@"TOP_RIGHT"])   {
            legalImagePosition = TOP_RIGHT_CORNER;
        }
        else if ([imagePosition isEqualToString:@"BOTTOM_LEFT"])   {
            legalImagePosition = BOTTOM_LEFT_CORNER;
        }
        else if ([imagePosition isEqualToString:@"BOTTOM_CENTER"])   {
            legalImagePosition = BOTTOM_CENTER;
        }
        else if ([imagePosition isEqualToString:@"BOTTOM_RIGHT"])   {
            legalImagePosition = BOTTOM_RIGHT_CORNER;
        }
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster - setLegalImagePositionFromString" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

#pragma mark - NSCoding Protocols

- (void)encodeWithCoder:(NSCoder *)encoder {
    @try {
        [encoder encodeInt32:posterId forKey:@"posterId"];
        [encoder encodeObject:self.title forKey:@"title"];
        [encoder encodeObject:self.posterImage forKey:@"posterImage"];
        [encoder encodeObject:self.localPosterImagePath forKey:@"localPosterImagePath"];
        [encoder encodeObject:self.posterMarkers forKey:@"posterMarkers"];
        [encoder encodeBool:legalContentSwitch forKey:@"legalContentSwitch"];
        [encoder encodeObject:self.legalImageURL forKey:@"legalImageURL"];
        [encoder encodeValueOfObjCType:@encode( LegalImagePosition ) at:&legalImagePosition];
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster - encodeWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

- (id)initWithCoder:(NSCoder *)decoder {
    @try {
        if (self = [super init]) {
            
            self.posterId = [decoder decodeInt32ForKey:@"posterId"];
            self.title = [decoder decodeObjectForKey:@"title"];
            self.posterImage = [decoder decodeObjectForKey:@"posterImage"];
            self.localPosterImagePath = [decoder decodeObjectForKey:@"localPosterImagePath"];
            self.posterMarkers = [decoder decodeObjectForKey:@"posterMarkers"];
            self.legalContentSwitch = [decoder decodeBoolForKey:@"legalContentSwitch"];
            self.legalImageURL = [decoder decodeObjectForKey:@"legalImageURL"];
            [decoder decodeValueOfObjCType:@encode( LegalImagePosition ) at:&legalImagePosition];
        }
        return self;
    }
    @catch (NSException *exception) {
        NSString *errorInDetail = [[NSString alloc] initWithFormat:@"%@ %@", [exception name],[exception reason]];
        errorAlert= [[UIAlertView alloc] initWithTitle:@"Error in Poster - initWithCoder" message:errorInDetail delegate:nil   cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [errorAlert show];
    }
    @finally {  }
}

@end