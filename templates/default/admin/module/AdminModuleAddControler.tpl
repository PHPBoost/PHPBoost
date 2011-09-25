# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	<table class="module_table">
		<tr> 
			<th colspan="4">
				{@admin-module.modules_available}
			</th>
		</tr>
		# IF C_MODULES_AVAILABLE #
		<tr>
			<td class="row2" style="width:160px;text-align:center;">
				{@admin-module.name}
			</td>
			<td class="row2" style="text-align:center;">
				{@admin-module.description}
			</td>
			<td class="row2" style="width:100px;text-align:center;">
				{@admin-module.activate_module}
			</td>
		</tr>
		# ELSE #
		<tr>
			<td class="row2" colspan="4" style="text-align:center;">
				<strong>{@admin-module.no_modules_available}</strong>
			</td>
		</tr>
		# ENDIF #
				
		# START available #
		<tr> 	
			<td class="row2">					
				<img class="valign_middle" src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="" /> <strong>{available.NAME}</strong> <em>({available.VERSION})</em>
			</td>
			<td class="row2">	
				<strong>{@admin-module.author}:</strong> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
				<strong>{@admin-module.description}:</strong> {available.DESCRIPTION}<br />
				<strong>{@admin-module.compatibility}:</strong> PHPBoost {available.COMPATIBILITY}<br />
			</td>
			<td class="row2">	
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="submit" name="module_{available.ID}" value="{@admin-module.activate_module}" class="submit" />
			</td>
		</tr>						
		# END available #
	</table>			
</form>
		
		