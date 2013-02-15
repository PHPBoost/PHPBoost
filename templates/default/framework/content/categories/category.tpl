<li id="menu_element_{ID}" class="menu_link_element" style="position: relative;">
	{NAME}
	<div style="float:left;">
		<img src="/trunk/templates/default/images/drag.png" alt="plus" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move">
		<img src="/trunk/templates/base/images/form/url.png" alt="plus" class="valign_middle" style="cursor:move"> 
	</div>
	<div style="float:right;">
		<img src="/trunk/templates/base/images/french/edit.png" alt="Plus de détails" class="valign_middle">
		<img src="/trunk/templates/base/images/french/delete.png" alt="Supprimer" class="valign_middle">
	</div>
	<div class="spacer"></div>
	
	<ul id="menu_element_{ID}_list" class="menu_link_list" style="position: relative;">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>