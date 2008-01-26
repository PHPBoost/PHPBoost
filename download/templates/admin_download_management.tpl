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
		
		# START list #
		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_DEL_ENTRY}");
		}
		-->
		</script>

		
		<table class="module_table">
			<tr style="text-align:center;">
				<th style="width:35%">
					{L_TITLE}
				</th>
				<th>
					{L_SIZE}
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
			
			# START list.download #
			
			<tr style="text-align:center;"> 
				<td class="row2"> 
					<a href="../download/download.php?cat={list.download.IDCAT}&amp;id={list.download.ID}">{list.download.TITLE}</a>
				</td>
				<td class="row2"> 
					{list.download.SIZE}
				</td>
				<td class="row2"> 
					{list.download.CAT}
				</td>
				<td class="row2"> 
					{list.download.PSEUDO}
				</td>
				<td class="row2">
					{list.download.DATE}
				</td>
				<td class="row2">
					{list.download.APROBATION}
					<br />
					<span class="text_small">{list.download.VISIBLE}</span>
				</td>
				<td class="row2"> 
					<a href="admin_download.php?id={list.download.ID}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
				</td>
				<td class="row2">
					<a href="admin_download.php?delete=1&amp;id={list.download.ID}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
				</td>
			</tr>
			# END list.download #

		</table>

		<br /><br />
		<p style="text-align: center;">{PAGINATION}</p>
		# END list #

		# START download #
		<script type="text/javascript" src="../templates/{THEME}/images/calendar.js"></script>
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
		-->
		</script>
		
		# START download.preview #
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
									{download.preview.TITLE}
								</div>
								<div style="float:right">
									{download.COM}
								</div>
							</div>
							<div class="module_contents">
								<p>					
									<strong>{L_DESC}:</strong> {download.preview.CONTENTS}
									<br /><br />
									<strong>{L_CATEGORY}:</strong> 
									<a href="../download/download.php?cat={download.preview.IDCAT}">{download.preview.CAT}</a><br />
									
									<strong>{L_DATE}:</strong> {download.preview.DATE}<br />									
									<strong>{L_DOWNLOAD}:</strong> {download.preview.COMPT}	
								</p>
								<p style="text-align: center;">					
									<a href="../download/count.php?id={download.preview.IDURL}"><img src="{download.preview.MODULE_DATA_PATH}/images/{LANG}/bouton_dl.gif" alt="" /></a>
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
		# END download.preview #

		# IF C_ERROR_HANDLER #
		<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
		</div>
		# ENDIF #
		
		<form action="admin_download.php" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend>{L_EDIT_FILE}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="title">* {L_TITLE}</label></dt>
					<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="{download.TITLE}" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="idcat">* {L_CATEGORY}</label></dt>
					<dd><label>
						<select id="idcat" name="idcat">				
						# START download.select #				
							{download.select.CAT}				
						# END download.select #				
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
				<label for="contents">{L_CONTENTS}</label>
				<label>
					# INCLUDE handle_bbcode #
					<textarea type="text" rows="20" cols="90" id="contents" name="contents">{download.CONTENTS}</textarea> 
					<br />
				</label>
				<br />
				<dl class="overflow_visible">
					<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
					<dd>
						<label><input type="radio" value="2" name="visible" {download.VISIBLE_WAITING} /> 
					<input type="text" size="8" maxlength="8" id="start" name="start" value="{download.START}" class="text" /> 
					<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
						<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
						</div>
					</div>
					<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={download.DAY_RELEASE_S}&amp;m={download.MONTH_RELEASE_S}&amp;y={download.YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
					
					{L_UNTIL}&nbsp;
					
					<input type="text" size="8" maxlength="8" id="end" name="end" value="{download.END}" class="text" />					
					<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
						<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
						</div>
					</div>
					<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={download.DAY_RELEASE_E}&amp;m={download.MONTH_RELEASE_E}&amp;y={download.YEAR_RELEASE_E}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a></label>
					<br />
					<label><input type="radio" value="1" name="visible" {download.VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
					<br />
					<label><input type="radio" value="0" name="visible" {download.VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
					</dd>
				</dl>
				<dl class="overflow_visible">
					<dt><label for="current_date">* {L_DOWNLOAD_DATE}</label></dt>
					<dd><label>
						<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="{download.CURRENT_DATE}" class="text" /> 
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
							<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={download.DAY_DATE}&amp;m={download.MONTH_DATE}&amp;y={download.YEAR_DATE}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
						
						{L_AT}
						<input type="text" size="2" maxlength="2" name="hour" value="{download.HOUR}" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="{download.MIN}" class="text" />
					</label></dd>
				</dl>
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="hidden" name="id" value="{download.IDURL}" />
				<input type="hidden" name="user_id" value="{download.USER_ID}" />
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
				&nbsp;&nbsp; 
				<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />				
			</fieldset>	
		</form>
		# END download #
		
		</div>
		