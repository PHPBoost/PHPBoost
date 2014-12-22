		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FILES_MANAGEMENT}</li>
				<li>
					<a href="admin_files.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick-link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files_config.php" class="quick-link">{L_CONFIG_FILES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			<section>
				<header><h1>{L_FILES_MANAGEMENT}</h1></header>
				<div class="content">
					<div class="upload-address-bar">
						<a href="admin_files.php"><i class="fa fa-home"></i> {L_ROOT}</a>{URL}
					</div>
					
					# INCLUDE message_helper #
					<form action="{TARGET}" method="post">
						<div class="upload-elements-container">
							# START folder #
								<div style="float:left;width:33%;text-align:center;">
									<i class="fa fa-folder fa-2x"></i> {folder.NAME}
								</div>	
							# END folder #
									
							# START file #
								<div style="float:left;width:33%;text-align:center;">
									# IF file.C_DISPLAY_REAL_IMG #
										<img src="{PATH_TO_ROOT}/upload/{file.FILE_ICON}" alt="" style="width:100px;height:auto;" />
									# ELSE #
										<img src="{PATH_TO_ROOT}/templates/default/images/upload/{file.FILE_ICON}" alt="" />
									# ENDIF #
									{file.NAME}
									<span class="smaller">{file.FILETYPE}</span><br />
									<span class="smaller">{file.SIZE}</span><br />
								</div>
							# END file #
							
							<div style="float:left;width:33%;text-align:center;">
								<strong>{L_MOVE_TO}</strong>
								<br />
								<i class="fa fa-arrow-right fa-2x"></i>
							</div>
							<div style="float:left;width:33%;text-align:center;">
									<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/upload.js"></script>
									<script>
									<!--
										var path = '{PATH_TO_ROOT}/templates/{THEME}';
										var selected_cat = {SELECTED_CAT};
									-->
									</script>
									<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><i class="fa fa-home"></i> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
									<br />
									{FOLDERS}
							</div>
						</div>
						<div class="spacer"></div>
						<fieldset class="fieldset-submit">
							<input type="hidden" name="new_cat" id="id_cat" value="{SELECTED_CAT}">
							<button type="submit" class="submit" value="true" name="valid">{L_SUBMIT}</button>
						</fieldset>
					</form>
				</div>
				<footer></footer>				
			</section>
		</div>
		