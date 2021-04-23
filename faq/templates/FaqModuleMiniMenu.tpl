# IF C_ITEMS #
	<div class="cell-infos">
		<i class="far fa-question-circle fa-2x"></i>
		<a class="small mini-faq-question" href="{U_ITEM}">{QUESTION}</a>
	</div>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	</div>
# ENDIF #
