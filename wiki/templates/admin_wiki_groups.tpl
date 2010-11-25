		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_ENTRY}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
				<ul>
					<li class="title_menu">{L_WIKI_MANAGEMENT}</li>
				<li>
					<a href="admin_wiki.php"><img src="wiki.png" alt="" /></a>
					<br />
					<a href="admin_wiki.php" class="quick_link">{L_CONFIG_WIKI}</a>
				</li>
				<li>
					<a href="admin_wiki_groups.php"><img src="wiki.png" alt="" /></a>
					<br />
					<a href="admin_wiki_groups.php" class="quick_link">{L_WIKI_GROUPS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_wiki_groups.php?token={TOKEN}" method="post">
				<table class="module_table">
					<tr>			
						<th colspan="2">
							{L_WIKI_GROUPS}
						</th>
					</tr>	
					<tr>			
						<td colspan="2" class="row3">
							{EXPLAIN_WIKI_GROUPS}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:200px;">
							{L_CREATE_ARTICLE}
						</td>
						<td class="row2">
							{SELECT_CREATE_ARTICLE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_CREATE_CAT}
						</td>
						<td class="row2">
							{SELECT_CREATE_CAT}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_RESTORE_ARCHIVE}
						</td>
						<td class="row2">
							{SELECT_RESTORE_ARCHIVE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_DELETE_ARCHIVE}
						</td>
						<td class="row2">
							{SELECT_DELETE_ARCHIVE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_EDIT}
						</td>
						<td class="row2">
							{SELECT_EDIT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_DELETE}
						</td>
						<td class="row2">
							{SELECT_DELETE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RENAME}
						</td>
						<td class="row2">
							{SELECT_RENAME}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_REDIRECT}
						</td>
						<td class="row2">
							{SELECT_REDIRECT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_MOVE}
						</td>
						<td class="row2">
							{SELECT_MOVE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_STATUS}
						</td>
						<td class="row2">
							{SELECT_STATUS}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_COM}
						</td>
						<td class="row2">
							{SELECT_COM}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RESTRICTION}
						</td>
						<td class="row2">
							{SELECT_RESTRICTION}
						</td>
					</tr>
				</table>
				
				<br /><br />
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>
		</div>