<script>
	function popup_upload(path, width, height, scrollbars)
	{
		if (height == '0')
			height = screen.height - 150;
		if (width == '0')
			width = screen.width - 200;
		window.open(path, "", "width=" + width + ", height=" + height + ",location=no,status=no,toolbar=no,scrollbars=" + scrollbars + ",resizable=yes");
	}

	var hide_folder = false;
	var empty_folder = 0;
	function display_new_folder()
	{
		if (document.getElementById('empty-folder'))
			document.getElementById('empty-folder').style.display = 'none';

		if (typeof this.divid == 'undefined')
			this.divid = 0;
		else
			this.divid++;

		if (!hide_folder)
		{
			document.getElementById('new-folder').innerHTML += '<div id="new-folder' + divid + '"><div class="cell-header"><div class="cell-name ellipsis"><input type="text" name="folder-name" id="folder-name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');"></div><i class="fa fa-folder" aria-hidden="true"></i></div></div>';
			document.getElementById('folder-name').focus();
		}
		else
		{
			document.getElementById('new-folder' + (divid - 1)).style.display = 'block';
			document.getElementById('new-folder' + (divid - 1)).innerHTML = '<div id="new-folder' + divid + '"><div class="cell-header"><div class="cell-name ellipsis"> <input type="text" name="folder-name" id="folder-name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');"></div><i class="fa fa-folder" aria-hidden="true"></i></div></div>';
			document.getElementById('folder-name').focus();
			this.divid--;
			hide_folder = false;
		}
	}

	function display_rename_folder(id, previous_name, previous_cut_name)
	{
		if (document.getElementById('f' + id))
		{
			document.getElementById('f' + id).innerHTML = '<input type="text" name="finput' + id + '" id="finput' + id + '" value="' + previous_name + '" onblur="rename_folder(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');">';
			document.getElementById('finput' + id).focus();
		}
	}

	function rename_folder(id_folder, previous_name, previous_cut_name)
	{
		var name = document.getElementById("finput" + id_folder).value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;

		document.getElementById('img' + id_folder).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		if (name != '' && regex.test(name)) // Prohibition of special characters in the name.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
			document.getElementById('img' + id_folder).innerHTML = '';
		}
		else if (name != '')
		{
			data = "id_folder=" + id_folder + "&name=" + name + "&previous_name=" + previous_name + "&user_id=" + {USER_ID};
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_folder=1');
			xhr_object.onreadystatechange = function()
			{
				if (xhr_object.readyState == 4 && xhr_object.status == 200)
				{
					if (xhr_object.responseText != "")
					{
						document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + name + '</a>';
						document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\',\'' + xhr_object.responseText.replace(/\'/g,"\\\'") + '\', \'' + name.replace(/\'/g,"\\\'") + '\');" class="fa fa-edit"></a>';
					}
					else
					{
						alert("{L_FOLDER_ALREADY_EXIST}");
						document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
					}
					document.getElementById('img' + id_folder).innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}

	function add_folder(id_parent, user_id, divid)
	{
		var name = document.getElementById("folder-name").value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;

		if (name != '' && regex.test(name)) // Prohibition of special characters in the name.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('new-folder' + divid).innerHTML = '';
			document.getElementById('new-folder' + divid).style.display = 'none';
			hide_folder = true;
			if (document.getElementById('empty-folder') && empty_folder == 0)
				document.getElementById('empty-folder').style.display = 'block';
		}
		else if (name != '')
		{
			data = "name=" + name + "&user_id=" + user_id + "&id_parent=" + id_parent;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&new_folder=1');
			xhr_object.onreadystatechange = function()
				{
					if (xhr_object.readyState == 4 && xhr_object.status == 200)
					{
						if (xhr_object.responseText > 0)
						{
							var newFolder = '\
							<div class="cell-header">\
								<div id="f' + xhr_object.responseText + '" class="cell-name ellipsis">\
									<a href="admin_files.php?f=' + xhr_object.responseText + '">' + name + '</a>\
								</div>\
								<i class="far fa-folder" aria-hidden="true"></i>\
							</div>\
							<div class="cell-list">\
								<ul>\
									<li class="li-stretch">\
										<span id="fhref' + xhr_object.responseText + '" aria-label="' + ${escapejs(LangLoader::get_message('edit', 'common'))} + '">\
											<a id="fihref' + xhr_object.responseText + '" href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\',\'' + name.replace(/\'/g,"\\\'") + '\',\'' + name.replace(/\'/g,"\\\'") + '\');">\
												<i class="far fa-edit" aria-hidden="true"></i>\
											</a>\
										</span>\
										<a href="admin_files.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="' + ${escapejs(LangLoader::get_message('delete', 'common'))} + '">\
											<i class="far fa-trash-alt" aria-hidden="true"></i>\
										</a>\
										<a href="admin_files.php?movefd=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;fm=' + user_id + '" aria-label="{L_MOVETO}">\
											<i class="fa fa-share" aria-hidden="true"></i>\
										</a>\
									</li>\
									<span id="img' + xhr_object.responseText + '"></span>\
								</ul>\
							</div>';
							document.getElementById('new-folder' + divid).innerHTML =  newFolder;
							var total_folder = document.getElementById('total-folder').innerHTML;
							total_folder++;
							document.getElementById('total-folder').innerHTML = total_folder;

							empty_folder++;
						}
						else
						{
							alert("{L_FOLDER_ALREADY_EXIST}");
							document.getElementById('new-folder' + divid).innerHTML = '';
							document.getElementById('new-folder' + divid).style.display = 'none';
							hide_folder = true;
						}
					}
				}
			xmlhttprequest_sender(xhr_object, data);
		}
		else
		{
			if (document.getElementById('empty-folder') && empty_folder == 0)
				document.getElementById('empty-folder').style.display = 'block';
			document.getElementById('new-folder' + divid).innerHTML = '';
			document.getElementById('new-folder' + divid).style.display = 'none';
			hide_folder = true;
		}
	}

	function display_rename_file(id, previous_name, previous_cut_name)
	{
		if (document.getElementById('fi' + id))
		{
			document.getElementById('fi1' + id).style.display = 'none';
			document.getElementById('fi' + id).style.display = 'inline';
			document.getElementById('fi' + id).innerHTML = '<input type="text" name="fiinput' + id + '" id="fiinput' + id + '" value="' + previous_name + '" onblur="rename_file(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');">';
			document.getElementById('fiinput' + id).focus();
		}
	}

	function rename_file(id_file, previous_name, previous_cut_name)
	{
		var name = document.getElementById("fiinput" + id_file).value;
		var regex = /\/|\\|\||\?|<|>|\"/;

		document.getElementById('imgf' + id_file).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		if (name != '' && regex.test(name)) // Prohibition of special characters in the name.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('fi1' + id_file).style.display = 'inline';
			document.getElementById('fi' + id_file).style.display = 'none';
			document.getElementById('imgf' + id_file).innerHTML = '';
		}
		else if (name != '')
		{
			data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name + "&user_id=" + {USER_ID};
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_file=1');
			xhr_object.onreadystatechange = function()
			{
				if (xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '')
				{
					if (xhr_object.responseText == '/')
					{
						alert("{L_FOLDER_ALREADY_EXIST}");
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi' + id_file).style.display = 'none';
					}
					else
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi1' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a aria-label="' + ${escapejs(LangLoader::get_message('edit', 'common'))} + '" href="javascript:display_rename_file(\'' + id_file + '\',\'' + name.replace(/\'/g,"\\\'") + '\',\'' + previous_name.replace(/\'/g,"\\\'") + '\',\'' + xhr_object.responseText.replace(/\'/g,"\\\'") + '\');"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					}
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
				else if (xhr_object.readyState == 4 && xhr_object.responseText == '')
				{
					document.getElementById('fi' + id_file).style.display = 'none';
					document.getElementById('fi1' + id_file).style.display = 'inline';
					document.getElementById('fihref' + id_file).innerHTML = '<a aria-label="' + ${escapejs(LangLoader::get_message('edit', 'common'))} + '" href="javascript:display_rename_file(\'' + id_file + '\',\'' + previous_name.replace(/\'/g,"\\\'") + '\',\'' + previous_cut_name.replace(/\'/g,"\\\'") + '\');"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}

	function change_status(id, status)
	{
		jQuery.ajax({
			url: "{PATH_TO_ROOT}/admin/admin_files.php",
			type: "post",
			data: {
				token: '{TOKEN}',
				item_id: id,
				status: status
			},
			success: function(returnData)
			{
				if (status === 0)
				{
					$('#status_' + id).removeClass('fas fa-user').addClass('fas fa-user-shield');
					$('#status_function_' + id).attr("onclick", "change_status(" + id + ", 1)");
				}
				else
				{
					$('#status_' + id).removeClass('fas fa-user-shield').addClass('fas fa-users');
					$('#status_function_' + id).attr("onclick", "change_status(" + id + ", 0)");
				}
				location.reload();
			}
		});
	}
</script>

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {L_FILES_MANAGEMENT}
	</a>
	<ul>
		<li>
			<a href="admin_files.php" class="quick-link">{L_FILES_MANAGEMENT}</a>
		</li>
		<li>
			<a href="${relative_url(AdminFilesUrlBuilder::configuration())}" class="quick-link">{L_CONFIG_FILES}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<div id="new-multiple-files">
		# INCLUDE message_helper #
		<form action="admin_files.php?f={FOLDER_ID}&amp;fm={USER_ID}&amp;token={TOKEN}" enctype="multipart/form-data" method="post">
			<fieldset>
				<legend>{L_ADD_FILES}</legend>
				<div class="dnd-area">
					<div class="dnd-dropzone">
						<label for="inputfiles" class="dnd-label">${LangLoader::get_message('drag.and.drop.files', 'upload-common')} <span class="d-block"></span></label>
						<input type="file" name="upload_file[]" id="inputfiles" class="ufiles" />
					</div>
					<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
					<div class="ready-to-load">
						<button type="button" class="button clear-list">${LangLoader::get_message('clear.list', 'upload-common')}</button>
						<span class="fa-stack fa-lg">
							<i class="far fa-file fa-stack-2x "></i>
							<strong class="fa-stack-1x files-nbr"></strong>
						</span>
					</div>
					<div class="modal-container">
						<button class="button upload-help" data-modal data-target="upload-helper" aria-label="${LangLoader::get_message('upload.helper', 'upload-common')}"><i class="fa fa-question" aria-hidden="true"></i></button>
						<div id="upload-helper" class="modal modal-animation">
							<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
							<div class="content-panel">
								<h3>${LangLoader::get_message('upload.helper', 'upload-common')}</h3>
								<p><strong>${LangLoader::get_message('max.file.size', 'upload-common')} :</strong> {MAX_FILE_SIZE_TEXT}</p>
								<p><strong>${LangLoader::get_message('allowed.extensions', 'upload-common')} :</strong> "{ALLOWED_EXTENSIONS}"</p>
							</div>
						</div>
					</div>
				</div>
				<ul class="ulist"></ul>
			</fieldset>
			<div class="form-element custom-checkbox full-field">
				<label for="is_public_checkbox">{L_PUBLIC_CHECKBOX}</label>
				<div class="form-field form-field-checkbox">
					<label class="checkbox" for="is_public_checkbox">
						<input type="checkbox" id="is_public_checkbox" name='is_public_checkbox' />
						<span></span>
					</label>
				</div>
			</div>
			<fieldset class="fieldset-submit">
				<div class="fieldset-inset">
					<button type="submit" class="button submit" name="valid_up" value="true">{L_UPLOAD}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</div>
			</fieldset>
		</form>
	</div>

	<fieldset>
		<legend>{L_FILES_ACTION}</legend>
		<div class="fieldset-inset">
			<div class="upload-address-bar">
				<a href="admin_files.php"><i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}</a>{URL}# IF C_SHOW_PUBLIC_FILES # | {L_PUBLIC_TITLE}# ENDIF #
			</div>

			<div class="upload-address-bar-links">
				<a class="button" href="admin_files.php?root=1">
					<i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}
				</a>
				<a class="button" href="admin_files.php?# IF C_MEMBER_ROOT_FOLDER #showm=1# ELSE #fup={FOLDER_ID}{FOLDERM_ID}# ENDIF #">
					<i class="fa fa-level-up-alt" aria-hidden="true"></i> {L_FOLDER_UP}
				</a>
				<a class="button success" href="javascript:display_new_folder();">
					<i class="fa fa-plus" aria-hidden="true"></i> {L_FOLDER_NEW}
				</a>
				<a class="button success" href="javascript:document.getElementById('inputfiles').click();">
					<i class="fa fa-save" aria-hidden="true"></i> {L_ADD_FILES}
				</a>
			</div>
		</div>
	</fieldset>

	<fieldset class="upload-elements-container">
		<legend>{L_FOLDER_CONTENT} : {URL}# IF C_SHOW_PUBLIC_FILES # {L_PUBLIC_TITLE}# ENDIF #</legend>
		# IF C_PERSONAL_SUMMARY #
			<div class="cell align-right">
				<span class="pinned question"><span id="total-folder">{L_FOLDERS} :</span> <strong>{TOTAL_FOLDERS}</strong></span>
				<span class="pinned question"><span>{L_FILES} :</span> <strong>{TOTAL_PERSONAL_FILES}</strong></span>
				<span class="pinned question"><span>{L_FOLDER_SIZE} :</span> <strong>{TOTAL_FOLDER_SIZE}</strong></span>
				<span class="pinned moderator"><span>{L_DATA} :</span> <strong>{TOTAL_SIZE}</strong></span>
			</div>
		# ENDIF #
		# IF C_SHOW_PUBLIC_FILES #
			<div class="cell align-right">
				<span class="pinned question"><span>{L_FILES} :</span> <strong>{TOTAL_PUBLIC_FILES}</strong></span>
				<span class="pinned question"><span>{L_FOLDER_SIZE} :</span> <strong>{TOTAL_PUBLIC_SIZE}</strong></span>
				<span class="pinned moderator"><span>{L_DATA} :</span> <strong>{TOTAL_SIZE}</strong></span>
			</div>
		# ENDIF #

		<div class="fieldset-inset">
			# IF C_EMPTY_FOLDER #
				<div id="empty-folder" class="message-helper bgc notice">{L_EMPTY_FOLDER}</div>
				<span id="new-folder"></span>
			# ELSE #
				<div class="cell-flex cell-tile cell-inline">
					<div id="public-folder" class="cell">
						<div class="cell-header">
							<div class="cell-name"><a href="admin_files.php?showp=1">{L_PUBLIC_TITLE}</a></div>
							<i class="far fa-folder" aria-hidden="true"></i>
						</div>
					</div>

					# START folder #
						<div id="members-folder-{folder.ID}" class="cell">
							<div class="cell-header">
								<div id="f{folder.ID}" class="cell-name ellipsis"><a href="admin_files.php{folder.U_FOLDER}" class="com">{folder.NAME}</a></div>
								<i class="# IF folder.C_MEMBER_FOLDER #fa fa-users# ELSE #far fa-folder# ENDIF #" aria-hidden="true"></i>
							</div>
							# IF NOT folder.C_MEMBERS_FOLDER #
								<div class="cell-list">
									<ul>
										<li class="li-stretch">
											# IF folder.C_TYPEFOLDER #
												<a id="fhref{folder.ID}" href="javascript:display_rename_folder('{folder.ID}','{folder.NAME_WITH_SLASHES}','{folder.NAME_CUT_WITH_SLASHES}');" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF NOT folder.C_MEMBERS_FOLDER #
												<a href="admin_files.php?{folder.DEL_TYPE}={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{FOLDERM_ID}" data-confirmation="# IF folder.C_MEMBER_FOLDER #{L_CONFIRM_EMPTY_FOLDER}# ELSE #delete-element# ENDIF #" aria-label="{folder.L_TYPE_DEL_FOLDER}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
											# IF folder.C_TYPEFOLDER #
												<a href="admin_files{folder.U_MOVE}" aria-label="{L_MOVETO}"><i class="fa fa-share" aria-hidden="true"></i></a>
											# ENDIF #
										</li>
										<span id="img{folder.ID}"></span>
									</ul>
								</div>
							# ENDIF #
						</div>
					# END folder #
					<div id="new-folder" class="cell"></div>
				</div>
				<div class="cell-flex cell-tile cell-inline">
					# START personal_files #
						<div class="cell# IF personal_files.C_RECENT_FILE # new-content# ENDIF #">
							<span id="imgf{personal_files.ID}"></span>
							<div class="cell-header">
								<div id="fi1{personal_files.ID}" class="cell-name ellipsis">{personal_files.NAME}</div>
								<span id="fi{personal_files.ID}"></span>
								# IF NOT personal_files.C_ENABLED_THUMBNAILS #
									<a href="{personal_files.URL}" {personal_files.LIGHTBOX} aria-label="${Langloader::get_message('see.details', 'common')}">
										<i class="{personal_files.IMG} fa-lg" aria-hidden="true"></i>
									</a>
								# ENDIF #
							</div>
							# IF personal_files.C_ENABLED_THUMBNAILS #
								<div class="cell-body">
									<div class="cell-thumbnail cell-landscape cell-center" aria-label="${Langloader::get_message('see.details', 'common')}">
										# IF personal_files.C_IMG #
											<img src="{personal_files.URL}" alt="{personal_files.NAME}">
											<a class="cell-thumbnail-caption" href="{personal_files.URL}" data-lightbox="formatter" data-rel="lightcase:collection">
												<i class="fa fa-eye" aria-hidden="true"></i>
											</a>
										# ELSE #
											<i class="{personal_files.IMG} fa-fw fa-4x"></i>
											<a class="cell-thumbnail-caption" href="{personal_files.URL}" {personal_files.LIGHTBOX}>
												<i class="{personal_files.IMG}"> </i>
											</a>
										# ENDIF #
									</div>
								</div>
							# ENDIF #
							<div class="cell-form grouped-inputs">
								{personal_files.BBCODE}
								<a class="grouped-element submit" href="#" onclick="copy_to_clipboard('{personal_files.DISPLAYED_CODE}'); return false;" aria-label="${LangLoader::get_message('tag_copytoclipboard', 'editor-common')}">
									<i class="fa fa-copy" aria-hidden="true"></i>
								</a>
							</div>

							<div class="cell-list">
								<ul>
									<li class="li-stretch">
										<a id="fihref{personal_files.ID}" href="javascript:display_rename_file('{personal_files.ID}','{personal_files.NAME_WITH_SLASHES}','{personal_files.NAME_CUT_WITH_SLASHES}');" aria-label="${LangLoader::get_message('edit', 'common')}">
											<i class="far fa-edit" aria-hidden="true"></i>
										</a>
										<a href="admin_files.php?del={personal_files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" aria-label="{L_DELETE}" data-confirmation="delete-element">
											<i class="far fa-trash-alt" aria-hidden="true"></i>
										</a>
										<a href="admin_files{personal_files.U_MOVE}" aria-label="{L_MOVETO}">
											<i class="fa fa-share" aria-hidden="true"></i>
										</a>
										# IF personal_files.C_IS_PUBLIC_FILE #
											<a href="#" id="status_function_{personal_files.ID}" onclick="change_status({personal_files.ID}, 0);return false;" aria-label="{L_CHANGE_PERSONAL}">
												<i id="status_{personal_files.ID}" class="fas fa-users" aria-hidden="true"></i>
											</a>
										# ELSE #
											<a href="#" id="status_function_{personal_files.ID}" onclick="change_status({personal_files.ID}, 1);return false;" aria-label="{L_CHANGE_PUBLIC}">
												<i id="status_{personal_files.ID}" class="fas fa-user-shield" aria-hidden="true"></i>
											</a>
										# ENDIF #
									</li>
								</ul>
							</div>
							<div class="cell-list">
								<ul class="small">
									<li class="li-stretch">
										<span>{personal_files.FILETYPE}</span>
										<span>{personal_files.SIZE}</span>
									</li>
								</ul>
							</div>

						</div>
					# END personal_files #
				</div>


			# ENDIF #
		</div>
		# IF C_SHOW_PUBLIC_FILES #
			# IF C_PUBLIC_FILES_EXIST #
				<div class="cell-flex cell-tile cell-inline">
					# START public_files #
						<div class="cell# IF public_files.C_RECENT_FILE # new-content# ENDIF #">
							<span><span>{L_POSTOR}</span># IF public_files.C_POSTOR_EXIST #<a class="{public_files.POSTOR_LEVEL_CLASS}"# IF public_files.C_POSTOR_GROUP_COLOR # style="color:{public_files.POSTOR_GROUP_COLOR}"# ENDIF # href="{public_files.U_POSTOR_PROFILE}">{public_files.POSTOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #</span>
							<span id="imgf{public_files.ID}"></span>
							<div class="cell-header">
								<div id="fifl{public_files.ID}" class="cell-name ellipsis">{public_files.NAME}</div>
								# IF NOT public_files.C_ENABLED_THUMBNAILS #
									<a href="{public_files.URL}" aria-label="${Langloader::get_message('see.details', 'common')}">
										<i class="far {public_files.IMG}"></i>
									</a>
								# ENDIF #
								<span id="fi{public_files.ID}"></span>
							</div>
							# IF public_files.C_ENABLED_THUMBNAILS #
								<div class="cell-body" aria-label="${Langloader::get_message('see.details', 'common')}">
									<div class="cell-thumbnail cell-landscape cell-center">
										# IF public_files.C_IMG #
											<img src="{public_files.URL}" alt="{public_files.NAME}">
											<a class="cell-thumbnail-caption" href="{public_files.URL}">
												<i class="fa fa-eye"></i>
											</a>
										# ELSE #
											<i class="far {public_files.IMG} fa-4x"></i>
											<a class="cell-thumbnail-caption" href="{public_files.URL}">
												<i class="far {public_files.IMG}"></i>
											</a>
										# ENDIF #
									</div>
								</div>
							# ENDIF #
							<div class="cell-form grouped-inputs">
								{public_files.BBCODE}
								<a class="grouped-element submit" href="#" onclick="copy_to_clipboard('{public_files.DISPLAYED_CODE}'); return false;" aria-label="${LangLoader::get_message('tag_copytoclipboard', 'editor-common')}"><i class="fa fa-copy" aria-hidden="true"></i></a>
							</div>
							<div class="cell-list">
								<ul>
									<li class="li-stretch">
										{public_files.RENAME_FILE}
										<a href="admin_files.php?del={public_files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="{L_DELETE}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
										# IF public_files.C_IS_PUBLIC_FILE #
											<a href="#" id="status_function_{public_files.ID}" onclick="change_status({public_files.ID}, 0);return false;" aria-label="{L_CHANGE_PERSONAL}">
												<i id="status_{public_files.ID}" class="fas fa-users"></i>
											</a>
										# ELSE #
											<a href="#" id="status_function_{public_files.ID}" onclick="change_status({public_files.ID}, 1);return false;" aria-label="{L_CHANGE_PUBLIC}">
												<i id="status_{public_files.ID}" class="fas fa-user-shield"></i>
											</a>
										# ENDIF #
									</li>
								</ul>
							</div>
							<div class="cell-list">
								<ul class="small">
									<li class="li-stretch">
										<span>{public_files.FILETYPE}</span>
										<span>{public_files.SIZE}</span>
									</li>
								</ul>
							</div>
						</div>
					# END public_files #
				</div>
			# ELSE #
				<span class="message-helper bgc notice">{L_NO_ITEM}</span>
			# ENDIF #
		# ENDIF #
	</fieldset>
</div>
<script>
	jQuery('#inputfiles').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '-1',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'upload-common'))},
		warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'upload-common'))},
		warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'upload-common'))},
		warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.number', 'upload-common'))}
	});

	var urlPath = $(location).attr("href").split('/'),
		publicPath = 'admin_files.php?showp=1',
		urlEnd = urlPath[urlPath.length - 1];
	if (urlEnd === publicPath)
		jQuery('#members-folder, #public-folder').hide();
</script>
