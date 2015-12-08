# START item #
<div class="lc lt">
	<a href="{item.U_LINK}" class="lt-title-a">
		<p class="lc-title lt-title">{item.TITLE}</p>
		<p class="lt-img-container">
			# IF item.C_IMG #
				<img src="{item.U_IMG}" title="{item.TITLE}" alt="{item.TITLE}" class="lt-img"/>
			# ENDIF #
		</p>
		
		<p class="lc-desc lt-desc">{item.DESC}</p>
		<p class="lc-author lt-author">${TextHelper::lowercase_first(LangLoader::get_message('by', 'common'))} : <span>{item.PSEUDO}</span></p>
	</a>
</div>
# END item #