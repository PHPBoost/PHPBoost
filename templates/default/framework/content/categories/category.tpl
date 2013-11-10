<li id="cat_{ID}" class="cat_element">
	<div class="cat_title"> 
		<div class="cat_action_admin">
			<a href="{U_EDIT}" title="{L_EDIT}" class="icon-edit"></a>
			<a href="{U_DELETE}" title="{L_DELETE}" class="icon-delete" data-confirmation="delete-element"></a>
		</div>
		<i title="${LangLoader::get_message('move', 'admin')}" class="icon-arrows" ></i>
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/url.png" alt="Url" class="valign_middle"> 
		{NAME}
		# IF C_DESCRIPTION #<span class="cat_desc"> | {DESCRIPTION}</span># ENDIF #
	</div>
	<div class="spacer"></div>
	
	<ul id="subcat_{ID}" class="cat_list" style="position: relative;">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>