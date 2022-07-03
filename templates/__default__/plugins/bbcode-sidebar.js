/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 07 01
 * @since       PHPBoost 1.2 - 2022 03 22
*/


// Auto resize textarea on focus
jQuery.fn.autoResize = function(){
	let resize = e => {
		e.style.height = '';
		e.style.height = e.scrollHeight + 'px'
	};
	return this.each((i,e) => {
		e.style.overflow = 'hidden';
		resize(e);
		jQuery(e).bind('input', e => {
			resize(e.target);
		})
	})
};

jQuery('.bbcode-sidebar > textarea').each(function () {
    var y,
        $this = jQuery(this),
        $thisHeight = jQuery(this).outerHeight(),
        offset = $this.offset(),
        tools = $this.siblings('.bbcode-bar'),
        preview = $this.siblings('.xmlhttprequest-preview'),
		parent = $this.closest('.form-element-textarea'),
        sidebar = tools.outerHeight();
	$this.height($this[0].scrollHeight);
	preview.appendTo(parent);

    $this.on('mouseup', function (event) {
        var container = $this.parent().outerHeight();
        (y = event.pageY - offset.top), sidebar > y ?
			tools.css({ top: '0.809em', bottom: 'auto' }) :
			y > container - sidebar ?
				tools.css({ top: 'auto', bottom: '0.809em' }) :
				tools.css({ top: y - sidebar / 2 + 'px', bottom: 'auto' });
    }),

    jQuery(document).on('click', function (event) {
        jQuery(event.target).hasClass('auto-resize') || jQuery(event.target).is('button') || jQuery(event.target).is('i') || $this.is(':focus') ? $this.autoResize() : $this.css('height', $thisHeight + 'px');
    });
});

jQuery('.bbcode-group-title, .close-bbcode-sub').each(function(){
	var mainButtonParent = jQuery(this).closest('.bbcode-group');
	jQuery(this).on('click', function() {
		mainButtonParent.toggleClass('bbcode-sub');
		if(mainButtonParent.siblings().hasClass('bbcode-sub')) 
			mainButtonParent.siblings().removeClass('bbcode-sub');
	});
});