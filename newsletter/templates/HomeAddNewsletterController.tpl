<table class="table">
	<thead>
		<tr>
			<th colspan="3">{@newsletter.types.choice}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<label class="radio">
					<input type="radio" id="type-text" name="type" checked value="text">
					<span class="text-strong">{@newsletter.types.text}</span>
				</label>
				<br /><span class="newsletter-type success">${html(@newsletter.types.forall)}</span><br />${html(@newsletter.types.text_explain)}
			</td>
			<td>
				<label class="radio">
					<input type="radio" id="type-bbcode" name="type" value="bbcode">
					<span class="text-strong">{@newsletter.types.bbcode}</span>
				</label>
				<br /><span class="newsletter-type success">${html(@newsletter.types.forall)}</span><br />${html(@newsletter.types.bbcode_explain)}
			</td>
			<td>
				<label class="radio">
					<input type="radio" id="type-html" name="type" value="html">
					<span class="text-strong">{@newsletter.types.html}</span>
				</label>
				<br /><span class="newsletter-type error">${html(@newsletter.types.forexpert)}</span><br />${html(@newsletter.types.html_explain)}
			</td>
		</tr>
	</tbody>
</table>
