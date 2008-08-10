		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_SYSTEM_REPORT}</li>
				<li>
					<a href="admin_system_report.php"><img src="../templates/{THEME}/images/admin/system_report.png" alt="" /></a>
					<br />
					<a href="admin_system_report.php" class="quick_link">{L_SYSTEM_REPORT}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<form class="fieldset_content">
				<fieldset>
					<legend>
						{L_SERVER}
					</legend>
					<dl>
						<dt>{L_PHP_VERSION}</dt>
						<dd>{PHP_VERSION}</dd>
					</dl>
					<dl>
						<dt>{L_DBMS_VERSION}</dt>
						<dd>{DBMS_VERSION}</dd>
					</dl>
					<dl>
						<dt>{L_GD_LIBRARY}</dt>
						<dd>
							# IF C_SERVER_GD_LIBRARY #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_URL_REWRITING}</dt>
						<dd>
							# IF C_URL_REWRITING_KNOWN #
								# IF C_SERVER_URL_REWRITING #
								<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
								# ELSE #
								<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
								# ENDIF #
							# ELSE #
							<img src="../templates/{THEME}/images/question.png" alt="{L_UNKNOWN}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_REGISTER_GLOBALS_OPTION}</dt>
						<dd>
							# IF C_REGISTER_GLOBALS #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_SERVER_URL}</dt>
						<dd>{SERV_SERV_URL}</dd>
					</dl>
					<dl>
						<dt>{L_SITE_PATH}</dt>
						<dd>{SERV_SITE_PATH}</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>
						{L_PHPBOOST_CONFIG}
					</legend>
					<dl>
						<dt>{L_KERNEL_VERSION}</dt>
						<dd>{KERNEL_VERSION}</dd>
					</dl>
					<dl>
						<dt>{L_SERVER_URL}</dt>
						<dd>{KERNEL_SERV_URL}</dd>
					</dl>
					<dl>
						<dt>{L_SITE_PATH}</dt>
						<dd>{KERNEL_SITE_PATH}</dd>
					</dl>
					<dl>
						<dt>{L_DEFAULT_THEME}</dt>
						<dd>{KERNEL_DEFAULT_THEME}</dd>
					</dl>
					<dl>
						<dt>{L_DEFAULT_LANG}</dt>
						<dd>{KERNEL_DEFAULT_LANGUAGE}</dd>
					</dl>
					<dl>
						<dt>{L_DEFAULT_EDITOR}</dt>
						<dd>{KERNEL_DEFAULT_EDITOR}</dd>
					</dl>
					<dl>
						<dt>{L_START_PAGE}</dt>
						<dd>{KERNEL_START_PAGE}</dd>
					</dl>
					<dl>
						<dt>{L_URL_REWRITING}</dt>
						<dd>
							# IF C_KERNEL_URL_REWRITING #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_OUTPUT_GZ}</dt>
						<dd>
							# IF C_KERNEL_OUTPUT_GZ #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_COOKIE_NAME}</dt>
						<dd>{COOKIE_NAME}</dd>
					</dl>
					<dl>
						<dt>{L_SESSION_LENGTH}</dt>
						<dd>{SESSION_LENGTH}</dd>
					</dl>
					<dl>
						<dt>{L_SESSION_GUEST_LENGTH}</dt>
						<dd>{SESSION_LENGTH_GUEST}</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>{L_DIRECTORIES_AUTH}</legend>
					<dl>
						<dt>cache</dt>
						<dd>
							# IF C_AUTH_DIR_CACHE #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>upload</dt>
						<dd>
							# IF C_AUTH_DIR_UPLOAD #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>kernel</dt>
						<dd>
							# IF C_AUTH_DIR_KERNEL #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>kernel/auth</dt>
						<dd>
							# IF C_AUTH_DIR_KERNEL_AUTH #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>menus</dt>
						<dd>
							# IF C_AUTH_DIR_MENUS #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
					<dl>
						<dt>{L_ROOT}</dt>
						<dd>
							# IF C_AUTH_DIR_ROOT #
							<img src="../templates/{THEME}/images/success.png" alt="{L_YES}" />
							# ELSE #
							<img src="../templates/{THEME}/images/stop.png" alt="{L_NO}" />
							# ENDIF #
						</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>{L_SUMMERIZATION}</legend>
					<p>{L_SUMMERIZATION_EXPLAIN}</p>
					<textarea style="font-family:Courier new; width:53em;" rows="20">{SUMMERIZATION}</textarea>
				</fieldset>
			</form>
		</div>
		