<section id="module-user-users-list">
	<header>
		<h1>{@users}</h1>
	</header>
	<div class="content">
		# IF C_ARE_GROUPS #
		<table id="table2">
			<tr>
				<td class="valign-top">
					# INCLUDE SELECT_GROUP #
				</td>
			</tr>
		</table>	
		<br /><br />
		# ENDIF #

		# INCLUDE table #
	</div>
	<footer></footer>
</section>