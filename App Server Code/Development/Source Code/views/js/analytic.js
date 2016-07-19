function getDateranges()
{
	var campaignId=$("#byCampaign").val();
	
	var actionType = "getCampaignDates";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, campaignId:campaignId},
		function(data) {
			$("#from").val(data.campaign_start_date);
			$("#to").val(data.campaign_end_date);
			
		},'json'
	);
	return false;
}

function getCampaigns()
{
	var clientId=$("#byClient").val();
	//alert(clientId+"clientId");
	var actionType = "clientCampaignInfo";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, clientId:clientId},
		function(data) {
		 //location.reload(true);
		// alert("data.campaign_id"+data.campaign_id);
		/*  $('select[name^="byCampaign"] option:selected').attr("selected",null);
        $('select[name=byCampaign] option[value='+data.campaign_id+']').attr('selected', 'selected');
		$("#select2-chosen-2").html(data.campaign_name); */
		 // $('#byCampaign option:selected').removeAttr('selected');
		// //$('select[name="byCampaign"]').find('option[value="' +data.campaign_id+ '"]').attr("selected",true);
		// $("#select2-chosen-2").html(data.campaign_name);
		// var campaign_id = [];
		  //var campaign_name = [];
		  var byCampaign = $('#byCampaign');
		 $("#byCampaign option").remove(); 
		// $('#byCampaign option:selected').removeAttr('selected');
		      $("#byCampaign").append(
	 
        $('<option></option>').val(0).html('--Select--')
    ); 
		 $("#select2-chosen-2").html('');
		$.each(data, function(i,e) {
   // campaign_id.push(e.campaign_id);
	//campaign_name.push(e.campaign_name);
	//var items = "'+ e.campaign_id +'";
	 //

	byCampaign.append(
        $('<option></option>').val(e.campaign_id).html(e.campaign_name)
    );
});  
		},'json'
	);
	return false;
	

}

 function getclientCampaigns()
  {
	var campaignId=$("#byCampaign").val();
	var clientId=$("#byClient").val();
	var actionType = "campaignInfo";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, campaignId:campaignId,clientId:clientId},
		function(data) {
/* 		  $('#byClient option:selected').removeAttr('selected');
		 $('select[name="byClient"]').find('option[value="' +data.client_id+ '"]').attr("selected",true);
		 $("#select2-chosen-1").html(data.name); */
		  $("#from").val(data.start_date);
	     $("#to").val(data.end_date);
		  //location.reload(true);
		
		var dpStartDate=$('#from').val();
        var dpEndDate=$('#to').val();
	   
	   
		 activateDatePickers(dpStartDate,dpEndDate);
	//alert("data.start_date"+data.start_date);
	//alert("data.campaign_id"+data.campaign_id);
		/*     $('#byClient option:selected').removeAttr('selected');
		 $('select[name="byClient"]').find('option[value="' +data.client_id+ '"]').attr("selected",true);
		 $("#select2-chosen-1").html(data.name);
		   $("#from").val(data.campaign_start_date);
		  $("#to").val(data.campaign_end_date); */
		 // location.reload(true);
		 
		 
		
	
	 /*  var dpStartDate=$('#from').val(data.start_date);
     var dpEndDate=$('#to').val(data.end_date);
		  $( "#from" ).datepicker({
			  defaultDate: "-2m",
			 minDate:  new Date(dpStartDate),
			 maxDate:  new Date(dpEndDate),
			 dateFormat:"yy-mm-dd",
			  changeMonth: true,
			  numberOfMonths: 3  
			});   */
		 
  //  var dpStartDate=$('#from').val();
  //  var dpEndDate=$('#to').val();
     /*    var pickerOpts = {
		  defaultDate: "-2m",
			// minDate:  new Date(dpStartDate),
			// maxDate:  new Date(dpEndDate),
			 dateFormat:"yy-mm-dd",
			  changeMonth: true,
			  numberOfMonths: 3 , 
            minDate: new Date(2012, 7 - 1, 8),
            maxDate: new Date(2012, 7 - 1, 28)
        };  
 $("#from").datepicker(pickerOpts); */
		 
	},'json'
	);
	return false;
	

}
function  activateDatePickers(dpStartDate,dpEndDate){
//alert(dpStartDate);
 $( "#from" ).datepicker({
			  defaultDate: "-2m",
			 minDate:  new Date(dpStartDate),
			 maxDate:  new Date(dpEndDate),
			 dateFormat:"yy-mm-dd",
			  changeMonth: true,
			  numberOfMonths: 3  
			});   
}



