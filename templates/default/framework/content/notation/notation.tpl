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
	var Note{ID_IN_MODULE} = new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}', '{AVERAGE_NOTES}');
	Note{ID_IN_MODULE}.set_already_post(${escapejs(ALREADY_NOTE)});
	Note{ID_IN_MODULE}.set_user_connected(${escapejs(IS_USER_CONNECTED)});
	Note{ID_IN_MODULE}.set_current_url(${escapejs(CURRENT_URL)});
	Note{ID_IN_MODULE}.add_lang('auth_error', ${escapejs(L_AUTH_ERROR)});
	Note{ID_IN_MODULE}.add_lang('already_vote', ${escapejs(L_ALREADY_NOTE)});
	Note{ID_IN_MODULE}.add_lang('note', ${escapejs(L_NOTE)});
	Note{ID_IN_MODULE}.add_lang('notes', ${escapejs(L_NOTES)});
-->
</script>
	
<div class="notation" id="notation-{ID_IN_MODULE}" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
	<span class="stars">
		# START star #
			<span class="star # IF star.STAR_EMPTY #icon-star-o# ENDIF ## IF star.STAR_HALF #icon-star-half-o# ENDIF ## IF star.STAR_FULL #icon-star# ENDIF #" id="star-{ID_IN_MODULE}-{star.I}"></span>
		# END star #
	</span>
	<span class="notes">
		<span class="number-notes" itemprop="reviewCount">{NUMBER_NOTES}</span>
		# IF C_MORE_1_NOTES #
			<span>{L_NOTES}</span>
		# ELSE #
			<span>{L_NOTE}</span>
		# ENDIF #
	</span>
	<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
	<meta itemprop="bestRating" content="{NOTATION_SCALE}">
</div>
# ENDIF #