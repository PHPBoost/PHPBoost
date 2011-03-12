<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<link type="text/css" href="templates/update.css" title="phpboost" rel="stylesheet" />
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="templates/global.js"></script>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
			var step = {NUM_STEP};
		-->
		</script>
	<div id="global">
		<div id="header_container">
		</div>
		<div id="left_menu">
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_STEPS_LIST}
					</td>
				</tr>
				# START link_menu #
					<tr>
						<td class="{link_menu.CLASS}">
							<img src="templates/images/{link_menu.STEP_IMG}" alt="" class="valign_middle" />&nbsp;&nbsp;{link_menu.STEP_NAME}
						</td>				
					</tr>
				# END link_menu #
			</table>
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_INSTALL_PROGRESS}
					</td>
				</tr>
				<tr>
					<td class="row_next row_final">
						<div style="margin:auto;width:200px">
							<div style="text-align:center;margin-bottom:5px;">{L_STEP} :&nbsp;{PROGRESS_LEVEL}%</div>
							<div style="float:left;height:12px;border:1px solid black;background:white;width:192px;padding:2px;padding-left:3px;padding-right:1px;">
								# START progress_bar #<img src="templates/images/progress.png" alt="" /># END progress_bar #
							</div>
						</div>
					</td>
				</tr>						
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						<div style="text-align:right;padding-top:5px;padding-right:30px;"><img src="templates/images/phpboost.png" alt="Logo PHPBoost" class="valign_middle" /> {L_STEP}</div>
					</th>
				</tr>
				
				<tr> 					
					# IF C_INTRO #
					<td class="row_contents">						
						<span style="float:right;padding:8px;padding-top:0px;padding-right:25px">
							<img src="templates/images/PHPBoost_box3.0.png" alt="Logo PHPBoost" />
						</span>
						<h1>{L_INTRO_TITLE}</h1>
						{L_INTRO_EXPLAIN}
						
						<div style="margin-bottom:60px;">&nbsp;</div>
						
						<h1>{DISTRIBUTION}</h1>
						{L_DISTRIBUTION_EXPLAIN}
						<br />
						{DISTRIBUTION_DESCRIPTION}
						
						<fieldset class="submit_case">
							<a href="{L_NEXT_STEP}" title="{L_START_INSTALL}" ><img src="templates/images/right.png" alt="{L_START_INSTALL}" class="valign_middle" /></a>
						</fieldset>						
					</td>
					# ENDIF #
					
					
					# IF C_LICENSE #
					<td class="row_contents">
						<h1>{L_REQUIRE_LICENSE}</h1>
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
									{L_QUERY_TERMS}
								</legend>
								{L_REQUIRE_LICENSE_AGREEMENT}
								<br />
								<br />
								<div style="width:auto;height:340px;overflow-y:scroll;border:1px solid #DFDFDF;background-color:#F1F4F1">
									{L_LICENSE_TERMS}
								</div>
								<div style="text-align:center;margin:15px;margin-bottom:10px;">
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
					# ENDIF #
					
					
					# IF C_SERVER_CONFIG #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
						display_result = false;
						function refresh()
						{
							load_progress_bar(20, '');
							if( !display_result )
								Effect.Appear('result_box');
							
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
							<h1>{L_CONFIG_SERVER_TITLE}</h1>
							<a href="http://www.php.net/">
								<img src="templates/images/php.png" alt="PHP" style="float:right; margin-bottom:5px; margin-left:5px;"/>
							</a>
							{L_CONFIG_SERVER_EXPLAIN}
							
							<fieldset>
								<legend>{L_PHP_VERSION}</legend>
								<p>{L_CHECK_PHP_VERSION_EXPLAIN}</p>
								<dl>
									<dt><label>{L_CHECK_PHP_VERSION}</label></dt>
									<dd>
									# IF C_PHP_VERSION_OK #
										<img src="templates/images/success.png" alt="{L_YES}" />
									# ELSE #
										<img src="templates/images/stop.png" alt="{L_NO}" />
									# ENDIF #
									</dd>								
								</dl>
							</fieldset>
							
							<fieldset>
								<legend>{L_EXTENSIONS}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label>{L_GD_LIBRARY}</label><br /><span>{L_GD_LIBRARY_EXPLAIN}</span></dt>
									<dd>
									# IF C_GD_LIBRAIRY_ENABLED #
										<img src="templates/images/success.png" alt="{L_YES}" />
									# ELSE #
										<img src="templates/images/stop.png" alt="{L_NO}" />
									# ENDIF #
									</dd>								
								</dl>
								<dl>
									<dt><label>{L_URL_REWRITING}</label><br /><span>{L_URL_REWRITING_EXPLAIN}</span></dt>
									<dd>
									# IF C_URL_REWRITING_KNOWN #
										# IF C_URL_REWRITING_ENABLED #
										<img src="templates/images/success.png" alt="{L_YES}" />
										# ELSE #
										<img src="templates/images/stop.png" alt="{L_NO}" />
										# ENDIF #
									# ELSE #
									<img src="templates/images/question.png" alt="{L_UNKNOWN}" />
									# ENDIF #
									</dd>								
								</dl>
							</fieldset>
							
							<fieldset>
								<legend>{L_AUTH_DIR}</legend>
								<p>{L_CHECK_AUTH_DIR}</p>
								<div id="chmod">
									# START chmod #							
									<dl>
										<dt><label>{chmod.TITLE}</label></dt>
										<dd>
											# IF chmod.C_EXISTING_DIR #
												<div class="success_block">{L_EXISTING}</div>
											# ELSE #
												<div class="failure_block">{L_NOT_EXISTING}</div>
											# ENDIF #
											# IF chmod.C_WRITIBLE_DIR #
												<div class="success_block">{L_WRITABLE}</div>
											# ELSE #
												<div class="failure_block">{L_NOT_WRITABLE}</div>
											# ENDIF #
										</dd>								
									</dl>
									# END chmod #
								</div>
							</fieldset>	
							
							<fieldset style="display:none;" id="result_box">
								<legend>
									{L_RESULT}
								</legend>
								# IF C_ERROR #
									<div class="error">
										{L_ERROR}
									</div>
								# ENDIF #
								<div style="margin:auto;width:500px;">
									<div id="progress_info" style="text-align:center;"></div>
									<div style="float:left;height:13px;border:1px solid black;background:white;width:448px;padding:2px;padding-top:1px;padding-left:3px;padding-right:1px;" id="progress_bar"></div>
									&nbsp;<span id="progress_percent">0</span>%
								</div>
							</fieldset>
							
							# IF C_ERROR #
							<script type="text/javascript">
							<!--
								document.getElementById("result_box").style.display = "block";
								load_progress_bar(5, '');
								progress_bar(100, "{L_QUERY_SUCCESS}");
							-->
							</script>
							# ENDIF #
							
							<form action="{U_CURRENT_STEP}#result_box" method="post">
								<fieldset class="submit_case">
									<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
									<a href="{U_CURRENT_STEP}" title="{L_REFRESH}" id="enougth_js_preview">
										<img src="templates/images/refresh.png" alt="{L_REFRESH}" class="valign_middle" />
									</a>
									<script type="text/javascript">
									<!--
										document.getElementById("enougth_js_preview").style.display = "none";
										document.write("<a title=\"{L_REFRESH}\" href=\"javascript:refresh();\" ><img src=\"templates/images/refresh.png\" alt=\"{L_REFRESH}\" class=\"valign_middle\" /></a>&nbsp;<span id=\"image_loading\"></span>&nbsp;");
									-->
									</script>
									<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" />
									<input type="hidden"  name="submit" value="next" />
								</fieldset>
							</form>
						</div>
					</td>
					# ENDIF #

					
					# IF C_DATABASE_CONFIG #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
						
						function display_result_text(return_code)
						{
							switch(return_code)
							{
								case '{DB_CONFIG_SUCCESS}':
									document.getElementById("db_result").innerHTML = '<div class="success">{L_DB_CONFIG_SUCESS}</div>';
									break;
								case '{DB_CONFIG_ERROR_CONNECTION_TO_DBMS}':
									document.getElementById("db_result").innerHTML = '<div class="error">{L_DB_CONFIG_ERROR_CONNECTION_TO_DBMS}</div>';
									break;
								case '{DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED}':
									document.getElementById("db_result").innerHTML = '<div class="success">{L_DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED}</div>';
									break;
								case '{DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED}':
									document.getElementById("db_result").innerHTML = '<div class="error">{L_DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED}</div>';
									break;
								case '{DB_CONFIG_ERROR_TABLES_ALREADY_EXIST}':
									document.getElementById("db_result").innerHTML = '<div class="notice">{L_DB_CONFIG_ERROR_TABLES_ALREADY_EXIST}</div>';
									break;
								default:
									document.getElementById("db_result").innerHTML = '<div class="error">{L_UNKNOWN_ERROR}</div>';
							}
						}
						
						display_result = false;
						
						function send_infos()
						{
							if( !check_form_db() )
								return;
							
							load_progress_bar(20, '');
							data = "host=" + document.getElementById("host").value + "&login=" + document.getElementById("login").value + "&password=" + document.getElementById("password").value + "&database=" + document.getElementById("database").value + "&prefix=" + document.getElementById("tableprefix").value;

							if( !display_result )
								Effect.Appear('result_box');
								
							var xhr_object = xmlhttprequest_init('xmlhttprequest.php?lang={LANG}&db=1');
							xhr_object.onreadystatechange = function() 
							{
								switch(xhr_object.readyState)
								{
									case 1:
										progress_bar(25, "{L_QUERY_LOADING}");
										break;
									case 2:
										progress_bar(50, "{L_QUERY_SENT}");
										break;
									case 3:
										progress_bar(75, "{L_QUERY_PROCESSING}");
										break;
									case 4:
										if( xhr_object.status == 200 )
										{
											progress_bar(100, "{L_QUERY_SUCCESS}");
											display_result_text(xhr_object.responseText);
										}
										else
											progress_bar(99, "{L_QUERY_FAILURE}");
										break;
								}
							}
							xmlhttprequest_sender(xhr_object, data);
						}
						
						function check_form_db()
						{
							if(document.getElementById('host').value == "")
							{
								alert("{L_REQUIRE_HOSTNAME}");
								return false;
							}

							if(document.getElementById('login').value == "")
							{
								alert("{L_REQUIRE_LOGIN}");
								return false;
							}
							if(document.getElementById('database').value == "")
							{
								alert("{L_REQUIRE_DATABASE_NAME}");
								return false;
							}
							
							return true;
						}
						-->
						</script>
						<h1>{L_DB_TITLE}</h1>
						<a href="http://www.mysql.com/">
							<img src="templates/images/mysql.png" alt="MySQL" style="float:right; margin-bottom:5px; margin-left:5px;"/>
						</a>
						{L_DB_EXPLAIN}
						<br />
						
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_db();" class="fieldset_content">
							<fieldset>
								<legend>{L_SGBD_PARAMETERS}</legend>
								<dl>
									<dt><label for="host">* {L_HOST}</label><br /><span>{L_HOST_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="150" size="25" id="host" name="host" value="{HOST_VALUE}" class="small_text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="login">* {L_LOGIN}</label><br /><span>{L_LOGIN_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="25" size="25" id="login" name="login" value="{LOGIN_VALUE}" class="small_text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="password">{L_PASSWORD}</label><br /><span>{L_PASSWORD_EXPLAIN}</span></dt>
									<dd><label><input type="password" maxlength="25" size="25" id="password" name="password" value="{PASSWORD_VALUE}" class="small_text" /></label></dd>
								</dl>
							</fieldset>	
							
							<fieldset>
								<legend>{L_DB_PARAMETERS}</legend>
								<dl>
									<dt><label for="database">* {L_DB_NAME}</label><br /><span>{L_DB_NAME_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="150" size="25" id="database" name="database" value="{DB_NAME_VALUE}" class="small_text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="tableprefix">{L_DB_PREFIX}</label><br /><span>{L_DB_PREFIX_EXPLAIN}</span></dt>
									<dd><label><input type="text" maxlength="20" size="25" name="tableprefix" id="tableprefix" value="{PREFIX_VALUE}" class="small_text" /></label></dd>
								</dl>
							</fieldset>
							
							# IF C_ALREADY_INSTALLED #
							<fieldset>
								<legend>{L_ALREADY_INSTALLED}</legend>
								<div class="warning">
									{L_ALREADY_INSTALLED_EXPLAIN}
								</div>
								<label><input type="checkbox" name="overwrite_db" /> {L_ALREADY_INSTALLED_OVERWRITE}</label>
							</fieldset>
							# ENDIF #
							
							<fieldset id="result_box">
								<legend>
									{L_RESULT}
								</legend>
								<div style="margin:auto;margin-bottom:15px;width:500px;">
									<div id="db_result">
										{ERROR}
									</div>
									<div id="progress_info" style="text-align:center;">
										{PROGRESS_STATUS}
									</div>
									<div style="float:left;height:13px;border:1px solid black;background:white;width:448px;padding:2px;padding-top:1px;padding-left:3px;padding-right:1px;" id="progress_bar">
										{PROGRESS_BAR}
									</div>
									&nbsp;<span id="progress_percent">{PROGRESS}</span>%
								</div>
							</fieldset>
							
							# IF NOT C_DISPLAY_RESULT #
							<script type="text/javascript">
							<!--
								document.getElementById("result_box").style.display = 'none';
							-->
							</script>
							# ENDIF #
							
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
					# ENDIF #
					
					# IF C_DATABASE_MAJ #
					<td class="row_contents">
						<h1>{L_DATABASE_MAJ}</h1>
						{L_DATABASE_MAJ_EXPLAIN}
						
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_site_config();" class="fieldset_content">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
								<input title="{L_NEXT_STEP}" class="img_submit" src="templates/images/right.png" type="image" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# ENDIF #
					
					
					# IF C_SITE_CONFIG #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							var site_url = "{SITE_URL}";
							var site_path = "{SITE_PATH}";
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
						-->
						</script>
						<h1>{L_SITE_CONFIG}</h1>
						{L_SITE_CONFIG_EXPLAIN}
						
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_site_config();" class="fieldset_content">
							<fieldset>
								<legend>{L_YOUR_SITE}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label for="site_url">* {L_SITE_URL}</label><br /><span>{L_SITE_URL_EXPLAIN}</span></dt>
									<dd><input type="text" maxlength="150" size="25" id="site_url" name="site_url" value="{SITE_URL}" class="small_text" /></dd>	
								</dl>
								<dl>
									<dt><label for="site_path">* {L_SITE_PATH}</label><br /><span>{L_SITE_PATH_EXPLAIN}</span></dt>
									<dd><input type="text" maxlength="255" size="25" id="site_path" name="site_path" value="{SITE_PATH}" class="small_text" /></dd>
								</dl>
								<dl>
									<dt><label for="site_name">* {L_SITE_NAME}</label></dt>
									<dd><input type="text" size="25" maxlength="100" id="site_name" name="site_name" class="small_text" /></dd>								
								</dl>
								<dl>
									<dt><label for="site_desc">{L_SITE_DESCRIPTION}</label><br /><span>{L_SITE_DESCRIPTION_EXPLAIN}</span></dt>
									<dd><textarea rows="3" cols="23" name="site_desc" id="site_desc" class="post"></textarea></dd>								
								</dl>
								<dl>
									<dt><label for="site_keyword">{L_SITE_KEYWORDS}</label><br /><span>{L_SITE_KEYWORDS_EXPLAIN}</span></dt>
									<dd><textarea rows="3" cols="23" name="site_keyword" id="site_keyword" class="post"></textarea></dd>								
								</dl>
								<dl>
									<dt><label for="site_timezone">{L_SITE_TIMEZONE}</label><br /><span>{L_SITE_TIMEZONE_EXPLAIN}</span></dt>
									<dd>
										<select name="site_timezone" id="site_timezone">
											# START timezone #
											<option value="{timezone.VALUE}" {timezone.SELECTED}>{timezone.NAME}</option>
											# END timezone #
										</select>
									</dd>								
								</dl>
							</fieldset>
							
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
								<input title="{L_NEXT_STEP}" class="img_submit" src="templates/images/right.png" type="image" />
								<input type="hidden" name="submit" value="submit" />
							</fieldset>
						</form>
					</td>
					# ENDIF #
					
					# IF C_ADMIN_ACCOUNT #
					<td class="row_contents">
						<script type="text/javascript">
						<!--
							function check_form_admin()
							{								
								if (document.getElementById("login").value == "")
								{
									alert("{L_REQUIRE_LOGIN}");
									return false;
								}
								else if (document.getElementById("login").value.length < 3)
								{
									alert("{L_LOGIN_TOO_SHORT}");
									return false;
								}
								else if (document.getElementById("password").value == "")
								{
									alert("{L_REQUIRE_PASSWORD}");
									return false;
								}
								else if (document.getElementById("password_repeat").value == "")
								{
									alert("{L_REQUIRE_PASSWORD_REPEAT}");
									return false;
								}
								else if (document.getElementById("password").value.length < 6)
								{
									alert("{L_PASSWORD_TOO_SHORT}");
									return false;
								}
								else if (document.getElementById("mail").value == "")
								{ 
									alert("{L_REQUIRE_MAIL}");
									return false;
								}	
								else if (document.getElementById("password").value != document.getElementById("password_repeat").value)
								{
									alert("{L_PASSWORDS_ERROR}");
									return false;
								}
								else if (!check_mail_validity(document.getElementById("mail").value))
								{
									alert("{L_EMAIL_ERROR}");
									return false;
								}
								else
									return true;
							}
							function check_login(value) 
							{
								if (value.length<3)			
								{	
									document.getElementById('msg_login').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_login_div').innerHTML = "{L_LOGIN_TOO_SHORT}";
								}
								else	
								{
									document.getElementById('msg_login').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_login_div').innerHTML = '';
								}
							}
							function check_mail(value) 
							{
								if (!check_mail_validity(value))
								{	
									document.getElementById('msg_email').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_email_div').innerHTML = "{L_MAIL_INVALID}";
								}
								else
								{	
									document.getElementById('msg_email').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_email_div').innerHTML = '';
								}
							}
							function check_password(value) 
							{
								if (value.length<6)
								{	
									document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_password1_div').innerHTML = "{L_PASSWORD_TOO_SHORT}";
								}
								else
								{
									var password = document.getElementById('password_repeat').value;
									if (password == value)
									{
										document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password1_div').innerHTML = '';
										document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password2_div').innerHTML = '';
									}
									else if (password.length > 0)
									{	
										document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password1_div').innerHTML = '';
										document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password2_div').innerHTML = "{L_PASSWORDS_ERROR}";
									}
									else
									{
										document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password1_div').innerHTML = '';
									}
								}	
							}
							function check_password2(value) 
							{
								if (value.length<6)
								{	
									document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
									document.getElementById('msg_password2_div').innerHTML = "{L_PASSWORD_TOO_SHORT}";
								}
								else
								{
									var password = document.getElementById('password').value;
									if (password == value)
									{
										document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password1_div').innerHTML = '';
										document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password2_div').innerHTML = '';
									}
									else if (password.length > 0)
									{	
										document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password2_div').innerHTML = '';
										document.getElementById('msg_password1').innerHTML = '<img src="./templates/images/forbidden_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password1_div').innerHTML = "{L_PASSWORDS_ERROR}";
									}
									else
									{
										document.getElementById('msg_password2').innerHTML = '<img src="./templates/images/processed_mini.png" alt="" class="valign_middle" />';
										document.getElementById('msg_password2_div').innerHTML = '';
									}
								}	
							}
							function change_img_theme(id, value)
							{
								if(document.images )
									document.images[id].src = "../templates/" + value + "/theme/images/theme.jpg";
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
						<h1>{L_ADMIN_ACCOUNT_CREATION}</h1>
						{L_EXPLAIN_ADMIN_ACCOUNT_CREATION}
						<form action="{U_CURRENT_STEP}" method="post" onsubmit="return check_form_admin();" class="fieldset_content">
							# START error #
							<fieldset>
								<legend>{L_ERROR}</legend>
								{error.ERROR}
							</fieldset>
							# END error #
							<fieldset>
								<legend>{L_ADMIN_ACCOUNT}</legend>	
								<p>{L_CHECK_EXTENSIONS}</p>
								<dl>
									<dt><label for="login">* {L_PSEUDO}</label><br /><span>{L_PSEUDO_EXPLAIN}</span></dt>
									<dd><input type="text" size="25" maxlength="25" id="login" name="login" value="{LOGIN_VALUE}" class="small_text" onblur="check_login(this.value);" /> &nbsp;<span id="msg_login"></span><div id="msg_login_div"></div></dd>								
								</dl>
								<dl>
									<dt><label for="password">* {L_PASSWORD}</label><br /><span>{L_PASSWORD_EXPLAIN}</span></dt>
									<dd><input type="password" size="25" id="password" name="password" value="{PASSWORD_VALUE}" class="small_text" onblur="check_password(this.value);" /> &nbsp;<span id="msg_password1"></span><div id="msg_password1_div"></div></dd>								
								</dl>
								<dl>
									<dt><label for="password_repeat">* {L_PASSWORD_REPEAT}</label></dt>
									<dd><input type="password" size="25" id="password_repeat" name="password_repeat" value="{PASSWORD_VALUE}" class="small_text" onblur="check_password2(this.value);" /> &nbsp;<span id="msg_password2"></span><div id="msg_password2_div"></div></dd>
								</dl>
								<dl>
									<dt><label for="mail">* {L_MAIL}</label><br /><span>{L_MAIL_EXPLAIN}</span></dt>
									<dd><input type="text" size="25" maxlength="50" id="mail" name="mail" value="{MAIL_VALUE}" class="small_text" onblur="check_mail(this.value);" /> &nbsp;<span id="msg_email"></span><div id="msg_email_div"></div></dd>								
								</dl>
								<dl>
									<dt><label for="create_session">{L_CREATE_SESSION}</label></dt>
									<dd><input type="checkbox" name="create_session" id="create_session" {CHECKED_AUTO_CONNECTION} /></dd>								
								</dl>
								<dl>
									<dt><label for="auto_connection">{L_AUTO_CONNECTION}</label></dt>
									<dd><input type="checkbox" name="auto_connection" id="auto_connection" {CHECKED_AUTO_CONNECTION} /></dd>								
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
					# ENDIF #
					
					# IF C_END #
					<td class="row_contents">
						{CONTENTS}						
						<fieldset class="submit_case" style="width:450px;text-align:center">
							<table style="margin:auto;">
								<tr>
									<td style="width:50%">
										<a href="../member/member.php"><img src="templates/images/go-home.png" alt="{L_SITE_INDEX}" /></a>
									</td>
									<td style="padding: 0 20px;">
										<a href="{U_ADMIN_INDEX}"><img src="templates/images/admin_panel.png" alt="{L_ADMIN_INDEX}" /></a>
									</td>
								</tr>
								<tr>
									<td style="width:50%">
										<a href="../member/member.php">{L_SITE_INDEX}</a>
									</td>
									<td style="padding:0 20px;">
										<a href="{U_ADMIN_INDEX}">{L_ADMIN_INDEX}</a>
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
					# ENDIF #
				</tr>
			</table>		
		</div>
	</div>
	<div id="footer">
		<span>
			{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
		</span>	
	</div>
	</body>
</html>
