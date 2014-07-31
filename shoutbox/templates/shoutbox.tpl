<section>
	<header><h1>{TITLE}</h1></header>
	<div class="content">
		
		# IF C_POST_MESSAGE #
		# INCLUDE SHOUTBOX_FORM #
		# ELSE #
		<div class="notice">{E_UNAUTHORIZED}</div>
		<div class="spacer">&nbsp;</div>
		# ENDIF #
		
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
				# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" alt="" class="message-avatar" /># ENDIF #
			</div>
			<div class="message-container">
				<div class="message-date">
					<span class="actions">
						<a href="\#msg{messages.ID_COMMENT}">\#{messages.ID}</a>
						# IF messages.C_MODERATOR #
							<a href="{messages.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
							<a href="{messages.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
					<span>{messages.DATE}</span>
				</div>
				<div class="message-message">
					<div class="message-content">{messages.MESSAGE}</div>
				</div>
			</div>
		</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION ## INCLUDE PAGINATION ## ENDIF #</footer>
</section>