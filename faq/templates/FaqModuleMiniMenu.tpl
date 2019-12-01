<div class="cell-body">
	# IF C_QUESTION #
		<a href="{U_LINK}">
			<div class="cell-infos">
				<i class="far fa-question-circle fa-2x"></i>
				<span class="small mini-faq-question">{QUESTION}</span>
			</div>
		</a>
	# ELSE #
		<div class="cell-content">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ENDIF #
</div>
