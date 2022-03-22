/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 22
 * @since       PHPBoost 1.2 - 2022 03 22
*/


// Auto resize textarea on focus
$.fn.autoResize = function(){
	let r = e => {
		e.style.height = '';
		e.style.height = e.scrollHeight + 'px'
	};
	return this.each((i,e) => {
		e.style.overflow = 'hidden';
		r(e);
		$(e).bind('input', e => {
			r(e.target);
		})
	})
};

jQuery('.bbcode-sidebar textarea').each(function(){
	var y,
		$this = jQuery(this),
		$thisHeight = jQuery(this).outerHeight(),
		offset = $this.offset(),
		tools = $this.closest('.form-field-textarea').find('.bbcode-bar'),
		sidebar = tools.outerHeight();

	// Sidebar follow the mouse click
	$this.on('mouseup', function(e) {
		var container = $this.closest('.form-field-textarea').outerHeight();
		// mouse coords
		y = e.pageY - offset.top;

		if(y < (sidebar))
		tools.css({
			'top': '0.809em',
			'bottom': 'auto'
		});
		else if (y > (container - sidebar))
		tools.css({
			'top': 'auto',
			'bottom': '0.809em'
		});
		else
		tools.css({
			'top': (y - sidebar/2) + 'px',
			'bottom': 'auto'
		});
	});

	// If focused, textarea resizes
	jQuery(document).on('click', function(){
		if($this.is(':focus'))
			$this.autoResize();
		else
			$this.css('height', $thisHeight + 'px');
	})
});
