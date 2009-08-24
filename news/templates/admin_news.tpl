		{ADMIN_MENU}
		
		<div id="admin_contents">
			<script type="text/javascript">
			<!--
			function Confirm() {
				return confirm("{L_CONFIRM_DEL_NEWS}");
			}
			-->
			</script>
			<table class="module_table">
				<tr>
					<th style="text-align:center;">
						{L_TITLE}
					</th>
					<th style="text-align:center;">
						{L_CATEGORY}
					</th>
					<th style="text-align:center;">
						{L_PSEUDO}
					</th>
					<th style="text-align:center;">
						{L_DATE}
					</th>
					<th style="text-align:center;">
						{L_APROB}
					</th>
					<th style="text-align:center;">
						{L_UPDATE}
					</th>
					<th style="text-align:center;">
						{L_DELETE}
					</th>
				</tr>
				# IF NO_NEWS #
					<tr style="text-align:center;">
						<td colspan="7" style="font-weight:bold;padding:15px;">{NO_NEWS}</td>
					</tr>
				# ELSE #
				# START news #
				<tr style="text-align:center;"> 
					<td class="row2"> 
						<a href="{news.U_NEWS}">{news.TITLE}</a>
					</td>
					<td class="row2">
						<a href="{news.U_CAT}">{news.CATEGORY}</a>
					</td>
					<td class="row2">
						# IF news.U_USER #
							<a href="{news.U_USER}"{news.LEVEL}>{news.LOGIN}</a>
						# ELSE #
							{news.LOGIN}
						# ENDIF #
					</td>
					<td class="row2">
						{news.DATE}
					</td>
					<td class="row2">
						{news.APROBATION} 
						<br />
						<span class="text_small">{news.VISIBLE}</span>
					</td>
					<td class="row2"> 
						<a href="management.php?edit={news.IDNEWS}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="management.php?del={news.IDNEWS}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END news #
				# ENDIF #
			</table>
			# IF NOT NO_NEWS #
			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
			# ENDIF #