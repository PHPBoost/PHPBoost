<section>
	<header>
		<h1>
			{@sandbox.module.title} - {@title.bbcode}
		</h1>
	</header>

	<div class="sandbox-summary">
	  <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
	  </div>
	  <ul>
		<li>
			<a class="summary-link" href="#typography">{@bbcode.title.typography}</a>
		</li>
		<li>
			<a class="summary-link" href="#blocks">{@bbcode.title.blocks}</a>
			<ul>
				<li><a href="#paragraph" class="summary-link">{@bbcode.paragraph}</a></li>
				<li><a href="#block" class="summary-link">{@bbcode.block}</a></li>
				<li><a href="#fieldset" class="summary-link">{@bbcode.fieldset}</a></li>
			</ul>
		</li>
		<li>
			<a class="summary-link" href="#code">{@bbcode.title.code}</a>
			<ul>
				<li><a href="#quote" class="summary-link">{@bbcode.quote}</a></li>
				<li><a href="#hidden" class="summary-link">{@bbcode.hidden}</a></li>
				<li><a href="#php" class="summary-link">{@bbcode.code.php}</a></li>
			</ul>
		</li>
		<li>
			<a class="summary-link" href="#media">{@bbcode.title.media}</a>
			<ul>
				<li><a href="#image" class="summary-link">{@bbcode.image}</a></li>
				<li><a href="#lightbox" class="summary-link">{@bbcode.lightbox}</a></li>
				<li><a href="#youtube" class="summary-link">{@bbcode.youtube}</a></li>
				<li><a href="#movie" class="summary-link">{@bbcode.movie}</a></li>
				<li><a href="#audio" class="summary-link">{@bbcode.audio}</a></li>
			</ul>
		</li>
		<li>
			<a class="summary-link" href="#bbcode-table">{@bbcode.title.table}</a>
		</li>
		<li>
			<a class="summary-link" href="#wiki">{@wiki.module}</a>
		</li>
	  </ul>
	</div>
	<div class="open-summary">
		<i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
	</div>

	<div id="typography" class="sandbox-title">
		<h2>{@bbcode.title.typography}</h2>
	</div>

	<div class="no-style">
		<article class="block">
			<header>
				<h5>{@bbcode.titles}</h5>
			</header>
			<div class="content">
				<h2 class="formatter-title">{@bbcode.title_1} h2.formatter-title</h2>
				<div class="spacer"></div>
				<h3 class="formatter-title">{@bbcode.title_2} h3.formatter-title</h3>
				<div class="spacer"></div>
				<h4 class="formatter-title">{@bbcode.title_3} h4.formatter-title</h4>
				<div class="spacer"></div>
				<h5 class="formatter-title">{@bbcode.title_4} h5.formatter-title</h5>
				<div class="spacer"></div>
				<h6 class="formatter-title">{@bbcode.title_5} h6.formatter-title</h6>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{TYPOGRAPHY}
		</div>
	</div>

	<div id="blocks" class="sandbox-title">
		<h2>{@bbcode.title.blocks}</h2>
	</div>

	<div class="no-style">
		<article id="paragraph" class="block">
			<header>
				<h5>{@bbcode.paragraph}</h5>
			</header>
			<div class="content"><p>{@lorem.medium.content}</p></div>
		</article>
	</div>

	<div class="no-style">
		<article id="block" class="block">
			<header>
				<h5>{@bbcode.block}</h5>
			</header>
			<div class="formatter-container formatter-block">{@lorem.medium.content}</div>
		</article>
	</div>

	<div class="no-style">
		<article id="fieldset" class="block">
			<header>
				<h5>{@bbcode.fieldset}</h5>
			</header>
			<fieldset class="formatter-container formatter-fieldset">
				<legend>{@bbcode.legend}</legend>
				<div class="formatter-content">
					{@lorem.medium.content}
				</div>
			</fieldset>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BLOCK}
		</div>
	</div>

	<div id="code" class="sandbox-title">
		<h2>{@bbcode.title.code}</h2>
	</div>

	<div class="no-style">
		<article id="quote" class="block">
			<header>
				<h5>{@bbcode.quote}</h5>
			</header>
			<blockquote class="formatter-container formatter-blockquote">
				<span class="formatter-title title-perso">{@bbcode.quote} : Lorem Ipsum</span>
				<div class="formatter-content">
					{@lorem.medium.content}
				</div>
			</blockquote>
		</article>
	</div>

	<div class="no-style">
		<article id="hidden" class="block">
			<header>
				<h5>{@bbcode.hidden}</h5>
			</header>
			<div class="formatter-container formatter-hide no-js" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@bbcode.hidden} :</span>
				<div class="formatter-content">
					{@lorem.medium.content}
				</div>
			</div>
		</article>
	</div>

	<div class="no-style">
		<article id="php" class="block">
			<header>
				<h5>{@bbcode.code.php}</h5>
			</header>
			<div class="formatter-container formatter-code">
				<span class="formatter-title">{@bbcode.code.php} : CategoriesCache.class.php</span>
				<div class="formatter-content">
					<pre style="display:inline;">
						<pre class="php" style="font-family:monospace;">
							<span style="color: #FF0000; font-weight: normal;">&lt;?php</span>
							&nbsp;
							<span style="color: #0000FF; font-weight: bold;">abstract</span>
							<span style="color: #0000FF; font-weight: bold;">class</span> CategoriesCache <span style="color: #0000FF; font-weight: bold;">implements</span> CacheData
							<span style="color: #8000FF;">&#123;</span>
							<span style="color: #0000FF; font-weight: bold;">private</span> <span style="color: #000080;">$categories</span><span style="color: #8000FF;">;</span>
							&nbsp;
								<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> synchronize<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
								<span style="color: #8000FF;">&#123;</span>
									<span style="color: #000080;">$categories_cache</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
									<span style="color: #000080;">$category_class</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_category_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
							&nbsp;
									<span style="color: #000080;">$root_category</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_root_category</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
									<span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span>Category<span style="color: #8000FF;">::</span><span style="color: #000000;">ROOT_CATEGORY</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$root_category</span><span style="color: #8000FF;">;</span>
									<span style="color: #000080;">$result</span> <span style="color: #8000FF;">=</span> PersistenceContext<span style="color: #8000FF;">::</span><span style="color: #000000;">get_querier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">select_rows</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_table_name</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #0000FF; font-weight: bold;">array</span><span style="color: #8000FF;">&#40;</span><span style="color: #808080;">'*'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'ORDER BY id_parent, c_order'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
									<span style="color: #0000FF; font-weight: bold;">while</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$row</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$result</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">fetch</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
									<span style="color: #8000FF;">&#123;</span>
										<span style="color: #000080;">$category</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">new</span> <span style="color: #000080;">$category_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
										<span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">set_properties</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$row</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
										<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">auth_is_empty</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
										<span style="color: #8000FF;">&#123;</span>
											<span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">set_authorizations</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$root_category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_authorizations</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
										<span style="color: #8000FF;">&#125;</span>
										<span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$row</span><span style="color: #8000FF;">&#91;</span><span style="color: #808080;">'id'</span><span style="color: #8000FF;">&#93;</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">;</span>
									<span style="color: #8000FF;">&#125;</span>
								<span style="color: #8000FF;">&#125;</span>
							&nbsp;

								<span style="color: #008000; font-style: italic;">/**
								 * Loads and returns the categories cached data.
								 * @return CategoriesCache The cached data
								 */</span>
								<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> load<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
								<span style="color: #8000FF;">&#123;</span>
									<span style="color: #0000FF; font-weight: bold;">return</span> CacheManager<span style="color: #8000FF;">::</span><span style="color: #000000;">load</span><span style="color: #8000FF;">&#40;</span>get_called_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_module_identifier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'categories'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
								<span style="color: #8000FF;">&#125;</span>
							&nbsp;
								<span style="color: #008000; font-style: italic;">/**
								 * Invalidates categories cached data.
								 */</span>
								<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> invalidate<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
								<span style="color: #8000FF;">&#123;</span>
									CacheManager<span style="color: #8000FF;">::</span><span style="color: #000000;">invalidate</span><span style="color: #8000FF;">&#40;</span><span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_module_identifier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'categories'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
								<span style="color: #8000FF;">&#125;</span>
							<span style="color: #8000FF;">&#125;</span>
							<span style="color: #FF0000; font-weight: normal;">?&gt;</span>
						</pre>
					</pre>
				</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BLOCK_CODE}
		</div>
	</div>


	<div id="media" class="sandbox-title">
		<h2>{@bbcode.title.media}</h2>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="image" class="block">
			<header>
				<h5>{@bbcode.image}</h5>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@bbcode.image}" />
			</div>
		</article>
		<article id="lightbox" class="block">
			<header>
				<h5>{@bbcode.lightbox}</h5>
			</header>
			<div class="content">
				<a href="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
					<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@bbcode.lightbox}" />
				</a>
				<a href="{PATH_TO_ROOT}/templates/{THEME}/theme/images/admin.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
					<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/admin.jpg" alt="{@bbcode.lightbox}" />
				</a>
			</div>
		</article>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="youtube" class="block">
			<header>
				<h5>{@bbcode.youtube}</h5>
			</header>
			<div class="media-content">
				<iframe class="youtube-player" src="https://www.youtube.com/embed/YE7VzlLtp-4" allowfullscreen="" width="100%" height="185"></iframe>
				<!-- <video class="youtube-player" controls="" src="http://www.youtubeinmp4.com/redirect.php?video=YE7VzlLtp-4">
					<source src="https://youtu.be/YE7VzlLtp-4" type="video/mp4" />
				</video> -->
			</div>
		</article>
		<article id="movie" class="block">
			<header>
				<h5>{@bbcode.movie}</h5>
			</header>
			<div class="media-content">
				<video class="video-player" controls="">
					<source src="http://data.babsoweb.com/private/logo-pbt.mp4" type="video/mp4" />
				</video>
			</div>
		</article>
	</div>

	<div class="no-style">
		<article id="audio" class="block">
			<header>
				<h5>{@bbcode.audio}</h5>
			</header>
			<div class="content">
				<audio class="audio-player" controls>
					<source src="http://data.babsoweb.com/private/herbiestyle.mp3" />
				</audio>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{MEDIA}
		</div>
	</div>

	<div id="bbcode-table" class="sandbox-title">
		<h2>{@bbcode.title.table} (.formatter-table)</h2>
	</div>

	<div class="no-style">
		<article class="block">
			<table class="table formatter-table">
				<tbody>
					<tr class="formatter-table-row">
						<td class="formatter-table-head" colspan="2">{@bbcode.table.header}</td>
					</tr>
					<tr class="formatter-table-row">
						<td class="formatter-table-col">{@bbcode.table.name}</td>
						<td class="formatter-table-col">{@bbcode.table.description}</td>
					</tr>
					<tr class="formatter-table-row">
						<td class="formatter-table-col">{@bbcode.table.name}</td>
						<td class="formatter-table-col">{@bbcode.table.description}</td>
					</tr>
					<tr class="formatter-table-row">
						<td class="formatter-table-col">{@bbcode.table.name}</td>
						<td class="formatter-table-col">{@bbcode.table.description}</td>
					</tr>
				</tbody>
			</table>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{TABLE}
		</div>
	</div>

	<div id="wiki" class="sandbox-title">
		<h2>{@wiki.module}</h2>
	</div>
	# IF C_WIKI #
	<div class="no-style">
		<article class="block">
			<p class="message-helper bgc notice">{@wiki.conditions}</p>
			<div class="content">
				# START wikimenu #
					<div class="wiki-summary">
						<div class="wiki-summary-title">{@wiki.table.of.contents}</div>
						{wikimenu.MENU}
					</div>
				# END wikimenu #
				{WIKI_CONTENTS}
			</div>
		</article>
	</div>
	# ELSE #
	 {@wiki.not}
	# ENDIF #

	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
