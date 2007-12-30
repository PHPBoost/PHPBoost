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
					<th style="width:35%">
						{L_TITLE}
					</th>
					<th>
						{L_CATEGORY}
					</th>
					<th>
						{L_PSEUDO}
					</th>
					<th>
						{L_DATE}
					</th>
					<th>
						{L_APROB}
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
						<a href="admin_articles.php?id={list.articles.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_articles.php?delete=1&amp;id={list.articles.ID}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END list.articles #

			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
			# END list #


			# START articles #
			<script type="text/javascript" src="../templates/{THEME}/images/calendar.js"></script>
			<script type="text/javascript">
			<!--
			function check_form()
			{
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

			# START articles.error_handler #
			<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{articles.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{articles.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {articles.error_handler.L_ERROR}
						<br />	
					</div>
			</div>
			# END articles.error_handler #
			
			<form action="admin_articles.php" name="form" method="post" onsubmit="return check_form();" class="fieldset_content">
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
						<dd><label>
							<select name="icon" id="icon" onchange="change_icon(this.options[this.selectedIndex].value)" onclick="change_icon(this.options[this.selectedIndex].value)">
								{articles.IMG_LIST}
							</select>
							<span id="icon_img">{articles.IMG_ICON}</span>
							<br />
							<span class="text_small">{L_OR_DIRECT_PATH}</span> <input type="text" class="text" name="icon_path" value="{articles.IMG_PATH}" onblur="if( this.value != '' )change_icon(this.value)" />
						</label></dd>
					</dl>
					<br />
					<label for="contents">* {L_TEXT}</label>
					<label>
						# INCLUDE handle_bbcode #
						<textarea type="text" rows="30" cols="90" id="contents" name="contents">{articles.CONTENTS}</textarea> 
						<p style="text-align:center;">{L_EXPLAIN_PAGE}</p>
					</label>
					<br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<label><input type="radio" value="2" name="visible" {articles.VISIBLE_WAITING} />
							<input type="text" size="8" maxlength="8" id="start" name="start" value="{articles.START}" class="text" />					
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
								<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={articles.DAY_RELEASE_S}&amp;m={articles.MONTH_RELEASE_S}&amp;y={articles.YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							
							{L_UNTIL}&nbsp;
							
							<input type="text" size="8" maxlength="8" id="end" name="end" value="{articles.END}" class="text" /> 					
							<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
								<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={articles.DAY_RELEASE_E}&amp;m={articles.MONTH_RELEASE_E}&amp;y={articles.YEAR_RELEASE_E}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a></label>
							<br />
							<label><input type="radio" id="release_date" value="1" name="visible" {articles.VISIBLE_ENABLED} /> {L_IMMEDIATE}</label></label>
							<br />
							<label><input type="radio" value="0" name="visible" {articles.VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_ARTICLES_DATE}</label></dt>
						<dd><label>
							<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{articles.CURRENT_DATE}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={articles.DAY_DATE}&amp;m={articles.MONTH_DATE}&amp;y={articles.YEAR_DATE}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							{L_AT}
							<input type="text" size="2" maxlength="2" name="hour" value="{articles.HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{articles.MIN}" class="text" />
						</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{articles.IDARTICLES}" />
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
			# END articles #
	</div>
	