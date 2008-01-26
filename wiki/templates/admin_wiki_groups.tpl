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
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_CREATE_ARTICLE}
							<br />
							<a href="javascript:check_select_multiple('1', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('1', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_CREATE_CAT}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_CREATE_CAT}
							<br />
							<a href="javascript:check_select_multiple('2', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('2', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_RESTORE_ARCHIVE}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_RESTORE_ARCHIVE}
							<br />
							<a href="javascript:check_select_multiple('3', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('3', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1" style="width:250px;">
							{L_DELETE_ARCHIVE}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_DELETE_ARCHIVE}
							<br />
							<a href="javascript:check_select_multiple('4', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('4', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_EDIT}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_EDIT}
							<br />
							<a href="javascript:check_select_multiple('5', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('5', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_DELETE}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_DELETE}
							<br />
							<a href="javascript:check_select_multiple('6', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('6', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RENAME}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_RENAME}
							<br />
							<a href="javascript:check_select_multiple('7', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('7', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_REDIRECT}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_REDIRECT}
							<br />
							<a href="javascript:check_select_multiple('8', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('8', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_MOVE}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_MOVE}
							<br />
							<a href="javascript:check_select_multiple('9', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('9', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_STATUS}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_STATUS}
							<br />
							<a href="javascript:check_select_multiple('10', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('10', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_COM}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_COM}
							<br />
							<a href="javascript:check_select_multiple('11', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('11', false);">{L_SELECT_NONE}</a>
						</td>
					</tr>
					<tr>
						<td class="row1">
							{L_RESTRICTION}
						</td>
						<td class="row2" style="text-align:center;">
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_RESTRICTION}
							<br />
							<a href="javascript:check_select_multiple('12', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('12', false);">{L_SELECT_NONE}</a>
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