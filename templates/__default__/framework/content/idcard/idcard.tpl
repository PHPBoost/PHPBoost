<div class="id-card cell-tile cell-row">
	<div class="cell">
		<header class="cell-header">
			<h5 class="cell-name">{@about.author}</h5>
		</header>
		<div class="cell-body">
			# IF C_AVATAR #
				<div class="cell-thumbnail"><img src="{U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" /></div>
			# ENDIF #
			<div class="cell-content">
				<h6>
					# IF C_AUTHOR_IS_MEMBER #
						<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{AUTHOR_NAME}</a>
					# ENDIF #
				</h6>
				<p class="biography">{BIOGRAPHY}</p>
			</div>
		</div>
	</div>
</div>
