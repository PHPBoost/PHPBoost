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
			<a class="summary-link" href="#fwkboost">{@css.title.fwkboost}</a>
			<ul>
				<li><a href="#single-item" class="summary-link">{@css.page.title}</a></li>
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
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>


	# INCLUDE TYPOGRAPHY #
	# INCLUDE COLOR #

	<div id="media" class="sandbox-block">
		<h2>{@css.title.media}</h2>
	</div>

	<article id="image">
		<header>
			<h5>{@css.image}</h5>
		</header>
		<div class="content">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@css.image}" />
			<span class="message-helper bgc error">[ajouter figure/img]</span>
		</div>
	</article>
	<article id="lightbox">
		<header>
			<h5>{@css.lightbox}</h5>
		</header>
		<div class="content">
			<a href="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/theme.jpg" alt="{@css.lightbox}" />
			</a>
			<a href="{PATH_TO_ROOT}/templates/default/theme/images/admin.jpg" data-lightbox="formatter" data-rel="lightcase:collection">
				<img style="max-width: 150px" src="{PATH_TO_ROOT}/templates/default/theme/images/admin.jpg" alt="{@css.lightbox}" />
			</a>
		</div>
	</article>

	<article id="youtube">
		<header>
			<h5>{@css.youtube}</h5>
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
			<h5>{@css.movie}</h5>
		</header>
		<div class="media-content">
			<video class="video-player" controls="">
				<source src="http://data.babsoweb.com/private/logo-pbt.mp4" type="video/mp4" />
			</video>
		</div>
	</article>

	<article id="audio">
		<header>
			<h5>{@css.audio}</h5>
		</header>
		<div class="content">
			<audio class="audio-player" controls>
				<source src="http://data.babsoweb.com/babsodata/tom/herbiestyle.mp3" />
			</audio>
		</div>
	</article>
	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{MEDIA}
		</div>
	</div>

	<div id="miscellaneous" class="sandbox-block">
		<h2>{@css.miscellaneous}</h2>
	</div>

	<article id="progress-bar">
		<header>
			<h5>{@css.progress_bar}</h5>
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
								<li><a id="class_0" href="#"><i class="fa fa-folder"></i>{@css.root}</a>
									<ul>
										<li class="sub"><a id="class_1" href="#"><i class="fa fa-folder"></i>{@css.cat} 1</a><span id="cat_1"></span></li>
										<li class="sub">
											<a class="parent" href="#">
												<span class="far fa-minus-square" id="img2_2"></span><span class="fa fa-folder-open" id="img_2"></span>
											</a>
											<a class="selected" id="class_2" href="#">{@css.cat} 2</a>
											<ul>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@css.cat} 3</a></li>
												<li class="sub"><a href="#"><i class="fa fa-folder"></i>{@css.cat} 4</a></li>
											</ul>
											<span id="cat_2"></span>
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
								<li><a href="#"><i class="fa fa-folder"></i>{@css.cat} 3</a></li>
								<li><a href="#"><i class="fa fa-folder"></i>{@css.cat} 4</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@css.file} 1</a></li>
								<li><a href="#"><i class="fa fa-file"></i>{@css.file} 2</a></li>
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
				<button type="submit" class="button submit">.submit</button><br />
				<button type="submit" class="button small">.small</button><br />
				<button type="submit" class="button alt-button">.alt-button</button><br />
				<button type="submit" class="button alt-button">.alt-button.alt</button>
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
			<div class="content">{@css.notation.possible.values}
				<div class="notation">
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-100"></span></a> <!-- 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-75"></span></a>  <!-- 0.75 à 1 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-50"></span></a>  <!-- 0.5 à 0.75-->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-25"></span></a>  <!-- 0.05 à 0.5 -->
					<a href="#" onclick="return false;" class="far star fa-star"><span class="star-width star-width-0"></span></a>   <!-- 0 à 0.10 -->
				</div>
			</div>
			<div class="content">{@css.notation.example}
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
						<div class="sortable-selector" aria-label="{@css.sortable.move}"></div>
						<div class="sortable-title">
							<span><a>{@css.static.sortable}</a></span>
						</div>
					</li>
					<li class="sortable-element dragged" style="position: relative;">
						<div class="sortable-selector" aria-label="{@css.sortable.move}"></div>
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

	# INCLUDE TABLE #

	# INCLUDE MESSAGE_HELPER #


	<div id="blocks" class="sandbox-block">
		<h2>{@css.blocks}</h2>
	</div>

	<div class="content">1 {@css.blocks.per.line}</div>
	<div class="elements-container">
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.medium.content}
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
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.short.content}
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
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.short.content}
			</div>
		</article>
		<article class="block">
			<header>
				<h2>{@css.block.title}</h2>
			</header>
			<div class="content">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@css.picture}" class="item-thumbnail" />
				{@lorem.short.content}
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
<script>jQuery('#sandbox-css article a').on('click', function(){return false;});</script>
