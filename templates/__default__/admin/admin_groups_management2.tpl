<script>
	function check_form_add_mbr(){
		if(document.getElementById('login_mbr').value == "") {
			alert("{@warning.username}");
			return false;
		}
		return true;
	}

	function check_form(){
		if(document.getElementById('name').value == "") {
			alert("{@warning.title}");
			return false;
		}
		return true;
	}

	function img_change(url)
	{
		if( document.images && url != '' )
		{
			document.getElementById('img_group_change').style.display = 'inline';
			document.getElementById('img_group_change').src = "{PATH_TO_ROOT}/images/group/" + url;
		}
		else
			document.getElementById('img_group_change').style.display = 'none';
	}
	function XMLHttpRequest_search()
	{
		var login = jQuery('#login').val();
		if( login != "" )
		{
			jQuery('#search_img').append('<i class="fa fa-spinner fa-spin"></i>');

			jQuery.ajax({
				url: '{PATH_TO_ROOT}/kernel/framework/ajax/member_xmlhttprequest.php?token={TOKEN}&insert_member=1',
				type: "post",
				dataType: "html",
				data: {'login': login},
				success: function(returnData){
					jQuery('#xmlhttprequest-result-search').html(returnData);
					jQuery('#xmlhttprequest-result-search').fadeIn();
				},
				error: function(e){
					jQuery('#search_img').children("i").remove();
				}
			});
		}
		else
			alert("{L_REQUIRE_LOGIN}");
	}
