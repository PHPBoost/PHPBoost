    /* Push the body and the nav over by 285px over */
jQuery('.open-summary').on('click', function(f) {
    jQuery('.sandbox-summary').animate({left: '0px'}, 200);
    jQuery('body').animate({ left: '285px'}, 200);
	f.stopPropagation();
});
jQuery(document).on('click', function(f) {
    if (jQuery(f.target).is('.open-summary') === false) {
        jQuery('.sandbox-summary').animate({left: '-285px'}, 200);
        jQuery('body').animate({left: '0'}, 200);
    }
});

    /* Then push them back */
jQuery('.close-summary, .summary-link').on('click', function() {
    jQuery('.sandbox-summary').animate({left: '-285px'}, 200);
    jQuery('body').animate({left: '0'}, 200);
});

// smooth scroll
jQuery('.summary-link').on('click',function(){
	var targetId = jQuery(this).attr("href");

    history.pushState('', '', targetId);
	jQuery('html, body').animate({scrollTop:jQuery(targetId).offset().top}, 'slow');
	return false;
});

// mini module

jQuery('.sbx-toggle-btn').on('click',function(e){
	jQuery('#module-mini-sandbox').addClass('toggle');
	e.stopPropagation();
});
jQuery(document).on('click', function(e) {
    if (jQuery(e.target).is('.sbx-menu, .sbx-item-title, .item-2x, .item-2x a, .item-3x a, .submit, i, .item-form select') === false) {
      jQuery('#module-mini-sandbox').removeClass('toggle');
    }
});

jQuery('#sandbox-css a').on('click', function(){return false;});

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
