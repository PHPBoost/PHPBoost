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
		<form action="admin_bugstracker_config.php?token={TOKEN}" method="post">
			<table class="module_table">
				# START config #
				<dl>
					<dt>{config.L_LABEL}</dt>
					<dd><input type="text" name="{config.NAME}" value="{config.VALUE}" /></dd>
				</dl>
				# END config #
				# START auth #
				<tr>
					<td class="row1" style="width:200px;">
						{auth.L_SELECT}
					</td>
					<td class="row2">
						{auth.SELECT}
					</td>
				</tr>
				# END auth #
			</table>
			
			<br /><br />
			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />				
			</fieldset>	
		</form>
	</div>