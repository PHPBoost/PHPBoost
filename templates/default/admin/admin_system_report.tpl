		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_SERVER}</li>
				<li>
					<a href="admin_phpinfo.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/phpinfo.png" alt="" /></a>
					<br />
					<a href="admin_phpinfo.php" class="quick_link">{L_PHPINFO}</a>
				</li>
				<li>
					<a href="admin_system_report.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/system_report.png" alt="" /></a>
					<br />
					<a href="admin_system_report.php" class="quick_link">{L_SYSTEM_REPORT}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form class="fieldset-content" action="">
				<fieldset>
					<legend>
						{L_SERVER}
					</legend>
					<div class="form-element">
						<label>{L_PHP_VERSION}</label>
						<div class="form-field">{PHP_VERSION}</div>
					</div>
					<div class="form-element">
						<label>{L_DBMS_VERSION}</label>
						<div class="form-field">{DBMS_VERSION}</div>
					</div>
					<div class="form-element">
						<label>{L_GD_LIBRARY}</label>
						<div class="form-field">
							# IF C_SERVER_GD_LIBRARY #
							<i class="fa fa-success fa-2x" title="{L_YES}"></i>
							# ELSE #
							<i class="fa fa-error fa-2x" title="{L_NO}"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{L_URL_REWRITING}</label>
						<div class="form-field">
							# IF C_URL_REWRITING_KNOWN #
								# IF C_SERVER_URL_REWRITING #
								<i class="fa fa-success fa-2x" title="{L_YES}"></i>
								# ELSE #
								<i class="fa fa-error fa-2x" title="{L_NO}"></i>
								# ENDIF #
							# ELSE #
							<i class="fa fa-question fa-2x" title="{L_UNKNOWN}"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{L_REGISTER_GLOBALS_OPTION}</label>
						<div class="form-field">
							# IF C_REGISTER_GLOBALS #
							<i class="fa fa-success fa-2x" title="{L_YES}"></i>
							# ELSE #
							<i class="fa fa-error fa-2x" title="{L_NO}"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{L_SERVER_URL}</label>
						<div class="form-field">{SERV_SERV_URL}</div>
					</div>
					<div class="form-element">
						<label>{L_SITE_PATH}</label>
						<div class="form-field">{SERV_SITE_PATH}</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						{L_PHPBOOST_CONFIG}
					</legend>
					<div class="form-element">
						<label>{L_KERNEL_VERSION}</label>
						<div class="form-field">{KERNEL_VERSION}</div>
					</div>
					<div class="form-element">
						<label>{L_SERVER_URL}</label>
						<div class="form-field">{KERNEL_SERV_URL}</div>
					</div>
					<div class="form-element">
						<label>{L_SITE_PATH}</label>
						<div class="form-field">{KERNEL_SITE_PATH}</div>
					</div>
					<div class="form-element">
						<label>{L_DEFAULT_THEME}</label>
						<div class="form-field">{KERNEL_DEFAULT_THEME}</div>
					</div>
					<div class="form-element">
						<label>{L_DEFAULT_LANG}</label>
						<div class="form-field">{KERNEL_DEFAULT_LANGUAGE}</div>
					</div>
					<div class="form-element">
						<label>{L_DEFAULT_EDITOR}</label>
						<div class="form-field">{KERNEL_DEFAULT_EDITOR}</div>
					</div>
					<div class="form-element">
						<label>{L_START_PAGE}</label>
						<div class="form-field">{KERNEL_START_PAGE}</div>
					</div>
					<div class="form-element">
						<label>{L_URL_REWRITING}</label>
						<div class="form-field">
							# IF C_KERNEL_URL_REWRITING #
							<i class="fa fa-success fa-2x" title="{L_YES}"></i>
							# ELSE #
							<i class="fa fa-error fa-2x" title="{L_NO}"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{L_OUTPUT_GZ}</label>
						<div class="form-field">
							# IF C_KERNEL_OUTPUT_GZ #
							<i class="fa fa-success fa-2x" title="{L_YES}"></i>
							# ELSE #
							<i class="fa fa-error fa-2x" title="{L_NO}"></i>
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						<label>{L_COOKIE_NAME}</label>
						<div class="form-field">{COOKIE_NAME}</div>
					</div>
					<div class="form-element">
						<label>{L_SESSION_LENGTH}</label>
						<div class="form-field">{SESSION_LENGTH}</div>
					</div>
					<div class="form-element">
						<label>{L_SESSION_GUEST_LENGTH}</label>
						<div class="form-field">{SESSION_LENGTH_GUEST}</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{L_DIRECTORIES_AUTH}</legend>
					# START directories #
					<div class="form-element">
						<label>{directories.NAME}</label>
						<div class="form-field">
							# IF directories.C_AUTH_DIR #
							<i class="fa fa-success fa-2x" title="{L_YES}"></i>
							# ELSE #
							<i class="fa fa-error fa-2x" title="{L_NO}"></i>
							# ENDIF #
						</div>
					</div>
					# END directories #
				</fieldset>
				<fieldset>
					<legend>{L_SUMMERIZATION}</legend>
					<p>{L_SUMMERIZATION_EXPLAIN}</p>
					<textarea style="font-family:Courier new; width:53em;" rows="20" cols="15">{SUMMERIZATION}</textarea>
				</fieldset>
			</form>
		</div>
		