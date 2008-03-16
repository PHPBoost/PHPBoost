		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_ENTRY}");
		}
		function check_select_multiple(id, status)
		{
			var i;
			
			for(i = -1; i <= 2; i++)
			{
				if( document.getElementById(id + 'r' + i) )
					document.getElementById(id + 'r' + i).selected = status;
			}				
			document.getElementById(id + 'r3').selected = true;
			
			for(i = 0; i < {NBR_GROUP}; i++)
			{	
				if( document.getElementById(id + 'g' + i) )
					document.getElementById(id + 'g' + i).selected = status;		
			}
		}
		function check_select_multiple_ranks(id, start)
		{
			var i;				
			for(i = start; i <= 3; i++)
			{	
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = true;			
			}
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
			<form action="admin_wiki_groups.php" method="post">
				<table class="module_table">
					<tr>			
						<th colspan="2">
							{L_WIKI_GROUPS}
						</th>
					</tr>	
					<tr>			
						<td colspan="2" class="row3" style="text-align:center;">
							{EXPLAIN_WIKI_GROUPS}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:200px;">
							{L_CREATE_ARTICLE}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_CREATE_ARTICLE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_CREATE_CAT}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_CREATE_CAT}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_RESTORE_ARCHIVE}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_RESTORE_ARCHIVE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_DELETE_ARCHIVE}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_DELETE_ARCHIVE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_EDIT}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_EDIT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_DELETE}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_DELETE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RENAME}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_RENAME}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_REDIRECT}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_REDIRECT}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_MOVE}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_MOVE}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_STATUS}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_STATUS}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_COM}
						</td>
						<td class="row2" style="text-align:center;">
							{SELECT_COM}
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RESTRICTION}
						</td>
						<td class="row2" style="text-align:center;">
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