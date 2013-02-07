		<script type="text/javascript">
		<!--
			var OpenCloseDiv = Class.create({
				id : '',
				id_click : '',
				already_click : false,
				initialize : function(id, id_click) {
					this.id = id;
					this.id_click = id_click;
				},
				open : function () {
					this.already_click = true;
					$(this.id).appear({duration: 0.5});
					this.change_picture_less();
				},
				close : function () {
					this.already_click = false;
					$(this.id).fade({duration: 0.3});
					this.change_picture_more();
				},
				change_status : function ()	{
					if (this.already_click == true) {
						this.close();
					}
					else {
						this.open();
					}
				},
				hide_div : function () {
					$(this.id).hide();
				},
				change_picture_more : function () {
					$(this.id_click).update('<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/admin/plus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" />');
				},
				change_picture_less : function () {
					$(this.id_click).update('<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/admin/minus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" />');
				},
				get_already_click : function () {
					return this.already_click;
				}
			});
			-->
		</script>
		<form action="{REWRITED_SCRIPT}" method="post">
			<table class="module_table" style="width:99%;margin-bottom:30px;">
				<tr> 
					<th colspan="5">
						{@modules.installed_not_activated_modules}
					</th>
				</tr>
				# START errors #
				<tr>
					# INCLUDE errors.MSG #
				</tr>
				# END errors #
				# IF C_MODULES_NOT_ACTIVATED #
				<tr>
					<td class="row2" style="width:100px;text-align:center;">
						{@modules.name}
					</td>
					<td class="row2" style="text-align:center;">
						{@modules.description}
					</td>
					<td class="row2" style="width:50px;text-align:center;">
						{@modules.activated_module}
					</td>
					<!-- <td class="row2" style="width:200px;text-align:center;">
						{@modules.authorization}
					</td> -->
					<td class="row2" style="width:80px;text-align:center;">
						{@modules.delete}
					</td>
				</tr>
				# ELSE #
				<tr>
					<td class="row2" colspan="4" style="text-align:center;">
						<strong>{@modules.no_deactivated_modules_available}</strong>
					</td>
				</tr>
				# ENDIF #

				# START modules_not_activated #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<span id="m{modules_not_activated.ID}"></span>
							<img class="valign_middle" src="{PATH_TO_ROOT}/{modules_not_activated.ICON}/{modules_not_activated.ICON}.png" alt="" /><br />
							<strong>{modules_not_activated.NAME}</strong> <em>({modules_not_activated.VERSION})</em>
						</td>
						<td class="row2">
							<div id="desc_explain{modules_not_activated.ID}" style="display: none;">
								<strong>{@modules.name} :</strong> {modules_not_activated.AUTHOR} {modules_not_activated.AUTHOR_WEBSITE}<br />
								<strong>{@modules.description} :</strong> {modules_not_activated.DESCRIPTION}<br />
								<strong>{@modules.compatibility} :</strong> PHPBoost {modules_not_activated.COMPATIBILITY}<br />
								<strong>{@modules.php_version} :</strong> {modules_not_activated.PHP_VERSION}
							</div>
							<div id="picture_desc{modules_not_activated.ID}" style="text-align: center;" "><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" /></div>
						</td>
						<td class="row2">								
							<label><input type="radio" name="activated-{modules_not_activated.ID}" value="1" # IF modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF # /> {@modules.yes}</label>
							<label><input type="radio" name="activated-{modules_not_activated.ID}" value="0" # IF NOT modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF # /> {@modules.no}</label>
						</td>
						<!-- <td class="row2">
							<div id="auth_explain{modules_not_activated.ID}" style="display: none;">
								{modules_not_activated.AUTHORIZATIONS}
							</div>
							<div id="picture_auth{modules_not_activated.ID}" style="text-align: center;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" /></div>
						</td>-->
						<td class="row2">
							<a href="{modules_not_activated.U_DELETE_LINK}">	
								<input name="delete-{modules_not_activated.ID}" value="{@modules.delete}" class="submit" style="width:70px;text-align:center;"/>
							</a>
						</td>
					</tr>
					<script type="text/javascript">
					<!--
						Event.observe(window, 'load', function() {
							var OpenCloseDivDesc = new OpenCloseDiv('desc_explain{modules_not_activated.ID}', 'picture_desc{modules_not_activated.ID}');
							var OpenCloseDivAuth = new OpenCloseDiv('auth_explain{modules_not_activated.ID}', 'picture_auth{modules_not_activated.ID}');
							
							Event.observe($('picture_desc{modules_not_activated.ID}'), 'click', function() {
								OpenCloseDivDesc.change_status();
							});
							
							Event.observe($('picture_auth{modules_not_activated.ID}'), 'click', function() {
								OpenCloseDivAuth.change_status();
							});
						});
					-->
					</script>							
				# END modules_not_activated #
			</table>
			
			<table class="module_table" style="width:99%;margin-bottom:30px;">
				<tr> 
					<th colspan="5">
						{@modules.installed_activated_modules}
					</th>
				</tr>
				# IF C_MODULES_ACTIVATED #
				<tr>
					<td class="row2" style="width:100px;text-align:center;">
						{@modules.name}
					</td>
					<td class="row2" style="text-align:center;">
						{@modules.description}
					</td>
					<td class="row2" style="width:50px;text-align:center;">
						{@modules.activated_module}
					</td>
					<!-- <td class="row2" style="width:200px;text-align:center;">
						{@modules.authorization}
					</td>-->
					<td class="row2" style="width:80px;text-align:center;">
						{@modules.delete}
					</td>
				</tr>
				# ELSE #
				<tr>
					<td class="row2" colspan="4" style="text-align:center;">
						<strong>{@modules.modules_available}</strong>
					</td>
				</tr>
				# ENDIF #

				# START modules_activated #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<span id="m{modules_activated.ID}"></span>
							<img class="valign_middle" src="{PATH_TO_ROOT}/{modules_activated.ICON}/{modules_activated.ICON}.png" alt="" /><br />
							<strong>{modules_activated.NAME}</strong> <em>({modules_activated.VERSION})</em>
						</td>
						<td class="row2">
							<div id="desc_explain{modules_activated.ID}" style="display: none;">
								<strong>{@modules.name} :</strong> {modules_activated.AUTHOR} {modules_activated.AUTHOR_WEBSITE}<br />
								<strong>{@modules.description} :</strong> {modules_activated.DESCRIPTION}<br />
								<strong>{@modules.compatibility} :</strong> PHPBoost {modules_activated.COMPATIBILITY}<br />
								<strong>{@modules.php_version} :</strong> {modules_activated.PHP_VERSION}
							</div>
							<div id="picture_desc{modules_activated.ID}" style="text-align: center;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" /></div>
						</td>
						<td class="row2">								
							<label><input type="radio" name="activated-{modules_activated.ID}" value="1" # IF modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF # /> {@modules.yes}</label>
							<label><input type="radio" name="activated-{modules_activated.ID}" value="0" # IF NOT modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF # /> {@modules.no}</label>
						</td>
						<!-- <td class="row2">
							<div id="auth_explain{modules_activated.ID}" style="display: none;">
								{modules_activated.AUTHORIZATIONS}
							</div>
							<div id="picture_auth{modules_activated.ID}" style="text-align: center;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" alt="" class="valign_middle" style="cursor: pointer; width: 25px; height: auto;" /></div>
						</td>-->
						<td class="row2">
							<a href="{modules_activated.U_DELETE_LINK}">	
								<input name="delete-{modules_activated.ID}" value="{@modules.delete}" class="submit" style="width:70px;text-align:center;"/>
							</a>
						</td>
					</tr>
					<script type="text/javascript">
					<!--
						Event.observe(window, 'load', function() {
							var OpenCloseDivDesc = new OpenCloseDiv('desc_explain{modules_activated.ID}', 'picture_desc{modules_activated.ID}');
							var OpenCloseDivAuth = new OpenCloseDiv('auth_explain{modules_activated.ID}', 'picture_auth{modules_activated.ID}');
							
							Event.observe($('picture_desc{modules_activated.ID}'), 'click', function() {
								OpenCloseDivDesc.change_status();
							});
							
							Event.observe($('picture_auth{modules_activated.ID}'), 'click', function() {
								OpenCloseDivAuth.change_status();
							});
						});
					-->
					</script>							
				# END modules_activated #
			</table>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="update_modules_configuration" value="{@modules.update}" class="submit" />
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="hidden" name="update" value="true" />
				&nbsp;&nbsp; 
				<input type="reset" value="{@modules.reset}" class="reset" />
			</fieldset>
		</form>			

	