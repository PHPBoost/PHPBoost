		<script type="text/javascript" src="../templates/{THEME}/images/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_DESC}");
				return false;
		    }
			if(document.getElementById('cat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
			}
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
				if(document.getElementById('cat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
		    }
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_DOWNLOAD_MANAGEMENT}</li>
				<li>
					<a href="admin_download.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download.php" class="quick_link">{L_DOWNLOAD_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_download_add.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_add.php" class="quick_link">{L_DOWNLOAD_ADD}</a>
				</li>
				<li>
					<a href="admin_download_cat.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_cat.php" class="quick_link">{L_DOWNLOAD_CAT}</a>
				</li>
				<li>
					<a href="admin_download_config.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_config.php" class="quick_link">{L_DOWNLOAD_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# START error_handler #
			<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
						<br />	
					</div>
			</div>
			# END error_handler #
		
			# START download #
			<table  class="module_table">
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
									{TITLE}
								</div>
								<div style="float:right">
									{download.COM}
								</div>
							</div>
							<div class="module_contents">
								<p>					
									<strong>{L_DESC}:</strong> {download.CONTENTS}									
									<br /><br />									
									<strong>{L_CATEGORY}:</strong> 
									<a href="../download/php?cat={IDCAT}">{download.CAT}</a><br />
									
									<strong>{L_DATE}:</strong> {download.DATE}<br />									
									<strong>{L_DOWNLOAD}:</strong> {download.COMPT}
								</p>
								<p style="text-align: center;">					
									<a href="../download/count.php?id={download.IDURL}"><img src="../download/templates/images/{LANG}/bouton_dl.gif" alt="" /></a>
									{download.URL}
								</p>
							</div>
							<div class="module_bottom_l"></div>		
							<div class="module_bottom_r"></div>
							<div class="module_bottom"></div>
						</div>
						<br />
					</td>
				</tr>
			</table>
			<br /><br /><br />
			# END download #

			<form action="admin_download_add.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_DOWNLOAD_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></label></dd>
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
					<dl>
						<dt><label for="url">* {L_URL}</label></dt>
						<dd><label><input type="text" size="65" id="url" name="url" id="url" value="{URL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="size">{L_SIZE}</label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="size" id="size" value="{SIZE}" class="text" /> {UNIT_SIZE}</label></dd>
					</dl>
					<dl>
						<dt><label for="compt">{L_DOWNLOAD}</label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="{COMPT}" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">* {L_CONTENTS}</label>
					<label>
						# INCLUDE handle_bbcode #
						<textarea type="text" rows="20" cols="90" id="contents" name="contents">{CONTENTS}</textarea> 
						<br />
					</label>
					<br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<label><input type="radio" value="2" name="visible" {VISIBLE_WAITING} /> 
						<input type="text" size="8" maxlength="8" id="start" name="start" value="{START}" class="text" /> 
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
						
						{L_UNTIL}&nbsp;
						
						<input type="text" size="8" maxlength="8" id="end" name="end" value="{END}" class="text" />					
						<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
							<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_E}&amp;m={MONTH_RELEASE_E}&amp;y={YEAR_RELEASE_E}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a></label>
						<br />
						<label><input type="radio" value="1" name="visible" {VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
						<br />
						<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_DOWNLOAD_DATE}</label></dt>
						<dd><label>
							<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{CURRENT_DATE}" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={DAY_DATE}&amp;m={MONTH_DATE}&amp;y={YEAR_DATE}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
							
							{L_AT}
							<input type="text" size="2" maxlength="2" name="hour" value="{HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{MIN}" class="text" />
						</label></dd>
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
		