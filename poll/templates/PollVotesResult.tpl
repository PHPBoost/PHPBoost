# IF C_HAS_VOTES #
    <div id="results" class="align-center">{@poll.results}</div>
    # START votes_result #
		<div class="flex-between">
			<span>{votes_result.ANSWER}</span>
			<span class="small">{votes_result.VOTES_NUMBER}/{TOTAL_VOTES_NUMBER}</span>
		</div>
		<div class="progressbar-container" role="progressbar" aria-valuenow="{votes_result.PERCENTAGE}" aria-valuemin="0" aria-valuemax="100">
			<div class="progressbar-infos">{votes_result.PERCENTAGE}%</div>
			<div class="progressbar" style="width:{votes_result.PERCENTAGE}%;"></div>
		</div>
    # END votes_result #
    <div id="total-votes" class="align-center"><span class="pinned notice">{@poll.total.votes} {TOTAL_VOTES_NUMBER}</span></div>
# ELSE #
    <div id="no-vote" class="align-center">{@poll.no.vote}</div>
# ENDIF #
