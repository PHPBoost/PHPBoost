<div id="admin_contents">
	<form action="content.php" method="post" class="fieldset_content">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<dl>
				<dt><label for="name">{L_NAME}</label></dt>
				<dd><label><input type="text" size="18" name="name" id="name" class="text" value="{NAME}" /></label></dd>
			</dl>
			<dl>
				<dt><label for="location">* {L_LOCATION}</label></dt>
				<dd><label><select name="location" id="location">{LOCATIONS}</select></label></dd>
			</dl>
			<dl>
				<dt></dt>
				<dd><select name="feed_url">
					# START modules #
						<option value="{modules.URL}" style="font-weight:bold; text-transform:uppercase; padding:1px; text-align:center;">-- {modules.NAME} --</option>
						# START modules.feeds_urls #
							 <option value="{modules.feeds_urls.URL}">
							 	{modules.feeds_urls.SPACE} {modules.feeds_urls.NAME}
							 	# IF modules.feeds_urls.FEED_NAME #({modules.feeds_urls.FEED_NAME})# ENDIF #
						 	</option>
						# END modules.feeds_urls #
					# END modules #
				</select></dd>
			</dl>
			<dl>
				<dt><label for="activ">{L_STATUS}</label></dt>
				<dd><label>
					<select name="activ" id="activ">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
                            <option value="1">{L_ENABLED}</option>
                            <option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #					
					</select>
				</label></dd>
			</dl>
			<dl>
				<dt><label for="auth">{L_AUTHS}</label></dt>
				<dd><label>{AUTH_MENUS}</label></dd>
			</dl>
		</fieldset>		
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}" />
			<input type="hidden" name="id" value="{IDMENU}" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />			
		</fieldset>	
	</form>
</div>
