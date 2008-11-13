<div id="admin_quick_menu">
	<ul>
		<li class="title_menu">{L_MENUS_MANAGEMENT}</li>
		<li>
		    <a href="admin_menus.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="{L_MENUS_MANAGEMENT}" /></a><br />
		    <a href="admin_menus.php" class="quick_link">{L_MENUS_MANAGEMENT}</a>
	    </li>
		<li>
		    <a href="admin_menus_add.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="{L_ADD_CONTENT_MENUS}" /></a><br />
		    <a href="admin_menus_add.php" class="quick_link">{L_ADD_CONTENT_MENUS}</a>
		</li>
		<li>
		    <a href="admin_link_menus.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="{L_ADD_LINKS_MENUS}" /></a><br />
		    <a href="admin_link_menus.php" class="quick_link">{L_ADD_LINKS_MENUS}</a>
		</li>
	</ul>
</div>

<div id="admin_contents">
# IF C_ADD_MENU_LINKS #
<dl>
	<dt><label for="activ">{L_TYPE}</label></dt>
	<dd><label> <select name="activ" id="activ">
		<option value="vertical" selected="selected">{L_VERTICAL_MENU}</option>
		<option value="horizontal" selected="selected">{L_HORIZONTAL_MENU}</option>
		<option value="tree" selected="selected">{L_TREE_MENU}</option>
		<option value="vertical_scroll" selected="selected">{L_VERTICAL_SCROLL_MENU}</option>
		<option value="horizontal_scroll" selected="selected">{L_HORIZONTAL_SCROLL_MENU}</option>
	</select> </label></dd>
</dl>
# ENDIF #
</div>