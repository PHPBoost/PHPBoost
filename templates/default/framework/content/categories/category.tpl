<li id="cat_{ID}" class="sortable-element">
	<div class="sortable-title"> 
		<i title="${LangLoader::get_message('move', 'admin')}" class="icon-arrows"></i>
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/url.png" alt="Url" class="valign_middle"> 
		{NAME}
		# IF C_DESCRIPTION #<span class="cat_desc"> | {DESCRIPTION}</span># ENDIF #
		<div class="sortable-actions">
			<div class="sortable-options">
				<a href="{U_EDIT}" title="{L_EDIT}" class="icon-edit"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_DELETE}" title="{L_DELETE}" class="icon-delete" data-confirmation="delete-element"></a>
			</div>
		</div>
	</div>
	<div class="spacer"></div>
	
	<ul id="subcat_{ID}" class="sortable-block">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>