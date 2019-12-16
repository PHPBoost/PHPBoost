<table class="table">
	<thead>
		<tr>
			<th colspan="3">{@newsletter.types.choice}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<label>
					<input type="radio" id="type" name="type" checked="checked" value="text">
					<strong>{@newsletter.types.text}</strong>
				</label>
				<br /><span class="newsletter-type success">${html(@newsletter.types.forall)}</span><br />${html(@newsletter.types.text_explain)}
			</td>
			<td>
				<label>
					<input type="radio" id="type" name="type" value="bbcode">
					<strong>{@newsletter.types.bbcode}</strong>
				</label>
				<br /><span class="newsletter-type success">${html(@newsletter.types.forall)}</span><br />${html(@newsletter.types.bbcode_explain)}
			</td>
			<td>
				<label>
					<input type="radio" id="type" name="type" value="html">
					<strong>{@newsletter.types.html}</strong>
				</label>
				<br /><span class="newsletter-type error">${html(@newsletter.types.forexpert)}</span><br />${html(@newsletter.types.html_explain)}
			</td>
		</tr>
	</tbody>
</table>
