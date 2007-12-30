		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('search').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
		}
		-->
		</script>

		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
			</div>
			<div class="module_contents">
				# START error_handler #
				<span id="errorh"></span>
				<div class="{error_handler.CLASS}">
					<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
				</div>		
				# END error_handler #
					
				<form action="search.php{SID}#errorh" method="post" onsubmit="return check_form();" class="fieldset_content">
					<fieldset>
						<legend>{L_SEARCH_FORUM}</legend>
						<dl>
							<dt><label for="search">{L_KEYWORDS}</label></dt>
							<dd><label><input type="text" size="35" id="search" name="search" value="{SEARCH}"  class="text" /></label></dd>
						</dl>
						<dl>
							<dt><label for="time">{L_DATE}</label></dt>
							<dd><label>	
								<select id="time" name="time">
									<option value="30000" selected="selected">{L_ALL}</option>
									<option value="1">1 {L_DAY}</option>
									<option value="7">7 {L_DAYS}</option>
									<option value="15">15 {L_DAYS}</option>
									<option value="30">1 {L_MONTH}</option>
									<option value="180">6 {L_MONTH}</option>
									<option value="360">1 {L_YEAR}</option>
								</select>
							</label></dd>
						</dl>
						<dl>
							<dt><label for="idcat">{L_CATEGORY}</label></dt>
							<dd><label>
								<select name="idcat" id="idcat">
									# START cat #
										{cat.CAT}
									# END cat #
								</select>
							</label></dd>
						</dl>
						<dl>
							<dt><label for="where">{L_OPTIONS}</label></dt>
							<dd>
								<label><input type="radio" name="where" id="where" value="contents"  checked="checked" /> {L_CONTENTS}</label>
								<br />
								<label><input type="radio" name="where" value="title" /> {L_TITLE}</label>
							</dd>
						</dl>
					</fieldset>			
					<fieldset class="fieldset_submit">
						<legend>{L_SEARCH}</legend>
						<input type="submit" name="valid_search" value="{L_SEARCH}" class="submit" />			
					</fieldset>
					
					# START list #
					<div class="module_position" style="width:100%;">					
						<div class="msg_top_l"></div>		
						<div class="msg_top_r"></div>
						<div class="msg_top"></div>
						<div class="msg_container">
							<div class="msg_top_row">
								<div class="msg_pseudo_mbr">
									{list.USER_ONLINE} {list.USER_PSEUDO}
								</div>
								<span class="text_strong" style="float:left;">&nbsp;&nbsp;{L_TOPIC}: {list.U_TITLE}</span>
								<span class="text_small" style="float: right;">{L_ON}: {list.DATE}</span>&nbsp;
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										{list.CONTENTS}
									</div>									
								</div>
							</div>
						</div>	
						<div class="msg_bottom_l"></div>		
						<div class="msg_bottom_r"></div>
						<div class="msg_bottom"><span class="text_small">{L_RELEVANCE}: {list.RELEVANCE}%</span></div>
					</div>
					<br />
					# END list #	
				</form>			
			</div>	
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		