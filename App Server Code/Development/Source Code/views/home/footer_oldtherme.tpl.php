</article>
	<!-- End content -->
<footer>
		
		<div class="float-left">
			<?php /*
			<a href="#" class="button">Help</a>
			<a href="#" class="button">About</a>
			*/ ?>
		</div>
		
		<div class="float-right">
			<a href="#top" class="button"><img src="<?php echo $config['LIVE_URL']; ?>views/images/icons/fugue/navigation-090.png" width="16" height="16"> Page top</a>
		</div>
		
	</footer>
	<center><p>&copy 2014 <a href="http://www.seemoreinteractive.com">Seemore</a> | Control Panel</p></center>
	<!--
	
	Updated as v1.5:
	Libs are moved here to improve performance
	
	-->
	
	<!-- Generic libs -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<?php /*?><script src="<?php echo $config['LIVE_URL']; ?>views/js/old-browsers.js"></script>	<?php */?>	<!-- remove if you do not need older browsers detection -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/jquery.hashchange.js"></script>
	
	<!-- Template libs -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.accessibleList.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/searchField.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/common.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/standard.js"></script>
	<!--[if lte IE 8]><script src="<?php echo $config['LIVE_URL']; ?>views/js/standard.ie.js"></script><![endif]-->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.tip.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.contextMenu.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.modal.js"></script>
	
	<!-- Custom styles lib -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/list.js"></script>
	
	<!-- Plugins -->
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/jquery.dataTables.min.js"></script>
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/libs/jquery.datepick/jquery.datepick.min.js"></script>
	
	<script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.minicolors.js"></script>

	
	<!-- Custom Javascripts code -->
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/common.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/login.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/users.js"></script>
    <script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/edit.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/validation.js"></script>
	<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/analytic.js"></script>
	
	<!-- Charts library -->
	<!--Load the AJAX API-->
	<script src="http://www.google.com/jsapi"></script>
	<script>
		
		/*
		 * This script is dedicated to building and refreshing the demo chart
		 * Remove if not needed
		 */
		
		// Load the Visualization API and the piechart package.
		google.load('visualization', '1', {'packages':['corechart']});
		
		// Add listener for tab
		$('#tab-stats').onTabShow(function() { drawVisitorsChart(); }, true);
		
		// Handle viewport resizing
		var previousWidth = $(window).width();
		$(window).resize(function()
		{
			if (previousWidth != $(window).width())
			{
				drawVisitorsChart();
				previousWidth = $(window).width();
			}
		});
		
		// Demo chart
		function drawVisitorsChart() {

			// Create our data table.
			var data = new google.visualization.DataTable();
			var raw_data = [['Website', 50, 73, 104, 129, 146, 176, 139, 149, 218, 194, 96, 53],
							['Shop', 82, 77, 98, 94, 105, 81, 104, 104, 92, 83, 107, 91],
							['Forum', 50, 39, 39, 41, 47, 49, 59, 59, 52, 64, 59, 51],
							['Others', 45, 35, 35, 39, 53, 76, 56, 59, 48, 40, 48, 21]];
			
			var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
			
			data.addColumn('string', 'Month');
			for (var i = 0; i < raw_data.length; ++i)
			{
				data.addColumn('number', raw_data[i][0]);	
			}
			
			data.addRows(months.length);
			
			for (var j = 0; j < months.length; ++j)
			{	
				data.setValue(j, 0, months[j]);	
			}
			for (var i = 0; i < raw_data.length; ++i)
			{
				for (var j = 1; j < raw_data[i].length; ++j)
				{
					data.setValue(j-1, i+1, raw_data[i][j]);	
				}
			}
			
			// Create and draw the visualization.
			var div = $('#chart_div');
			new google.visualization.ColumnChart(div.get(0)).draw(data, {
				title: 'Monthly unique visitors count',
				width: div.width(),
				height: 330,
				legend: 'right',
				yAxis: {title: '(thousands)'}
			});
			
			// Message
			notify('Chart updated');
		};
		
	</script>
	
	<script>
		
		/*
		 * This script shows how to setup the various template plugins and functions
		 */
		
		$(document).ready(function()
		{
			/*
			 * Example context menu
			 */
			
			// Context menu for all favorites
			$('.favorites li').bind('contextMenu', function(event, list)
			{
				var li = $(this);
				
				// Add links to the menu
				if (li.prev().length > 0)
				{
					list.push({ text: 'Move up', link:'#', icon:'up' });
				}
				if (li.next().length > 0)
				{
					list.push({ text: 'Move down', link:'#', icon:'down' });
				}
				list.push(false);	// Separator
				list.push({ text: 'Delete', link:'#', icon:'delete' });
				list.push({ text: 'Edit', link:'#', icon:'edit' });
			});
			
			// Extra options for the first one
			$('.favorites li:first').bind('contextMenu', function(event, list)
			{
				list.push(false);	// Separator
				list.push({ text: 'Settings', icon:'terminal', link:'#', subs:[
					{ text: 'General settings', link: '#', icon: 'blog' },
					{ text: 'System settings', link: '#', icon: 'server' },
					{ text: 'Website settings', link: '#', icon: 'network' }
				] });
			});
			
			/*
			 * Dynamic tab content loading
			 */
			
			$('#tab-comments').onTabShow(function()
			{
				$(this).loadWithEffect('ajax-tab.html', function()
				{
					notify('Content loaded via ajax');
				});
			}, true);
			
			/*
			 * Table sorting
			 */
			
			// A small classes setup...
			$.fn.dataTableExt.oStdClasses.sWrapper = 'no-margin last-child';
			$.fn.dataTableExt.oStdClasses.sInfo = 'message no-margin';
			$.fn.dataTableExt.oStdClasses.sLength = 'float-left';
			$.fn.dataTableExt.oStdClasses.sFilter = 'float-right';
			$.fn.dataTableExt.oStdClasses.sPaging = 'sub-hover paging_';
			$.fn.dataTableExt.oStdClasses.sPagePrevEnabled = 'control-prev';
			$.fn.dataTableExt.oStdClasses.sPagePrevDisabled = 'control-prev disabled';
			$.fn.dataTableExt.oStdClasses.sPageNextEnabled = 'control-next';
			$.fn.dataTableExt.oStdClasses.sPageNextDisabled = 'control-next disabled';
			$.fn.dataTableExt.oStdClasses.sPageFirst = 'control-first';
			$.fn.dataTableExt.oStdClasses.sPagePrevious = 'control-prev';
			$.fn.dataTableExt.oStdClasses.sPageNext = 'control-next';
			$.fn.dataTableExt.oStdClasses.sPageLast = 'control-last';
			
			// Apply to Order History table
			$('#cms_clients').each(function(i)
			{
				$(".dataTables_wrapper").css("width","80%");
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							null,
							{ sType: 'string' },
							{ bSortable: false },
							{ bSortable: false },
							{ bSortable: false },
							{ bSortable: false }
					
						],
						bAutoWidth: false,
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>RfClrtip<"block-footer clearfix"lf>',
						//"sDom": 'RfClrtip',
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
							this.parent().fnAdjustColumnSizing();

						}

					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			// Apply to Client Invoices table
			$('#cms_clients_products').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ sType: 'string' },
							{ bSortable: false },
							{ sType: 'string' },
							{ sType: 'string' },
							{ sType: 'string' },
							{ bSortable: false },
							{ bSortable: false }
					
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			// Apply to Ranking Reports table
			$('#reports_ranking').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ sType: 'string' },
							{ sType: 'string' },
							{ sType: 'string' },
							{ bSortable: false },
							{ bSortable: false }
					
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			// Apply to My Packages table
			$('#my_package').each(function(i)
			{
				// DataTable config
				var table = $(this),
					oTable = table.dataTable({
						/*
						 * We set specific options for each columns here. Some columns contain raw data to enable correct sorting, so we convert it for display
						 * @url http://www.datatables.net/usage/columns
						 */
						aoColumns: [
							{ sType: 'string' },
							{ sType: 'string' },
							{ sType: 'string' },
							{ sType: 'string' },
							{ bSortable: false },
							{ sType: 'string' },
							
						],
						
						/*
						 * Set DOM structure for table controls
						 * @url http://www.datatables.net/examples/basic_init/dom.html
						 */
						sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
						
						/*
						 * Callback to apply template setup
						 */
						fnDrawCallback: function()
						{
							this.parent().applyTemplateSetup();
						},
						fnInitComplete: function()
						{
							this.parent().applyTemplateSetup();
						}
					});
				
				// Sorting arrows behaviour
				table.find('thead .sort-up').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'asc']]);
					
					// Prevent bubbling
					return false;
				});
				table.find('thead .sort-down').click(function(event)
				{
					// Stop link behaviour
					event.preventDefault();
					
					// Find column index
					var column = $(this).closest('th'),
						columnIndex = column.parent().children().index(column.get(0));
					
					// Send command
					oTable.fnSort([[columnIndex, 'desc']]);
					
					// Prevent bubbling
					return false;
				});
			});
			
			
			/*
				Add "current" class when page loaded.
			*/
				updateBreadcrumb();
			  /* $("div.container_12 ul li").removeClass('current');
			  $(this).addClass("current"); */
			
			/*
			 * Datepicker
			 * Thanks to sbkyle! http://themeforest.net/user/sbkyle
			 */
			$('.datepicker').datepick({
				alignment: 'bottom',
				showOtherMonths: true,
				selectOtherMonths: true,
				renderer: {
					picker: '<div class="datepick block-border clearfix form"><div class="mini-calendar clearfix">' +
							'{months}</div></div>',
					monthRow: '{months}', 
					month: '<div class="calendar-controls" style="white-space: nowrap">' +
								'{monthHeader:M yyyy}' +
							'</div>' +
							'<table cellspacing="0">' +
								'<thead>{weekHeader}</thead>' +
								'<tbody>{weeks}</tbody></table>', 
					weekHeader: '<tr>{days}</tr>', 
					dayHeader: '<th>{day}</th>', 
					week: '<tr>{days}</tr>', 
					day: '<td>{day}</td>', 
					monthSelector: '.month', 
					daySelector: 'td', 
					rtlClass: 'rtl', 
					multiClass: 'multi', 
					defaultClass: 'default', 
					selectedClass: 'selected', 
					highlightedClass: 'highlight', 
					todayClass: 'today', 
					otherMonthClass: 'other-month', 
					weekendClass: 'week-end', 
					commandClass: 'calendar', 
					commandLinkClass: 'button',
					disabledClass: 'unavailable'
				}
			});
		});
		
		

		</script>
		
		<script>
		$(document).ready( function() {
				
				var consoleTimeout;
				
				$('.minicolors').each( function() {
					var dispHexValue = '';
					//
					// Dear reader, it's actually much easier than this to initialize 
					// miniColors. For example:
					//
					//  $(selector).minicolors();
					//
					// The way I've done it below is just to make it easier for me 
					// when developing the plugin. It keeps me sane, but it may not 
					// have the same effect on you!
					//
					$(this).minicolors({
						control: $(this).attr('data-control') || 'hue',
						defaultValue: '',
						inline: $(this).hasClass('inline'),
						letterCase: $(this).hasClass('uppercase') ? 'uppercase' : 'lowercase',
						opacity: $(this).hasClass('opacity'),
						position: $(this).attr('data-position') || 'default',
						styles: $(this).attr('data-style') || '',
						swatchPosition: $(this).attr('data-swatch-position') || 'left',
						textfield: !$(this).hasClass('no-textfield'),
						theme: $(this).attr('data-theme') || 'default',
						change: function(hex, opacity) {
							
							// Generate text to show in console
							text = hex ? hex : 'transparent';
							if( opacity ) text += ', ' + opacity;
							text += ' / ' + $(this).minicolors('rgbaString');
							
							// Show text in console; disappear after a few seconds
							$('#console').text(text).addClass('busy');
							clearTimeout(consoleTimeout);
							consoleTimeout = setTimeout( function() {
								$('#console').removeClass('busy');
							}, 3000);
							dispHexValue=hex;
						},
						 hide: function() {
							var inputID = $(this).attr('id');
							$("#hdn_"+inputID).val(dispHexValue);
						}
					});
					
				});
				
			});
	</script>
	<!-- jcaraousel js-->
	<!--<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>plugins/jsor-jcarousel-8e3df57/lib/jquery-1.9.1.min.js"></script>-->
    <script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>plugins/jsor-jcarousel-8e3df57/lib/jquery.jcarousel.min.js"></script>
    
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel();
});
jQuery(document).ready(function() {
    jQuery('#mycarousel2').jcarousel();
});
jQuery(document).ready(function() {
    jQuery('#mycarousel3').jcarousel();
});
</script>

