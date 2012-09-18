		<link href="{PICTURES_DATA_PATH}/gallery.css" rel="stylesheet" type="text/css" media="screen, handheld">
		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_ENTRY}");
		}
		
		var list_cats = new Array({LIST_CATS});
		var array_cats = new Array();
		{ARRAY_JS}
			
		function XMLHttpRequest_get_parent(divid, direction)
		{
			document.getElementById('l' + divid).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			var xhr_object = xmlhttprequest_init('admin_xmlhttprequest.php?token={TOKEN}&g_' + direction + '=' + divid + '&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					XMLHttpRequest_gallery_cats_move(xhr_object.responseText, divid, direction);
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('l' + divid).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		
		function XMLHttpRequest_gallery_cats_move(change_cat, divid, direction)
		{
			var xhr_object = xmlhttprequest_init('admin_xmlhttprequest.php?token={TOKEN}&id=' + divid + '&move=' + direction + '&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{	
					gallery_cats_move(change_cat, divid, direction);
					eval(xhr_object.responseText);
					document.getElementById('l' + divid).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('l' + divid).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		
		function gallery_cats_move(change_cat, divid, direction)
		{		 
			var i;
			var id_left = array_cats[divid]['id_left'];
			var id_right = array_cats[divid]['id_right'];
			var id = array_cats[divid]['i'];
			var tmp_list_cats = list_cats;		
			var id_parent;
			var pos_parent;
			var parents_end = 0;
			var check_change_cat = false;
			var tmp;

			if( change_cat.substring(0, 1) == 's' )
			{	
				change_cat = change_cat.substring(1, change_cat.length);
				check_change_cat = true;
			}
			id_parent = parseInt(change_cat);
			pos_parent = array_cats[id_parent]['i'];
			parents_end = pos_parent + ((array_cats[id_parent]['id_right'] - array_cats[id_parent]['id_left']) - 1)/2;
				
			if( direction == 'up' )
			{
				if( !check_change_cat ) 
				{
					var parent_cats = new Array();
					var parent_cats_id = new Array();
					for(i = pos_parent; i <= parents_end; i++)
					{
						parent_cats.push(document.getElementById('c' + i).innerHTML);
						parent_cats_id.push(list_cats[i]);
					}
					var child_cats = new Array();
					var child_cats_id = new Array();
					var child_end = id + (((id_right - id_left) - 1)/2);
					for(i = id; i <= child_end; i++)
					{	
						child_cats.push(document.getElementById('c' + i).innerHTML);
						child_cats_id.push(list_cats[i]);
					}
					var z = 0;
					var child_length = pos_parent + child_cats.length;
					for(i = pos_parent; i < child_length; i++)
					{
						document.getElementById('c' + i).innerHTML = child_cats[z];
						tmp_list_cats[i] = child_cats_id[z];
						z++;
					}
					z = 0;
					var parent_length = pos_parent + child_cats.length + parent_cats.length;
					for(i = pos_parent + child_cats.length; i < parent_length; i++)
					{				
						document.getElementById('c' + i).innerHTML = parent_cats[z];
						tmp_list_cats[i] = parent_cats_id[z];
						z++;
					}
				}
				else
				{
					var parent_cats = new Array();
					var child_cats = new Array();
					var parent_end;
					var nbr_child = (((id_right - id_left) - 1)/2);
					var child_end = id + nbr_child;
					
					for(i = pos_parent; i < id; i++)
						parent_cats.push(document.getElementById('c' + i).innerHTML);
					
					for(i = id; i <= child_end; i++)
						child_cats.push(document.getElementById('c' + i).innerHTML);
					
					var z = 0;
					child_end = pos_parent + child_cats.length;
					for(i = pos_parent; i < child_end; i++)
					{	
						document.getElementById('c' + i).innerHTML = child_cats[z];
						z++;
					}
					
					parent_end = pos_parent + child_cats.length + parent_cats.length;
					var z = 0;
					for(i = pos_parent + child_cats.length; i < parent_end; i++)
					{	
						document.getElementById('c' + i).innerHTML = parent_cats[z];
						z++;
					}
				}
			}
			else
			{
				if( !check_change_cat ) 
				{
					var parent_cats = new Array();
					var parent_cats_id = new Array();
					for(i = pos_parent; i <= parents_end; i++)
					{	
						parent_cats.push(document.getElementById('c' + i).innerHTML);
						parent_cats_id.push(list_cats[i]);
					}
					var child_cats = new Array();
					var child_cats_id = new Array();
					var child_end = id + ((id_right - id_left) - 1)/2;
					for(i = id; i <= child_end; i++)
					{	
						child_cats.push(document.getElementById('c' + i).innerHTML);
						child_cats_id.push(list_cats[i]);
					}
					
					var z = 0;
					var child_length = id + parent_cats.length + child_cats.length;
					for(i = id + parent_cats.length; i < child_length; i++)
					{
						document.getElementById('c' + i).innerHTML = child_cats[z];
						tmp_list_cats[i] = child_cats_id[z];
						z++;
					}
					z = 0;
					var parent_length = id + parent_cats.length;
					for(i = id; i < parent_length; i++)
					{				
						document.getElementById('c' + i).innerHTML = parent_cats[z];
						tmp_list_cats[i] = parent_cats_id[z];
						z++;
					}
				}
				else
				{
					var parent_cats = new Array();
					var child_cats = new Array();
					var parent_end;
					var nbr_child = (((id_right - id_left) - 1)/2);
					
					for(i = id + nbr_child + 1; i <= pos_parent; i++)
						parent_cats.push(document.getElementById('c' + i).innerHTML);
						
					var child_end = id + nbr_child;
					for(i = id; i <= child_end; i++)
						child_cats.push(document.getElementById('c' + i).innerHTML);
						
					var z = 0;
					child_end = id + child_cats.length + parent_cats.length;
					for(i = id + parent_cats.length; i < child_end; i++)
					{	
						document.getElementById('c' + i).innerHTML = child_cats[z];
						z++;
					}

					z = 0;
					parent_end = id + parent_cats.length;
					for(i = id; i < parent_end; i++)
					{	
						document.getElementById('c' + i).innerHTML = parent_cats[z];
						z++;
					}
				}
			}
		}
		function Confirm() {
			return confirm("{L_CONFIRM_DEL}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GALLERY_MANAGEMENT}</li>
				<li>
					<a href="admin_gallery.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery.php" class="quick_link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_add.php" class="quick_link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_cat.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat.php" class="quick_link">{L_GALLERY_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_cat_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat_add.php" class="quick_link">{L_GALLERY_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_config.php" class="quick_link">{L_GALLERY_CONFIG}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<table class="module_table" style="width:99%;">
				<tr>			
					<th colspan="3">
						{L_GALLERY_CAT_MANAGEMENT}
					</th>
				</tr>
				<tr> 
					<td class="row1" colspan="2" style="text-align:center;">
						# INCLUDE message_helper #		
					</td>
				</tr>
				<tr>
					<td style="padding-left:20px;" class="row2">
						<span id="display"></span>
						<br />
						<div style="width:80%;margin:auto;">						
							<div>
								<div class="row3 gallery_cat_admin">
									<span style="float:left;">
										&nbsp;&nbsp;<img src="{PICTURES_DATA_PATH}/images/folder.png" alt="" style="vertical-align:middle" /> &nbsp;<a href="gallery.php" class="gallery_link_cat">{L_ROOT}</a>
									</span>
									<span style="float:right;">
										<a href="admin_gallery_cat.php?root=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>&nbsp;&nbsp;
									</span>&nbsp;
								</div>	
							</div>
							
							# START list #	
							<span id="c{list.I}">
								<div style="margin-left:{list.INDENT}px;">
									<div class="row3 gallery_cat_admin">
										<span style="float:left;">
											&nbsp;&nbsp;<img src="{PICTURES_DATA_PATH}/images/folder.png" alt="" style="vertical-align:middle" /> {list.LOCK} &nbsp;<a href="gallery{list.U_GALLERY_VARS}" class="gallery_link_cat">{list.NAME}</a> &nbsp;<span class="text_small">{list.DESC}</span>
										</span>
										<span style="float:right;">
											<span id="l{list.ID}"></span> 
											<script type="text/javascript">
											<!--
											document.write('<a href="javascript:XMLHttpRequest_get_parent(\'{list.ID}\', \'up\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>');
											-->
											</script>
											<noscript><a href="admin_gallery_cat.php?id={list.ID}&amp;move=up&amp;token={TOKEN}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a></noscript>
											
											<script type="text/javascript">
											<!--
											document.write('<a href="javascript:XMLHttpRequest_get_parent(\'{list.ID}\', \'down\');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>');
											-->
											</script>										
											<noscript><a href="admin_gallery_cat.php?id={list.ID}&amp;move=down&amp;token={TOKEN}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a></noscript>
											
											<a href="admin_gallery_cat.php?id={list.ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a> <a href="admin_gallery_cat.php?del={list.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a>&nbsp;&nbsp;
										</span>&nbsp;
									</div>	
								</div>
							</span>					
							# END list #
							<br />
						</div>
					</td>
				</tr>
			</table>
		</div>	
		