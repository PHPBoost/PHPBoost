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
	<form action="feed.php" method="post" class="fieldset_content" onsubmit="return CheckForm();">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<div class="form-element">
				<label for="name">* {L_NAME}</label>
				<div class="form-field"><label><input type="text" size="18" name="name" id="name" value="{NAME}"></label></div>
			</div>
			<div class="form-element">
				<label for="location">* {L_LOCATION}</label>
				<div class="form-field"><label><select name="location" id="location">{LOCATIONS}</select></label></div>
			</div>
			<div class="form-element">
				<label for="feed_url">* {L_FEED}</label>
				<div class="form-field"><select name="feed_url" id="feed_url">
					<option
						value="null"
						# IF C_NEW # selected="selected"# ENDIF #
						style="font-weight:bold; text-transform:uppercase; padding:1px; text-align:center;">
						{L_AVAILABLES_FEEDS}
					</option>
					<option value="null" style="text-align:center;"></option>
					# START modules #
						<optgroup label="{modules.NAME}">
						# START modules.feeds_urls #
							 <option value="{modules.feeds_urls.URL}"{modules.feeds_urls.SELECTED}>
							 	{modules.feeds_urls.SPACE} {modules.feeds_urls.NAME}
							 	# IF modules.feeds_urls.FEED_NAME #({modules.feeds_urls.FEED_NAME})# ENDIF #
						 	</option>
						# END modules.feeds_urls #
						</optgroup>
						<option value="null" style="text-align:center;">-----------------------------</option>
					# END modules #
				</select></div>
			</div>
			<div class="form-element">
				<label for="activ">{L_STATUS}</label>
				<div class="form-field"><label>
					<select name="activ" id="activ">
					   # IF C_ENABLED #
							<option value="1" selected="selected">{L_ENABLED}</option>
							<option value="0">{L_DISABLED}</option>
						# ELSE #
                            <option value="1">{L_ENABLED}</option>
                            <option value="0" selected="selected">{L_DISABLED}</option>
						# ENDIF #					
					</select>
				</label></div>
			</div>
			<div class="form-element">
				<label>{L_AUTHS}</label>
				<div class="form-field">{AUTH_MENUS}</div>
			</div>
		</fieldset>		
	
		# INCLUDE filters #
	
		<fieldset class="fieldset-submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}">
			# IF C_EDIT #<input type="hidden" name="id" value="{IDMENU}"># ENDIF #
			<button type="submit" name="valid" value="true">{L_ACTION}</button>
			<input type="hidden" name="token" value="{TOKEN}">
		</fieldset>	
	</form>
</div>
