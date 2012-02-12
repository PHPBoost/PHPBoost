		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_DEL_ENTRY}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_WEB_MANAGEMENT}</li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick_link">{L_WEB_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick_link">{L_WEB_ADD}</a>
				</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick_link">{L_WEB_CAT}</a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick_link">{L_WEB_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">		
			<table class="module_table">
				<tr> 
					<th colspan="8">
						{L_LISTE}
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						{L_NAME}
					</td>
					<td class="row1">
						{L_CATEGORY}
					</td>
					<td class="row1">
						{L_VIEW}
					</td>
					<td class="row1">
						{L_DATE}
					</td>
					<td class="row1">
						{L_APROB}
					</td>
					<td class="row1">
						{L_UPDATE}
					</td>
					<td class="row1">
						{L_DELETE}
					</td>
				</tr>
				
				# START web #
				<tr style="text-align:center;"> 
					<td class="row2"> 
						<a href="../web/web.php?cat={web.IDCAT}&amp;id={web.IDWEB}">{web.NAME}</a>
					</td>
					<td class="row2"> 
						{web.CAT}
					</td>
					<td class="row2"> 
						{web.COMPT}
					</td>
					<td class="row2">
						{web.DATE}
					</td>
					<td class="row2">
						{web.APROBATION}
					</td>
					<td class="row2"> 
						<a href="admin_web.php?id={web.IDWEB}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_web.php?delete=true&amp;id={web.IDWEB}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="Supprimer" title="Supprimer" /></a>
					</td>
				</tr>
				# END web #

			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
		