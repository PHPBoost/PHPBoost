		# IF C_DOWNLOAD_CAT #
		<menu class="dynamic_menu right">
			<ul>
				<li><a><i class="icon-cog"></i></a>
					<ul>
						# IF C_ADD_FILE #
						<li>
							<a href="{U_ADD_FILE}" title="{L_ADD_FILE}">
								{L_ADD_FILE}
							</a>
						</li>
						# ENDIF #
						# IF IS_ADMIN #
						<li>
							<a href="{U_MANAGE_FILES}" title="{L_MANAGE_FILES}">{L_MANAGE_FILES}</a>
						<li>
						# ENDIF #
					</ul>
				</li>
			</ul>
		</menu>
		
		<section>		
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('download',IDCAT))}" class="pbt-icon-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					{TITLE}
					# IF C_ADMIN #
					<a href="{U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
					# END IF #
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
								# IF row.list_cats.C_CAT_IMG #
									<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" /></a>
									<br />
								# ENDIF #
								<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
								
								# IF C_ADMIN #
								<a href="{row.list_cats.U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
								# ENDIF #
								<div class="smaller">
									{row.list_cats.NUM_FILES}
								</div>
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr style="margin-bottom:25px;" />
				# ENDIF #
				
				# IF C_FILES #
					
					<div style="float:right;" class="row3" id="form">
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
							<option value="size"{SELECTED_SIZE}>{L_SIZE}</option>
							<option value="date"{SELECTED_DATE}>{L_DATE}</option>
							<option value="hits"{SELECTED_HITS}>{L_POPULARITY}</option>
							<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
						</select>
						<select name="mode" id="mode" class="nav" onchange="change_order()">
							<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
							<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
						</select>
					</div>
					<div class="spacer">&nbsp;</div>
					
					# START file #
						<article class="block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<header>
								<h1>
									<a href="{file.U_DOWNLOAD_LINK}" itemprop="name">{file.NAME}</a>
									# IF C_ADMIN #
										<a href="{file.U_ADMIN_EDIT_FILE}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
										<a href="{file.U_ADMIN_DELETE_FILE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
									# ENDIF #
								</h1>
							</header>
							<div class="contents">
								# IF file.C_IMG #
									<div class="float_right">
										<a href="{file.U_DOWNLOAD_LINK}">
											<img src="{file.IMG}" alt="{file.IMG_NAME}" itemprop="image" />
										</a>
									</div>
								# ENDIF #
								# IF file.C_DESCRIPTION #
									<p itemprop="text">
									{file.DESCRIPTION}
									</p>
								# ENDIF #
								<div class="smaller">
									<span itemprop="dateCreated"> 
										{file.DATE} 
									<span> 
									<br />
									{file.COUNT_DL}
									<br />
									{file.U_COM_LINK}
									<br />
									{L_NOTE} {file.NOTE}
								</div>
								<div class="spacer"></div>								
							</div>
							<footer></footer>
						</article>						
					# END file #
					<div style="text-align:center;">{PAGINATION}</div>
				# ENDIF #
				
				# IF C_NO_FILE #
					<div class="notice">
						{L_NO_FILE_THIS_CATEGORY}
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<footer></footer>
		</section>
		# ENDIF #
		
		# IF C_DISPLAY_DOWNLOAD #			
		<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">					
			<header>
				<h1 itemprop="name">
					{NAME}
					<span class="actions">
						{U_COM}
						# IF C_EDIT_AUTH #
							<a href="{U_EDIT_FILE}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
							<a href="{U_DELETE_FILE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
				</h1>
				
			</header>
			<div class="content">
				<table>
					<tr>
						<td style="text-align:center;padding-right:20px;vertical-align:top;">
							# IF C_IMG #
								<img src="{U_IMG}" alt="{IMAGE_ALT}" itemprop="image"/>
								<br /><br />
							# ENDIF #
							<a href="{U_DOWNLOAD_FILE}">
								<img src="{PICTURES_DATA_PATH}/images/download_file.png" alt="" />
							</a>
							<p><a href="{U_DOWNLOAD_FILE}">{L_DOWNLOAD_FILE}</a></p>
							# IF IS_USER_CONNECTED #
							<a href="{U_DEADLINK}">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/notice.png" alt="" />
							</a>
							<p><a href="{U_DEADLINK}">{L_DEADLINK}</a></p>
							# ENDIF #
						</td>
						<td>
							<span itemprop="text">
								{CONTENTS}
							</span>
						</td>
					</tr>
				</table>
				<br />
				<table style="width:430px;margin-right:0;" class="module_table text_small">
					<tr>
						<th colspan="2">
							{L_FILE_INFOS}
						</th>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_SIZE}
						</td>
						<td class="row2" style="padding:3px">
							{SIZE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_INSERTION_DATE}
						</td>
						<td class="row2" style="padding:3px" itemprop="dateCreated">
							{CREATION_DATE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_RELEASE_DATE}
						</td>
						<td class="row2" style="padding:3px" itemprop="dateModified">
							{RELEASE_DATE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_DOWNLOADED}
						</td>
						<td class="row2" style="padding:3px">
							{HITS}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_NOTE} <em><span id="nbrnote{ID_FILE}">({NUM_NOTES})</span></em>
						</td>
						<td class="row2" style="padding:1px">
							{KERNEL_NOTATION}
						</td>
					</tr>
				</table>
				{COMMENTS}
			</div>
			<footer></footer>
		</article>
		# ENDIF #
		