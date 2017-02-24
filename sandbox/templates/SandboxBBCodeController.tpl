<section>
	<header>
		<h1>
			{@module.title} - {@title.bbcode}
			# IF IS_ADMIN #
			<span class="actions">
				<a href="{PATH_TO_ROOT}/admin/menus/menus.php" title="Admin/menus">Admin/menus</a>
			</span>
			# ENDIF #
		</h1>
	</header>
	
		
	<article class="content">
		<header>
			<h2>
				{@bbcode.title.typography}
			</h2>
		</header>

		<div class="pbt-box-medium">
			<h4>{@bbcode.titles} <i class="fa bbcode-icon-title"></i></h4>
			<h2 class="formatter-title">{@bbcode.title_1}</h2><br />
			<h3 class="formatter-title">{@bbcode.title_2}</h3><br />
			<h4 class="formatter-title">{@bbcode.title_3}</h4><br />
			<h5 class="formatter-title">{@bbcode.title_4}</h5><br /><br />
		</div>
		
		<div class="pbt-box-medium">
			<h4>{@bbcode.title.lists} <i class="fa bbcode-icon-list"></i></h4>
			<ul class="formatter-ul">
				<li class="formatter-li">{@bbcode.element_1}
					<ul class="formatter-ul">
						<li class="formatter-li">{@bbcode.element}</li>
						<li class="formatter-li">{@bbcode.element}</li>
					</ul>
				</li>
				<li class="formatter-li">{@bbcode.element_2}</li>
				<li class="formatter-li">{@bbcode.element_3}</li>
			</ul>

			<ol class="formatter-ol">
				<li class="formatter-li">{@bbcode.element_1}
					<ol class="formatter-ol">
						<li class="formatter-li">{@bbcode.element}</li>
						<li class="formatter-li">{@bbcode.element}</li>
					</ol>
				</li>
				<li class="formatter-li">{@bbcode.element_2}</li>
				<li class="formatter-li">{@bbcode.element_3}</li>
			</ol>
		</div>		
		
	</article>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@bbcode.title.blocks} <i class="fa fa-fw bbcode-icon-subtitle"></i></h2>
	</header>
	<article class="content">
		
		<div class="pbt-box-largest">
			<h4>{@bbcode.paragraph}</h4>
			<p>{@framework.lorem.medium}</p>
		</div>
		
		<div class="pbt-box-largest">
			<h4>{@bbcode.block}</h4>
			<div class="formatter-container formatter-block">
				{@framework.lorem.medium}
			</div>
		</div>
		
		<div class="pbt-box-largest">
			<h4>{@bbcode.fieldset}</h4>
			<fieldset class="formatter-container formatter-fieldset">
				<legend>{@bbcode.legend}</legend>
				<div class="formatter-content">
					{@framework.lorem.medium}
				</div>
			</fieldset>
		</div>
		
	</article>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@bbcode.title.media}</h2>
	</header>
	<article class="content">
		
		<div class="pbt-box-medium">
			<h4>{@bbcode.image} <i class="fa fa-fw bbcode-icon-image"></i></h4>
			<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@bbcode.image}" />
		</div>
		
		<div class="pbt-box-medium">
			<h4>{@bbcode.lightbox} <i class="fa fa-fw bbcode-icon-lightbox"></i></h4>
			<a href="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" data-lightbox="formatter" data-rel=lightcase:collection" title="{@bbcode.lightbox}">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@bbcode.lightbox}" />
			</a>
		</div>
		
		<div class="pbt-box-medium">
			<h4>{@bbcode.youtube} <i class="fa fa-fw bbcode-icon-youtube"></i></h4>
			<video class="youtube-player" controls="" src="http://www.youtubeinmp4.com/redirect.php?video=YE7VzlLtp-4">
				<source src="https://youtu.be/YE7VzlLtp-4" type="video/mp4"></source>
			</video>
		</div>
		
		<div class="pbt-box-medium">
			<h4>{@bbcode.movie} <i class="fa fa-fw bbcode-icon-movie"></i></h4>
			<video class="video-player" controls="" src="http://data.babsoweb.com/private/logo-pbt.mp4">
				<source src="http://data.babsoweb.com/private/logo-pbt.mp4" type="video/mp4"></source>
			</video>
		</div>
		
		<div class="pbt-box-largest">
			<h4>{@bbcode.audio} <i class="fa fa-fw bbcode-icon-sound"></i></h4>
			<audio class="audio-player" controls="">
				<source src="http://data.babsoweb.com/private/soundofyou.mp3" type="video/mp4"></source>
			</audio>
		</div>
		
	</article>
	<footer></footer>
</section>

<section>
	<header>
		<h2>
			{@bbcode.title.code}
			<i class="fa fa-fw bbcode-icon-quote"></i>
			<i class="fa fa-fw bbcode-icon-hide"></i> 
			<i class="fa fa-fw bbcode-icon-code"></i> 
		</h2>
	</header>
	<article class="content">
		
		<div class="pbt-box-largest">
			
			<div class="formatter-container formatter-blockquote">
				<span class="formatter-title title-perso">{@bbcode.quote} :</span>
				<div class="formatter-content">
					{@framework.lorem.medium}
				</div>
			</div>		
		</div>
		
		<div class="pbt-box-largest">
			<div class="formatter-container formatter-hide" onclick="bb_hide(this)">
				<span class="formatter-title title-perso">{@bbcode.hidden} :</span>
				<div class="formatter-content">
					{@framework.lorem.medium}
				</div>
			</div>
		</div>
		
		<div class="pbt-box-largest">
			<div class="formatter-container formatter-code"><span class="formatter-title">{@bbcode.code.php} :</span><div class="formatter-content"><pre style="display:inline;"><pre class="php" style="font-family:monospace;"><span style="color: #FF0000; font-weight: normal;">&lt;?php</span>
			&nbsp;
			<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">class</span> CategoriesCache <span style="color: #0000FF; font-weight: bold;">implements</span> CacheData
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
			<span style="color: #FF0000; font-weight: normal;">?&gt;</span></pre></pre></div></div><br /><br />
		</div>
	</article>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@bbcode.title.table} <i class="fa fa-fw bbcode-icon-table"></i> </h2>
	</header>	
	<div class="pbt-box-largest">
		<table class="formatter-table">
			<tbody>
				<tr class="formatter-table-row">
					<th class="formatter-table-head" colspan="2">{@bbcode.table.header}</th>
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
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@wiki.module}</h2>
	</header>
	<article class="content">
		<div class="pbt-box-largest">
			<p class="notice">{@wiki.conditions}</p>
			<header>
				<h3>{@wiki.table.of.contents}</h3>
			</header>
			<div class="content">
				# START wikimenu #
					<div class="wiki-summary">
						<div class="wiki-summary-title">{@wiki.table.of.contents}</div>
						{wikimenu.MENU}
					</div>
				# END wikimenu #
			</div>
			<footer></footer>
		</div>
		<div class="pbt-box-100P">
			<header>
				<h3>{@wiki.contents}</h3>
			</header>
			<div class="content">
				{WIKI_CONTENTS}
			</div>
			<footer></footer>
		</div>
	</article>
	<footer></footer>
</section>

