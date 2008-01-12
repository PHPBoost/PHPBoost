		<script type="text/javascript">
		<!--
		function Confirm() {
			return confirm("{L_CONFIRM_DELETE}");
		}
		-->
		</script>

		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_COM}</li>
				<li>
					<a href="admin_com.php"><img src="../templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com.php" class="quick_link">{L_COM_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_com.php"><img src="../templates/{THEME}/images/admin/com.png" alt="" /></a>
					<br />
					<a href="admin_com.php" class="quick_link">{L_COM_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_COM_MANAGEMENT}
					</th>
				</tr>
				 
				# START com_list #				
				<tr style="text-align:center;"> 
					<td class="row2">
						{com_list.CONTENTS}
					</td>
				</tr>				
				# END com_list #
			</table>			
		</div>
		