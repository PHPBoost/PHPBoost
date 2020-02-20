<div class="cell-body">
	<div class="cell-content">
		# IF C_PARTNERS #
			# START partners #
				<a href="{partners.U_VISIT}" class="mini-content-friends-link"># IF partners.C_HAS_PARTNER_THUMBNAIL #<img src="{partners.U_PARTNER_THUMBNAIL}" alt="{partners.NAME}" itemprop="image" class="content-friends-picture" /># ELSE #{partners.NAME}# ENDIF #</a>
			# END partners #
		# ELSE #
			${LangLoader::get_message('no_item_now', 'common')}
		# ENDIF #
	</div>
</div>
