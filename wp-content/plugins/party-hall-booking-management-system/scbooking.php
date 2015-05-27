<?php
/*
Plugin Name: Party Hall Booking Manager
Plugin URI: http://www.codeinterest.com/
Description: A Custom wordpress Booking plugin to manage Party Hall Booking and scheduling
Version: 1.1 
Author: wpproducts
Author URI: http://www.solvercircle.com
*/
define('CCB_SCBOOKING_PLUGIN_URL', plugins_url('',__FILE__));
define("CCB_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));
include_once('includes/calendar_shortcode.php');
include_once('includes/managebooking_shortcode.php');
include_once('includes/create_page.php');

function ccb_create_custom_post_type() {
	register_post_type( 'ccb_custom_booking',
		array(
			'labels' => array(
				'name' => __( 'Rooms' ),
				'singular_name' => __( 'Room' ),
				'menu_name'=>__('Party Hall Booking Manager'),
				'all_items'=>__('Rooms'),
				'add_new_item'=>__('Add New Room'),
				'add_new'=> __('Add New Room'),
				'not_found'=>__('No rooms found.'),
				'search_items'=>__('Search Rooms'),
				'edit_item'=>__('Edit Room'),
				'view_item'=>__('View Room'),
				'not_found_in_trash'=>__('No Rooms found in trash')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'custom_bookings'),
      'supports' => array('title','thumbnail')
		)
	);
}

add_action( 'init', 'ccb_create_book_taxonomy' );

function ccb_create_book_taxonomy() {
	register_taxonomy(
		'custom_cat',
		'ccb_custom_booking',
		array(
			'label' => __( 'Category' ),
			'rewrite' => array( 'slug' => 'custom_cat' ),
			'hierarchical' => true,
		)
	);
}

function  ccb_add_metabox_for_room(){
add_meta_box(
		'room_attribute_metabox', // ID, should be a string
		'Room Attribute Settings', // Meta Box Title
		'ccb_room_meta_box_content', // Your call back function, this is where your form field will go
		'ccb_custom_booking', // The post type you want this to edit screen section (�post�, �page�, �dashboard�, �link�, �attachment� or �custom_post_type� where custom_post_type is the custom post type slug)
		'normal', // The placement of your meta box, can be �normal�, �advanced�or side
		'high' // The priority in which this will be displayed
		);
}
function ccb_room_meta_box_content($post){
$room_roomtype = get_post_meta($post->ID, '_room_roomtype', true);
$room_noofbed = get_post_meta($post->ID, '_room_noofbed', true);
$room_bathroom = get_post_meta($post->ID, '_room_bathroom', true);
$room_price = get_post_meta($post->ID, '_room_price', true);
$room_capacity = get_post_meta($post->ID, '_room_capacity', true);
$room_description = get_post_meta($post->ID, '_room_description', true);
?>
<table >
  <tbody>
    <tr>
      <th scope="row">Room Type:</th>
      <td>
      	<select id="roomtype" name="roomtype">
        	<option value="private" <?php if($room_roomtype=='private') echo 'selected'; ?> >Private</option>
          <option value="dorm" <?php if($room_roomtype=='dorm') echo 'selected'; ?> >Dorm</option>
        </select>
      </td>
    </tr>
    <tr>
    	<th scope="row">No of Bed:</th>
      <td><input type="text" name="roommetabox_noofbed" id="roommetabox_noofbed" value="<?php if(isset($room_noofbed)) echo $room_noofbed;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Bath Room:</th>
    	<td>
      	<select id="bathroom" name="bathroom">
        	<option value="insuite" <?php if($room_bathroom == 'insuite') echo 'selected'; ?> >Insuite</option>
          <option value="shared" <?php if($room_bathroom == 'shared') echo 'selected'; ?> >Shared</option>
        </select>
      </td>
    </tr>
    <tr>
    	<th scope="row">Price:</th>
      <td><input type="text" name="roommetabox_price" id="roommetabox_price" value="<?php if(isset($room_price)) echo $room_price;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Capacity:</th>
      <td><input type="text" name="roommetabox_capacity" id="roommetabox_capacity" value="<?php if(isset($room_capacity)) echo $room_capacity;?>" style="width:300px;" /></td>
    </tr>
    <tr>
    	<th scope="row">Description:</th>
      <td>
      <textarea name="roommetabox_Description" id="roommetabox_Description" rows="8" cols="50"><?php if(isset($room_description)) echo $room_description;?>
      </textarea>
      </td>
    </tr>
  </tbody>
</table>
<?php
}
function ccb_save_room_metabox(){
	global $post;
	// Get our form field
	if( $_POST ) :
		$room_roomtype = esc_attr( $_POST['roomtype'] );
		$room_noofbed = esc_attr( $_POST['roommetabox_noofbed'] );
		$room_bathroom = esc_attr( $_POST['bathroom'] );
		$room_price = esc_attr( $_POST['roommetabox_price'] );
		$room_capacity = esc_attr( $_POST['roommetabox_capacity'] );
		$room_description = esc_attr( $_POST['roommetabox_Description'] );
		// Update post meta
		update_post_meta($post->ID, '_room_roomtype', $room_roomtype);
		update_post_meta($post->ID, '_room_noofbed', $room_noofbed);
		update_post_meta($post->ID, '_room_bathroom', $room_bathroom);
		update_post_meta($post->ID, '_room_price', $room_price);
		update_post_meta($post->ID, '_room_capacity', $room_capacity);
		update_post_meta($post->ID, '_room_description', $room_description);
	endif;
}

add_action( 'save_post', 'ccb_save_room_metabox' );
add_action('add_meta_boxes','ccb_add_metabox_for_room');
/*---------------------*/
function ccb_custom_manage_booking_menu(){
    add_submenu_page( 'edit.php?post_type=ccb_custom_booking', 'Manage Booking', 'Manage Booking', 'manage_options', 'manage-booking-menu', 'ccb_manage_booking_settings');
}

function ccb_custom_add_booking_menu(){
    add_submenu_page( 'edit.php?post_type=ccb_custom_booking', 'Add Booking', 'Add Booking', 'manage_options', 'add-hotel-booking-menu', 'ccb_add_booking_settings' );
}
function ccb_booking_calendar_menu(){
    add_submenu_page( 'edit.php?post_type=ccb_custom_booking', 'Booking Calendar', 'Booking Calendar', 'manage_options', 'booking-calendar-menu', 'ccb_booking_calendar' );
}
function ccb_cssfix_front(){
    add_submenu_page( 'edit.php?post_type=ccb_custom_booking', 'FrontEnd CSS Fix', 'FrontEnd CSS Fix', 'manage_options', 'css-fix-menu', 'ccb_cssfix_front_setting' );
}
function ccb_pro_version(){
	add_submenu_page( 'edit.php?post_type=ccb_custom_booking', 'Booking Pro Version', 'BOOKING PRO VERSION', 'manage_options', 'booking-pro-version', 'ccb_booking_pro_version_setting' );
}
//-------------Booking Settings-----------------------
function ccb_scbooking_get_opt_val($opt_name,$default_val){
		if(get_option($opt_name)!=''){
			return $value = get_option($opt_name);
		}else{
			return $value =$default_val;
		}
}

function ccb_booking_calendar(){
	//include_once('includes/add_booking.php');
	include_once('calendar.php');
}

function ccb_manage_booking_settings(){
	include_once('includes/managebooking_backend.php');
}
function ccb_add_booking_settings(){
	include_once('includes/addbooking_backend.php');
} 
function ccb_cssfix_front_setting(){
	include_once('includes/add_cssfix_front.php');	
}
function ccb_booking_pro_version_setting(){
  include_once('includes/booking_pro_version.php');
}

add_action( 'admin_menu', 'ccb_custom_add_booking_menu' );
add_action( 'admin_menu', 'ccb_custom_manage_booking_menu');
add_action( 'admin_menu', 'ccb_booking_calendar_menu' );
add_action('admin_menu','ccb_cssfix_front');
add_action('admin_menu','ccb_pro_version');
/*---------------------*/

include_once('operations/scbooking_init.php');

function ccb_booking_uninstall(){
}

register_activation_hook( __FILE__, 'ccb_scbooking_install' );
register_deactivation_hook( __FILE__, 'ccb_booking_uninstall' );
add_action( 'init', 'ccb_create_custom_post_type' );
//=================== AJAX Calls ===============================
function ccb_get_room_bycat(){
  if($_REQUEST){
    global $table_prefix,$wpdb;
    $term_id = $_REQUEST['term_id'];
    $sql_room = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id=tr.object_id inner join wp_postmeta pm on pm.post_id= p.id where p.post_status = 'publish' and tt.term_id=".$term_id." and pm.meta_key='_room_price'";
    $result = $wpdb->get_results($sql_room);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_get_room_bycat','ccb_get_room_bycat' );
add_action( 'wp_ajax_ccb_get_room_bycat', 'ccb_get_room_bycat' );
function ccb_get_roomprice_by_custompost(){
  if($_REQUEST){
    global $table_prefix,$wpdb;
    $post_id = $_REQUEST['post_id'];

    $sql_room_price = "select * from ".$table_prefix."postmeta where meta_key='_room_price' and post_id=".$post_id;
    $result = $wpdb->get_results($sql_room_price);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_get_roomprice_by_custompost','ccb_get_roomprice_by_custompost' );
add_action( 'wp_ajax_ccb_get_roomprice_by_custompost', 'ccb_get_roomprice_by_custompost' );
function ccb_get_bookings(){
  if($_REQUEST){
    global $table_prefix,$wpdb;
    $booking_id = $_REQUEST['booking_id'];
    $sql = "select * from ".$table_prefix."ccb_scbooking where booking_id=".$booking_id;
    $result = $wpdb->get_results($sql);
    echo json_encode($result);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_get_bookings','ccb_get_bookings' );
add_action( 'wp_ajax_ccb_get_bookings', 'ccb_get_bookings' );
function ccb_save_booking(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;

    $hdnbookingid = $_REQUEST['hdnbookingid'];
    $room_type = $_REQUEST['room_type'];
    $roomid = $_REQUEST['roomid'];
    $room = $_REQUEST['room'];
    $shift = $_REQUEST['booking_shift'];

    $from_date = $_REQUEST['from_date'];

    $to_date = $_REQUEST['to_date'];
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $details = $_REQUEST['details'];
    $bookingby = $_REQUEST['bookingby'];
    $guest_type = $_REQUEST['guest_type'];
    $price = $_REQUEST['price'];
    $paid = $_REQUEST['paid'];
    $due = $_REQUEST['due'];
    $payment_method = $_REQUEST['payment_method'];
    $tracking_no = $_REQUEST['tracking_no'];

    $values = array(
      'room_type'=>$room_type,
      'room_id'=>$roomid,
      'room'=>$room,
      'shift'=>$shift,
      'from_date'=>$from_date, 
      'to_date'=>$to_date, 
      'first_name'=>$first_name, 
      'last_name'=>$last_name, 
      'email'=>$email, 
      'phone'=>$phone, 
      'details'=>$details, 
      'booking_by'=>$bookingby, 
      'guest_type'=>$guest_type, 
      'custom_price'=>$price, 
      'paid'=>$paid, 
      'due'=>$due,
      'payment_method'=>$payment_method,
      'tracking_no'=> $tracking_no
    );
    if($hdnbookingid == "" || $hdnbookingid == NULL){
      $wpdb->insert($table_prefix.'ccb_scbooking',$values );	
      $inserted_id = $wpdb->insert_id;
      echo $inserted_id;
    }
    else{
      $wpdb->update(
         $table_prefix.'ccb_scbooking',
         $values,
         array('booking_id' =>$hdnbookingid)
       );
       echo $hdnbookingid;
    }

  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_save_booking','ccb_save_booking' );
add_action( 'wp_ajax_ccb_save_booking', 'ccb_save_booking' );
function ccb_save_cssfixfront(){
    if ( count($_POST) > 0 ){ 
      $cssfix = $_REQUEST['cssfix'];
      $css = $_REQUEST['css'];
      $isupdate ="";
      if($cssfix == "front"){
        $isupdate = update_option('cssfix_front',$css);
      }
      if($isupdate){
        echo "added";
      }
    }
    exit;
}
add_action( 'wp_ajax_nopriv_ccb_save_cssfixfront','ccb_save_cssfixfront' );
add_action( 'wp_ajax_ccb_save_cssfixfront', 'ccb_save_cssfixfront' );
function ccb_load_managebooking_data(){
  if($_POST['page'])
  {
    $page = $_POST['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 15;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    global $table_prefix,$wpdb;
    $sql = "select * from ".$table_prefix."ccb_scbooking ";
    $result_count = $wpdb->get_results($sql);
    $count = count($result_count);
    $sql = $sql.' LIMIT '.$start.', '.$per_page.'';
    $result_page_data = $wpdb->get_results($sql); 
    $msg = "<style type='text/css'>
        /*-----paginations------*/
        #loading{
            width: 50px;
            position: absolute;
            height:50px;
        }
        #inner_content{
           padding: 0 20px 0 0!important;
        }
        #inner_content .pagination ul li.inactive,
        #inner_content .pagination ul li.inactive:hover{
            background-color:#ededed;
            color:#bababa;
            border:1px solid #bababa;
            cursor: default;
        }
        #inner_content .data ul li{
            list-style: none;
            font-family: verdana;
            margin: 5px 0 5px 0;
            color: #000;
            font-size: 13px;
        }

        #inner_content .pagination{
            width: 80%;/*800px;*/
            height: 45px;
        }
        #inner_content .pagination ul li{
            list-style: none;
            float: left;
            border: 1px solid #006699;
            padding: 2px 6px 2px 6px;
            margin: 0 3px 0 3px;
            font-family: arial;
            font-size: 14px;
            color: #006699;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        #inner_content .pagination ul li:hover{
            color: #fff;
            background-color: #006699;
            cursor: pointer;
        }
        .go_button
        {
          background-color:#f2f2f2;
          border:1px solid #006699;
          color:#cc0000;
          padding:2px 6px 2px 6px;
          cursor:pointer;
          position:absolute;
          margin-top:-1px;
          width:50px;
        }
        .total
        {
          float:right;
          font-family:arial;
          color:#999;
          padding-right:150px;
        }
        #namediv input {
          width:5%!important;
        }
        /*---------------------------------*/
      </style>";  
    $msg .= "<div id='content_top'></div>";
    if(count($result_page_data)){

          $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
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
                      <tr>';
                  foreach($result_page_data as $booking){
                    $msg .= '<tr class="alternate">
                                <td>'.$booking->room.'</td>
                                <td>'.$booking->from_date.'</td>
                                <td>'.$booking->to_date.'</td>
                                <td>'.$booking->email.'</td>
                                <td>'.$booking->phone.'</td>

                                <td>
                                  ';
                    $msg .= '<a href="'.site_url().'/wp-admin/edit.php?post_type=ccb_custom_booking&page=add-hotel-booking-menu&calltype=editbooking&id='.$booking->booking_id.'">edit</a>
                                  &nbsp;&nbsp;|&nbsp;&nbsp;<a style="cursor:pointer" id="delete_booking">delete</a>
                                  <input type="hidden" id="hdnbookingid"  name="hdnbookingid" value="'.$booking->booking_id.'" />
                                </td>
                            </tr>';
                  }
                  $msg .= '</tr>
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
                          </table>';	
      //}
    }
    else{
      $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
    }	
    $msg = "<div class='data'>" . $msg . "</div>"; // Content for Data

    $no_of_paginations = ceil($count / $per_page);

    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<div class='pagination'><ul>";

    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active'>First</li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class='inactive'>First</li>";
    }

    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active'>Previous</li>";
    } else if ($previous_btn) {
        $msg .= "<li class='inactive'>Previous</li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
        else
            $msg .= "<li p='$i' class='active'>{$i}</li>";
    }

    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active'>Next</li>";
    } else if ($next_btn) {
        $msg .= "<li class='inactive'>Next</li>";
    }

    // TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
    }
    $goto = "<input type='text' class='goto' size='1' style='margin-top:-2px;margin-left:30px;height:24px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
    $total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
    $img_loading = "<span ><div id='loading'></div></span>";
    $msg = $msg . "" . $goto . $total_string . $img_loading . "</div></ul>";  // Content for pagination
    echo $msg;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_load_managebooking_data','ccb_load_managebooking_data' );
