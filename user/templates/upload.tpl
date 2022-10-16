<script>
	function insertAtCursor(myField, myValue)
	{
		// IE support
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
		}
		// MOZILLA/NETSCAPE support
		else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
		} else {
			myField.value += myValue;
		}
	}
	function insert_popup(code)
	{
		# IF C_TINYMCE_EDITOR #
			if (opener == null)
				var field = window.parent.document.getElementById("{FIELD}");
			else
				var field = opener.document.getElementById("{FIELD}");
		# ELSE #
			var field = opener.document.getElementById("{FIELD}");
		# ENDIF #

		var field_type = field.tagName.toLowerCase();

		if (field_type == 'input')
		{
			field.value = code;
		}
		else
		{
			# IF C_TINYMCE_EDITOR #
				window.parent.insertTinyMceContent(code); // insertion for tinymce.
			# ELSE #
				insertAtCursor(field, code);
				field.scrollTop(field.prop("selectionStart"));
			# ENDIF #
		}

		field.focus();
	}
	function close_popup()
	{
		opener=self;
		self.close();
	}
	function Confirm_member()
	{
		return confirm("{@warning.empty.folder.content}");
	}
	function popup_upload(path, width, height, scrollbars)
	{
		if( height == '0' )
			height = screen.height - 150;
		if( width == '0' )
			width = screen.width - 200;
		window.open(path, "", "width="+width+", height="+height+ ",location=no,status=no,toolbar=no,scrollbars=" + scrollbars + ",resizable=yes");
	}
	var hide_folder = false;
	var empty_folder = 0;
	function display_new_folder()
	{
		if( document.getElementById('empty-folder') )
				document.getElementById('empty-folder').style.display = 'none';

		if ( typeof this.divid == 'undefined' )
			this.divid = 0;
		else
			this.divid++;

		if( !hide_folder )
		{
			document.getElementById('new-folder').innerHTML += '<div id="new-folder' + divid + '"><div class="cell-header"><div class="cell-name ellipsis"><input type="text" name="folder_name" id="folder_name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');"></div><i class="fa fa-folder"></i></div></div>';
			document.getElementById('folder_name').focus();
		}
		else
		{
			document.getElementById('new-folder' + (divid - 1)).style.display = 'block';
			document.getElementById('new-folder' + (divid - 1)).innerHTML = '<div id="new-folder' + divid + '"><div class="cell-header"><div class="cell-name ellipsis"><input type="text" name="folder_name" id="folder_name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');"></div><i class="fa fa-folder"></i></div></div>';
			document.getElementById('folder_name').focus();
			this.divid--;
			hide_folder = false;
		}
	}
	function display_rename_folder(id, previous_name, previous_cut_name)
	{
		if( document.getElementById('f' + id) )
		{
			document.getElementById('f' + id).innerHTML = '<input type="text" name="finput' + id + '" id="finput' + id + '" value="' + previous_name + '" onblur="rename_folder(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');">';
			document.getElementById('finput' + id).focus();
		}
	}
	function rename_folder(id_folder, previous_name, previous_cut_name)
	{
		var name = document.getElementById('finput' + id_folder).value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;

		document.getElementById('img' + id_folder).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		if( name != '' && regex.test(name) ) // prohibition of special characters in the name.
		{
			alert("{@warning.folder.forbidden.chars}");
			document.getElementById('f' + id_folder).innerHTML = '<a href="upload.php?f=' + id_folder + '{POPUP}">' + previous_cut_name + '</a>';
			document.getElementById('img' + id_folder).innerHTML = '';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "id_folder=" + id_folder + "&name=" + name2 + "&previous_name=" + previous_name;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_folder=1');
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{
					if( xhr_object.responseText != '' )
					{
						document.getElementById('f' + id_folder).innerHTML = '<a href="upload.php?f=' + id_folder + '{POPUP}">' + name + '</a>';
						document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');" class="far fa-edit"></a>';
					}
					else
					{
						alert("{@warning.folder.already.exists}");
						document.getElementById('f' + id_folder).innerHTML = '<a href="upload.php?f=' + id_folder + '{POPUP}">' + previous_cut_name + '</a>';
					}
					document.getElementById('img' + id_folder).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('img' + id_folder).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}
	function add_folder(id_parent, user_id, divid)
	{
		var name = document.getElementById("folder_name").value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;

		if( name != '' && regex.test(name) ) // prohibition of special characters in the name.
		{
			alert("{@warning.folder.forbidden.chars}");
			document.getElementById('new-folder' + divid).innerHTML = '';
			document.getElementById('new-folder' + divid).style.display = 'none';
			hide_folder = true;
			if( document.getElementById('empty-folder') && empty_folder == 0 )
				document.getElementById('empty-folder').style.display = 'block';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "name=" + name2 + "&user_id=" + user_id + "&id_parent=" + id_parent;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&new_folder=1');
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 )
				{
					if( xhr_object.responseText > 0 )
					{
						var newFolder = '\
						<div class="cell-header">\
							<div id="f' + xhr_object.responseText + '" class="cell-name ellipsis">\
								<a href="upload.php?f=' + xhr_object.responseText + '{POPUP}">' + name + '</a>\
							</div>\
							<i class="fa fa-folder" aria-hidden="true"></i>\
						</div>\
						<div class="cell-list">\
							<ul>\
								<li class="li-stretch">\
									<span id="fhref' + xhr_object.responseText + '" aria-label="' + ${escapejs(@common.edit)} + '">\
										<a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');">\
											<i class="far fa-edit" aria-hidden="true"></i>\
										</a>\
									</span>\
									<a href="upload.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element" aria-label="' + ${escapejs(@common.delete)} + '">\
										<i class="far fa-trash-alt" aria-hidden="true"></i>\
									</a>\
									<a class="offload" href="upload.php?movefd=' + xhr_object.responseText + '&amp;f={FOLDER_ID}{POPUP}" aria-label="{@common.move.to}">\
										<i class="fa fa-share" aria-hidden="true"></i>\
									</a>\
								</li>\
								<span id="img' + xhr_object.responseText + '"></span>\
							</ul>\
						</div>';
						document.getElementById('new-folder' + divid).innerHTML = newFolder;
						var total_folder = document.getElementById('total-folder').innerHTML;
						total_folder++;
						document.getElementById('total-folder').innerHTML = total_folder;

						empty_folder++;
					}
					else
					{
						alert("{@warning.folder.already.exists}");
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
			if( document.getElementById('empty-folder') && empty_folder == 0 )
				document.getElementById('empty-folder').style.display = 'block';
			document.getElementById('new-folder' + divid).innerHTML = '';
			document.getElementById('new-folder' + divid).style.display = 'none';
			hide_folder = true;
		}
	}
	function display_rename_file(id, previous_name, previous_cut_name)
	{
		if( document.getElementById('fi' + id) )
		{
			document.getElementById('fifl' + id).style.display = 'none';
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
		if( name != '' && regex.test(name) ) // prohibition of special characters in the name.
		{
			alert("{@warning.folder.forbidden.chars}");
			document.getElementById('fifl' + id_file).style.display = 'inline';
			document.getElementById('fi' + id_file).style.display = 'none';
			document.getElementById('imgf' + id_file).innerHTML = '';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "id_file=" + id_file + "&name=" + name2 + "&previous_name=" + previous_cut_name;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_file=1');
			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					if( xhr_object.responseText == '/' )
					{
						alert("{@warning.folder.already.exists}");
						document.getElementById('fifl' + id_file).style.display = 'inline';
						document.getElementById('fi' + id_file).style.display = 'none';
					}
					else
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fifl' + id_file).style.display = 'inline';
						document.getElementById('fifl' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g,"\\\'") + '\',\'' + previous_name.replace(/\'/g,"\\\'") + '\',\'' + xhr_object.responseText.replace(/\'/g,"\\\'") + '\');" class="far fa-edit"></a>';
					}
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{
					document.getElementById('fi' + id_file).style.display = 'none';
					document.getElementById('fifl' + id_file).style.display = 'inline';
					document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\',\'' + previous_name.replace(/\'/g,"\\\'") + '\',\'' + previous_cut_name.replace(/\'/g,"\\\'") + '\');" class="far fa-edit"></a>';
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}
	var delay = 1000; // Delay after which the block is automatically hidden after the mouse has left.
	var timeout;
	var displayed = false;
	var previous_block;

	// Reveal the block.
	function upload_display_block(divID)
	{
		var i;

		if( timeout )
			clearTimeout(timeout);

		var block = document.getElementById('move' + divID);
		if( block.style.display == 'none' )
		{
			if( document.getElementById(previous_block) )
				document.getElementById(previous_block).style.display = 'none';
			displayed = true;
			block.style.display = 'block';
			previous_block = 'move' + divID;
		}
		else
		{
			block.style.display = 'none';
			displayed = false;
		}
	}
	// Hide the block.
	function upload_hide_block(idfield, stop)
	{
		if( stop && timeout )
		{
			clearTimeout(timeout);
		}
		else if( displayed )
		{
			clearTimeout(timeout);
			timeout = setTimeout('upload_display_block(\'' + idfield + '\')', delay);
		}
	}
	var selected = 0;
	function select_div(id)
	{
		if( document.getElementById(id) )
		{
			if( selected == 0 )
			{
				document.getElementById(id).select();
				selected = 1;
			}
			else
			{
				document.getElementById(id).blur();
				selected = 0;
			}
		}
	}

    function change_status(id, status)
    {
        jQuery.ajax({
            url: "{PATH_TO_ROOT}/user/upload.php",
            type: "post",
            data: {
                token: '{TOKEN}',
                item_id: id,
                status: status
            },
            success: function(returnData)
                {
                    if (status === 0) {
                        $('#status_' + id).removeClass('fas fa-user').addClass('fas fa-user-shield');
                        $('#status_function_' + id).attr("onclick", "change_status(" + id + ", 1)");
                    } else {
                        $('#status_' + id).removeClass('fas fa-user-shield').addClass('fas fa-users');
                        $('#status_function_' + id).attr("onclick", "change_status(" + id + ", 0)");
                    }
                    location.reload();
                }
        });
    }
</script>

<section id="module-user-upload" class="user-upload-files">
    <header class="section-header">
        <h1>{@upload.files.management}</h1>
    </header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<div class="tabs-container">
					<nav class="tabs-nav">
						<ul>
							<li><a href="#" data-tabs data-target="upload-personal">{@upload.personal.files}</a></li>
							<li><a href="#" data-tabs data-target="upload-shared">{@upload.shared.files}</a></li>
						</ul>
					</nav>
					<div id="upload-personal" class="tabs tabs-animation first-tab">
						<div class="content-panel">
							<div id="new-multiple-files">
								# INCLUDE MESSAGE_HELPER #
								<form action="upload.php?f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" enctype="multipart/form-data" method="post">
									<fieldset>
										<legend>{@upload.files.add}</legend>
										<div class="dnd-area">
											<div class="dnd-dropzone">
												<label for="inputfiles" class="dnd-label">{@upload.drag.and.drop.files} <span class="d-block"></span></label>
												<input type="file" name="upload_file[]" id="inputfiles" class="ufiles" />
											</div>
											<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
											<div class="ready-to-load">
												<button type="button" class="button clear-list">{@upload.clear.list}</button>
												<span class="fa-stack fa-lg">
													<i class="far fa-file fa-stack-2x"></i>
													<strong class="fa-stack-1x files-nbr"></strong>
												</span>
											</div>
											<div class="modal-container">
												<button class="button upload-help" data-modal data-target="upload-helper" aria-label="{@upload.upload.helper}"><i class="fa fa-question" aria-hidden="true"></i></button>
												<div id="upload-helper" class="modal modal-animation">
													<div class="close-modal" aria-label="{@form.close}"></div>
													<div class="content-panel">
														<h3>{@upload.upload.helper}</h3>
														# IF IS_ADMIN #
															<p><strong>{@upload.max.file.size} :</strong> {MAX_FILE_SIZE_TEXT}</p>
														# ELSE #
															<p><strong>{@upload.max.files.size} :</strong> {SIZE_LIMIT}</p>
														# ENDIF #
														<p><strong>{@upload.allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
													</div>
												</div>
											</div>
										</div>
										<ul class="ulist"></ul>
									</fieldset>
									<div class="form-element">
										<label for="is_shared_checkbox">{@upload.shared.checkbox}</label>
										<div class="form-field form-field-checkbox">
											<label class="checkbox" for="is_shared_checkbox">
												<input type="checkbox" id="is_shared_checkbox" name='is_shared_checkbox' />
												<span></span>
											</label>
										</div>
									</div>
									<fieldset class="fieldset-submit">
										<div class="fieldset-inset">
											<input type="hidden" name="token" value="{TOKEN}">
											<button type="submit" name="valid_up" value="true" class="button submit">{@form.upload}</button>
										</div>
									</fieldset>
								</form>
							</div>

							<div class="upload-address-bar">
								<a class="offload" href="upload.php?root=1{POPUP}"><i class="fa fa-home" aria-hidden="true"></i> {@common.root}</a>{URL}
							</div>

							<div class="upload-address-bar-links">
								<a class="offload" href="upload.php?fup={FOLDER_ID}{POPUP}">
									<i class="fa fa-level-up-alt" aria-hidden="true"></i> {@upload.folder.up}
								</a>
								<a href="javascript:display_new_folder();">
									<i class="fa fa-plus" aria-hidden="true"></i> {@upload.folder.new}
								</a>
								<a href="javascript:document.getElementById('inputfiles').click();">
									<i class="fa fa-save" aria-hidden="true"></i> {@upload.files.add}
								</a>
							</div>

							<h2>{@upload.folder.content}</h2>
							<div class="cell-flex cell-tile # IF C_POPUP #cell-inline# ELSE #cell-columns-4# ENDIF #">
								# START folder #
									<div class="cell">
										<span id="img{folder.ID}"></span>
										<div class="cell-header">
											<div class="cell-name ellipsis"><a class="offload" id="f{folder.ID}" href="upload.php?f={folder.ID}{POPUP}">{folder.NAME}</a></div>
											<a id="f{folder.ID}" href="upload.php?f={folder.ID}{POPUP}"><i class="fa fa-folder"></i></a>
										</div>
										<div class="cell-list">
											<ul>
												<li class="li-stretch">
													{folder.RENAME_FOLDER}
													<a href="upload.php?delf={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
													<a class="offload" href="upload{folder.U_MOVE}" aria-label="{@common.move.to}"><i class="fa fa-share" aria-hidden="true"></i></a>
												</li>
											</ul>
										</div>
									</div>
								# END folder #
								<div id="new-folder" class="cell"></div>
							</div>
							<div class="cell-flex cell-tile  # IF C_POPUP #cell-inline# ELSE #cell-columns-4# ENDIF #">
								# IF C_PERSONAL_FILES #
									# START personal_files #
										<div class="cell# IF personal_files.C_RECENT_FILE # new-content# ENDIF ## IF NOT personal_files.C_FILE_EXISTS # missing-file# ENDIF #">
											<span id="imgf{personal_files.ID}"></span>
											<div class="cell-header">
												<div id="fifl{personal_files.ID}" class="cell-name ellipsis">
													{personal_files.NAME}
												</div>
												<span class="change-name" id="fi{personal_files.ID}"></span>
												# IF NOT personal_files.C_ENABLED_THUMBNAILS #
													<a href="{personal_files.URL}" {personal_files.LIGHTBOX} aria-label="{@common.see.details}">
														<i class="far {personal_files.IMG}" aria-hidden="true"> </i>
													</a>
												# ENDIF #
											</div>
											# IF personal_files.C_ENABLED_THUMBNAILS #
												<div class="cell-body" aria-label="{@common.see.details}">
													<div class="cell-thumbnail cell-landscape cell-center">
														# IF personal_files.C_IMG #
															<img src="{personal_files.URL}" alt="{personal_files.NAME}">
															<a class="cell-thumbnail-caption" href="{personal_files.URL}" data-lightbox="formatter" data-rel="lightcase:collection">
																<i class="fa fa-eye" aria-hidden="true"></i>
															</a>
														# ELSE #
															<i class="far {personal_files.IMG} fa-4x"></i>
															<a class="cell-thumbnail-caption" href="{personal_files.URL}" {personal_files.LIGHTBOX}>
																<i class="far {personal_files.IMG}"> </i>
															</a>
														# ENDIF #
													</div>
												</div>
											# ENDIF #
											<div class="cell-form grouped-inputs">
												<input type="text" readonly="readonly" onclick="select_div(text_{personal_files.ID});" id="text_{personal_files.ID}" class="grouped-element" value="{personal_files.DISPLAYED_CODE}">
												# IF C_POPUP #
													<a class="grouped-element submit" href="javascript:insert_popup('{personal_files.INSERTED_CODE}')" aria-label="{@upload.popup.insert}"><i class="fa fa-clipboard" aria-hidden="true"></i></a>
												# ELSE #
													<a class="grouped-element submit" href="#" onclick="copy_to_clipboard('{personal_files.DISPLAYED_CODE}'); return false;" aria-label="{@common.copy.to.clipboard}"><i class="fa fa-copy" aria-hidden="true"></i></a>
												# ENDIF #
											</div>
											<div class="cell-list">
												<ul>
													<li class="li-stretch">
														{personal_files.RENAME_FILE}
														<a href="upload.php?del={personal_files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
														<a class="offload" href="upload{personal_files.U_MOVE}" aria-label="{@common.move.to}"><i class="fa fa-share" aria-hidden="true"></i></a>
														# IF personal_files.C_IS_SHARED_FILE #
															<a class="offload" href="#" id="status_function_{personal_files.ID}" onclick="change_status({personal_files.ID}, 0);return false;" aria-label="{@upload.change.to.personal}">
																<i id="status_{personal_files.ID}" class="fas fa-users"></i>
															</a>
														# ELSE #
															<a class="offload" href="#" id="status_function_{personal_files.ID}" onclick="change_status({personal_files.ID}, 1);return false;" aria-label="{@upload.change.to.shared}">
																<i id="status_{personal_files.ID}" class="fas fa-user-shield"></i>
															</a>
														# ENDIF #
													</li>
												</ul>
											</div>
											<div class="cell-list">
												<ul class="small">
													<li class="li-stretch"><span>{personal_files.FILETYPE}</span> <span>{personal_files.SIZE}</span></li>
													<li class="li-stretch" aria-label="{@upload.file.date}"><span><i class="far fa-clock" aria-hidden></i></span> <span>{personal_files.DATE_FULL}</span></li>
												</ul>
											</div>
										</div>
									# END personal_files #
								# ELSE #
									<span class="message-helper bgc notice">{@common.no.item.now}</span>
								# ENDIF #
							</div>
							# IF C_PERSONAL_SUMMARY #
								<div class="cell-flex cell-tile cell-options">
									<div class="cell">
										<div class="cell-list">
											<ul class="small">
												<li class="li-stretch"><span id="total-folder">{@upload.folders} :</span> <strong>{TOTAL_FOLDERS}</strong></li>
												<li class="li-stretch"><span>{@upload.files} :</span> <strong>{TOTAL_PERSONAL_FILES}</strong></li>
												<li class="li-stretch"><span>{@upload.folder.size} :</span> <strong>{TOTAL_FOLDER_SIZE}</strong></li>
												<li class="li-stretch"><span>{@upload.total.datas} :</span> <strong>{TOTAL_SIZE}</strong></li>
											</ul>
										</div>
									</div>
								</div>
							# ENDIF #
						</div>
					</div>
					<div id="upload-shared" class="tabs tabs-animation">
						<div class="content-panel">
							# IF C_SHARED_FILES #
								<div class="cell-flex cell-tile # IF C_POPUP #cell-inline# ELSE #cell-columns-4# ENDIF #">
									# START shared_files #
										<div class="cell# IF shared_files.C_RECENT_FILE # new-content# ENDIF ## IF NOT shared_files.C_FILE_EXISTS # missing-file# ENDIF #">
											<span id="imgf{shared_files.ID}"></span>
											<div class="cell-header">
												<div id="fifl{shared_files.ID}" class="cell-name ellipsis">{shared_files.NAME}</div>
												# IF NOT shared_files.C_ENABLED_THUMBNAILS #
													<a href="{shared_files.URL}" {shared_files.LIGHTBOX} aria-label="{@common.see.details}">
														<i class="far {shared_files.IMG}"> </i>
													</a>
												# ENDIF #
												<span id="fi{shared_files.ID}"></span>
											</div>
											# IF shared_files.C_ENABLED_THUMBNAILS #
												<div class="cell-body" aria-label="{@common.see.details}">
													<div class="cell-thumbnail cell-landscape cell-center">
														# IF shared_files.C_IMG #
															<img src="{shared_files.URL}" alt="{shared_files.NAME}">
															<a class="cell-thumbnail-caption" href="{shared_files.URL}" data-lightbox="formatter" data-rel="lightcase:collection">
																<i class="fa fa-eye"></i>
															</a>
														# ELSE #
															<i class="far {shared_files.IMG} fa-4x"></i>
															<a class="cell-thumbnail-caption" href="{shared_files.URL}" {shared_files.LIGHTBOX}>
																<i class="far {shared_files.IMG}"></i>
															</a>
														# ENDIF #
													</div>
												</div>
											# ENDIF #
											<div class="cell-form grouped-inputs">
												<input type="text" readonly="readonly" onclick="select_div(text_{shared_files.ID});" id="text_{shared_files.ID}" class="grouped-element" value="{shared_files.DISPLAYED_CODE}">
												# IF C_POPUP #
													<a class="grouped-element" href="javascript:insert_popup('{shared_files.INSERTED_CODE}')" aria-label="{@upload.popup.insert}"><i class="fa fa-file-export" aria-hidden="true"></i></a>
												# ELSE #
													<a class="grouped-element" href="#" onclick="copy_to_clipboard('{shared_files.DISPLAYED_CODE}'); return false;" aria-label="{@common.copy.to.clipboard}"><i class="fa fa-copy" aria-hidden="true"></i></a>
												# ENDIF #
											</div>
											<div class="cell-list">
												<ul class="small">
													<li class="li-stretch"><span>{shared_files.FILETYPE}</span><span>{shared_files.SIZE}</span></li>
													<li class="li-stretch" aria-label="{@upload.file.date}"><span><i class="far fa-clock" aria-hidden></i></span><span>{shared_files.DATE_FULL}</span></li>
												</ul>
											</div>
										</div>
									# END shared_files #
								</div>
								<div class="cell-flex cell-options cell-tile">
									<div class="cell">
										<div class="cell-list">
											<ul class="small">
												<li class="li-stretch">
													<span>{@upload.files} :</span> <strong>{TOTAL_SHARED_FILES}</strong>
												</li>
												<li class="li-stretch">
													<span>{@upload.total.datas} :</span> <strong>{TOTAL_SIZE}</strong>
												</li>
											</ul>
										</div>
									</div>
								</div>
							# ELSE #
								<span class="message-helper bgc notice">{@common.no.item.now}</span>
							# ENDIF #
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer>
		# IF C_DISPLAY_CLOSE_BUTTON #
			<div class="sub-section">
				<div class="content-container">
					<fieldset class="fieldset-submit">
						<legend>{@form.close}</legend>
						<button type="reset" class="button reset-button" onclick="javascript:close_popup()" value="true">{@form.close}</button>
					</fieldset>
				</div>
			</div>
		# ENDIF #
	</footer>

</section>
<script>
	jQuery('#inputfiles').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '{MAX_FILES_SIZE}',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(@H|upload.warning.disabled)},
		warningExtension: ${escapejs(@H|upload.warning.extension)},
		warningFileSize: ${escapejs(@H|upload.warning.file.size)},
		warningFilesNbr: ${escapejs(@H|upload.warning.files.number)},
	});
</script>
