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


// holds all our boxes 
var boxes2Images = []; 
// New, holds the 8 tiny boxes that will be our selection handles
// the selection handles will be in this order:
// 0  1  2
// 3     4
// 5  6  7
var selectionHandles = [];

// Hold canvas information
var canvas;
var ctx;
var WIDTH;
var HEIGHT;
var INTERVAL = 20;  // how often, in milliseconds, we check to see if a redraw is needed

var isDrag = false;
var isResizeDrag = false;
var expectResize = -1; // New, will save the # of the selection handle if the mouse is over one.
var mx, my; // mouse coordinates

 // when set to true, the canvas will redraw everything
 // invalidate() just sets this to false right now
 // we want to call invalidate() whenever we make a change
var canvasValid = false;

// The node (if any) being selected.
// If in the future we want to select multiple objects, this will get turned into an array
var mySel = null;

// The selection color and width. Right now we have a red selection with a small width
var mySelColor = '#CC0000';
var mySelWidth = 2;
var mySelBoxColor = 'darkred'; // New for selection boxes
var mySelBoxSize = 6;
var mySelImageWidth = 0;
var mySelImageHeight = 0;

// we use a fake canvas to draw individual shapes for selection testing
var ghostcanvas;
var gctx; // fake canvas context

// since we can drag from anywhere in a node
// instead of just its x/y corner, we need to save
// the offset of the mouse when we start dragging.
var offsetx, offsety;

// Padding and border style widths for mouse offsets
var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;



var tryOnObj = new Image();

function init() {
  // tryOnObj.src ="http://devarapps.seemoreinteractive.com/files/clients/85/products/kohls_sunglasses1.png";

  // tryOnObj.onload = function() {
  //   var defaultPhotoWidth = $("#imageWidthSlider").attr("value");
  //   pX = ((tryOnObj.width - defaultPhotoWidth)/2);
  //   // $("#drawFramesImageSize").html("tryOnObj.width" + tryOnObj.width + "defaultPhotoWidth: "+ defaultPhotoWidth+" "+pX);
  //   resizePhoto(defaultPhotoWidth);
  // };
  videoCanvas = document.getElementById("videoCanvas");
  videoCtx = videoCanvas.getContext("2d");
  // pictureCanvas = document.getElementById("pictureCanvas");
  // pictureCtx = pictureCanvas.getContext("2d");
  init2();
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





// Box Image object to hold data
function Box2Image() {
  this.x = 10;
  this.y = 10;
  this.w = 1; // default width and height?
  this.h = 1;
  this.imageObj = new Image();
}

// New methods on the Box class
Box2Image.prototype = {
  // we used to have a solo draw function
  // but now each box is responsible for its own drawing
  // mainDraw() will call this with the normal canvas
  // myDown will call this with the ghost canvas with 'black'
  drawImage: function(context, optionalColor) {
        // We can skip the drawing of elements that have moved off the screen:
      if (this.x > WIDTH || this.y > HEIGHT) return; 
      if (this.x + this.w < 0 || this.y + this.h < 0) return;
      
      // context.fillRect(this.x,this.y,this.w,this.h);
      context.drawImage(this.imageObj, this.x,this.y,this.w,this.h);
      

    // draw selection
    // this is a stroke along the box and also 8 new selection handles
    if (mySel === this) {

      mySelImageWidth = this.w;
      mySelImageHeight = this.h;

      context.strokeStyle = mySelColor;
      context.lineWidth = mySelWidth;
      context.strokeRect(this.x,this.y,this.w,this.h);

      // draw the boxes
      
      var half = mySelBoxSize / 2;
      
      //Not using now
      // 0  1  2
      // 3     4
      // 5  6  7

      //Current structure
      // 0  4  1
      //      
      // 2     3
      
      // top left, middle, right
      selectionHandles[0].x = this.x-half;
      selectionHandles[0].y = this.y-half;
      
      selectionHandles[4].x = this.x+this.w/2-half;
      selectionHandles[4].y = this.y-half;
      
      selectionHandles[1].x = this.x+this.w-half;
      selectionHandles[1].y = this.y-half;
      
      // //middle left
      // selectionHandles[3].x = this.x-half;
      // selectionHandles[3].y = this.y+this.h/2-half;
      
      // //middle right
      // selectionHandles[4].x = this.x+this.w-half;
      // selectionHandles[4].y = this.y+this.h/2-half;
      
      // //bottom left, middle, right
      // selectionHandles[6].x = this.x+this.w/2-half;
      // selectionHandles[6].y = this.y+this.h-half;
      
      selectionHandles[2].x = this.x-half;
      selectionHandles[2].y = this.y+this.h-half;
      
      selectionHandles[3].x = this.x+this.w-half;
      selectionHandles[3].y = this.y+this.h-half;

      
      context.fillStyle = mySelBoxColor;
      for (var i = 0; i < 5; i ++) {
        var cur = selectionHandles[i];
        context.fillRect(cur.x, cur.y, mySelBoxSize, mySelBoxSize);
      }
    }
    
  } // end draw

}

//Initialize a new Box, add it, and invalidate the canvas
function addRectImage(imageSrc) {
  var tryOnObj = new Image();
  tryOnObj.src = imageSrc;

  tryOnObj.onload = function() {
    var rectImage = new Box2Image;
    rectImage.x = 20;
    rectImage.y = 20;
    rectImage.w = tryOnObj.width;
    rectImage.h = tryOnObj.height;
    rectImage.imageObj = tryOnObj;
    boxes2Images.push(rectImage);
    invalidate();
  };

  
}

// initialize our canvas, add a ghost canvas, set draw loop
// then add everything we want to intially exist on the canvas
function init2() {
  canvas = document.getElementById('pictureCanvas');
  HEIGHT = canvas.height;
  WIDTH = canvas.width;
  ctx = canvas.getContext('2d');
  ghostcanvas = document.createElement('canvas');
  ghostcanvas.height = HEIGHT;
  ghostcanvas.width = WIDTH;
  gctx = ghostcanvas.getContext('2d');
  
  //fixes a problem where double clicking causes text to get selected on the canvas
  canvas.onselectstart = function () { return false; }
  
  // fixes mouse co-ordinate problems when there's a border or padding
  // see getMouse for more detail
  if (document.defaultView && document.defaultView.getComputedStyle) {
    stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10)     || 0;
    stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)      || 0;
    styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10) || 0;
    styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)  || 0;
  }
  
  // make mainDraw() fire every INTERVAL milliseconds
  // setInterval(mainDraw, INTERVAL);
  setInterval(mainDrawImages, INTERVAL);
  
  // set our events. Up and down are for dragging,
  // double click is for making new boxes
  canvas.onmousedown = myDown;
  canvas.onmouseup = myUp;
  canvas.ondblclick = myDblClick;
  canvas.onmousemove = myMove;
  
  // set up the selection handle boxes
  for (var i = 0; i < 5; i ++) {
    var rect = new Box2Image;
    selectionHandles.push(rect);
  }
  
  // add custom initialization here:

  // addRectImage("images/city.png");
  // addRectImage("images/kohls_sunglasses1.png");
  // addRectImage("images/kohls_sunglasses1.png");
  addRectImage("images/kohls_sunglasses1.png");
  // addRectImage("http://devarapps.seemoreinteractive.com/files/clients/85/products/kohls_sunglasses1.png");

}


