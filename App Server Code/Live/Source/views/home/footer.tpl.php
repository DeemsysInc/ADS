<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/bs3/js/bootstrap.min.js"></script>
<!--common script init for all pages-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/scripts.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/analytic.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/login.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.scrollTo/jquery.scrollTo.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>-->
<!--<script src="<?php echo $config['LIVE_URL']; ?>views/js/calendar/clndr.js"></script>-->
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/calendar/moment-2.2.1.js"></script>
<!--<script src="<?php echo $config['LIVE_URL']; ?>views/js/evnt.calendar.init.js"></script>-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jvector-map/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jvector-map/jquery-jvectormap-us-lcc-en.js"></script>
<!--<script src="<?php echo $config['LIVE_URL']; ?>views/js/gauge/gauge.js"></script>-->


<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>




<!--clock init-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/css3clock/js/css3clock.js"></script>


<!--Easy Pie Chart-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/sparkline/jquery.sparkline.js"></script>
<!--Morris Chart-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/morris-chart/morris.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/morris-chart/raphael-min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/dashboard.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.customSelect.min.js" ></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script type="text/javascript">
  jQuery(document).ready(function($) {

   $( "#from" ).datepicker({
      defaultDate: "-2m",
      maxDate: +0,
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
    $( "#to" ).datepicker({
      //defaultDate: "+1w",
      maxDate: +0,
        dateFormat:"yy-mm-dd",
      changeMonth: true,
      numberOfMonths: 3
      
    });
});
</script>

<!--dynamic table initialization -->
<script type="text/javascript" language="javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/data-tables/DT_bootstrap.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/dynamic_table_init.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/select2/select2.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/select-init.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-tags-input/jquery.tagsinput.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/advanced-form.js"></script>



</body>
</html>