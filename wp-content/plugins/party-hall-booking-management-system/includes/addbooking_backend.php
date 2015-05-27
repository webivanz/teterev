<?php
	global $table_prefix,$wpdb;
	$sql_taxonomy = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."terms t on tt.term_id = t.term_id where tt.taxonomy = 'custom_cat'";
	$taxonomies = $wpdb->get_results( $sql_taxonomy );
	$sql_paymentmethod = "select * from ".$table_prefix."ccb_scbooking_paymentmethods";
	$payment_methods = $wpdb->get_results( $sql_paymentmethod );
	?>
	<script>
  jQuery(function() {
    jQuery( "#dtpfromdate" ).datepicker({ dateFormat: "yy-mm-dd" });
		jQuery( "#dtptodate" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
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
			//alert($term_id);
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
            jQuery('#hdnbookingid').val(booking['booking_id']);
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
  </script>
  
  <style type="text/css">
		.bookinglavel{
			width:16%;
		}
		.bookinginput{
			width:75%;
		}
		#namediv input {
			width: 60%!important;
		}
	</style>
  <?php $current_user = wp_get_current_user();
	?>	  
  <div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2>Hotel Booking</h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:69%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Add Booking</h3>
        <form id="frmbooking" action="" method="post">
          <table style="margin:10px;">
          	<tr>
            	<td class="bookinglavel">Room Type</td>
              <td class="bookinginput">
              	<select id="roomtype" name="roomtype">
                  <?php foreach($taxonomies as $taxo){?>
                  <option value="<?php echo $taxo->term_id;?>"><?php echo $taxo->name;?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">Room</td>
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
              	From Date:
              </td>
              <td class="bookinginput">
              	<input type="text" id="dtpfromdate" name="dtpfromdate" value="" style="width:230px;" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	To Date:
              </td>
              <td class="bookinginput">
              	<input type="text" id="dtptodate" name="dtptodate" value="" style="width:230px;" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	First Name:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtFirstName" name="txtFirstName" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Last Name:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtLastName" name="txtLastName" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Email:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtEmail" name="txtEmail" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Phone:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtPhone" name="txtPhone" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Details:
              </td>
              <td class="bookinginput">
              	<textarea cols="36" rows="10" id="details" name="details"></textarea>
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Booking/Reservation By:
              </td>
              <td class="bookinginput">
              	<input type="text" readonly="readonly" id="txtbookingby" name="txtbookingby" value="<?php echo $current_user->display_name; ?>" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Guest Type:
              </td>
              <td class="bookinginput">
              	<!--<input type="text" id="txtGuestType" name="txtGuestType" />-->
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
              	Price:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtCustomPrice" name="txtCustomPrice" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Paid:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtPaid" name="txtPaid" onkeyup="ccb_calculate_due()" value="" />
              </td>
            </tr>
            <tr>
            	<td class="bookinglavel">
              	Due:
              </td>
              <td class="bookinginput">
              	<input type="text" id="txtDue" name="txtDue" value="" />
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
            <tr>
            	<td></td>
              <td>
              <input type="submit" id="btnaddbooking" name="btnaddbooking" value="Add Booking" style="width:150px;"/>
              <input type="hidden" id="hdnbookingid" name="hdnbookingid" value="" style="width:150px;"/>
              </td>
            </tr>
          </table>
          </form>
          
    	</div>
      </div>
    </div>
   </div>
  </div>