<script>
function readURL(input) {
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function (e) {
$('#img_prev')
.attr('src', e.target.result)
.width(250)
.height(250);
};

reader.readAsDataURL(input.files[0]);
}
}
function previewLogo(input) {
	
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function (e) {
$('#img_prev')
.attr('src', e.target.result)
.width(253)
.height(115);
};

reader.readAsDataURL(input.files[0]);
}
}
function previewBgImage(input) {
	
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function (e) {
$('#img_prev_bgimage')
.attr('src', e.target.result)
.width(253)
.height(250);
};

reader.readAsDataURL(input.files[0]);
}
}
function previewProductImage(input) {
	
if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function (e) {
$('#img_prev_product')
.attr('src', e.target.result)
.width(253)
.height(250);
};

reader.readAsDataURL(input.files[0]);
}
}
</script>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/creator.js"></script>

<?php /*?><script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/dropzone.js"></script><?php */?>
<script type="text/javascript" src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.easyui.min.js"></script>

<!-- start ui slider -->

<link rel="stylesheet" href="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/css/jquery.ui.theme.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/css/jquery.ui.slider.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/css/main.css" type="text/css" />

<script src="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/js/jquery.ui.widget.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/js/jquery.ui.mouse.js"></script>
<script src="<?php echo $config['LIVE_URL']; ?>plugins/jqueryUiSlider/js/jquery.ui.slider.js"></script>

        
<!-- end ui slider -->

  

</body>
</html>