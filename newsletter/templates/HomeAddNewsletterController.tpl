<form action="{REWRITED_SCRIPT}" name="form" method="post">	
	<table  class="module_table">
		<tr> 
			<th colspan="3">
				{@newsletter.types.choice}
			</th>
		</tr>
		<tr> 
			<td class="row1" style="text-align:center;">
				<label><input type="radio" id="type" name="type" checked="checked" value="text" />
				<strong>{@newsletter.types.text}</strong></label>
				<br />{@newsletter.types.textexplain}
			</td>
			<td class="row1" style="text-align:center;">
				<label><input type="radio" id="type" name="type" value="bbcode" />
				<strong>{@newsletter.types.bbcode}</strong></label>
				<br />{@newsletter.types.bbcodeexplain}
			</td>
			<td class="row1" style="text-align:center;">
				<label><input type="radio" id="type" name="type" value="html" />
				<strong>{@newsletter.types.html}</strong></label>
				<br />{@newsletter.types.htmlexplain}
			</td>
		</tr>
	</table>
	<br /><br />	
	<fieldset class="fieldset_submit">
		<legend>{@newsletter.types.choice}</legend>
		<input type="submit" value="{@newsletter.types.next}" class="submit" />
	</fieldset>
</form>