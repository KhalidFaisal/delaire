"use strict";
$(document).ready(function() {
	var tooltip_init = {
		init: function() {
			$("button").tooltip();
			$("a").tooltip();
			$("input").tooltip();
		}
	};
    tooltip_init.init();

    // Re-initialize Feather icons and clean up stray tooltips when DataTables redraws
    $(document).on('draw.dt', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        $('.tooltip').remove(); // Remove any floating orphaned tooltips left behind by DOM changes
    });
});

// Use delegated initialization for Bootstrap 5 tooltips to support dynamically added elements
if (typeof bootstrap !== 'undefined') {
    new bootstrap.Tooltip(document.body, {
        selector: '[data-bs-toggle="tooltip"]'
    });
}