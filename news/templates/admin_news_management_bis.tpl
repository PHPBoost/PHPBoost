			# START news #
							<label for="extend_contents">{L_EXTENDED_NEWS}</label>
							# INCLUDE handle_bbcode #
							<label><textarea type="text" rows="15" cols="86" id="extend_contents" name="extend_contents">{news.EXTEND_CONTENTS}</textarea></label>
						<br />
						<dl class="overflow_visible">
							<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
							<dd>
								<label><input type="radio" value="2" name="visible" {news.VISIBLE_WAITING} /></label>
								<input type="text" size="7" maxlength="8" id="start" name="start" value="{news.START}" class="text" /> 								
								<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={news.DAY_RELEASE_S}&amp;m={news.MONTH_RELEASE_S}&amp;y={news.YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								{L_AT}
								<input type="text" size="1" maxlength="2" name="start_hour" value="{news.START_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="start_min" value="{news.START_MIN}" class="text" />
								&nbsp;{L_UNTIL}&nbsp;
								<input type="text" size="7" maxlength="8" id="end" name="end" value="{news.END}" class="text" />
								<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(2, 'end_date');" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
								{L_AT}
								<input type="text" size="1" maxlength="2" name="end_hour" value="{news.END_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="end_min" value="{news.END_MIN}" class="text" />
								
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
									<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
									</div>
								</div>
								<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
									</div>
								</div>
								<br />
								<label><input type="radio" value="1" name="visible" {news.VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
								<br />
								<label><input type="radio" value="0" name="visible" {news.VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
							</dd>
						</dl>
						<dl class="overflow_visible">
							<dt><label for="current_date">* {L_NEWS_DATE}</label></dt>
							<dd>
								<input type="text" size="7" maxlength="8" id="current_date" name="current_date" value="{news.CURRENT_DATE}" class="text" />		
								<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={news.DAY_DATE}&amp;m={news.MONTH_DATE}&amp;y={news.YEAR_DATE}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>								
								{L_AT}
								<input type="text" size="1" maxlength="2" name="current_hour" value="{news.CURRENT_HOUR}" class="text" /> {L_UNIT_HOUR} <input type="text" size="1" maxlength="2" name="current_min" value="{news.CURRENT_MIN}" class="text" />
								
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
									<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
									</div>
								</div>
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
							<dd><label><input type="text" size="60" id="img_field" name="img" value="{news.IMG}" class="text" /></label></dd>
						</dl>
						<dl>
							<dt><label for="alt">{L_IMG_DESC}</label></dt>
							<dd><label><input type="text" size="60" name="alt" id="alt" value="{news.ALT}" class="text" /></label></dd>
						</dl>
					</fieldset>		
					
					<fieldset class="fieldset_submit">
						<legend>{L_UPDATE}</legend>
						<input type="hidden" name="id" value="{news.IDNEWS}" class="submit" />
						<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
						&nbsp;&nbsp; 
						<input type="submit" name="previs" value="{L_PREVIEW}" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="{L_RESET}" class="reset" />				
					</fieldset>	
				</form>

			# END news #
		</div>	
