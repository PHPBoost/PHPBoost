		<script type="text/javascript">
		<!--
		function check_form_conf(o)
		{
			return true;
		}	
		-->
		</script>
				
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_PANEL}</li>
				<li>
					<a href="admin_panel.php"><img src="panel.png" alt="" /></a>
					<br />
					<a href="admin_panel.php" class="quick_link">{L_PANEL_CONFIG}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_panel.php" method="post" onsubmit="return check_form_conf(this);" class="fieldset_content">
			
			<table style="width:100%;" cellspacing="10px">
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" colspan="2">
							# START top #
							{top.NAME}  <a href="admin_panel.php?delete={top.LOCATION}-{top.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END top #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top;" witdh="50%">
							# START aboveleft #
							{aboveleft.NAME} <a href="admin_panel.php?delete={aboveleft.LOCATION}-{aboveleft.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END aboveleft #
					</td>
					<td style="background-color:#99CCFF; vertical-align:top;"  width="50%">
							# START aboveright #
							{aboveright.NAME} <a href="admin_panel.php?delete={aboveright.LOCATION}-{aboveright.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END aboveright #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top;" colspan="2">
							# START center #
							{center.NAME}  <<a href="admin_panel.php?delete={center.LOCATION}-{center.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END center #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top;" width="50%">
							# START belowleft #
							{belowleft.NAME}  <a href="admin_panel.php?delete={belowleft.LOCATION}-{belowleft.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END belowleft #
					</td>
					<td style="background-color:#99CCFF; vertical-align:top;" width="50%">
							# START belowright #
							{belowright.NAME} <a href="admin_panel.php?delete={belowright.LOCATION}-{belowright.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END belowright #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top;" colspan="2">
							# START bottom #
							{bottom.NAME}  <a href="admin_panel.php?delete={bottom.LOCATION}-{bottom.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END bottom #
							&nbsp;
					</td>
				</tr>
			</table>
			
				<fieldset>
					<legend>{L_PANEL_LEGEND}</legend>
					<dl>
						<dt><label for="panel_module">* MODULE</label></dt>
						<dd><select name="panel_module" id="panel_module">
							# START options_module #
							<option value="{options_module.ID}">{options_module.NAME}</option>
							# END options_module #
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="panel_type">* TYPE</label></dt>
						<dd><select name="panel_type" id="panel_type">
							<optgroup label="Summary">
							<option value="0" selected="selected">Summary Latest</option>
							<option value="1">Summary Random</option>
							<option value="2">Summary Popular</option>
							</optgroup>
							<optgroup label="Content">
							<option value="10">Content Home page</option>
							</optgroup>							
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="panel_location">* LOCATION</label></dt>
						<dd><select name="panel_location" id="panel_location">
							# START options_location #
							<option value="{options_location.ID}">{options_location.NAME}</option>
							# END options_location #
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="panel_cat">* CATEGORY</label></dt>
						<dd><select name="panel_cat" id="panel_cat">
							<option value="0">Toutes</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="50">50</option>					
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="panel_limit_max">* LIMITE</label></dt>
						<dd><input type="text" id="panel_limit_max" name="panel_limit_max" value="0" />
						</dd>
					</dl>
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>					
			</form>
		</div>
		