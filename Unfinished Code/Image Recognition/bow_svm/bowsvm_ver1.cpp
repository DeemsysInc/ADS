// bowsvm_ver1.cpp : Defines the entry point for the console application.
//

#include <stdio.h>
#include <iostream>
#include "opencv2/core/core.hpp"
#include "opencv2/features2d/features2d.hpp"
#include "opencv2/highgui/highgui.hpp"
#include <stdio.h>
#include <opencv2/opencv.hpp>
#include <stdlib.h>
#include <vector>
#include <utility>
#include <iostream>
#include <opencv2/ml/ml.hpp>
#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/objdetect/objdetect.hpp>
#include <dirent.h>
#include "opencv2/nonfree/features2d.hpp"

using namespace cv; 
using namespace std;

using std::cout;
using std::cerr;
using std::endl;
using std::vector;

void createTrainDataUsingBow(std::vector<char*> files, cv::Mat& train, cv::Mat& response, double label)
{
    cv::Ptr<cv::DescriptorMatcher> matcher = cv::DescriptorMatcher::create("FlannBased");
    cv::Ptr<cv::DescriptorExtractor> extractor = new cv::SurfDescriptorExtractor();
    cv::BOWImgDescriptorExtractor dextract( extractor, matcher );
    cv::SurfFeatureDetector detector(500);

    // cluster count
    int cluster = 100;

    // create the object for the vocabulary.
    cv::BOWKMeansTrainer bow( cluster,  cv::TermCriteria(CV_TERMCRIT_EPS+CV_TERMCRIT_ITER, 10, FLT_EPSILON), 1, cv::KMEANS_PP_CENTERS );

    // get SURF descriptors and add to BOW each input files
    std::vector<char*>::const_iterator file;
    int i=0;
    for( file = files.begin(); file != files.end(); file++)
    {
        //cv::Mat img = cv::imread( *file, 0);
        std::cout << "files: " <<  files[i].toStdString() << endl;
		cv::Mat img = cv::imread( *file);
		resize(img,img,cv::Size(64,128));
		//img.convertTo(img,CV_32F);
		
		cvtColor(img, img, CV_RGB2GRAY);
        std::vector<cv::KeyPoint> keypoints;
		detector.detect( img, keypoints);
        cv::Mat descriptors;
        extractor->compute( img, keypoints, descriptors);
        if ( !descriptors.empty() ) bow.add( descriptors );
    }

    // Create the vocabulary with KMeans.
    cv::Mat vocabulary;
    vocabulary = bow.cluster();

    for( file = files.begin(); file != files.end(); file++)
    {
        // set training data using BOWImgDescriptorExtractor
        dextract.setVocabulary( vocabulary );
        std::vector<cv::KeyPoint> keypoints;
        //cv::Mat img = cv::imread( *file,0);
		//img.convertTo(img,CV_32F);
		cv::Mat img = cv::imread( *file);
		resize(img,img,cv::Size(64,128));
		
		cvtColor(img, img, CV_RGB2GRAY);
        detector.detect( img, keypoints);
        cv::Mat desc;
        dextract.compute( img, keypoints, desc );
        if ( !desc.empty() )
        {
            train.push_back( desc );            // update training data
            response.push_back( label );        // update response data
        }
    }
}

