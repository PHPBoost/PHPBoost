<div id="admin_contents">
	<form action="content.php" method="post" class="fieldset_content">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<dl>
				<dt><label for="activ">{L_TYPE}</label></dt>
				<dd><label> <select name="activ" id="activ">
					<option value="vertical" selected="selected">{L_VERTICAL_MENU}</option>
					<option value="horizontal" selected="selected">{L_HORIZONTAL_MENU}</option>
					<option value="tree" selected="selected">{L_TREE_MENU}</option>
					<option value="vertical_scroll" selected="selected">{L_VERTICAL_SCROLL_MENU}</option>
					<option value="horizontal_scroll" selected="selected">{L_HORIZONTAL_SCROLL_MENU}</option>
				</select> </label></dd>
			</dl>
			<dl>
				<dt><label for="location">* {L_LOCATION}</label></dt>
				<dd><label><select name="location" id="location">{LOCATIONS}</select></label></dd>
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
		{TEST}
	    <script type="text/javascript">
	    <!--
	    Sortable.create('menu', {tree:true,scroll:window});
	    -->
	    </script>
	    <a href="#" onclick="alert(Sortable.serialize('menu'));return false">serialize 1</a>
			
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="action" value="{ACTION}" />
			<input type="hidden" name="id" value="{IDMENU}" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
			<input type="reset" value="{L_RESET}" class="reset" />					
		</fieldset>	
	</form>
</div>