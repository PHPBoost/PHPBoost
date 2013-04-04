<li id="menu_element_{ID}" class="menu_link_element">
	<div class="menu_link_element_title"> 
		<div class="menu_link_element_admin">
			<a href="{U_EDIT}"><img src="/trunk/templates/base/images/french/edit.png" alt="Editer" class="valign_middle"></a>
			<a href="{U_DELETE}"><img src="/trunk/templates/base/images/french/delete.png" alt="Supprimer" class="valign_middle"></a>
		</div>
		<img src="/trunk/templates/default/images/drag.png" alt="Drag&Drop" class="valign_middle drag" >
		<img src="/trunk/templates/base/images/form/url.png" alt="Url" class="valign_middle"> 
		{NAME}
	</div>
	<div class="spacer"></div>
	
	<ul id="menu_element_{ID}_list" class="menu_link_list" style="position: relative;">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>