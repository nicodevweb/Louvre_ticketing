// ======================================================================
	// 
	// MULTI FORM MANAGER (ticket.html.twig)
	// 
// ======================================================================

$(document).ready(function(){

	// We get div tag containing "data-prototype" attribute
	var container = $('div#louvre_reservationbundle_reservation_tickets');

	// We define a sole counter to name fields which will be dynamically added
	var index = container.find(':input').length;

	// We add a new field on each add link click
	$('#addForm').click(function(e) {
		addTicket(container);

		e.preventDefault(); // Avoid seeing '#' in url
		return false;
	});

	// Automatically add first form if no one already exists
	if (index == 0)
	{
		addTicket(container);
	}
	else
	{
		// If tickets already exist, a supression link is added for each of them
		container.children('div').each(function() {
			addDeleteLink($(this));
		});
	}

	// Add a TicketType form function
	function addTicket(container)
	{
		// Set in "data-prototype" content :
		// Field label instead of "__name__label__"
		// Field number instead of "__name__"
		var template = container.attr('data-prototype')
			.replace(/__name__label__/g, 'Billet n°' + (index + 1))
			.replace(/__name__/g, index)
		;

		// A jQuery object is created to content this template
		var prototype = $(template);

		// Add a delete link to delete ticket
		addDeleteLink(prototype);

		// Add prototype in the end of container div tag
		container.append(prototype);

		// Index is incremented
		index++;
	}

	// Add a delete link in prototype function
	function addDeleteLink(prototype)
	{
		// Link creation
		var deleteLink = $('<div class="text-right"><a href="#" id="addForm" class="delete-ticket"><span class="glyphicon glyphicon-remove-sign"></span> Supprimer ce billet</a></div>');

		// On click link event to delete Ticket
		deleteLink.click(function(e) {
			prototype.remove();

			e.preventDefault(); // Avoid seeing '#' in url
			return false;
		});

		// Link is added to prototype
		prototype.prepend(deleteLink);
	}

});