		<div style="margin-bottom:10px;">
			<menu class="dynamic_menu right group">
				<ul>
				# IF C_ACTIV_COM #
					<li>
						<a href="{U_COM}" ><i class="icon-comments"></i> {L_COM}</a>
					</li>
				# ENDIF #
					<li>
						<a><i class="icon-cog"></i> {L_OTHER_TOOLS}</a>
						<ul>
							<li><a href="{U_HISTORY}" title="{L_HISTORY}">
								<img src="{PICTURES_DATA_PATH}/images/history.png"/>
								{L_HISTORY}
							</a></li>
							# IF C_INDEX_PAGE #
								# IF IS_ADMIN #
									<li><a href="{U_EDIT_INDEX}" title="{L_EDIT_INDEX}">
										<img src="{PICTURES_DATA_PATH}/images/edit_index.png"/>
										{L_EDIT_INDEX}
									</a></li>
								# ENDIF #
							# ENDIF #
							# IF NOT C_INDEX_PAGE #
								# IF C_EDIT #
								<li><a href="{U_EDIT}" title="{L_EDIT}">
									<img src="{PICTURES_DATA_PATH}/images/edit.png"/>
									{L_EDIT}
								</a></li>
								# ENDIF #
								# IF C_DELETE #
								<li><a href="{U_DELETE}" title="{L_DELETE}" data-confirmation="delete-element">
									<img src="{PICTURES_DATA_PATH}/images/delete.png"/>
									{L_DELETE}
								</a></li>
								# ENDIF #
								# IF C_RENAME #
								<li><a href="{U_RENAME}" title="{L_RENAME}">
									<img src="{PICTURES_DATA_PATH}/images/rename.png"/>
									{L_RENAME}
								</a></li>
								# ENDIF #
								# IF C_REDIRECT #
								<li><a href="{U_REDIRECT}" title="{L_REDIRECT}">
									<img src="{PICTURES_DATA_PATH}/images/redirect.png"/>
									{L_REDIRECT}
								</a></li>
								# ENDIF #
								# IF C_MOVE #
								<li><a href="{U_MOVE}" title="{L_MOVE}">
									<i class="icon-forward"></i>
									{L_MOVE}
								</a></li>
								# ENDIF #
								# IF C_ADD_ARTICLE #
								<li><a href="{U_ADD_ARTICLE}" title="{L_ADD_ARTICLE}">
									<img src="{PICTURES_DATA_PATH}/images/create_article.png"/>
									{L_ADD_ARTICLE}
								</a></li>
								# ENDIF #
								# IF C_ADD_CAT #
								<li><a href="{U_ADD_CAT}" title="{L_ADD_CAT}">
									<img src="{PICTURES_DATA_PATH}/images/add_cat.png"/>
									{L_ADD_CAT}
								</a></li>
								# ENDIF #
								# IF C_STATUS #
								<li><a href="{U_STATUS}" title="{L_STATUS}">
									<img src="{PICTURES_DATA_PATH}/images/article_status.png"/>
									{L_STATUS}
								</a></li>
								# ENDIF #
								# IF C_RESTRICTION #
								<li><a href="{U_RESTRICTION}" title="{L_RESTRICTION}">
									<img src="{PICTURES_DATA_PATH}/images/restriction_level.png"/>
									{L_RESTRICTION}
								</a></li>
								# ENDIF #
								<li><a href="{U_PRINT}" title="{L_PRINT}">
									<img src="{PICTURES_DATA_PATH}/images/print_mini.png"/>
									{L_PRINT}
								</a></li>
							# ENDIF #
						</ul>
					</li>
					<li>
						<a><i class="icon-edit-sign"></i> {L_CONTRIBUTION_TOOLS}</a>
						<ul>
							# IF C_ADD_ARTICLE #
							<li><a href="{U_ADD_ARTICLE}" title="{L_ADD_ARTICLE}">
								<img src="{PICTURES_DATA_PATH}/images/create_article.png"/>
								{L_ADD_ARTICLE}
							</a></li>
							# ENDIF #
							# IF C_ADD_CAT #
							<li><a href="{U_ADD_CAT}" title="{L_ADD_CAT}">
								<img src="{PICTURES_DATA_PATH}/images/add_cat.png"/>
								{L_ADD_CAT}
							</a></li>
							# ENDIF #
							<li><a href="{U_RANDOM}" title="{L_RANDOM}">
								<img src="{PICTURES_DATA_PATH}/images/random_page.png"/>
								{L_RANDOM}
							</a></li>
							<li><a href="{U_SEARCH}" title="{L_SEARCH}">
								<img src="{PICTURES_DATA_PATH}/images/search.png"/>
								{L_SEARCH}
							</a></li>
							# IF IS_USER_CONNECTED #
							<li><a href="{U_FOLLOWED}" title="{L_FOLLOWED}">
								<img src="{PICTURES_DATA_PATH}/images/followed-articles.png"/>
								{L_FOLLOWED}
							</a></li>
								# IF NOT C_INDEX_PAGE #
								<li><a href="{U_WATCH}" title="{L_WATCH}">
									<img src="{PICTURES_DATA_PATH}/images/follow-article.png"/>
									{L_WATCH}
								</a></li>
								# ENDIF #
							# ENDIF #
							<li><a href="{U_EXPLORE}" title="{L_EXPLORE}">
								<img src="{PICTURES_DATA_PATH}/images/explorer.png"/>
								{L_EXPLORE}
							</a></li>
							<li><a href="{U_RSS}" title="{L_RSS}">
								<img src="{PICTURES_DATA_PATH}/images/rss.png"/>
								{L_RSS}
							</a></li>
						</ul>
					</li>
				</ul>
			</menu>
		</div>
		<div  class="spacer" style="margin-top:15px;">&nbsp;</div>