<?php 
  global $export_data, $nl, $separator;
  global $table_prefix,$wpdb;
  $sql_rooms = "select * from ".$table_prefix."term_taxonomy tt inner join ".$table_prefix."term_relationships tr on tt.term_taxonomy_id = tr.term_taxonomy_id inner join ".$table_prefix."posts p on p.id = tr.object_id inner join wp_postmeta pm on pm.post_id = p.id where p.post_status = 'publish' and pm.meta_key = '_room_price'";
  $rooms = $wpdb->get_results($sql_rooms);
  $sql = "select * from ".$table_prefix."ccb_scbooking";
  $scbookings = $wpdb->get_results($sql);
  //
  define('CCB_PROCESSING_BG_COLOR','7FCA27') ;
  define('CCB_BOOKED_BG_COLOR','138219') ;
	
	function ccb_scbooking_get_opt_val_for_calendar($opt_name,$default_val){
			if(get_option($opt_name)!=''){
				return $value = get_option($opt_name);
			}else{
				return $value =$default_val;
			}
	}
  //
   $cDay = "";
	 $cMonth = "";
	 $cYear = ""; 
    if($_POST){
        $cDay = $_REQUEST['booking_day'];
        $cMonth = $_REQUEST["booking_month"];
        $cYear = $_REQUEST["booking_year"]; 
    }
    else{
        $cDay = "01";//date('d');
        $cMonth = date("n");
        $cYear = date("Y");
    }
		//$export_data = "";
		$nl = "\n";
		$separator = ",";
	?>
  <style type="text/css">
		#prev_month{
			background:url('<?php echo CCB_SCBOOKING_PLUGIN_URL ?>/images/prev.jpg') no-repeat; 
			width: 25px; 
			height: 30px; 
			cursor:pointer;
		}
		#next_month{
			background:url('<?php echo CCB_SCBOOKING_PLUGIN_URL ?>/images/next.jpg') no-repeat;
			width: 25px; 
			height: 30px; 
			cursor:pointer;
		}
		#btnbookings{
			background:url('<?php echo CCB_SCBOOKING_PLUGIN_URL ?>/images/search.png') no-repeat;
			width: 30px; 
			height: 30px; 
			cursor:pointer;
		}
		
	
	</style>
  <script type="text/javascript">
	 function ccb_export_csv(){
			var export_data = <?php $export_data; ?>
			console.log(export_data);
			jQuery.ajax({
					type: "POST",
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
					data: {
            action: 'ccb_export_booking',
            export_data: export_data 
          },
					//dataType:'json',
					success: function (data) {
						console.log(data);
					},
					error : function(s , i , error){
						console.log(error);
					}
					
			});
	 }
	 jQuery(document).ready(function(){
	 		jQuery('#lnkexport').on("click", function(e){
					ccb_export_csv();						
			});
	 });
	</script>
 <div id="icon-options-general" class="icon32">
  	<br>
  </div>
	<h2>Booking Calendar</h2>
  <div style="width:85%;">
  	<form id="frmgetbooking" method="post" >
    <table id="calendarhead">
    	<tr>
        <td>
        		<input type="hidden" name="booking_day" id="booking_day" value="1" />
        		<select id="booking_month" name="booking_month" >
              <option value="1" <?php if($cMonth==1) echo 'selected="selected"'?>>January</option>
              <option value="2" <?php if($cMonth==2) echo 'selected="selected"'?>>February</option>
              <option value="3" <?php if($cMonth==3) echo 'selected="selected"'?>>March</option>
              <option value="4" <?php if($cMonth==4) echo 'selected="selected"'?>>April</option>
              <option value="5" <?php if($cMonth==5) echo 'selected="selected"'?>>May</option>
              <option value="6" <?php if($cMonth==6) echo 'selected="selected"'?>>June</option>
              <option value="7" <?php if($cMonth==7) echo 'selected="selected"'?>>July</option>
              <option value="8" <?php if($cMonth==8) echo 'selected="selected"'?>>August</option>
              <option value="9" <?php if($cMonth==9) echo 'selected="selected"'?>>September</option>
              <option value="10" <?php if($cMonth==10) echo 'selected="selected"'?>>October</option>
              <option value="11" <?php if($cMonth==11) echo 'selected="selected"'?>>November</option>
              <option value="12" <?php if($cMonth==12) echo 'selected="selected"'?>>December</option>
            </select>
    		</td>
        <td>
        		<select id="booking_year" name="booking_year" >
              <option value="2013" <?php if($cYear==2013) echo 'selected="selected"'?>>2013</option>
              <option value="2014" <?php if($cYear==2014) echo 'selected="selected"'?>>2014</option>
              <option value="2015" <?php if($cYear==2015) echo 'selected="selected"'?>>2015</option>
              <option value="2016" <?php if($cYear==2016) echo 'selected="selected"'?>>2016</option>
              <option value="2017" <?php if($cYear==2017) echo 'selected="selected"'?>>2017</option>
              <option value="2018" <?php if($cYear==2018) echo 'selected="selected"'?>>2018</option>
              <option value="2019" <?php if($cYear==2019) echo 'selected="selected"'?>>2019</option>
              <option value="2020" <?php if($cYear==2020) echo 'selected="selected"'?>>2020</option>
            </select>
    		</td>
        <td><input type="submit" id="btnbookings" name="btnbookings" value="" /></td>
      </tr>
    </table>
   </form> 
  </div>
 
