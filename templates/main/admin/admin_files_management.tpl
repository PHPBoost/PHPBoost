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
			window.open('../member/upload_popup.php?id=' + id, "", "width="+width+", height="+height+ ",location=no,status=no,toolbar=no,scrollbars=" + scrollbars + ",resizable=yes");
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
				document.getElementById('new_folder').innerHTML += '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onBlur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');" /></td></tr></table></div>';
				document.getElementById('folder_name').focus();
			}
			else
			{	
				document.getElementById('new_folder' + (divid - 1)).style.display = 'block';
				document.getElementById('new_folder' + (divid - 1)).innerHTML = '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onBlur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');" /></td></tr></table></div>';
				document.getElementById('folder_name').focus();
				this.divid--;	
				hide_folder = false;
			}
		}
		function display_rename_folder(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('f' + id) )
			{	
				document.getElementById('f' + id).innerHTML = '<input type="text" name="finput' + id + '" id="finput' + id + '" class="text" value="' + previous_name + '" onBlur="rename_folder(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
				document.getElementById('finput' + id).focus();
			}
		}		
		function rename_folder(id_folder, previous_name, previous_cut_name)
		{
			var xhr_object = null;
			var data = null;
			var name = document.getElementById("finput" + id_folder).value;
			var filename = "../includes/xmlhttprequest.php?rename_folder=1";
			var regex = /\/|\.|\\|\||\?|<|>|\"/;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			document.getElementById('img' + id_folder).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" style="vertical-align:middle;" />';
			
			if( name != '' && regex.test(name) ) //interdiction des caractères spéciaux dans le nom.
			{
				alert("{L_FOLDER_FORBIDDEN_CHARS}");
				document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
				document.getElementById('img' + id_folder).innerHTML = '';
			}
			else if( name != '' )
			{
				data = "id_folder=" + id_folder + "&name=" + name + "&previous_name=" + previous_name + "&user_id=" + {USER_ID};
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
					{
						if( xhr_object.responseText != "" )
						{
							document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + name + '</a>';
							document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" style="vertical-align:middle" /></a>';
						}
						else
						{	
							alert("{L_FOLDER_ALREADY_EXIST}");
							document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
						}
						document.getElementById('img' + id_folder).innerHTML = '';
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}
		}	
		function add_folder(id_parent, user_id, divid)
		{
			var xhr_object = null;
			var data = null;
			var name = document.getElementById("folder_name").value;
			var filename = "../includes/xmlhttprequest.php?new_folder=1";
			var regex = /\/|\.|\\|\||\?|<|>|\"/;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			if( name != '' && regex.test(name) ) //interdiction des caractères spéciaux dans la nom.
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
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
					{
						if( xhr_object.responseText > 0 )
						{
							document.getElementById('new_folder' + divid).innerHTML = '<table style="border:0"><tr><td style="width:34px;vertical-align:top;"><a href="admin_files.php?f=' + xhr_object.responseText + '"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></a></td><td style="padding-top:8px;"> <span id="f' + xhr_object.responseText + '"><a class="com" href="admin_files.php?f=' + xhr_object.responseText + '">' + name + '</a></span></span><div style="padding-top:5px;"><span id="fhref' + xhr_object.responseText + '"><span id="fihref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" style="vertical-align:middle;" /></a></span></a></span> <a href="admin_files.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}" onClick="javascript:return Confirm_folder();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" style="vertical-align:middle" /></a> <div style="position:relative;z-index:100;margin-top:95px;margin-left:-70px;float:left;display:none;" id="move' + xhr_object.responseText + '"><div class="bbcode_block" style="width:150px;" onmouseover="upload_hide_block(' + xhr_object.responseText + ', 1);" onmouseout="upload_hide_block(' + xhr_object.responseText + ', 0);"><div style="margin-bottom:4px;"><strong>{L_MOVETO}</strong>:</div><select name="move" onchange="if( this.options[this.selectedIndex].value != -1) document.location = \'admin_files.php?movef=' + xhr_object.responseText + '&amp;to=\' + this.options[this.selectedIndex].value">{MOVE_LIST}</select><br /><br /></div></div><a href="javascript:upload_display_block(' + xhr_object.responseText + ');" onmouseover="upload_hide_block(' + xhr_object.responseText + ', 1);" onmouseout="upload_hide_block(' + xhr_object.responseText + ', 0);" class="bbcode_hover" title=""><img src="../templates/{THEME}/images/upload/move.png" alt="" style="vertical-align:middle;" /></a></div></td></tr></table>';
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

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
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
			var xhr_object = null;
			var data = null;
			var name = document.getElementById("fiinput" + id_file).value;
			var filename = "../includes/xmlhttprequest.php?rename_file=1";
			var regex = /\/|\\|\||\?|<|>|\"/;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" style="vertical-align:middle;" />';
			
			if( name != '' && regex.test(name) ) //interdiction des caractères spéciaux dans la nom.
			{
				alert("{L_FOLDER_FORBIDDEN_CHARS}");	
				document.getElementById('fi1' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
				document.getElementById('img' + id_file).innerHTML = '';
			}
			else if( name != '' )
			{
				data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name + "&user_id=" + {USER_ID};
				xhr_object.open("POST", filename, true);

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
							document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" style="vertical-align:middle" /></a>';
						}
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';	
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" style="vertical-align:middle" /></a>';
						document.getElementById('img' + id_file).innerHTML = '';					
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}
		}
		
		var delay = 300; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
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
					<a href="admin_files.php"><img src="../templates/{THEME}/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick_link">{L_FILES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="../templates/{THEME}/images/admin/files.png" alt="" /></a>
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
							<a href="admin_files.php?root=1"><img src="../templates/{THEME}/images/upload/home.png" style="vertical-align:middle;" alt="" /></a>
							<a href="admin_files.php?root=1">{L_ROOT}</a>
							<br />					
							<a href="admin_files.php?fup={FOLDER_ID}"><img src="../templates/{THEME}/images/upload/folder_up.png" style="vertical-align:middle;" alt="" /></a>
							<a href="admin_files.php?fup={FOLDER_ID}">{L_FOLDER_UP}</a>
						</span>
						<span style="float:right;">
							<span id="new_folder_link">
								<a href="javascript:display_new_folder();"><img src="../templates/{THEME}/images/upload/folder_new.png" style="vertical-align:middle;" alt="" /></a>
								<a href="javascript:display_new_folder();">{L_FOLDER_NEW}</a>
							</span>
							<br />
							<a href="#new_file"><img src="../templates/{THEME}/images/upload/files_add.png" style="vertical-align:middle;" alt="" /></a>
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
								<img src="../templates/{THEME}/images/upload/folder_mini.png" style="vertical-align:middle;" alt="" /> <a href="admin_files.php">{L_ROOT}</a>{URL}
							</div>
						</div>
					</td>
				</tr>	
				
				<tr>	
					<td class="row2" style="padding:10px 2px;">
						# START empty_folder #
							<p style="text-align:center;" id="empty_folder">					
								{empty_folder.EMPTY_FOLDER}					
							</p>
						# END empty_folder #
						
						# START folder #		
						<div style="width:210px;height:90px;float:left;margin-top:5px;">
							<table style="border:0;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<a href="admin_files.php{folder.U_FOLDER}"><img src="../templates/{THEME}/images/upload/{folder.IMG_FOLDER}" alt="" /></a>
									</td>
									<td style="padding-top:8px;">						
										<span id="f{folder.ID}"><a href="admin_files.php{folder.U_FOLDER}" class="com">{folder.NAME}</a></span>
										<div style="padding-top:5px;">
											{folder.RENAME_FOLDER}	<a href="admin_files.php?{folder.DEL_TYPE}={folder.ID}&amp;f={FOLDER_ID}" onClick="javascript:return Confirm_{folder.ALERT_DEL}();" title="{folder.L_TYPE_DEL_FOLDER}">{folder.DEL_TYPE_IMG}</a>
											
											<a style="display:none" href="admin_files{folder.U_MOVE}" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" style="vertical-align:middle;" /></a>
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
										{files.IMG}
									</td>
									<td style="padding-top:8px;">	
										{files.URL}<span id="fi1{files.ID}">{files.NAME}</span></a><span id="fi{files.ID}"></span><br />
										{files.BBCODE}<br />							
										<span class="text_small">{files.FILETYPE}</span><br />
										<span class="text_small">{files.SIZE}</span><br />
										{files.RENAME_FILE}
										<a href="admin_files.php?del={files.ID}&amp;f={FOLDER_ID}" onClick="javascript:return Confirm_file();" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" style="vertical-align:middle;" /></a> 
										
										<a style="display:none" href="admin_files{files.U_MOVE}" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" style="vertical-align:middle;" /></a>						
										
										{files.INSERT}
										<span id="img{files.ID}"></span>
									</td>
								</tr>
							</table>
						</div>	
						# END files #				
						
					</td>
				</tr>
				
				# START error_handler #
				<tr>
					<td class="row3">	
						<span id="errorh"></span>
						<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
							<br />	
						</div>
						<br />	
					</td>	
				</tr>
				# END error_handler #
				<tr>				
					<td class="row3" id="new_file">							
						<form action="admin_files.php?f={FOLDER_ID}" enctype="multipart/form-data" method="post">
							<span style="float:left">						
								<strong>{L_ADD_FILES}</strong>
								<br />
									<input type="file" name="upload_file" size="30" class="submit" />					
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
		