		<script type="text/javascript">
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

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick_link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick_link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick_link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick_link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">		
			# START web #
			<table class="module_table">
					<tr> 
						<th colspan="2">
							{L_PREVIEW}
						</th>
					</tr>
					<tr> 
						<td class="row1">
							<br />
							<table width="96%" border="0" style="border-collapse: collapse; margin: auto;" class="table_news" cellspacing="0" cellpadding="3">
								<tr>
									<th style="text-align: center;">
										<span class="text_subtitle">{web.NAME}</span>
									</th>
								</tr>
								<tr>					
									<td>						
										<p>
											<br /><br />
											
											<strong>&nbsp;{web.L_DESC}:</strong> {web.PREVIEWED_CONTENTS}
											
											<br /><br />
											
											<strong>&nbsp;{web.L_CAT}:</strong> 
											<a href="{PATH_TO_ROOT}/web/web.php?cat={web.IDCAT}" title="{web.CAT}">{web.CAT}</a><br />
											
											<strong>&nbsp;{web.L_DATE}:</strong> {web.DATE}<br />
											
											<strong>&nbsp;{web.L_VIEWS}:</strong> {COMPT} <br />
											<strong>&nbsp;{web.L_NOTE}:</strong> 0 <br />
											<strong>&nbsp;{web.L_COM}</strong>
											<br /><br />
										</p>
										<p style="text-align: center;">					
											<a href="{web.URL}"><img src="{PICTURES_DATA_PATH}/images/{LANG}/bouton_url.gif" alt="" title="" /></a>
										</p>
								
										<br /><br /><br />
										<span id="web">&nbsp;</span>
									</td>
								</tr>
								<tr>
									<td class="news_bottom">
										&nbsp;
									</td>	
								</tr>
							</table>
							<br />
						</td>
					</tr>
			</table>	

			<br /><br /><br />
			# END web #

			# INCLUDE message_helper #
			
			<form action="admin_web_add.php?token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_WEB_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" size="55" maxlength="50" name="name" id="name" value="{NAME}" class="text" /></label></dd>
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
						<dt><label for="url">* {L_URL_LINK}</label></dt>
						<dd><label><input type="text" size="65" id="url" name="url" id="url" value="{URL}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="compt">{L_VIEWS}</label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="{COMPT}" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">{L_DESC}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="contents">{CONTENTS}</textarea> 
						<br />
					</label>
					<dl>
						<dt><label for="aprob">* {L_APROB}</label></dt>
						<dd>
							<label><input type="radio" {CHECK_ENABLED} name="aprob" id="aprob" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {CHECK_DISABLED}  name="aprob" value="0" /> {L_NO}</label></dd>
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
		