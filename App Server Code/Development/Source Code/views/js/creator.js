function addButtonCreator(){
	var actionType = "addButtonCreator";
	jQuery.post(root_url+"includes/ajax/creator.ajax.php", { action: actionType},
		function(data){
			//alert(data);
			 $("#displayButton").show();
			$("#displayButton").html(data);
			
			$("#addButtonProperties").show();
			$("#add3DModelProperties").hide();
			
		}
	);	
}


function add3DmodelCreator(){
	var actionType = "add3DmodelCreator";
	jQuery.post(root_url+"includes/ajax/creator.ajax.php", { action: actionType},
		function(data){
			//alert(data);
			$("#display3dModel").show();
			$("#display3dModel").html(data);	
			$("#add3DModelProperties").show();
			$("#addButtonProperties").hide();
			
			
		}
	);	
}
function addURLCreator(){
	var actionType = "addURLCreator";
	jQuery.post(root_url+"includes/ajax/creator.ajax.php", { action: actionType},
		function(data){
			//alert(data);
			$("#displayURL").show();
			$("#displayURL").html(data);	
			$("#addURLProperties").show();
			$("#add3DModelProperties").hide();
			$("#addButtonProperties").hide();
			
			
		}
	);	
}

function changeButtonText(){
	
	$("#btn").text($("#btn_text").val());
	
	
}
function changeURL(){
	
	$("#ta_url").text($("#url").val());
	
	
}

  $(function(){
	  $('#displayButton').draggable();  
	  $( "#displayButton" ).resizable();
	  $('#display3dModel').draggable();  
	  $( "#display3dModel" ).resizable();
	  $('#displayURL').draggable();  
	  $( "#displayURL" ).resizable();
	  
	  $("#displayButton").click(function() {
        $("#addButtonProperties").show();
		$("#add3DModelProperties").hide();
		    var x_value=$('#displayButton').position().left;
			var y_value=$('#displayButton').position().top;
			$("#btn_x").val(x_value);
			$("#btn_y").val(y_value);
			
			$("#x_btn_slider").slider("value",x_value);
			$("#y_btn_slider").slider("value",y_value);
			
			$('#displayButton').css('left', +x_value+'px' );
			$('#displayButton').css('top', +y_value+'px' );
		
      });
	  $("#display3dModel").click(function() {
        $("#add3DModelProperties").show();
		$("#addButtonProperties").hide();
		
		    var x_value=$('#display3dModel').position().left;
			var y_value=$('#display3dModel').position().top;
			$("#model_x").val(x_value);
			$("#model_y").val(y_value);
			
			$("#x_model_slider").slider("value",x_value);
			$("#y_model_slider").slider("value",y_value);
			
			$('#display3dModel').css('left', +x_value+'px' );
			$('#display3dModel').css('top', +y_value+'px' );
      });
	  
	 $("#deletBtnProperties").click(function() {
	      $("#addButtonProperties").hide();
		  $("#displayButton").hide();
		  //$(this).closest('form').find("input[type=text], textarea").val("");	  
	 });
	 $("#deletModelProperties").click(function() {
	      $("#add3DModelProperties").hide();
		  $("#display3dModel").hide();
		  //$(this).closest('form').find("input[type=text], textarea").val("");	  
	 });
	 
  });

	  
	  


//Jquery UI slider script

