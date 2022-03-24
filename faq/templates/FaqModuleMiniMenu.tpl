# IF C_ITEMS #
	<div class="cell-content flex-between">
		<i class="far fa-question-circle fa-2x"></i>
		<a class="small mini-faq-question offload" href="{U_ITEM}">{TITLE}</a>
	</div>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">
			{@common.no.item.now}
		</div>
	</div>
# ENDIF #
