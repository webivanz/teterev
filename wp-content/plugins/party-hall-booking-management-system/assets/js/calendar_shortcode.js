jQuery(".tooltip").bt({
  contentSelector: "jQuery(this).attr('title')",
  shrinkToFit: true,
  padding: 10,
  fill:'#EAECF0',
  cornerRadius: 10,
  positions: ['right', 'left',  'bottom']
  });
jQuery( "#addbooking_dialog" ).dialog({ 
    autoOpen: false,
    height: 550,
    width: 560,
    modal: true,
    buttons: {
      "Add Booking": function() {
          ccb_save_booking();
          jQuery( this ).dialog( "close" );
      },
      Cancel: function() {
        jQuery( this ).dialog( "close" );
        ccb_cleardata();
      }
    },
    close: function() {
      ccb_cleardata();
    } 
});
function ccb_openpopup(cat_id,room_id,from_date){
  jQuery( "#addbooking_dialog" ).dialog( "open" );
  jQuery("#roomtype").val(cat_id);
  ccb_get_rooms_for_bookingcell(room_id);
  jQuery("#dtpfromdate").val(from_date);
}
function ccb_open_edit_popup(booking_id){
  jQuery( "#addbooking_dialog" ).dialog( "open" );
  ccb_setbooking_info(booking_id);
}