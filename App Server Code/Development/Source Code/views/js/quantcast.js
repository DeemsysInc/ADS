
$(document).ready(function () {
//get uniques count
function getUniques() {
  var actionType = "uniques";
  $.post(root_url+"includes/ajax/quantcast.ajax.php", { action: actionType},
		function(data) {
			$("#uniques").html(data.uniques);
			setTimeout(refreshUserlist, 5000); 
		},'json'
	);
  } getUniques();
  
});







