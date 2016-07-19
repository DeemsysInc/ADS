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

using namespace cv;

int main()
{
    CvSVM svm;
    svm.load( "learned.svm" );
    std::cout << "svm support vector count: " << svm.get_support_vector_count() << std::endl;
    if( svm.get_support_vector_count() == 0 ) // model not loaded
    {
    // Set up training data
    int width = 1920, height = 1080;

    int num_files=2;
    std::string files[2]={"images/elmer_bottle_marker.jpg",
        "images/elmer_prod_bolster_pillow.jpg"};;

    int img_area=width*height;
    Mat training_mat(num_files,img_area,CV_32FC1);

    for(int z=0;z<num_files;z++){
        Mat img_mat = imread(files[z],CV_32FC1);
        int ii = 0; // Current column in training_mat
        for (int i = 0; i<img_mat.rows; i++) {
            for (int j = 0; j < img_mat.cols; j++) {
                training_mat.at<float>(z,ii++) = img_mat.at<uchar>(i,j);
            }
        }
    }

    Mat labels(num_files,1,CV_32FC1);
    labels.at<float>(0,0)=1.0;
    labels.at<float>(1,0)=0.0;

    // Set up SVM's parameters
    CvSVMParams params;
    params.svm_type    = CvSVM::C_SVC;
    params.kernel_type = CvSVM::POLY;
    params.gamma = 3;
    params.term_crit   = cvTermCriteria(CV_TERMCRIT_ITER, 100, 1e-6);
    params.degree=10;

    // Train the SVM
    CvSVM svm;
    svm.train(training_mat, labels, Mat(), Mat(), params);
    svm.save("learned.svm");

    return 1;

    }

    // CvSVM svm;
    // svm.load("learned.svm");
    std::cout<<"read image source"<<std::endl;
    Mat img;
    img=imread("images/elmer_bottle_marker.jpg",CV_32FC1);
    std::cout<<"read image source end"<<std::endl;
    
    // float f=svm.predict(img);
    // std::cout<<f<<std::endl;

    // waitKey(0);

}