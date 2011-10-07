# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	# INCLUDE MSG #	
	<table class="module_table">
		<tr> 
			<th colspan="4">
				{@modules.modules_available}
			</th>
		</tr>
		# IF C_MODULES_AVAILABLE #
		<tr>
			<td class="row2" style="width:160px;text-align:center;">
				{@modules.name}
			</td>
			<td class="row2" style="text-align:center;">
				{@modules.description}
			</td>
			<td class="row2" style="width:100px;text-align:center;">
				{@modules.activate_module}
			</td>
		</tr>
		# ELSE #
		<tr>
			<td class="row2" colspan="4" style="text-align:center;">
				<strong>{@modules.no_module_to_install}</strong>
			</td>
		</tr>
		# ENDIF #
				
		# START available #
		<tr> 	
			<td class="row2">					
				<img class="valign_middle" src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="" /> <strong>{available.NAME}</strong> <em>({available.VERSION})</em>
			</td>
			<td class="row2">	
				<strong>{@modules.author}:</strong> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
				<strong>{@modules.description}:</strong> {available.DESCRIPTION}<br />
				<strong>{@modules.compatibility}:</strong> PHPBoost {available.COMPATIBILITY}<br />
			</td>
			<td class="row2" style="text-align:center;">	
				<input type="radio" name="activated-{available.ID}" value="1" checked="checked" /> {@modules.yes}
				<input type="radio" name="activated-{available.ID}" value="0" /> {@modules.no}
				<br /><br />
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="submit" name="add-{available.ID}" value="{@modules.install_module}" class="submit" />
				<input type="hidden" name="module_id" value="{available.ID}" />
			</td>
		</tr>						
		# END available #
	</table>			
</form>
		
		