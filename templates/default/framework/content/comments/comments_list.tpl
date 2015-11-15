# START comments #
	<article id="com{comments.ID_COMMENT}" class="message" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<header>
			<h2>${LangLoader::get_message('comment', 'comments-common')}</h2>
		</header>
		<div class="message-container">

			<div class="message-user-infos">
				<div class="message-pseudo">
					# IF comments.C_VISITOR #
						<span itemprop="author">{comments.PSEUDO}</span>
					# ELSE #
						<a itemprop="author" href="{comments.U_PROFILE}" class="{comments.LEVEL_CLASS}" # IF comments.C_GROUP_COLOR # style="color:{comments.GROUP_COLOR}" # ENDIF #>
							{comments.PSEUDO}
						</a>
					# ENDIF #
				</div>
				<div class="message-level">{comments.L_LEVEL}</div>
				# IF comments.C_AVATAR #<img src="{comments.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar" /># ENDIF #
			</div>

			<div class="message-date">
				<span class="actions">
					<a itemprop="url" href="\#com{comments.ID_COMMENT}">\#{comments.ID_COMMENT}</a>
					# IF comments.C_MODERATOR #
						<a href="{comments.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
						<a href="{comments.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
					# ENDIF #
				</span>
				<time itemprop="datePublished" datetime="{comments.DATE_ISO8601}">{comments.DATE}</time>
			</div>

			<div class="message-message">
				<div itemprop="text" class="message-content content">{comments.MESSAGE}</div>
				# IF comments.C_VIEW_TOPIC #
					<div class="view-topic">
						<a href="{comments.U_TOPIC}\#com{comments.ID_COMMENT}">
						{L_VIEW_TOPIC}
						<i class="fa fa-arrow-circle-right"></i>
					</a>
					</div>
				# ENDIF #
				<!-- 
				<div id="message-rating">
					<div class="positive_vote_button">+</div>
					<div class="negative_vote_button">-</div>
				</div>
				 -->
			</div>
			
		</div>
		<footer></footer>
	</article>
# END comments #