$(function() {
    //add btn opacity
	$('#opacity_btn_slider').slider({
        value:1,
		range: "min",
        min: 0,
        max: 1,
        step: 0.1,
        slide: function( event, ui ) {
            $('#btn_opacity').val(ui.value);
            $('#displayButton').css('opacity', ui.value);
        }
    });
    $('#btn_opacity').val($('#opacity_btn_slider').slider('value'));
	$("#btn_opacity").change(function () {
		 
			var value=$("#btn_opacity").val();
			$("#opacity_btn_slider").slider("value",value);
			$('#displayButton').css('opacity', value );
	 });

    

   //add button Rotation in degrees

	$('#btn_rotation_slider').slider({
		  orientation	: 'horizontal',
		  max			: 360,
		  min			: -360,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			   $('#displayButton').css({'-moz-transform':'rotate('+ui.value+'deg)','-webkit-transform':'rotate('+ui.value+'deg)'});
			   $('#btn_rotation').val(ui.value);
		  }
	 });	
	 $('#btn_rotation').val($('#btn_rotation_slider').slider('value'));
	 $("#btn_rotation").change(function () {
		 
			var value=$("#btn_rotation").val();
			$("#btn_rotation_slider").slider("value",value);
			 $('#displayButton').css({'-moz-transform':'rotate('+value+'deg)','-webkit-transform':'rotate('+value+'deg)'});
	 });
	 
	 // add btn x  position
	 $('#x_btn_slider').slider({
		  orientation	: 'horizontal',
		  max			: 180,
		  min			: -180,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#btn_x').val(ui.value);
            $('#displayButton').css('position','absolute');
			$('#displayButton').css('left', ui.value );
		  }
	 });	
	 $('#btn_x').val($('#x_btn_slider').slider('value') );
	 $("#btn_x").change(function () {
		 
			var value=$("#btn_x").val();
			$("#x_btn_slider").slider("value",value);
			$('#displayButton').css('position','absolute');
			$('#displayButton').css('left', +value+'px' );
	 });
	 
	 // add btn y position	
	 $('#y_btn_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#btn_y').val(ui.value );
            $('#displayButton').css('position','absolute');
			$('#displayButton').css('top', ui.value );
		  }
	 });	
	 $('#btn_y').val($('#y_btn_slider').slider('value'));
	 $("#btn_y").change(function () {
		 
			var value=$("#btn_y").val();
			$("#y_btn_slider").slider("value",value);
			$('#displayButton').css('position','absolute');
			$('#displayButton').css('top', +value+'px' );
	 });
	 
	  //add 3d model X coordinates	
	 $('#x_model_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#model_x').val(ui.value );
            $('#display3dModel').css('position','absolute');
			$('#display3dModel').css('left', ui.value );
		  }
	 });	
	 $('#model_x').val($('#x_model_slider').slider('value') );
     $("#model_x").change(function () {
		 
			var value=$("#model_x").val();
			$("#x_model_slider").slider("value",value);
			$('#display3dModel').css('left', +value+'px' );
	 });
	   //add 3d model Y coordinates	
	 $('#y_model_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#model_y').val(ui.value );
            $('#display3dModel').css('position','absolute');
			$('#display3dModel').css('top', ui.value );
		  }
	 });	
	 $('#model_y').val($('#y_model_slider').slider('value') );
	 $("#model_y").change(function () {
		 
			var value=$("#model_y").val();
			$("#y_model_slider").slider("value",value);
			$('#display3dModel').css('top', +value+'px' );
	 });
	    //add 3d model Scale	

     //var steps = [0, 20, 30, 50, 75, 100, 150, 200, 300];
	 $('#scale_model_slider').slider({
		  //orientation	: 'horizontal',
		  // range: "min",
		    value		: 0,
			  min			: 0,
		      max			: 300,
		range: "min",
		 
		  
		  step: 0.1,
		  slide		: function(event, ui) {
			$('#model_scale').val(ui.value );	
			
				$('#display3dModel').css({
					'-moz-transform': 'scale(' + ui.value + ')',
					'-webkit-transform': 'scale(' + ui.value + ')',
				});
				
			
		  }
	 });	
	 $('#model_scale').val($('#scale_model_slider').slider('value') );
	 $("#model_scale").change(function () {
		 
			var value=$("#model_scale").val();
			$("#scale_model_slider").slider("value",value);
			$('#display3dModel').css({
					'-moz-transform': 'scale(' + value + ')',
					'-webkit-transform': 'scale(' + value + ')',
				});
			
	 });
	 //add 3d model X rotation	
	 $('#x_rot_model_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  step: 0.001,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#model_x_rot').val(ui.value);
           	 $('#display3dModel').css('transform','rotateX(' + ui.value + 'deg)');
		  }
	 });	
	 $('#model_x_rot').val($('#x_rot_model_slider').slider('value'));
	 $("#model_x_rot").change(function () {
		 
			var value=$("#model_x_rot").val();
			$("#x_rot_model_slider").slider("value",value);
			$('#display3dModel').css('transform','rotateX(' + value + 'deg)');
			
	 });
	    //add 3d model Y rotation	
	 $('#y_rot_model_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  step: 0.001,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#model_y_rot').val(ui.value);
           	$('#display3dModel').css('transform','rotateY(' + ui.value + 'deg)');
		  }
	 });	
	 $('#model_y_rot').val($('#y_rot_model_slider').slider('value'));
	 $("#model_y_rot").change(function () {
		 
			var value=$("#model_y_rot").val();
			$("#y_rot_model_slider").slider("value",value);
			$('#display3dModel').css('transform','rotateY(' + value + 'deg)');
			
	 });
	    //add 3d model Z rotation	
	 $('#z_rot_model_slider').slider({
		  orientation	: 'horizontal',
		  max			: 1000,
		  min			: -180,
		  step: 0.001,
		  value		: 0,
		  range: "min",
		  slide		: function(event, ui) {
			  
			$('#model_z_rot').val(ui.value);
           	$('#display3dModel').css('transform','rotateZ(' + ui.value + 'deg)');
		  }
	 });	
	 $('#model_z_rot').val($('#z_rot_model_slider').slider('value'));
	 $("#model_z_rot").change(function () {
		 
			var value=$("#model_z_rot").val();
			$("#z_rot_model_slider").slider("value",value);
			$('#display3dModel').css('transform','rotateZ(' + value + 'deg)');
			
	 });


			
});			