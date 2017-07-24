 /** Days to be disabled as an array */
var disableddates = [
	"5-1-2017",
	"11-1-2017",
	"12-25-2017",
	"5-1-2018",
	"11-1-2018",
	"12-25-2018",
	"5-1-2019",
	"11-1-2019",
	"12-25-2019",
	"5-1-2020",
	"11-1-2020",
	"12-25-2020",
	"5-1-2021",
	"11-1-2021",
	"12-25-2021",
	"5-1-2022",
	"11-1-2022",
	"12-25-2022"
];

var daySoldOut = {
	'7-25-2017' : 530,
	'7-27-2017' : 1007,
	'8-18-2017' : 1001
};
 
function disableSpecificDates(date)
{

	var m = date.getMonth();
	var d = date.getDate();
	var y = date.getFullYear();

	// First convert the date in to the mm-dd-yyyy format 
	// Take note that we will increment the month count by 1 
	var currentdate = (m + 1) + '-' + d + '-' + y ;

	// We will now check if the date belongs to disableddates array 
	for (var i = 0; i < disableddates.length; i++) {
		// Now check if the current date is in disabled dates array
		if ($.inArray(currentdate, disableddates) != -1 ) {
			return [false];
		} 
	}

	// In case a 1000 tickets have been sold, the date is disabled
	if (daySoldOut[currentdate] > 1000)
	{
		return [false];
	}

	// In case the date is not present in disabled array, we will now check if it is a Sunday 
	var day = date.getDay();
	return [day != 0];
}

function disableFullDayButton(date)
{
	if (date == $.datepicker.formatDate('mm-dd-yy', new Date()))
	{
		$('#fullDay').prop('disabled', true);
	}
	else
	{
		$('#fullDay').prop('disabled', false);
	}
}

jQuery(function($){

	$('#datepicker').datepicker({
		// Disable Sunday and specific days
		beforeShowDay: disableSpecificDates,
		// Disable fullDay button if 
		onSelect: disableFullDayButton,
		// Set date format to MySQL date format
		dateFormat: "mm-dd-yy",
		// Disable days before today
		minDate: 0,
		// Define hidden input to get the selected date
		altField: '#date'
	});

	// Change date content of date input on change of date selection in calendar
	$('#date').change(function(){
		$('#datepicker')('setDate', $(this).val());
	});
});