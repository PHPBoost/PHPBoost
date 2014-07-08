# START categories #
	<li id="c{categories.ID}" class="sortable-element" style="cursor:default;margin-left:{categories.MARGIN_LEFT}px;">
		<div class="sortable-title">
			<i class="fa fa-folder"></i>
			# IF categories.C_DISPLAY_URL #
				<a href="{categories.URL}">{categories.NAME}</a>
			# ELSE #
				{categories.NAME}
			# ENDIF #
			
			<div class="sortable-actions">
				<span id="l{categories.ID}"></span>
				# IF categories.C_NOT_FIRST_CAT #
					<div class="sortable-options">
						<a href="{categories.ACTION_GO_UP}" title="${LangLoader::get_message('category.move_up', 'categories-common')}" class="fa fa-arrow-up"></a>
					</div>
					# IF C_AJAX_MODE #
						<script>
						<!--document.getElementById("up_{categories.ID}").href = "javascript:ajax_move_cat({categories.ID}, 'up');";-->
						</script>
					# ENDIF #
				# ENDIF #
				
				# IF categories.C_NOT_LAST_CAT #
					<div class="sortable-options">
						<a href="{categories.ACTION_GO_DOWN}" title="${LangLoader::get_message('category.move_down', 'categories-common')}" class="fa fa-arrow-down"></a>
					</div>
					# IF C_AJAX_MODE #
						<script>
							<!--document.getElementById("down_{categories.ID}").href = "javascript:ajax_move_cat({categories.ID}, 'down');";-->
						</script>
					# ENDIF #
				# ENDIF #
				
				# IF categories.C_VISIBLE #
					<div class="sortable-options">
						<a href="{categories.ACTION_HIDE}" title="{L_MANAGEMENT_HIDE_CAT}" id="visibility_{categories.ID}" class="fa fa-eye"></a>
					</div>
					# IF C_AJAX_MODE #
						<script>
							<!--document.getElementById("visibility_{categories.ID}").href = "javascript:ajax_change_cat_visibility({categories.ID}, 'hide');";-->
						</script>
					# ENDIF #
				# ELSE #
					<div class="sortable-options">
						<a href="{categories.ACTION_SHOW}" title="{L_MANAGEMENT_SHOW_CAT}" id="visibility_{categories.ID}" class="fa fa-eye-slash"></a>
					</div>
					# IF C_AJAX_MODE #
						<script>
							<!--document.getElementById("visibility_{categories.ID}").href = "javascript:ajax_change_cat_visibility({categories.ID}, 'show');";-->
						</script>
					# ENDIF #
				# ENDIF #
				
				<div class="sortable-options">
					<a href="{categories.ACTION_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
				</div>
				<div class="sortable-options">
					<a href="{categories.ACTION_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</div>
			</div>
		</div>
	</li>
	{categories.NEXT_CATEGORY}
# END categories #