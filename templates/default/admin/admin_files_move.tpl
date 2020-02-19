

		<nav id="admin-quick-menu">
			<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
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
						<div class="cell-flex cell-tile cell-columns-3">
							# START folder #
								<div class="cell">
									<div class="cell-body no-style">
										<div class="cell-content">
											<i class="fa fa-folder" aria-hidden="true"></i> {folder.NAME}
										</div>
									</div>
								</div>
							# END folder #

							# START file #
								<div class="cell">
									<div class="cell-header">
										<div class="cell-name">{file.NAME}</div>
										# IF NOT file.C_ENABLED_THUMBNAILS #
											<i class="{file.FILE_ICON}" aria-hidden="true"></i>
										# ENDIF #
									</div>
									# IF file.C_ENABLED_THUMBNAILS #
										<div class="cell-body">
											<div class="cell-thumbnail cell-landscape cell-center">
												# IF file.C_REAL_IMG #
													<img src="{PATH_TO_ROOT}/upload/{file.FILE_ICON}" alt="{file.NAME}" />
												# ELSE #
													<i class="{file.FILE_ICON} fa-4x" aria-hidden="true"></i>
												# ENDIF #
											</div>
										</div>
									# ENDIF #
									<div class="cell-list">
										<ul>
											<li class="li-stretch">
												<span>{file.FILETYPE}</span>
												<span>{file.SIZE}</span>
											</li>
										</ul>
									</div>
								</div>
							# END file #

							<div class="cell">
								<div class="cell-infos no-style">
									<span class="text-strong">{L_MOVE_TO}</span>
									<i class="fa fa-arrow-right" aria-hidden="true"></i>
								</div>
							</div>
							<div class="cell">
								<div class="cell-body">
									<div class="cell-content">
										<script src="{PATH_TO_ROOT}/templates/default/plugins/upload.js"></script>
										<script>
											var path = '{PATH_TO_ROOT}/templates/{THEME}';
											var selected_cat = {SELECTED_CAT};
										</script>
										<span><a href="javascript:select_cat(0);"><i class="fa fa-home" aria-hidden="true"></i> <span id="class-0" class="{CAT_0}">{L_ROOT}</span></a></span>
										{FOLDERS}
									</div>
								</div>
							</div>
						</div>
						<div class="spacer"></div>
						<fieldset class="fieldset-submit">
							<input type="hidden" name="new_cat" id="id_cat" value="{SELECTED_CAT}">
							<button type="submit" class="button submit" value name="valid">{L_SUBMIT}</button>
							<input type="hidden" name="token" value="{TOKEN}">
						</fieldset>
					</form>
				</div>
			</fieldset>
		</div>
