<div id="admin_contents">
	<form action="links.php?action=save" method="post" class="fieldset_content" onsubmit="build_menu_elements_tree();">
		<fieldset> 
			<legend>{L_ACTION_MENUS}</legend>
			<dl>
				<dt><label for="name">{L_NAME}</label></dt>
				<dd><input type="text" name="name" id="name" value="{MENU_NAME}" /></dd>
			</dl>
			<dl>
				<dt><label for="name">{L_URL}</label></dt>
				<dd><input type="text" name="name" id="name" value="{MENU_LINK}" /></dd>
			</dl>
			<dl>
				<dt><label for="name">{L_IMAGE}</label></dt>
				<dd><input type="text" name="name" id="name" value="{MENU_IMG}" /></dd>
			</dl>
			<dl>
				<dt><label for="type">{L_TYPE}</label></dt>
				<dd>
					<label>
						<select name="type" id="type">
							# START type #
								<option value="{type.NAME}"{type.SELECTED}>{type.L_NAME}</option>
							# END type #
						</select>
					</label>
				</dd>
			</dl>
			<dl>
				<dt><label for="location">{L_LOCATION}</label></dt>
				<dd><label><select name="location" id="location">{LOCATIONS}</select></label></dd>
			</dl>
			<dl>
				<dt><label for="activ">{L_STATUS}</label></dt>
				<dd><label>
					<select name="enabled" id=""enabled"">
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
		
		<fieldset>
			<legend>{L_CONTENT}</legend>
			{MENU_TREE}
		    <script type="text/javascript">
		    <!--
		    Sortable.create('menu', {tree:true,scroll:window,format: /^menu_element_([0-9]+)$/});
		    -->
		    </script>
		    <script type="text/javascript">
			<!--
			function show_sub_menu_properties(id)
			{
				//Si les propriétés sont repliées, on les affiche
				if (document.getElementById("menu_element_" + id + "_properties").style.display == "none")
				{
					Effect.Appear("menu_element_" + id + "_properties");
					document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png";
				}
				//Sinon, on les cache
				else
				{
					Effect.Fade("menu_element_" + id + "_properties");
					document.getElementById("menu_element_" + id + "_more_image").src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png";
				}
			}
			
			function build_menu_elements_tree()
			{
				document.getElementById("menu_tree").value = Sortable.serialize('menu');
			}
			-->
			</script>
	    </fieldset>			
	
		<fieldset class="fieldset_submit">
			<legend>{L_ACTION}</legend>
			<input type="hidden" name="id" value="{MENU_ID}" />
			<input type="hidden" name="menu_tree" id="menu_tree" value="" />
			<input type="submit" name="valid" value="{L_ACTION}" class="submit" />
			<input type="reset" value="{L_RESET}" class="reset" />					
		</fieldset>
	</form>
</div>