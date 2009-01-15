<?php
/*
 * @package     Bugstracker
 * @author      alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license     GPL
 */
?>
	<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_BUGS_MANAGEMENT}</li>
			<li>
				<a href="admin_bugstracker_config.php"><img src="bugstracker.png" alt="" /></a>
				<br />
				<a href="admin_bugstracker_config.php" class="quick_link">{L_BUGS_CONFIG}</a>
			</li>
			<li>
				<a href="admin_bugstracker_parameters.php"><img src="bugstracker.png" alt="" /></a>
				<br />
				<a href="admin_bugstracker_parameters.php" class="quick_link">{L_BUGS_PARAMETERS}</a>
			</li>
		</ul>
	</div>
		
	<div id="admin_contents">
			
				# START error_handler #
				<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
						<br />	
					</div>
				</div>
				# END error_handler #	

				# START update #
			<script type="text/javascript">
			<!--
			String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };
			function check_form_update(f){
				if( (f.l_weight.value == "") || isNaN(f.l_weight.value) ) {
					alert("{L_ERROR_WEIGHT}");
					f.l_weight.focus();
					return false;
				}
				var str = f.l_label.value.trim();
				f.l_label.value = str;
				if(str == "") {
					alert("{L_ERROR_LABEL}");
					f.l_label.focus();
					return false;
			    }
				return true;
			}
			-->
			</script>			
				
				<form name="form_update" action="admin_bugstracker_parameters.php" method="post" style="margin:auto;" onsubmit="return check_form_update(this);" class="fieldset_content">
					<fieldset>
						<legend>{L_ADD_SEVERITY}</legend>
						<p>{L_REQUIRE}</p>

						<dl>
							<dt><label for="l_weight">(*) {L_WEIGHT}</label></dt>
							<dd><input type="text" maxlength="10" size="10" id="l_weight" name="l_weight" value="{update.WEIGHT}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="l_label">(*) {L_LABEL}</label></dt>
							<dd><input type="text" maxlength="50" size="30" id="l_label" name="l_label" value="{update.LABEL}" class="text" /></dd>
						</dl>
					</fieldset>			
					
					<fieldset class="fieldset_submit">
						<legend>{L_UPDATE}</legend>
						<input type="submit" name="{L_SUBMIT_NAME]" value="{L_SUBMIT_VALUE}" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="{L_RESET}" class="reset" />				
					</fieldset>	
				</form>
				# END update #
				
			# START all #
	<script type="text/javascript">
	function on_nature_changed(f)
	{
		f.submit();
	}
	</script>
			<table class="module_table" style="width: 98%;">	
				<tr>
					<th colspan="8" class="row1">
						{L_PROFIL}
						&nbsp;&nbsp;<a href="{all.U_ADD}"><img src="../templates/main2/images/french/add.png" alt="" class="valign_middle" /></a>
					</th>
				</tr>
				<tr>
					<td colspan="8" class="row1" style="text-align:center;">
						<form name="form_nature" method="post" action="admin_bugstracker_parameters.php">
								<select name="nature" id="nature" onchange="javascript:on_nature_changed(this.form);">
								# START all.select_nature #
									<option value="{all.select_nature.VALUE}" {all.select_nature.SELECT}>{all.select_nature.TEXT}</option>
								# END all.select_nature #
								</select>
						</form>
					</td>
				</tr>
				<tr>
					<td colspan="8" class="row1">
						{PAGINATION}&nbsp;
					</td>
				</tr>
				<tr style="font-weight:bold;text-align: center;">
					<td width="20%" class="row3">
						{L_ID}
					</td>
					<td width="20%" class="row3">
						{L_WEIGHT}
					</td>
					<td width="60%" class="row3">
						{L_LABEL}
					</td>	
				</tr>
				
				# START all.data #
				<tr> 
					<td width="20%" class="row2" style="text-align:center;padding:4px 0px;">
						<a href="{all.data.U_EDIT}">{all.data.ID}</a>
					</td>
					<td width="20%" class="row2" style="text-align:center;padding:4px 0px;">
						{all.data.WEIGHT}
					</td>
					<td width="60%" class="row2" style="text-align:center;padding:4px 0px;"> 
						{all.data.LABEL}
					</td>
				</tr>
				# END all.data #

				<tr>
					<td colspan="8" class="row1">
						<span style="float:left;">{PAGINATION}</span>
					</td>
				</tr>
			</table>
			# END all #
			
			# START edit #
		<script type="text/javascript">
			<!--
			String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };
			function check_form_edit(f){
				if( (f.l_weight.value == "") || isNaN(f.l_weight.value) ) {
					alert("{L_ERROR_WEIGHT}");
					f.l_weight.focus();
					return false;
				}
				var str = f.l_label.value.trim();
				f.l_label.value = str;
				if( str == "") {
					alert("{L_ERROR_LABEL}");
					f.l_label.focus();
					return false;
			    }
				return true;
			}
			-->
		</script>			

		<form name="form_edit" action="admin_bugstracker_parameters.php" method="post" style="margin:auto;" onsubmit="return check_form_edit(this);" class="fieldset_content">

			<fieldset>
						<legend>{L_LEGEND}</legend>
						<p>{L_REQUIRE}</p>
						
						<dl>
							<dt>Nature</dt>
							<dd>
								<select name="nature" id="nature">
								# START edit.select_nature #
									<option value="{edit.select_nature.VALUE}" {edit.select_nature.SELECT}>{edit.select_nature.TEXT}</option>
								# END edit.select_nature #
								</select>
							</dd>
						</dl>
						<dl>
							<dt><label for="l_weight">(*) {L_WEIGHT}</label></dt>
							<dd><input type="text" maxlength="10" size="10" id="l_weight" name="l_weight" value="{edit.WEIGHT}" class="text" /></dd>
						</dl>
						<dl>
							<dt><label for="l_label">(*) {L_LABEL}</label></dt>
							<dd><input type="text" maxlength="50" size="30" id="l_label" name="l_label" value="{edit.LABEL}" class="text" /></dd>
						</dl>
					</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="{L_SUBMIT_NAME}" value="{L_SUBMIT_VALUE}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>	
		</form>

			# END edit #
	</div>