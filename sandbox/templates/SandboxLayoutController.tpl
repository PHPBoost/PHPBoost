<section id="sandbox-css">
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>
			{@sandbox.module.title} - {@title.fwkboost}
		</h1>
	</header>
	<div class="sandbox-summary">
      <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </div>
      <ul>
        <li>
			<a class="summary-link" href="#fwkboost">{@fwkboost.title.fwkboost}</a>
			<ul>
				<li><a href="#single-item" class="summary-link">{@fwkboost.page.title}</a></li>
				<li><a href="#options" class="summary-link">{@fwkboost.options}</a></li>
				<li><a href="#options-infos" class="summary-link">{@fwkboost.options}.infos</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#typography">{@fwkboost.title.typography}</a>
			<ul>
				<li><a href="#titles" class="summary-link">{@fwkboost.titles}</a></li>
				<li><a href="#sizes" class="summary-link">{@fwkboost.title.sizes}</a></li>
				<li><a href="#styles" class="summary-link">{@fwkboost.styles}</a></li>
				<li><a href="#rank-colors" class="summary-link">{@fwkboost.rank.color}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#miscellaneous">{@fwkboost.miscellaneous}</a>
			<ul>
				<li><a href="#progress-bar" class="summary-link">{@fwkboost.progress_bar}</a></li>
				<li><a href="#icons" class="summary-link">{@fwkboost.main_actions_icons}</a></li>
				<li><a href="#explorer" class="summary-link">{@fwkboost.explorer}</a></li>
				<li><a href="#lists" class="summary-link">{@fwkboost.lists}</a></li>
				<li><a href="#buttons" class="summary-link">{@fwkboost.buttons}</a></li>
				<li><a href="#notation" class="summary-link">{@fwkboost.notation}</a></li>
				<li><a href="#pagination" class="summary-link">{@fwkboost.pagination}</a></li>
				<li><a href="#sortable" class="summary-link">{@fwkboost.sortable}</a></li>
				<li><a href="#css-table" class="summary-link">{@fwkboost.table}</a></li>
				<li><a href="#messages" class="summary-link">{@fwkboost.messages.and.coms}</a></li>
				<li><a href="#alerts" class="summary-link">{@fwkboost.alert.messages}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#blocks">{@fwkboost.blocks}</a>
		</li>
      </ul>
    </div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<div id="fwkboost" class="sandbox-block">
		<h2>{@fwkboost.title.fwkboost}</h2>
	</div>

	<article id="single-item">
		<header>
			<h2>
				<span>{@fwkboost.page.title}</span>
			</h2>
			<div class="flex-between">
				<div class="more">{@fwkboost.more}</div>
				<span class="controls">
					<a href="#" aria-label="{@fwkboost.edit}"><i class="far fa-fw fa-edit" aria-hidden="true" aria-label="{@fwkboost.edit}"></i></a>
					<a href="#" aria-label="{@fwkboost.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true" aria-label="{@fwkboost.delete}"></i></a>
				</span>
			</div>
		</header>
		<div class="content">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
			<div>{@lorem.large.content}</div>
		</div>
	</article>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PAGE}
		</div>
	</div>

	<article id="options">
		<header>
			<h5>{@fwkboost.form} {@fwkboost.options}</h5>
		</header>
		<div class="content">
			<form class="options">
				<div class="horizontal-fieldset">
				    <span class="horizontal-fieldset-desc">{@fwkboost.options.sort_by}</span>
				    <div class="horizontal-fieldset-element">
						<div class="form-element">
							<div class="form-field form-field-select picture-status-constraint">
								<select>
									<option value="{@fwkboost.options.sort_by.alphabetical}">{@fwkboost.options.sort_by.alphabetical}</option>
									<option value="{@fwkboost.options.sort_by.size}">{@fwkboost.options.sort_by.size}</option>
									<option value="{@fwkboost.options.sort_by.date}">{@fwkboost.options.sort_by.date}</option>
									<option value="{@fwkboost.options.sort_by.popularity}">{@fwkboost.options.sort_by.popularity}</option>
									<option value="{@fwkboost.options.sort_by.note}">{@fwkboost.options.sort_by.note}</option>
								</select>
								<span class="text-status-constraint" style="display: none;"></span>
							</div>
						</div>
					</div>
					<div class="horizontal-fieldset-element">
						<div class="form-element">
							<div class="form-field form-field-select picture-status-constraint">
								<select>
									<option value="{@fwkboost.modules_menus.direction.up}">{@fwkboost.modules_menus.direction.up}</option>
									<option value="{@fwkboost.modules_menus.direction.down}">{@fwkboost.modules_menus.direction.down}</option>
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
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{FORM_OPTION}
		</div>
	</div>

	<article id="options-infos">
		<header>
			<h5>{@fwkboost.class} {@fwkboost.options}.infos</h5>
		</header>
		<div class="content">
			<div class="options infos">
				<div class="align-center">
					<span>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image">
					</span>
					<div class="spacer"></div>
					<a href="#" class="button alt-button">
						<i class="fa fa-globe" aria-hidden="true"></i> {@fwkboost.options.link}
					</a>
					<a href="#" class="button alt-button" aria-label="{@fwkboost.options.link}">
						<i class="fa fa-unlink" aria-hidden="true" aria-label="{@fwkboost.options.link}"></i>
					</a>
				</div>
				<h6>{@fwkboost.options.file.title}</h6>
				<span class="text-strong">{@fwkboost.options.option.title} : </span><span>0</span><br />
				<span class="text-strong">{@fwkboost.options.option.title} : </span><span><a itemprop="about" class="small" href="#">{@fwkboost.options.link}</a></span><br />
				<span> {@fwkboost.options.option.com}</span>
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
							<span>{@fwkboost.options.sort_by.note}</span>
						</span>
					</div>
				</div>
			</div>
			{@lorem.large.content}
		</div>
		<div class="spacer"></div>
	</article>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{DIV_OPTION}
		</div>
	</div>

	<div id="typography" class="sandbox-block">
		<h2>{@fwkboost.title.typography}</h2>
	</div>

	<article id="titles">
		<header>
			<h5>{@fwkboost.titles}</h5>
		</header>
		<div class="content">
			<h1>h1. {@fwkboost.title} 1</h1>
			<h2>h2. {@fwkboost.title} 2</h2>
			<h3>h3. {@fwkboost.title} 3</h3>
			<h4>h4. {@fwkboost.title} 4</h4>
			<h5>h5. {@fwkboost.title} 5</h5>
			<h6>h6. {@fwkboost.title} 6</h6>
		</div>
	</article>

	<article id="sizes">
		<header>
			<h5>{@fwkboost.title.sizes}</h5>
		</header>
		<div class="content">
			<span class="smallest">{@fwkboost.text.smallest}</span> <br />
			<span class="smaller">{@fwkboost.text.smaller}</span> <br />
			<span class="small">{@fwkboost.text.small}</span> <br />
			<span class="normal">{@fwkboost.text}</span> <br />
			<span class="big">{@fwkboost.text.big}</span> <br />
			<span class="bigger">{@fwkboost.text.bigger}</span> <br />
			<span class="biggest">{@fwkboost.text.biggest}</span>
		</div>
	</article>
	<article id="styles">
		<header>
			<h5>{@fwkboost.styles}</h5>
		</header>
		<div class="content">
			<strong>{@fwkboost.text_bold}</strong><br />
			<em>{@fwkboost.text_italic}</em><br />
			<span style="text-decoration: underline;">{@fwkboost.text_underline}</span><br />
			<s>{@fwkboost.text_strike}</s><br />
			<a href="#">{@fwkboost.link}</a>
		</div>
	</article>
	<article id="rank-colors">
		<header>
			<h5>{@fwkboost.rank.color}</h5>
		</header>
		<div class="content">
			<a href="#" class="administrator">{@fwkboost.admin}</a> <br />
			<a href="#" class="moderator">{@fwkboost.modo}</a> <br />
			<a href="#" class="member">{@fwkboost.member}</a> <br />
			<a href="#" class="visitor">{@fwkboost.visitor}</a> <br />
			<a href="#" class="custom-author">{@fwkboost.custom.author}</a> <br />
		</div>
	</article>

	<div id="media" class="sandbox-block">
		<h2>{@fwkboost.title.media}</h2>
	</div>

	<article id="image">
		<header>
			<h5>{@fwkboost.image}</h5>
		</header>
		<div class="content">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@fwkboost.image}" />
			<span class="message-helper bgc error">[ajouter figure/img]</span>
		</div>
	</article>
	<article id="lightbox">
		<header>
			<h5>{@fwkboost.lightbox}</h5>
		</header>
		<div class="content">
			<a href="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@fwkboost.lightbox}" />
			</a>
			<a href="{PATH_TO_ROOT}/templates/default/theme/images/admin.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/default/theme/images/admin.jpg" alt="{@fwkboost.lightbox}" />
			</a>
		</div>
	</article>

	<article id="youtube">
		<header>
			<h5>{@fwkboost.youtube}</h5>
		</header>
		<div class="media-content">
			<iframe class="youtube-player" src="https://www.youtube.com/embed/YE7VzlLtp-4" allowfullscreen="" width="100%" height="185"></iframe>
			<!-- <video class="youtube-player" controls="" src="http://www.youtubeinmp4.com/redirect.php?video=YE7VzlLtp-4">
				<source src="https://youtu.be/YE7VzlLtp-4" type="video/mp4" />
			</video> -->
		</div>
	</article>
	<article id="movie">
		<header>
			<h5>{@fwkboost.movie}</h5>
		</header>
		<div class="media-content">
			<video class="video-player" controls="">
				<source src="http://data.babsoweb.com/private/logo-pbt.mp4" type="video/mp4" />
			</video>
		</div>
	</article>

	<article id="audio">
		<header>
			<h5>{@fwkboost.audio}</h5>
		</header>
		<div class="content">
			<audio class="audio-player" controls>
				<source src="http://data.babsoweb.com/babsodata/tom/herbiestyle.mp3" />
			</audio>
		</div>
	</article>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{MEDIA}
		</div>
	</div>

	<div id="miscellaneous" class="sandbox-block">
		<h2>{@fwkboost.miscellaneous}</h2>
	</div>

	<article id="progress-bar">
		<header>
			<h5>{@fwkboost.progress_bar}</h5>
		</header>
		<span class="message-helper bgc warning">[Ajouter role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"]</span>
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
				<h5>{@fwkboost.explorer}</h5>
			</header>
			<div class="content">
				<div class="explorer">
					<div class="cats">
							<h2>{@fwkboost.explorer}</h2>
						<div class="content">
							<ul>
								<li><a id="class_0" href="#"><i class="fa fa-folder"></i>{@fwkboost.root}</a>
									<ul>
										<li class="sub"><a id="class_1" href="#"><i class="fa fa-folder"></i>{@fwkboost.cat} 1</a><span id="cat_1"></span></li>
										<li class="sub">
											<a class="parent" href="#">
												<span class="far fa-minus-square" id="img2_2"></span><span class="fa fa-folder-open" id="img_2"></span>
											</a>
											<a class="selected" id="class_2" href="#">{@fwkboost.cat} 2</a>
											<ul>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@fwkboost.cat} 3</a></li>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@fwkboost.cat} 4</a></li>
											</ul>
											<span id="cat_2"></span>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
					<div class="files">
							<h2>{@fwkboost.tree}</h2>
						<div class="content" id="cat_contents">
							<ul>
								<li><a href="#"><i class="fa fa-folder"></i>{@fwkboost.cat} 3</a></li>
								<li><a href="#"><i class="fa fa-folder"></i>{@fwkboost.cat} 4</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@fwkboost.file} 1</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@fwkboost.file} 2</a></li>
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
				<h5>{@fwkboost.lists}</h5>
			</header>
			<div class="content">
				<ul>
					<li>{@fwkboost.element} 1
						<ul>
							<li>{@fwkboost.element}</li>
							<li>{@fwkboost.element}</li>
						</ul>
					</li>
					<li>{@fwkboost.element} 2</li>
					<li>{@fwkboost.element} 3</li>
				</ul>

				<ol>
					<li>{@fwkboost.element} 1
						<ol>
							<li>{@fwkboost.element}</li>
							<li>{@fwkboost.element}</li>
						</ol>
					</li>
					<li>{@fwkboost.element} 2</li>
					<li>{@fwkboost.element} 3</li>
				</ol>
			</div>
		</article>
		<article id="buttons" class="block">
			<header>
				<h5>{@fwkboost.buttons}</h5>
			</header>
			<div class="content">
				<button type="submit" class="button">{@fwkboost.buttons}</button>
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
				<h5>{@fwkboost.notation}</h5>
			</header>
			<div class="content">{@fwkboost.notation.possible.values}
				<div class="notation">
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-100"></span></a> <!-- 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-75"></span></a>  <!-- 0.75 à 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-50"></span></a>  <!-- 0.5 à 0.75-->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-25"></span></a>  <!-- 0.05 à 0.5 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-0"></span></a>   <!-- 0 à 0.10 -->
				</div>
			</div>
			<div class="content">{@fwkboost.notation.example}
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
				<h5>{@fwkboost.pagination}</h5>
			</header>
			<div class="content">
				# INCLUDE PAGINATION #
			</div>
		</article>
	</div>

	<div class="no-style">
		<article id="sortable" class="block">
			<header>
				<h5>{@fwkboost.sortable}</h5>
			</header>
			<div class="content">
				<ul class="sortable-block">
					<li class="sortable-element">
						<div class="sortable-selector" aria-label="{@fwkboost.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@fwkboost.static.sortable}</a></span>
						</div>
					</li>
					<li class="sortable-element dragged" style="position: relative;">
						<div class="sortable-selector" aria-label="{@fwkboost.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@fwkboost.moving.sortable}</a></span>
						</div>
					</li>
					<li>
						<div class="dropzone">{@fwkboost.dropzone}</div>
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

	# INCLUDE TABLE #

	# INCLUDE MESSAGE #

	# INCLUDE MESSAGE_HELPER #


	<div id="blocks" class="sandbox-block">
		<h2>{@fwkboost.blocks}</h2>
	</div>

	<div class="content">1 {@fwkboost.blocks.per.line}</div>
	<div class="elements-container">
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
				{@lorem.medium.content}
			</div>
		</article>
	</div>

	<div class="content">2 {@fwkboost.blocks.per.line}</div>
	<div class="elements-container columns-2">
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
	</div>

	<div class="content">3 {@fwkboost.blocks.per.line}</div>
	<div class="elements-container columns-3">
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@fwkboost.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@fwkboost.picture}" class="item-thumbnail" />
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
