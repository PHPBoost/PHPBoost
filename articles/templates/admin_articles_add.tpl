		<script type="text/javascript">
		<!--
			var theme = '{THEME}';
		-->
		</script>
		<script type="text/javascript" src="../kernel/framework/js/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(document.getElementById('idcat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
		}
		function change_icon(img_path)
		{
			document.getElementById('icon_img').innerHTML = '<img src="' + img_path + '" alt="" class="valign_middle" />';
		}
		function bbcode_page()
		{
			var page = prompt("{L_PAGE_PROMPT}");
			if( page != null && page != '' )
				insertbbcode('[page]' + page, '[/page]', 'contents');
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ARTICLES_MANAGEMENT}</li>
				<li>
					<a href="admin_articles.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles.php" class="quick_link">{L_ARTICLES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_articles_add.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_add.php" class="quick_link">{L_ARTICLES_ADD}</a>
				</li>
				<li>
					<a href="admin_articles_cat.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_cat.php" class="quick_link">{L_ARTICLES_CAT}</a>
				</li>
				<li>
					<a href="admin_articles_cat_add.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_cat_add.php" class="quick_link">{L_ARTICLES_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_articles_config.php"><img src="articles.png" alt="" /></a>
					<br />
					<a href="admin_articles_config.php" class="quick_link">{L_ARTICLES_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# IF C_ARTICLES_PREVIEW #
			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_PREVIEW}
					</th>
				</tr>
				<tr> 
					<td>
						<br />
						<div class="module_position">					
							<div class="module_top_l"></div>		
							<div class="module_top_r"></div>
							<div class="module_top">
								<div style="float:left">
									{TITLE_PRW}
								</div>
								<div style="float:right">
									{L_COM} (0)
								</div>
							</div>
							<div class="module_contents">
								{CONTENTS_PRW}
							</div>
							<div class="module_bottom_l"></div>		
							<div class="module_bottom_r"></div>
							<div class="module_bottom">
								<div style="float:left" class="text_small">
									&nbsp;
								</div>
								<div style="float:right" class="text_small">
									{L_WRITTEN_BY}: {PSEUDO_PRW}, {L_ON}: {DATE_PRW}
								</div>
							</div>
						</div>
						<br />
					</td>
				</tr>
			</table>	

			<br /><br /><br />
			# ENDIF #
				
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #	
				
			<form action="admin_articles_add.php" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_ARTICLES_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* {L_CATEGORY}</label></dt>
						<dd><label>
						<select id="idcat" name="idcat">				
								{CATEGORIES}		
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="icon">{L_ARTICLE_ICON}</label></dt>
						<dd><label>
							<select name="icon" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
								{IMG_LIST}
							</select>
							<span id="icon_img">{IMG_ICON}</span>
							<br />
							<span class="text_small">{L_OR_DIRECT_PATH}</span> <input type="text" class="text" name="icon_path" value="{IMG_PATH}" onblur="if( this.value != '' )change_icon(this.value)" />
						</label></dd>
					</dl>
					<br />
					<label for="contents">* {L_TEXT}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea type="text" rows="30" cols="90" id="contents" name="contents">{CONTENTS}</textarea> 
						<p class="text_center" style="margin-top:8px;">
							<a href="javascript:bbcode_page();"><img src="../articles/articles.png" alt="{L_EXPLAIN_PAGE}" title="{L_EXPLAIN_PAGE}" /></a>
						</p>
						<p class="text_center" style="margin-top:-15px;">
							<a href="javascript:bbcode_page();">{L_EXPLAIN_PAGE}</a>
						</p>
					</label>
					<br /><br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<label><input type="radio" value="2" name="visible" {VISIBLE_WAITING} /> 
							<input type="text" size="8" maxlength="8" id="start" name="start" value="{START}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
								<div id="start_date" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgstart_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>

							{L_UNTIL}&nbsp;
							
							<input type="text" size="8" maxlength="8" id="end" name="end" value="{END}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
								<div id="end_date" class="calendar_block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgend_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							</label>
							<br />
							<label><input type="radio" value="1" name="visible" id="release_date" {VISIBLE_ENABLED} /> {L_IMMEDIATE}</label>
							<br />
							<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_ARTICLES_DATE}</label></dt>
						<dd>
							<label>
								<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{CURRENT_DATE}" class="text" /> 
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
									<div id="current" class="calendar_block" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
									</div>
								</div>
								<a onclick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcurrent" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								
								{L_AT}
								<input type="text" size="2" maxlength="2" name="hour" value="{HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{MIN}" class="text" />
							</label>
						</dd>
					</dl>
				</fieldset>			
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>