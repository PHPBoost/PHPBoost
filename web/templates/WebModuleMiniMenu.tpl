<div class="cell-body">
	<div class="cell-content">
		# IF C_PARTNERS #
			# START items #
				<a href="{items.U_VISIT}" class="mini-content-friends-link offload"># IF items.C_HAS_PARTNER_THUMBNAIL #<img src="{items.U_PARTNER_THUMBNAIL}" alt="{items.NAME}" itemprop="image" class="content-friends-picture" /># ELSE #{items.NAME}# ENDIF #</a>
			# END items #
		# ELSE #
			{@common.no.item.now}
		# ENDIF #
	</div>
</div>
