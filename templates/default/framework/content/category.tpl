# START categories #
	<span id="c{categories.ID}">
		<div style="margin-left:{categories.MARGIN_LEFT}px;">
			<div class="row3 management_cat_admin">
				<span style="float:left;">
					&nbsp;&nbsp;<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/folder.png" alt="" style="vertical-align:middle" />
					&nbsp;
					
					# IF categories.C_DISPLAY_URL #
						<a href="{categories.URL}">{categories.NAME}</a>
					# ELSE #
						{categories.NAME}			
					# ENDIF #
				</span>
				<span style="float:right;">
					<span id="l{categories.ID}"></span>
					# IF categories.C_NOT_FIRST_CAT #
						<a href="{categories.ACTION_GO_UP}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" />
						</a>
						# IF C_AJAX_MODE #
							<script type="text/javascript">
								<!--
									document.getElementById("up_{categories.ID}").href = "javascript:ajax_move_cat({categories.ID}, 'up');";
								-->
							</script>
						# ENDIF #
					# ENDIF #
					# IF categories.C_NOT_LAST_CAT #
						<a href="{categories.ACTION_GO_DOWN}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" />
						</a>
						# IF C_AJAX_MODE #
							<script type="text/javascript">
								<!--
									document.getElementById("down_{categories.ID}").href = "javascript:ajax_move_cat({categories.ID}, 'down');";
								-->
							</script>
						# ENDIF #
					# ENDIF #
					# IF categories.C_VISIBLE #
						<a href="{categories.ACTION_HIDE}" title="{L_MANAGEMENT_HIDE_CAT}" id="visibility_{categories.ID}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/visible.png" alt="{L_MANAGEMENT_HIDE_CAT}" class="valign_middle" />
						</a>&nbsp;
						# IF C_AJAX_MODE #
							<script type="text/javascript">
								<!--
									document.getElementById("visibility_{categories.ID}").href = "javascript:ajax_change_cat_visibility({categories.ID}, 'hide');";
								-->
							</script>
						# ENDIF #
					# ELSE #
						<a href="{categories.ACTION_SHOW}" title="{L_MANAGEMENT_SHOW_CAT}" id="visibility_{categories.ID}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unvisible.png" alt="{L_MANAGEMENT_SHOW_CAT}" class="valign_middle" />
						</a>&nbsp;
						# IF C_AJAX_MODE #
							<script type="text/javascript">
								<!--
									document.getElementById("visibility_{categories.ID}").href = "javascript:ajax_change_cat_visibility({categories.ID}, 'show');";
								-->
							</script>
						# ENDIF #
					# ENDIF #
					
					<a href="{categories.ACTION_EDIT}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" />
					</a>&nbsp;
					
					<a href="{categories.ACTION_DELETE}" title="{L_CONFIRM_DELETE}" onclick="return confirm('{L_CONFIRM_DELETE}');">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" />
					</a>
					&nbsp;&nbsp;
				</span>
				&nbsp;
			</div>	
		</div>
	</span>
	{categories.NEXT_CATEGORY}
# END categories #