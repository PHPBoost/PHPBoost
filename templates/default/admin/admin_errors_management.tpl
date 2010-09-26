		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ERRORS}</li>
                <li>
                    <a href="admin_errors.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/errors.png" alt="" /></a>
                    <br />
                    <a href="admin_errors.php" class="quick_link">${i18n('logged_errors')}</a>
                </li>
                <li>
                    <a href="errors/?url=/404/list/"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/errors.png" alt="" /></a>
                    <br />
                    <a href="errors/?url=/404/list/" class="quick_link">${i18n('404_errors')}</a>
                </li>
			</ul>
		</div>
		
		<script type="text/javascript">
		<!--
		function Confirm_del() {
			return confirm("{L_ERASE_RAPPORT}");
		}
		-->	
		</script>
		<div id="admin_contents">
			<form action="admin_errors.php" method="post" class="fieldset_content" onsubmit="javascript:return Confirm_del()">
				<fieldset>
					<legend>{L_ERASE}</legend>
					<dl>
						<dt><label>{L_ERASE_RAPPORT}</label><br /><span>{L_ERASE_RAPPORT_EXPLAIN}!</span></dt>
						<dd><label><input type="submit" name="erase" value="{L_ERASE}" class="reset" /> </label></dd>
					</dl>
				</fieldset>
				<input type="hidden" name="token" value="{TOKEN}" />
			</form>
			
			<br />
			
			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_ERRORS}
					</th>
				</tr>
				<tr> 
					<td class="row2" style="text-align: center;" colspan="2">
						<a href="admin_errors.php?all=1">${i18n('all_errors')}</a>
					</td>
				</tr>
				<tr> 
					<td class="row1" style="text-align: center;">
						{L_DESC}
					</td>
					<td  class="row1" style="text-align: center;">
						{L_DATE}
					</td>
				</tr>	
				# START errors #
				<tr>
					<td class="row2">
						<p class="{errors.CLASS}" style="width:auto;margin:auto;padding:8px;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{errors.IMG}.png" style="float:left;padding-right:6px;" alt="" /> 
							<strong>{errors.ERROR_TYPE} : </strong>{errors.ERROR_MESSAGE}<br /><br /><br />
                            <em>{errors.ERROR_STACKTRACE}</em>
						</p>
					</td>
					<td class="row2" style="text-align:center;width:80px;">
						{errors.DATE}
					</td>
				</tr>
				# END errors #
			</table>
		</div>
