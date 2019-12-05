

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_FILES_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="admin_files.php" class="quick-link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(AdminFilesUrlBuilder::configuration())}" class="quick-link">{L_CONFIG_FILES}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">
			<fieldset>
				<legend>{L_FILES_MANAGEMENT}</legend>
				<div class="fieldset-inset">
					<div class="upload-address-bar">
						<a href="admin_files.php"><i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}</a>{URL}
					</div>

					# INCLUDE message_helper #
					<form action="{TARGET}" method="post">
						<div class="upload-elements-container">
							# START folder #
								<div class="file-move-container">
									<i class="fa fa-folder fa-2x" aria-hidden="true"></i> {folder.NAME}
								</div>
							# END folder #

							# START file #
								<div class="file-move-container">
									# IF file.C_DISPLAY_REAL_IMG #
										<img src="{PATH_TO_ROOT}/upload/{file.FILE_ICON}" alt="{file.NAME}" />
									# ELSE #
										<i class="{file.FILE_ICON}" aria-hidden="true"></i>
									# ENDIF #
									{file.NAME}
									<span class="smaller">{file.FILETYPE}</span><br />
									<span class="smaller">{file.SIZE}</span><br />
								</div>
							# END file #

							<div class="file-move-container">
								<strong>{L_MOVE_TO}</strong>
								<br />
								<i class="fa fa-arrow-right fa-2x" aria-hidden="true"></i>
							</div>
							<div class="file-move-container">
									<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/upload.js"></script>
									<script>
										var path = '{PATH_TO_ROOT}/templates/{THEME}';
										var selected_cat = {SELECTED_CAT};
									</script>
									<span><a href="javascript:select_cat(0);"><i class="fa fa-home" aria-hidden="true"></i> <span id="class-0" class="{CAT_0}">{L_ROOT}</span></a></span>
									<br />
									{FOLDERS}
							</div>
						</div>
						<div class="spacer"></div>
						<fieldset class="fieldset-submit">
							<input type="hidden" name="new_cat" id="id_cat" value="{SELECTED_CAT}">
							<button type="submit" class="button submit" value="true" name="valid">{L_SUBMIT}</button>
							<input type="hidden" name="token" value="{TOKEN}">
						</fieldset>
					</form>
				</div>
			</fieldset>
		</div>
