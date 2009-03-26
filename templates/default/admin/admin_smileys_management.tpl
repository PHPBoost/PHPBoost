		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_SMILEY}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADD_SMILEY}</li>
				<li>
					<a href="admin_smileys.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys.php" class="quick_link">{L_SMILEY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_smileys_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/smileys.png" alt="" /></a>
					<br />
					<a href="admin_smileys_add.php" class="quick_link">{L_ADD_SMILEY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_SMILEY_MANAGEMENT}
					</th>
				</tr>
				<tr style="text-align:center;width: 25%"> 
					<td class="row1">
						{L_SMILEY}
					</td>
					<td class="row1" style="width: 25%">
						{L_CODE}
					</td>
					<td class="row1" style="width: 25%">
						{L_UPDATE}
					</td>
					<td class="row1" style="width: 25%">
						{L_DELETE}
					</td>
				</tr>
				 
				# START list #
				
				<tr style="text-align:center;"> 
					<td class="row2">
						<img src="{PATH_TO_ROOT}/images/smileys/{list.URL_SMILEY}" alt="" />
					</td>
					<td class="row2">
						{list.CODE_SMILEY}
					</td>
					<td class="row2">
						<a href="admin_smileys.php?id={list.IDSMILEY}&amp;edit=1" title="{L_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
					</td>
					<td class="row2">
						<a href="admin_smileys.php?id={list.IDSMILEY}&amp;del=1&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" /></a>
					</td>
				</tr>
				
				# END list #

			</table>
		</div>
		