//
//  tableviewcellViewController.h
//  tableviewcell
//
//  Created by Vikram Nerasu on 09/06/13.
//  Copyright (c) 2013 digitalimperia. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface tableviewcellViewController : UIViewController<UITableViewDataSource, UITableViewDelegate>
{
    NSArray *myDataArray;
    IBOutlet UILabel *myLabel;
    IBOutlet UITableView *myTableView;
    
    CGPoint startOffset;
    CGPoint destinationOffset;
    NSDate *startTime;
    NSTimer *timer;

UITableView *slotMachine;
NSMutableArray *listOfItems;
NSTimer *tableTimer;
}


@property (nonatomic, retain) NSDate *startTime;
@property (nonatomic, retain) NSTimer *timer;

@property (nonatomic,retain) UITableView *slotmachine;
@property (nonatomic,retain) NSMutableArray *listOfItems;
@property (nonatomic,retain) NSTimer *tableTimer;

-(void)automaticScroll;
-(void)stopscroll;
@end
