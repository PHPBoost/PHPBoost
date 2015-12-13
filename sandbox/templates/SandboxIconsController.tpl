<style>
	<!--
		.code {margin-bottom: 5px;}
		.fa:before {margin-right: 3px}
	-->
</style>

<section>
	<header>
		<h1>{@title.icons}</h1>
	</header>
	
	<article>
		<header>
			<h2>{@css.icon.sample}</h2>
		</header>
		<div class="content">	
			<table>
				<caption>{@css.icon.social}</caption>
				<thead>
					<tr>
						<th>{@css.icon.icon}</th>
						<th>{@css.icon.name}</th>
						<th>{@css.icon.code}</th>
					</tr>
				</thead>
				<tbody>
					# START social #
					<tr>
						<td><i class="fa fa-{social.FA} fa-lg"></i></td>
						<td><span>{social.FA}</span></td>
						<td>{social.BEFORE}</td>
					</tr>
					# END web #
				</tbody>
			</table>
			
			<table>
				<caption>{@css.icon.screen}</caption>
				<thead>
					<tr>
						<th>{@css.icon.icon}</th>
						<th>{@css.icon.name}</th>
						<th>{@css.icon.code}</th>
					</tr>
				</thead>
				<tbody>
					# START responsive #
					<tr>
						<td><i class="fa fa-{responsive.FA} fa-lg"></i></td>
						<td><span>{responsive.FA}</span></td>
						<td>{responsive.BEFORE}</td>
					</tr>
					# END responsive #
				</tbody>
			</table>
		</div>
		<footer>{@css.icon.list}<a class="basic-button" href="https://fortawesome.github.io/Font-Awesome/cheatsheet/" title="Font-Awesome">Cheatsheet Font-Awesome</a></footer>
	</article>
	
	<article>
		<header>
			<h2>{@css.icon.howto}</h2>
		</header>
		<div class="content">
			<p>{@css.icon.howto.explain}</p>
			<p>{@css.icon.howto.update}</p>		
			
			<br />
			<h3>{@css.icon.howto.html}</h3>
			<p>{@css.icon.howto.html.class}</p>
			<div class="code">
				<pre><span><</span><span>i class="fa fa-edit"><</span>/i> Edition</pre>
			</div>			
			<p>{@css.icon.howto.html.class.result.i}<i class="fa fa-edit"></i> Edition</p>
			<div class="code">
				<pre><span><</span>a class="fa fa-globe" href="http://www.phpboost.com">PHPBoost<<span>/a></span></pre>
			</div>
			<p>{@css.icon.howto.html.class.result.a}<a class="fa fa-globe" href="http://www.phpboost.com">PHPBoost</a></p>
			<p>{@css.icon.howto.html.class.result.all}</p>
			
			<br />
			<h3>{@css.icon.howto.css}</h3>
			<p>{@css.icon.howto.css.class}</p>
			<span class="formatter-code">{@css.icon.howto.css.css.code}</span>
			<div class="code">
				<pre>.success { ... }<br /><br />.success::before {<br /> content: "\f00c";<br /> font-family: FontAwesome; <br />}</pre>				
			</div>
			<span class="formatter-code">{@css.icon.howto.css.html.code}</span>
			<div class="code">
				<pre><span>div class="success">{@css.message_success}<<span>/div></span></pre>
			</div>
			<div class="success">{@css.message_success}</div>
			
			<br />
			<h3>{@css.icon.howto.variants}</h3>
			<p>{@css.icon.howto.variants.explain}</p>
			<p>{@css.icon.howto.variants.list}<a class="basic-button" href="https://fortawesome.github.io/Font-Awesome/examples/">Font-Awesome/examples</a></p>
			<div class="code">
				<pre><<span>i class="fa fa-spinner fa-pulse fa-2x><</span>/i></pre>
			</div>
			<p>{@css.icon.howto.variants.spinner}<i class="fa fa-spinner fa-pulse fa-2x"></i></p>
		</div>
	</article>
	
	<footer></footer>
</section>

