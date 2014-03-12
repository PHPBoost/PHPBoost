# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	# INCLUDE MSG #
	<table>
		<caption>{@modules.modules_available}</caption>
		# IF C_MODULES_AVAILABLE #
		<thead>
			<tr> 
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>{@modules.activate_module}</th>
				<th>{@modules.install_module}</th>
			</tr>
		</thead>
		<tbody>
			# START available #
			<tr>
				<td>
					<img src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="" />
					<span class="text-strong">{available.NAME}</span>
					<em>({available.VERSION})</em>
				</td>
				<td style="text-align:left;">
					<span class="text-strong">{@modules.author} :</span> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
					<span class="text-strong">{@modules.description} :</span> {available.DESCRIPTION}<br />
					<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {available.COMPATIBILITY}<br />
				</td>
				<td class="input-radio">
					<label><input type="radio" name="activated-{available.ID}" value="1" checked="checked"> {@modules.yes}</label>
					<label><input type="radio" name="activated-{available.ID}" value="0"> {@modules.no}</label>
				</td>
				<td>
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="add-{available.ID}" value="true">{@modules.install_module}</button>
				</td>
			</tr>
			# END available #
		</tbody>
	</table>
		# ELSE #
	</table>
	<div class="notice message-helper-small">{@modules.no_module_to_install}</div>
		# ENDIF #
</form>