<?php
	global $table_prefix,$wpdb;
	$sql = "select * from ".$table_prefix."ccb_scbooking";
	$bookings = $wpdb->get_results($sql);
?>
<script type="text/javascript">
		jQuery(document).ready(function(){
				//============================= Pagination Script=====================================
				ccb_load_moredeals_data(1);
				/*----------------More Deals------------------*/
				function ccb_load_moredeals_data(page){
						ccb_loading_show();                    
						jQuery.ajax
						({
								type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' );?>',
								data: {
                  action: 'ccb_load_managebooking_data',
                  page: page
                },
								success: function(msg)
								{
								}
						}).done(function(msg){
                ccb_loading_hide();
                jQuery("#inner_content").html(msg);
            });
				
				}
				/*---------------------------------------------*/
				function ccb_loading_show(){
						jQuery('#loading').html("<img src='<?php echo CCB_SCBOOKING_PLUGIN_URL; ?>/images/loading.gif'/>").fadeIn('fast');
				}
				function ccb_loading_hide(){
						jQuery('#loading').fadeOut('fast');
				}                
				jQuery('#inner_content').delegate('.pagination li.active','click',function(){
						var page = jQuery(this).attr('p');
						ccb_load_moredeals_data(page);
						jQuery('html, body').animate({
								scrollTop: jQuery("#content_top").offset().top
						}, 1950);
						
				});           
				jQuery('#inner_content').delegate('#go_btn','click',function(){
						var page = parseInt(jQuery('.goto').val());
						var no_of_pages = parseInt(jQuery('.total').attr('a'));
						if(page != 0 && page <= no_of_pages){
								ccb_load_moredeals_data(page);
								jQuery('html, body').animate({
										scrollTop: jQuery("#content_top").offset().top
								}, 2050);
						}else{
								alert('Enter a PAGE between 1 and '+no_of_pages);
								jQuery('.goto').val("").focus();
								return false;
						}
						
				});
				//=========================== End pagination Script=====================================
				jQuery('#inner_content').delegate('#lnkapprove','click',function(e){
					e.preventDefault();
					var bookingid = jQuery(this).parent().children('#hdnbookingid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'ccb_activate_booking',
                booking_id:bookingid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('Booking Activated');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
					
				});	
				
				jQuery('#inner_content').delegate('#delete_booking','click',function(e){
					e.preventDefault();
          if(!confirm('Are you sure you want to delete')){
            return false;
          }
					var bookingid = jQuery(this).parent().children('#hdnbookingid').val();
					jQuery.ajax({
							type: "POST",
              url: '<?php echo admin_url( 'admin-ajax.php' );?>',
							data: {
                action: 'ccb_delete_booking',
                booking_id:bookingid
              },
							success: function (data) {
									var count = data.length;
									if(count>0){
										alert('Booking Deleted');
									}
							},
							error : function(s , i , error){
									console.log(error);
							}
					});
					console.log(jQuery(this).parent().parent().remove());
				});
					
		});
	</script>
	<div class="wrapper">
  <div class="wrap" style="float:left; width:100%;">
    <div id="icon-options-general" class="icon32"><br />
    </div>
    <h2>Hotel Booking</h2>
    <div class="main_div">
     	<div class="metabox-holder" style="width:80%; float:left;">
        <div id="namediv" class="stuffbox" style="width:99%;">
        <h3 class="top_bar">Manage Booking</h3>
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
            <?php
            foreach($bookings as $booking){
            ?>
            <tr class="alternate">
                <td><?php echo $booking->room;?></td>
                <td><?php echo $booking->from_date;?></td>
                <td><?php echo $booking->to_date;?></td>
                <td><?php echo $booking->email;?></td>
                <td><?php echo $booking->phone;?></td>
                
                <td>
                  <a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=ccb_custom_booking&page=add-hotel-booking-menu&calltype=editbooking&id=<?php echo $booking->booking_id;?>">edit</a>|
                  <a style="cursor: pointer;" id="delete_booking">delete</a>
                  <input type="hidden" id="hdnbookingid"  name="hdnbookingid" value="<?php echo $booking->booking_id;?>" />
                </td>
            </tr>
            <?php
            }
            ?>
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
  </div>
  <div id='loading'></div>