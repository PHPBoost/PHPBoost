<script>
<!--
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
		document.getElementById('new-folder').innerHTML += '<div class="upload-elements-repertory" id="new-folder' + divid + '"><i class="fa fa-folder fa-2x" aria-hidden="true"></i> <input type="text" name="folder-name" id="folder-name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');"></div>';
		document.getElementById('folder-name').focus();
	}
	else
	{
		document.getElementById('new-folder' + (divid - 1)).style.display = 'block';
		document.getElementById('new-folder' + (divid - 1)).innerHTML = '<div class="upload-elements-repertory" id="new-folder' + divid + '"><i class="fa fa-folder fa-2x" aria-hidden="true"></i> <input type="text" name="folder-name" id="folder-name" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid-1) + ');"></div>';
		document.getElementById('folder-name').focus();
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
	var name = document.getElementById("finput" + id_folder).value;
	var regex = /\/|\.|\\|\||\?|<|>|\"/;

	document.getElementById('img' + id_folder).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans le nom.
	{
		alert("{L_FOLDER_FORBIDDEN_CHARS}");
		document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
		document.getElementById('img' + id_folder).innerHTML = '';
	}
	else if( name != '' )
	{
		data = "id_folder=" + id_folder + "&name=" + name + "&previous_name=" + previous_name + "&user_id=" + {USER_ID};
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_folder=1');
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( xhr_object.responseText != "" )
				{
					document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + name + '</a>';
					document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a>';
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

	if( name != '' && regex.test(name) ) //interdiction des caracteres speciaux dans la nom.
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
		data = "name=" + name + "&user_id=" + user_id + "&id_parent=" + id_parent;
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&new_folder=1');
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( xhr_object.responseText > 0 )
				{
					document.getElementById('new-folder' + divid).innerHTML = '<a href="admin_files.php?f=' + xhr_object.responseText + '"><i class="fa fa-folder fa-4x" aria-hidden="true"></i></a><br /> <span id="f' + xhr_object.responseText + '"><a href="admin_files.php?f=' + xhr_object.responseText + '" class="com">' + name + '</a></span><br /> <div class="upload-repertory-controls"><span id="fhref' + xhr_object.responseText + '"><span id="fihref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');" class="fa fa-edit"></a></span></span> <span><a href="admin_files.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}" class="fa fa-trash-alt" data-confirmation="delete-element"></a></span> <span><a href="admin_files.php?movefd=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;fm=' + user_id + '" aria-label{L_MOVETO}"><i class="fa fa-share" aria-hidden="true"></i></a></span> <span id="img' + xhr_object.responseText + '"></div>';
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
	if( name != '' && regex.test(name) ) //interdiction des caracteres speciaux dans la nom.
	{
		alert("{L_FOLDER_FORBIDDEN_CHARS}");
		document.getElementById('fi1' + id_file).style.display = 'inline';
		document.getElementById('fi' + id_file).style.display = 'none';
		document.getElementById('imgf' + id_file).innerHTML = '';
	}
	else if( name != '' )
	{
		data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name + "&user_id=" + {USER_ID};
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_file=1');
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
					document.getElementById('fihref' + id_file).innerHTML = '<a aria-label="${LangLoader::get_message('edit', 'common')}" href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><i class="fa fa-edit" aria-hidden="true"></i></a>';
				}
				document.getElementById('imgf' + id_file).innerHTML = '';
			}
			else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
			{
				document.getElementById('fi' + id_file).style.display = 'none';
				document.getElementById('fi1' + id_file).style.display = 'inline';
				document.getElementById('fihref' + id_file).innerHTML = '<a aria-label="${LangLoader::get_message('edit', 'common')}" href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');"><i class="fa fa-edit" aria-hidden="true"></i></a>';
				document.getElementById('imgf' + id_file).innerHTML = '';
			}
		}
		xmlhttprequest_sender(xhr_object, data);
	}
}

var delay = 300; //Delai apres lequel le bloc est automatiquement masque, apres le depart de la souris.
var timeout;
var displayed = false;
//Affiche le bloc.
function upload_display_block(divID)
{
	var i;

	if( timeout )
		clearTimeout(timeout);

	var block = document.getElementById('move' + divID);
	if( block.style.display == 'none' )
	{
		block.style.display = 'block';
		displayed = true;
	}
	else
	{
		block.style.display = 'none';
		displayed = false;
	}
}
//Cache le bloc.
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

