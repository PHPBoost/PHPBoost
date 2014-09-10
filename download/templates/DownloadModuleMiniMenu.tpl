<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title"># IF C_SORT_BY_DATE #{@last_download_files}# ELSE #{@most_downloaded_files}# ENDIF #</h5>
	</div>
	<div class="module-mini-contents">
		<p class="left">
		# IF C_FILES #
			# START downloadfiles #
				# IF C_SORT_BY_DATE #
					<time datetime="{downloadfiles.DATE_ISO8601}">{downloadfiles.DATE}</time> - 
					<a href="{downloadfiles.U_LINK}" title="{downloadfiles.NAME}">
						{downloadfiles.NAME}
					</a><br/>
				# ELSE #
					<a href="{downloadfiles.U_LINK}" title="{downloadfiles.NAME}">
						{downloadfiles.NAME}
					</a><br/>
					<span class="smaller">{downloadfiles.L_DOWNLOADED_TIMES}</span><br/>
				# ENDIF #
			# END downloadfiles #
		# ELSE #
		${LangLoader::get_message('no_item_now', 'common')}
		# ENDIF #
		</p>
	</div>
	<div class="module-mini-bottom">
	</div>
</div>