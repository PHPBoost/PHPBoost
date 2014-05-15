		<script>
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_NAME}");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
				if(document.getElementById('idcat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
		    }
			
			return true;
		}

		-->
		</script>

		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick-link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_cat.php#add_cat"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php#add_cat" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick-link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick-link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick-link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin-contents">
			# IF C_PREVIEW #
			<fieldset>
				<legend>
					{L_PREVIEW}
				</legend>
				<article>
					<header>
						<h1>
							{NAME}
						</h1>
					</header>
					<div class="content">
						<p>
							<strong>{L_DESC}:</strong> {PREVIEWED_CONTENTS}
							<br /><br />
							<strong>{L_CATEGORY}:</strong> 
							
							<a href="" title="{CAT}">{CAT}</a><br />
							
							<strong>{L_DATE}:</strong> {DATE}<br />
							<strong>{L_VIEWS}:</strong> {COMPT} {L_TIMES}
							
							<span class="spacer">&nbsp;</span>
						</p>
						<p class="center">
							<button type="button" name="{NAME}" class="visit" value="true">
								{L_VISIT}<i class="fa fa-globe"></i>
							</button>
						</p>
						<br />
					</div>
					<footer></footer>
				</article>
			</fieldset>
			# ENDIF #
			
			# INCLUDE message_helper #
			
			<form action="admin_web_add.php?token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_WEB_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" size="55" maxlength="50" name="name" id="name" value="{NAME}"></label></div>
					</div>
					<div class="form-element">
						<label for="idcat">* {L_CATEGORY}</label>
						<div class="form-field"><label>
							<select id="idcat" name="idcat">				
							# START select #				
								{select.CAT}				
							# END select #				
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="url">* {L_URL_LINK}</label>
						<div class="form-field"><label><input type="text" size="65" id="url" name="url" id="url" value="{URL}"></label></div>
					</div>
					<div class="form-element">
						<label for="compt">{L_VIEWS}</label>
						<div class="form-field"><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="{COMPT}"></label></div>
					</div>
					<div class="form-element-textarea">
						<label for="contents">{L_DESC}</label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="contents">{CONTENTS}</textarea> 
					</div>
					<div class="form-element">
						<label for="aprob">* {L_APROB}</label>
						<div class="form-field">
							<label><input type="radio" {CHECK_ENABLED} name="aprob" id="aprob" value="1"> {L_YES}</label>
							<label><input type="radio" {CHECK_DISABLED}  name="aprob" value="0"> {L_NO}</label></div>
					</div>
				</fieldset>		
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
					<button type="submit" name="previs" value="true">{L_PREVIEW}</button>
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</form>
		</div>
		