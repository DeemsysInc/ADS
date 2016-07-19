// BoFSURF.cpp : Defines the entry point for the console application.
//

#include "stdafx.h"
#include <opencv/cv.h>
#include <opencv/highgui.h>
#include <opencv/ml.h>
#include <opencv2/nonfree/features2d.hpp>
#include <stdio.h>
#include <iostream>
#include <opencv2/features2d/features2d.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <vector>
#include <dirent.h>

using namespace cv;
using namespace std;

#define DICTIONARY_BUILD 1 // set DICTIONARY_BUILD 1 to do Step 1, otherwise it goes to step 2

int main( int argc, char** argv )
{	
	int minHessian = 400; //Hessian Threshold
#if DICTIONARY_BUILD == 1
	string dirName = "/Volumes/OFFFICE/opencv/workspace/images/all_clients_markers/";
	//Step 1 - Obtain the set of bags of features.

	//to store the input file names
	char * filename = new char[100];		
	//to store the current input image
	Mat input;	

	//To store the keypoints that will be extracted by SURF
	vector<KeyPoint> keypoints;
	//To store the SURF descriptor of current image
	Mat descriptor;
	//To store all the descriptors that are extracted from all the images.
	Mat featuresUnclustered(0,0,CV_32F);
	//The SURF feature extractor and descriptor		
	OrbDescriptorExtractor detector;

	//the number of bags
	int dictionarySize=200;

	Mat labels(0, 0, CV_32F);
	Mat trainingData(0, 0, CV_32F);
	
	//I select 20 (1000/50) images from 1000 images to extract feature descriptors and build the vocabulary
	// for(int f=0;f<999;f+=50){

    DIR *dir;
    dir = opendir(dirName.c_str());
    struct dirent *ent;
    if (dir != NULL) {
    	int i=1;
        while ((ent = readdir (dir)) != NULL) {
        	sprintf(filename,"/Volumes/OFFFICE/opencv/workspace/images/all_clients_markers/%s",ent->d_name);
	    	std::cout << "filename: " << filename << std::endl;
			//open the file
			input = imread(filename, CV_LOAD_IMAGE_GRAYSCALE); //Load as grayscale				
			//detect feature points
			detector.detect(input, keypoints);
			std::cout << "keypoints: " << keypoints.size() << std::endl;
			//compute the descriptors for each keypoint
			detector.compute(input, keypoints,descriptor);		
			
			if (!descriptor.empty())
			{
				//put the all feature descriptors in a single Mat object 
				featuresUnclustered.push_back(descriptor);	
				//Vikram: store labels info
				labels.push_back((float) i);
				trainingData.push_back(descriptor);	
				printf("%i i value: \n",i);
				i++;
			}
			
			
        }
        closedir (dir);
	}	


	//Construct BOWKMeansTrainer
	
	//define Term Criteria
	TermCriteria tc(CV_TERMCRIT_ITER,100,0.001);
	//retries number
	int retries=1;
	//necessary flags
	int flags=KMEANS_RANDOM_CENTERS;
	//Create the BoW (or BoF) trainer
	BOWKMeansTrainer bowTrainer(dictionarySize,tc,retries,flags);
	//convert featuresUnclustered to type CV_32FC1
	Mat featuresUnclusteredF(featuresUnclustered.rows,featuresUnclustered.cols,CV_32FC1);
	featuresUnclustered.convertTo(featuresUnclusteredF,CV_32FC1);
	//cluster the feature vectors
	Mat dictionary=bowTrainer.cluster(featuresUnclusteredF);	


	//Setting up SVM parameters
	CvSVMParams params;
	params.kernel_type=CvSVM::RBF;
	params.svm_type=CvSVM::C_SVC;
	params.gamma=0.50625000000000009;
	params.C=312.50000000000000;
	params.term_crit=cvTermCriteria(CV_TERMCRIT_ITER,100,0.000001);
	CvSVM svm;


	printf("%s\n","Training SVM classifier");

	bool res=svm.train(trainingData,labels,cv::Mat(),cv::Mat(),params);

	cout<<"Processing evaluation data..."<<endl;


	//store the vocabulary
	FileStorage fs("dictionary.yml", FileStorage::WRITE);
	fs << "vocabulary" << dictionary;
	fs.release();
	
#else
	//Step 2 - Obtain the BoF descriptor for given image/video frame. 

    //prepare BOW descriptor extractor from the dictionary    
	Mat dictionaryF; 
	FileStorage fs("dictionary.yml", FileStorage::READ);
	fs["vocabulary"] >> dictionaryF;
	fs.release();	

	//convert to 8bit unsigned format
	Mat dictionary(dictionaryF.rows,dictionaryF.cols,CV_32FC1);
	dictionaryF.convertTo(dictionary,CV_32FC1);
    
	//create a matcher with BruteForce-Hamming distance
	Ptr<DescriptorMatcher> matcher = DescriptorMatcher::create("FlannBased");	
	
	//create SURF feature point extracter
	Ptr<FeatureDetector> detector(new OrbFeatureDetector());
	//create SURF descriptor extractor
	Ptr<DescriptorExtractor> extractor(new OrbDescriptorExtractor());	
	//create BoF (or BoW) descriptor extractor
	BOWImgDescriptorExtractor bowDE(extractor,matcher);
	//Set the dictionary with the vocabulary we created in the first step
	bowDE.setVocabulary(dictionary);

	//To store the image file name
	char * filename = new char[100];
	//To store the image tag name - only for save the descriptor in a file
	char * imageTag = new char[10];

	//open the file to write the resultant descriptor
	FileStorage fs1("descriptor.yml", FileStorage::WRITE);	
	
	//the image file with the location. change it according to your image file location
	sprintf(filename,"/Volumes/OFFFICE/opencv/workspace/images/test.jpg");		
	//read the image
	Mat img=imread(filename,CV_LOAD_IMAGE_GRAYSCALE);	
	// cout << "Mat img: " << img << endl;	
	//To store the keypoints that will be extracted by SURF
	vector<KeyPoint> keypoints;		
	//Detect SURF keypoints (or feature points)
	detector->detect(img,keypoints);
	cout << "keypoints: " << keypoints.size() << endl;
	//To store the BoW (or BoF) representation of the image
	Mat bowDescriptor;		
	//extract BoW (or BoF) descriptor from given image
	bowDE.compute(img,keypoints,bowDescriptor);







	// cout << "bowDescriptor: " << bowDescriptor << endl;
	//prepare the yml (some what similar to xml) file
	sprintf(imageTag,"img1");			
	//write the new BoF descriptor to the file
	fs1 << imageTag << bowDescriptor;		
	//You may use this descriptor for classifying the image.
	// Mat image = imread("myimage.png", 0), bowDescriptor;

	// Mat foundImage;
	// FileStorage fsr("descriptor.yml", FileStorage::READ);	
	// // FileNode fn = fsr["img1"];
	// // for(cv::FileNodeIterator fit = fn.begin(); fit != fn.end(); ++fit)
	// // {
	// // 	FileNode item = *fit;
	// //   	std::string somekey = item.name();
	// //   	double someval = (double)item;
	// //   	cout << "foundImage: " << somekey << endl;
	// // }
	// fsr["img1"] >> foundImage;
	// // fn["data"] >> foundImage;
	//  // foundImage = n["data"];
	// cout << "foundImage: " << foundImage << endl;
	// // namedWindow( "Found Image", WINDOW_AUTOSIZE );
 // //    imshow("Found Image",foundImage);
 //    fsr.release();


	// //release the file storage
	fs1.release();
#endif
	printf("\ndone\n");	
	// waitKey();
    return 0;
}