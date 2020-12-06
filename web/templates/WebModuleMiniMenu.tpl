<div class="cell-body">
	<div class="cell-content">
		# IF C_PARTNERS #
			# START items #
				<a href="{items.U_VISIT}" class="mini-content-friends-link"># IF items.C_HAS_PARTNER_THUMBNAIL #<img src="{items.U_PARTNER_THUMBNAIL}" alt="{items.NAME}" itemprop="image" class="content-friends-picture" /># ELSE #{items.NAME}# ENDIF #</a>
			# END items #
		# ELSE #
			${LangLoader::get_message('no_item_now', 'common')}
		# ENDIF #
	</div>
</div>
