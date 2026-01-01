<style>
	.code {margin-bottom: 5px;}
	.fa:before {margin-right: 3px}
</style>

<section id="module-sandbox-icons">
	<header class="section-header">
		<h1>{@sandbox.module.title} - {@sandbox.icons}</h1>
	</header>

	# INCLUDE SANDBOX_SUBMENU #

<div class="sub-section">
	<div class="content-container">
		<div class="content">
			<article id="font-awesome-list">
				<i class="fab fa-fort-awesome-alt fa-3x"></i> <i class="fab fa-font-awesome-flag fa-3x"></i>
				<header>
					<h2>{@H|sandbox.icons.fa}</h2>
				</header>
				<p>{@H|sandbox.icons.fa.howto.clue}</p>
				<p>{@H|sandbox.icons.fa.howto.update}</p>
				<h3>{@H|sandbox.icons.fa.sample}</h3>
				<div class="content">
					<label for="search-fa">{@H|sandbox.icons.fa.search}</label>
					<input id="search-fa" type="text" name="search-fa" value="">
				</div>
				<div class="cell-flex cell-inline cell-tile fa-list flex-between limited-height">
					# INCLUDE FAS #
					# INCLUDE FAB #
				</div>
			</article>
			<script>
				jQuery('#search-fa').on('input', function(){
					var val = jQuery(this).val();
					var target = jQuery('.fa-list').children();
					target.each(function(){
						var id = jQuery(this).attr('id');
						if(id.indexOf(val) !== -1)
						{
							jQuery(this).show();
						}
						else {
							jQuery(this).hide();
						}
					});
				});
			</script><article>
				<header>
					<h3>{@H|sandbox.icons.fa.howto}</h3>
				</header>
				<div class="content">
					<h4>{@H|sandbox.icons.fa.howto.html}</h4>
					<p>{@H|sandbox.icons.fa.howto.html.class}</p>
					<pre class="precode precode-inline"><code>&lt;i class="far fa-edit">&lt;/i> Edition</code></pre>
					<p>{@H|sandbox.icons.fa.howto.html.class.result.i}<i class="far fa-edit"></i> Edition</p>
					<pre class="precode precode-inline"><code>&lt;a class="fa fa-globe" href="https://www.phpboost.com">PHPBoost&lt;/a></code></pre>
					<p>{@H|sandbox.icons.fa.howto.html.class.result.a}<a class="offload" href="https://www.phpboost.com"><i class="fa fa-globe"></i>PHPBoost</a></p>
					<p>{@H|sandbox.icons.fa.howto.html.class.result.all}</p>

					<h4>{@H|sandbox.icons.fa.howto.css}</h4>
					<p>{@H|sandbox.icons.fa.howto.css.class}</p>
					<div class="formatter-container">
						<span class="formatter-title">{@H|sandbox.icons.fa.howto.css.css.code}</span>
						<div class="formatter-content formatter-code">
							<div class="no-style">
<pre class="precode"><code>.success { ... }
.success::before {
    content: "\f00c";
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
}</code></pre>
							</div>
						</div>
					</div>

					<div class="formatter-container no-js">
						<span class="formatter-title">{@H|sandbox.icons.fa.howto.css.html.code}</span>
						<div class="formatter-content  formatter-code">
							<div class="no-style">
								<pre class="precode"><code>&lt;div class="message-helper bgc success">{@sandbox.component.message.success}&lt;/div></code></pre>
							</div>
						</div>
					</div>
					<div class="message-helper bgc success">{@sandbox.component.message.success}</div>

					<br />
					<h4>{@H|sandbox.icons.fa.howto.bbcode}</h4>
					<p>{@H|sandbox.icons.fa.howto.bbcode.some.icons} <i class="fab fa-font-awesome-flag"></i></p>
					<p>{@H|sandbox.icons.fa.howto.bbcode.tag}</p>
					<p>{@H|sandbox.icons.fa.howto.bbcode.icon.name}</p>
					<p>{@H|sandbox.icons.fa.howto.bbcode.icon.test} <i class="fa fa-cubes"></i></p>
					<p>{@H|sandbox.icons.fa.howto.bbcode.icon.variants}<a class="pinned bgc-full link-color offload" href="https://www.phpboost.com/wiki/la-bibliotheque-font-awesome"><i class="fa fa-share"></i> {@sandbox.phpboost.doc}</a>.</p>

					<br />
					<h4>{@H|sandbox.icons.fa.howto.variants}</h4>
					<p>{@H|sandbox.icons.fa.howto.variants.clue}</p>
					<p>{@H|sandbox.icons.fa.howto.variants.list}<a class="pinned bgc moderator offload" href="https://fontawesome.com/docs/web/style/animate" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> Font-Awesome/examples</a></p>

					<pre class="precode precode-inline"><code>&lt;i class="fa fa-spinner fa-spin-pulse fa-2x">&lt;/i></code></pre>

					<p>{@H|sandbox.icons.fa.howto.variants.spinner}<i class="fa fa-spinner fa-spin-pulse fa-2x"></i></p>
				</div>
			</article>
		</div>
	</div>
</div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE FA #</div></div></div>

	<div class="sub-section"><div class="content-container"><div class="content"># INCLUDE ICOMOON #</div></div></div>

	<footer></footer>
</section>
