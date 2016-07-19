<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/bs3/js/bootstrap.min.js"></script>
	
<!--common script init for all pages-->
<script src="<?php echo $config['LIVE_URL']; ?>views/js/scripts.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.nicescroll.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery-multi-select/js/jquery.quicksearch.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/dashboard.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.customSelect.min.js" ></script>

<!--dynamic table initialization -->
<script type="text/javascript" language="javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/data-tables/DT_bootstrap.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/dynamic_table_init.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/select2/select2.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/select-init.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/advanced-form.js"></script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/login.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.validate.min.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/additional-methods.min.js"></script>
<?php if($pAction=="analytics" || $pAction==""){?>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/analytic.js"></script>
<script src="<?php echo $config['LIVE_URL'];?>plugins/jquery-date-range-picker-master/moment.min.js"></script>
    <script src="<?php echo $config['LIVE_URL'];?>plugins/jquery-date-range-picker-master/jquery.daterangepicker.js"></script>
<script>
$(document).ready(function () {

	$('#from-to-date-range').dateRangePicker({
		 stickyMonths: true,
		 autoClose: true,
		 showShortcuts:false,
		 endDate: new Date()
	});
	$('a[rel*=facebox]').facebox()
});
   


</script>
<?php }?>

<?php if($pAction=="clients"){?>

<script src="<?php echo $config['LIVE_URL']; ?>views/js/clients.js"></script>
<?php }?>

<script src="<?php echo $config['LIVE_URL']; ?>views/js/edit.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>views/js/facebox.js"></script>

   

</body>
</html>