add_action( 'wp_ajax_ccb_load_managebooking_data', 'ccb_load_managebooking_data' );
function ccb_activate_booking(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $bookingid = $_REQUEST['booking_id'];	
    $values = array('confirmed'=>1);
    $wpdb->update(
          $table_prefix.'ccb_scbooking',
          $values,
          array('booking_id' =>$bookingid)
    );
    echo $bookingid;		 
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_activate_booking','ccb_activate_booking' );
add_action( 'wp_ajax_ccb_activate_booking', 'ccb_activate_booking' );

function ccb_delete_booking(){
  if ( count($_POST) > 0 ){ 
    global $table_prefix,$wpdb;
    $bookingid = $_REQUEST['booking_id'];	
    $aff_rows = $wpdb->query("delete from ".$table_prefix."ccb_scbooking where booking_id='".$bookingid."'");
    echo $aff_rows;		 
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_delete_booking','ccb_delete_booking' );
add_action( 'wp_ajax_ccb_delete_booking', 'ccb_delete_booking' );
function ccb_load_managebooking_data_front(){
  if($_POST['page'])
  {
    $page = $_POST['page'];
    $cur_page = $page;
    $page -= 1;
    $per_page = 15;
    $previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
    $start = $page * $per_page;

    global $table_prefix,$wpdb;
    $sql = "select * from ".$table_prefix."ccb_scbooking ";
    $result_count = $wpdb->get_results($sql);
    $count = count($result_count);
    $sql = $sql.' LIMIT '.$start.', '.$per_page.'';
    $result_page_data = $wpdb->get_results($sql); 
    $msg = "<style type='text/css'>
        /*-----paginations------*/
        #loading{
            width: 50px;
            position: absolute;
            height:50px;
        }
        #inner_content{
           padding: 0 20px 0 0!important;
        }
        #inner_content .pagination ul li.inactive,
        #inner_content .pagination ul li.inactive:hover{
            background-color:#ededed;
            color:#bababa;
            border:1px solid #bababa;
            cursor: default;
        }
        #inner_content .data ul li{
            list-style: none;
            font-family: verdana;
            margin: 5px 0 5px 0;
            color: #000;
            font-size: 13px;
        }

        #inner_content .pagination{
            width: 80%;/*800px;*/
            height: 45px;
        }
        #inner_content .pagination ul li{
            list-style: none;
            float: left;
            border: 1px solid #006699;
            padding: 2px 6px 2px 6px;
            margin: 0 3px 0 3px;
            font-family: arial;
            font-size: 14px;
            color: #006699;
            font-weight: bold;
            background-color: #f2f2f2;
        }
        #inner_content .pagination ul li:hover{
            color: #fff;
            background-color: #006699;
            cursor: pointer;
        }
        .go_button
        {
          background-color:#f2f2f2;
          border:1px solid #006699;
          color:#cc0000;
          padding:2px 6px 2px 6px;
          cursor:pointer;
          position:absolute;
          margin-top:-1px;
          width:50px;
        }
        .total
        {
          float:right;
          font-family:arial;
          color:#999;
          padding-right:150px;
        }
        #namediv input {
          width:5%!important;
        }
        /*---------------------------------*/
      </style>";  
    $msg .= "<div id='content_top'></div>";
    if(count($result_page_data)){

          $msg .= '<table class="wp-list-table widefat fixed bookmarks" cellspacing="0">
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
                      <tr>';
                  foreach($result_page_data as $booking){
                    $msg .= '<tr class="alternate">
                                <td>'.$booking->room.'</td>
                                <td>'.$booking->from_date.'</td>
                                <td>'.$booking->to_date.'</td>
                                <td>'.$booking->email.'</td>
                                <td>'.$booking->phone.'</td>

                                <td>
                                  ';
                    $msg .= '<a href="'.site_url().'/wp-admin/edit.php?post_type=ccb_custom_booking&page=add-hotel-booking-menu&calltype=editbooking&id='.$booking->booking_id.'">edit</a>
                                  &nbsp;&nbsp;|&nbsp;&nbsp;<a style="cursor:pointer" id="delete_booking">delete</a>
                                  <input type="hidden" id="hdnbookingid"  name="hdnbookingid" value="'.$booking->booking_id.'" />
                                </td>
                            </tr>';
                  }
                  $msg .= '</tr>
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
                          </table>';	
      //}
    }
    else{
      $msg .= '<div style="padding:80px;color:red;">Sorry! No Data Found!</div>';
    }	
    $msg = "<div class='data'>" . $msg . "</div>"; // Content for Data

    $no_of_paginations = ceil($count / $per_page);

    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }
    /* ----------------------------------------------------------------------------------------------------------- */
    $msg .= "<div class='pagination' style='margin-top:10px;'><ul>";

    // FOR ENABLING THE FIRST BUTTON
    if ($first_btn && $cur_page > 1) {
        $msg .= "<li p='1' class='active'>First</li>";
    } else if ($first_btn) {
        $msg .= "<li p='1' class='inactive'>First</li>";
    }

    // FOR ENABLING THE PREVIOUS BUTTON
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $msg .= "<li p='$pre' class='active'>Previous</li>";
    } else if ($previous_btn) {
        $msg .= "<li class='inactive'>Previous</li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {

        if ($cur_page == $i)
            $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
        else
            $msg .= "<li p='$i' class='active'>{$i}</li>";
    }

    // TO ENABLE THE NEXT BUTTON
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $msg .= "<li p='$nex' class='active'>Next</li>";
    } else if ($next_btn) {
        $msg .= "<li class='inactive'>Next</li>";
    }

    // TO ENABLE THE END BUTTON
    if ($last_btn && $cur_page < $no_of_paginations) {
        $msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
    } else if ($last_btn) {
        $msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
    }
    $goto = "<input type='text' class='goto' size='1' style='margin-top:-2px;margin-left:30px;height:24px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
    $total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
    $img_loading = "<span ><div id='loading'></div></span>";
    $msg = $msg . "" . $goto . $total_string . $img_loading . "</div></ul>";  // Content for pagination
    echo $msg;
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_load_managebooking_data_front','ccb_load_managebooking_data_front' );
add_action( 'wp_ajax_ccb_load_managebooking_data_front', 'ccb_load_managebooking_data_front' );
function ccb_export_booking(){
  if($_POST){
    $export_data = $_REQUEST['export_data'];
    $file_name = "booking_".uniqid().".csv";
    $file_path = CCB_SCBOOKING_PLUGIN_URL."/operations/".$file_name;
    $fp = fopen($file_path, 'w');
    fwrite($fp, $export_data);
    fclose($fp);
  }
  exit;
}
add_action( 'wp_ajax_nopriv_ccb_export_booking','ccb_export_booking' );
add_action( 'wp_ajax_ccb_export_booking', 'ccb_export_booking' );
//==============================================
function ccb_communitycenterbookingjs(){
    wp_register_script( 'jquery.bt.min',plugins_url('/tooltip/beautytips-master/jquery.bt.min.js',__FILE__));

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script( 'jquery.bt.min',"",array(),"",true);
		
}
function 	ccb_communitycenterbookingcss(){
    wp_register_style( 'jquery-ui',plugins_url('/assets/css/jquery/smoothness/jquery-ui.css',__FILE__));    
    wp_register_style( 'jquery.bt',plugins_url('/tooltip/beautytips-master/jquery.bt.css',__FILE__));
			
		wp_enqueue_style( 'jquery-ui');	
    wp_enqueue_style( 'jquery.bt');
		
			
}
add_action('admin_enqueue_scripts','ccb_communitycenterbookingjs');
add_action('admin_enqueue_scripts','ccb_communitycenterbookingcss');  

