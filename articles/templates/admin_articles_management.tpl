{ADMIN_MENU}
		<div id="admin_contents">
			# START list #
			<script type="text/javascript">
			<!--
			function Confirm() {
			return confirm("{L_CONFIRM_DEL_ARTICLE}");
			}
			-->
			</script>
			<table  class="module_table">
				<tr style="text-align:center;">
					<th style="width:28%;text-align:center">
						<a href="../articles/admin_articles{U_ARTICLES_TITLE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_TITLE}
						<a href="../articles/admin_articles{U_ARTICLES_TITLE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
						<a href="../articles/admin_articles{U_ARTICLES_CAT_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_CATEGORY}
						<a href="../articles/admin_articles{U_ARTICLES_CAT_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>						
					</th>
					<th>
						<a href="../articles/admin_articles{U_ARTICLES_PSEUDO_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_PSEUDO}
						<a href="../articles/admin_articles{U_ARTICLES_PSEUDO_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
						<a href="../articles/admin_articles{U_ARTICLES_DATE_TOP}}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_DATE}
						<a href="../articles/admin_articles{U_ARTICLES_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
						<a href="../articles/admin_articles{U_ARTICLES_APPROB_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
						{L_APROB}
						<a href="../articles/admin_articles{U_ARTICLES_APPROB_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</th>
					<th>
						{L_UPDATE}
					</th>
					<th>
						{L_DELETE}
					</th>
				</tr>
				
				# START list.articles #
				<tr style="text-align:center;"> 
					<td class="row2">
						<a href="../articles/articles.php?cat={list.articles.IDCAT}&amp;id={list.articles.ID}">{list.articles.TITLE}</a>
					</td>
					<td class="row2"> 
						{list.articles.U_CAT}
					</td>
					<td class="row2"> 
						{list.articles.PSEUDO}
					</td>		
					<td class="row2">
						{list.articles.DATE}
					</td>
					<td class="row2">
						{list.articles.APROBATION} 
						<br />
						<span class="text_small">{list.articles.VISIBLE}</span>
					</td>
					<td class="row2"> 
						<a href="management.php?edit={list.articles.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="management.php?del={list.articles.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END list.articles #

			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
			# END list #


			# START articles #
			<script type="text/javascript">
			<!--
				var theme = '{THEME}';
			-->
			</script>
			<script type="text/javascript" src="../kernel/lib/js/calendar.js"></script>
			<script type="text/javascript">
			<!--
			function check_form()
			{
				# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
				# ENDIF #
			
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
						
			# START articles.preview #
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
										{articles.preview.TITLE}
									</div>
									<div style="float:right">
										{L_COM} (0)
									</div>
								</div>
								<div class="module_contents">
									{articles.preview.CONTENTS}
								</div>
								<div class="module_bottom_l"></div>		
								<div class="module_bottom_r"></div>
								<div class="module_bottom">
									<div style="float:left" class="text_small">
										&nbsp;
									</div>
									<div style="float:right" class="text_small">
										{L_WRITTEN_BY}: {articles.preview.PSEUDO}, {L_ON}: {articles.preview.DATE}
									</div>
								</div>
							</div>
							<br />
						</td>
					</tr>
			</table>	
			<br /><br /><br />
			# END articles.preview #

			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>
			</div>
			# ENDIF #
			
			<form action="admin_articles.php?token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_ARTICLE}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{articles.TITLE}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* {L_CATEGORY}</label></dt>
						<dd><label>
							<select id="idcat" name="idcat">				
								{articles.CATEGORIES}		
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="icon">{L_ARTICLE_ICON}</label></dt>
						<dd>
							<select name="icon" id="icon" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
								{articles.IMG_LIST}
							</select>
							<span id="icon_img">{articles.IMG_ICON}</span>
							<br />
							<span class="text_small">{L_OR_DIRECT_PATH}</span> <input type="text" class="text" name="icon_path" value="{articles.IMG_PATH}" onblur="if(this.value != '')change_icon(this.value)" />
						</dd>
					</dl>
					<br />
					<label for="contents">* {L_TEXT}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea rows="30" cols="90" id="contents" name="contents">{articles.CONTENTS}</textarea> 
						<p class="text_center" style="margin-top:8px;">
							<a href="javascript:bbcode_page();"><img src="../articles/articles.png" alt="{L_EXPLAIN_PAGE}" title="{L_EXPLAIN_PAGE}" /></a>
						</p>
						<p class="text_center" style="margin-top:-15px;">
							<a href="javascript:bbcode_page();">{L_EXPLAIN_PAGE}</a>
						</p>
					</label>
					<br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<div onclick="document.getElementById('start_end_date').checked = true;">
								<label><input type="radio" value="2" name="visible" id="start_end_date" {articles.VISIBLE_WAITING} /></label>
								<input type="text" size="8" maxlength="8" id="start" name="start" value="{articles.START}" class="text" />					
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
									<div id="start_date" class="calendar_block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
									</div>
								</div>
								<a onclick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={articles.DAY_RELEASE_S}&amp;m={articles.MONTH_RELEASE_S}&amp;y={articles.YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgstart_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								
								{L_UNTIL}&nbsp;
								
								<input type="text" size="8" maxlength="8" id="end" name="end" value="{articles.END}" class="text" /> 					
								<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
									</div>
								</div>
								<a onclick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={articles.DAY_RELEASE_E}&amp;m={articles.MONTH_RELEASE_E}&amp;y={articles.YEAR_RELEASE_E}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgend_date" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							</div>
							<label><input type="radio" id="release_date" value="1" name="visible" {articles.VISIBLE_ENABLED} /> {L_IMMEDIATE}</label>
							<br />
							<label><input type="radio" value="0" name="visible" {articles.VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_ARTICLES_DATE}</label></dt>
						<dd><label>
							<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{articles.CURRENT_DATE}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={articles.DAY_DATE}&amp;m={articles.MONTH_DATE}&amp;y={articles.YEAR_DATE}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img class="valign_middle" id="imgcurrent" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							{L_AT}
							<input type="text" size="2" maxlength="2" name="hour" value="{articles.HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{articles.MIN}" class="text" />
						</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{articles.IDARTICLES}" />
					<input type="hidden" name="user_id" value="{articles.USER_ID}" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
			# END articles #
	</div>
	