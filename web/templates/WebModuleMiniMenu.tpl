<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{@partners}</h5>
	</div>
	<div class="module-mini-contents">
		<p class="center">
		# IF C_PARTNERS #
			# START partners #
			<a href="{partners.U_VISIT}" title="{partners.NAME}">
				# IF partners.C_HAS_PARTNER_PICTURE #
				<img src="{partners.PARTNER_PICTURE}" alt="" itemprop="image" class="partner-picture-menu" />
				# ELSE #
				{partners.NAME}
				# ENDIF #
			</a>
			# END partners #
		# ELSE #
		${LangLoader::get_message('no_item_now', 'common')}
		# ENDIF #
		</p>
	</div>
	<div class="module-mini-bottom">
	</div>
</div>