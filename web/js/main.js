// ======================================================================
	// 
	// DATEPICKER MANAGER (ticket.html.twig)
	// 
// ======================================================================

jQuery(function($){

	// Ticket view datepicker
	$('#datepickerTicket').datepicker({
		// Make Datepicker pop-up at the top of the text field
		beforeShow: function (textbox, instance) {
	        var txtBoxOffset = $(this).offset();
	        var top = txtBoxOffset.top;
	        var left = txtBoxOffset.left;
	        var textBoxHeight = $(this).outerHeight();
	        setTimeout(function () {
	            instance.dpDiv.css({
	               top: top-$('#ui-datepicker-div').outerHeight(),
	               left: left
	            });
	        }, 0);
	    },
	    // Enable change month functionality
		changeMonth: true,
	    // Enable change year functionality
		changeYear: true,
		// Set year selection range to the last 100 years
		yearRange: '-100:+0',
		// Set view date format to French format
		altFormat: 'yy-mm-dd',
		altField: '#altDatepickerTicket',
		// Set date format to MySQL date format
		dateFormat: 'dd/mm/yy',
		// Disable days before today
		maxDate: 0,

	});


});