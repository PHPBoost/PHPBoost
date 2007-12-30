		<script type="text/javascript">
		<!--
		function Confirm_file() {
			return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_FILE']) ? $this->_var['L_CONFIRM_DEL_FILE'] : ''; ?>");
		}
		function Confirm_folder() {
			return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_FOLDER']) ? $this->_var['L_CONFIRM_DEL_FOLDER'] : ''; ?>");
		}	
		function Confirm_member() {
			return confirm("<?php echo isset($this->_var['L_CONFIRM_EMPTY_FOLDER']) ? $this->_var['L_CONFIRM_EMPTY_FOLDER'] : ''; ?>");
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
				document.getElementById('new_folder').innerHTML += '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onBlur="add_folder(\'<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>\', \'<?php echo isset($this->_var['USER_ID']) ? $this->_var['USER_ID'] : ''; ?>\', ' + divid + ');" /></td></tr></table></div>';
				document.getElementById('folder_name').focus();
			}
			else
			{	
				document.getElementById('new_folder' + (divid - 1)).style.display = 'block';
				document.getElementById('new_folder' + (divid - 1)).innerHTML = '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onBlur="add_folder(\'<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>\', \'<?php echo isset($this->_var['USER_ID']) ? $this->_var['USER_ID'] : ''; ?>\', ' + (divid - 1) + ');" /></td></tr></table></div>';
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
			
			document.getElementById('img' + id_folder).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" style="vertical-align:middle;" />';
			
			if( name != '' && regex.test(name) ) //interdiction des caractères spéciaux dans le nom.
			{
				alert("<?php echo isset($this->_var['L_FOLDER_FORBIDDEN_CHARS']) ? $this->_var['L_FOLDER_FORBIDDEN_CHARS'] : ''; ?>");
				document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + previous_cut_name + '</a>';
				document.getElementById('img' + id_folder).innerHTML = '';
			}
			else if( name != '' )
			{
				data = "id_folder=" + id_folder + "&name=" + name + "&previous_name=" + previous_name + "&user_id=" + <?php echo isset($this->_var['USER_ID']) ? $this->_var['USER_ID'] : ''; ?>;
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
					{
						if( xhr_object.responseText != "" )
						{
							document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="admin_files.php?f=' + id_folder + '">' + name + '</a>';
							document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="" style="vertical-align:middle" /></a>';
						}
						else
						{	
							alert("<?php echo isset($this->_var['L_FOLDER_ALREADY_EXIST']) ? $this->_var['L_FOLDER_ALREADY_EXIST'] : ''; ?>");
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
				alert("<?php echo isset($this->_var['L_FOLDER_FORBIDDEN_CHARS']) ? $this->_var['L_FOLDER_FORBIDDEN_CHARS'] : ''; ?>");
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
							document.getElementById('new_folder' + divid).innerHTML = '<table style="border:0"><tr><td style="width:34px;vertical-align:top;"><a href="admin_files.php?f=' + xhr_object.responseText + '"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_max.png" alt="" /></a></td><td style="padding-top:8px;"> <span id="f' + xhr_object.responseText + '"><a class="com" href="admin_files.php?f=' + xhr_object.responseText + '">' + name + '</a></span></span><div style="padding-top:5px;"><span id="fhref' + xhr_object.responseText + '"><span id="fihref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="" style="vertical-align:middle;" /></a></span></a></span> <a href="admin_files.php?delf=' + xhr_object.responseText + '&amp;f=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>" onClick="javascript:return Confirm_folder();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="" style="vertical-align:middle" /></a> <div style="position:relative;z-index:100;margin-top:95px;margin-left:-70px;float:left;display:none;" id="move' + xhr_object.responseText + '"><div class="bbcode_block" style="width:150px;" onmouseover="upload_hide_block(' + xhr_object.responseText + ', 1);" onmouseout="upload_hide_block(' + xhr_object.responseText + ', 0);"><div style="margin-bottom:4px;"><strong><?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?></strong>:</div><select name="move" onchange="if( this.options[this.selectedIndex].value != -1) document.location = \'admin_files.php?movef=' + xhr_object.responseText + '&amp;to=\' + this.options[this.selectedIndex].value"><?php echo isset($this->_var['MOVE_LIST']) ? $this->_var['MOVE_LIST'] : ''; ?></select><br /><br /></div></div><a href="javascript:upload_display_block(' + xhr_object.responseText + ');" onmouseover="upload_hide_block(' + xhr_object.responseText + ', 1);" onmouseout="upload_hide_block(' + xhr_object.responseText + ', 0);" class="bbcode_hover" title=""><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/move.png" alt="" style="vertical-align:middle;" /></a></div></td></tr></table>';
							var total_folder = document.getElementById('total_folder').innerHTML;
							total_folder++;						
							document.getElementById('total_folder').innerHTML = total_folder;
							
							empty_folder++;
						}
						else
						{	
							alert("<?php echo isset($this->_var['L_FOLDER_ALREADY_EXIST']) ? $this->_var['L_FOLDER_ALREADY_EXIST'] : ''; ?>");
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
			
			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" style="vertical-align:middle;" />';
			
			if( name != '' && regex.test(name) ) //interdiction des caractères spéciaux dans la nom.
			{
				alert("<?php echo isset($this->_var['L_FOLDER_FORBIDDEN_CHARS']) ? $this->_var['L_FOLDER_FORBIDDEN_CHARS'] : ''; ?>");	
				document.getElementById('fi1' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
				document.getElementById('img' + id_file).innerHTML = '';
			}
			else if( name != '' )
			{
				data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name + "&user_id=" + <?php echo isset($this->_var['USER_ID']) ? $this->_var['USER_ID'] : ''; ?>;
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' ) 
					{	
						if( xhr_object.responseText == '/' )
						{
							alert("<?php echo isset($this->_var['L_FOLDER_ALREADY_EXIST']) ? $this->_var['L_FOLDER_ALREADY_EXIST'] : ''; ?>");	
							document.getElementById('fi1' + id_file).style.display = 'inline';
							document.getElementById('fi' + id_file).style.display = 'none';
						}
						else
						{
							document.getElementById('fi' + id_file).style.display = 'none';
							document.getElementById('fi1' + id_file).style.display = 'inline';
							document.getElementById('fi1' + id_file).innerHTML = xhr_object.responseText;
							document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="" style="vertical-align:middle" /></a>';
						}
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';	
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="" style="vertical-align:middle" /></a>';
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
				<li class="title_menu"><?php echo isset($this->_var['L_FILES_MANAGEMENT']) ? $this->_var['L_FILES_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_files.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files.php" class="quick_link"><?php echo isset($this->_var['L_FILES_MANAGEMENT']) ? $this->_var['L_FILES_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_files_config.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/files.png" alt="" /></a>
					<br />
					<a href="admin_files_config.php" class="quick_link"><?php echo isset($this->_var['L_CONFIG_FILES']) ? $this->_var['L_CONFIG_FILES'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<table class="module_table">
				<tr> 
					<th>
						<?php echo isset($this->_var['L_FILES_ACTION']) ? $this->_var['L_FILES_ACTION'] : ''; ?>
					</th>
				</tr>							
				<tr> 
					<td class="row2">
						<span style="float:left;">
							<a href="admin_files.php?root=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/home.png" style="vertical-align:middle;" alt="" /></a>
							<a href="admin_files.php?root=1"><?php echo isset($this->_var['L_ROOT']) ? $this->_var['L_ROOT'] : ''; ?></a>
							<br />					
							<a href="admin_files.php?fup=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_up.png" style="vertical-align:middle;" alt="" /></a>
							<a href="admin_files.php?fup=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>"><?php echo isset($this->_var['L_FOLDER_UP']) ? $this->_var['L_FOLDER_UP'] : ''; ?></a>
						</span>
						<span style="float:right;">
							<span id="new_folder_link">
								<a href="javascript:display_new_folder();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_new.png" style="vertical-align:middle;" alt="" /></a>
								<a href="javascript:display_new_folder();"><?php echo isset($this->_var['L_FOLDER_NEW']) ? $this->_var['L_FOLDER_NEW'] : ''; ?></a>
							</span>
							<br />
							<a href="#new_file"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/files_add.png" style="vertical-align:middle;" alt="" /></a>
							<a href="#new_file"><?php echo isset($this->_var['L_ADD_FILES']) ? $this->_var['L_ADD_FILES'] : ''; ?></a>		
							<br />
						</span>
					</td>
				</tr>							
				<tr> 
					<td class="row2" style="margin:0px;padding:0px">
						<div style="float:left;padding:2px;padding-left:8px;">
							<?php echo isset($this->_var['L_URL']) ? $this->_var['L_URL'] : ''; ?>
						</div>
						<div style="float:right;width:90%;padding:2px;background:#f3f3ee;padding-left:6px;color:black;border:1px solid #7f9db9;">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/folder_mini.png" style="vertical-align:middle;" alt="" /> <a href="admin_files.php"><?php echo isset($this->_var['L_ROOT']) ? $this->_var['L_ROOT'] : ''; ?></a><?php echo isset($this->_var['URL']) ? $this->_var['URL'] : ''; ?>
							</div>
						</div>
					</td>
				</tr>	
				
				<tr>	
					<td class="row2" style="padding:10px 2px;">
						<?php if( !isset($this->_block['empty_folder']) || !is_array($this->_block['empty_folder']) ) $this->_block['empty_folder'] = array();
foreach($this->_block['empty_folder'] as $empty_folder_key => $empty_folder_value) {
$_tmpb_empty_folder = &$this->_block['empty_folder'][$empty_folder_key]; ?>
							<p style="text-align:center;" id="empty_folder">					
								<?php echo isset($_tmpb_empty_folder['EMPTY_FOLDER']) ? $_tmpb_empty_folder['EMPTY_FOLDER'] : ''; ?>					
							</p>
						<?php } ?>
						
						<?php if( !isset($this->_block['folder']) || !is_array($this->_block['folder']) ) $this->_block['folder'] = array();
foreach($this->_block['folder'] as $folder_key => $folder_value) {
$_tmpb_folder = &$this->_block['folder'][$folder_key]; ?>		
						<div style="width:210px;height:90px;float:left;margin-top:5px;">
							<table style="border:0;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<a href="admin_files.php<?php echo isset($_tmpb_folder['U_FOLDER']) ? $_tmpb_folder['U_FOLDER'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/<?php echo isset($_tmpb_folder['IMG_FOLDER']) ? $_tmpb_folder['IMG_FOLDER'] : ''; ?>" alt="" /></a>
									</td>
									<td style="padding-top:8px;">						
										<span id="f<?php echo isset($_tmpb_folder['ID']) ? $_tmpb_folder['ID'] : ''; ?>"><a href="admin_files.php<?php echo isset($_tmpb_folder['U_FOLDER']) ? $_tmpb_folder['U_FOLDER'] : ''; ?>" class="com"><?php echo isset($_tmpb_folder['NAME']) ? $_tmpb_folder['NAME'] : ''; ?></a></span>
										<div style="padding-top:5px;">
											<?php echo isset($_tmpb_folder['RENAME_FOLDER']) ? $_tmpb_folder['RENAME_FOLDER'] : ''; ?>	<a href="admin_files.php?<?php echo isset($_tmpb_folder['DEL_TYPE']) ? $_tmpb_folder['DEL_TYPE'] : ''; ?>=<?php echo isset($_tmpb_folder['ID']) ? $_tmpb_folder['ID'] : ''; ?>&amp;f=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>" onClick="javascript:return Confirm_<?php echo isset($_tmpb_folder['ALERT_DEL']) ? $_tmpb_folder['ALERT_DEL'] : ''; ?>();" title="<?php echo isset($_tmpb_folder['L_TYPE_DEL_FOLDER']) ? $_tmpb_folder['L_TYPE_DEL_FOLDER'] : ''; ?>"><?php echo isset($_tmpb_folder['DEL_TYPE_IMG']) ? $_tmpb_folder['DEL_TYPE_IMG'] : ''; ?></a>
											
											<a style="display:none" href="admin_files<?php echo isset($_tmpb_folder['U_MOVE']) ? $_tmpb_folder['U_MOVE'] : ''; ?>" title="<?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/move.png" alt="" style="vertical-align:middle;" /></a>
											<span id="img<?php echo isset($_tmpb_folder['ID']) ? $_tmpb_folder['ID'] : ''; ?>"></span>
										</div>
									</td>
								</tr>						
							</table>
						</div>
						<?php } ?>
						
						<span id="new_folder"></span>
						
						<?php if( !isset($this->_block['files']) || !is_array($this->_block['files']) ) $this->_block['files'] = array();
foreach($this->_block['files'] as $files_key => $files_value) {
$_tmpb_files = &$this->_block['files'][$files_key]; ?>
						<div style="width:210px;height:90px;float:left;margin-top:5px;">
							<table style="border:0;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<?php echo isset($_tmpb_files['IMG']) ? $_tmpb_files['IMG'] : ''; ?>
									</td>
									<td style="padding-top:8px;">	
										<?php echo isset($_tmpb_files['URL']) ? $_tmpb_files['URL'] : ''; ?><span id="fi1<?php echo isset($_tmpb_files['ID']) ? $_tmpb_files['ID'] : ''; ?>"><?php echo isset($_tmpb_files['NAME']) ? $_tmpb_files['NAME'] : ''; ?></span></a><span id="fi<?php echo isset($_tmpb_files['ID']) ? $_tmpb_files['ID'] : ''; ?>"></span><br />
										<?php echo isset($_tmpb_files['BBCODE']) ? $_tmpb_files['BBCODE'] : ''; ?><br />							
										<span class="text_small"><?php echo isset($_tmpb_files['FILETYPE']) ? $_tmpb_files['FILETYPE'] : ''; ?></span><br />
										<span class="text_small"><?php echo isset($_tmpb_files['SIZE']) ? $_tmpb_files['SIZE'] : ''; ?></span><br />
										<?php echo isset($_tmpb_files['RENAME_FILE']) ? $_tmpb_files['RENAME_FILE'] : ''; ?>
										<a href="admin_files.php?del=<?php echo isset($_tmpb_files['ID']) ? $_tmpb_files['ID'] : ''; ?>&amp;f=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>" onClick="javascript:return Confirm_file();" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="" style="vertical-align:middle;" /></a> 
										
										<a style="display:none" href="admin_files<?php echo isset($_tmpb_files['U_MOVE']) ? $_tmpb_files['U_MOVE'] : ''; ?>" title="<?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/move.png" alt="" style="vertical-align:middle;" /></a>						
										
										<?php echo isset($_tmpb_files['INSERT']) ? $_tmpb_files['INSERT'] : ''; ?>
										<span id="img<?php echo isset($_tmpb_files['ID']) ? $_tmpb_files['ID'] : ''; ?>"></span>
									</td>
								</tr>
							</table>
						</div>	
						<?php } ?>				
						
					</td>
				</tr>
				
				<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
				<tr>
					<td class="row3">	
						<span id="errorh"></span>
						<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
							<br />	
						</div>
						<br />	
					</td>	
				</tr>
				<?php } ?>
				<tr>				
					<td class="row3" id="new_file">							
						<form action="admin_files.php?f=<?php echo isset($this->_var['FOLDER_ID']) ? $this->_var['FOLDER_ID'] : ''; ?>" enctype="multipart/form-data" method="post">
							<span style="float:left">						
								<strong><?php echo isset($this->_var['L_ADD_FILES']) ? $this->_var['L_ADD_FILES'] : ''; ?></strong>
								<br />
									<input type="file" name="upload_file" size="30" class="submit" />					
									<input type="hidden" name="max_file_size" value="2000000" />
									<br />
									<input type="submit" name="valid_up" value="<?php echo isset($this->_var['L_UPLOAD']) ? $this->_var['L_UPLOAD'] : ''; ?>" class="submit" />							
							</span>	
							<span style="float:right;text-align:right">
								<?php echo isset($this->_var['L_FOLDERS']) ? $this->_var['L_FOLDERS'] : ''; ?>: <strong><span id="total_folder"><?php echo isset($this->_var['TOTAL_FOLDERS']) ? $this->_var['TOTAL_FOLDERS'] : ''; ?></span></strong><br />
								<?php echo isset($this->_var['L_FILES']) ? $this->_var['L_FILES'] : ''; ?>: <strong><?php echo isset($this->_var['TOTAL_FILES']) ? $this->_var['TOTAL_FILES'] : ''; ?></strong><br />
								<?php echo isset($this->_var['L_FOLDER_SIZE']) ? $this->_var['L_FOLDER_SIZE'] : ''; ?>: <strong><?php echo isset($this->_var['TOTAL_FOLDER_SIZE']) ? $this->_var['TOTAL_FOLDER_SIZE'] : ''; ?></strong><br />
								<?php echo isset($this->_var['L_DATA']) ? $this->_var['L_DATA'] : ''; ?>: <strong><?php echo isset($this->_var['TOTAL_SIZE']) ? $this->_var['TOTAL_SIZE'] : ''; ?></strong>
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
		