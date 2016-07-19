$(function() {
    $('#slider1').slider({
        value:1,
        min: 0,
        max: 1,
        step: 0.1,
        slide: function( event, ui ) {
            $('#opacity').val(ui.value + ' px');
            $('#displayButton').css('opacity', ui.value);
        }
    });
    $('#opacity').val($('#slider1').slider('value') + ' px');

    var aFonts = new Array('', 'Verdana', 'arial', 'Tahoma', 'Times New Roman', 'Georgia');
    $('#slider2').slider({
        value:1,
        min: 1,
        max: 5,
        step: 1,
        slide: function(event, ui) {
            var sFontFamily = aFonts[ui.value];
            $('#font_family').val(sFontFamily);
            $('.box').css('font-family', sFontFamily);
        }
    });
    $('#font_family').val(aFonts[$('#slider2').slider('value')]);

    var aWeights = new Array('', 'normal', 'bold', 'bolder', 'lighter', 'inherit');
    $('#slider3').slider({
        value:1,
        min: 1,
        max: 5,
        step: 1,
        slide: function(event, ui) {
            var sFontWeight = aWeights[ui.value];
            $('#font_weight').val(sFontWeight);
            $('.box').css('font-weight', sFontWeight);
        }
    });
    $('#font_weight').val(aWeights[$('#slider3').slider('value')]);

    var aAligns = new Array('', 'left', 'right', 'center', 'justify');
    $('#slider4').slider({
        value:1,
        min: 1,
        max: 4,
        step: 1,
        slide: function(event, ui) {
            var sTextAlign = aAligns[ui.value];
            $('#text_align').val(sTextAlign);
            $('.box').css('text-align', sTextAlign);
        }
    });
    $('#text_align').val(aAligns[$('#slider4').slider('value')]);


//new added

                          $('#slider5').slider({
								orientation	: 'horizontal',
								max			: 180,
								min			: -180,
								value		: 0,
								slide		: function(event, ui) {
									//var $this = $(this);
									/* Change the rotation and register that value in data object when it stops */
									 $('#displayButton1').css({'-moz-transform':'rotate('+ui.value+'deg)','-webkit-transform':'rotate('+ui.value+'deg)'});
									//$('#rotation',$this).html(ui.value);
									 $('#rotation').val(ui.value + ' Degree');
								}
							});	
							

                           $('#rotation').val($('#slider5').slider('value') + ' Degree');




			
});			