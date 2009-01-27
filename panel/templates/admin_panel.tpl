<?php
/**
* admin_panel.tpl
*
* @author alain091	
* @copyright (c) 2009 alain091
* @license GPL
*
*/
?>


		<script type="text/javascript">
		//<![CDATA[
		function trim(stringToTrim) {
			return stringToTrim.replace(/^\s+|\s+$/g,"");
		}

		function Cat(an_id,a_name) {
			this.id=an_id;
			this.name=a_name;
		}

		var table_cats = new Array();
{TABLE_CATS}

		function check_form_conf(f)
		{
			if (f.panel_module.value == "0") {
				alert("Choisir un module SVP.");
				f.panel_module.focus();
				return false;			
			}
			var limit_max = trim(f.panel_limit_max.value);
			if (limit_max != "") {
				if(isNaN(limit_max) || (limit_max <= 0) ) {
					alert("La limite maxi doit être un nombre positif.");
					f.panel_limit_max.focus();
					return false;
				}
			}
			return true;
		}
		
		function change_cat(o)
		{
			var tmp = o.options[o.options.selectedIndex].value;
			if (tmp == 0) return;
			
			// effacer le contenu courant
			var o1 = document.getElementById("panel_cat");
			for (i=1; i<o1.length; i++) {
				o1[i] = null;
			}
			// mettre les cats du module	
			if (table_cats[tmp].length != 0) {
				o1.length = 1 + table_cats[tmp].length;
				for( i=0; i<table_cats[tmp].length; i++ ) {
					o1[i+1].value = table_cats[tmp][i].id;
					o1[i+1].text = table_cats[tmp][i].name;
				}
			}
		}
		//]]>
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
			<form id="f_form" action="admin_panel.php" method="post" onsubmit="return check_form_conf(this);" class="fieldset_content">
			
			<table style="width:100%;" cellspacing="10px">
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" colspan="2">
							# START top #
							{top.NAME}  <a href="admin_panel.php?delete={top.LOCATION}-{top.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END top #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" witdh="50%">
							# START aboveleft #
							{aboveleft.NAME} <a href="admin_panel.php?delete={aboveleft.LOCATION}-{aboveleft.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END aboveleft #
					</td>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;"  width="50%">
							# START aboveright #
							{aboveright.NAME} <a href="admin_panel.php?delete={aboveright.LOCATION}-{aboveright.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END aboveright #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" colspan="2">
							# START center #
							{center.NAME}  <<a href="admin_panel.php?delete={center.LOCATION}-{center.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END center #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" width="50%">
							# START belowleft #
							{belowleft.NAME}  <a href="admin_panel.php?delete={belowleft.LOCATION}-{belowleft.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END belowleft #
					</td>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" width="50%">
							# START belowright #
							{belowright.NAME} <a href="admin_panel.php?delete={belowright.LOCATION}-{belowright.NAME}"><img border="0" src="../templates/phpboost/images/french/delete.png" alt="supprimer"/></a><br />
							# END belowright #
					</td>
				</tr>
				<tr>
					<td style="background-color:#99CCFF; vertical-align:top; height:20px; min-height:20px;" colspan="2">
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
						<dd><select name="panel_module" id="panel_module" onchange="javascript:change_cat(this);" >
							<option value="0" selected="selected">(Aucun)</option>
							# START options_module #
							<option value="{options_module.ID}">{options_module.NAME}</option>
							# END options_module #
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
							<option value="0" selected="selected">(Toutes)</option>						
							</select>
						</dd>
					</dl>
					<dl>
						<dt><label for="panel_limit_max">* LIMITE</label></dt>
						<dd><input type="text" id="panel_limit_max" name="panel_limit_max" value="{LIMIT}" />
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />			
				</fieldset>					
			</form>
		</div>
		