    # INCLUDE UPLOAD_FORM #
    
    # INCLUDE MSG #
    <form action="{REWRITED_SCRIPT}" method="post">
        # IF C_UPDATES #
            <div class="warning" style="width:300px;margin:auto;">{@modules.updates_available}</div>&nbsp;&nbsp;
            <table class="module_table">
                <tr><th colspan="5">{@modules.updates_available}</th></tr>
                <tr>
					<td class="row2" style="width:160px;text-align:center;">
						{@modules.name}
					</td>
					<td class="row2" style="text-align:center;">
						{@modules.description}
					</td>
					<td class="row2" style="width:100px;text-align:center;">
						{@modules.upgrade_module}
					</td>
				</tr>
                # START modules_upgradable #
                <tr> 	
					<td class="row2">					
						<img class="valign_middle" src="{PATH_TO_ROOT}/{modules_upgradable.ICON}/{modules_upgradable.ICON}.png" alt="" /> <strong>{modules_upgradable.NAME}</strong> <em>({modules_upgradable.VERSION})</em>
					</td>
					<td class="row2">	
						<strong>{@modules.author}:</strong> {modules_upgradable.AUTHOR} {modules_upgradable.AUTHOR_WEBSITE}<br />
						<strong>{@modules.description}:</strong> {modules_upgradable.DESCRIPTION}<br />
						<strong>{@modules.compatibility}:</strong> PHPBoost {modules_upgradable.COMPATIBILITY}<br />
						<strong>{@modules.php_version} :</strong> {modules_upgradable.PHP_VERSION}
					</td>
					<td class="row2" style="text-align:center;">
						<input type="hidden" name="token" value="{TOKEN}" />
						<input type="submit" name="upgrade-{modules_upgradable.ID}" value="{@modules.upgrade_module}" class="submit" />
						<input type="hidden" name="module_id" value="{modules_upgradable.ID}" />
					</td>
				</tr>	
                # END modules_upgradable #
            </table>
    </form>
	# ELSE #
          &nbsp;<div class="warning" style="width:300px;margin:auto;margin-top:100px;">{@modules.no_upgradable_module_available}</div>
	# ENDIF #