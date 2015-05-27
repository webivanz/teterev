<?php
function ccb_managescbooking_shortcode($atts){
		global $table_prefix,$wpdb;
		include_once('add_booking.php');
		$sql = "select * from ".$table_prefix."ccb_scbooking";
		$bookings = $wpdb->get_results($sql);
		$output = '<style type="text/css">
			button,input{
				padding:0px!important;
			}
		</style>';
		include_once('/../operations/get_cssfixfront.php');
		$output .='<script type="text/jscript">
			jQuery(document).ready(function(){
					 //============================= Pagination Script=====================================
						ccb_load_moredeals_data(1);
						/*----------------More Deals------------------*/
						function ccb_load_moredeals_data(page){
								ccb_loading_show();                    
								jQuery.ajax
								({
										type: "POST",
                    url: "'.admin_url( 'admin-ajax.php' ).'",  
										data: {
                      action: "ccb_load_managebooking_data_front",
                      page: page
                    },
										success: function(msg)
										{
												jQuery("#inner_content").ajaxComplete(function(event, request, settings)
												{
														ccb_loading_hide();
														jQuery("#inner_content").html(msg);
												});
										}
								});
						
						}
						/*---------------------------------------------*/
						function ccb_loading_show(){
								jQuery("#loading").html("<img src=\''.CCB_SCBOOKING_PLUGIN_URL.'/images/loading.gif\'/>").fadeIn(\'fast\');
						}
						function ccb_loading_hide(){
								jQuery(\'#loading\').fadeOut(\'fast\');
						}                
						jQuery(\'#inner_content\').delegate(\'.pagination li.active\',\'click\',function(){
								var page = jQuery(this).attr(\'p\');
								//loadData(page);
								ccb_load_moredeals_data(page);
								jQuery(\'html, body\').animate({
										scrollTop: jQuery("#content_top").offset().top
								}, 1950);
								
						});           
						jQuery(\'#inner_content\').delegate(\'#go_btn\',\'click\',function(){
								var page = parseInt(jQuery(\'.goto\').val());
								var no_of_pages = parseInt(jQuery(\'.total\').attr(\'a\'));
								if(page != 0 && page <= no_of_pages){
										//loadData(page);
										ccb_load_moredeals_data(page);
										jQuery(\'html, body\').animate({
												scrollTop: jQuery("#content_top").offset().top
										}, 2050);
								}else{
										alert(\'Enter a PAGE between 1 and \'+no_of_pages);
										jQuery(\'.goto\').val("").focus();
										return false;
								}
								
						});
						//=========================== End pagination Script=====================================	
					 jQuery("#inner_content").delegate("#lnkapprove","click",function(e){
						e.preventDefault();
						var bookingid = jQuery(this).parent().children("#hdnbookingid").val();
						jQuery.ajax({
								type: "POST",
                url: "'.admin_url( 'admin-ajax.php' ).'",  
								data: {
                  action: "ccb_activate_booking",
                  booking_id:bookingid
                },
								success: function (data) {
										var count = data.length;
										if(count>0){
											alert("Booking Activated");
										}
								},
								error : function(s , i , error){
										console.log(error);
								}
						});
						
					});	
					
					jQuery("#inner_content").delegate("#delete_booking","click",function(e){
						e.preventDefault();
            if(!confirm("Are you sure want to delete")){
              return false;
            }
						var bookingid = jQuery(this).parent().children("#hdnbookingid").val();
						
						jQuery.ajax({
								type: "POST",
								url: "'.admin_url( 'admin-ajax.php' ).'",   
								data: {
                  action: "ccb_delete_booking",
                  booking_id:bookingid
                },
								success: function (data) {
										var count = data.length;
										if(count>0){
											alert("Booking Deleted");
										}
								},
								error : function(s , i , error){
										console.log(error);
								}
						}).done(function(msg){
              jQuery(this).parent().parent().remove();
            });
						console.log(jQuery(this).parent().parent().remove());
					});
						
			});
			function ccb_open_edit_popup(booking_id){
				jQuery( "#addbooking_dialog" ).dialog( "open" );
				setbooking_info(booking_id);
			}
		</script>
		<div class="wrapper">
		<div class="wrap" style="float:left; width:100%;">
			 <div class="main_div">
				<div class="metabox-holder" style="width:100%; float:left;">
					<div id="namediv" class="stuffbox" style="width:99%;">
						<div id="inner_content">		
							<div class="data"></div>
							<div class="pagination"></div>
						<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
						<thead>
							<tr>
								<th>Room</th>
								<th>From Date</th>
								<th>To Date</th>
								<th>Email</th>
								<th>Phone</th>
								<th></th>
							</tr>
						</thead>
						<tr>
							';
						foreach($bookings as $booking){
								$output .= '<tr class="alternate">
									<td>'.$booking->room.'</td>
									<td>'.$booking->from_date.'</td>
									<td>'.$booking->to_date.'</td>
									<td>'.$booking->email.'</td>
									<td>'.$booking->phone.'</td>
									<td>';
									$output .='<a onclick="ccb_open_edit_popup('.$booking->booking_id.')" style="cursor:pointer;text-decoration:none;" >edit</a>
										&nbsp;&nbsp;&nbsp;<a style="cursor: pointer;" id="delete_booking">delete</a>
										<input type="hidden" id="hdnbookingid"  name="hdnbookingid" value="'.$booking->booking_id.'" />
										</td>
									</tr>
									';
							}
							
						 $output .= '
						</tr>
						<tfoot>
							<tr>
								<th>Room</th>
								<th>From Date</th>
								<th>To Date</th>
								<th>Email</th>
								<th>Phone</th>
								<th></th>
							</tr>
						</tfoot>
					</table>
					
					</div>
				</div>
			</div>
		 </div>
		</div>';	
		return $output;
}
add_shortcode('ccb_managescbooking','ccb_managescbooking_shortcode');