<?php
global $table_prefix,$wpdb;

$sql_taxonomy = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."terms t on tt.term_id = t.term_id where tt.taxonomy = 'custom_cat'";
$taxonomies = $wpdb->get_results( $sql_taxonomy );
$sql_paymentmethod = "select * from ".$table_prefix."ccb_scbooking_paymentmethods";
$payment_methods = $wpdb->get_results( $sql_paymentmethod );
$current_user = wp_get_current_user();
?>

<script type="text/javascript">
  jQuery(function() {
    jQuery( "#dtpfromdate" ).datepicker({ dateFormat: "yy-mm-dd" });
		jQuery( "#dtptodate" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
	// Read a page's GET URL variables and return them as an associative array.
	function ccb_getUrlVars()
	{
			var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++)
			{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
			}
			return vars;
	}
	//
	function ccb_get_rooms(){
		  var term_id = jQuery('#roomtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'ccb_get_room_bycat',
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								ccb_get_roomprice();
							}
							else{
								jQuery('#optroom').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	function ccb_get_rooms_for_bookingcell(roomid){
		  var term_id = jQuery('#roomtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'ccb_get_room_bycat',  
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								ccb_get_roomprice();
							}
							else{
								jQuery('#optroom').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optroom').val(roomid);
			});
	}
	function ccb_get_rooms_for_editbooking(roomid){
		  //alert(roomid);
		  var term_id = jQuery('#roomtype').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'ccb_get_room_bycat',
            term_id:term_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								for(var i=0;i<data.length;i++){
										if(i==0){
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'" selected="selected">'+data[i]['post_title']+'</option>');
										}
										else{
											jQuery('#optroom').append('<option value="'+data[i]['ID']+'">'+data[i]['post_title']+'</option>');
										}
								}
								ccb_get_roomprice();
							}
							else{
								jQuery('#optroom').empty();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			}).done(function(msg){
					jQuery('#optroom').val(roomid);
			});
	}
	function ccb_get_roomprice(){
			var post_id = jQuery('#optroom').val();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'ccb_get_roomprice_by_custompost',
            post_id:post_id
          },
					success: function (data) {
							var count = data.length;
							if(count>0){
								jQuery('#txtCustomPrice').val(data[0]['meta_value']);
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	function ccb_setbooking_info(booking_id){
			ccb_get_rooms();
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					dataType:'json', 
					data: {
            action: 'ccb_get_bookings',
            booking_id:booking_id
          },
					success: function (data) {
							var count = data.length;
							jQuery('#optroom').empty();
							if(data.length > 0 ){
								console.log(data);
								var booking = data[0];
								jQuery('#hdnbookingid').val(booking['booking_id']);
								jQuery('#roomtype').val(booking['room_type']);
								ccb_get_rooms_for_editbooking(booking['room_id']);
								jQuery('#dtpfromdate').val(booking['from_date']);
								jQuery('#dtptodate').val(booking['to_date']);
								
								jQuery('#txtFirstName').val(booking['first_name']);
								jQuery('#txtLastName').val(booking['last_name']);
								jQuery('#txtEmail').val(booking['email']);
								jQuery('#txtPhone').val(booking['phone']);
								jQuery('#details').val(booking['details']);
								jQuery('#txtbookingby').val(booking['booking_by']);
								jQuery('#optguest_type').val(booking['guest_type']);
								jQuery('#txtCustomPrice').val(booking['custom_price']);
								jQuery('#txtPaid').val(booking['paid']);
								jQuery('#txtDue').val(booking['due']);
								jQuery('#optpaymentmethod').val(booking['payment_method']);
								jQuery('#txtTrackingNo').val(booking['tracking_no']);
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
			
			
	}
	function ccb_cleardata(){
			jQuery('#hdnbookingid').val('');
			jQuery('#dtpfromdate').val('');
			jQuery('#dtptodate').val('');
			
			jQuery('#txtFirstName').val('');
			jQuery('#txtLastName').val('');
			jQuery('#txtEmail').val('');
			jQuery('#txtPhone').val('');
			jQuery('#details').val('');
			jQuery('#txtbookingby').val('<?php echo $current_user->display_name?>');
			jQuery('#optguest_type').val('');
			jQuery('#txtCustomPrice').val('');
			jQuery('#txtPaid').val('');
			jQuery('#txtDue').val('');
			jQuery('#txtTrackingNo').val('');
	}
	jQuery(document).ready(function(){
			var calltype = ccb_getUrlVars()["calltype"];
			if(calltype){
				if(calltype == 'editbooking'){
					ccb_get_rooms();
					<?php
          if(isset($_REQUEST['id'])){
              $id = $_REQUEST['id'];
              global $table_prefix,$wpdb;
              $sql = "select * from ".$table_prefix."ccb_scbooking where booking_id=".$id;
              $result = $wpdb->get_results($sql);
              ?>
              var booking = <?php echo json_encode($result[0]);?>;
              console.log(booking);
              jQuery('#hdnbookingid').val(booking['booking_id']);
              //alert(booking['room_type']);
              jQuery('#roomtype').val(booking['room_type']);
              ccb_get_rooms_for_editbooking(booking['room_id']);

              jQuery('#optshift').val(booking['shift']);
              jQuery('#dtpfromdate').val(booking['from_date']);
              jQuery('#dtptodate').val(booking['to_date']);

              jQuery('#txtFirstName').val(booking['first_name']);
              jQuery('#txtLastName').val(booking['last_name']);
              jQuery('#txtEmail').val(booking['email']);
              jQuery('#txtPhone').val(booking['phone']);
              jQuery('#details').val(booking['details']);
              jQuery('#txtbookingby').val(booking['booking_by']);
              jQuery('#optguest_type').val(booking['guest_type']);
              jQuery('#txtCustomPrice').val(booking['custom_price']);
              jQuery('#txtPaid').val(booking['paid']);
              jQuery('#txtDue').val(booking['due']);
              jQuery('#optpaymentmethod').val(booking['payment_method']);
              jQuery('#txtTrackingNo').val(booking['tracking_no']);
          <?php } ?>
				}	
			}
			
      //---------------------------------	
			ccb_get_rooms();
			jQuery('#roomtype').on('change', function(){
					ccb_get_rooms();
			});
			//---room price--------
			ccb_get_roomprice();
			jQuery('#optroom').on('change', function(){
					ccb_get_roomprice();
			});
			//----save booking----
			jQuery('#frmbooking').on('submit',function(e){
	  		 e.preventDefault();
				 ccb_save_booking();
			});
			//---------------------------
			<?php if(isset($_REQUEST['calendarcell'])){
			$calendarcell = $_REQUEST['calendarcell'];
			$calendarcell_data = explode("|",$calendarcell);
			$cell_month_cat = $calendarcell_data[0];
			$cell_month = $calendarcell_data[1];
			$cell_date =  $calendarcell_data[2];
			?>
					jQuery('#roomtype').val(<?php echo $cell_month_cat;?>);
					ccb_get_rooms_for_bookingcell(<?php echo $cell_month;?>);
					jQuery('#dtpfromdate').val('<?php echo $cell_date;?>');	   
			<?php }?>
			//--------------------------------
	});
	function ccb_save_booking(){
			var hdnbookingid = jQuery('#hdnbookingid').val();
			var roomtype = jQuery('#roomtype').find('option:selected').val();
			var room = jQuery('#optroom').find('option:selected').text();
			var room_id = jQuery('#optroom').find('option:selected').val();
      var bshift = jQuery('#optshift').find('option:selected').val();
			var from_date = jQuery('#dtpfromdate').val();
			var to_date = jQuery('#dtptodate').val();
			
			var first_name = jQuery('#txtFirstName').val();
			var last_name = jQuery('#txtLastName').val();
			var email = jQuery('#txtEmail').val();
			var phone = jQuery('#txtPhone').val();
			var details = jQuery('#details').val();
			var bookingby = jQuery('#txtbookingby').val();
			var guest_type = jQuery('#optguest_type').val();
			var price = jQuery('#txtCustomPrice').val();
			var paid = jQuery('#txtPaid').val();
			var due = jQuery('#txtDue').val();
			var payment_method = jQuery('#optpaymentmethod').find('option:selected').val();
			var tracking_no = jQuery('#txtTrackingNo').val();
			
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {action:'ccb_save_booking',hdnbookingid: hdnbookingid,room_type:roomtype,roomid: room_id, room: room,booking_shift:bshift,from_date:from_date,to_date:to_date,first_name:first_name,last_name:last_name,email:email,phone:phone,details: details,bookingby: bookingby, guest_type: guest_type, price: price,paid: paid,due: due, payment_method: payment_method, tracking_no: tracking_no },
					success: function (data) {
							if(data.length>0){
								alert('added successfully');
								jQuery("#frmgetbooking").submit();
							}
					},
					error : function(s , i , error){
							console.log(error);
					}
			});
	}
	function ccb_calculate_due(){
		$price = jQuery('#txtCustomPrice').val();
		$paid = jQuery('#txtPaid').val();
		$due = ($price - $paid);
		jQuery('#txtDue').val($due); 
	}
	//===-----------------------add booking dialog-------------------------------===
  </script>
  <style type="text/css">
	#frmbooking select, button, input, textarea {
		border:1px solid #E2E2E2;
		margin:5px;
	}
	#frmbooking label {
		margin:3px;
	}
	span{font-size:12px;}
	#frmbooking table{
		width: 50%;
	}
	input.rounded {
		border: 1px solid #ccc;
	    -moz-border-radius: 5px;
	    -webkit-border-radius: 5px;
	    border-radius: 5px;
	    /*-moz-box-shadow: 2px 2px 3px #666;
	    -webkit-box-shadow: 2px 2px 3px #666;
	    box-shadow: 2px 2px 3px #666;*/
	    font-size: 20px;
	    padding: 4px 7px;
	    outline: 0;
	    -webkit-appearance: none;
	}
	select.rounded {
		border: 1px solid #ccc;
	    -moz-border-radius: 5px;
	    -webkit-border-radius: 5px;
	    border-radius: 5px;
	    /*-moz-box-shadow: 2px 2px 3px #666;
	    -webkit-box-shadow: 2px 2px 3px #666;
	    box-shadow: 2px 2px 3px #666;*/
	    font-size: 20px;
	    padding: 4px 7px;
	    outline: 0;
	    -webkit-appearance: none;
	}
	input.rounded:focus {
	    border-color: #4CB7FF;
	}
  </style>
  <?php $current_user = wp_get_current_user();
	?>
   
  <div id="addbooking_dialog" title="Add/Edit Booking" class="wrapper" style="display:none;">
  <div class="wrap" style="float:left; width:100%;">
   
    <div class="main_div">
     	<div class="metabox-holder" style="width:49%; float:left;">
        <form id="frmbooking" action="" method="post" style="width:100%">
          <table style="margin:10px;width:300px;">
          	<tr>
            	<td class="bookinglavel"> <label for="roomtype">Room Type</label></td>
              <td class="bookinginput">
              	<select id="roomtype" name="roomtype">
                  <?php foreach($taxonomies as $taxo){?>
                  <option value="<?php echo $taxo->term_id;?>"><?php echo $taxo->name;?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel"><label for="optroom">Room</label></td>
              <td class="bookinginput">
              	<select id="optroom" name="optroom" >
                  
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">Shift</td>
              <td class="bookinginput">
 	             	<select id="optshift" name="optshift" >
  									<option value="Day">Day</option>                
                    <option value="Night">Night</option>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="dtpfromdate">From Date:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="dtpfromdate" name="dtpfromdate" class="rounded" value="" style="width:230px;" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="dtptodate">To Date:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="dtptodate" name="dtptodate" value="" class="rounded" style="width:230px;" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtFirstName">First Name:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtFirstName" name="txtFirstName" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtLastName">Last Name:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtLastName" name="txtLastName" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtEmail">Email:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtEmail" name="txtEmail"  class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtPhone">Phone:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtPhone" name="txtPhone" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="details">Details:</label>
              </td>
              <td class="bookinginput">
              	<textarea cols="50" rows="7" id="details" class="rounded" name="details"></textarea>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtbookingby">Booking/Reservation By:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" readonly="readonly" id="txtbookingby" name="txtbookingby" class="rounded" value="<?php echo $current_user->display_name; ?>" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="optguest_type">Guest Type:</label>
              </td>
              <td class="bookinginput">
                <select id="optguest_type" name="optguest_type" >
                    <option value="single">Single</option>
                    <option value="business">Business</option>
                    <option value="couple">Couple</option>
                    <option value="group_of_adults">Group of Adults</option>
                    <option value="family_with_kids">Family with Kids</option>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtCustomPrice">Price:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtCustomPrice" name="txtCustomPrice" class="rounded" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtPaid">Paid:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtPaid" name="txtPaid" class="rounded" onkeyup="ccb_calculate_due()" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	<label for="txtDue">Due:</label>
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtDue" name="txtDue" class="rounded" value="" />
                <input type="hidden" id="hdnbookingid" name="hdnbookingid" value="" style="width:150px;"/>
              </td>
            </tr>
             <tr>
            	<td class="bookinglavel">
              	Payment Method:
              </td>
              <td class="bookinginput">
              	<select id="optpaymentmethod" name="optpaymentmethod" >
                	<?php foreach($payment_methods as $pm){?>
                  	<option value="<?php echo $pm->payment_method;?>"><?php echo $pm->payment_method;?></option>
                  <?php }?>  
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Receipt/Tracking No:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtTrackingNo" name="txtTrackingNo" value="" />
              </td>
            </tr>
          </table>
          </form>
          
    		</div>
      </div>
    </div>
   </div>