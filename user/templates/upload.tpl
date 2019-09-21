	<script src="{PATH_TO_ROOT}/templates/default/plugins/dnduploader.js"></script>
	<script>
	<!--
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
		return confirm("{L_CONFIRM_EMPTY_FOLDER}");
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
			document.getElementById('new-folder').innerHTML += '<div class="upload-elements-repertory" id="new-folder' + divid + '"><i class="fa fa-folder fa-2x"></i> <input type="text" name="folder_name" id="folder_name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');"></div>';
			document.getElementById('folder_name').focus();
		}
		else
		{
			document.getElementById('new-folder' + (divid - 1)).style.display = 'block';
			document.getElementById('new-folder' + (divid - 1)).innerHTML = '<div class="upload-elements-repertory" id="new-folder' + divid + '"><i class="fa fa-folder fa-2x"></i> <input type="text" name="folder_name" id="folder_name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');"></div>';
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
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
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
						document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a>';
					}
					else
					{
						alert("{L_FOLDER_ALREADY_EXIST}");
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
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
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
						document.getElementById('new-folder' + divid).innerHTML = '<a class="infos-options" href="upload.php?f=' + xhr_object.responseText + '{POPUP}"><i class="fa fa-folder fa-2x"></i></a> &nbsp;<span class="infos-options" id="f' + xhr_object.responseText + '"><a href="upload.php?f=' + xhr_object.responseText + '{POPUP}">' + name + '</a></span> <div class="upload-repertory-controls"><span id="fhref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a></span>&nbsp;<span><a href="upload.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a></span>&nbsp;<span><a href="upload.php?movefd=' + xhr_object.responseText + '&amp;f={FOLDER_ID}{POPUP}" aria-label="{L_MOVETO}" class="fa fa-move"></a></span><span id="img' + xhr_object.responseText + '"></span></div>';
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
		if( name != '' && regex.test(name) ) // prohibition of special characters in the name.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('fi1' + id_file).style.display = 'inline';
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
						alert("{L_FOLDER_ALREADY_EXIST}");
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi' + id_file).style.display = 'none';
					}
					else
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi1' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a>';
					}
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{
					document.getElementById('fi' + id_file).style.display = 'none';
					document.getElementById('fi1' + id_file).style.display = 'inline';
					document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a>';
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
	-->
	</script>

	<script>
		// functions for debbugging of multiple transferts
		function ui_add_log(message, color)
		{
			var d = new Date();

			var dateString = (('0' + d.getHours())).slice(-2) + ':' +
			(('0' + d.getMinutes())).slice(-2) + ':' +
			(('0' + d.getSeconds())).slice(-2);

			color = (typeof color === 'undefined' ? 'muted' : color);

			var template = $('#debug-template').text();
			template = template.replace('%%date%%', dateString);
			template = template.replace('%%message%%', message);
			template = template.replace('%%color%%', color);

			$('#debug').find('li.empty').fadeOut(); // remove the 'no messages yet'
			$('#debug').prepend(template);
		}

		// Creates a new file and add it to our list
		function ui_multi_add_file(id, file)
		{
			var template = $('#files-template').text();
			template = template.replace('%%filename%%', file.name);

			template = $(template);
			template.prop('id', 'uploaderFile' + id);
			template.data('file-id', id);

			$('#files').find('li.empty').fadeOut(); // remove the 'no files yet'
			$('#files').prepend(template);
		}

		// Changes the status messages on our list
		function ui_multi_update_file_status(id, status, message)
		{
	    	$('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
		}

		// Updates a file progress, depending on the parameters it may animate it or change the color.
		function ui_multi_update_file_progress(id, percent, color, active)
		{
			color = (typeof color === 'undefined' ? false : color);
			active = (typeof active === 'undefined' ? true : active);

			var bar = $('#uploaderFile' + id).find('div.progressbar');

			bar.width(percent + '%').css('width', percent + '%');
			bar.toggleClass('progress-bar-striped progress-bar-animated', active);

			if (percent === 0){
				bar.html('');
			} else {
				bar.html(percent + '%');
			}

			if (color !== false){
				bar.removeClass('bg-success bg-info bg-warning bg-danger');
				bar.addClass('bg-' + color);
			}
		}

		// dnduploader options
		$(function(){
			$('#drag-and-drop-zone').dnduploader({ //
				url: '{PATH_TO_ROOT}/user/upload.php',
				maxFileSize: {MAX_WEIGHT},
				extFilter: null, // array of allowed extensions e.g ["jpg", "jpeg", "png", "gif"] instead of null
				onDragEnter: function(){ // Happens when dragging something over the DnD area
					this.addClass('dnd-active');
				},
				onDragLeave: function(){ // Happens when dragging something OUT of the DnD area
					this.removeClass('dnd-active');
				},
				onInit: function(){ // Plugin is ready to use
					ui_add_log('{L_PG_STATUS_READY}', 'info');
				},
				onComplete: function(){ // All files in the queue are processed (success or error)
					ui_add_log('{L_PG_STATUS_SUCCESS_ALL}');
					// setTimeout(function() {
					// 	location.reload();
					// }, 1000);
				},
				onNewFile: function(id, file){ // When a new file is added using the file selector or the DnD area
					ui_add_log('{L_PG_STATUS_NEW}' + id);
					ui_multi_add_file(id, file);
				},
				onBeforeUpload: function(id){ // about to start uploading a file
					ui_add_log('{L_PG_STATUS_START}' + id);
					ui_multi_update_file_status(id, 'uploading', '{L_PG_STATUS_UPLOADING}');
					ui_multi_update_file_progress(id, 0, '', true);
				},
				onUploadCanceled: function(id) { // Happens when a file is directly canceled by the user.
					ui_multi_update_file_status(id, 'warning', '{L_PG_STATUS_CANCELED}');
					ui_multi_update_file_progress(id, 0, 'warning', false);
				},
				onUploadProgress: function(id, percent){ // Updating file progress
					ui_multi_update_file_progress(id, percent);
				},
				onUploadSuccess: function(id, data){ // A file was successfully uploaded
					ui_add_log('{L_PG_STATUS_RESPONSE_LOG}' + id + ': ' + JSON.stringify(data));
					ui_add_log('{L_PG_STATUS_END_LOG}' + id, 'success');
					ui_multi_update_file_status(id, 'success', '{L_PG_STATUS_END}');
					ui_multi_update_file_progress(id, 100, 'success', false);
				},
				onUploadError: function(id, xhr, status, message){
					ui_multi_update_file_status(id, 'danger', message);
					ui_multi_update_file_progress(id, 0, 'danger', false);
				},
				onFallbackMode: function(){ // When the browser doesn't support this plugin :(
					ui_add_log('{L_PG_STATUS_EXCEPTION}', 'danger');
				},
				onFileSizeError: function(file){
					ui_add_log(file.name + '{L_PG_STATUS_EXCEEDED}', 'danger');
				}
			});
		});

	</script>

	<section id="module-user-upload">
		<header>
			<h1>{L_FILES_ACTION}</h1>
		</header>

		<div class="content">

			<!-- dnd -->
			<div class="elements-container columns-1">
				<div class="block dnd-dropzone">
					<div id="drag-and-drop-zone" class="dnd-uploader xmlhttprequest-preview">
						<h2>{L_DND_FILES}</h2>
						<label for="multiupload" class="upload-btn submit" aria-label="{L_CLICK_TO_ADD}">
							<input id="multiupload" class="inputfile" type="file" name="file" />
							{L_PICK_FILES}
						</label>
					</div>
				</div>
			</div>

			<!-- dnd debugger -->
			<div class="elements-container columns-2">
			    <div class="block content">
			        <h5>Liste des transferts</h5>
			        <ul id="files">
			            <li>{L_WAITING_FILES}</li>
			        </ul>
			    </div>

			    <div class="block content">
			        <h5>Status des transferts</h5>
			        <ul id="debug">
			            <li>{L_WAITING_PLUGIN}</li>
			        </ul>
			    </div>
			</div>

			<!-- dnd debugger - File transferts -->
			<script type="text/html" id="files-template">
			    <li>
			        <div>
			            <p>
			            	<strong>%%filename%%</strong> - Status: <span>{L_WAITING_FILES}</span>
			            </p>
			            <div class="progressbar-container">
			                <div class="progressbar" style="width:0%"></div>
			            </div>
			            <hr />
			        </div>
			    </li>
			</script>

			<!-- dnd debugger - transfert status -->
			<script type="text/html" id="debug-template">
			    <li class="text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
			</script>

			<!-- single -->
			<div id="new-file">
				# INCLUDE message_helper #
				<form action="upload.php?f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" enctype="multipart/form-data" method="post">
					<fieldset>
						<legend>{L_ADD_FILES}</legend>
						<div class="form-element">
							<div class="form-field">
								<input type="file" name="upload_file" id="upload-file">
								<input type="hidden" name="max_file_size" value="2000000">
							</div>
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="valid_up" value="true" class="submit">{L_UPLOAD}</button>
							</div>
					</fieldset>
				</form>
			</div>

			<div class="upload-address-bar">
				<a href="upload.php?root=1{POPUP}"><i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}</a>{URL}
			</div>

			<div class="upload-address-bar-links">
				<a href="upload.php?fup={FOLDER_ID}{POPUP}">
					<i class="fa fa-level-up" aria-hidden="true"></i> {L_FOLDER_UP}
				</a>
				<a href="javascript:display_new_folder();">
					<i class="fa fa-plus" aria-hidden="true"></i> {L_FOLDER_NEW}
				</a>
				<a href="javascript:document.getElementById('upload-file').click();">
					<i class="fa fa-save" aria-hidden="true"></i> {L_ADD_FILES}
				</a>
			</div>
			<div class="spacer"></div>

			<legend>{L_FOLDER_CONTENT}</legend>

			<div class="upload-elements-container">

				# IF C_EMPTY_FOLDER #
					<div id="empty-folder" class="message-helper notice">{L_EMPTY_FOLDER}</div>
					<span id="new-folder"></span>
				# ELSE #
					# START folder #
						<div class="upload-elements-repertory">
							<a class="infos-options" href="upload.php?f={folder.ID}{POPUP}">
								<i class="fa # IF folder.C_MEMBER_FOLDER #fa-users # ELSE #fa-folder # ENDIF #fa-2x"></i>
							</a>
							<span class="infos-options" id="f{folder.ID}"><a href="upload.php?f={folder.ID}{POPUP}">{folder.NAME}</a></span>
							<div class="upload-repertory-controls">
								{folder.RENAME_FOLDER}
								<span>
									<a href="upload.php?delf={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element" aria-label="{folder.L_TYPE_DEL_FOLDER}"><i class="fa fa-delete" aria-hidden="true"></i></a>
								</span>
								<span>
									<a href="upload{folder.U_MOVE}" aria-label="{L_MOVETO}"><i class="fa fa-move" aria-hidden="true"></i></a>
								</span>
								<span id="img{folder.ID}"></span>
							</div>
						</div>
					# END folder #
					<span id="new-folder"></span>

					# START files #
					<div class="upload-elements-file">
						# IF files.C_IMG #
						<a href="{files.URL}" data-lightbox="formatter" data-rel="lightcase:collection">
							<div class="upload-element-picture" style="background-image: url({files.URL})"></div>
						</a>
						# ELSE #
						<a class="# IF files.C_RECENT_FILE #upload-recent-file# END IF #" href="{files.URL}" {files.LIGHTBOX}>
							<div class="upload-element-icon"><i class="fa-upload-img {files.IMG}"></i></div>
						</a>
						# ENDIF #
						<div class="upload-element-name# IF files.C_RECENT_FILE # upload-recent-file# ENDIF #" id="fi1{files.ID}">{files.NAME}</div>
						<span id="fi{files.ID}"></span>
						{files.BBCODE}
						<div class="upload-file-controls">
							{files.RENAME_FILE}
							<a href="upload.php?del={files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" data-confirmation="delete-element" aria-label="{L_DELETE}"><i class="fa fa-delete" aria-hidden="true"></i></a>
							<a href="upload{files.U_MOVE}" aria-label="{L_MOVETO}"><i class="fa fa-move" aria-hidden="true"></i></a>
							{files.INSERT}
						</div>
						<span class="infos-options text-strong">{files.FILETYPE}</span>
						<span class="text-strong">{files.SIZE}</span>
						<span id="imgf{files.ID}"></span>
					</div>
					# END files #
				# ENDIF #
				<div class="options infos">
					<span class="infos-options" id="total-folder">{L_FOLDERS} : <strong>{TOTAL_FOLDERS}</strong></span>
					<span class="infos-options">{L_FILES} : <strong>{TOTAL_FILES}</strong></span>
					<span class="infos-options">{L_FOLDER_SIZE} : <strong>{TOTAL_FOLDER_SIZE}</strong></span>
					<span class="infos-options">{L_DATA} : <strong>{TOTAL_SIZE}</strong></span>
				</div>
			</div>

		</div>

		<footer>
			# IF C_DISPLAY_CLOSE_BUTTON #
			<fieldset class="fieldset-submit">
				<legend>${LangLoader::get_message('close', 'main')}</legend>
				<button type="reset" onclick="javascript:close_popup()" value="true">${LangLoader::get_message('close', 'main')}</button>
			</fieldset>
			# ENDIF #
		</footer>

	</section>
