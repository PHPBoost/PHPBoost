		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CACHE}</li>
                <li>
                    <a href="admin_cache.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/cache.png" alt="" /></a>
                    <br />
                    <a href="admin_cache.php" class="quick_link">{L_CACHE}</a>
                </li>
                <li>
                    <a href="admin_cache.php?cache=syndication"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/rss.png" alt="" /></a>
                    <br />
                    <a href="admin_cache.php?cache=syndication" class="quick_link">{L_SYNDICATION}</a>
                </li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div id="error_msg">
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>	
				</div>
			</div>
			<script type="text/javascript">
			<!--
				//Javascript timeout to hide this message
				setTimeout('Effect.Fade("error_msg");', 1500);
			-->
			</script>
			# ENDIF #
			
			<form action="admin_cache.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_CACHE}</legend>
					<p>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/cache.png" alt="" class="img_left" />
						{L_EXPLAIN_SITE_CACHE}
						<br /><br />
					</p>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend>{L_GENERATE}</legend>
					<input type="submit" name="cache" value="{L_GENERATE}" class="submit" />
					<input type="hidden" name="token" value="{TOKEN}" />
				</fieldset>	
			</form>
		</div>
		