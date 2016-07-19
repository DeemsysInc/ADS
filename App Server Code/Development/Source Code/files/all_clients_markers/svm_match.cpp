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


void createTrainDataUsingBow(std::vector<string> posFiles, std::vector<string> negFiles, cv::Mat& train, cv::Mat& response)
{
    cv::Ptr<cv::DescriptorMatcher> matcher = cv::DescriptorMatcher::create("FlannBased");
    cv::Ptr<cv::DescriptorExtractor> extractor = new cv::SurfDescriptorExtractor();
    cv::BOWImgDescriptorExtractor dextract( extractor, matcher );
    cv::SurfFeatureDetector detector(500);

    // cluster count
    int dictionarySize = posFiles.size()+negFiles.size();
    cout << "dictionarySize:  " << dictionarySize << endl;
    // create the object for the vocabulary.
    cv::BOWKMeansTrainer bow( dictionarySize,  cv::TermCriteria(CV_TERMCRIT_EPS+CV_TERMCRIT_ITER, 10, FLT_EPSILON), 1, cv::KMEANS_PP_CENTERS );

    // get SURF descriptors and add to BOW each input files
    for (decltype(posFiles.size()) i = 0; i < posFiles.size(); ++i){
        std::vector<cv::KeyPoint> keypoints;
        cv::Mat descriptors;
        cv::Mat img = cv::imread( posFiles[i], CV_LOAD_IMAGE_GRAYSCALE );
        if (img.cols == 0) {
            cout << "Error reading file " << endl;
        }else{
            cout << "Positive file name:  " << posFiles[i] << endl;
            detector.detect( img, keypoints);
            extractor->compute( img, keypoints, descriptors);
            if ( !descriptors.empty() ) bow.add( descriptors );
        }
    }

     // get SURF descriptors and add to BOW each input files
    for (decltype(negFiles.size()) i = 0; i < negFiles.size(); ++i){
        std::vector<cv::KeyPoint> keypoints;
        cv::Mat descriptors;
        cv::Mat img = cv::imread( negFiles[i], CV_LOAD_IMAGE_GRAYSCALE );
        if (img.cols == 0) {
            cout << "Error reading file " << endl;
        }else{
            cout << "Negative file name:  " << negFiles[i] << endl;
            detector.detect( img, keypoints);
            extractor->compute( img, keypoints, descriptors);
            if ( !descriptors.empty() ) bow.add( descriptors );
        }
    }
   
    // Create the vocabulary with KMeans.
    cv::Mat vocabulary;
    vocabulary = bow.cluster();

    //store the vocabulary
    FileStorage fs("svmmatch_voc.yml", FileStorage::WRITE);
    fs << "vocabulary" << vocabulary;
    fs.release();

    float k = 1;
    for (decltype(posFiles.size()) i = 0; i < posFiles.size(); ++i){
        // set training data using BOWImgDescriptorExtractor
        dextract.setVocabulary( vocabulary );
        std::vector<cv::KeyPoint> keypoints;
        cv::Mat img = cv::imread( posFiles[i], CV_LOAD_IMAGE_GRAYSCALE );
        detector.detect( img, keypoints);
        cv::Mat desc;
        dextract.compute( img, keypoints, desc );
        if ( !desc.empty() )
        {
            train.push_back( desc );            // update training data
            response.push_back( k ); 
            cout << k << " : path output: " << posFiles[i] << endl;  
            k++;     // update response data
        }
        
    }

    float negLabel = -1;
    for (decltype(negFiles.size()) j = 0; j < negFiles.size(); ++j){
        // set training data using BOWImgDescriptorExtractor
        dextract.setVocabulary( vocabulary );
        std::vector<cv::KeyPoint> keypoints;
        cv::Mat img = cv::imread( negFiles[j], CV_LOAD_IMAGE_GRAYSCALE );
        detector.detect( img, keypoints);
        cv::Mat desc;
        dextract.compute( img, keypoints, desc );
        if ( !desc.empty() )
        {
            train.push_back( desc );            // update training data
            response.push_back( negLabel );
            cout << negLabel << " : path output: " << negFiles[j] << endl;        // update response data
        }
        
    }
}


int main( int argc, char** argv )
{
    string posSamplesDir = "/home/devarapps/public_html/files/all_clients_markers/";
    string negSamplesDir = "/home/devarapps/public_html/imgrec/target/images/TRAIN_MED/";

    // Get the files to train from somewhere
    vector<string> positiveTrainingImages;
    static vector<string> negativeTrainingImages;
    static vector<string> validExtensions;
    validExtensions.push_back("jpg");
    validExtensions.push_back("png");
    validExtensions.push_back("ppm");

    getFilesInDirectory(posSamplesDir, positiveTrainingImages, validExtensions);
    getFilesInDirectory(negSamplesDir, negativeTrainingImages, validExtensions);

    /// Retrieve the descriptor vectors from the samples
    unsigned long overallSamples = positiveTrainingImages.size() + negativeTrainingImages.size();
    cout << "positiveTrainingImages: " << positiveTrainingImages.size() << endl;
    cout << "negativeTrainingImages: " << negativeTrainingImages.size() << endl;
    cout << "overallSamples: " << overallSamples << endl;

    const int image_area = 30*40;

    // Initialize your training set.
    Mat training_mat(overallSamples,image_area,CV_32FC1);
    Mat labels(overallSamples,1,CV_32FC1);

    // Set temp matrices
    Mat tmp_img;
    Mat tmp_dst( 30, 40, CV_8UC1 ); // to the right size for resize

    // create training data
    cv::Mat train;
    cv::Mat response;
    createTrainDataUsingBow(positiveTrainingImages, negativeTrainingImages, train, response);

    cout << "allImage descriptors rows: " << train.rows << endl;
    cout << "allImage descriptors cols: " << train.cols << endl;

    // svm parameters
    CvTermCriteria criteria = cvTermCriteria(CV_TERMCRIT_EPS, 1000, FLT_EPSILON);
    CvSVMParams svm_param = CvSVMParams( CvSVM::C_SVC, CvSVM::RBF, 10.0, 8.0, 1.0, 10.0, 0.5, 0.1, NULL, criteria);

    // train svm
    cv::SVM svm;
    svm.train(train, response, cv::Mat(), cv::Mat(), svm_param);
    svm.save("svmmatch_svm.xml");

}