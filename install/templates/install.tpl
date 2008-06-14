<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<link type="text/css" href="templates/install.css" title="phpboost" rel="stylesheet" />
		<script type="text/javascript" src="templates/global.js"></script>
		<link rel="shortcut" href="../favicon.ico" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
			var step = {NUM_STEP};
		-->
		</script>
		<div id="header">
			<img src="templates/images/header_boost.jpg" alt="PHPBoost" />
		</div>

		<div id="sub_header">
			<div id="sub_header_left">
			</div>
			<div id="sub_header_right">
			</div>
		</div>
		<div id="left_menu">
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_LANG}
					</td>
				</tr>
				<tr>
					<td class="row_next" style="text-align:center;">
						<form action="{U_CHANGE_LANG}" method="post">
							<select name="new_language" id="change_lang" onchange="document.location = 'install.php?step=' + step + '&lang=' + document.getElementById('change_lang').value;">
								# START lang #
								<option value="{lang.LANG}" {lang.SELECTED}>{lang.LANG_NAME}</option>
								# END lang #
							</select>
							<img src="../images/stats/countries/{LANG_IDENTIFIER}.png" alt="" class="valign_middle" />
							<noscript>
								<br /><br />
								<input type="submit" class="submit" value="{L_CHANGE}" />
							</noscript>
						</form>
					</td>
				</tr>
			</table>
						
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_STEPS_LIST}
					</td>
				</tr>
				# START link_menu #
					{link_menu.ROW}
				# END link_menu #
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_INSTALL_PROGRESS}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<div style="margin:auto;width:235px">
							<div style="text-align:center;">{L_STEP}</div>
							<div style="float:left;height:12px;border:1px solid black;background:white;width:192px;padding:2px;padding-left:3px;padding-right:1px;">
								{PROGRESS_BAR_PICS}
							</div>&nbsp;{PROGRESS_LEVEL}%
						</div>
					</td>
				</tr>						
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_APPENDICES}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/help.png" alt="{L_DOCUMENTATION}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_DOCUMENTATION}">{L_DOCUMENTATION}</a>
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/intro.png" alt="{L_RESTART_INSTALL}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_RESTART}" onclick="return confirm('{L_CONFIRM_RESTART}');">{L_RESTART_INSTALL}</a>
					</td>
				</tr>					
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						{L_STEP}
					</th>
				</tr>
				
				<tr> 					
					# START intro #
					<td class="row_contents">						
						<span style="float:left;padding:8px;padding-top:0px">
							<img src="templates/images/phpboost.png" alt="Logo PHPBoost" />
						</span>
						{L_INTRO_EXPLAIN}
						<fieldset class="submit_case">
							<a href="{L_NEXT_STEP}" title="{L_START_INSTALL}" ><img src="templates/images/right.png" alt="{L_START_INSTALL}" /></a>
						</fieldset>		
					</td>
					# END intro #
					
					
					# START license #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							function check_license_agreement()
							{
								if( document.getElementById('license_agreement').checked == false )
								{
									alert("{L_ALERT_PLEASE_AGREE_LICENSE}");
									return false;
								}
								else
									return true;
							}
						-->
						</script>
						<form action="{TARGET}" method="post" onsubmit="return check_license_agreement();" class="fieldset_content">
							<fieldset>
								<legend>
									{L_REQUIRE_LICENSE}
								</legend>
								{L_REQUIRE_LICENSE_AGREEMENT}
								<br />
								<br />
								<div style="width:auto;height:300px;overflow-y:scroll;background-color:#91BAD8;">
									{L_LICENSE_TERMS}
								</div>
								<br />
								<br />
								<div style="text-align:center;">
									<label style="cursor:pointer;">
										<input type="checkbox" name="license_agreement" id="license_agreement" class="valign_middle" />
										{L_PLEASE_AGREE}
									</label>
								</div>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}" ><img src="templates/images/left.png" alt="{L_START_INSTALL}" class="valign_middle" /></a>
								&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" />
								<input type="hidden"  name="submit" value="next" />
							</fieldset>		
						</form>
					</td>
					# END license #
					
					
					# START config_server #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
						display_result = false;
						function refresh()
						{
							load_progress_bar(20, '');
							if( !display_result )
								document.getElementById('result_box').style.display = 'block';
							
							data = null;
							var xhr_object = xmlhttprequest_init('xmlhttprequest.php?lang={LANG}&chmod=1');
							xhr_object.onreadystatechange = function() 
							{
								if( xhr_object.readyState == 1 )
									progress_bar(25, "{L_QUERY_LOADING}");
								else if( xhr_object.readyState == 2 )
									progress_bar(50, "{L_QUERY_SENT}");
								else if( xhr_object.readyState == 3 )
									progress_bar(75, "{L_QUERY_PROCESSING}");
								else if( xhr_object.readyState == 4 )
								{
									if( xhr_object.status == 200 )
									{
										document.getElementById("chmod").innerHTML = xhr_object.responseText;
										progress_bar(100, "{L_QUERY_SUCCESS}");
									}
									else
										progress_bar(99, "{L_QUERY_FAILURE}");
								}									
							}
							xmlhttprequest_sender(xhr_object, data);
						}
						-->
						</script>
						
						<div class="fieldset_content">
							<p>{L_CONFIG_SERVER_EXPLAIN}</p>
													
							<fieldset>
								<legend>{L_PHP_VERSION}</legend>
								<p>{L_CHECK_PHP_VERSION_EXPLAIN}</p>
								<dl>
									<dt><label>{L_CHECK_PHP_VERSION}</label></dt>
									<dd><label>{config_server.PHP_VERSION}</label></dd>								
								</dl>
							</fieldset>
							
							<fieldset>
								<legend>{L_EXTENSIONS}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label>{L_GD_LIBRARY}</label><br /><span>{L_GD_LIBRARY_EXPLAIN}</span></dt>
									<dd>{config_server.GD}</dd>								
								</dl>
								<dl>
									<dt><label>{L_URL_REWRITING}</label><br /><span>{L_URL_REWRITING_EXPLAIN}</span></dt>
									<dd>{config_server.URL_REWRITING}</dd>								
								</dl>
							</fieldset>
							
							<fieldset>
								<legend>{L_AUTH_DIR}</legend>
								<p>{L_CHECK_AUTH_DIR}</p>
								<div id="chmod">
									# START config_server.chmod #							
									<dl>
										<dt><label>{config_server.chmod.TITLE}</label></dt>
										<dd>
											{config_server.chmod.FOUND}
											{config_server.chmod.WRITABLE}
										</dd>								
									</dl>
									# END config_server.chmod #
								</div>
							</fieldset>	
							
							<fieldset style="display:none;" id="result_box">
								<legend>
									{L_RESULT}
								</legend>
								<div style="margin:auto;width:500px;">
									<div id="progress_info" style="text-align:center;"></div>
									<div style="float:left;height:12px;border:1px solid black;background:white;width:448px;padding:2px;padding-left:3px;padding-right:1px;" id="progress_bar"></div>
									&nbsp;<span id="progress_percent">0</span>%
								</div>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
								<script type="text/javascript">
								<!--
									document.write("<a title=\"{L_REFRESH}\" href=\"javascript:refresh();\" ><img src=\"templates/images/refresh.png\" alt=\"{L_REFRESH}\" class=\"valign_middle\" /></a>&nbsp;<span id=\"image_loading\"></span>&nbsp;");
								-->
								</script>
								<noscript>
									<p><a href="{U_CURRENT_STEP}">{L_REFRESH}</a>&nbsp;&nbsp;</p>
								</noscript>
								<a href="{U_NEXT_STEP}" title="{L_NEXT_STEP}"><img src="templates/images/right.png" alt="{L_NEXT_STEP}" class="valign_middle" /></a>
							</fieldset>
						</div>
					</td>
					# END config_server #

					
					# START db #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
						display_result = false;
						function send_infos()
						{
							load_progress_bar(20, '');
							data = "dbms=" + document.getElementById("dbms").value + "&host=" + document.getElementById("host").value + "&login=" + document.getElementById("login").value + "&password=" + document.getElementById("password").value + "&database=" + document.getElementById("database").value;

							if( !display_result )
								document.getElementById('result_box').style.display = 'block';
								
							var xhr_object = xmlhttprequest_init('xmlhttprequest.php?lang={LANG}&db=1');
							xhr_object.onreadystatechange = function() 
							{
								if( xhr_object.readyState == 1 )
									progress_bar(25, "{L_QUERY_LOADING}");
								else if( xhr_object.readyState == 2 )
									progress_bar(50, "{L_QUERY_SENT}");
								else if( xhr_object.readyState == 3 )
									progress_bar(75, "{L_QUERY_PROCESSING}");
								else if( xhr_object.readyState == 4 )
								{
									if( xhr_object.status == 200 )
										progress_bar(100, "{L_QUERY_SUCCESS}", "db_result", xhr_object.responseText);
									else
										progress_bar(99, "{L_QUERY_FAILURE}");
								}									
							}
							xmlhttprequest_sender(xhr_object, data);
						}
						function check_form_db()
						{
							if(document.getElementById('host').value == "") {
								alert("{L_REQUIRE_HOSTNAME}");
								return false;
							}

							if(document.getElementById('login').value == "") {
								alert("{L_REQUIRE_LOGIN}");
								return false;
							}
							if(document.getElementById('database').value == "") {
								alert("{L_REQUIRE_DATABASE_NAME}");
								return false;
							}
							
							return true;
						}
						-->
						</script>
						<p>{L_DB_EXPLAIN}</p>
						
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_db();" class="fieldset_content">
							<fieldset>
								<legend>{L_DBMS}</legend>
								<dl>
									<dt><label for="dbms">{L_CHOOSE_DBMS}</label><br /><span>{L_CHOOSE_DBMS_EXPLAIN}</span></dt>
									<dd><label>
										<select name="dbms" id="dbms">
											# START db.dbms #
											<option value="{db.dbms.DBMS}" {db.dbms.SELECTED} {db.dbms.DISABLED}>{db.dbms.L_DBMS}</option>
											# END db.dbms #
										</select>
									</label></dd>								
								</dl>
							</fieldset>
							
							<fieldset>
								<legend>{L_INFORMATIONS}</legend>
								<dl>
									<dt><label for="host">* {L_HOST}</label><br /><span>{L_HOST_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="150" size="25" id="host" name="host" value="{HOST_VALUE}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="login">* {L_LOGIN}</label><br /><span>{L_LOGIN_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="25" size="25" id="login" name="login" value="{LOGIN_VALUE}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="password">{L_PASSWORD}</label><br /><span>{L_PASSWORD_EXPLAIN}</span></dt>
									<dd><label><input type="password" maxlength="25" size="25" id="password" name="password" value="{PASSWORD_VALUE}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="database">* {L_DB_NAME}</label><br /><span>{L_DB_NAME_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="150" size="25" id="database" name="database" value="{DB_NAME_VALUE}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="tableprefix">{L_DB_PREFIX}</label><br /><span>{L_DB_PREFIX_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="20" size="25" name="tableprefix" id="tableprefix" value="phpboost_" class="text" /></label></dd>
								</dl>
							</fieldset>		
							
							<fieldset style="display:{db.DISPLAY_RESULT};" id="result_box">
								<legend>
									{L_RESULT}
								</legend>
								<div style="margin:auto;width:500px;">
									<div id="db_result">
										{db.ERROR}
									</div>
									<div id="progress_info" style="text-align:center;">
										{db.PROGRESS_STATUS}
									</div>
									<div style="float:left;height:12px;border:1px solid black;background:white;width:448px;padding:2px;padding-left:3px;padding-right:1px;" id="progress_bar">
										{db.PROGRESS_BAR}
									</div>
									&nbsp;<span id="progress_percent">{db.PROGRESS}</span>%
								</div>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
								<script type="text/javascript">
								<!--
									document.write("<a href=\"javascript:send_infos();\" title=\"{L_TEST_DB_CONFIG}\" /><img src=\"templates/images/refresh.png\" class=\"valign_middle\" title=\"{L_TEST_DB_CONFIG}\" /></a>&nbsp;");
								-->
								</script>
								<input title="{L_NEXT_STEP}" class="img_submit" src="templates/images/right.png" type="image" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# END db #
					
					
					# START site_config #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							var site_url = "{site_config.SITE_URL}";
							var site_path = "{site_config.SITE_PATH}";
							function check_form_site_config()
							{
								if( document.getElementById('site_url').value == "" )
								{
									alert("{L_REQUIRE_SITE_URL}");
									return false;
							    }
								if( document.getElementById('site_name').value == "" )
								{
									alert("{L_REQUIRE_SITE_NAME}");
									return false;
							    }
								if( document.getElementById('site_url').value != site_url )
								{
									return confirm("{L_CONFIRM_SITE_URL}");
								}
								if( document.getElementById('site_path').value != site_path )
								{
									return confirm("{L_CONFIRM_SITE_PATH}");
								}
							}
							
							function change_img_theme(id, value)
							{
								if(document.images )
									document.images[id].src = "../templates/" + value + "/images/theme.jpg";
							}
							
							var array_identifier = new Array();
							{JS_LANG_IDENTIFIER}
							function change_img_lang(id, lang)
							{
								if( array_identifier[lang] && document.getElementById(id) ) 
									document.getElementById(id).src = '../images/stats/countries/' + array_identifier[lang] + '.png';
							}
						-->
						</script>
						<p>{L_CONFIG_SITE_EXPLAIN}</p>
						
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_site_config();" class="fieldset_content">
							<fieldset>
								<legend>{L_YOUR_SITE}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label for="site_url">* {L_SITE_URL}</label><br /><span>{L_SITE_URL_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="150" size="25" id="site_url" name="site_url" value="{site_config.SITE_URL}" class="text" /></label></dd>	
								</dl>
								<dl>
									<dt><label for="site_path">* {L_SITE_PATH}</label><br /><span>{L_SITE_PATH_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="255" size="25" id="site_path" name="site_path" value="{site_config.SITE_PATH}" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="lang">* {L_DEFAULT_LANGUAGE}</label></dt>
									<dd><label>
										<select id="lang" name="lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">
											# START site_config.lang #
											<option value="{site_config.lang.LANG}" {site_config.lang.SELECTED}>{site_config.lang.LANG_NAME}</option>
											# END site_config.lang #
										</select> <img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
									</label></dd>
								</dl>
								<dl>
									<dt><label for="theme">* {L_DEFAULT_THEME}</label></dt>
									<dd><label>
										<select id="theme" name="theme" onchange="change_img_theme('img_theme', this.options[selectedIndex].value)">
											# START site_config.theme #
												<option value="{site_config.theme.THEME}" {site_config.theme.SELECTED}>{site_config.theme.THEME_NAME}</option>
											# END site_config.theme # 				
										</select>
										<img id="img_theme" src="../templates/{IMG_THEME}/images/theme.jpg" alt="" style="vertical-align:top" />
									</label></dd>								
								</dl>
								<dl>
									<dt><label for="site_name">* {L_SITE_NAME}</label></dt>
									<dd><label><input type="text" size="25" maxlength="100" id="site_name" name="site_name" class="text" /></label></dd>								
								</dl>
								<dl>
									<dt><label for="site_desc">{L_SITE_DESCRIPTION}</label><br /><span>{L_SITE_DESCRIPTION_EXPLAIN}</span></dt>
									<dd><label><textarea rows="3" cols="23" name="site_desc" id="site_desc"></textarea></label></dd>								
								</dl>
								<dl>
									<dt><label for="site_keyword">{L_SITE_KEYWORDS}</label><br /><span>{L_SITE_KEYWORDS_EXPLAIN}</span></dt>
									<dd><label><textarea rows="3" cols="23" name="site_keyword" id="site_keyword"></textarea></label></dd>								
								</dl>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
								<input title="{L_NEXT_STEP}" class="img_submit" src="templates/images/right.png" type="image" class="img_submit" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# END site_config #
					
					
					# START admin #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							function check_form_admin()
							{
								regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$/;
								if( document.getElementById("login").value == "" )
								{
									alert("{L_REQUIRE_LOGIN}");
									return false;
								}
								else if( document.getElementById("login").value.length < 3 )
								{
									alert("{L_LOGIN_TOO_SHORT}");
									return false;
								}
								else if( document.getElementById("password").value == "" )
								{
									alert("{L_REQUIRE_PASSWORD}");
									return false;
								}
								else if( document.getElementById("password_repeat").value == "" )
								{
									alert("{L_REQUIRE_PASSWORD_REPEAT}");
									return false;
								}
								else if( document.getElementById("mail").value == "" )
								{
									alert("{L_REQUIRE_MAIL}");
									return false;
								}	
								else if( document.getElementById("password").value != document.getElementById("password_repeat").value )
								{
									alert("{L_PASSWORDS_ERROR}");
									return false;
								}
								else if( regex.exec(document.getElementById("mail")) != null  )
								{
									alert("{L_EMAIL_ERROR}");
									return false;
								}
								else
									return true
							}
							
							function change_img_theme(id, value)
							{
								if(document.images )
									document.images[id].src = "../templates/" + value + "/images/theme.jpg";
							}
							
							var array_identifier = new Array();
							{JS_LANG_IDENTIFIER}
							function change_img_lang(id, lang)
							{
								if( array_identifier[lang] && document.getElementById(id) ) 
									document.getElementById(id).src = '../images/stats/countries/' + array_identifier[lang] + '.png';
							}
						-->
						</script>
						<p>{L_EXPLAIN_ADMIN_ACCOUNT}</p>
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_admin();" class="fieldset_content">
							# START admin.error #
							<fieldset>
								<legend>{L_ERROR}</legend>
								{admin.error.ERROR}
							</fieldset>
							# END admin.error #
							<fieldset>
								<legend>{L_ADMIN_ACCOUNT}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label for="login">* {L_PSEUDO}</label><br /><span>{L_PSEUDO_EXPLAIN}</span></dt>
									<dd><label><input type="text" size="25" maxlength="25" id="login" name="login" value="{LOGIN_VALUE}" class="text" /></label></dd>								
								</dl>
								<dl>
									<dt><label for="password">* {L_PASSWORD}</label><br /><span>{L_PASSWORD_EXPLAIN}</span></dt>
									<dd><label><input type="password" size="25" id="password" name="password" value="{PASSWORD_VALUE}" class="text" /></label></dd>								
								</dl>
								<dl>
									<dt><label for="password_repeat">* {L_PASSWORD_REPEAT}</label></dt>
									<dd><label><input type="password" size="25" id="password_repeat" name="password_repeat" value="{PASSWORD_VALUE}" class="text" /></label></dd>								
								</dl>
								<dl>
									<dt><label for="mail">* {L_MAIL}</label><br /><span>{L_MAIL_EXPLAIN}</span></dt>
									<dd><label><input type="text" size="25" maxlength="40" id="mail" name="mail" value="{MAIL_VALUE}" class="text" /></label></dd>								
								</dl>
								<dl>
									<dt><label for="lang">* {L_LANG}</label></dt>
									<dd><label>
										<select id="lang" name="lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">
											# START admin.lang #
												<option value="{admin.lang.LANG}" {admin.lang.SELECTED}>{admin.lang.LANG_NAME}</option>
											# END admin.lang #								
										</select>
										<img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
									</label></dd>								
								</dl>
								<dl>
									<dt><label for="create_session">{L_CREATE_SESSION}</label></dt>
									<dd><label><input type="checkbox" name="create_session" id="create_session" {CHECKED_AUTO_CONNECTION} /></label></dd>								
								</dl>
								<dl>
									<dt><label for="auto_connection">{L_AUTO_CONNECTION}</label></dt>
									<dd><label><input type="checkbox" name="auto_connection" id="auto_connection" {CHECKED_AUTO_CONNECTION} /></label></dd>								
								</dl>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" class="valign_middle" alt="{L_PREVIOUS_STEP}" /></a>
								&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# END admin #
					
					
					# START modules #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							var current_preselection = 'all';
							
							var module_list = new Array({ARRAY_MODULE_LIST});
							var num_modules = module_list.length;
							
							var module_index_list = new Array({ARRAY_MODULE_INDEX_LIST});
							
							var preselections_configs = new Array();
							preselections_configs['community'] = Array('articles', 'gallery', 'news', 'forum', 'contact', 'newsletter', 'online', 'poll', 'calendar', 'shoutbox', 'stats', 'wiki', 'web', 'links', 'download', 'guestbook');
							preselections_configs['publication'] = Array('pages', 'contact', 'articles', 'web', 'stats', 'links', 'news');
							preselections_configs['all'] = module_list;
							preselections_configs['no_one'] = Array();
							
							var member_accounts = 1;
							member_accounts_configs = Array();
							member_accounts_configs['all'] = 1;
							member_accounts_configs['community'] = 1;
							member_accounts_configs['publication'] = 0;
							member_accounts_configs['no_one'] = 1;
							
							var module_index = new Array();
							module_index['all'] = 'news';
							module_index['community'] = 'news';
							module_index['publication'] = 'news';
							module_index['no_one'] = 'default';
							
							var selected_modules = new Array();
							function activ_preselection(preselection)
							{
								if( preselection == current_preselection )
									return;
									
								document.getElementById("preselection_" + preselection).className = "preselection_selected";
								document.getElementById("preselection_" + current_preselection).className = "preselection_unselected";
								document.getElementById("preselection_name").value = preselection;
								if( preselection != 'perso' )
									switch_to_selection(preselection);
								current_preselection = preselection;								
							}
							function select_module(module_name)
							{
								if( current_preselection != 'perso' )
									activ_preselection('perso');
								if( typeof selected_modules[module_name] == 'undefined' || selected_modules[module_name] == 0 )
								{
									document.getElementById("module_" + module_name).className = "selected_module";
									document.getElementById("install_" + module_name).checked = "checked";
									document.getElementById("index_module_" + module_name).disabled = "";
									selected_modules[module_name] = 1;
								}
								else
								{
									document.getElementById("module_" + module_name).className = "unselected_module";
									document.getElementById("install_" + module_name).checked = "";
									document.getElementById("index_module_" + module_name).disabled = "disabled";
									selected_modules[module_name] = 0;
								}
								if( document.getElementById("index_module").value == module_name )
									document.getElementById("index_module").value = "default";
							}
							function switch_to_selection(preselection)
							{
								array_preselection = preselections_configs[preselection];
								for(i = 0; i < num_modules; i++)
								{
									module_name = module_list[i];
									if( in_array(module_name, array_preselection) )
									{
										document.getElementById("module_" + module_name).className = "selected_module";
										document.getElementById("install_" + module_name).checked = "checked";
										if( in_array(module_name, module_index_list) )
											document.getElementById("index_module_" + module_name).disabled = "";
										selected_modules[module_name] = 1;
									}
									else
									{
										document.getElementById("module_" + module_name).className = "unselected_module";
										document.getElementById("install_" + module_name).checked = "";
										document.getElementById("index_module_" + module_name).disabled = "disabled";
										selected_modules[module_name] = 0;
									}
								}
								if( member_accounts_configs[preselection] == 1 )
								{
									document.getElementById("activ_member_block").className = "selected_module";
									document.getElementById("activ_member").checked = "checked";
									member_accounts = 1;
								}
								else
								{
									document.getElementById("activ_member_block").className = "unselected_module";
									document.getElementById("activ_member").checked = "";
									member_accounts = 0;
								}
								document.getElementById("index_module").value = module_index[preselection];
							}
							function activ_member()
							{
								if( current_preselection != 'perso' )
									activ_preselection('perso');
								if( member_accounts == 0 )
								{
									document.getElementById("activ_member_block").className = "selected_module";
									document.getElementById("activ_member").checked = "checked";
									member_accounts = 1;
								}
								else
								{
									document.getElementById("activ_member_block").className = "unselected_module";
									document.getElementById("activ_member").checked = "";
									member_accounts = 0;
								}
							}
							function in_array(string, array)
							{
								array_length = array.length;
								for(var i = 0; i < array_length; i++)
									if( array[i] == string )
										return true;
								return false;
							}
						-->
						</script>
						<p>{L_EXPLAIN_MODULES}</p>
						
						<form action="{U_CURRENT_STEP}" method="post" class="fieldset_content">
							<fieldset>
								<legend>{L_PRESELECTIONS}</legend>
								<input type="hidden" name="preselection_name" id="preselection_name" value="all" />
								<noscript>
									<div class="notice">{L_REQUIRE_JAVASCRIPT}</div>
								</noscript>
								<table style="width:100%;">
									<tr>								
										<td style="text-align:center;width:20%;height:100px;">
											<div class="preselection_selected" id="preselection_all">
												<a href="javascript:activ_preselection('all');"><img src="templates/images/all.png" alt="{L_ALL}" /></a>
												<br />
												<a href="javascript:activ_preselection('all');">{L_ALL}</a>
											</div>
										</td>
										<td style="text-align:center;width:20%;">
											<div class="preselection_unselected" id="preselection_community">
												<a href="javascript:activ_preselection('community');"><img src="templates/images/community.png" alt="{L_COMMUNITY}" /></a>
												<br />
												<a href="javascript:activ_preselection('community');">{L_COMMUNITY}</a>
											</div>
										</td>
										<td style="text-align:center;width:20%;">
											<div class="preselection_unselected" id="preselection_publication">
												<a href="javascript:activ_preselection('publication');"><img src="templates/images/publication.png" alt="{L_PUBLICATION}" /></a>
												<br />
												<a href="javascript:activ_preselection('publication');">{L_PUBLICATION}</a>
											</div>
										</td>
										<td style="text-align:center;width:20%;">
											<div class="preselection_unselected" id="preselection_perso">
												<a href="javascript:activ_preselection('perso');"><img src="templates/images/perso.png" alt="{L_PERSO}" /></a>
												<br />
												<a href="javascript:activ_preselection('perso');">{L_PERSO}</a>
											</div>
										</td>										
										<td style="text-align:center;width:20%;">
											<div class="preselection_unselected" id="preselection_no_one">
												<a href="javascript:activ_preselection('no_one');"><img src="templates/images/no_module.png" alt="{L_NO_MODULE}" /></a>
												<br />
												<a href="javascript:activ_preselection('no_one');">{L_NO_MODULE}</a>
											</div>
										</td>
									</tr>
								</table>
							</fieldset>
							
							<fieldset>
								<legend>{L_MODULE_LIST}</legend>
								# START modules.module_list #
									<div class="selected_module" id="module_{modules.module_list.MODULE_FOLDER_NAME}" onclick="select_module('{modules.module_list.MODULE_FOLDER_NAME}');" style="cursor:pointer;">
										<input type="checkbox" name="install_{modules.module_list.MODULE_FOLDER_NAME}" id="install_{modules.module_list.MODULE_FOLDER_NAME}" class="valign_middle" checked="checked" />
										<img src="{modules.module_list.SRC_IMAGE_MODULE}" alt="{modules.module_list.MODULE_NAME}" class="valign_middle" />
										<strong>{modules.module_list.MODULE_NAME}</strong>
										<span class="text_small">{modules.module_list.MODULE_DESC}</span>
									</div>
								# END modules.module_list #
							</fieldset>
							
							<fieldset>
								<legend>{L_OTHER_OPTIONS}</legend>
								<div class="selected_module" id="activ_member_block" onclick="activ_member();" style="cursor:pointer;">
									<input type="checkbox" name="activ_member" id="activ_member" class="valign_middle" checked="checked" />
									<img src="templates/images/member_accounts.png" alt="{L_ACTIV_MEMBER_ACCOUNTS}" class="valign_middle" />
									{L_ACTIV_MEMBER_ACCOUNTS}
								</div>
								<div class="unselected_module" style="padding-left:27px;">
									<img src="templates/images/index_module.png" alt="{L_INDEX_MODULE}" class="valign_middle" />
									{L_INDEX_MODULE}
									<select name="index_module" id="index_module">
										<option selected="selected" value="default">{L_DEFAULT_INDEX}</option>
										# START modules.module_index_list #
										<option value="{modules.module_index_list.MODULE}" id="index_module_{modules.module_index_list.MODULE}">{modules.module_index_list.MODULE_NAME}</option>
										# END modules.module_index_list #
									</select>
								</div>
							</fieldset>
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" class="valign_middle" alt="{L_PREVIOUS_STEP}" /></a>
								&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# END modules #
					
					
					# START register_online #
					<td class="row_contents">
						<p>{L_REGISTER_EXPLAIN}</p>
						<form action="{U_NEXT_STEP}" method="post" class="fieldset_content">
							<fieldset>
								<legend>{L_REGISTER}</legend>
								<dl>
									<dt><label for="register">{L_I_WANT_TO_REGISTER}</label></dt>
									<dd><label><input type="checkbox" name="register" id="register" checked="checked" /></label></dd>								
								</dl><label>
							</fieldset>
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>
								<input title="{L_NEXT_STEP}" src="templates/images/right.png" type="image" class="img_submit" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# END register_online #
					# START end #
					<td class="row_contents">
						{end.CONTENTS}
						{end.REGISTER}
						
						<fieldset class="submit_case">
							<p style="text-align:center;">
								<a href="{end.U_INDEX}"><img src="templates/images/go-home.png" alt="{L_SITE_INDEX}" /></a>
								<br />
								<a href="{end.U_INDEX}">{L_SITE_INDEX}</a>
							</p>
						</fieldset>
					</td>
					# END end #
										
				</tr>
			</table>		
		</div>
		<div id="footer">
			<span class="text_small">{L_GENERATED_BY}</span>
		</div>
	</body>
</html>