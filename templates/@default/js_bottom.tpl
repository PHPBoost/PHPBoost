<script>
// Delete confirmation
	function update_data_confirmations() {
		jQuery('[data-confirmation]').each(function() {
			data_confirmation = jQuery(this).attr('data-confirmation');
			if (data_confirmation == 'delete-element')
				var message = ${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))};
			else if (data_confirmation == 'delete-elements')
				var message = ${escapejs(LangLoader::get_message('confirm.delete.elements', 'status-messages-common'))};
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
				'errorMessage'    : ${escapejs(LangLoader::get_message('element.unexist', 'status-messages-common'))},
				'sequenceInfo.of' : ' ' + ${escapejs(TextHelper::lcfirst(LangLoader::get_message('of', 'common')))} + ' ',
				'close'           : ${escapejs(LangLoader::get_message('close', 'main'))},
				'navigator.prev'  : ${escapejs(LangLoader::get_message('previous', 'common'))},
				'navigator.next'  : ${escapejs(LangLoader::get_message('next', 'common'))},
				'navigator.play'  : ${escapejs(LangLoader::get_message('play', 'common'))},
				'navigator.pause' : ${escapejs(LangLoader::get_message('pause', 'common'))}
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
		ariaLabel : ${escapejs(LangLoader::get_message('click.to.select', 'common'))}
	});

// SelectImg multi
	jQuery('.multiple-select-to-list').multipleSelectImg();

// sizes of .cell-thumbnail
	jQuery('.cell-thumbnail.cell-landscape').each(function() {
		var widthRef = $(this).innerWidth();
		$(this).outerHeight(widthRef * 9 / 16);
	});
	jQuery('.cell-thumbnail.cell-square').each(function() {
		var widthRef = $(this).innerWidth();
		$(this).outerHeight(widthRef);
	});
	jQuery('.cell-thumbnail.cell-portrait').each(function() {
		var widthRef = $(this).innerWidth();
		$(this).outerHeight(widthRef * 16 / 9);
	});

// Display the page only when it's loaded
	jQuery(window).ready(function() {
  		jQuery('.body-wrapper').animate({opacity: 1}, 300);
	});

// Autoresize Textareas
	// $(document).autoboxOn('textarea:not(.lined textarea)');

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
				768: { items: 2},
				1024: { items: 3},
				1366: { items: 4}
			}
	});
</script>

# IF C_COOKIEBAR_ENABLED #
<script src="{PATH_TO_ROOT}/user/templates/js/cookiebar.js"></script>
# ENDIF #
