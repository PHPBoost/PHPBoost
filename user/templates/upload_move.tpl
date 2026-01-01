{HEADER}
<script>
	function close_popup() {
		opener=self;
		self.close();
	}
</script>
<section id="module-user-upload-move" class="content">
	<header class="section-header">
		<h1>{@upload.files.management}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="upload-address-bar">
				<a class="offload"  href="upload.php?root=1{POPUP}"><i class="fa fa-home" aria-hidden="true"></i> {@common.root}</a>{U_FOLDER}
			</div>
			# INCLUDE MESSAGE_HELPER #
			<form action="{TARGET}" method="post">
				<div class="cell-flex cell-inline cell-tile">
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
							<span class="text-strong">{@common.move.to}</span>
							<i class="fa fa-arrow-right" aria-hidden="true"></i>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<script src="{PATH_TO_ROOT}/templates/__default__/plugins/upload# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
							<script>
								var path = '{PATH_TO_ROOT}/templates/{THEME}';
								var selected_cat = {SELECTED_CAT};
							</script>
							<span class="upload-root-cat"><a href="javascript:select_cat(0);"><i class="fa fa-home" aria-hidden="true"></i> <span id="class-0" class="{CAT_0}">{@common.root}</span></a></span>
							{FOLDERS}
						</div>
					</div>
				</div>
				<div class="spacer"></div>
				<fieldset class="fieldset-submit">
					<input type="hidden" name="new_cat" id="id_cat" value="{SELECTED_CAT}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" value="true" name="valid" class="button submit">{@form.submit}</button>
				</fieldset>
			</form>

		</div>
	</div>
	<footer></footer>
</section>
{FOOTER}
