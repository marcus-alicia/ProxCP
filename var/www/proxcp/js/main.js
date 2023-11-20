$(document).ready(function() {
	// Custom tab script
    $('.tab-action').click(function() {
        var tabData = $(this).data('tab-cnt');
        // Content
        $('.tab-cnt').removeClass('active');
        $('#' + tabData).css('display','none').toggleClass('active');
        // Actions
        $('.tab-action').removeClass('active');
        $(this).toggleClass('active');
    });
});
