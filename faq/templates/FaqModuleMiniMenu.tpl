<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{@faq.random_question}</h5>
	</div>
	<div class="module-mini-contents">
		<p class="center">
		# IF C_QUESTION #
			<a href="{U_LINK}"><img src="{PATH_TO_ROOT}/faq/faq.png" alt="" itemprop="image" /></a>
			<br />
			<a href="{U_LINK}" class="small">{QUESTION}</a>
		# ELSE #
		${LangLoader::get_message('no_item_now', 'common')}
		# ENDIF #
		</p>
	</div>
	<div class="module-mini-bottom">
	</div>
</div>