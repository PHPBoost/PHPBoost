<script type="text/javascript">
<!--
function CheckForm() {
	if (document.getElementById('name').value == '') {
		document.getElementById('name').select();
		window.alert({JL_REQUIRE_TITLE});
		return false;
	}
	if (document.getElementById('feed_url').options[0].selected || document.getElementById('feed_url').options[1].selected) {
		document.getElementById('feed_url').select();
		window.alert({JL_REQUIRE_FEED});
		return false;
	}
	return true;
}
-->
</script>
<div id="admin_contents">
	<form action="feed.php?token={TOKEN}" method="post" class="fieldset_content" onsubmit="return CheckForm();">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<dl>
				<dt><label for="name">* {L_NAME}</label></dt>
				<dd><label><input type="text" size="18" name="name" id="name" class="text" value="{NAME}" /></label></dd>
			</dl>
			<dl>
				<dt><label for="location">* {L_LOCATION}</label></dt>
				<dd><label><select name="location" id="location">{LOCATIONS}</select></label></dd>
			</dl>
			<dl>
				<dt><label for="feed_url">* {L_FEED}</label></dt>
				<dd><select name="feed_url" id="feed_url">
					<option
						value="null"
						# IF C_NEW # selected="selected"# ENDIF #
						style="font-weight:bold; text-transform:uppercase; padding:1px; text-align:center;">
						{L_AVAILABLES_FEEDS}
					</option>
					<option value="null" style="text-align:center;">------------------------------</option>
					# START modules #
						<option
							value="{modules.URL}"
							{modules.SELECTED}
							style="font-weight:bold; text-transform:uppercase; padding:1px; text-align:center;">
							-- {modules.NAME} --
						</option>
						# START modules.feeds_urls #
							 <option value="{modules.feeds_urls.URL}"{modules.feeds_urls.SELECTED}>
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
				<dt>{L_AUTHS}</dt>
				<dd>{AUTH_MENUS}</dd>
			</dl>
		</fieldset>		
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}" />
			# IF C_EDIT #<input type="hidden" name="id" value="{IDMENU}" /># ENDIF #
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />			
		</fieldset>	
	</form>
</div>
