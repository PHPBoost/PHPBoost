		# INCLUDE forum_top #
		
		<script>
		<!--
		function check_form(){
			if(document.getElementByName('search').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
			return true;
		}
		-->
		</script>
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">
				&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
			</div>
			<div class="module-contents">
				<form action="search.php?token={TOKEN}#search_forum" method="post" onsubmit="return check_form();" class="fieldset-content">
					<p class="center">{L_REQUIRE}</p>
					<fieldset>
						<legend>{L_SEARCH_FORUM}</legend>
						<div class="form-element">
							<label for="search_forum_form">* {L_KEYWORDS}</label>
							<div class="form-field"><label><input type="text" size="35" id="search_forum_form" name="search" value="{SEARCH}"></label></div>
						</div>
						<div class="form-element">
							<label for="time">{L_DATE}</label>
							<div class="form-field"><label>	
								<select id="time" name="time">
									<option value="30000" selected="selected">{L_ALL}</option>
									<option value="1">1 {L_DAY}</option>
									<option value="7">7 {L_DAYS}</option>
									<option value="15">15 {L_DAYS}</option>
									<option value="30">1 {L_MONTH}</option>
									<option value="180">6 {L_MONTH}</option>
									<option value="360">1 {L_YEAR}</option>
								</select>
							</label></div>
						</div>
						<div class="form-element">
							<label for="idcat">{L_CATEGORY}</label>
							<div class="form-field"><label>
								<select name="idcat" id="idcat">
									# START cat #
										{cat.CAT}
									# END cat #
								</select>
							</label></div>
						</div>
						<div class="form-element">
							<label for="where">{L_OPTIONS}</label>
							<div class="form-field">
								<label><input type="radio" name="where" id="where" value="contents" {CONTENTS_CHECKED}> {L_CONTENTS}</label>
								<label><input type="radio" name="where" value="title" {TITLE_CHECKED}> {L_TITLE}</label>
								<label><input type="radio" name="where" value="all" {ALL_CHECKED}> {L_TITLE}/{L_CONTENTS}</label>
							</div>
						</div>
						<div class="form-element">
							<label for="colorate_result">{L_COLORATE_RESULT}</label>
							<div class="form-field">
								<label><input type="checkbox" name="colorate_result" id="colorate_result" value="1" {COLORATE_RESULT}></label>
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{L_SEARCH}</legend>
						<button type="submit" name="valid_search" value="true" class="submit">{L_SEARCH}</button>
					</fieldset>
					
					<p><span id="search_forum"></span></p>
					# INCLUDE message_helper #
					
					# IF C_FORUM_SEARCH #
					<div class="module-position" style="width:100%;">
						<div class="module-top-l"></div>
						<div class="module-top-r"></div>
						<div class="module-top">&nbsp;</div>
					</div>
					# ENDIF #
					<div class="content">
						# START list #
						<div class="message">
							<div class="message-user-infos">
								<div class="message-pseudo">
									{list.USER_ONLINE} {list.USER_PSEUDO}
								</div>
								<div class="message-level"></div>
								{list.USER_AVATAR}
							</div>
						
							<div class="message-container">
								<div class="message-date">
									<span class="actions">
										{L_TOPIC} : {list.U_TITLE} | <span class="smaller">{L_RELEVANCE}: {list.RELEVANCE}%</span>
									</span>
									<span>{L_ON} : {list.DATE}</span>
								</div>
								<div class="message-message">
									<div class="message-content">
										{list.CONTENTS}
									</div>
								</div>
							</div>
						</div>
						# END list #
					</div>
				</form>
			</div>	
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom">
				&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a> &raquo; {U_FORUM_CAT}
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		