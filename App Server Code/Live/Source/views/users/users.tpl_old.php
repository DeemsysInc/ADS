<script type="text/javascript" charset="utf-8">
	$(document).ready( function () {
				var oTable = $('#vziom_clients').dataTable();
				oTable.makeEditable( {
					sUpdateURL : '<?php echo $config['LIVE_URL']; ?>includes/ajax/users.ajax.php?action=ajaxEditSalesUsers',
				
					"submitdata": function ( value, settings ) {
						return {
							"row_id": this.parentNode.getAttribute('id'),
							"column": oTable.fnGetPosition( this )[2]
						};
					},
					"aoColumns": [
						null,
						null,
						{
							tooltip: 'Click to edit First name',
							event:'click',
							loadtext: 'loading...',
							submitdata: function ( value, settings ) {
								return { "row_id": this.parentNode.getAttribute('id') };
							},
							type: 'text',
							onblur: 'cancel',
							cssclass: "required",
							//submit: 'Ok',
							loadtype: 'GET',
							callback: function( sValue, y ) {
							}
						},
						{
							tooltip: 'Click to edit Last name',
							event:'click',
							loadtext: 'loading...',
							submitdata: function ( value, settings ) {
								return { "row_id": this.parentNode.getAttribute('id') };
							},
							type: 'text',
							onblur: 'cancel',
							cssclass: "required",
							//submit: 'Ok',
							loadtype: 'GET',
							callback: function( sValue, y ) {
							}
						},
						null,
						{
							tooltip: 'Click to edit Email Address',
							event:'click',
							loadtext: 'loading...',
							submitdata: function ( value, settings ) {
								return { "row_id": this.parentNode.getAttribute('id') };
							},
							type: 'text',
							onblur: 'cancel',
							cssclass: "required email",
							//submit: 'Ok',
							loadtype: 'GET',
							callback: function( sValue, y ) {
							}
						},
						{
						
							tooltip: 'Click to edit Phone',
							event:'click',
							loadtext: 'loading...',
							submitdata: function ( value, settings ) {
								return { "row_id": this.parentNode.getAttribute('id') };
							},
							type: 'text',
							//onblur: 'cancel',
							
							//submit: 'Ok',
							loadtype: 'GET',
							callback: function( sValue, y ) {
							},
							onsubmit: function(settings, td) {
								var input = $(td).find('input');
								var original = input.val();
								var isValid = toValidateUSPhone(original);
								if (isValid==false){
									alert("Enter valid phone number");
									return false;
								}else{
									return true;
								}
							}
						},
						null
					]									

				});
				
				$('#frm_users').submit( function() {
					var sData = oTable.$('input').serialize();
					var actionType = "deleteUsers";
					//alert( "The following data would have been submitted to the server: \n\n"+sData );
					$.post(root_url+"includes/ajax/users.ajax.php", { action: actionType, dataVals:sData},
						function(data){
							if(data.msg=='success')
							{
								window.location=data.redirect;
								return true;
							} else
							{
								alert('User not deleted');
								return false;
							}
						}, 'json'
					);	
			
					return false;
				} );
				
			} );


</script>
<div class="full_w">
	<h2 style="margin-left:15px;">Sales Users</h2>
	
	<div class="entry">
		<div class="sep"></div>
	</div>
	<form id="frm_users">
					<div style="text-align:right; padding-bottom:1em;">
						<a class="button add" href="<?php echo $config['LIVE_URL']; ?>users/add">Add User</a> 
						<button type="submit" class="button cancel">Delete</button>
					</div>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="vziom_clients">
	<thead>
		<tr>
			<th>Check</th>
			<th>Sl.No.</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Email Address</th>
			<th>Phone</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0;$i<count($outArray);$i++){ ?>
		<tr id="<?php echo ($i+1)."_".$outArray[$i]['u_id']; ?>">
			<td align="middle"><input type="checkbox" name="check<?php echo $i; ?>" value="<?php echo $outArray[$i]['u_id']; ?>"></td>
			<td align="middle"><?php echo ($i+1); ?></td>
			<td id="u_first_name"><?php echo $outArray[$i]['u_first_name']; ?></td>
			<td id="u_last_name"><?php echo $outArray[$i]['u_last_name']; ?></td>
			<td><?php echo $outArray[$i]['u_uname']; ?></td>
			<td id="u_email"><?php echo $outArray[$i]['u_email']; ?></td>
			<td id="phone" onkeyup="validateUSNumber(event,this);">&nbsp;</td>
			<td>
				<a href="javascript:void(0);" class="table-icon edit" title="Edit" onclick="editUser('<?php echo $outArray[$i]['u_id']; ?>');"></a>
				<a href="javascript:void(0);" class="table-icon delete" title="Delete" onclick="deleteUser('<?php echo $outArray[$i]['u_id']; ?>');"></a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Check</th>
			<th>Sl.No.</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Email Address</th>
			<th>Phone</th>
			<th>Action</th>
		</tr>
	</tfoot>
</table>
</form>
</div>