void trainSVM(std::vector<char*> positive, std::vector<char*> negative)
{
    // create training data
    cv::Mat train;
    cv::Mat response;
    std::cout<<"createTrainDataUsingBow start"<<endl;
    createTrainDataUsingBow(positive, train, response, 1.0);
    createTrainDataUsingBow(negative, train, response, -1.0);
	printf("hello");
	std::cout<<"createTrainDataUsingBow end"<<endl;
	
	
    // svm parameters
    // CvTermCriteria criteria = cvTermCriteria(CV_TERMCRIT_EPS, 1000, FLT_EPSILON);
    // CvSVMParams svm_param = CvSVMParams( CvSVM::C_SVC, CvSVM::RBF, 10.0, 8.0, 1.0, 10.0, 0.5, 0.1, NULL, criteria);

    // // train svm
    // cv::SVM svm;
	
	std::cout<<train.size()<<response.size();

	CvSVMParams params;
    params.svm_type    = CvSVM::C_SVC;
    params.kernel_type = CvSVM::LINEAR;
    params.term_crit   = cvTermCriteria(CV_TERMCRIT_ITER, 100, 1e-6);

	CvSVM svm;
	printf("%s\n","Training SVM classifier");
	bool res=svm.train(train,response,cv::Mat(),cv::Mat(),params);
	cout<<"Processing evaluation data..."<<endl;

    // svm.train(train, response, cv::Mat(), cv::Mat(), svm_param);
    svm.save("svm-classifier.xml");

    
}
void svmPredict(const char* classifier, const char* vocaname, const char* query)
{
    // load image
    cv::Mat img = cv::imread(query, CV_LOAD_IMAGE_GRAYSCALE);

    // load svm
    cv::SVM svm;
    svm.load(classifier);

    // declare BOWImgDescriptorExtractor
    cv::Ptr<cv::DescriptorMatcher> matcher = cv::DescriptorMatcher::create("FlannBased");
    cv::Ptr<cv::DescriptorExtractor> extractor = new cv::SurfDescriptorExtractor();
    cv::BOWImgDescriptorExtractor dextract( extractor, matcher );
	cv::SurfFeatureDetector detector(500);

    // load vocabulary data
    cv::Mat vocabulary;
    cv::FileStorage fs( vocaname, cv::FileStorage::READ);
    fs["vocabulary data"] >> vocabulary;
    fs.release();
	if( vocabulary.empty()  ) printf("vocab empty!");

    // Set the vocabulary
    dextract.setVocabulary( vocabulary );
    std::vector<cv::KeyPoint> keypoints;
    detector.detect( img, keypoints);
    cv::Mat desc_bow;
    dextract.compute( img, keypoints, desc_bow );
    if( desc_bow.empty() )  printf("desc_bow empty!") ;

    // svm predict
    float predict = svm.predict(desc_bow, true);

    std::cout << predict << std::endl;

    
}
vector<char *> getFileNamesFromDir(const String &pDirName){
	std::vector<char*> pos,neg;
	char str[1024],str1[1024];
    
    string dirName = pDirName;
    char * filename = new char[100];
    DIR *dir;
    dir = opendir(dirName.c_str());
    struct dirent *ent;
    if (dir != NULL) {
    	int i=1;
        while ((ent = readdir (dir)) != NULL) {
        	string strFileName = ent->d_name;
        	std::string fn = strFileName;
        	if((fn.substr(fn.find_last_of(".") + 1) == "jpg") || (fn.substr(fn.find_last_of(".") + 1) == "png"))
        	{
        	sprintf(filename,"%s%s",dirName.c_str(), ent->d_name);
	       	cout<<"filename: "<< filename <<endl;
	       	pos.push_back(filename);
	       }
			i++;
        }
        closedir (dir);
      }
      return pos;
 }
int main()
{
	std::vector<char*> pos,neg;
	char str[1024],str1[1024];
    
    string dirPos = "/Volumes/OFFFICE/opencv/workspace/BoFORB/images/TRAIN_LOW/";
    pos = getFileNamesFromDir(dirPos);

    string dirNeg = "/Volumes/OFFFICE/opencv/workspace/BoFORB/images/TRAIN_MED/";
    neg = getFileNamesFromDir(dirNeg);

	// for(int i=0;i<=4;i++)
	// {sprintf(str, "C:\\Users\\Rishu1\\Desktop\\positive\\%d.jpg", i+1);
	// 	pos.push_back(str);
	// }
	// for(int j=5;j<10;j++)
	// {sprintf(str, "C:\\Users\\Rishu1\\Desktop\\negative\\%d.jpg", j+1);
	//     neg.push_back(str);
	// }
	trainSVM(pos,neg);
	// svmPredict("svm-classifier.xml","myvocab.xml" ,"C:\\Users\\Rishu1\\Desktop\\11.jpg");

	return 0;
}