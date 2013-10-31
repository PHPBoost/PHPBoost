		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_SERVER}</li>
				<li>
					<a href="admin_phpinfo.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/phpinfo.png" alt="" /></a>
					<br />
					<a href="admin_phpinfo.php" class="quick_link">{L_PHPINFO}</a>
				</li>
				<li>
					<a href="admin_system_report.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/system_report.png" alt="" /></a>
					<br />
					<a href="admin_system_report.php" class="quick_link">{L_SYSTEM_REPORT}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form class="fieldset_content" action="">
				<fieldset>
					<legend>
						{L_SERVER}
					</legend>
					<div class="form-element">
						{L_PHP_VERSION}
						<div class="form-field">{PHP_VERSION}</div>
					</div>
					<div class="form-element">
						{L_DBMS_VERSION}
						<div class="form-field">{DBMS_VERSION}</div>
					</div>
					<div class="form-element">
						{L_GD_LIBRARY}
						<div class="form-field">
							# IF C_SERVER_GD_LIBRARY #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						{L_URL_REWRITING}
						<div class="form-field">
							# IF C_URL_REWRITING_KNOWN #
								# IF C_SERVER_URL_REWRITING #
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
								# ELSE #
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
								# ENDIF #
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/question.png" alt="{L_UNKNOWN}" />
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						{L_REGISTER_GLOBALS_OPTION}
						<div class="form-field">
							# IF C_REGISTER_GLOBALS #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						{L_SERVER_URL}
						<div class="form-field">{SERV_SERV_URL}</div>
					</div>
					<div class="form-element">
						{L_SITE_PATH}
						<div class="form-field">{SERV_SITE_PATH}</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>
						{L_PHPBOOST_CONFIG}
					</legend>
					<div class="form-element">
						{L_KERNEL_VERSION}
						<div class="form-field">{KERNEL_VERSION}</div>
					</div>
					<div class="form-element">
						{L_SERVER_URL}
						<div class="form-field">{KERNEL_SERV_URL}</div>
					</div>
					<div class="form-element">
						{L_SITE_PATH}
						<div class="form-field">{KERNEL_SITE_PATH}</div>
					</div>
					<div class="form-element">
						{L_DEFAULT_THEME}
						<div class="form-field">{KERNEL_DEFAULT_THEME}</div>
					</div>
					<div class="form-element">
						{L_DEFAULT_LANG}
						<div class="form-field">{KERNEL_DEFAULT_LANGUAGE}</div>
					</div>
					<div class="form-element">
						{L_DEFAULT_EDITOR}
						<div class="form-field">{KERNEL_DEFAULT_EDITOR}</div>
					</div>
					<div class="form-element">
						{L_START_PAGE}
						<div class="form-field">{KERNEL_START_PAGE}</div>
					</div>
					<div class="form-element">
						{L_URL_REWRITING}
						<div class="form-field">
							# IF C_KERNEL_URL_REWRITING #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						{L_OUTPUT_GZ}
						<div class="form-field">
							# IF C_KERNEL_OUTPUT_GZ #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</div>
					</div>
					<div class="form-element">
						{L_COOKIE_NAME}
						<div class="form-field">{COOKIE_NAME}</div>
					</div>
					<div class="form-element">
						{L_SESSION_LENGTH}
						<div class="form-field">{SESSION_LENGTH}</div>
					</div>
					<div class="form-element">
						{L_SESSION_GUEST_LENGTH}
						<div class="form-field">{SESSION_LENGTH_GUEST}</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{L_DIRECTORIES_AUTH}</legend>
					# START directories #
					<div class="form-element">
						{directories.NAME}
						<div class="form-field">
							# IF directories.C_AUTH_DIR #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/stop.png" alt="{L_NO}" />
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
		