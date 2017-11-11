// var main = function() {
  /* Push the body and the nav over by 285px over */
  jQuery('.open-summary').click(function() {
    jQuery('.sandbox-summary').animate({
      left: "0px"
    }, 200);

    jQuery('body').animate({
      left: "285px"
    }, 200);
  });

  /* Then push them back */
  jQuery('.close-summary, .summary-link').click(function() {
    jQuery('.sandbox-summary').animate({
      left: "-285px"
    }, 200);

    jQuery('body').animate({
      left: "0px"
    }, 200);
  });
// };

// smooth scroll
jQuery('.summary-link').click(function(){
	var the_id = jQuery(this).attr("href");

	jQuery('html, body').animate({
		scrollTop:jQuery(the_id).offset().top
	}, 'slow');
	return false;
});

// mini module
function openSandboxMenu(myid)
{
    jQuery('#' + myid).toggleClass('toggle');
}

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