function getClientProductUserInfo(product_id,start_date,end_date){
	var actionType = "clientProductUsersFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {	
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
		}
	);
}


function getClientProductScannedInfo(product_id,start_date,end_date){
	var actionType = "clientProductScannedFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {	
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
		}
	);
}

function getClientProductClosetInfo(product_id,start_date,end_date){
	
   document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductClosetFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {	
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
		}
	);
}
function getClientProductWishlistInfo(product_id,start_date,end_date){

   document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductWishlistFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {		
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
		}
	);
}

function getClientProductShareInfo(product_id,start_date,end_date){
document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductShareFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {		
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
		}
	);
}

function getClientProductShareEmailInfo(product_id,start_date,end_date){
document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductShareEmailFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}
function getClientProductShareFBInfo(product_id,start_date,end_date){
	//alert(pAddress);

document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductShareFBFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}
function getClientProductShareTwitterInfo(product_id,start_date,end_date){
	//alert(pAddress);

document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductShareTwitterFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}

function getClientProductShareSmsInfo(product_id,start_date,end_date){
	//alert(pAddress);

document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductShareSmsFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}




function getClientProductCartInfo(product_id,start_date,end_date){
	//alert(pAddress);

document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductCartFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}
function getClientProductDetailsInfo(product_id,start_date,end_date){
	//alert(pAddress);

document.getElementById('displayProductUserLevel').scrollIntoView();

	var actionType = "clientProductDetailsFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, product_id:product_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayProductUserLevel").html(data);
			location.href="#displayProductUserLevel";
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}

/////offers flow

function getClientOfferUserInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferUsersFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferAddInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferAddFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferRemoveInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferRemoveFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferRedeemInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferRedeemFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferShareInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferShareFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}

function getClientOfferShareFBInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferShareFBFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferShareTwitterInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferShareTwitterFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferShareEmailInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferShareEmailFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}
function getClientOfferShareSMSInfo(offer_id,start_date,end_date){


	var actionType = "clientOfferShareSMSFlowLevel";
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferUserLevel").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
			location.href="#displayOfferUserLevel";
		}
	);
}



function getClientOffersFlowInfoDetails(offer_id,start_date,end_date,flow_type){
	//alert(pAddress);

document.getElementById('displayOfferFlowDet').scrollIntoView();
var actionType = "";
if(flow_type=='users')
{
	var actionType = "clientOfferUsersFlowLevel";
}
else if(flow_type=='myoffers')
{
	var actionType = "clientOfferMyOffersFlowLevel";
}
else if(flow_type=='add')
{
	var actionType = "clientOfferAddFlowLevel";
}
else if(flow_type=='remove')
{
	var actionType = "clientOfferRemoveFlowLevel";
}
else if(flow_type=='redeem')
{
	var actionType = "clientOfferRedeemFlowLevel";
}
else if(flow_type=='share')
{
	var actionType = "clientOfferShareFlowLevel";
}
else if(flow_type=='email')
{
	var actionType = "clientOfferShareEmailFlowLevel";
}
else if(flow_type=='facebook')
{
	var actionType = "clientOfferShareFBFlowLevel";
}
else if(flow_type=='twitter')
{
	var actionType = "clientOfferShareTwitterFlowLevel";
}
else if(flow_type=='sms')
{
	var actionType = "clientOfferShareSmsFlowLevel";
}
else if(flow_type=='scanned')
{
	var actionType = "clientOfferScannedFlowLevel";
}
	
	$.post(root_url+"includes/ajax/clients.ajax.php", { action: actionType, offer_id:offer_id,start_date:start_date,end_date:end_date},
		function(data) {
			//alert(data);			
			$("#displayOfferFlowDet").html(data);
			//$("#camp_keyword_search_form").attr("action", root_url+"campgrounds/search/");
			//$("#ajaxLoaderDiv").hide("slow");
		}
	);
}








