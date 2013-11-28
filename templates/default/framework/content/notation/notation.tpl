<style>
<!--
.static-notation span.star, .notation span.star { color: #999; font-size: 14px; padding-right: 5px;}

.notation span.star-hover {
	color: #e3cf7a;
}

-->
</style>

# IF C_STATIC_DISPLAY #
	# IF C_NOTES #
	<div itemscope="itemscope" itemtype="http://schema.org/AggregateRating" style="display:inline;" class="static-notation">
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
		Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseover', function() {  
			Note{ID_IN_MODULE}.over_event();
		});
		
		Event.observe($('note_pictures{ID_IN_MODULE}'), 'mouseout', function() {  
			Note{ID_IN_MODULE}.out_event();
		});
		
		$('note_select{ID_IN_MODULE}').hide();
		$('valid_note{ID_IN_MODULE}').hide();
		$('note_pictures{ID_IN_MODULE}').style.display = 'inline';
	});
-->
</script>
	
<div itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
	<span style="display:none" class="notation" id="note_pictures{ID_IN_MODULE}">
	# START star #
		<span class="star # IF star.STAR_EMPTY #icon-star-o# ENDIF ## IF star.STAR_HALF #icon-star-half-o# ENDIF ## IF star.STAR_FULL #icon-star# ENDIF #" id="star_{ID_IN_MODULE}_{star.I}" href="javascript:Note{ID_IN_MODULE}.send_request({star.I})" onmouseover="javascript:Note{ID_IN_MODULE}.change_picture_status({star.I});"></span>
	# END star #
	</span>
		
	<form action="" method="post" class="smaller">
		<span id="note_value{ID_IN_MODULE}">
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
		<select id="note_select{ID_IN_MODULE}" name="note">
			<option value="-1">{L_NOTE}</option>
			# START star #
			<option value="{star.I}">{star.I}</option>
			# END star #
		</select>
		<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
		<meta itemprop="bestRating" content="{NOTATION_SCALE}">
		<input type="hidden" name="token" value="{TOKEN}">
		<button type="submit" name="valid" id="valid_note{ID_IN_MODULE}" value="true" style="padding:1px 2px;">{L_VALID_NOTE}</button>
	</form>
</div>
# ENDIF #