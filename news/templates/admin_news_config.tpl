		<script type="text/javascript">
		<!--
		function check_msg(){
			if(document.getElementById('nbr_column').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_NEWS_MANAGEMENT}</li>
				<li>
					<a href="admin_news.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news.php" class="quick_link">{L_NEWS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_news_add.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_add.php" class="quick_link">{L_ADD_NEWS}</a>
				</li>
				<li>
					<a href="admin_news_cat.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_cat.php" class="quick_link">{L_CAT_NEWS}</a>
				</li>
				<li>
					<a href="admin_news_config.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_config.php" class="quick_link">{L_CONFIG_NEWS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">

			<form action="admin_news_config.php?token={TOKEN}" method="post" onsubmit="return check_msg();" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_NEWS}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="pagination_news">* {L_NBR_NEWS_P}</label></dt>
						<dd><label><input type="text" maxlength="6" size="6" name="pagination_news" id="pagination_news" value="{PAGINATION}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="pagination_arch">* {L_NBR_ARCH_P}</label></dt>
						<dd><label><input type="text" maxlength="6" size="6" name="pagination_arch" id="pagination_arch" value="{PAGINATION_ARCH}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* {L_NBR_COLUMN_MAX}</label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_column" name="nbr_column" value="{NBR_COLUMN}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="activ_pagin">{L_ACTIV_PAGINATION}</label></dt>
						<dd>
							<label><input type="radio" {PAGIN_ENABLED} name="activ_pagin" id="activ_pagin" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {PAGIN_DISABLED} name="activ_pagin" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="type">{L_ACTIV_NEWS_BLOCK}</label></dt>
						<dd>
							<label><input type="radio" {BLOCK_ENABLED} name="type" id="type" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {BLOCK_DISABLED} name="type" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_com">{L_ACTIV_COM_NEWS}</label></dt>
						<dd>
							<label><input type="radio" {COM_ENABLED} name="activ_com" id="activ_com" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {COM_DISABLED} name="activ_com" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_icon">{L_ACTIV_ICON_NEWS}</label></dt>
						<dd>
							<label><input type="radio" {ICON_ENABLED} name="activ_icon" id="activ_icon" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {ICON_DISABLED} name="activ_icon" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_author">{L_DISPLAY_NEWS_AUTHOR}</label></dt>
						<dd>
							<label><input type="radio" {AUTHOR_ENABLED} name="display_author" id="display_author" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {AUTHOR_DISABLED} name="display_author" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_date">{L_DISPLAY_NEWS_DATE}</label></dt>
						<dd>
							<label><input type="radio" {DATE_ENABLED} name="display_date" id="display_date" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {DATE_DISABLED} name="display_date" value="0" /> {L_NO}</label>
						</dd>
					</dl>
				</fieldset>	
				
				<fieldset>
					<legend>{L_EDITO_WHERE}</legend>
					<dl>
						<dt><label for="activ_edito">{L_ACTIV_EDITO}</label></dt>
						<dd>
							<label><input type="radio" {EDITO_ENABLED} name="activ_edito" id="activ_edito" value="1" />	{L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {EDITO_DISABLED} name="activ_edito" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="edito_title">{L_TITLE}</label></dt>
						<dd><label><input type="text" maxlength="100" size="60" name="edito_title" id="edito_title" value="{TITLE}" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">{L_TEXT}</label>
					<label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="edito">{CONTENTS}</textarea>
					</label>
					<br />
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />					
					<script type="text/javascript">
					<!--				
					document.write('&nbsp;&nbsp; <input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />&nbsp;&nbsp;');
					-->
					</script>
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>
		