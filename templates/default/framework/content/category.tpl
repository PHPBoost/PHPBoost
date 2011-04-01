# START categories #
<div class="menu_link_element" id="list_{categories.ID}">
	<div style="float:left;">
		<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="drag" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move" />
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png" alt="" style="vertical-align:middle" />
		# IF categories.C_DISPLAY_URL #
			<a href="{categories.URL}">{categories.NAME}</a>
		# ELSE #
			<span class="text_strong" >{categories.NAME}</span>
		# ENDIF #
	</div>
	<div style="float:right;">
		<img id="loading_{categories.ID}" alt="" class="valign_middle" />
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" id="change_display_{categories.ID}" class="valign_middle" />
		<a href="{categories.ACTION_EDIT}">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" />
		</a>
		<a href="{categories.ACTION_DELETE}">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" id="delete_{categories.ID}" class="valign_middle" />
		</a>
	</div>
	<div class="spacer"></div>
	<div id="children_{categories.ID}">
		{categories.NEXT_CATEGORY}
	</div>
</div>
# IF C_AJAX_MODE #
<script type="text/javascript">
<!--
	Event.observe(window, 'load', function() {
		var categorie = new Categorie({categories.ID}, '{categories.DISPLAY}', Categories);
		
		$('delete_{categories.ID}').observe('click',function(){
			categorie.delete_fields();
		});
		
		$('change_display_{categories.ID}').observe('click',function(){
			categorie.change_display();
		});
	});
-->
</script>
# ENDIF #
# END categories #