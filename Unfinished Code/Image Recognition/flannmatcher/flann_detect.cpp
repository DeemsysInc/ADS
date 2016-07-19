#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/ml/ml.hpp>
#include "stdafx.h"
#include <opencv/cv.h>
#include <opencv2/nonfree/features2d.hpp>
#include <stdio.h>
#include <iostream>
#include <opencv2/features2d/features2d.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <dirent.h>
#include <vector>

using namespace cv; 
using namespace std;

using std::cout;
using std::cerr;
using std::endl;
using std::vector;

vector<string> fileNames;


static string toLowerCase(const string& in) {
    string t;
    for (string::const_iterator i = in.begin(); i != in.end(); ++i) {
        t += tolower(*i);
    }
    return t;
}

static void getFilesInDirectory(const string& dirName, vector<string>& fileNames, const vector<string>& validExtensions) {
    printf("Opening directory %s\n", dirName.c_str());
    struct dirent* ep;
    size_t extensionLocation;
    DIR* dp = opendir(dirName.c_str());
    if (dp != NULL) {
        while ((ep = readdir(dp))) {
            // Ignore (sub-)directories like . , .. , .svn, etc.
            if (ep->d_type & DT_DIR) {
                continue;
            }
            extensionLocation = string(ep->d_name).find_last_of("."); // Assume the last point marks beginning of extension like file.ext
            // Check if extension is matching the wanted ones
            string tempExt = toLowerCase(string(ep->d_name).substr(extensionLocation + 1));
            if (find(validExtensions.begin(), validExtensions.end(), tempExt) != validExtensions.end()) {
                printf("Found matching data file '%s'\n", ep->d_name);
                // string filePath = dirName.c_str() + ep->d_name;
                fileNames.push_back((string) dirName + ep->d_name);
            } else {
                printf("Found file does not match required file type, skipping: '%s'\n", ep->d_name);
            }
        }
        // cout << "fileNames count after reading: " << fileNames.size() << endl;
        (void) closedir(dp);
    } else {
        printf("Error opening directory '%s'!\n", dirName.c_str());
    }
    return;
}


// void createTrainDataUsingBow(std::vector<string> posFiles, cv::Mat& train, cv::Mat& response)
// {
//     cv::Ptr<cv::DescriptorExtractor> extractor = new cv::SurfDescriptorExtractor();
//     cv::SurfFeatureDetector detector(500);

//     // get SURF descriptors and add to BOW each input files
//     for (decltype(posFiles.size()) i = 0; i < posFiles.size(); ++i){
//         std::vector<cv::KeyPoint> keypoints;
//         cv::Mat descriptors;
//         cv::Mat img = cv::imread( posFiles[i], CV_LOAD_IMAGE_GRAYSCALE );
//         if (img.cols == 0) {
//             cout << "Error reading file " << endl;
//         }else{
//             cout << "Positive file name:  " << posFiles[i] << endl;
//             detector.detect( img, keypoints);
//             extractor->compute( img, keypoints, descriptors);
//             if ( !descriptors.empty() ) bow.add( descriptors );
//         }
//     }



// }


int main( int argc, char** argv )
{
    string posSamplesDir = "/Volumes/OFFFICE/opencv/workspace/images/matching_to_many_images/train/";

    // Get the files to train from somewhere
    vector<string> positiveTrainingImages;
    static vector<string> validExtensions;
    validExtensions.push_back("jpg");
    validExtensions.push_back("png");
    validExtensions.push_back("ppm");

    getFilesInDirectory(posSamplesDir, positiveTrainingImages, validExtensions);

    /// Retrieve the descriptor vectors from the samples
    unsigned long overallSamples = positiveTrainingImages.size();
    cout << "positiveTrainingImages: " << positiveTrainingImages.size() << endl;
   
    //create SURF feature point extracter
    Ptr<FeatureDetector> detector(new OrbFeatureDetector());
    //create SURF descriptor extractor
    Ptr<DescriptorExtractor> extractor(new OrbDescriptorExtractor());   
    Ptr<DescriptorMatcher> matcher = DescriptorMatcher::create("BruteForce-Hamming");   

    Mat queryImage;
    vector<Mat> trainImages;
    vector<int> trainImagesNames;
    vector<vector<KeyPoint> > trainKeypoints;

    int fileNo = 1;
    vector<KeyPoint> trainEachKeypoint;
    Mat trainingData(0, 0, CV_32F);
    for (decltype(positiveTrainingImages.size()) i = 0; i < positiveTrainingImages.size(); ++i){
        cv::Mat img = cv::imread( positiveTrainingImages[i], CV_LOAD_IMAGE_GRAYSCALE );
        if (img.cols == 0) {
            cout << "Error reading file " << endl;
        }else{
            cout << "Positive file name:  " << positiveTrainingImages[i] << endl;
            trainImages.push_back( img );
            trainImagesNames.push_back(fileNo );
            detector->detect( img, trainEachKeypoint );
            trainKeypoints.push_back(trainEachKeypoint);
            cout << "trainKeypoints size: " << trainKeypoints.size() << endl;
            fileNo++;
        }
    }
    cout << "trainImages size: " << trainImages.size() << endl;
    cout << "trainImagesNames size: " << trainImagesNames.size() << endl;

    vector<KeyPoint> queryKeypoints;
    
    

    Mat queryDescriptors;
    vector<Mat> trainDescriptors;
    extractor->compute( trainImages, trainKeypoints, trainDescriptors );
    cout << "trainDescriptors cols: " << trainDescriptors.size() << endl;

    //source image code
    cout << "Reading the images..." << endl;
    queryImage = imread( argv[1], CV_LOAD_IMAGE_GRAYSCALE);
    detector->detect( queryImage, queryKeypoints );
    extractor->compute( queryImage, queryKeypoints, queryDescriptors );
    cout << "queryKeypoints size: " << queryKeypoints.size() << endl;
    cout << "queryDescriptors size: " << queryDescriptors.size() << endl;

    vector<DMatch> matches;
    matcher->add( trainDescriptors );
    matcher->train();
    matcher->match( queryDescriptors, matches );
    cout << "Number of matches: " << matches.size() << endl;

}