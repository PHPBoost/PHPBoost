<section>
	<header>
		<h2>{@css.typography}</h2>
	</header>
	<div class="content">

		<h5>{@css.titles}</h5><br />
		<div class="content">
			<h1>h1. {@css.title} 1</h1>
			<h2>h2. {@css.title} 2</h2>
			<h3>h3. {@css.title} 3</h3>
			<h4>h4. {@css.title} 4</h4>
			<h5>h5. {@css.title} 5</h5>
			<h6>h6. {@css.title} 6</h6><br />
		</div>

		<h5>{@css.specific_titles}</h5><br />
		<div class="content">
			<h2 class="formatter-title">{@css.title} 1</h2><br />
			<h3 class="formatter-title">{@css.title} 2</h3><br />
			<h4 class="formatter-title">{@css.title} 3</h4><br />
			<h5 class="formatter-title">{@css.title} 4</h5><br /><br />
		</div>

		<h5>{@css.styles}</h5><br />
		<div class="content">
			<strong>{@css.text_bold}</strong><br />
			<em>{@css.text_italic}</em><br />
			<span style="text-decoration: underline;">{@css.text_underline}</span><br />
			<strike>{@css.text_strike}</strike><br /><br />
		</div>

		<h5>{@css.sizes}</h5><br />
		<div class="content">
			<a href="#">{@css.link}</a> <br />
			<a href="#" class="smaller">{@css.link_smaller}</a> <br />
			<a href="#" class="small">{@css.link_small}</a> <br />
			<a href="#" class="big">{@css.link_big}</a> <br />
			<a href="#" class="bigger">{@css.link_bigger}</a> <br />
			<a href="#" class="biggest">{@css.link_biggest}</a> <br /><br />
		</div>

		<h5>{@css.rank_color}</h5><br />
		<div class="content">
			<a href="#" class="admin">{@css.admin}</a> <br />
			<a href="#" class="modo">{@css.modo}</a> <br />
			<a href="#" class="member">{@css.member}</a> <br />
		</div>

	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@css.miscellaneous}</h2>
	</header>

	<div class="content">
		<h5>{@css.main_actions_icons}</h5><br />
		<div class="content">
			<ul>
				<li>{@css.rss_feed} : <a href="#" class="fa fa-syndication"></a></li>
				<li>{@css.edit} : <a href="#" class="fa fa-edit"></a></li>
				<li>{@css.delete} : <a href="#" class="fa fa-delete"></a></li>
				<li>{@css.delete.confirm} : <a href="#" class="fa fa-delete" data-confirmation="delete-element"></a></li>
				<li>{@css.delete.confirm.custom} : <a href="#" class="fa fa-delete" data-confirmation="{@css.delete.custom_message}"></a></li>
			</ul>
		</div>

		<br />
		<h5>{@css.lists}</h5><br />
		<div class="content">
			<ul>
				<li>{@css.element} 1</li>
				<li>{@css.element} 2</li>
				<li>{@css.element} 3</li>
			</ul>

			<ol>
				<li>{@css.element} 1</li>
				<li>{@css.element} 2</li>
				<li>{@css.element} 3</li>
			</ol>

			<ul class="formatter-ul">
				<li class="formatter-li">{@css.element} bbcode 1</li>
				<li class="formatter-li">{@css.element} bbcode 2</li>
				<li class="formatter-li">{@css.element} bbcode 3</li>
			</ul>

			<ol class="formatter-ol">
				<li class="formatter-li">{@css.element} bbcode 1</li>
				<li class="formatter-li">{@css.element} bbcode 2</li>
				<li class="formatter-li">{@css.element} bbcode 3</li>
			</ol>
		</div>
		<br />

		<h5>{@css.progress_bar}</h5><br />
		<div class="content">
			<h6>25%</h6>
			<div class="progressbar-container" style="width:35%;">
				<div class="progressbar-infos">25%</div>
				<div class="progressbar" style="width:25%;"></div>
			</div><br />

			<h6>50%</h6>
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar-infos">50%</div>
				<div class="progressbar" style="width:50%"></div>
			</div><br />

			<h6>75%</h6>
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar-infos">75%</div>
				<div class="progressbar" style="width:75%"></div>
			</div><br />

			<h6>100%</h6>
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar-infos">100%</div>
				<div class="progressbar" style="width:100%"></div>
			</div>
		</div><br />

		<br />
		<h5>{@css.explorer}</h5>
		<div class="content">
			<div class="explorer">
				<div class="cats">
						<h2>{@css.explorer}</h2>
					<div class="content">
						<ul>
							<li><a id="class_0" href="#"><i class="fa fa-folder"></i>{@css.root}</a>
								<ul>
									<li class="sub"><a id="class_1" href="#"><i class="fa fa-folder"></i>{@css.cat} 1</a><span id="cat_1"></span></li>
									<li class="sub"><a class="parent" href="javascript:show_cat_contents(2, 0);"><span class="fa fa-minus-square-o" id="img2_2"></span><span class="fa fa-folder-open" id ="img_2"></span></a><a class="selected" id="class_2" href="#">{@css.cat} 2</a>
									<span id="cat_2">
										<ul>
											<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@css.cat} 3</a></li>
											<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@css.cat} 4</a></li>
										</ul>
									</span></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="files">
						<h2>{@css.tree}</h2>
					<div class="content" id="cat_contents">
						<ul>
							<li><a href="#"><i class="fa fa-folder"></i>{@css.cat} 3</a></li>
							<li><a href="javascript:open_cat(2); show_cat_contents(0, 0);"><i class="fa fa-folder"></i>{@css.cat} 4</a></li>
							<li><a href="#"><i class="fa fa-file"></i>{@css.file} 1</a></li>
							<li><a href="#"><i class="fa fa-file"></i>{@css.file} 2</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<br/></br>
		<div style="overflow: hidden;">
			<h5>{@css.options}</h5>
			<div class="options">
				<h6>{@css.options.sort_by}</h6>
				<select>
					<option value="{@css.options.sort_by.alphabetical}">{@css.options.sort_by.alphabetical}</option>
					<option value="{@css.options.sort_by.size}">{@css.options.sort_by.size}</option>
					<option value="{@css.options.sort_by.date}">{@css.options.sort_by.date}</option>
					<option value="{@css.options.sort_by.popularity}">{@css.options.sort_by.popularity}</option>
					<option value="{@css.options.sort_by.note}">{@css.options.sort_by.note}</option>
				</select>
				<select>
					<option value="{@css.modules_menus.direction.up}">{@css.modules_menus.direction.up}</option>
					<option value="{@css.modules_menus.direction.down}">{@css.modules_menus.direction.down}</option>
				</select>
			</div>
		</div>
		<br/></br>
		<h5>{@css.button}</h5>
		<div class="content center">
			<button type="submit" class="button">{@css.button}</button>
			<button type="submit" class="button-hover">{@css.button} hover</button>
			<button type="submit" class="button-active">{@css.button} active</button>
		</div>
		<br />
		<div class="content center">
			<button type="submit" class="button-small">{@css.button} small</button>
			<button type="submit" class="submit">{@css.button} submit</button>
			<button type="submit" class="basic-button">{@css.button} basic-button</button>
			<button type="submit" class="basic-button alt">{@css.button} basic-button.alt</button>
		</div>
		<br/></br>
		<h5>{@css.sortable}</h5>
		<br/></br>
		<div class="content">
			<ul class="sortable-block">
				<li class="sortable-element">
					<div class="sortable-selector" title="Déplacer"></div>
					<div class="sortable-title">
						<span><a>{@css.static.sortable}</a></span>
					</div>
				</li>
				<li class="sortable-element dragged" style="position: relative;">
					<div class="sortable-selector" title="Déplacer"></div>
					<div class="sortable-title">
						<span><a>{@css.moved.sortable}</a></span>
					</div>
				</li>
				<li>
					<div class="dropzone">{@css.dropzone}</div>
				</li>
			</ul>
		</div>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@css.quote}, {@css.code}, {@css.hidden}</h2>
	</header>
	<div class="content">
		<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">{@css.quote} :</span><div class="formatter-content">Dein Syria per speciosam interpatet diffusa planitiem. hanc nobilitat Antiochia, mundo cognita civitas, cui non certaverit alia advecticiis ita adfluere copiis et internis, et Laodicia et Apamia itidemque Seleucia iam inde a primis auspiciis florentissimae.</div></div><br />
		<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title title-perso">{@css.hidden} :</span><div class="formatter-content">Dein Syria per speciosam interpatet diffusa planitiem. hanc nobilitat Antiochia, mundo cognita civitas, cui non certaverit alia advecticiis ita adfluere copiis et internis, et Laodicia et Apamia itidemque Seleucia iam inde a primis auspiciis florentissimae.</div></div><br />
		<div class="formatter-container formatter-code"><span class="formatter-title">{@css.code.php} :</span><div class="formatter-content"><pre style="display:inline;"><pre class="php" style="font-family:monospace;"><a href="http://www.php.net/%26amp%3Blt%3CSEMI%3E%3Fphp"><span style="color: #FF0000; font-weight: normal;">&lt;?php</span></a>
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
			<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_table_name<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_category_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_module_identifier<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_root_category<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_categories<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_childrens<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id_category</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #000080;">$childrens</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">array</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
				<span style="color: #0000FF; font-weight: bold;">foreach</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span> <span style="color: #0000FF; font-weight: bold;">as</span> <span style="color: #000080;">$id</span> <span style="color: #8000FF;">=&gt;</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">&#41;</span>
				<span style="color: #8000FF;">&#123;</span>
					<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_id_parent</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span> <span style="color: #8000FF;">==</span> <span style="color: #000080;">$id_category</span><span style="color: #8000FF;">&#41;</span>
					<span style="color: #8000FF;">&#123;</span>
						<span style="color: #000080;">$childrens</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">;</span>
					<span style="color: #8000FF;">&#125;</span>
				<span style="color: #8000FF;">&#125;</span>
				<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$childrens</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> category_exists<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #0000FF; font-weight: bold;">return</span> array_key_exists<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">,</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_category<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">category_exists</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
				<span style="color: #8000FF;">&#123;</span>
					<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#93;</span><span style="color: #8000FF;">;</span>
				<span style="color: #8000FF;">&#125;</span>
				throw <span style="color: #0000FF; font-weight: bold;">new</span> CategoryNotFoundException<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
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
		&nbsp;
			<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #000080;">$class_name</span> <span style="color: #8000FF;">=</span> get_called_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
				<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #0000FF; font-weight: bold;">new</span> <span style="color: #000080;">$class_name</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
		<span style="color: #8000FF;">&#125;</span>
		<a href="http://www.php.net/%3F%26amp%3Bgt%3CSEMI%3E"><span style="color: #FF0000; font-weight: normal;">?&gt;</span></a></pre></pre></div></div><br /><br />
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@css.pagination}</h2>
	</header>
	<div class="content">
		<div class="center"># INCLUDE PAGINATION #</div>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@css.table}</h2>
	</header>
	<div class="content">
		<table id="table">
			<caption>
				{@css.table_description}
			</caption>
			<thead>
				<tr>
					<th>
						<a href="#" class="fa fa-table-sort-up"></a>
						{@css.table.name}
						<a href="#" class="fa fa-table-sort-down"></a>
					</th>
					<th>
						{@css.table.description}
					</th>
					<th>
						{@css.table.author}
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="5">
						# INCLUDE PAGINATION #
					</th>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>
						{@css.table.test}
					</td>
					<td>
						{@css.table.description}
					</td>
					<td>
						{@css.table.author}
					</td>
				</tr>
				<tr>
					<td>
						{@css.table.test}
					</td>
					<td>
						{@css.table.description}
					</td>
					<td>
						{@css.table.author}
					</td>
				</tr>
				<tr>
					<td>
						{@css.table.test}
					</td>
					<td>
						{@css.table.description}
					</td>
					<td>
						{@css.table.author}
					</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<h5>{@css.specific.table}</h5>
		<table class="formatter-table">
			<tbody>
				<tr class="formatter-table-row">
					<th class="formatter-table-head" colspan="2">{@css.table.header}</th>
				</tr>
				<tr class="formatter-table-row">
					<td class="formatter-table-col">{@css.table.test}</td>
					<td class="formatter-table-col">{@css.table.description}</td>
				</tr>
				<tr class="formatter-table-row">
					<td class="formatter-table-col">{@css.table.test}</td>
					<td class="formatter-table-col">{@css.table.description}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h2>{@css.messages_and_coms}</h2>
	</header>
	<div class="content"><br />
		<div id="com2" class="message" itemscope="itemscope" itemtype="http://schema.org/Comment">
			<div class="message-container">

				<div class="message-user-infos">
					<div class="message-pseudo">
							<a itemprop="author" href="{PATH_TO_ROOT}/user/?url=/profile/1" class="admin">{@css.messages.login}</a>
					</div>
					<div class="message-level">{@css.messages.level}</div>
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" class="message-avatar" alt="${LangLoader::get_message('avatar', 'user-common')}" />
				</div>

				<div class="message-date">
					<span class="actions">
						<a itemprop="url" href="#com2">#2</a>
							<a href="#comments_message" class="fa fa-edit"></a>
							<a href="#comments_message" class="fa fa-delete" data-confirmation="delete-element"></a>
					</span>
					<span itemprop="datePublished" content="2013-09-05T15:37:01+00:00">{@css.messages.date}</span>
				</div>

				<div class="message-message">
					<div itemprop="text" class="message-content" class="content">{@css.messages.content}</div>
				</div>

			</div>
		</div>
	</div>
	<footer></footer>
