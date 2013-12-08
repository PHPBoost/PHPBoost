		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ERRORS}</li>
                <li>
                    <a href="admin_errors.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/errors.png" alt="" /></a>
                    <br />
                    <a href="admin_errors.php" class="quick_link">{@logged_errors}</a>
                </li>
                <li>
                    <a href="errors/?url=/404/list/"><img src="{PATH_TO_ROOT}/templates/default/images/admin/errors.png" alt="" /></a>
                    <br />
                    <a href="errors/?url=/404/list/" class="quick_link">{@404_errors}</a>
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
			<form action="admin_errors.php" method="post" class="fieldset-content" onsubmit="javascript:return Confirm_del()">
				<fieldset>
					<legend>{L_ERASE}</legend>
					<div class="form-element">
						<label>{L_ERASE_RAPPORT}</label><br /><span>{L_ERASE_RAPPORT_EXPLAIN}!</span>
						<div class="form-field"><label><button type="submit" name="erase" value="true">{L_ERASE}</button> </label></div>
					</div>
				</fieldset>
				<input type="hidden" name="token" value="{TOKEN}">
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
						<a href="admin_errors.php?all=1">{@all_errors}</a>
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
						<p class="{errors.CLASS}" style="width:auto;margin:auto;">
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
