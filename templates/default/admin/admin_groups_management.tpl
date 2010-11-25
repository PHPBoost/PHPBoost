		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_GROUP}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GROUPS_MANAGEMENT}</li>
				<li>
					<a href="admin_groups.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link">{L_GROUPS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link">{L_ADD_GROUPS}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<fieldset class="fieldset_content">
				<legend>{L_GROUPS_MANAGEMENT}</legend>
			
				<table class="module_table">
					<tr style="text-align:center;">
						<th>
							{L_NAME}
						</th>
						<th>
							{L_IMAGE}
						</th>
						<th>
							{L_UPDATE}
						</th>
						<th>
							{L_DELETE}
						</th>
					</tr>
					
					# START group #
					<tr style="text-align:center;"> 
						<td class="row2">
							<a href="{PATH_TO_ROOT}/member/member{group.LINK}">{group.NAME}</a>
						</td>
						<td class="row2">
							{group.IMAGE}
						</td>
						<td class="row2"> 
							<a href="admin_groups.php?id={group.ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
						</td>
						<td class="row2">
							<a href="admin_groups.php?del=1&amp;id={group.ID}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
						</td>
					</tr>
					# END group #

					<tr style="text-align:center;">
						<td class="row1" colspan="4">
							<a href="admin_groups.php?add=1" title="{L_ADD_GROUPS}">{L_ADD_GROUPS}</a>
						</td>
					</tr>
				</table>
				&nbsp;
			</fieldset>
			<p style="text-align:center;padding-top:8px;">{PAGINATION}</p>
		</div>
		