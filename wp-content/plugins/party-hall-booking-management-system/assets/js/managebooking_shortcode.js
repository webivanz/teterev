jQuery( "#addbooking_dialog" ).dialog({ 
    autoOpen: false,
    height: 590,
    width: 560,
    modal: true,
    buttons: {
      "Add Booking": function() {
          save_booking();
          jQuery( this ).dialog( "close" );
      },
      Cancel: function() {
        jQuery( this ).dialog( "close" );
      }
    },
    close: function() {
    } 
});