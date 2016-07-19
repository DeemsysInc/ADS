(function(){

var videoCanvas;
var pictureCanvas;
var layer3;
var videoCtx;
var pictureCtx;

var WIDTH = 400;
var HEIGHT = 300;

var resizedWidth = WIDTH;
var resizedHeight = HEIGHT;
var angleStep = 1;
var moveStep = 2;

var pAngle = 0;
var pX = 0;
var pY = 0;
var pWidth = WIDTH;
var pHeight = HEIGHT;

var tryOnObj = new Image();

function init() {
  tryOnObj.src ="http://devarapps.seemoreinteractive.com/files/clients/85/products/kohls_sunglasses1.png";

  tryOnObj.onload = function() {
    var defaultPhotoWidth = $("#imageWidthSlider").attr("value");
    pX = ((tryOnObj.width - defaultPhotoWidth)/2);
    // $("#drawFramesImageSize").html("tryOnObj.width" + tryOnObj.width + "defaultPhotoWidth: "+ defaultPhotoWidth+" "+pX);
    resizePhoto(defaultPhotoWidth);
  };
  videoCanvas = document.getElementById("videoCanvas");
  videoCtx = videoCanvas.getContext("2d");
  pictureCanvas = document.getElementById("pictureCanvas");
  pictureCtx = pictureCanvas.getContext("2d");

  drawCamera();
}

function drawTryOnImage(pAngle,pX, pY, pWidth, pHeight) {
  pictureCtx.clearRect(0, 0, pictureCanvas.width, pictureCanvas.height);
  pictureCtx.save();
  // pictureCtx.translate(100,100);
  pictureCtx.rotate(pAngle * Math.PI / 180);
  // $("#drawFramesImageSize").html(pWidth + ' X ' + pHeight);
  pictureCtx.drawImage(tryOnObj, pX, pY, pWidth, pHeight);
  pictureCtx.restore();
}

function drawCamera(){
  video = document.getElementById('video');
      // canvasImage = document.getElementById('canvasImage');
      //  canvasImageContext = canvasImage.getContext('2d');
      vendorUrl = window.URL || window.webkitURL || window.mozURL || window.msURL;

  navigator.getMedia = navigator.getUserMedia || 
                      navigator.webkitGetUserMedia ||
                      navigator.mozGetUserMedia || 
                      navigator.msGetUserMedia;
  navigator.getMedia({
      video: true,
      audio: false
  }, function(stream){
      video.src = vendorUrl.createObjectURL(stream);
      // video.play();
  }, function(error){
      //An error occured
  });

  video.addEventListener('play', function(){
      drawCameraFrames(this, videoCtx, WIDTH, HEIGHT);
  }, false);
}

function drawCameraFrames(video, context, WIDTH, HEIGHT){
    context.drawImage(video, 0, 0, WIDTH, HEIGHT);
    setTimeout(drawCameraFrames, 10, video, context, WIDTH, HEIGHT);
    // $("#drawFramesImageSize").html(resizedWidth + ' X ' + resizedHeight);

    // context.globalAlpha = 0.5;
    // context.drawImage(imageObj, 10, 10, resizedWidth, resizedHeight);

}

function resizePhoto(tempW) {
    
    MAX_WIDTH = 400;
    MAX_HEIGHT = 300;
    // var tempW = imageObj.width;
    // var tempH = imageObj.height;
    // // alert(tempW + 'X' + tempH);

    // if (tempW > tempH) {
    //     if (tempW > MAX_WIDTH) {
    //        tempH *= MAX_WIDTH / tempW;
    //        tempW = MAX_WIDTH;
    //     }
    // } else {
    //     if (tempH > MAX_HEIGHT) {
    //        tempW *= MAX_HEIGHT / tempH;
    //        tempH = MAX_HEIGHT;
    //     }
    // }

    tempH = tempW * (MAX_HEIGHT / MAX_WIDTH);
    // alert('After resizing: ' + tempW + 'X' + tempH);   
    $("#resizedImage").html(tempW + 'X' + tempH);
    resizedWidth = tempW;
    resizedHeight = tempH;
    drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
  }

  //Slider Event
  $("#divSlider").change(function() {
    var sliderValue = $("#imageWidthSlider").val();
    resizePhoto(sliderValue);
  });

    //Rotating Image
    $('#btnRotateRight').click(function(ev){
      pAngle = pAngle + angleStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });

    $('#btnRotateLeft').click(function(ev){
      pAngle = pAngle - angleStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });

    //Moving Image
    $('#moveRight').click(function(ev){
      pX = pX + moveStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });

    $('#moveLeft').click(function(ev){
      pX = pX - moveStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });
    $('#moveUp').click(function(ev){
      pY = pY - moveStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });

    $('#moveDown').click(function(ev){
      pY = pY + moveStep;
      drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
    });

     $('#startbutton').click(function(ev){
        // alert('startbutton');
         takepicture();
        ev.preventDefault();
        $("#videoCanvas").hide();
        $("#pictureCanvas").hide();
        $("#photo").show();
    });

    function takepicture() {
      var printCanvas=document.createElement('canvas');
      var printCtx=printCanvas.getContext('2d');
      printCanvas.width = videoCanvas.width;
      printCanvas.height = videoCanvas.height;
      printCtx.drawImage(videoCanvas,0,0);
      printCtx.drawImage(pictureCanvas,0,0);

      // $("#drawFramesImageSize").html(printCanvas.width + " X " + printCanvas.height);
      var dataURL =printCanvas.toDataURL();
      photo.setAttribute('src', dataURL);

      var image = printCanvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.


    window.location.href=image;

    }
    $('#clearbutton').click(function(ev){
        // alert('startbutton');
         // clearphoto();
          ev.preventDefault();
          $("#photo").hide();
          $("#videoCanvas").show();
          $("#pictureCanvas").show();
          
    });
    // function clearphoto() {
    //   var context = canvas.getContext('2d');
    //   context.fillStyle = "#AAA";
    //   context.fillRect(0, 0, canvas.width, canvas.height);

    //   var data = canvas.toDataURL('image/png');
    //   photo.setAttribute('src', data);
    // }

init();

  // $("#translate").click(function () {
  //   // draw2();
  // });

  // $("#rotate").click(function () {
  //     pAngle = pAngle + 5;
  //     drawTryOnImage(pAngle,pX, pY, resizedWidth, resizedHeight);
  // });

    

  //   // var stage = new Kinetic.Stage({
  //   //   container: 'container',
  //   //   width: 578,
  //   //   height: 200
  //   // });
  //   // var layer = new Kinetic.Layer();
  //   var imageData;
  //   var imageObj = new Image();
  //   imageObj.crossOrigin = "anonymous";
  //   imageObj.src = 'http://devarapps.seemoreinteractive.com/files/clients/85/products/kohls_sunglasses1.png';
  //     // imageObj.src = 'http://localhost/workspace/videocanvas/images/kohls_sunglasses1.png';
  //     var resizedWidth = imageObj.width;
  //     var resizedHeight = imageObj.height;
  //     imageObj.onload = function() {
  //       // var yoda = new Kinetic.Image({
  //       //   x: 200,
  //       //   y: 50,
  //       //   image: imageObj,
  //       //   width: 106,
  //       //   height: 118,
  //       //   //offset: [x:250, y: 100]
  //       // });
  //       //  // add the shape to the layer
  //       // layer.add(yoda);
  //       // //layer.add(background)

  //       // // add the layer to the stage
  //       // stage.add(layer);

  //       // // var angularSpeed = 360 / 4;
  //       // // var anim = new Kinetic.Animation(function(frame) {
  //       // //   var angleDiff = frame.timeDiff * angularSpeed / 1000;
  //       // //   yoda.rotate(angleDiff); 
  //       // // }, layer);

  //       // // anim.start();

  //       // $("#translate").click(function () {
  //       //   yoda.move(10, 0);
  //       //   layer.draw();
  //       // });

  //       // $("#rotate").click(function () {
  //       //     yoda.rotate(20 * Math.PI / 180);
  //       //     layer.draw();
  //       // });

  //       // $("#scale").click(function () {
  //       //     yoda.setScaleX(yoda.getScaleX() * 1.1);
  //       //     yoda.setScaleY(yoda.getScaleY() * 1.1);
  //       //     layer.draw();
  //       // });
      
  //      // var canvas = document.createElement('canvas');
  //      // canvas.width = imageObj.width;
  //      // canvas.height = imageObj.height;

  //      var ctx = canvas.getContext('2d');
  //      // Draw image on canvas to get its pixel data
  //      ctx.drawImage(imageObj, 0, 0);
  //      ctx.rotate(20 * Math.PI / 180);
  //      // Get image pixels
  //      // imageObj = ctx.getImageData(0, 0, canvas.width, canvas.height);

        // var defaultPhotoWidth = $("#imageWidthSlider").attr("value");
        // resizePhoto(defaultPhotoWidth);
  //     };



 


  //   // $('#resizebutton').click(function(ev){
  //   //     // alert('startbutton');
  //   //      resizePhoto(400,300);
  //   //       ev.preventDefault();
  //   //       $("#canvas").show();
  //   //       $("#photo").hide();
  //   // });



 
 
  
  
  
  //   //Slider Event
  //   $("#divSlider").change(function() {
  //     var sliderValue = $("#imageWidthSlider").val();
  //     resizePhoto(sliderValue);
  //   });


})();