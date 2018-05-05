# IF C_STATIC_DISPLAY #
	# IF C_NOTES #
		<div class="static-notation" itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
			# START star #
				<a href="" onclick="return false;" class="fa star # IF star.STAR_EMPTY #fa-star-o# ENDIF ## IF star.STAR_HALF #fa-star-half-o# ENDIF ## IF star.STAR_FULL #fa-star# ENDIF #"></a>
			# END star #
			<meta itemprop="ratingCount" content="{NUMBER_NOTES}">
			<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
			<meta itemprop="bestRating" content="{NOTATION_SCALE}">
		</div>
	# ELSE #
		<span>{L_NO_NOTE}</span>
	# ENDIF #
# ELSE #
	# IF C_JS_NOT_ALREADY_INCLUDED #
		<script>
		<!--
			var NOTATION_LANG_AUTH = ${escapejs(L_AUTH_ERROR)};
			var NOTATION_LANG_ALREADY_VOTE = ${escapejs(L_ALREADY_NOTE)};
			var NOTATION_LANG_NOTE = ${escapejs(L_NOTE)};
			var NOTATION_LANG_NOTES = ${escapejs(L_NOTES)};
			var NOTATION_USER_CONNECTED = ${escapejs(IS_USER_CONNECTED)};
		-->
		</script>
		<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/notation.js"></script>
	# ENDIF #
	
	<script>
	<!--
		jQuery(document).ready(function() { new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}', '{AVERAGE_NOTES}', '{ALREADY_NOTE}'); });
	-->
	</script>
		
	<div class="notation" id="notation-{ID_IN_MODULE}" # IF C_NOTES #itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating"# ENDIF #>
		<span class="stars">
			# START star #
				<a href="" onclick="return false;" class="fa star # IF star.STAR_EMPTY #fa-star-o# ENDIF ## IF star.STAR_HALF #fa-star-half-o# ENDIF ## IF star.STAR_FULL #fa-star# ENDIF #" id="star-{ID_IN_MODULE}-{star.I}"></a>
			# END star #
		</span>
		<span class="notes">
			<span class="number-notes" # IF C_NOTES #itemprop="ratingCount"# ENDIF #>{NUMBER_NOTES}</span>
			# IF C_MORE_1_NOTES #
				<span title="{NUMBER_NOTES} {L_NOTES}">{L_NOTES}</span>
			# ELSE #
				<span title="{NUMBER_NOTES} {L_NOTE}">{L_NOTE}</span>
			# ENDIF #
		</span>
		# IF C_NOTES #
		<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
		<meta itemprop="bestRating" content="{NOTATION_SCALE}">
		# ENDIF #
	</div>
# ENDIF #