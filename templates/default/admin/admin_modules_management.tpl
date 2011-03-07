		<script type="text/javascript">
		<!--
			var OpenCloseDiv = Class.create({
				id : '',
				id_click : '',
				already_click : false,
				initialize : function(id, id_click) {
					this.id = id;
					this.id_click = id_click;
					this.hide_div();
					this.change_picture_more();
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
					$(this.id_click).update('<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/admin/plus.png" alt="" class="valign_middle" style="width: 25px; height: auto;" />');
				},
				change_picture_less : function () {
					$(this.id_click).update('<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/admin/minus.png" alt="" class="valign_middle" style="width: 25px; height: auto;" />');
				},
				get_already_click : function () {
					return this.already_click;
				}
			});
			-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MODULES_MANAGEMENT}</li>
				<li>
					<a href="admin_modules.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_modules_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_add.php" class="quick_link">{L_ADD_MODULES}</a>
				</li>
				<li>
					<a href="admin_modules_update.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_update.php" class="quick_link">{L_UPDATE_MODULES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# IF C_MODULES_LIST #	
				<form action="admin_modules.php?uninstall=1" method="post">
					<table class="module_table" style="width:99%;margin-bottom:30px;">
						<tr> 
							<th colspan="5">
								{L_MODULES_INSTALLED}
							</th>
						</tr>
						<tr> 
							<td class="row2" colspan="5" style="text-align:center;">
								# INCLUDE message_helper #	
							</td>
						</tr>
					
						# IF C_MODULES_INSTALLED #
						<tr>
							<td class="row2" style="width:100px;text-align:center;">
								{L_NAME}
							</td>
							<td class="row2" style="text-align:center;">
								{L_DESC}
							</td>
							<td class="row2" style="width:50px;text-align:center;">
								{L_ACTIV}
							</td>
							<td class="row2" style="width:200px;text-align:center;">
								{L_AUTH_ACCESS}
							</td>
							<td class="row2" style="width:80px;text-align:center;">
								{L_UNINSTALL}
							</td>
						</tr>
						# ENDIF #
						# IF C_NO_MODULE_INSTALLED #
						<tr>
							<td class="row2" colspan="4" style="text-align:center;">
								<strong>{L_NO_MODULES_INSTALLED}</strong>
							</td>
						</tr>
						# ENDIF #

						# START installed #
							<tr> 	
								<td class="row2" style="text-align:center;">					
									<span id="m{installed.ID}"></span>
									<img class="valign_middle" src="{PATH_TO_ROOT}/{installed.ICON}/{installed.ICON}.png" alt="" /><br />
									<strong>{installed.NAME}</strong> <em>({installed.VERSION})</em>
								</td>
								<td class="row2">
									<div id="desc_explain{installed.ID}">
										<strong>{L_AUTHOR}:</strong> {installed.AUTHOR} {installed.AUTHOR_WEBSITE}<br />
										<strong>{L_DESC}:</strong> {installed.DESC}<br />
										<strong>{L_ADMIN}:</strong> {installed.ADMIN}<br />
										<br />
										<strong>{L_COMPAT}:</strong> PHPBoost {installed.COMPAT}
									</div>
									<div id="picture_desc{installed.ID}" style="text-align: center;" "></div>
								</td>
								<td class="row2">								
									<label><input type="radio" name="activ{installed.ID}" value="1" {installed.ACTIV_ENABLED} /> {L_YES}</label>
									<label><input type="radio" name="activ{installed.ID}" value="0" {installed.ACTIV_DISABLED} /> {L_NO}</label>
								</td>
								<td class="row2">
									<div id="auth_explain{installed.ID}">
										{installed.AUTH_MODULES}
									</div>
									<div id="picture_auth{installed.ID}" style="text-align: center;"></div>
								</td>
								<td class="row2">	
									<input type="submit" name="{installed.ID}" value="{L_UNINSTALL}" class="submit" />
								</td>
							</tr>
							<script type="text/javascript">
							<!--
								Event.observe(window, 'load', function() {
									var OpenCloseDivDesc = new OpenCloseDiv('desc_explain{installed.ID}', 'picture_desc{installed.ID}');
									var OpenCloseDivAuth = new OpenCloseDiv('auth_explain{installed.ID}', 'picture_auth{installed.ID}');
									
									Event.observe($('picture_desc{installed.ID}'), 'click', function() {
										OpenCloseDivDesc.change_status();
									});
									
									Event.observe($('picture_auth{installed.ID}'), 'click', function() {
										OpenCloseDivAuth.change_status();
									});
								});
							-->
							</script>							
						# END installed #
					</table>
					
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
						<input type="hidden" name="token" value="{TOKEN}" />
						&nbsp;&nbsp; 
						<input type="reset" value="{L_RESET}" class="reset" />
					</fieldset>
				</form>
			# ENDIF #
			
			
			# IF C_MODULES_DEL #				
				<form action="admin_modules.php?uninstall=1" method="post" class="fieldset_content">
					<fieldset>
						<legend>{L_DEL_MODULE}</legend>
						<div class="error_warning" style="width:500px;margin:auto;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> {L_DEL_DATA}
						</div>
						<br />
						<dl>
							<dt><label for="drop_files">{L_DEL_FILE}</label></dt>
							<dd><label><input type="radio" name="drop_files" value="1" /> {L_YES}</label>
							<label><input type="radio" name="drop_files" id="drop_files" value="0" checked="checked" /> {L_NO}</label></dd>
						</dl>
					</fieldset>		
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="idmodule" value="{IDMODULE}" />
						<input type="hidden" name="token" value="{TOKEN}" />
						<input type="submit" name="valid_del" value="{L_SUBMIT}" class="submit" />
					</fieldset>
				</form>
			# ENDIF #
		</div>
		