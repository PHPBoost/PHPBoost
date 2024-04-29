# IF C_STATIC_DISPLAY #
	# IF C_NOTES #
		<div role="note" aria-label="{@common.note}: {AVERAGE_NOTES}/{NOTATION_SCALE}" class="notation static-notation" itemprop="aggregateRating" itemscope="itemscope" itemtype="https://schema.org/AggregateRating">
			# START star #
				<a href="#" onclick="return false;" class="far star fa-star" id="star-{ID_IN_MODULE}-{star.I}" aria-hidden="true"><span class="star-width {star.STAR_WIDTH}"></span></a>
			# END star #
			<meta itemprop="ratingCount" content="{NOTES_NUMBER}">
			<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
			<meta itemprop="bestRating" content="{NOTATION_SCALE}">
		</div>
	# ELSE #
		<span>{@common.no.note}</span>
	# ENDIF #
# ELSE #
	# IF C_JS_NOT_ALREADY_INCLUDED #
		<script>
			var NOTATION_LANG_AUTH = ${escapejs(@warning.auth)};
			var NOTATION_LANG_ALREADY_VOTE = ${escapejs(@common.already.voted)};
			var NOTATION_LANG_NOTE = ${escapejs(@common.note)};
			var NOTATION_LANG_NOTES = ${escapejs(@common.notes)};
			var NOTATION_USER_CONNECTED = ${escapejs(IS_USER_CONNECTED)};
		</script>
		<script src="{PATH_TO_ROOT}/templates/__default__/plugins/notation# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
	# ENDIF #

	<script>
		jQuery(document).ready(function() { new Note('{ID_IN_MODULE}', '{NOTATION_SCALE}', '{AVERAGE_NOTES}', '{ALREADY_NOTE}'); });
	</script>

	<div class="notation" id="notation-{ID_IN_MODULE}" # IF C_NOTES #itemprop="aggregateRating" itemscope="itemscope" itemtype="https://schema.org/AggregateRating"# ENDIF #>
		<span class="stars">
			# START star #
				<a aria-label="{@common.add.note} {star.I}/{NOTATION_SCALE}" href="#" onclick="return false;" class="far star fa-star" id="star-{ID_IN_MODULE}-{star.I}"><span class="star-width {star.STAR_WIDTH}"></span></a>
			# END star #
		</span>
		<span class="notes">
			<span class="number-notes" # IF C_NOTES #itemprop="ratingCount"# ENDIF #>{NOTES_NUMBER}</span>
			# IF C_SEVERAL_NOTES #
				<span role="note" aria-label="{NOTES_NUMBER} {@common.notes}">{@common.notes}</span>
			# ELSE #
				<span role="note" aria-label="{NOTES_NUMBER} {@common.note}">{@common.note}</span>
			# ENDIF #
		</span>
		# IF C_NOTES #
			<meta itemprop="ratingValue" content="{AVERAGE_NOTES}">
			<meta itemprop="bestRating" content="{NOTATION_SCALE}">
		# ENDIF #
	</div>
# ENDIF #
