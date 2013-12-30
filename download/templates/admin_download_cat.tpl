		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
			# INCLUDE message_helper #
			<fieldset>
				<legend>{L_CATS_MANAGEMENT}</legend>
				{CATEGORIES}
			</fieldset>
			
			<div style="text-align:center; margin:30px 20px;">
				<a href="{U_RECOUNT_SUBFILES}" title="{L_RECOUNT_SUBFILES}">
					<i class="fa fa-refresh fa-2x"></i>
				</a>
				<br />
				<a href="{U_RECOUNT_SUBFILES}">{L_RECOUNT_SUBFILES}</a>
			</div>
		</div>