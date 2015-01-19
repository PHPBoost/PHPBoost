<section>
	<header><h1>{@users}</h1></header>
	# IF C_ARE_GROUPS #
	<table>
		<tr>
			<td style="vertical-align:top;">
				# INCLUDE SELECT_GROUP #
			</td>
		</tr>
	</table>	
	<br /><br />
	# ENDIF #
	# INCLUDE table #
	<footer></footer>
</section>