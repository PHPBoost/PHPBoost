{JS_BOTTOM}
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

// Accordion
	jQuery('.multiple-accordion').accordion();
	jQuery('.single-accordion').accordion({
		openSingle: true
	});

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
	colorSurround('[data-color-surround]');

// Owl Carousel
	jQuery('[id*="slideboost-"] > br').remove();
	jQuery('[id*="slideboost-4-"]')
		.addClass('owl-carousel')
		.owlCarousel({
            nav: true,
            navText: [
                '<span aria-label="' + ${escapejs(@common.previous)} + '"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>',
                '<span aria-label="' + ${escapejs(@common.next)} + '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'
            ],
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
	jQuery('[id*="slideboost-3-"]')
		.addClass('owl-carousel')
		.owlCarousel({
            nav: true,
            navText: [
                '<span aria-label="' + ${escapejs(@common.previous)} + '"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>',
                '<span aria-label="' + ${escapejs(@common.next)} + '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'
            ],
			autoplay: true,
			autoplayTimeout: 3500,
			loop: true,
			margin: 15,
			smartSpeed: 1000,
			autoplayHoverPause: true,
			responsive: {
				0: { items: 1},
				769: { items: 2},
				1025: { items: 3}
			}
	});
	jQuery('[id*="slideboost-2-"]')
		.addClass('owl-carousel')
		.owlCarousel({
            nav: true,
            navText: [
                '<span aria-label="' + ${escapejs(@common.previous)} + '"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>',
                '<span aria-label="' + ${escapejs(@common.next)} + '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'
            ],
			autoplay: true,
			autoplayTimeout: 3500,
			loop: true,
			margin: 15,
			smartSpeed: 1000,
			autoplayHoverPause: true,
			responsive: {
				0: { items: 1},
				769: { items: 2}
			}
	});
	jQuery('[id*="slideboost-1-"]')
		.addClass('owl-carousel')
		.owlCarousel({
            nav: true,
            navText: [
                '<span aria-label="' + ${escapejs(@common.previous)} + '"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>',
                '<span aria-label="' + ${escapejs(@common.next)} + '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'
            ],
			autoplay: true,
			autoplayTimeout: 3500,
			loop: true,
			margin: 15,
			smartSpeed: 1000,
			autoplayHoverPause: true,
			responsive: {
				0: { items: 1}
			}
	});

// Sidebar behaviour - needed to fix the BBCode troubles on long texts
	// jQuery('#main').theiaStickySidebar();
	// # IF C_HAS_LEFT_MENUS #jQuery('#menu-left').theiaStickySidebar();# ENDIF #
	// # IF C_HAS_RIGHT_MENUS #jQuery('#menu-right').theiaStickySidebar();# ENDIF #

// Add outline on element if only Tab key is pressed
    jQuery('*').on('focus', function(e) {
        $this = jQuery(this);
        jQuery(window).keyup(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 9) {
                $this.addClass('focus-on-tab');
            }
        });
    }).on('click', function(e) {
        jQuery(this).removeClass('focus-on-tab');
    });

// Display the page only when it's loaded
	jQuery(window).ready(function() {
		jQuery('.content-preloader').animate({opacity: 1}, 300);
	});

// Construct list item for file path - Use + symbol to indent list items
    document.querySelectorAll('.file-path > br').forEach(br => br.remove());
    document.querySelectorAll('.file-path li').forEach(el => {
        const span = document.createElement('span');
        span.innerHTML = '&vdash;&nbsp;';
        el.prepend(span);

        let str = el.innerHTML;
        const hyphenNb = (str.match(/\+/g) || []).length;
        el.style.paddingLeft = hyphenNb * 1.1618 + 'em';
        const newstr = str.replace(/\+/g, '');
        el.innerHTML = newstr;
    });

</script>