// D&D multiple files
$(document).ready(function(){
	var $input = $('#inputfile');

	$input.on('change', function(){
		var fileNbr = $input[0].files.length,
			items = $input[0].files,
			list = '';
		$('#ready-to-upload p').innerHTML = 'Files number: ' + fileNbr;
		for(var i=0; i < fileNbr; i++) {
			var fileName = items[i].name,
				fileSize = items[i].size,
				fileType = items[i].type;
			if(fileType.indexOf('image/') === 0)
			{
			  list += '<span><img width="40" src="' + URL.createObjectURL(items[i]) + '" /> '+fileName+'</span>';
			} else
			list += '<span>'+fileName+'</span>';
			// list += '<li>'+fileName+' / '+ fileSize +' / '+ fileType +'</li>';
			// console.log(list);
		}
		$('#ready-to-load ul').append(list);
	})
});

-->
</script>

<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
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
						<label for="inputfiles" class="dnd-label">${LangLoader::get_message('drag.and.drop.files', 'main')} <p></p></label>
						<input type="file" name="upload_file[]" id="inputfiles" class="ufiles" />
					</div>
					<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
					<div class="ready-to-load">
						<button type="button" class="button clear-list">${LangLoader::get_message('clear.list', 'main')}</button>
						<span class="fa-stack fa-lg">
							<i class="far fa-file fa-stack-2x "></i>
							<strong class="fa-stack-1x files-nbr"></strong>
						</span>
					</div>
					<div class="modal-container">
						<button class="button upload-help" data-modal data-target="upload-helper"><i class="fa fa-question"></i></button>
						<div id="upload-helper" class="modal modal-animation">
							<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
							<div class="content-panel">
								<h3>${LangLoader::get_message('upload.helper', 'main')}</h3>
								<p><strong>${LangLoader::get_message('max.file.size', 'main')} :</strong> {MAX_FILE_SIZE_TEXT}</p>
								<p><strong>${LangLoader::get_message('allowed.extensions', 'main')} :</strong> "{ALLOWED_EXTENSIONS}"</p>
							</div>
						</div>
					</div>
				</div>
				<ul class="ulist"></ul>
			</fieldset>
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
				<a href="admin_files.php"><i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}</a>{URL}
			</div>

			<div class="upload-address-bar-links">
				<a href="admin_files.php?root=1">
					<i class="fa fa-home" aria-hidden="true"></i> {L_ROOT}
				</a>
				<a href="admin_files.php?# IF C_MEMBER_ROOT_FOLDER #showm=1# ELSE #fup={FOLDER_ID}{FOLDERM_ID}# ENDIF #">
					<i class="fa fa-level-up-alt" aria-hidden="true"></i> {L_FOLDER_UP}
				</a>
				<a href="javascript:display_new_folder();">
					<i class="fa fa-plus" aria-hidden="true"></i> {L_FOLDER_NEW}
				</a>
				<a href="javascript:document.getElementById('inputfiles').click();">
					<i class="fa fa-save" aria-hidden="true"></i> {L_ADD_FILES}
				</a>
			</div>
		</div>
	</fieldset>

	<fieldset class="upload-elements-container">
		<legend>{L_FOLDER_CONTENT}</legend>
		<div class="fieldset-inset">
			# IF C_EMPTY_FOLDER #
				<div id="empty-folder" class="message-helper bgc notice">{L_EMPTY_FOLDER}</div>
				<span id="new-folder"></span>
			# ELSE #
				# START folder #
					<div class="upload-elements-repertory">
						<div class="upload-element-icon">
							<a href="admin_files.php{folder.U_FOLDER}"><i class="# IF folder.C_MEMBER_FOLDER #fa fa-users# ELSE #far fa-folder# ENDIF # fa-4x" aria-hidden="true"></i></a>
						</div>
						<span id="f{folder.ID}"><a href="admin_files.php{folder.U_FOLDER}" class="com">{folder.NAME}</a></span><br />
						<div class="upload-repertory-controls">
							# IF folder.C_TYPEFOLDER #<span id="fhref{folder.ID}"><a href="javascript:display_rename_folder('{folder.ID}', '{folder.NAME_WITH_SLASHES}', '{folder.NAME_CUT_WITH_SLASHES}');" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a></span># ENDIF #
							<span>
								# IF NOT folder.C_MEMBERS_FOLDER #<a href="admin_files.php?{folder.DEL_TYPE}={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{FOLDERM_ID}" data-confirmation="# IF folder.C_MEMBER_FOLDER #{L_CONFIRM_EMPTY_FOLDER}# ELSE #delete-element# ENDIF #" aria-label="{folder.L_TYPE_DEL_FOLDER}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
							</span>
							<span>
								# IF folder.C_TYPEFOLDER #<a href="admin_files{folder.U_MOVE}" aria-label="{L_MOVETO}"><i class="fa fa-share" aria-hidden="true"></i></a># ENDIF #
							</span>
							<span id="img{folder.ID}"></span>
						</div>
					</div>
				# END folder #
				<span id="new-folder"></span>

				# START files #
					<div class="upload-elements-file">
						# IF files.C_ENABLED_THUMBNAILS #
							# IF files.C_IMG #
								<a href="{files.URL}" data-lightbox="formatter" data-rel="lightcase:collection">
									<div class="upload-element-picture" style="background-image: url({files.URL})"></div>
								</a>
								<div class="upload-element-name# IF files.C_RECENT_FILE # upload-recent-file# ENDIF #" id="fi1{files.ID}">{files.NAME}</div>
							# ELSE #
								<a class="# IF files.C_RECENT_FILE #upload-recent-file# END IF #" href="{files.URL}" {files.LIGHTBOX}>
									<div class="upload-element-icon"><i class="{files.IMG} fa-fw fa-4x"></i></div>
								</a>
								<div class="upload-element-name# IF files.C_RECENT_FILE # upload-recent-file# ENDIF #" id="fi1{files.ID}">{files.NAME}</div>
							# ENDIF #
						# ELSE #
							<div class="upload-element-name# IF files.C_RECENT_FILE # upload-recent-file# ENDIF #" id="fi1{files.ID}">
								# IF files.C_IMG #
									<a href="{files.URL}" data-lightbox="formatter" data-rel="lightcase:collection"><i class="{files.IMG} fa-lg"></i></a>
								# ELSE #
									<a class="# IF files.C_RECENT_FILE #upload-recent-file# END IF #" href="{files.URL}" {files.LIGHTBOX}><i class="{files.IMG} fa-lg"></i></a>
								# ENDIF #
								{files.NAME}
							</div>
						# ENDIF #
						<span id="fi{files.ID}"></span>
						{files.BBCODE}
						<div class="upload-file-controls">
							<span id="fihref{files.ID}"><a href="javascript:display_rename_file('{files.ID}', '{files.NAME_WITH_SLASHES}', '{files.NAME_CUT_WITH_SLASHES}');" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a></span>
							<a href="admin_files.php?del={files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
							<a href="admin_files{files.U_MOVE}" aria-label="{L_MOVETO}"><i class="fa fa-share" aria-hidden="true"></i></a>
							{files.INSERT}
						</div>
						<span class="text-strong">{files.FILETYPE}</span><br />
						<span class="text-strong">{files.SIZE}</span>
						<span id="imgf{files.ID}"></span>
					</div>
				# END files #
			# ENDIF #
		</div>
		<div class="options infos">
			<span class="infos-options" id="total-folder">{L_FOLDERS} : <strong>{TOTAL_FOLDERS}</strong></span>
			<span class="infos-options">{L_FILES} : <strong>{TOTAL_FILES}</strong></span>
			<span class="infos-options">{L_FOLDER_SIZE} : <strong>{TOTAL_FOLDER_SIZE}</strong></span>
			<span class="infos-options">{L_DATA} : <strong>{TOTAL_SIZE}</strong></span>
		</div>
	</fieldset>

	<div class="spacer"></div>
</div>
<script>
	jQuery('#inputfiles').dndfiles({
		multiple: true,
		maxFileSize: '{MAX_FILE_SIZE}',
		maxFilesSize: '-1',
		allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
		warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'main'))},
		warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'main'))},
		warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'main'))},
		warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.nbr', 'main'))},
	});
</script>
