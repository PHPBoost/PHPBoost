		# IF C_CATEGORIES #
			<menu class="dynamic_menu right">
				<ul>
					<li><a><i class="icon-cog"></i></a>
						<ul>
							# IF C_ADD_FILE #
							<li>
								<a href="{U_ADD_FILE}" title="{L_ADD_FILE}">{L_ADD_FILE}</a>
							</li>
							# ENDIF #
							# IF C_MODO #
							<li>
								<a href="{PATH_TO_ROOT}/media/moderation_media.php" title="{L_MODO_PANEL}">
									{L_MODO_PANEL}
								</a>
							</li>
							# END IF #
							# IF C_ADMIN #
								# IF C_CAT #
								<li>
									<a href="{U_ADMIN_CAT}" title="{L_ADMIN_CAT}">{L_ADMIN_CAT}</a>
								</li>
								# ENDIF #
							<li>
								<a href="{U_CONFIG}" title="${LangLoader::get_message('configuration', 'admin')}">${LangLoader::get_message('configuration', 'admin')}</a>
							</li>
							# END IF #
						</ul>
					</li>
				</ul>
			</menu>
			
			<div class="spacer"></div>
			
			<section>
				<header>
					<h1>
						<a href="${relative_url(SyndicationUrlBuilder::rss('media', ID_CAT))}" class="icon-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
						{TITLE}
					</h1>
				</header>
				<div class="content">
					# IF C_DESCRIPTION #
						{DESCRIPTION}
						<hr style="margin-top:25px;" />
					# ENDIF #
	
					# IF C_SUB_CATS #
						# START row #
							# START row.list_cats #
								<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
									<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}">
										<img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" />
									</a>
									<br />
									<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
									# IF C_ADMIN #
									<a href="{row.list_cats.U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
									# ENDIF #
									# IF row.list_cats.NUM_MEDIA #
									<div class="smaller">
										{row.list_cats.NUM_MEDIA}
									</div>
									# ENDIF #
								</div>
							# END row.list_cats #
							<div class="spacer">&nbsp;</div>
						# END row #
						<hr />
					# ENDIF #
	
					# IF C_FILES #
						<div class="options" id="form">
							<script type="text/javascript">
							<!--
							function change_order()
							{
								window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
							}
							-->
							</script>
							{L_ORDER_BY}
							<select name="sort" id="sort" class="nav" onchange="change_order()">
								<option value="alpha"{SELECTED_ALPHA}>{L_ALPHA}</option>
								<option value="date"{SELECTED_DATE}>{L_DATE}</option>
								<option value="nbr"{SELECTED_NBR}>{L_NBR}</option>
								<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
								<option value="com"{SELECTED_COM}>{L_COM}</option>
							</select>
							<select name="mode" id="mode" class="nav" onchange="change_order()">
								<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
								<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
							</select>
						</div>
						<div class="spacer">&nbsp;</div>
	
						# START file #
							<article class="block">
								<header>
									<h1>
										<a href="{file.U_MEDIA_LINK}">{file.NAME}</a>
										# IF C_MODO #
										<span class="actions">
											<a href="{file.U_ADMIN_UNVISIBLE_MEDIA}" class="icon-eye-slash" title="{L_UNAPROBED}"></a>
											<a href="{file.U_ADMIN_EDIT_MEDIA}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
											<a href="{file.U_ADMIN_DELETE_MEDIA}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
										</span>
										# ENDIF #
									</h1>
								</header>
								<div class="content">
									# IF A_DESC #
									# IF file.C_DESCRIPTION #
										<p style="margin-top:10px">
										{file.DESCRIPTION}
										<br />
										</p>
									# ENDIF #
									# ELSE #
										<br />
									# ENDIF #
									# IF A_BLOCK #
									<div class="smaller">
										# IF A_DATE #
										{file.DATE}
										<br />
										# ENDIF #
										# IF A_USER #
										{file.POSTER}
										<br />
										# ENDIF #
										# IF A_COUNTER #
										{file.COUNT}
										<br />
										# ENDIF #
										# IF A_COM #
										{file.U_COM_LINK}
										<br />
										# ENDIF #
										# IF A_NOTE #
										{L_NOTE} {file.NOTE}
										# ENDIF #
									</div>
									# ENDIF #
								</div>
								<footer></footer>
							</article>
						# END file #
					# ENDIF #
	
					# IF C_NO_FILE #
						<div class="notice">
							{L_NO_FILE_THIS_CATEGORY}
						</div>
					# ENDIF #
				</div>
				<footer>{PAGINATION}</footer>
			</section>
		# ENDIF #

		# IF C_DISPLAY_MEDIA #
		<article>
			<header>
				<h1>
					{NAME} 
					<span class="actions">
						# IF A_COM #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" />
							{U_COM}
						# ENDIF #
						# IF C_MODO #
							<a href="{U_UNVISIBLE_MEDIA}" class="icon-eye-slash" title="{L_UNAPROBED}"></a>
							<a href="{U_EDIT_MEDIA}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
							<a href="{U_DELETE_MEDIA}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
				</h1>
			</header>
			<div class="content">
				# IF A_DESC #
				<p class="text_justify" style="margin-top:15px">
					{CONTENTS}
				</p>
				# ENDIF #
				<p class="center" style="margin-top:25px;margin-bottom:25px;">
					# INCLUDE media_format #
				</p>
				# IF C_DISPLAY #
				<table style="width:430px;float:right;margin-top:40px;" class="module_table text_small">
					<tr>
						<th colspan="2">
							{L_MEDIA_INFOS}
						</th>
					</tr>
					# IF A_DATE #
					<tr>
						<td class="row1" style="padding:3px">
							{L_DATE}
						</td>
						<td class="row2" style="padding:3px">
							{DATE}
						</td>
					</tr>
					# ENDIF #
					# IF A_USER #
					<tr>
						<td class="row1" style="padding:3px">
							{L_BY}
						</td>
						<td class="row2" style="padding:3px">
							{BY}
						</td>
					</tr>
					# ENDIF #
					# IF A_COUNTER #
					<tr>
						<td class="row1" style="padding:3px">
							{L_VIEWED}
						</td>
						<td class="row2" style="padding:3px">
							{HITS}
						</td>
					</tr>
					# ENDIF #
					# IF A_NOTE #
					<tr>
						<td class="row1" style="padding:3px">
							{L_NOTE} <em><span id="nbrnote{ID_MEDIA}">({NUM_NOTES})</span></em>
						</td>
						<td class="row2" style="padding:1px">
							{KERNEL_NOTATION}
						</td>
					</tr>
					# ENDIF #
				</table>
				<div class="spacer"></div>
				# ENDIF #
				
				{COMMENTS}
			</div>
			<footer></footer>
		</article>
		# ENDIF #