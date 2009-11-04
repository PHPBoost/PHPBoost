		<script type="text/javascript">
		<!--
		function check_form_conf(){
			if(document.getElementById('mail').value == "") {
				alert("{L_REQUIRE_VALID_MAIL}");
				return false;
		    }
			
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONFIG}</li>
				<li>
					<a href="admin_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php" class="quick_link">{L_CONFIG_MAIN}</a>
				</li>
				<li>
					<a href="admin_config.php?adv=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
					<br />
					<a href="admin_config.php?adv=1" class="quick_link">{L_CONFIG_ADVANCED}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">

			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>	
			</div>
			# ENDIF #
				
			<script type="text/javascript">
			<!--	
			var array_identifier = new Array();
			{JS_LANG_IDENTIFIER}
			function change_img_lang(id, lang)
			{
				if( array_identifier[lang] && document.getElementById(id) ) 
					document.getElementById(id).src = '{PATH_TO_ROOT}/images/stats/countries/' + array_identifier[lang] + '.png';
			}
			-->
			</script>
				
			<form action="admin_config.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset> 
					<legend>{L_CONFIG_MAIN}</legend>
					<dl>
						<dt><label for="site_name">{L_SITE_NAME}</label></dt>
						<dd><label><input type="text" size="40" maxlength="100" id="site_name" name="site_name" value="{SITE_NAME}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="site_desc">{L_SITE_DESC}</label><br /><span>{L_SITE_DESC_EXPLAIN}</span></dt>
						<dd><label><textarea class="post" rows="3" cols="37" name="site_desc" id="site_desc">{SITE_DESCRIPTION}</textarea></label></dd>
					</dl>
					<dl>
						<dt><label for="site_keyword">{L_SITE_KEYWORDS}</label><br /><span>{L_SITE_KEYWORDS_EXPLAIN}</span></dt>
						<dd><label><textarea class="post" rows="3" cols="37" name="site_keyword" id="site_keyword">{SITE_KEYWORD}</textarea></label></dd>
					</dl> 
					<dl>
						<dt><label for="site_lang">* {L_DEFAULT_LANGUAGES}</label></dt>
						<dd>
							<label><select name="lang" id="site_lang" onchange="change_img_lang('img_lang', this.options[this.selectedIndex].value)">				
							# START select_lang #				
								{select_lang.LANG}				
							# END select_lang #				
							</select> <img id="img_lang" src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" /></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="default_theme">* {L_DEFAULT_THEME}</label></dt>
						<dd><label>
							<select name="theme" id="default_theme" onchange="change_img_path('img_theme', '{PATH_TO_ROOT}/templates/' + this.options[selectedIndex].value + '/theme/images/theme.jpg');">
							# START select #				
								{select.THEME}				
							# END select #				
							</select>
							<img id="img_theme" src="{PATH_TO_ROOT}/templates/{THEME_DEFAULT}/theme/images/theme.jpg" alt="" style="vertical-align:top" />
						</label></dd>
					</dl>
					<dl>
						<dt><label for="start_page">* {L_START_PAGE}</label></dt>
						<dd><label>
							<select name="start_page" id="start_page" onclick="document.getElementById('start_page2').value = '';">		
								<option value="" selected="selected" id="start_page_default">--</option>
								{SELECT_PAGE}			
							</select> 
						</label>
						<br />
						<label>{L_OTHER} 
						<input type="text" maxlength="255" size="20" id="start_page2" name="start_page2" class="text" value="{START_PAGE}" onclick="document.getElementById('start_page_default').selected = true;" /></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="count">{L_COMPT}</label></dt>
						<dd>
							<label><input type="radio" {COMPTEUR_ENABLED} name="compteur" id="count" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp;
							<label><input type="radio" {COMPTEUR_DISABLED} name="compteur" value="0" /> {L_UNACTIVE}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="bench">{L_BENCH}</label><br /><span>{L_BENCH_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {BENCH_ENABLED} name="bench" id="bench" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp;
							<label><input type="radio" {BENCH_DISABLED} name="bench" value="0" /> {L_UNACTIVE}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="theme_author">{L_THEME_AUTHOR}</label><br /><span>{L_THEME_AUTHOR_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {THEME_AUTHOR_ENABLED} name="theme_author" id="theme_author" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp;
							<label><input type="radio" {THEME_AUTHOR_DISABLED} name="theme_author" value="0" /> {L_UNACTIVE}</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>  
					<legend>
						{L_EMAIL_MANAGEMENT}
					</legend>
					<dl>
						<dt><label for="mail_exp">* {L_EMAIL_ADMIN_EXP}</label><br /><span>{L_EMAIL_ADMIN_EXP_EXPLAIN}</span></dt>
						<dd><label><input type="text" maxlength="255" size="40" id="mail_exp" name="mail_exp" value="{MAIL_EXP}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="mail">* {L_EMAIL_ADMIN}</label><br /><span>{L_EMAIL_ADMIN_EXPLAIN}</span></dt>
						<dd><label><input type="text" maxlength="255" size="40" id="mail" name="mail" value="{MAIL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="sign">{L_EMAIL_ADMIN_SIGN}</label><br /><span>{L_EMAIL_ADMIN_SIGN_EXPLAIN}</span></dt>
						<dd><label><textarea class="post" rows="3" cols="37" name="sign" id="sign">{SIGN}</textarea></label></dd>
					</dl>
				</fieldset> 

				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>
		</div>