//wipes the canvas context
function clear(c) {
  c.clearRect(0, 0, WIDTH, HEIGHT);
}

// Main draw loop.
// While draw is called as often as the INTERVAL variable demands,
// It only ever does something if the canvas gets invalidated by our code
function mainDraw() {
  if (canvasValid == false) {
    clear(ctx);
    
    // Add stuff you want drawn in the background all the time here
    
    // draw all boxes
    var l = boxes2.length;
    for (var i = 0; i < l; i++) {
      boxes2[i].draw(ctx); // we used to call drawshape, but now each box draws itself
    }
    
    // Add stuff you want drawn on top all the time here
    
    canvasValid = true;
  }
}

function mainDrawImages() {
  if (canvasValid == false) {
    clear(ctx);
    
    // Add stuff you want drawn in the background all the time here
    
    // draw all boxes
    var l = boxes2Images.length;
    for (var i = 0; i < l; i++) {
      boxes2Images[i].drawImage(ctx); // we used to call drawshape, but now each box draws itself
    }
    
    // Add stuff you want drawn on top all the time here
    
    canvasValid = true;
  }
}

// Happens when the mouse is moving inside the canvas
function myMove(e){

  if (isDrag) {
    getMouse(e);
    // console.log('Vikram: myMove');
    mySel.x = mx - offsetx;
    mySel.y = my - offsety;   
    
    // something is changing position so we better invalidate the canvas!
    invalidate();
  } else if (isResizeDrag) {
    // time ro resize!
    var oldx = mySel.x;
    var oldy = mySel.y;
    // console.log('vikram: new move');
    // console.log('vikram: mysel values: ' + oldx + " " + oldy );
    // console.log('vikram: mx values: ' + mx + " " + my );
    // console.log('vikram: Corners values: ' + expectResize );
    // console.log("Vikram: image size: " + mySelImageWidth + ' X ' + mySelImageHeight);
    // 0  1  2
    // 3     4
    // 5  6  7
    switch (expectResize) {
      case 0:
        mySel.x = mx;
        mySel.y = my;
        mySel.w += oldx - mx;
        // mySel.h += oldy - my;
         mySel.h = mySel.w/mySelImageWidth*mySelImageHeight;
        break;
      case 1:
        mySel.y = my;
        mySel.w = mx - oldx;
        mySel.h = mySel.w/mySelImageWidth*mySelImageHeight;
        // mySel.h += oldy - my;
        break;
      // case 3:
      //   mySel.x = mx;
      //   mySel.w += oldx - mx;
      //   break;
      // case 4:
      //   mySel.w = mx - oldx;
      //   break;
      case 2:
        mySel.x = mx;
        mySel.w += oldx - mx;
        // mySel.h = my - oldy;
         mySel.h = mySel.w/mySelImageWidth*mySelImageHeight;
        break;
      // case 6:
      //   mySel.h = my - oldy;
      //   break;
      case 3:
        mySel.w = mx - oldx;
        // mySel.h = my - oldy;
         mySel.h = mySel.w/mySelImageWidth*mySelImageHeight;
        break;
      case 4:
        mySel.y = my;
        mySel.h += oldy - my;
        break;
    }
    
    invalidate();
  }
  
  getMouse(e);
  // if there's a selection see if we grabbed one of the selection handles
  if (mySel !== null && !isResizeDrag) {
    for (var i = 0; i < 5; i++) {
      // 0  1  2
      // 3     4
      // 5  6  7
      
      var cur = selectionHandles[i];
      
      // we dont need to use the ghost context because
      // selection handles will always be rectangles
      if (mx >= cur.x && mx <= cur.x + mySelBoxSize &&
          my >= cur.y && my <= cur.y + mySelBoxSize) {
        // we found one!
        expectResize = i;
        invalidate();
        
        switch (i) {
          case 0:
            this.style.cursor='nw-resize';
            break;
          case 1:
            this.style.cursor='ne-resize';
            break;
          // case 322:
          //   this.style.cursor='w-resize';
          //   break;
          // case 4333:
          //   this.style.cursor='e-resize';
          //   break;
          case 2:
            this.style.cursor='sw-resize';
            break;
          // case 63333:
          //   this.style.cursor='s-resize';
          //   break;
          case 3:
            this.style.cursor='se-resize';
            break;
          case 4:
            this.style.cursor='n-resize';
            break;
        }
        return;
      }
      
    }
    // not over a selection box, return to normal
    isResizeDrag = false;
    expectResize = -1;
    this.style.cursor='auto';
  }
  
}

