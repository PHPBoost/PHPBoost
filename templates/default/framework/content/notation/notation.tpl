<style>
<!--
.static-notation .star, .notation .star { color: #e3cf7a; font-size: 14px; padding-right: 5px; text-decoration: none; }
.notation .star { cursor:pointer; }
-->
</style>

# IF C_STATIC_DISPLAY #
	# IF C_NOTES #
	<div class="static-notation" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
		# START star #
			<span class="star # IF star.STAR_EMPTY #icon-star-o# ENDIF ## IF star.STAR_HALF #icon-star-half-o# ENDIF ## IF star.STAR_FULL #icon-star# ENDIF #"></span>
		# END star #
		<meta itemprop="reviewCount" content="{NUMBER_NOTES}">
		<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
		<meta itemprop="bestRating" content="{NOTATION_SCALE}">
	</div>
	# ELSE #
	<span>{L_NO_NOTE}</span>
	# ENDIF #
# ELSE #
<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/notation.js"></script>
<script type="text/javascript">
<!--
	var Note{ID_IN_MODULE} = new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}', {NUMBER_NOTES});
	Note{ID_IN_MODULE}.set_default_note('{AVERAGE_NOTES}');
	Note{ID_IN_MODULE}.set_already_post(${escapejs(ALREADY_NOTE)});
	Note{ID_IN_MODULE}.set_user_connected(${escapejs(IS_USER_CONNECTED)});
	Note{ID_IN_MODULE}.set_current_url(${escapejs(CURRENT_URL)});
	Note{ID_IN_MODULE}.add_lang('auth_error', ${escapejs(L_AUTH_ERROR)});
	Note{ID_IN_MODULE}.add_lang('already_vote', ${escapejs(L_ALREADY_NOTE)});
	Note{ID_IN_MODULE}.add_lang('note', ${escapejs(L_NOTE)});
	Note{ID_IN_MODULE}.add_lang('notes', ${escapejs(L_NOTES)});
	
	Event.observe(window, 'load', function() {
		$$('#notation-{ID_IN_MODULE} .stars').invoke('observe', 'mouseover', function(event) {
			Note{ID_IN_MODULE}.over_event();
		});

		$$('#notation-{ID_IN_MODULE} .stars').invoke('observe', 'mouseout', function(event) {
			Note{ID_IN_MODULE}.out_event();
		});
		
		$$('.notation .star').invoke('observe', 'click', function(event) {
			var id_element = event.element().id;
			var star_nbr = id_element.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
			Note{ID_IN_MODULE}.send_request(star_nbr);
		});

		$$('.notation .star').invoke('observe', 'mouseover', function(event) {
			var id_element = event.element().id;
			var star_nbr = id_element.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
			Note{ID_IN_MODULE}.change_picture_status(star_nbr);
		});
	});
-->
</script>
	
<div class="notation" id="notation-{ID_IN_MODULE}" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
	<span class="stars">
		# START star #
			<span class="star # IF star.STAR_EMPTY #icon-star-o# ENDIF ## IF star.STAR_HALF #icon-star-half-o# ENDIF ## IF star.STAR_FULL #icon-star# ENDIF #" id="star-{ID_IN_MODULE}-{star.I}"></span>
		# END star #
	</span>
	<span id="number-notes-{ID_IN_MODULE}">
		# IF C_NOTES #
			<span itemprop="reviewCount">{NUMBER_NOTES}</span>
			# IF C_MORE_1_NOTES #
				{L_NOTES}
			# ELSE #
				{L_NOTE}
			# ENDIF #
		# ELSE #
			{L_NO_NOTE}
		# ENDIF #
	</span>
	<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
	<meta itemprop="bestRating" content="{NOTATION_SCALE}">
</div>
# ENDIF #