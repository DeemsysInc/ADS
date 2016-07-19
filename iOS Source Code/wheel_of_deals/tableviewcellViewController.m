//
//  tableviewcellViewController.m
//  tableviewcell
//
//  Created by Vikram Nerasu on 09/06/13.
//  Copyright (c) 2013 digitalimperia. All rights reserved.
//

#import "tableviewcellViewController.h"


static int totRows = 0;
NSInteger diceNow;
int startRow;
int endRow;
BOOL isDone=false;
@interface tableviewcellViewController ()


@end


@implementation tableviewcellViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    myDataArray = [[NSArray alloc]initWithObjects:@"Test1",@"Test2",@"Test3",@"Test4",@"Test5",@"Test6",@"Test7",@"Test8",@"Test9",@"Test10",@"Test11",@"Test12",@"Test13",@"Test14",@"Test15",@"Test16",@"Test17",@"Test18",@"Test19",@"Test20", nil];
    NSLog(@"Vikram: viewDidLoad");
	// Do any additional setup after loading the view, typically from a nib.
  
}

-(void)viewDidAppear:(BOOL)animated
{
    NSLog(@"Vikram: viewdidappear %d",totRows);
   //[myTableView setContentOffset:CGPointMake(0, CGFLOAT_MAX) animated:YES];
   
  // [myTableView scrollToRowAtIndexPath:indexPath atScrollPosition:UITableViewScrollPositionBottom animated:YES];
    [super viewDidAppear:animated];
    startRow = 2;
    endRow = 17;
    diceNow = [self genRandom:startRow Ending:endRow];
    NSLog(@"Vikram: generate random: %d",diceNow);
    [self scrollDirection:0 Ending:diceNow direction:@"down"];
    

}
-(NSInteger)genRandom:(int)startPoint Ending:(int)endPoint
{
    float low_bound = startPoint;
    float high_bound = endPoint;
    float rndValue = (((float)arc4random()/0x100000000)*(high_bound-low_bound)+low_bound);
    int intRndValue = (int)(rndValue + 0.5);
    return intRndValue;
}
-(void)scrollDirection:(int)startPoint Ending:(int)endPoint direction:(NSString*)dir
{
    NSLog(@"Vikram: startPOint: %d",startPoint);
    NSLog(@"Vikram: endPoint: %d",endPoint);
    NSLog(@"Vikram: direction: %@",dir);
    if ([dir isEqualToString:@"down"])
    {
        for (int i=startPoint;i<endPoint;i++){
            [self animateDown:i direction:dir];
        }
    }else{
        for (int i=startPoint;i>endPoint;i--){
            [self animateDown:i direction:dir];
        }
    }
}
-(void)animateDown:(int)scrollToRow direction:(NSString*)dir
{
    NSLog(@"Vikram: scrolltorow: %d",scrollToRow);
   
        [UIView animateWithDuration: 5.0
                              delay:0.0
                        options: UIViewAnimationOptionBeginFromCurrentState
                         animations: ^{
                              [myTableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:scrollToRow inSection:0] atScrollPosition:UITableViewScrollPositionMiddle animated:NO];
                         }completion: ^(BOOL finished){
                             if (finished){
                                 if ((diceNow-1)==scrollToRow){
                                     //[self animateDown:isDone];
                                     diceNow = [self genRandom:0 Ending:scrollToRow];
                                     NSLog(@"vikram: dicenow after 2 value: %d",diceNow);
                                     startRow = scrollToRow;
                                     endRow = (scrollToRow-diceNow);
                                    [self scrollDirection:startRow Ending:endRow direction:@"up"];
                                 }
                                 if (([dir isEqualToString:@"up"]) && ((endRow+1) == scrollToRow))
                                 {
                                     diceNow = [self genRandom:scrollToRow Ending:17];
                                     NSLog(@"vikram: dicenow after 3 value: %d",diceNow);
                                     startRow = scrollToRow;
                                     if ((diceNow+scrollToRow)>17){
                                         endRow = abs((17 - (diceNow+scrollToRow)));
                                     }else{
                                      endRow = (diceNow+scrollToRow);
                                    }
                                     if ((endRow-startRow) <=1){
                                         endRow = startRow+2;
                                     }
                                     
                                     [self scrollDirection:startRow Ending:endRow direction:@"down"];
                                 }
                                if (scrollToRow == (endRow-1)){
                                    NSLog(@"Vikram: it is done. Lock it at : %d",scrollToRow);
                                    NSIndexPath *indexPath1 = [NSIndexPath indexPathForRow:scrollToRow inSection:0];
                                    //[myTableView selectRowAtIndexPath:indexPath1 animated:NO scrollPosition:UITableViewScrollPositionNone];
                                    [self tableView:myTableView didSelectRowAtIndexPath:indexPath1];
                                    
                                }
                             }
                             
                         }
         ];

}
-(void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    NSLog(@"Vikram: scrollViewDidEndDecelerating");
}
-(void)animateNow:(int)scrollToRow
{
     [myTableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:scrollToRow inSection:0] atScrollPosition:UITableViewScrollPositionBottom animated:NO];
    
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    NSLog(@"Vikram: didSelectRowAtIndexPath");
    // Dispose of any resources that can be recreated.
}

-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSLog(@"Vikram: numberOfRowsInSection");
    totRows = [myDataArray count]-1;
    return [myDataArray count];
}
-(UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *cellIdentifier = @"cell";
    UITableViewCell *cell = [tableView dequeueReusableHeaderFooterViewWithIdentifier:cellIdentifier];
    if (cell == nil)
    {
        cell = [[UITableViewCell alloc]initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier];
    }
    NSString *cellText = [myDataArray objectAtIndex:indexPath.row];
    cell.textLabel.text = cellText;
    
    //cell.selectionStyle = UITableViewCellSelectionStyleNone;

    NSLog(@"Vikram: cellForRowAtIndexPath");
    return cell;
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    // If our cell is selected, return double height
    return 150.0;
}
-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSLog(@"Vikram: didSelectRowAtIndexPath");
    [myTableView selectRowAtIndexPath:indexPath animated:NO scrollPosition:UITableViewScrollPositionNone];
    myTableView.userInteractionEnabled = NO;
    myTableView.scrollEnabled = NO;
}
-(void)tableView:(UITableView *)tableView didDeselectRowAtIndexPath:(NSIndexPath *)indexPath
{
    //myLabel.text = [myDataArray objectAtIndex:indexPath.row];
    NSLog(@"Vikram: didDeselectRowAtIndexPath");
}
-(void)tableView:(UITableView *)tableView didEndDisplayingCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSLog(@"Vikram: didEndDisplayingCell");
    //indexPath = [NSIndexPath indexPathForRow:20 inSection:0];
    //[tableView scrollToRowAtIndexPath:indexPath atScrollPosition:UITableViewScrollPositionMiddle animated:YES];
   // [myTableView setContentOffset:CGPointMake(0, CGFLOAT_MAX) animated:NO];
    //indexPath = [NSIndexPath indexPathForRow:11 inSection:0];
    //[tableView selectRowAtIndexPath:indexPath animated:YES scrollPosition:UITableViewScrollPositionMiddle];
}

@end
