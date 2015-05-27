(function($){
    var params = birchschedule_view_appointments_edit;
    var locationsMap = params.locations_map;
    var servicesMap = params.services_map;
    var locationsOrder = params.locations_order;
    var locationsStaffMap = params.locations_staff_map;
    var staffOrder = params.staff_order;
    var locationsServicesMap = params.locations_services_map;
    var servicesStaffMap = params.services_staff_map;
    var servicesOrder = params.services_order;

    var namespace = birchpress.namespace;
    var defineFunction = birchpress.defineFunction;
    var addAction = birchpress.addAction;

    var ns = namespace('birchschedule.view.appointments.edit.clientlist.reschedule');

    defineFunction(ns, 'render', function(viewState) {
        birchschedule.view.appointments.edit.clientlist.render.fn.default(viewState);
        var clientId = viewState.clientId;
        if(viewState.view === 'reschedule') {
            var row = $('#birs_client_list_row_' + clientId);
            row.hide();

            var rescheduleRow = $('#birs_client_list_row_reschedule_' + clientId);
            var data = rescheduleRow.attr('data-reschedule-html');
            rescheduleRow.find('td').html(data);
            ns.initForm();
            rescheduleRow.show();
            birchpress.util.scrollTo(rescheduleRow);
        }
    });

    defineFunction(ns, 'setLocationOptions', function(){
        var options = birchschedule.model.getLocationOptions(locationsMap, locationsOrder);
        var html = birchschedule.view.getOptionsHtml(options);
        $('#birs_appointment_location').html(html);
    });

    defineFunction(ns, 'setServiceOptions', function(){
        var locationId = $('#birs_appointment_location').val();
        var options = birchschedule.model.getServiceOptions(locationsServicesMap, 
            locationId, servicesOrder);
        var html = birchschedule.view.getOptionsHtml(options);

        var serviceId = $('#birs_appointment_service').val();
        $('#birs_appointment_service').html(html);

        if(serviceId && _(options.order).has(serviceId)) {
            $('#birs_appointment_service').val(serviceId);
        }
    });

    defineFunction(ns, 'setStaffOptions', function(){
        var locationId = $('#birs_appointment_location').val();
        var serviceId = $('#birs_appointment_service').val();
        var options = birchschedule.model.getStaffOptions(locationsStaffMap, servicesStaffMap, 
            locationId, serviceId, staffOrder);
        var html = birchschedule.view.getOptionsHtml(options);

        var staffId = $('#birs_appointment_staff').val();
        $('#birs_appointment_staff').html(html);

        if(staffId && _(options.order).has(staffId)) {
            $('#birs_appointment_staff').val(staffId);
        }
    });

    defineFunction(ns, 'setLocationValue', function() {
        var appointmentLocationId = Number($('#birs_appointment_location')
            .attr('data-value'));
        if(appointmentLocationId) {
            $('#birs_appointment_location').val(appointmentLocationId);
        }
    });

    defineFunction(ns, 'setServiceValue', function(){
        var appointmentServiceId = Number($('#birs_appointment_service')
            .attr('data-value'));
        if(appointmentServiceId) {
            $('#birs_appointment_service').val(appointmentServiceId);
        }    
    });

    defineFunction(ns, 'setStaffValue', function(){
        var appointmentStaffId = Number($('#birs_appointment_staff')
            .attr('data-value'));
        if(appointmentStaffId) {
            $('#birs_appointment_staff').val(appointmentStaffId);
        }    
    });

    defineFunction(ns, 'ifOnlyShowAvailable', function() {
        return false;
    });

    defineFunction(ns, 'reloadTimeOptions', function(){

    });

    defineFunction(ns, 'initDatepicker', function(){
        var config = {
            ifOnlyShowAvailable: ns.ifOnlyShowAvailable
        };
        return birchschedule.view.initDatepicker(config);
    });

    defineFunction(ns, 'reschedule', function() {
        var ajaxUrl = birchschedule.model.getAjaxUrl();
        var i18nMessages = birchschedule.view.getI18nMessages();
        var postData = $('form').serialize();
        postData += '&' + $.param({
            action: 'birchschedule_view_appointments_edit_clientlist_reschedule'
        });
        $.post(ajaxUrl, postData, function(data, status, xhr){
            var result = birchschedule.model.parseAjaxResponse(data);
            if(result.errors) {
                birchschedule.view.showFormErrors(result.errors);
            } 
            else if(result.success) {
                var url = $.parseJSON(result.success.message).url;
                window.location = _.unescape(url);
            }
            $('#birs_appointment_reschedule').val(i18nMessages['Reschedule']);
            $('#birs_appointment_reschedule').prop('disabled', false);
        });
        $('#birs_appointment_reschedule').val(i18nMessages['Please wait...']);
        $('#birs_appointment_reschedule').prop('disabled', true);
    });

    defineFunction(ns, 'initForm', function() {
        birchschedule.view.initCountryStateField('birs_client_country', 'birs_client_state');
        var ajaxUrl = birchschedule.model.getAjaxUrl();

        ns.setLocationOptions();
        ns.setLocationValue();
        ns.setServiceOptions();
        ns.setServiceValue();
        ns.setStaffOptions();
        ns.setStaffValue();

        var datepicker = ns.initDatepicker();
        defineFunction(ns, 'refreshDatepicker', function(){
            birchschedule.view.refreshDatepicker(datepicker);
        });

        ns.reloadTimeOptions();

        $('#birs_appointment_location').change(function() {
            ns.setServiceOptions();
            ns.setStaffOptions();
            ns.refreshDatepicker();
            ns.reloadTimeOptions();
        });

        $('#birs_appointment_service').change(function(){
            ns.setStaffOptions();
            ns.refreshDatepicker();
            ns.reloadTimeOptions();
        });

        $('#birs_appointment_staff').change(function(){
            ns.refreshDatepicker();
            ns.reloadTimeOptions();
        });

        $('#birs_appointment_date').on('change', function(){
            ns.reloadTimeOptions();
        });
        $('#birs_appointment_reschedule_cancel').click(function(){
            birchschedule.view.appointments.edit.clientlist.setViewState({
                view: 'list'
            });
        });
        $('#birs_appointment_reschedule').click(function(){
        	ns.reschedule();
        });
    });

    defineFunction(ns, 'init', function() {
        birchschedule.view.appointments.edit.clientlist.render.fn.when('reschedule', ns.render);
    	$('.wp-list-table.birs_clients .row-actions .reschedule a').click(function(eventObject){
            var clientId = $(eventObject.target).attr('data-item-id');
            birchschedule.view.appointments.edit.clientlist.setViewState({
                view: 'reschedule',
                clientId: clientId
            });
    	});
    });

    addAction('birchschedule.initAfter', ns.init);

})(jQuery);