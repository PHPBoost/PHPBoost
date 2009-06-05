		<link href="{MODULE_DATA_PATH}/news.css" rel="stylesheet" type="text/css" media="screen, handheld">

		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="../kernel/framework/js/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }

			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_NEWS_MANAGEMENT}</li>
				<li>
					<a href="admin_news.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news.php" class="quick_link">{L_NEWS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_news_add.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_add.php" class="quick_link">{L_ADD_NEWS}</a>
				</li>
				<li>
					<a href="admin_news_cat.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_cat.php" class="quick_link">{L_CAT_NEWS}</a>
				</li>
				<li>
					<a href="admin_news_config.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_config.php" class="quick_link">{L_CONFIG_NEWS}</a>
				</li>
			</ul>
		</div>
		<div id="admin_contents">
			# START news #
			<div class="block_container">
				<div class="block_top">{L_PREVIEW}</div>
				<div class="block_contents row2">
					<div class="news_container">
						<div class="msg_top_l"></div>			
						<div class="msg_top_r"></div>
						<div class="msg_top">
							<div style="float:left"><a href="{PATH_TO_ROOT}/syndication.php?m=news" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle">{news.TITLE}</h3></div>
							<div style="float:right"></div>
						</div>												
						<div class="news_content">
							{news.IMG}
							{news.PREVIEWED_CONTENTS}
							<hr />	
							{news.PREVIEWED_EXTEND_CONTENTS}	
						</div>								
						<div class="news_bottom_l"></div>		
						<div class="news_bottom_r"></div>
						<div class="news_bottom">
							<span style="float:left"><a class="small_link" href="../member/member{news.U_USER_ID}">{news.PSEUDO}</a></span>
							<span style="float:right">{L_ON}: {news.DATE}</span>
						</div>
					</div>				
				</div>				
			</div>				

			<br /><br /><br />
			# END news #
			
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
			
			<form action="admin_news_add.php?token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_NEWS}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{NEWS_TITLE}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* {L_CATEGORY}</label></dt>
						<dd><label>
							<select id="idcat" name="idcat">				
							# START select #				
								{select.CAT}				
							# END select #				
							</select>
						</label></dd>
					</dl>
					<br />
					<label for="contents">* {L_TEXT}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="20" cols="86" id="contents" name="contents">{CONTENTS}</textarea></label>
					<br />
					<br />
					<label for="extend_contents">{L_EXTENDED_NEWS}</label>
					{KERNEL_EDITOR_EXTEND}
					<label><textarea rows="20" cols="86" id="extend_contents" name="extend_contents">{EXTEND_CONTENTS}</textarea> </label>
					<br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<div onclick="document.getElementById('start_end_date').checked = true;">
								<label><input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING} /></label>
								<input type="text" size="7" maxlength="8" id="start" name="start" value="{START}" class="text" /> 				
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
									<div id="start_date" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);"></div>
								</div>
								<a onclick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgstart_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								
								{L_AT}
								<input type="text" size="1" maxlength="2" name="start_hour" value="{START_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="start_min" value="{START_MIN}" class="text" />
								&nbsp;{L_UNTIL}&nbsp;
								<input type="text" size="7" maxlength="8" id="end" name="end" value="{END}" class="text" /> 
								<div style="position:relative;z-index:100;top:6px;margin-left:250px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);"></div>
								</div>
								<a onclick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(2, 'end_date');" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgend_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								{L_AT}
								<input type="text" size="1" maxlength="2" name="end_hour" value="{END_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="end_min" value="{END_MIN}" class="text" />
							</div>
							<label><input type="radio" value="1" name="visible" {VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
							<br />
							<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_NEWS_DATE}</label></dt>
						<dd>
							<input type="text" size="7" maxlength="8" id="current_date" name="current_date" value="{CURRENT_DATE}" class="text" />			
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);"></div>
							</div>
							<a onclick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcurrent"  src="../templates/{THEME}/images/calendar.png" alt="" /></a>							
							{L_AT}
							<input type="text" size="1" maxlength="2" name="current_hour" value="{CURRENT_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="current_min" value="{CURRENT_MIN}" class="text" />							
						</dd>
					</dl>
				</fieldset>	
				
				<fieldset>
					<legend>{L_IMG_MANAGEMENT}</legend>
					<dl>
						<dt><label>{L_PREVIEW_IMG}</label><br /><span>{L_PREVIEW_IMG_EXPLAIN}</span></dt>
						<dd>{IMG_PREVIEW}</dd>
					</dl>
					<dl>
						<dt><label for="img_field">{L_IMG_LINK}</label></dt>
						<dd><label><input type="text" size="60" id="img_field" name="img" value="{IMG}" class="text" /> &nbsp;&nbsp;<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd=img_field', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a></label></dd>
					</dl>
					<dl>
						<dt><label for="alt">{L_IMG_DESC}</label></dt>
						<dd><label><input type="text" size="60" name="alt" id="alt" value="{ALT}" class="text" /></label></dd>
					</dl>
				</fieldset>		
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