</section>

<br />

<section>
	<header>
		<h2>{@css.error_messages}</h2>
	</header>
	<div class="content">
		# START messages # # INCLUDE messages.VIEW # # END messages #
	</div>
	<footer></footer>
</section>

<br /><hr><br />

<h2>{@css.page}</h2><br />

<section>
	<header>
		<h2>{@css.page.title}</h2>
	</header>
	<div class="content">
		Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.<br/>
		<article>
			<header>
				<h2>{@css.page.subtitle}</h2>
			</header>
			<div class="content">
				Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.<br/>
				<article>
					<header>
						<h3>{@css.page.subsubtitle}</h3>
					</header>
					<div class="content">
						Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.
					</div>
					<footer></footer>
				</article>
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<h2>{@css.blocks}</h2><br />

<section>
	<header>
		<h2>{@css.page.title}</h2>
	</header>
	<div class="content">
		<article class="block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<h2>{@css.blocks.medium}</h2><br />

<section>
	<header>
		<h2>{@css.page.title}</h2>
	</header>
	<div class="content" style="overflow: hidden;">
		<article class="medium-block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.
			</div>
			<footer></footer>
		</article>
		<article class="medium-block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<h2>{@css.blocks.small}</h2><br />

<section>
	<header>
		<h2>{@css.page.title}</h2>
	</header>
	<div class="content" style="overflow: hidden;">
		<article class="small-block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis.
			</div>
			<footer></footer>
		</article>
		<article class="small-block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis.
			</div>
			<footer></footer>
		</article>
		<article class="small-block">
			<header>
				<h3>{@css.block.title}</h3>
			</header>
			<div class="content">
				Inpares parte deviis.
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>


<h2>{@wiki.module}</h2><br />
<section>
	<header></header>
	<div class="content" style="overflow: hidden;">
		<article>
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
		</article>
		<article>
			<header>
				<h3>{@wiki.contents}</h3>
			</header>
			<div class="content">
				{WIKI_CONTENTS}
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

