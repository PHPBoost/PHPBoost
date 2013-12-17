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
						<label>{L_ERASE_RAPPORT} <span class="field-description">{L_ERASE_RAPPORT_EXPLAIN}!</span></label>
						<div class="form-field"><label><button type="submit" name="erase" value="true">{L_ERASE}</button></label></div>
					</div>
				</fieldset>
				<input type="hidden" name="token" value="{TOKEN}">
			</form>
			
			<br />
			
		<table>
			<caption>
				{L_ERRORS}
			</caption>
			<thead>
				<tr> 
					<th>
						{L_DESC}
					</th>
					<th>
						{L_DATE}
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="2">
						<a href="admin_errors.php?all=1">{@all_errors}</a>
					</th>
				</tr>
			</tfoot>
			<tbody>
				# START errors #
				<tr>
					<td> 
						<div class="message-helper {errors.CLASS}">
							<i class="icon-{errors.CLASS}"></i>
							<div class="message-helper-content">
								<strong>{errors.ERROR_TYPE} : </strong>{errors.ERROR_MESSAGE}<br /><br /><br />
                            	<em>{errors.ERROR_STACKTRACE}</em>
							</div>
						</div>
					</td>
					<td>
						{errors.DATE}
					</td>
				</tr>
				# END errors #
			</tbody>
		</table>
			
		</div>
