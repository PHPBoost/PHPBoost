<li id="menu_element_{ID}" class="menu_link_element">
	<div class="menu_link_element_title"> 
		<div class="menu_link_element_admin">
			<a href="{U_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" class="valign_middle"></a>
			<a href="{U_DELETE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle"></a>
		</div>
		<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="Drag&Drop" class="valign_middle drag" >
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="Url" class="valign_middle"> 
		{NAME}
	</div>
	<div class="spacer"></div>
	
	<ul id="menu_element_{ID}_list" class="menu_link_list" style="position: relative;">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>