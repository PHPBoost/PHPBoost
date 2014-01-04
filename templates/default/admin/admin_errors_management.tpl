		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{@logged_errors}</li>
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
		
		<div id="admin_contents">
			# IF C_ERRORS #
			<form action="admin_errors.php" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_ERASE}</legend>
					<div class="form-element">
						<label>{L_ERASE_RAPPORT} <span class="field-description">{L_ERASE_RAPPORT_EXPLAIN}!</span></label>
						<div class="form-field"><label><button type="submit" name="erase" data-confirmation="{L_ERASE_RAPPORT}" value="true">{L_ERASE}</button></label></div>
					</div>
				</fieldset>
				<input type="hidden" name="token" value="{TOKEN}">
			</form>
			<div class="spacer">&nbsp;</div>
			# ENDIF #
			<table>
				<caption>{@logged_errors}</caption>
				# IF C_ERRORS #
				<thead>
					<tr> 
						<th style="width:80%;overflow:auto;">
							{L_DESC}
						</th>
						<th>
							${LangLoader::get_message('date', 'date-common')}
						</th>
					</tr>
				</thead>
				# IF C_MORE_ERRORS #
				<tfoot>
					<tr>
						<th colspan="2">
							<a href="admin_errors.php?all=1">{@all_errors}</a>
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START errors #
					<tr>
						<td> 
							<div class="message-helper {errors.CLASS}">
								<i class="fa fa-{errors.CLASS}"></i>
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
				# ELSE #
				<tbody>
					<tr>
						<td>{@no_error}</td>
					</tr>
				</tbody>
				# ENDIF #
			</table>
		</div>
