		<script>
		<!--
			function img_change(id, url, img)
			{
				if (document.getElementById(id))
					document.getElementById(id).src = url + img;
			}
		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_FORUM_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/forum" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_ranks.php" class="quick-link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php" class="quick-link">{L_FORUM_ADD_RANKS}</a>
				</li>
				<li>
					<a href="${relative_url(ForumUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			# INCLUDE message_helper #

			<form action="admin_ranks.php" method="post">
				<fieldset>
					<legend><h1>{L_FORUM_RANKS_MANAGEMENT}</h1></legend>
					<div class="fieldset-inset">
						<table class="table">
							<thead>
								<tr>
									<th>
										{L_RANK_NAME}
									</th>
									<th>
										{L_NBR_MSG}
									</th>
									<th>
										{L_IMG_ASSOC}
									</th>
									<th>
										{L_DELETE}
									</th>
								</tr>
							</thead>
							<tbody>
								# START rank #
									<tr>
										<td>
											<input type="text" maxlength="30" name="{rank.ID}name" value="{rank.RANK}">
										</td>
										<td>
											# IF rank.C_SPECIAL_RANK #<input type="number" min="0" name="{rank.ID}msg" value="{rank.MSG}"># ELSE #{rank.L_SPECIAL_RANK}# ENDIF #
										</td>
										<td>
											<select name="{rank.ID}icon" onchange="img_change('icon{rank.ID}', '{rank.JS_PATH_RANKS}', this.options[selectedIndex].value)">
												{rank.RANK_OPTIONS}
											</select>
											# IF rank.IMG_RANK #
												<span class="field-description">
													<img src="{rank.U_IMG_RANK}" id="icon{rank.ID}" alt="{rank.IMG_RANK}" />
												</span>
											# ENDIF #
										</td>
										<td>
											# IF rank.C_SPECIAL_RANK #<a href="{rank.U_DELETE}" class="far fa-trash-alt" data-confirmation="delete-element"></a># ELSE #{rank.L_SPECIAL_RANK}# ENDIF #
										</td>
									</tr>
								# END rank #
							</tbody>
						</table>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
						<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
		</div>
