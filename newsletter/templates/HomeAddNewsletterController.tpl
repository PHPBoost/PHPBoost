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
				<br /><span class="newsletter-type success">{@H|newsletter.types.for.all}</span><br />{@H|newsletter.types.text.clue}
			</td>
			<td>
				<label class="radio">
					<input type="radio" id="type-bbcode" name="type" value="bbcode">
					<span class="text-strong">{@newsletter.types.bbcode}</span>
				</label>
				<br /><span class="newsletter-type success">{@H|newsletter.types.for.all}</span><br />{@H|newsletter.types.bbcode.clue}
			</td>
			<td>
				<label class="radio">
					<input type="radio" id="type-html" name="type" value="html">
					<span class="text-strong">{@newsletter.types.html}</span>
				</label>
				<br /><span class="newsletter-type error">{@H|newsletter.types.for.experts}</span><br />{@H|newsletter.types.html.clue}
			</td>
		</tr>
	</tbody>
</table>