<style type="text/css">
      table tr td{
        border:solid 1px #E2E2E2;
				height: 60px;
				/*width:30px;*/
      }
      table#calendarhead tr td{
        border:none!important;
        height: 30px;
      }
			table .header_cell{
				width:65px;
			}
			table#tblcalendarbodyin{
				border-spacing:0px;	
				border-collapse:collapse;
        font-size: 10px;  
			}
			.com_name {
				/*padding: 0 .5em;*/
				writing-mode: tb-rl;
				font-weight:bold;
				height:100%;
				-webkit-transform:rotate(-90deg);
				-moz-transform:rotate(-90deg);
				-o-transform: rotate(-90deg);
				font-size:14px;
				/*filter: flipv fliph;
				-webkit-transform:rotate(-90deg); 
				white-space:nowrap; 
				display:block;*/
			}
			table#tblcalendarbody tr td{
				border-spacing:0px;	
				border-collapse:collapse;
			}
			table#tblcalendarbodyin tr td{
				width: 65px;
			}
      </style>
      <?php
      $monthNames = Array("January", "February", "March", "April", "May", "June", "July","August", "September", "October", "November", "December");
      $weekNames = Array("SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT");
      			
      $next_day = getDate(mktime(0,0,0,$cMonth,$cDay+1,$cYear));
      $prev_day = getDate(mktime(0,0,0,$cMonth,$cDay-1,$cYear));
       
      $next_month = getDate(mktime(0,0,0,$cMonth+1,$cDay,$cYear));
      $prev_month = getDate(mktime(0,0,0,$cMonth-1,$cDay,$cYear));
       
      $next_year = getDate(mktime(0,0,0,$cMonth,$cDay,$cYear+1));
      $prev_year = getDate(mktime(0,0,0,$cMonth,$cDay,$cYear-1));
      ?>

      <?php
      $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
      $maxday = date("t",$timestamp);             // visszaadja, hogy az adott h�napban mennyi nap van.
      $thismonth = getdate ($timestamp);              //  t�mbben visszakapjuk az ido �rt�keket
      $startday = $thismonth['wday']; // 'wday' = day of the week - sz�mban adja vissza a napot. pl. h�tfo=1
			
			//======================
			$fromdate = $cYear.'-'.$cMonth.'-'.$cDay;
			$todate = $cYear.'-'.$cMonth.'-'.$maxday;
			$sqlmonth =  "select * from ".$table_prefix."ccb_scbooking where  from_date>='".$fromdate."' and to_date<='".$todate."'";
			$month_result = $wpdb->get_results($sqlmonth);
			//============================================
			function ccb_searchForDay($day, $array) {
				 foreach ($array as $key => $val) {
						 if ($val['day'] === $day) {
								 //return $key;
								 return $val;
						 }
				 }
				 return null;
			}
			function ccb_searchForDue($day, $array) {
				 foreach ($array as $key => $val) {
						 if ($val['dueDay'] === $day) {
								 //return $key;
								 return $val;
						 }
				 }
				 return null;
			}
			function ccb_searchForBookingID($day, $array) {
				 foreach ($array as $key => $val) {
						 if ($val['day'] === $day) {
								 //return $key;
								 return $val['booking_id'];
						 }
				 }
				 return null;
			}
			function ccb_searchForBookingRange($day, $array) {
				 foreach ($array as $key => $val) {
						 if ($val['day'] === $day) {
								 //return $key;
								 return $val['booking_range'];
						 }
				 }
				 return null;
			}
			function ccb_COTTAGE($MAX,$room,$fromdate,$todate,$cYear,$cMonth,$shift){
					//
					global $table_prefix,$wpdb,$export_data, $nl, $separator ;

					/* PROCESSING BG COLOR */
					$processing_bg_color = ccb_scbooking_get_opt_val_for_calendar('_processing_bg_color',CCB_PROCESSING_BG_COLOR); 
					/* BOOKED BG COLOR */
					$booked_bg_color = ccb_scbooking_get_opt_val_for_calendar('_booked_bg_color',CCB_BOOKED_BG_COLOR); 
					
					$sqlmonth =  "select * from ".$table_prefix."ccb_scbooking where  from_date>='".$fromdate."' and to_date<='".$todate."' and room='".$room->post_title."' and shift='".$shift."'";
					$month_room_booking_result = $wpdb->get_results($sqlmonth);
					
					$room_month_booked_days_2d = array();
					$room_month_booked_days = array();
					
					$room_month_booking_due_2d = array();
					$room_month_booking_due = array();
					
					$count = 0;
					$outer_count = 0;
					$count_due = 0;
					foreach($month_room_booking_result as $mrr){
							$fromdate_timestmp = strtotime("".$mrr->from_date."");
							$fromday = date("j",$fromdate_timestmp);
							$fromday = ($fromday - 1);		
							$todate_timestmp = strtotime($mrr->to_date);
							$today = date("j",$todate_timestmp);
							$bookingrange = ($today - $fromday);
							for($j=0;$j<$bookingrange;$j++){
									$fromday++;
									$room_month_booked_days['day'] = $fromday;
									$room_month_booked_days['booking_id'] = $mrr->booking_id;
									$room_month_booked_days['booking_range'] = $bookingrange;
									//
									$room_month_booked_days['first_name'] = $mrr->first_name;
									$room_month_booked_days['last_name'] = $mrr->last_name;
									$room_month_booked_days['email'] = $mrr->email;
									$room_month_booked_days['phone'] = $mrr->phone;
									$room_month_booked_days['booking_by'] = $mrr->booking_by;
									$room_month_booked_days['price'] = $mrr->custom_price;
                  
                  $room_month_booked_days['payment_method'] = $mrr->payment_method;
                  $room_month_booked_days['from_date'] = $mrr->from_date;
                  $room_month_booked_days['to_date'] = $mrr->to_date;
									//
									$room_month_booked_days_2d[$count] = 	$room_month_booked_days;
								  $count++;	
									
									if($mrr->due > 0){
										//----------new-----------
										$room_month_booking_due['dueDay'] = $fromday;
										$room_month_booking_due['booking_id'] = $mrr->booking_id;
										//----------new-----------
										$room_month_booking_due_2d[$count_due] = $room_month_booking_due;
										$count_due++;
									}
							}
					}
					 
					$shtml = '';
					for ($i=1; $i<$MAX+1; $i++) {
					
						$day = ccb_searchForDay($i,$room_month_booked_days_2d);
						$due = ccb_searchForDue($i,$room_month_booking_due_2d);
						
						if ( ($day!=NULL || $day!="") && ($due!=NULL || $due !="")){
							$booking_id = ccb_searchForBookingID($i,$room_month_booked_days_2d);
							//---------colspan-----------
							$colspan = ccb_searchForBookingRange($i,$room_month_booked_days_2d);
							$val = ccb_searchForDay($i,$room_month_booked_days_2d);
							$i = $i + ($colspan-1);
							//---------------------------
              $paymentmethod="";$trackingno = 0;
              if(isset($val['payment_method'])){ 
                $paymentmethod = $val['payment_method'];
              }
              if(isset($val['tracking_no'])){
                $trackingno = $val['tracking_no'];
              }
							$shtml .= '<td style="color:black;background-color:#'.$processing_bg_color.';text-align:center;" colspan="'.$colspan.'"><a target="_blank" href="'.site_url().'/wp-admin/edit.php?post_type=ccb_custom_booking&page=add-hotel-booking-menu&calltype=editbooking&id='.$booking_id.'" style="text-decoration:none;"><span class="tooltip" title="Booking Info:<br>&nbsp;&nbsp;Name: '.$val['first_name'].' '.$val['last_name'].'<br>&nbsp;&nbsp;Email: '.$val['email'].'<br>&nbsp;&nbsp;Phone: '.$val['phone'].'<br>&nbsp;&nbsp;Price: '.$val['price'].'<br>&nbsp;&nbsp;Payment Method:'.$paymentmethod.'<br>&nbsp;&nbsp;Tracking No:'.$trackingno.'<br>&nbsp;&nbsp;Booking By: '.$val['booking_by'].'"><img src="'.CCB_SCBOOKING_PLUGIN_URL.'/images/process_30.png" /></span></td>';
						}
						else if(($day!=NULL || $day!="")){
							$colspan = ccb_searchForBookingRange($i,$room_month_booked_days_2d);
							$val = ccb_searchForDay($i,$room_month_booked_days_2d);
							$i = $i + ($colspan-1);
              $paymentmethod="";$trackingno = 0;$bookingfrom="";$bookingto="";
              if(isset($val['payment_method'])){ 
                $paymentmethod = $val['payment_method'];
              }
              if(isset($val['tracking_no'])){
                $trackingno = $val['tracking_no'];
              }
              if(isset($val['from_date'])){
                $bookingfrom = $val['from_date'];
              }
              if(isset($val['to_date'])){
                $bookingto = $val['to_date'];
              }
							$shtml .= '<td style="color:black;background-color:#'.$booked_bg_color .';text-align:center;" colspan="'.$colspan.'"><span class="tooltip" title="Booking Info:<br>&nbsp;&nbsp;Name: '.$val['first_name'].' '.$val['last_name'].'<br>&nbsp;&nbsp;Email: '.$val['email'].'<br>&nbsp;&nbsp;Phone: '.$val['phone'].'<br>&nbsp;&nbsp;Price: '.$val['price'].'<br>&nbsp;&nbsp;Payment Method: '.$paymentmethod.'<br>&nbsp;&nbsp;From: '.$bookingfrom.'<br>&nbsp;&nbsp;To: '.$bookingto.'<br>&nbsp;&nbsp;Tracking No: '.$trackingno.'<br>&nbsp;&nbsp;Booking By: '.$val['booking_by'].'"><img src="'.CCB_SCBOOKING_PLUGIN_URL.'/images/booked.png" /></span></td>';
						}
						else{
							if($i<10){
									$daypass = '0'.$i;
								}
								else{
									$daypass = $i;
								}
								if($cMonth<10){
									$cMonthpass = "0".$cMonth;
								}
								else{
									$cMonthpass = $cMonth;
								}
							$current_cell_date = $cYear.'-'.$cMonthpass.'-'.$daypass;
							$shtml .= '<td id="'.$i.'|'.$room->post_title.'" style="background-color:#F4F4F4;text-align:center;"><a  target="_blank" href="'.site_url().'/wp-admin/edit.php?post_type=ccb_custom_booking&page=add-hotel-booking-menu&calendarcell='.$room->term_id.'|'.$room->ID.'|'.$current_cell_date.'" style="text-decoration:none;" ><img src="'.CCB_SCBOOKING_PLUGIN_URL.'/images/add.jpg"></img></a></td> ';
						}
					}
					return $shtml;
			}
			//=============================
			
      echo '<div style=""><table id="tblcalendarbody" cellspacing="0" cellpadding="0" ><tr style="background-color:#0099FF;"><td style="width:100px;padding-left:5px;font-size:10px;"><b>COMMUNITY <br> CENTER</b></td> <td><table id="tblcalendarbodyin"><tr>';
      for ($i=1; $i<$maxday+1; $i++) {
        $time = mktime(0,0,0,$cMonth,$i,$cYear);
        $cc_date = date('m-d-Y', $time);
        $t=date('d-m-Y',$time);
        $cc_day = date("D",strtotime($t));
        echo "<td class='header_cell' align='center' valign='middle' height='20px'><div>". $cc_day . '<br/>' . $i  ."</div></td>";
      }
			echo '</tr></table></td></tr>';
			foreach($rooms as $room){
				echo '<tr><td style="background-color:#E4E4E4;padding-left:5px;color:#26A4CE;width:100px;border-top:solid 1px #D0D0D0;"> <div class="com_name" style="float:left;width:50%;margin-top:40%;margin-left:10px;">'.$room->post_title.' </div><div style="float:left;width:30%;"><table cellspacing="0" cellpadding="0"> <tr><td>Day</td></tr> <tr><td>Night</td></tr></table></div> </td> <td><table id="tblcalendarbodyin"><tr>' . ccb_COTTAGE($maxday,$room,$fromdate,$todate,$cYear,$cMonth,'Day') . '</tr><tr>'.ccb_COTTAGE($maxday,$room,$fromdate,$todate,$cYear,$cMonth,'Night').'</tr></table></td> </tr>';
				
			}
     echo '</table></div>';
      ?>
      <script type="text/javascript">
            jQuery(".tooltip").bt({
                            contentSelector: "jQuery(this).attr('title')",
                            shrinkToFit: true,
                            padding: 10,
                            fill:'#EAECF0',
                            cornerRadius: 10,
                            positions: ['right', 'left',  'bottom']
            });

      </script>