<section>
	<header><h1>{TITLE}</h1></header>
	<div class="content">
	
		# INCLUDE SHOUTBOX_FORM #

		# START messages #
		<article id="msg{messages.ID}" class="message">
			<div class="message-user-infos">
				<div class="message-pseudo">
					# IF messages.C_VISITOR #
						<span>{messages.PSEUDO}</span>
					# ELSE #
						<a href="{messages.U_PROFILE}" class="{messages.LEVEL_CLASS}" # IF messages.C_GROUP_COLOR # style="color:{messages.GROUP_COLOR}" # ENDIF #>
							{messages.PSEUDO}
						</a>
					# ENDIF #
				</div>
				<div class="message-level">{messages.L_LEVEL}</div>
				# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" class="message-avatar" /># ENDIF #
			</div>
			<div class="message-content">
				<div class="message-date">
					<span class="actions">
						<a href="\#msg{messages.ID_COMMENT}">\#{messages.ID}</a>
						# IF messages.C_MODERATOR #
							<a href="{messages.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
							<a href="{messages.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
					<span>{messages.DATE}</span>
				</div>
				<div class="message-message">
					<div class="message-content" class="content">{messages.MESSAGE}</div>
				</div>
			</div>
		</article>
		# END messages #
		# IF C_PAGINATION #
			# INCLUDE PAGINATION #
		# ENDIF #
	</div>
	<footer></footer>
</section>