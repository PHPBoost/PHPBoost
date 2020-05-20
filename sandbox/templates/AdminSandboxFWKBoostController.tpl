<section id="sandbox-css">
	# INCLUDE SANDBOX_SUB_MENU #
	<header>
		<h1>
			{@sandbox.module.title} - {@title.component}
		</h1>
	</header>
	<div class="sandbox-summary">
      <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </div>
      <ul>
        <li>
			<a class="summary-link" href="#component">{@component.title.component}</a>
			<ul>
				<li><a href="#single-item" class="summary-link">{@component.page.title}</a></li>
				<li><a href="#options" class="summary-link">{@component.options}</a></li>
				<li><a href="#options-infos" class="summary-link">{@component.options}.infos</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#typography">{@component.title.typography}</a>
			<ul>
				<li><a href="#titles" class="summary-link">{@component.titles}</a></li>
				<li><a href="#sizes" class="summary-link">{@component.title.sizes}</a></li>
				<li><a href="#styles" class="summary-link">{@component.styles}</a></li>
				<li><a href="#rank-colors" class="summary-link">{@component.rank.color}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#miscellaneous">{@component.miscellaneous}</a>
			<ul>
				<li><a href="#progress-bar" class="summary-link">{@component.progress_bar}</a></li>
				<li><a href="#icons" class="summary-link">{@component.main_actions_icons}</a></li>
				<li><a href="#explorer" class="summary-link">{@component.explorer}</a></li>
				<li><a href="#lists" class="summary-link">{@component.lists}</a></li>
				<li><a href="#buttons" class="summary-link">{@component.buttons}</a></li>
				<li><a href="#notation" class="summary-link">{@component.notation}</a></li>
				<li><a href="#pagination" class="summary-link">{@component.pagination}</a></li>
				<li><a href="#sortable" class="summary-link">{@component.sortable}</a></li>
				<li><a href="#css-table" class="summary-link">{@component.table}</a></li>
				<li><a href="#messages" class="summary-link">{@component.messages.and.coms}</a></li>
				<li><a href="#alerts" class="summary-link">{@component.alert.messages}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#blocks">{@component.blocks}</a>
		</li>
      </ul>
    </div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<div id="component" class="sandbox-block">
		<h2>{@component.title.component}</h2>
	</div>

	<div class="no-style">
		<article id="single-item" class="block">
			<header>
				<h2>
					<span>{@component.page.title}</span>
				</h2>
				<div class="flex-between">
					<div class="more">{@component.more}</div>
					<span class="controls">
						<a href="#" aria-label="{@component.edit}"><i class="far fa-fw fa-edit" aria-hidden="true" aria-label="{@component.edit}"></i></a>
						<a href="#" aria-label="{@component.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true" aria-label="{@component.delete}"></i></a>
					</span>
				</div>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				<div>{@lorem.large.content}</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PAGE}
		</div>
	</div>

	<div class="no-style">
		<article id="options" class="block">
			<header>
				<h5>{@component.form} {@component.options}</h5>
			</header>
			<div class="content">
				<form class="options">
					<div class="horizontal-fieldset">
					    <span class="horizontal-fieldset-desc">{@component.options.sort_by}</span>
					    <div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select picture-status-constraint">
									<select>
										<option value="{@component.options.sort_by.alphabetical}">{@component.options.sort_by.alphabetical}</option>
										<option value="{@component.options.sort_by.size}">{@component.options.sort_by.size}</option>
										<option value="{@component.options.sort_by.date}">{@component.options.sort_by.date}</option>
										<option value="{@component.options.sort_by.popularity}">{@component.options.sort_by.popularity}</option>
										<option value="{@component.options.sort_by.note}">{@component.options.sort_by.note}</option>
									</select>
									<span class="text-status-constraint" style="display: none;"></span>
								</div>
							</div>
						</div>
						<div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select picture-status-constraint">
									<select>
										<option value="{@component.modules_menus.direction.up}">{@component.modules_menus.direction.up}</option>
										<option value="{@component.modules_menus.direction.down}">{@component.modules_menus.direction.down}</option>
									</select>
									<span class="text-status-constraint" style="display: none;"></span>
								</div>
							</div>
						</div>
				    </div>
				</form>
				<div class="spacer"></div>
				{@lorem.large.content}
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{FORM_OPTION}
		</div>
	</div>

	<div class="no-style">
		<article id="options-infos" class="block">
			<header>
				<h5>{@component.class} {@component.options}.infos</h5>
			</header>
			<div class="content">
				<div class="options infos">
					<div class="align-center">
						<span>
							<img src="{NO_AVATAR_URL}" alt="PHPBoost" itemprop="image">
						</span>
						<div class="spacer"></div>
						<a href="#" class="button alt-button">
							<i class="fa fa-globe" aria-hidden="true"></i> {@component.options.link}
						</a>
						<a href="#" class="button alt-button" aria-label="{@component.options.link}">
							<i class="fa fa-unlink" aria-hidden="true" aria-label="{@component.options.link}"></i>
						</a>
					</div>
					<h6>{@component.options.file.title}</h6>
					<span class="text-strong">{@component.options.option.title} : </span><span>0</span><br />
					<span class="text-strong">{@component.options.option.title} : </span><span><a itemprop="about" class="small" href="#">{@component.options.link}</a></span><br />
					<span> {@component.options.option.com}</span>
					<div class="spacer"></div>
					<div class="align-center">
						<div class="notation" id="notation-1">
							<span class="stars">
								<a href="#" onclick="return false;" class="far star star-hover fa-star" id="star-1-1"></a>
								<a href="#" onclick="return false;" class="far star star-hover fa-star" id="star-1-2"></a>
								<a href="#" onclick="return false;" class="far star star-hover fa-star" id="star-1-3"></a>
								<a href="#" onclick="return false;" class="far star star-hover fa-star" id="star-1-4"></a>
								<a href="#" onclick="return false;" class="far star star-hover fa-star" id="star-1-5"></a>
							</span>
							<span class="notes">
								<span class="number-notes">0</span>
								<span>{@component.options.sort_by.note}</span>
							</span>
						</div>
					</div>
				</div>
				{@lorem.large.content}
			</div>
			<div class="spacer"></div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{DIV_OPTION}
		</div>
	</div>

	<div id="typography" class="sandbox-block">
		<h2>{@component.title.typography}</h2>
	</div>

	<div class="no-style">
		<article id="titles" class="block">
			<header>
				<h5>{@component.titles}</h5>
			</header>
			<div class="content">
				<h1>h1. {@component.title} 1</h1>
				<h2>h2. {@component.title} 2</h2>
				<h3>h3. {@component.title} 3</h3>
				<h4>h4. {@component.title} 4</h4>
				<h5>h5. {@component.title} 5</h5>
				<h6>h6. {@component.title} 6</h6>
			</div>
		</article>
	</div>

	<div class="no-style elements-container columns-3">
		<article id="sizes" class="block">
			<header>
				<h5>{@component.title.sizes}</h5>
			</header>
			<div class="content">
				<span class="smaller">{@component.text.smaller}</span> <br />
				<span class="small">{@component.text.small}</span> <br />
				<span class="big">{@component.text.big}</span> <br />
				<span class="bigger">{@component.text.bigger}</span> <br />
				<span class="biggest">{@component.text.biggest}</span>
			</div>
		</article>
		<article id="styles" class="block">
			<header>
				<h5>{@component.styles}</h5>
			</header>
			<div class="content">
				<strong>{@component.text_bold}</strong><br />
				<em>{@component.text_italic}</em><br />
				<span style="text-decoration: underline;">{@component.text_underline}</span><br />
				<s>{@component.text_strike}</s><br />
				<a href="#">{@component.link}</a>
			</div>
		</article>
		<article id="rank-colors" class="block">
			<header>
				<h5>{@component.rank.color}</h5>
			</header>
			<div class="content">
				<a href="#" class="admin">{@component.admin}</a> <br />
				<a href="#" class="modo">{@component.modo}</a> <br />
				<a href="#" class="member">{@component.member}</a> <br />
			</div>
		</article>
	</div>

	<div id="miscellaneous" class="sandbox-block">
		<h2>{@component.miscellaneous}</h2>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="progress-bar" class="block">
			<header>
				<h5>{@component.progress_bar}</h5>
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
				<h5>{@component.main_actions_icons}</h5>
			</header>
			<div class="content">
				<ul>
					<li>{@component.rss_feed} : <a href="#" class="fa fa-fw fa-rss" aria-label="{@component.rss_feed}"></a></li>
					<li>{@component.edit} : <a href="#" class="far fa-fw fa-edit" aria-label="{@component.edit}"></a></li>
					<li>{@component.delete} : <a href="#" class="far fa-fw fa-trash-alt" aria-label="{@component.delete}"></a></li>
					<li>{@component.delete.confirm} : <a href="#" class="fr fa-fwa fa-trash-alt" data-confirmation="delete-element" aria-label="{@component.delete.confirm}"></a></li>
					<li>{@component.delete.confirm.custom} : <a href="#" class="far fa-fw fa-trash-alt" data-confirmation="{@component.delete.custom_message}" aria-label="{@component.delete.confirm.custom}"></a></li>
				</ul>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PROGRESS_BAR}
		</div>
	</div>

	<div class="no-style">
		<article id="explorer" class="block">
			<header>
				<h5>{@component.explorer}</h5>
			</header>
			<div class="content">
				<div class="explorer">
					<div class="cats">
							<h2>{@component.explorer}</h2>
						<div class="content">
							<ul>
								<li><a id="class_0" href="#"><i class="fa fa-folder"></i>{@component.root}</a>
									<ul>
										<li class="sub"><a id="class_1" href="#"><i class="fa fa-folder"></i>{@component.cat} 1</a><span id="cat_1"></span></li>
										<li class="sub">
											<a class="parent" href="#">
												<span class="far fa-minus-square" id="img2_2"></span><span class="fa fa-folder-open" id="img_2"></span>
											</a>
											<a class="selected" id="class_2" href="#">{@component.cat} 2</a>
											<ul>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@component.cat} 3</a></li>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@component.cat} 4</a></li>
											</ul>
											<span id="cat_2"></span>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
					<div class="files">
							<h2>{@component.tree}</h2>
						<div class="content" id="cat_contents">
							<ul>
								<li><a href="#"><i class="fa fa-folder"></i>{@component.cat} 3</a></li>
								<li><a href="#"><i class="fa fa-folder"></i>{@component.cat} 4</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@component.file} 1</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@component.file} 2</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{EXPLORER}
		</div>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="lists" class="block">
			<header>
				<h5>{@component.lists}</h5>
			</header>
			<div class="content">
				<ul>
					<li>{@component.element} 1
						<ul>
							<li>{@component.element}</li>
							<li>{@component.element}</li>
						</ul>
					</li>
					<li>{@component.element} 2</li>
					<li>{@component.element} 3</li>
				</ul>

				<ol>
					<li>{@component.element} 1
						<ol>
							<li>{@component.element}</li>
							<li>{@component.element}</li>
						</ol>
					</li>
					<li>{@component.element} 2</li>
					<li>{@component.element} 3</li>
				</ol>
			</div>
		</article>
		<article id="buttons" class="block">
			<header>
				<h5>{@component.buttons}</h5>
			</header>
			<div class="content">
				<button type="submit" class="button">{@component.buttons}</button>
				<button type="submit" class="button submit">.submit</button><br />
				<button type="submit" class="button small">.small</button><br />
				<button type="submit" class="button alt-button">.alt-button</button><br />
				<button type="submit" class="button alt-button">.alt-button.alt</button>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BUTTON}
		</div>
	</div>

	<div class="no-style elements-container columns-2">
		<article id="notation" class="block">
			<header>
				<h5>{@component.notation}</h5>
			</header>
			<div class="content">{@component.notation.possible.values}
				<div class="notation">
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-100"></span></a> <!-- 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-75"></span></a>  <!-- 0.75 à 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-50"></span></a>  <!-- 0.5 à 0.75-->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-25"></span></a>  <!-- 0.05 à 0.5 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-0"></span></a>   <!-- 0 à 0.10 -->
				</div>
			</div>
			<div class="content">{@component.notation.example}
				<div class="notation">
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-100"></span></a>
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-100"></span></a>
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-25"></span></a>
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-0"></span></a>
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-0"></span></a>
				</div>
			</div>
		</article>
		<article id="pagination" class="block">
			<header>
				<h5>{@component.pagination}</h5>
			</header>
			<div class="content">
				# INCLUDE PAGINATION #
			</div>
		</article>
	</div>

	<div class="no-style">
		<article id="sortable" class="block">
			<header>
				<h5>{@component.sortable}</h5>
			</header>
			<div class="content">
				<ul class="sortable-block">
					<li class="sortable-element">
						<div class="sortable-selector" aria-label="{@component.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@component.static.sortable}</a></span>
						</div>
					</li>
					<li class="sortable-element dragged" style="position: relative;">
						<div class="sortable-selector" aria-label="{@component.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@component.moving.sortable}</a></span>
						</div>
					</li>
					<li>
						<div class="dropzone">{@component.dropzone}</div>
					</li>
				</ul>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{SORTABLE}
		</div>
	</div>

	<div class="no-style">
		<article id="css-table" class="block">
			<header>
				<h5>{@component.table}</h5>
			</header>
			<div class="content">
				<table class="table">
					<caption>
						{@component.table.caption}
					</caption>
					<thead>
						<tr>
							<th>
								<a href="#" class="fa fa-arrow-up" aria-label="{@component.table.sort.up}"></a>
								{@component.table.name} title
								<a href="#" class="fa fa-arrow-down" aria-label="{@component.table.sort.down}"></a>
							</th>
							<th>{@component.table.description} title</th>
							<th>{@component.table.author} title</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"># INCLUDE PAGINATION #</td>
						</tr>
					</tfoot>
				</table>

				<table class="table-no-header">
					<caption>
						{@component.table.caption.no.header}
					</caption>
					<tbody>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
						<tr>
							<td>{@component.table.test}</td>
							<td>{@component.table.description}</td>
							<td>{@component.table.author}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"># INCLUDE PAGINATION #</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{TABLE}
		</div>
	</div>

	<div class="no-style">

		<article id="messages" class="block">
			<header>
				<h5>{@component.messages.and.coms}</h5>
			</header>
			<div class="content">
				<article id="comID" class="message-container" itemscope="itemscope" itemtype="http://schema.org/Comment">
			        <header class="message-header-container">
			            <img class="message-user-avatar" src="{NO_AVATAR_URL}" alt="Text">
			            <div class="message-header-infos">
				            <div class="message-user-infos hidden-small-screens">
				                <div></div>
								<div class="message-user-links">
									<a href="#" class="button alt-button">MP</a>
									<a href="#" class="button alt-button">Facebook</a>
									<a href="#" class="button alt-button">Twitter</a>
									<a href="#" class="button alt-button"><i class="far fa-envelope"></i></a>
								</div>
							</div>
			                <div class="message-user">
			                    <h3 class="message-user-pseudo">
			                        <a class="Level" href="UrlProfil" itemprop="author">{@component.messages.login}</a>
			                    </h3>
			                    <div class="message-actions">
			                        <a href="UrlAction" aria-label="ActionName"><i class="far fa-fw fa-edit"></i></a>
			                        <a href="UrlAction" aria-label="ActionName"><i class="far fa-fw fa-trash-alt" data-confirmation="delete-element"></i></a>
			                    </div>
			                </div>
			                <div class="message-infos">
			                    <time datetime="{@component.messages.date}" itemprop="datePublished">{@component.messages.date}</time>
			                    <a href="#ID" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">\#ID</a>
			                </div>
			            </div>
			        </header>
			        <div class="message-content">
			            {@component.messages.content}
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi autem sequi quam ab amet culpa nobis vitae rerum laborum nulla!</p>
			        </div>
			        <footer class="message-footer-container">
			            <div class="message-user-assoc">
							<div></div>
							<div class="message-user-rank">
								<p>{@component.messages.level}</p>
				                <p class="message-group-level">
									<i class="far fa-star"></i>
					                <i class="far fa-star"></i>
					                <i class="far fa-star"></i>
					                <i class="far fa-star"></i>
					                <i class="far fa-star"></i>
								</p>
							</div>
			            </div>
			            <div class="message-user-management">
			                <div></div>
			                <div class="message-moderation-level">0% <i class="fa fa-exclamation-triangle"></i> <i class="fa fa-user-lock"></i></div>
			            </div>
			        </footer>
				</article>
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{MESSAGE}
		</div>
	</div>

	<div id="alerts" class="no-style">
		<article class="block">
			<header>
				<h5>{@component.alert.messages}</h5>
			</header>
			<div class="content">
				# START messages # # INCLUDE messages.VIEW # # END messages #
				# INCLUDE FLOATING_MESSAGES # # INCLUDE FLOATING_SUCCESS # # INCLUDE FLOATING_NOTICE # # INCLUDE FLOATING_WARNING # # INCLUDE FLOATING_ERROR #
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{ALERT}
		</div>
	</div>

	<div id="blocks" class="sandbox-block">
		<h2>{@component.blocks}</h2>
	</div>

	<div class="content">1 {@component.blocks.per.line}</div>
	<div class="elements-container">
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.medium.content}
			</div>
		</article>
	</div>

	<div class="content">2 {@component.blocks.per.line}</div>
	<div class="elements-container columns-2">
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
	</div>

	<div class="content">3 {@component.blocks.per.line}</div>
	<div class="elements-container columns-3">
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@component.block.title}</h2>
			</header>
			<div class="content">
				<img src="{NO_AVATAR_URL}" alt="{@component.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
	</div>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{BLOCK}
		</div>
	</div>
	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
<script>jQuery('#sandbox-css article a').on('click', function(){return false;});</script>
