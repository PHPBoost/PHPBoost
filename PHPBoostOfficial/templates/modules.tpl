# START item #
<div class="lc lm">
	<a href="{item.U_LINK}">
		<div class="float-left">
			<p class="lm-img-container">
				# IF item.C_IMG #
					<img src="{item.U_IMG}" title="{item.TITLE}" alt="{item.TITLE}" class="lm-img" width="64" height="64"/>
				# ENDIF #
			</p>
		</div>
		<p class="lc-title lm-title">{item.TITLE}</p>
		<p class="lc-desc">{item.DESC}</p>
		<p class="lc-author">${TextHelper::lowercase_first(LangLoader::get_message('by', 'common'))} : <span>{item.PSEUDO}</span></p>
	</a>
</div>
# END item #