</script>

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_GROUPS_MANAGEMENT}">
		<i class="fa fa-bars" aria-hidden="true"></i> {@user.groups.management}
	</a>
	<ul>
		<li>
			<a href="admin_groups.php" class="quick-link">{@user.groups.management}</a>
		</li>
		<li>
			<a href="admin_groups.php?add=1" class="quick-link">{@user.add.group}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# IF C_EDIT_GROUP #
		<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
			<p class="align-center small text-italic">{@form.required.fields}</p>
			<fieldset>
				<legend>{@user.edit.group}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="name">* {@form.name}</label>
						<div class="form-field">
							<input type="text" id="name" name="name" value="{NAME}">
						</div>
					</div>
					<div class="form-element inline-radio custom-radio">
						<label for="auth_flood">{@user.flood}</label>
						<div class="form-field">
							<div class="form-field-radio">
								<label class="radio" for="auth_flood">
									<input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" id="auth_flood" value="1">
									<span>{@common.yes}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="auth_flood_1">
									<input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" id="auth_flood_1" value="0">
									<span>{@common.no}</span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{@user.pm.limit} <span>{@user.pm.limit.clue}</span></label>
						<div class="form-field">
							<input type="text" name="pm_group_limit" id="pm_group_limit" value="{GROUP_PM_LIMIT}">
						</div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{@user.data.limit} <span class="field-description">{@user.data.limit.clue}</span></label>
						<div class="form-field">
							<input type="text" name="data_group_limit" id="data_group_limit" value="{GROUP_DATA_LIMIT}">
						</div>
					</div>
					<div class="form-element top-field">
						<label for="color_group">{@user.group.color}</label>
						<div class="form-field">
							<input type="color" name="color_group" id="color_group" value="{GROUP_COLOR}" pattern="#[A-Fa-f0-9]{6}">
						</div>
					</div>
					<div class="form-element custom-checkbox top-field">
						<label for="delete_group_color">{@user.delete.group.color}</label>
						<div class="form-field">
							<div class="form-field-checkbox">
								<label class="checkbox" for="delete_group_color">
									<input type="checkbox" name="delete_group_color" id="delete_group_color">
									<span>&nbsp;</span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-element top-field">
						<label for="img_group">{@user.group.thumbnail} <span class="field-description">{@user.group.thumbnail.clue}</span></label>
						<div class="form-field">
							<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
								{THUMBNAILS_LIST}
							</select>
						</div>
					</div>
					<div class="form-element">
						<label for="img_preview">{@form.preview}</label>
						<div class="form-field">
							<img src="{U_THUMBNAIL}" id="img_group_change" alt="{NAME}" # IF NOT C_HAS_THUMBNAIL #style="display: none;"# ENDIF # />
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<div class="fieldset-inset">
					<input type="hidden" name="id" value="{GROUP_ID}" class="update">
					<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
					<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</div>
			</fieldset>
		</form>

		# INCLUDE MESSAGE_HELPER #

		<form action="admin_groups.php?id={GROUP_ID}" method="post" onsubmit="return check_form_add_mbr();" class="fieldset-content">
			<fieldset>
				<legend>{@user.add.group.member}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="login">{@user.display.name}</label>
						<div class="form-field grouped-inputs">
							<input class="grouped-element" type="text" maxlength="25" id="login" value="{LOGIN}" name="login_mbr">
							<button class="grouped-element button submit" onclick="XMLHttpRequest_search();" type="button"><i class="fa fa-search"></i></button>
						</div>
						<div class="pinned" id="xmlhttprequest-result-search" style="display: none;" class="xmlhttprequest-result-search"></div>
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@common.add}</legend>
				<div class="fieldset-inset">
					<button type="submit" class="button submit" name="add_mbr" value="true">{@common.add}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</div>
			</fieldset>
		</form>

		<table class="table">
			<caption>
				{@user.group.members}
			</caption>
			<thead>
				<tr>
					<th>
						{@user.display.name}
					</th>
					<th>
						{@common.delete}
					</th>
				</tr>
			</thead>
			<tbody>
				# START member #
					<tr>
						<td>
							<a href="{member.U_PROFILE}" class="{member.LEVEL_CLASS}" # IF member.C_GROUP_COLOR # style="color:{member.GROUP_COLOR}" # ENDIF #>{member.LOGIN}</a>
						</td>
						<td>
							<a href="admin_groups.php?del_mbr=1&amp;id={GROUP_ID}&amp;user_id={member.USER_ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true" aria-label="{@common.delete}"></i></a>
						</td>
					</tr>
				# END member #
				# IF C_NO_MEMBERS #
					<tr>
						<td colspan="2">
							<div class="message-helper bgc notice">{@common.no.item.now}</div>
						</td>
					</tr>
				# ENDIF #
			</tbody>
		</table>

	# ENDIF #


	# IF C_ADD_GROUP #

		# INCLUDE MESSAGE_HELPER #

		<form action="admin_groups.php?add=1" method="post" enctype="multipart/form-data" class="fieldset-content">
			<fieldset>
				<legend>{@user.upload.thumbnail}</legend>
				<div class="dnd-area">
					<div class="dnd-dropzone">
						<label for="inputfiles" class="dnd-label">{@upload.drag.and.drop.files} <span class="d-block"></span></label>
						<input type="file" name="upload_groups[]" id="inputfiles" class="ufiles" />
					</div>
					<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
					<div class="ready-to-load">
						<button type="button" class="button clear-list">{@upload.clear.list}</button>
						<span class="fa-stack fa-lg">
							<i class="far fa-file fa-stack-2x "></i>
							<strong class="fa-stack-1x files-nbr"></strong>
						</span>
					</div>
					<div class="modal-container">
						<button class="button upload-help" data-modal data-target="upload-helper" aria-label="{@upload.upload.helper}"><i class="fa fa-question" aria-hidden="true"></i></button>
						<div id="upload-helper" class="modal modal-animation">
							<div class="close-modal" aria-label="{@common.close}"></div>
							<div class="content-panel">
								<div class="align-right"><a href="#" class="error big hide-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a></div>
								<h3>{@upload.upload.helper}</h3>
								<p><strong>{@upload.max.file.size} :</strong> {MAX_FILE_SIZE_TEXT}</p>
								<p><strong>{@upload.allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
							</div>
						</div>
					</div>
				</div>
				<ul class="ulist"></ul>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<div class="fieldset-inset">
				<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				</div>
			</fieldset>
		</form>

		<form action="admin_groups.php" method="post" onsubmit="return check_form();" class="fieldset-content">
			<p class="align-center small text-italic">{@form.required.fields}</p>
			<fieldset>
				<legend>{@user.add.group}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="name">* {@common.name}</label>
						<div class="form-field">
							<input type="text" maxlength="25" id="name" name="name" value="">
						</div>
					</div>
					<div class="form-element inline-radio custom-radio">
						<label for="auth_flood">{@user.flood}</label>
						<div class="form-field">
							<div class="form-field-radio">
								<label class="radio" for="auth_flood">
									<input type="radio" {AUTH_FLOOD_ENABLED} name="auth_flood" id="auth_flood" value="1">
									<span>{@common.yes}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="auth_flood_1">
									<input type="radio" {AUTH_FLOOD_DISABLED} name="auth_flood" id="auth_flood_1" value="0" checked>
									<span>{@common.no}</span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-element">
						<label for="pm_group_limit">{@user.pm.limit} <span class="field-description">{@user.pm.limit.clue}</span></label>
						<div class="form-field">
							<input type="text" name="pm_group_limit" id="pm_group_limit" value="75">
						</div>
					</div>
					<div class="form-element">
						<label for="data_group_limit">{@user.data.limit} <span class="field-description">{@user.data.limit.clue}</span></label>
						<div class="form-field">
							<input type="text" name="data_group_limit" id="data_group_limit" value="5">
						</div>
					</div>
					<div class="form-element top-field">
						<label for="color_group">{@user.group.color}</label>
						<div class="form-field">
							<input type="color" name="color_group" id="color_group" value="#366493" pattern="#[A-Fa-f0-9]{6}">
						</div>
					</div>
					<div class="form-element top-field">
						<label for="img_group">{@user.group.thumbnail} <span class="field-description">{@user.group.thumbnail.clue}</span></label>
						<div class="form-field">
							<select name="img" id="img_group" onchange="img_change(this.options[selectedIndex].value)">
								{THUMBNAILS_LIST}
							</select>
						</div>
					</div>
					<div class="form-element">
						<label for="img_preview">{@form.preview}</label>
						<div class="form-field">
							<img src="#" id="img_group_change" alt="{@common.image}" style="display: none;" />
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
				<div class="fieldset-inset">
					<input type="hidden" name="add" value="1">
					<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
					<input type="hidden" value="{TOKEN}" name="token">
				</div>
			</fieldset>
		</form>
	# ENDIF #
</div>
<script>
	jQuery('#inputfiles').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '-1',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(@H|upload.warning.disabled)},
		warningExtension: ${escapejs(@H|upload.warning.extension)},
		warningFileSize: ${escapejs(@H|upload.warning.file.size)},
		warningFilesNbr: ${escapejs(@H|upload.warning.files.number)},
	});
</script>
