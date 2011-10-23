		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
		<script type="text/javascript">
		<!--
		function Confirm_file() {
			return confirm("{L_CONFIRM_DEL_FILE}");
		}
		function Confirm_folder() {
			return confirm("{L_CONFIRM_DEL_FOLDER}");
		}	
		function Confirm_member() {
			return confirm("{L_CONFIRM_EMPTY_FOLDER}");
		}
		function popup_upload(id, width, height, scrollbars)
		{
			if( height == '0' )
				height = screen.height - 150;
			if( width == '0' )
				width = screen.width - 200;
			window.open('{PATH_TO_ROOT}/member/upload_popup.php?id=' + id, "", "width="+width+", height="+height+ ",location=no,status=no,toolbar=no,scrollbars=" + scrollbars + ",resizable=yes");
		}
		var hide_folder = false;
		var empty_folder = 0;
		
		function display_new_folder()
		{
			if( document.getElementById('empty_folder') )
				document.getElementById('empty_folder').style.display = 'none';	
			
			if ( typeof this.divid == 'undefined' )
				this.divid = 0;
			else
				this.divid++;
				
			if( !hide_folder )
			{
				document.getElementById('new_folder').innerHTML += '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');" /></td></tr></table></div>';
				document.getElementById('folder_name').focus();
			}
			else
			{	
				document.getElementById('new_folder' + (divid - 1)).style.display = 'block';
				document.getElementById('new_folder' + (divid - 1)).innerHTML = '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');" /></td></tr></table></div>';
				document.getElementById('folder_name').focus();
				this.divid--;	
				hide_folder = false;
			}
		}
		function display_rename_folder(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('f' + id) )
			{	
				document.getElementById('f' + id).innerHTML = '<input type="text" name="finput' + id + '" id="finput' + id + '" class="text" value="' + previous_name + '" onblur="rename_folder(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
				document.getElementById('finput' + id).focus();
			}
		}		
		function rename_folder(id_folder, previous_name, previous_cut_name)
		{
			var name = document.getElementById("finput" + id_folder).value;
			var regex = /\/|\.|\\|\||\?|<|>|\"/;

			document.getElementById('img' + id_folder).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
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
							document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
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
			var name = document.getElementById("folder_name").value;
			var regex = /\/|\.|\\|\||\?|<|>|\"/;
			
			if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
			{
				alert("{L_FOLDER_FORBIDDEN_CHARS}");
				document.getElementById('new_folder' + divid).innerHTML = '';
				document.getElementById('new_folder' + divid).style.display = 'none';
				hide_folder = true;
				if( document.getElementById('empty_folder') && empty_folder == 0 )
					document.getElementById('empty_folder').style.display = 'block';
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
							document.getElementById('new_folder' + divid).innerHTML = '<table style="border:0"><tr><td style="width:34px;vertical-align:top;"><a href="admin_files.php?f=' + xhr_object.responseText + '"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_max.png" alt="" /></a></td><td style="padding-top:8px;"> <span id="f' + xhr_object.responseText + '"><a class="com" href="admin_files.php?f=' + xhr_object.responseText + '">' + name + '</a></span></span><div style="padding-top:5px;"><span id="fhref' + xhr_object.responseText + '"><span id="fihref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a></span></a></span> <a href="admin_files.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}" onclick="javascript:return Confirm_folder();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a> <a href="admin_files.php?movefd=' + xhr_object.responseText + '&amp;f={FOLDER_ID}&amp;fm=' + user_id + '" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a></div><span id="img' + xhr_object.responseText + '"></span></td></tr></table>';
							var total_folder = document.getElementById('total_folder').innerHTML;
							total_folder++;						
							document.getElementById('total_folder').innerHTML = total_folder;
							
							empty_folder++;
						}
						else
						{	
							alert("{L_FOLDER_ALREADY_EXIST}");
							document.getElementById('new_folder' + divid).innerHTML = '';
							document.getElementById('new_folder' + divid).style.display = 'none';
							hide_folder = true;
						}
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}
			else
			{
				if( document.getElementById('empty_folder') && empty_folder == 0 )
					document.getElementById('empty_folder').style.display = 'block';
				document.getElementById('new_folder' + divid).innerHTML = '';
				document.getElementById('new_folder' + divid).style.display = 'none';
				hide_folder = true;
			}
		}
		function display_rename_file(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('fi' + id) )
			{	
				document.getElementById('fi1' + id).style.display = 'none';
				document.getElementById('fi' + id).style.display = 'inline';
				document.getElementById('fi' + id).innerHTML = '<input type="text" name="fiinput' + id + '" id="fiinput' + id + '" class="text" value="' + previous_name + '" onblur="rename_file(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
				document.getElementById('fiinput' + id).focus();
			}
		}	
		function rename_file(id_file, previous_name, previous_cut_name)
		{
			var name = document.getElementById("fiinput" + id_file).value;
			var regex = /\/|\\|\||\?|<|>|\"/;
			
			document.getElementById('imgf' + id_file).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
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
							document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
						}
						document.getElementById('imgf' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';	
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
						document.getElementById('imgf' + id_file).innerHTML = '';					
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}
		}
		
		var delay = 300; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
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
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FILES_MANAGEMENT}</li>
				<li>
					<a href="admin_files.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick_link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files_config.php" class="quick_link">{L_CONFIG_FILES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<table class="module_table">
				<tr> 
					<th>
						{L_FILES_ACTION}
					</th>
				</tr>							
				<tr> 
					<td class="row2">
						<span style="float:left;">
							<a href="admin_files.php?root=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/home.png" class="valign_middle" alt="" /></a>
							<a href="admin_files.php?root=1">{L_ROOT}</a>
							<br />					
							<a href="admin_files.php?fup={FOLDER_ID}{FOLDERM_ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_up.png" class="valign_middle" alt="" /></a>
							<a href="admin_files.php?fup={FOLDER_ID}{FOLDERM_ID}">{L_FOLDER_UP}</a>
						</span>
						<span style="float:right;">
							<span id="new_folder_link">
								<a href="javascript:display_new_folder();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_new.png" class="valign_middle" alt="" /></a>
								<a href="javascript:display_new_folder();">{L_FOLDER_NEW}</a>
							</span>
							<br />
							<a href="#new_file"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" class="valign_middle" alt="" /></a>
							<a href="#new_file">{L_ADD_FILES}</a>		
							<br />
						</span>
					</td>
				</tr>							
				<tr> 
					<td class="row2" style="margin:0px;padding:0px">
						<div style="float:left;padding:2px;padding-left:8px;">
							{L_URL}
						</div>
						<div style="float:right;width:90%;padding:2px;background:#f3f3ee;padding-left:6px;color:black;border:1px solid #7f9db9;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder_mini.png" class="valign_middle" alt="" /> <a href="admin_files.php">{L_ROOT}</a>{URL}
						</div>
					</td>
				</tr>	
				
				<tr>	
					<td class="row2" style="padding:5px 2px;">
						# IF C_EMPTY_FOLDER #
							<p style="text-align:center;padding-top:15px;" id="empty_folder">					
								{L_EMPTY_FOLDER}					
							</p>
						# ENDIF #
						
						# START folder #		
						<div style="width:210px;height:90px;float:left;margin-top:5px;">
							<table style="border:0;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<a href="admin_files.php{folder.U_FOLDER}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/{folder.IMG_FOLDER}" alt="" /></a>
									</td>
									<td style="padding-top:8px;">						
										<span id="f{folder.ID}"><a href="admin_files.php{folder.U_FOLDER}" class="com">{folder.NAME}</a></span>
										<div style="padding-top:5px;">
											{folder.RENAME_FOLDER}	<a href="admin_files.php?{folder.DEL_TYPE}={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{FOLDERM_ID}" onclick="javascript:return Confirm_{folder.ALERT_DEL}();" title="{folder.L_TYPE_DEL_FOLDER}">{folder.DEL_TYPE_IMG}</a>
											
											# IF folder.C_TYPEFOLDER #<a href="admin_files{folder.U_MOVE}" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a># ENDIF #
											<span id="img{folder.ID}"></span>
										</div>
									</td>
								</tr>						
							</table>
						</div>
						# END folder #
						
						<span id="new_folder"></span>
						
						# START files #
						<div style="width:210px;height:90px;float:left;margin-top:5px;">
							<table style="border:0;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/{files.IMG}" alt="" />
									</td>
									<td style="padding-top:8px;">	
										<a class="com" href="{files.URL}"{files.LIGHTBOX}><span id="fi1{files.ID}">{files.NAME}</span></a><span id="fi{files.ID}"></span><br />
										{files.BBCODE}<br />							
										<span class="text_small">{files.FILETYPE}</span><br />
										<span class="text_small">{files.SIZE}</span><br />
										{files.RENAME_FILE}
										<a href="admin_files.php?del={files.ID}&amp;f={FOLDER_ID}&amp;fm={USER_ID}&amp;token={TOKEN}" onclick="javascript:return Confirm_file();" title="{L_DELETE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a> 
										
										<a href="admin_files{files.U_MOVE}" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>						
										
										{files.INSERT}
										<span id="imgf{files.ID}"></span>
									</td>
								</tr>
							</table>
						</div>	
						# END files #	
						<div class="spacer">&nbsp;</div>						
					</td>
				</tr>
				<tr>
					<td class="row3">	
						# INCLUDE message_helper #
					</td>	
				</tr>
				<tr>				
					<td class="row3" id="new_file">							
						<form action="admin_files.php?f={FOLDER_ID}&amp;fm={USER_ID}&amp;token={TOKEN}" enctype="multipart/form-data" method="post">
							<span style="float:left">						
								<strong>{L_ADD_FILES}</strong>
								<br />
									<input type="file" name="upload_file" size="30" class="file" />					
									<input type="hidden" name="max_file_size" value="2000000" />
									<br />
									<input type="submit" name="valid_up" value="{L_UPLOAD}" class="submit" />							
							</span>	
							<span style="float:right;text-align:right">
								{L_FOLDERS}: <strong><span id="total_folder">{TOTAL_FOLDERS}</span></strong><br />
								{L_FILES}: <strong>{TOTAL_FILES}</strong><br />
								{L_FOLDER_SIZE}: <strong>{TOTAL_FOLDER_SIZE}</strong><br />
								{L_DATA}: <strong>{TOTAL_SIZE}</strong>
							</span>	
						</form>				
					</td>
				</tr>
				<tr> 
					<th>
						&nbsp;
					</th>
				</tr>
			</table>
		</div>
		