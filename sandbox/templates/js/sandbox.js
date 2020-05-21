// mini module
jQuery('#window-width').append(jQuery(window).innerWidth() + 'px');
setInterval(function() {
    jQuery('#window-width').empty();
    jQuery('#window-width').append(jQuery(window).innerWidth() + 'px');
}, 10);
jQuery('#window-height').append(jQuery(window).innerHeight() + 'px');
setInterval(function() {
    jQuery('#window-height').empty();
    jQuery('#window-height').append(jQuery(window).innerHeight() + 'px');
}, 10);
