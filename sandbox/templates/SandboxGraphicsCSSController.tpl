<section id="sandbox-css">
	<header>
		<h1>
			{@module.title} - {@title.css}
		</h1>
	</header>
	<div class="sandbox-summary">
      <div class="close-summary">
        <i class="fa fa-arrow-circle-left"></i>
      </div>
      <ul>
        <li>
			<a class="summary-link" href="#framework">{@css.title.framework}</a>
			<ul>
				<li><a href="#page-title" class="summary-link">{@css.page.title}</a></li>
				<li><a href="#options" class="summary-link">{@css.options}</a></li>
				<li><a href="#options-infos" class="summary-link">{@css.options}.infos</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#typography">{@css.title.typography}</a>
			<ul>
				<li><a href="#titles" class="summary-link">{@css.titles}</a></li>
				<li><a href="#sizes" class="summary-link">{@css.title.sizes}</a></li>
				<li><a href="#styles" class="summary-link">{@css.styles}</a></li>
				<li><a href="#rank-colors" class="summary-link">{@css.rank_color}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#miscellaneous">{@css.miscellaneous}</a>
			<ul>
				<li><a href="#progress-bar" class="summary-link">{@css.progress_bar}</a></li>
				<li><a href="#icons" class="summary-link">{@css.main_actions_icons}</a></li>
				<li><a href="#explorer" class="summary-link">{@css.explorer}</a></li>
				<li><a href="#lists" class="summary-link">{@css.lists}</a></li>
				<li><a href="#buttons" class="summary-link">{@css.button}</a></li>
				<li><a href="#notation" class="summary-link">{@css.notation}</a></li>
				<li><a href="#pagination" class="summary-link">{@css.pagination}</a></li>
				<li><a href="#sortable" class="summary-link">{@css.sortable}</a></li>
				<li><a href="#css-table" class="summary-link">{@css.table}</a></li>
				<li><a href="#messages" class="summary-link">{@css.messages.and.coms}</a></li>
				<li><a href="#alerts" class="summary-link">{@css.alert.messages}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#blocks">{@css.blocks}</a>
		</li>
      </ul>
    </div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<div id="framework" class="sandbox-title">
		<h2>{@css.title.framework}</h2>
	</div>

	<div class="no-style">
		<article id="page-title" class="block">
			<header>
				<h2>
					<span>{@css.page.title}</span>
					<span class="actions">
						<a href="#" class="fa fa-edit" title="{@css.edit}"></a>
						<a href="#" class="fa fa-trash" title="{@css.delete}"></a>
					</span>
				</h2>
				<div class="more">{@css.more}</div>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				<div>{@framework.lorem.large}</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PAGE}
		</div>
	</div>

	<div class="no-style">
		<article id="options" class="block">
			<header>
				<h5>{@css.form} {@css.options}</h5>
			</header>
			<div class="content">
				<form class="options">
					<div class="horizontal-fieldset">
					    <span class="horizontal-fieldset-desc">{@css.options.sort_by}</span>
					    <div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select picture-status-constraint">
									<select>
										<option value="{@css.options.sort_by.alphabetical}">{@css.options.sort_by.alphabetical}</option>
										<option value="{@css.options.sort_by.size}">{@css.options.sort_by.size}</option>
										<option value="{@css.options.sort_by.date}">{@css.options.sort_by.date}</option>
										<option value="{@css.options.sort_by.popularity}">{@css.options.sort_by.popularity}</option>
										<option value="{@css.options.sort_by.note}">{@css.options.sort_by.note}</option>
									</select>
									<span class="text-status-constraint" style="display: none;"></span>
								</div>
							</div>
						</div>
						<div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select picture-status-constraint">
									<select>
										<option value="{@css.modules_menus.direction.up}">{@css.modules_menus.direction.up}</option>
										<option value="{@css.modules_menus.direction.down}">{@css.modules_menus.direction.down}</option>
									</select>
									<span class="text-status-constraint" style="display: none;"></span>
								</div>
							</div>
						</div>
				    </div>
				</form>
				<div class="spacer"></div>
				{@framework.lorem.large}
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{FORM_OPTION}
		</div>
	</div>

	<div class="no-style">
		<article id="options-infos" class="block">
			<header>
				<h5>{@css.class} {@css.options}.infos</h5>
			</header>
			<div class="content">
				<div class="options infos">
					<div class="center">
						<span>
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image">
						</span>
						<div class="spacer"></div>
						<a href="#" class="basic-button">
							<i class="fa fa-globe"></i> {@css.options.link}
						</a>
						<a href="#" class="basic-button alt" title="{@css.options.link}">
							<i class="fa fa-unlink"></i>
						</a>
					</div>
					<h6>{@css.options.file.title}</h6>
					<span class="text-strong">{@css.options.option.title} : </span><span>0</span><br />
					<span class="text-strong">{@css.options.option.title} : </span><span><a itemprop="about" class="small" href="#">{@css.options.link}</a></span><br />
					<span> {@css.options.option.com}</span>
					<div class="spacer"></div>
					<div class="center">
						<div class="notation" id="notation-1">
							<span class="stars">
								<a href="" onclick="return false;" class="fa star star-hover fa-star-o" id="star-1-1"></a>
								<a href="" onclick="return false;" class="fa star star-hover fa-star-o" id="star-1-2"></a>
								<a href="" onclick="return false;" class="fa star star-hover fa-star-o" id="star-1-3"></a>
								<a href="" onclick="return false;" class="fa star star-hover fa-star-o" id="star-1-4"></a>
								<a href="" onclick="return false;" class="fa star star-hover fa-star-o" id="star-1-5"></a>
							</span>
							<span class="notes">
								<span class="number-notes">0</span>
								<span title="0 {@css.options.sort_by.note}">{@css.options.sort_by.note}</span>
							</span>
						</div>
					</div>
				</div>
				{@framework.lorem.large}
			</div>
			<div class="spacer"></div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{DIV_OPTION}
		</div>
	</div>

	<div id="typography" class="sandbox-title">
		<h2>{@css.title.typography}</h2>
	</div>

	<div class="no-style">
		<article id="titles" class="block">
			<header>
				<h5>{@css.titles}</h5>
			</header>
			<div class="content">
				<h1>h1. {@css.title} 1</h1>
				<h2>h2. {@css.title} 2</h2>
				<h3>h3. {@css.title} 3</h3>
				<h4>h4. {@css.title} 4</h4>
				<h5>h5. {@css.title} 5</h5>
				<h6>h6. {@css.title} 6</h6>
			</div>
		</article>
	</div>

	<div class="no-style elements-container columns-3">
		<article id="sizes" class="block">
			<header>
				<h5>{@css.title.sizes}</h5>
			</header>
			<div class="content">
				<span href="#" class="smaller">{@css.text.smaller}</span> <br />
				<span href="#" class="small">{@css.text.small}</span> <br />
				<span href="#" class="big">{@css.text.big}</span> <br />
				<span href="#" class="bigger">{@css.text.bigger}</span> <br />
				<span href="#" class="biggest">{@css.text.biggest}</span>
			</div>
		</article>
		<article id="styles" class="block">
			<header>
				<h5>{@css.styles}</h5>
			</header>
			<div class="content">
				<strong>{@css.text_bold}</strong><br />
				<em>{@css.text_italic}</em><br />
				<span style="text-decoration: underline;">{@css.text_underline}</span><br />
				<strike>{@css.text_strike}</strike><br />
				<a href="#" title="{@css.link}">{@css.link}</a>
			</div>
		</article>
		<article id="rank-colors" class="block">
			<header>
				<h5>{@css.rank_color}</h5>
			</header>
			<div class="content">
				<a href="#" class="admin" title="{@css.admin}">{@css.admin}</a> <br />
				<a href="#" class="modo" title="{@css.modo}">{@css.modo}</a> <br />
				<a href="#" class="member" title="{@css.member}">{@css.member}</a> <br />
			</div>
		</article>
	</div>

	<div id="miscellaneous" class="sandbox-title">
		<h2>{@css.miscellaneous}</h2>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="progress-bar" class="block">
			<header>
				<h5>{@css.progress_bar}</h5>
			</header>
			<div class="content">
				<h6>25%</h6>
				<div class="progressbar-container">
					<div class="progressbar-infos">25%</div>
					<div class="progressbar" style="width:25%;"></div>
				</div>

				<h6>50%</h6>
				<div class="progressbar-container">
					<div class="progressbar-infos">50%</div>
					<div class="progressbar" style="width:50%"></div>
				</div>

				<h6>75%</h6>
				<div class="progressbar-container">
					<div class="progressbar-infos">75%</div>
					<div class="progressbar" style="width:75%"></div>
				</div>

				<h6>100%</h6>
				<div class="progressbar-container">
					<div class="progressbar-infos">100%</div>
					<div class="progressbar" style="width:100%"></div>
				</div>
			</div>
		</article>

		<article id="icons" class="block">
			<header>
				<h5>{@css.main_actions_icons}</h5>
			</header>
			<div class="content">
				<ul>
					<li>{@css.rss_feed} : <a href="#" class="fa fa-syndication" title="{@css.rss_feed}"></a></li>
					<li>{@css.edit} : <a href="#" class="fa fa-edit" title="{@css.edit}"></a></li>
					<li>{@css.delete} : <a href="#" class="fa fa-delete" title="{@css.delete}"></a></li>
					<li>{@css.delete.confirm} : <a href="#" class="fa fa-delete" data-confirmation="delete-element" title="{@css.delete.confirm}"></a></li>
					<li>{@css.delete.confirm.custom} : <a href="#" class="fa fa-delete" data-confirmation="{@css.delete.custom_message}" title="{@css.delete.confirm.custom}"></a></li>
				</ul>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PROGRESS_BAR}
		</div>
	</div>

	<div class="no-style">
		<article id="explorer" class="block">
			<header>
				<h5>{@css.explorer}</h5>
			</header>
			<div class="content">
				<div class="explorer">
					<div class="cats">
							<h2>{@css.explorer}</h2>
						<div class="content">
							<ul>
								<li><a id="class_0" href="#" title="{@css.root}"><i class="fa fa-folder"></i>{@css.root}</a>
									<ul>
										<li class="sub"><a id="class_1" href="#" title="{@css.cat} 1"><i class="fa fa-folder"></i>{@css.cat} 1</a><span id="cat_1"></span></li>
										<li class="sub">
											<a class="parent" href="javascript:show_cat_contents(2, 0);" title="{@css.cat} 2">
												<span class="fa fa-minus-square-o" id="img2_2"></span><span class="fa fa-folder-open" id ="img_2"></span>
											</a>
											<a class="selected" id="class_2" href="#" title="{@css.cat} 2">{@css.cat} 2</a>
											<span id="cat_2">
												<ul>
													<li class="sub"><a href="#"><i class="fa fa-folder" title="{@css.cat} 3"></i>{@css.cat} 3</a></li>
													<li class="sub"><a href="#"><i class="fa fa-folder" title="{@css.cat} 4"></i>{@css.cat} 4</a></li>
												</ul>
											</span>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
					<div class="files">
							<h2>{@css.tree}</h2>
						<div class="content" id="cat_contents">
							<ul>
								<li><a href="#" title="{@css.cat} 3"><i class="fa fa-folder"></i>{@css.cat} 3</a></li>
								<li><a href="javascript:open_cat(2); show_cat_contents(0, 0);" title="{@css.cat} 4"><i class="fa fa-folder"></i>{@css.cat} 4</a></li>
								<li><a href="#" title="{@css.file} 1"><i class="fa fa-file"></i>{@css.file} 1</a></li>
								<li><a href="#" title="{@css.file} 2"><i class="fa fa-file"></i>{@css.file} 2</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{EXPLORER}
		</div>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="lists" class="block">
			<header>
				<h5>{@css.lists}</h5>
			</header>
			<div class="content">
				<ul>
					<li>{@css.element} 1
						<ul>
							<li>{@css.element}</li>
							<li>{@css.element}</li>
						</ul>
					</li>
					<li>{@css.element} 2</li>
					<li>{@css.element} 3</li>
				</ul>

				<ol>
					<li>{@css.element} 1
						<ol>
							<li>{@css.element}</li>
							<li>{@css.element}</li>
						</ol>
					</li>
					<li>{@css.element} 2</li>
					<li>{@css.element} 3</li>
				</ol>
			</div>
		</article>
		<article id="buttons" class="block">
			<header>
				<h5>{@css.button}</h5>
			</header>
			<div class="content">
				<button type="submit" class="button">{@css.button}</button>
				<button type="submit" class="submit">.submit</button><br />
				<button type="submit" class="button-small">.small</button><br />
				<button type="submit" class="basic-button">.basic-button</button><br />
				<button type="submit" class="basic-button alt">.basic-button.alt</button>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BUTTON}
		</div>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="notation" class="block">
			<header>
				<h5>{@css.notation}</h5>
			</header>
			<div class="content">
				<div class="notation">
					<a href="" onclick="return false;" class="fa star fa-star"></a>
					<a href="" onclick="return false;" class="fa star fa-star"></a>
					<a href="" onclick="return false;" class="fa star fa-star-half-o"></a>
					<a href="" onclick="return false;" class="fa star fa-star-o"></a>
					<a href="" onclick="return false;" class="fa star fa-star-o"></a>
				</div>
			</div>
		</article>
		<article id="pagination" class="block">
			<header>
				<h5>{@css.pagination}</h5>
			</header>
			<div class="content">
				# INCLUDE PAGINATION #
			</div>
		</article>
	</div>

	<div class="no-style">
		<article id="sortable" class="block">
			<header>
				<h5>{@css.sortable}</h5>
			</header>
			<div class="content">
				<ul class="sortable-block">
					<li class="sortable-element">
						<div class="sortable-selector" title="{@css.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@css.static.sortable}</a></span>
						</div>
					</li>
					<li class="sortable-element dragged" style="position: relative;">
						<div class="sortable-selector" title="{@css.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@css.moving.sortable}</a></span>
						</div>
					</li>
					<li>
						<div class="dropzone">{@css.dropzone}</div>
					</li>
				</ul>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{SORTABLE}
		</div>
	</div>

	<div class="no-style">
		<article id="css-table" class="block">
			<header>
				<h5>{@css.table}</h5>
			</header>
			<div class="content">
				<table id="table">
					<caption>
						{@css.table.description}
					</caption>
					<thead>
						<tr>
							<th>
								<a href="#" class="fa fa-table-sort-up" title="{@css.table.sort.up}"></a>
								{@css.table.name}
								<a href="#" class="fa fa-table-sort-down" title="{@css.table.sort.down}"></a>
							</th>
							<th>{@css.table.description}</th>
							<th>{@css.table.author}</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="5"># INCLUDE PAGINATION #</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>{@css.table.test}</td>
							<td>{@css.table.description}</td>
							<td>{@css.table.author}</td>
						</tr>
						<tr>
							<td>{@css.table.test}</td>
							<td>{@css.table.description}</td>
							<td>{@css.table.author}</td>
						</tr>
						<tr>
							<td>{@css.table.test}</td>
							<td>{@css.table.description}</td>
							<td>{@css.table.author}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{TABLE}
		</div>
	</div>

	<div class="no-style">
		<article id="messages" class="block">
			<header>
				<h5>{@css.messages.and.coms}</h5>
			</header>
			<div class="content">
				<div id="com2" class="message" itemscope="itemscope" itemtype="http://schema.org/Comment">
					<div class="message-container">

						<div class="message-user-infos">
							<div class="message-pseudo">
								<a itemprop="author" href="{PATH_TO_ROOT}/user/?url=/profile/1" class="admin" title="{@css.messages.login}">{@css.messages.login}</a>
								<div class="message-level">{@css.messages.level}</div>
							</div>
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" class="message-avatar" alt="${LangLoader::get_message('avatar', 'user-common')}" />
						</div>

						<div class="message-date">
							<span class="actions">
								<a itemprop="url" href="#com2" title="#2">#2</a>
								<a href="#" class="fa fa-edit" title="{@css.edit}"></a>
								<a href="#" class="fa fa-delete" data-confirmation="delete-element" title="{@css.delete.confirm}"></a>
							</span>
							<span itemprop="datePublished" content="2013-09-05T15:37:01+00:00">{@css.messages.date}</span>
						</div>

						<div class="message-message">
							<div itemprop="text" class="message-content">{@css.messages.content}</div>
						</div>

					</div>
				</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{MESSAGE}
		</div>
	</div>

	<div id="alerts" class="no-style">
		<article class="block">
			<header>
				<h5>{@css.alert.messages}</h5>
			</header>
			<div class="content">
				# START messages # # INCLUDE messages.VIEW # # END messages #
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{ALERT}
		</div>
	</div>

	<div id="blocks" class="sandbox-title">
		<h2>{@css.blocks}</h2>
	</div>

	<div class="content">1 {@css.blocks.per.line}</div>
	<div class="elements-container">
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.medium}
			</div>
		</article>
	</div>

	<div class="content">2 {@css.blocks.per.line}</div>
	<div class="elements-container columns-2">
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.mini}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.mini}
			</div>
		</article>
	</div>

	<div class="content">3 {@css.blocks.per.line}</div>
	<div class="elements-container columns-3">
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.mini}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.mini}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="thumbnail-item" />
				{@framework.lorem.mini}
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BLOCK}
		</div>
	</div>
	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
