jQuery(window).bind("load", function() {

    var $footer =  jQuery(".sticky-footer"),
        footerHeight = $footer.outerHeight(),
        backEnd = jQuery('#footer').parents('#global').length;

    if(backEnd)
    {
        positionAdminFooter();
        $(window).scroll(positionAdminFooter).resize(positionAdminFooter);
    }
    else
    {
        positionFooter();
        $(window).scroll(positionFooter).resize(positionFooter);
    }

    function positionFooter() {

        contentHeight = jQuery('#header').outerHeight() + jQuery('#global').outerHeight() + jQuery('#footer').outerHeight();
        footerTop = ($(window).height() - footerHeight)+"px";

        if (contentHeight < $(window).height()) {
            $footer.css({
                position: "absolute",
                top: footerTop,
                left: '50%',
                transform: 'translateX(-50%)'
            });
        } else {
            $footer.css({
                position: "relative"
            });
        }
    }

    function positionAdminFooter() {

        globalFooterTop = ($(window).height() - footerHeight)+"px";

        if ( (jQuery('#global').height() + footerHeight) < jQuery(window).height()){
            $footer.css({
                position: "absolute",
                top: globalFooterTop,
                right: 0
            });
        } else {
            $footer.css({
                position: "relative"
            });
        }
    }
});