function ccb_communitycenterbookingjs_front(){
  wp_register_script( 'jquery.bt.min',plugins_url('/tooltip/beautytips-master/jquery.bt.min.js',__FILE__), array( 'jquery' ));
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core', "", array('jquery'),"", false);
  wp_enqueue_script('jquery-ui-datepicker', "", array('jquery'),"", false);
  wp_enqueue_script('jquery-ui-dialog', "", array('jquery'),"", false);
  wp_enqueue_script('jquery.bt.min');
}
function ccb_communitycenterbookingcss_front(){
  wp_register_style( 'jquery-ui',plugins_url('/assets/css/jquery/smoothness/jquery-ui.css',__FILE__));
  wp_register_style( 'jquery.bt',plugins_url('/tooltip/beautytips-master/jquery.bt.css',__FILE__));
  
  wp_enqueue_style( 'jquery-ui' );
  wp_enqueue_style( 'jquery.bt' );
  
}
function add_calendar_shortcodejs(){
  wp_register_script( 'calendar_shortcodejs',plugins_url('/assets/js/calendar_shortcode.js',__FILE__));
  wp_register_script( 'managebooking_shortcodejs',plugins_url('/assets/js/managebooking_shortcode.js',__FILE__));
  
  wp_enqueue_script('calendar_shortcodejs', "", array('jquery-ui-dialog'),"", true);
  wp_enqueue_script('managebooking_shortcodejs', "", array('jquery-ui-dialog'),"", true);
}
add_action('wp_footer', 'add_calendar_shortcodejs');

add_action('wp_enqueue_scripts','ccb_communitycenterbookingjs_front');
add_action('wp_enqueue_scripts','ccb_communitycenterbookingcss_front');