// Happens when the mouse is clicked in the canvas
function myDown(e){
  getMouse(e);
  

  //we are over a selection box
  if (expectResize !== -1) {
    isResizeDrag = true;
    return;
  }
  
  clear(gctx);
  // var l = boxes2.length;
  var l = boxes2Images.length;
  for (var i = l-1; i >= 0; i--) {
    // draw shape onto ghost context
    // boxes2[i].draw(gctx, 'black');
    boxes2Images[i].drawImage(gctx,'');
    
    // get image data at the mouse x,y pixel
    var imageData = gctx.getImageData(mx, my, 1, 1);
    var index = (mx + my * imageData.width) * 4;
    
    // if the mouse pixel exists, select and break
    if (imageData.data[3] > 0) {
      // mySel = boxes2[i];
      mySel = boxes2Images[i];
      offsetx = mx - mySel.x;
      offsety = my - mySel.y;
      mySel.x = mx - offsetx;
      mySel.y = my - offsety;
      isDrag = true;
      
      invalidate();
      clear(gctx);
      return;
    }
    
  }
  // havent returned means we have selected nothing
  mySel = null;
  // clear the ghost canvas for next time
  clear(gctx);
  // invalidate because we might need the selection border to disappear
  invalidate();
}

function myUp(){
  isDrag = false;
  isResizeDrag = false;
  expectResize = -1;
}

// adds a new node
function myDblClick(e) {
  getMouse(e);
  // for this method width and height determine the starting X and Y, too.
  // so I left them as vars in case someone wanted to make them args for something and copy this code
  var width = 20;
  var height = 20;
  addRect(mx - (width / 2), my - (height / 2), width, height, 'rgba(220,205,65,0.7)');
}


function invalidate() {
  canvasValid = false;
}

// Sets mx,my to the mouse position relative to the canvas
// unfortunately this can be tricky, we have to worry about padding and borders
function getMouse(e) {
      var element = canvas, offsetX = 0, offsetY = 0;

      if (element.offsetParent) {
        do {
          offsetX += element.offsetLeft;
          offsetY += element.offsetTop;
        } while ((element = element.offsetParent));
      }

      // Add padding and border style widths to offset
      offsetX += stylePaddingLeft;
      offsetY += stylePaddingTop;

      offsetX += styleBorderLeft;
      offsetY += styleBorderTop;

      mx = e.pageX - offsetX;
      my = e.pageY - offsetY
}

// If you dont want to use <body onLoad='init()'>
// You could uncomment this init() reference and place the script reference inside the body tag
//init();
// window.init2 = init2;
// init2();
$("#pictureCanvas").attr("style","z-index: 2;position:absolute;left:1%;");
})();