		<link href="{PICTURES_DATA_PATH}/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
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
			document.getElementById('l' + divid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
			
			var xhr_object = xmlhttprequest_init('admin_xmlhttprequest.php?token={TOKEN}&g_' + direction + '=' + divid + '&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					XMLHttpRequest_forum_cat_move(xhr_object.responseText, divid, direction);
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('l' + divid).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		
		function XMLHttpRequest_forum_cat_move(change_cat, divid, direction)
		{
			var xhr_object = xmlhttprequest_init('admin_xmlhttprequest.php?token={TOKEN}&id=' + divid + '&move=' + direction + '&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{	
					forum_cat_move(change_cat, divid, direction);
					eval(xhr_object.responseText);
					document.getElementById('l' + divid).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('l' + divid).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		
		function forum_cat_move(change_cat, divid, direction)
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
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FORUM_MANAGEMENT}</li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick_link">{L_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_forum_config.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_config.php" class="quick_link">{L_FORUM_CONFIG}</a>
				</li>
				<li>
					<a href="admin_forum_groups.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_groups.php" class="quick_link">{L_FORUM_GROUPS}</a>
				</li>
				<li>
					<a href="admin_ranks.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks.php" class="quick_link">{L_FORUM_RANKS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_ranks_add.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ranks.png" alt="" /></a>
					<br />
					<a href="admin_ranks_add.php" class="quick_link">{L_FORUM_ADD_RANKS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			
			<fieldset>
				<legend>{L_CAT_MANAGEMENT}</legend>
				<style> .sortable-block .sortable-options { width:22px; } </style>
				<ul id="categories forum-administration" class="sortable-block" style="position:relative;" >
					# START list #	
					<li id="c{list.I}" class="sortable-element" style="cursor:default;margin-left:{list.INDENT}px;">
						<div class="sortable-title" >
							<i class="fa fa-folder"></i> 
							{list.LOCK} 
							{list.URL}
							<a href="{list.U_FORUM_VARS}" class="forum_link_cat">{list.NAME}</a>
							<div class="sortable-actions">
								<span id="l{list.ID}"></span> 
								<div class="sortable-options">
									<a href="javascript:XMLHttpRequest_get_parent('{list.ID}', 'up');" class="fa fa-arrow-up"></a>
								</div>
								<div class="sortable-options">
									<a href="javascript:XMLHttpRequest_get_parent('{list.ID}', 'down');" class="fa fa-arrow-down"></a>
								</div>
								<div class="sortable-options">
									<a href="admin_forum.php?id={list.ID}" title="{L_EDIT_CAT}" class="fa fa-edit"></a>
								</div>
								<div class="sortable-options">
									<a href="admin_forum.php?del={list.ID}&amp;token={TOKEN}" class="fa fa-delete" data-confirmation="delete-element"></a>
								</div>
							</div>
						</div>
					</li>					
					# END list #
				</ul>
			</fieldset>

		</div>