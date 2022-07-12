<script src="{PATH_TO_ROOT}/templates/__default__/plugins/autocomplete# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/basictable# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/lightcase# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/linedtextarea# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/list_order# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/multitabs# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/owl.carousel# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/precode# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/selectimg# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/selectimg.multi# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/sortable# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/theia-sticky-sidebar# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/tooltip# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/wizard# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script src="{PATH_TO_ROOT}/templates/__default__/plugins/bbcode-sidebar# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

<script>
// Delete confirmation
	function update_data_confirmations() {
		jQuery('[data-confirmation]').each(function() {
			data_confirmation = jQuery(this).attr('data-confirmation');
			if (data_confirmation == 'delete-element')
				var message = ${escapejs(@warning.confirm.delete)};
			else if (data_confirmation == 'delete-elements')
				var message = ${escapejs(@warning.confirm.delete.elements)};
			else
				var message = data_confirmation;
			this.onclick = function () { return confirm(message); }
		});
	}

// lightbox
	jQuery(document).ready(function() {
		update_data_confirmations();
		jQuery('a[rel^=lightbox]').attr('data-rel', 'lightcase:collection');
		jQuery('a[data-lightbox^=formatter]').attr('data-rel', 'lightcase:collection');
		jQuery('a[data-rel^=lightcase]').lightcase({
			labels : {
				'errorMessage'    : ${escapejs(@warning.element.unexists)},
				'sequenceInfo.of' : ' ' + ${escapejs(TextHelper::lcfirst(@common.of))} + ' ',
				'close'           : ${escapejs(@common.close)},
				'navigator.prev'  : ${escapejs(@common.previous)},
				'navigator.next'  : ${escapejs(@common.next)},
				'navigator.play'  : ${escapejs(@common.play)},
				'navigator.pause' : ${escapejs(@common.pause)}
			},
			maxHeight: window.innerHeight,
			maxWidth: window.innerWidth,
			shrinkFactor: 0.85
		});
	});

// BBCode tables because they have no header
	jQuery('.formatter-table').each(function(){
		$this = jQuery(this).find('tbody tr:first-child td');
		if (!$this.hasClass('formatter-table-head'))
			$this.closest('.formatter-table').removeClass('table').addClass('table-no-header');
	});

// All tables
	jQuery('.table').basictable();
	jQuery('.table-no-header').basictable({
		header: false
	});

// line numbers in <code>
	jQuery(function() {
		jQuery(".lined textarea").linedtextarea();
	});

// Delete captcha fielset if captcha is active when user is connected
	if(jQuery('.captcha-element .form-element').length == 0)
		jQuery('.captcha-element').removeClass('wizard-step');

// Multitabs
    jQuery('.modal-container [data-modal]').multiTabs({ pluginType: 'modal' });
    jQuery('.accordion-container.basic [data-accordion]').multiTabs({ pluginType: 'accordion'});
    jQuery('.accordion-container.siblings [data-accordion]').multiTabs({ pluginType: 'accordion', accordionSiblings: true });
    jQuery('.tabs-container [data-tabs]').multiTabs({ pluginType: 'tabs' });

// Wizard
    jQuery('.wizard-container').wizard();

// SelectImg
	jQuery('.select-to-list').selectImg({
		ariaLabel : ${escapejs(@common.click.to.select)}
	});

// SelectImg multi
	jQuery('.multiple-select-to-list').multipleSelectImg();

// sizes of .cell-thumbnail
	jQuery('.cell-thumbnail.cell-landscape').each(function() {
		var widthRef = jQuery(this).innerWidth();
		jQuery(this).outerHeight(widthRef * 9 / 16);
	});
	jQuery('.cell-thumbnail.cell-square').each(function() {
		var widthRef = jQuery(this).innerWidth();
		jQuery(this).outerHeight(widthRef);
	});
	jQuery('.cell-thumbnail.cell-portrait').each(function() {
		var widthRef = jQuery(this).innerWidth();
		jQuery(this).outerHeight(widthRef * 16 / 9);
	});
	jQuery(window).on('resize', function(){
		jQuery('.cell-thumbnail.cell-landscape').each(function() {
			var widthRef = jQuery(this).innerWidth();
			jQuery(this).outerHeight(widthRef * 9 / 16);
		});
		jQuery('.cell-thumbnail.cell-square').each(function() {
			var widthRef = jQuery(this).innerWidth();
			jQuery(this).outerHeight(widthRef);
		});
		jQuery('.cell-thumbnail.cell-portrait').each(function() {
			var widthRef = jQuery(this).innerWidth();
			jQuery(this).outerHeight(widthRef * 16 / 9);
		});
	});

// Add a colored square to the element and color its borders if it has
	jQuery('[data-color-surround]').colorSurround();

// Owl Carousel
	jQuery('[id*="slideboost"] > br').remove();
	jQuery('[id*="slideboost"]')
		.addClass('owl-carousel')
		.owlCarousel({
			autoplay: true,
			autoplayTimeout: 3500,
			loop: true,
			margin: 15,
			smartSpeed: 1000,
			autoplayHoverPause: true,
			responsive: {
				0: { items: 1},
				769: { items: 2},
				1025: { items: 3},
				1367: { items: 4}
			}
	});

// Sidebar behaviour - needed to fix the BBCode troubles on long texts
	jQuery('#menu-left, #menu-right').theiaStickySidebar();

// Display the page only when it's loaded
	jQuery(window).ready(function() {
		jQuery('.content-preloader').animate({opacity: 1}, 300);
	});
</script>

# IF C_COOKIEBAR_ENABLED #
	<script src="{PATH_TO_ROOT}/user/templates/js/cookiebar# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ENDIF #
