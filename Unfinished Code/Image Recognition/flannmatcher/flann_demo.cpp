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
    cv::Mat sceneImg = cv::imread(argv[2], cv::IMREAD_GRAYSCALE);

    std::vector<cv::KeyPoint> objectKeypoints;
	std::vector<cv::KeyPoint> sceneKeypoints;
	cv::Mat objectDescriptors;
	cv::Mat sceneDescriptors;

	// cv::FeatureDetector * detector = new cv::SIFT();
	cv::FeatureDetector * detector = new cv::ORB();
	// cv::FeatureDetector * detector = new cv::FastFeatureDetector();
	// cv::FeatureDetector * detector = new cv::SURF(600.0);
	detector->detect(objectImg, objectKeypoints);
	detector->detect(sceneImg, sceneKeypoints);
	delete detector;

	// cv::DescriptorExtractor * extractor = new cv::SIFT();
	// cv::DescriptorExtractor * extractor = new cv::BriefDescriptorExtractor();
	// cv::DescriptorExtractor * extractor = new cv::ORB();
	// cv::DescriptorExtractor * extractor = new cv::FREAK();
	// cv::DescriptorExtractor * extractor = new cv::SURF(600.0);
	cv::DescriptorExtractor * extractor = new cv::BRISK();
	extractor->compute(objectImg, objectKeypoints, objectDescriptors);
	extractor->compute(sceneImg, sceneKeypoints, sceneDescriptors);
	delete extractor;

	cv::Mat results;
	cv::Mat dists;
	int k=2; // find the 2 nearest neighbors
	if(objectDescriptors.type()==CV_8U)
	{
	        // Binary descriptors detected (from ORB or Brief)

	        // Create Flann LSH index
	        cv::flann::Index flannIndex(sceneDescriptors, cv::flann::LshIndexParams(12, 20, 2), cvflann::FLANN_DIST_HAMMING);

	        // search (nearest neighbor)
	        flannIndex.knnSearch(objectDescriptors, results, dists, k, cv::flann::SearchParams() );
	}
	else
	{
	        // assume it is CV_32F
	        // Create Flann KDTree index
	        cv::flann::Index flannIndex(sceneDescriptors, cv::flann::KDTreeIndexParams(), cvflann::FLANN_DIST_EUCLIDEAN);

	        // search (nearest neighbor)
	        flannIndex.knnSearch(objectDescriptors, results, dists, k, cv::flann::SearchParams() );
	}


	if(dists.type() == CV_32S){
		cv::Mat temp;
        dists.convertTo(temp, CV_32F);
        dists = temp;
	}

	float nndrRatio = 0.8;
	std::vector<cv::Point2f> mpts_1, mpts_2; // Used for homography
	std::vector<int> indexes_1, indexes_2; // Used for homography
	std::vector<uchar> outlier_mask;  // Used for homography


	for(unsigned int i=0; i<objectDescriptors.rows; ++i)
	{
        // Check if this descriptor matches with those of the objects
        // Apply NNDR
        if(results.at<int>(i,0) >= 0 && results.at<int>(i,1) >= 0 && dists.at<float>(i,0) <= nndrRatio * dists.at<float>(i,1))
        {
                mpts_1.push_back(objectKeypoints.at(i).pt);
                indexes_1.push_back(i);

                mpts_2.push_back(sceneKeypoints.at(results.at<int>(i,0)).pt);
                indexes_2.push_back(results.at<int>(i,0));
        }
	}
	cout << " mpts_1.size()" << mpts_1.size() << endl;
	// int nbMatches = 8;
	// if(mpts_1.size() >= nbMatches)
	// {
	//         cv::Mat H = findHomography(mpts_1,
	//                         mpts_2,
	//                         cv::RANSAC,
	//                         1.0,
	//                         outlier_mask);
	//         // Do what you want with the homography (like showing a rectangle)
	//         // The "outlier_mask" contains a mask representing the inliers and outliers.
	//         // ...
	// }
	unsigned int minInliers = 8;
	if(mpts_1.size() >= minInliers)
	{
		cv::Mat H = findHomography(mpts_1,
				mpts_2,
				cv::RANSAC,
				1.0,
				outlier_mask);
		int inliers=0, outliers=0;
		for(unsigned int k=0; k<mpts_1.size();++k)
		{
			if(outlier_mask.at(k))
			{
				++inliers;
			}
			else
			{
				++outliers;
			}
		}
		cout << " inliers: " <<inliers << endl;
		cout << " outliers: " <<outliers << endl;
	}

	return 0;
}