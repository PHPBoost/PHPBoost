<li id="cat_{ID}" class="cat_element">
	<div class="cat_title"> 
		<div class="cat_action_admin">
			<a href="{U_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" class="valign_middle"></a>
			<a href="{U_DELETE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle"></a>
		</div>
		<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="Drag&Drop" class="valign_middle drag" >
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="Url" class="valign_middle"> 
		{NAME}
		<span class="cat_desc"> | description de la catégorie</span>
	</div>
	<div class="spacer"></div>
	
	<ul id="subcat_{ID}" class="cat_list" style="position: relative;">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>