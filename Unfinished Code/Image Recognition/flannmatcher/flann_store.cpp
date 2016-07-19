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
#include <dirent.h>
#include <vector>
#include <opencv2/core/core.hpp>
#include <opencv2/calib3d/calib3d.hpp> // for homography



using namespace cv; 
using namespace std;

using std::cout;
using std::cerr;
using std::endl;
using std::vector;

// Main
int main(int argc, char** argv)
{
	const clock_t begin_time = clock();
    
    //Load as grayscale
    cv::Mat objectImg = cv::imread(argv[1], cv::IMREAD_GRAYSCALE);
    cv::Mat objectImg1 = cv::imread(argv[2], cv::IMREAD_GRAYSCALE);
    cv::Mat sceneImg = cv::imread(argv[3], cv::IMREAD_GRAYSCALE);

    std::vector<cv::KeyPoint> objectKeypoints, objectKeypoints1;
	std::vector<cv::KeyPoint> sceneKeypoints;
	cv::Mat objectDescriptors, objectDescriptors1;
	cv::Mat sceneDescriptors;

	// cv::FeatureDetector * detector = new cv::SIFT();
	cv::FeatureDetector * detector = new cv::ORB();
	// cv::FeatureDetector * detector = new cv::FastFeatureDetector();
	// cv::FeatureDetector * detector = new cv::SURF(600.0);
	detector->detect(objectImg, objectKeypoints);
	detector->detect(objectImg1, objectKeypoints1);
	detector->detect(sceneImg, sceneKeypoints);
	delete detector;

	std::cout << "1- sceneKeypoints found: " << sceneKeypoints.size() << std::endl;
	

	// cv::DescriptorExtractor * extractor = new cv::SIFT();
	// cv::DescriptorExtractor * extractor = new cv::BriefDescriptorExtractor();
	cv::DescriptorExtractor * extractor = new cv::ORB();
	// cv::DescriptorExtractor * extractor = new cv::FREAK();
	// cv::DescriptorExtractor * extractor = new cv::SURF(600.0);
	// cv::DescriptorExtractor * extractor = new cv::BRISK();
	extractor->compute(objectImg, objectKeypoints, objectDescriptors);
	extractor->compute(objectImg1, objectKeypoints1, objectDescriptors1);
	extractor->compute(sceneImg, sceneKeypoints, sceneDescriptors);
	delete extractor;

	Ptr<DescriptorMatcher> m = new BFMatcher(NORM_HAMMING);


	objectDescriptors.convertTo(objectDescriptors,CV_32F);
	objectDescriptors1.convertTo(objectDescriptors1,CV_32F);
	sceneDescriptors.convertTo(sceneDescriptors,CV_32F);

	std::cout << "1- objectDescriptors found: " << objectDescriptors.size() << std::endl;
	std::cout << "1- objectDescriptors1 found: " << objectDescriptors1.size() << std::endl;
    std::cout << "1- sceneDescriptors found: " << sceneDescriptors.size() << std::endl;


    vector<Mat> addDesc;
    addDesc.push_back(objectDescriptors);
    addDesc.push_back(objectDescriptors1);
    m->add( addDesc );


    cv::Mat results;
	cv::Mat dists;
	int k=2; // find the 2 nearest neighbors
    cv::flann::Index flann_index(
            addDesc,
            cv::flann::KDTreeIndexParams(4),
            cvflann::FLANN_DIST_EUCLIDEAN
               );
    // flann_index.save("index.fln");
    // flannIndex.knnSearch(sceneDescriptors, results, dists, k, cv::flann::SearchParams() );

	return 0;
}