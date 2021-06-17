# IF C_ITEMS #
	<div class="cell-infos">
		<i class="far fa-question-circle fa-2x"></i>
		<a class="small mini-faq-question offload" href="{U_ITEM}">{TITLE}</a>
	</div>
# ELSE #
	<div class="cell-alert">
		<div class="message-helper bgc notice">
			${LangLoader::get_message('common.no.item.now', 'common-lang')}
		</div>
	</div>
# ENDIF #
