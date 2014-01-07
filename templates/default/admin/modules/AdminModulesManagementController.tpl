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
			$(this.id_click).className = 'fa fa-plus';
		},
		change_picture_less : function () {
			$(this.id_click).className = 'fa fa-minus';
		},
		get_already_click : function () {
			return this.already_click;
		}
	});
	-->
</script>
<form action="{REWRITED_SCRIPT}" method="post">
	<table>
		<caption>{@modules.installed_not_activated_modules}</caption>
		# IF C_MODULES_NOT_ACTIVATED #
		<thead>
			<tr>
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>{@modules.activated_module}</th>
				<!-- <th>
							{@modules.authorization}
				</th> -->
				<th>{@modules.delete}</th>
			</tr>
		</thead>
		<tbody>
			# START errors #
			<tr>
				# INCLUDE errors.MSG #
			</tr>
			# END errors #
			
			# START modules_not_activated #
			<tr>
				<td>
					<span id="m{modules_not_activated.ID}"></span>
					<img src="{PATH_TO_ROOT}/{modules_not_activated.ICON}/{modules_not_activated.ICON}.png" alt="" /><br />
					<span class="text-strong">{modules_not_activated.NAME}</span> <em>({modules_not_activated.VERSION})</em>
				</td>
				<td>
					<div id="desc_explain{modules_not_activated.ID}" style="text-align:left;display: none;">
						<span class="text-strong">{@modules.name} :</span> # IF modules_not_activated.C_AUTHOR #<a href="mailto:{modules_not_activated.AUTHOR_EMAIL}">{modules_not_activated.AUTHOR}</a># ELSE #{modules_not_activated.AUTHOR}# ENDIF # # IF modules_not_activated.C_AUTHOR_WEBSITE #<a href="{modules_not_activated.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@modules.description} :</span> {modules_not_activated.DESCRIPTION}<br />
						<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_not_activated.COMPATIBILITY}<br />
						<span class="text-strong">{@modules.php_version} :</span> {modules_not_activated.PHP_VERSION}
					</div>
					<div style="text-align: center;cursor: pointer;"><i class="fa fa-plus" id="picture_desc{modules_not_activated.ID}"></i></div>
				</td>
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
				<td class="input-radio">
					<label><input type="radio" name="activated-{modules_not_activated.ID}" value="1" # IF modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #> {@modules.yes}</label>
					<label><input type="radio" name="activated-{modules_not_activated.ID}" value="0" # IF NOT modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #> {@modules.no}</label>
				</td>
				<td>
					<button type="submit" name="delete-{modules_not_activated.ID}" value="true">{@modules.delete}</button>
				</td>
			</tr>
			# END modules_not_activated #
		</tbody>

		# ELSE #
	</table>
	<div class="message-helper notice message-helper-small">
		<i class="fa fa-notice"></i>
		<div class="message-helper-content">{@modules.no_deactivated_modules_available}</div>
	</div>
		# ENDIF #
		
	<table>
		<caption>{@modules.installed_activated_modules}</caption>
		# IF C_MODULES_ACTIVATED #
		<thead>
			<tr> 
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>{@modules.activated_module}</th>
				<th>{@modules.delete}</th>
			</tr>
		</thead>
		<tbody>
			# START modules_activated #
			<tr>
				<td>
					<span id="m{modules_activated.ID}"></span>
					<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_activated.ICON}/{modules_activated.ICON}.png" alt="" /><br />
					<span class="text-strong">{modules_activated.NAME}</span> <em>({modules_activated.VERSION})</em>
				</td>
				<td>
					<div id="desc_explain{modules_activated.ID}" style="text-align:left;display:none;">
						<span class="text-strong">{@modules.name} :</span> # IF modules_activated.C_AUTHOR #<a href="mailto:{modules_activated.AUTHOR_EMAIL}">{modules_activated.AUTHOR}</a># ELSE #{modules_activated.AUTHOR}# ENDIF # # IF modules_activated.C_AUTHOR_WEBSITE #<a href="{modules_activated.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@modules.description} :</span> {modules_activated.DESCRIPTION}<br />
						<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_activated.COMPATIBILITY}<br />
						<span class="text-strong">{@modules.php_version} :</span> {modules_activated.PHP_VERSION}
					</div>
					<div style="text-align: center;cursor: pointer;"><i class="fa fa-plus" id="picture_desc{modules_activated.ID}"></i></div>
				</td>
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
				<td class="input-radio">
					<label><input type="radio" name="activated-{modules_activated.ID}" value="1" # IF modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #> {@modules.yes}</label>
					<label><input type="radio" name="activated-{modules_activated.ID}" value="0" # IF NOT modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #> {@modules.no}</label>
				</td>
				<td>
					<button type="submit" name="delete-{modules_activated.ID}" value="true">{@modules.delete}</button>
				</td>
			</tr>
			# END modules_activated #
		</tbody>
	</table>
		# ELSE #
	</table>
	<div class="message-helper notice">
		<i class="fa fa-notice"></i>
		<div class="message-helper-content">{@modules.modules_available}</div>
	</div>
		# ENDIF #
		
	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" name="update_modules_configuration" value="true">{@modules.update}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		<button type="reset" value="true">{@modules.reset}</button>
	</fieldset>
</form>