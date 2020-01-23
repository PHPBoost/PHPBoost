	<header id="header">
		<div id="top-header">
			<div id="site-infos">
				<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
				<div id="site-name-container">
					<a id="site-name" href="{PATH_TO_ROOT}/">{SITE_NAME}</a>
					<span id="site-slogan">{SITE_SLOGAN}</span>
				</div>
			</div>
			<div id="top-header-content">
				<nav id="horizontal-scrolling-top" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#">
								<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />{@cssmenu.element}
							</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">
										<img src="{PATH_TO_ROOT}/templates/base/theme/images/logo.png" alt="{@cssmenu.element}" />{@cssmenu.long.element}
									</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#"><img src="{PATH_TO_ROOT}/sandbox/sandbox.png" alt="{@cssmenu.element}" />{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#">
												<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />{@cssmenu.element}
											</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#">
														<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />{@cssmenu.element}
													</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">
														<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />{@cssmenu.element}
													</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">
														<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />{@cssmenu.element}
													</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
				<script>jQuery("#horizontal-scrolling-top").menumaker({ title: "{@cssmenu.horizontal.top}", format: "multitoggle", breakpoint: 768 }); </script>
			</div>
		</div>
		<div id="sub-header">
			<div id="sub-header-content">
				<nav id="horizontal-sub-header" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li class="has-sub">
							<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin">{@cssmenu.sub.admin}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
				<script>jQuery("#horizontal-sub-header").menumaker({ title: "{@cssmenu.horizontal.sub.header}", format: "multitoggle", breakpoint: 768 }); </script>
			</div>
			<div class="spacer"></div>
		</div>
		<div class="spacer"></div>
	</header>

	<div id="global">

		<aside id="menu-left" class="narrow-menu-left">
			<div class="module-mini-container cssmenu-content">
				<div class="module-mini-top hidden-small-screens">
					<div class="sub-title">{@cssmenu.vertical.scrolling}</div>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-scrolling-left" class="cssmenu cssmenu-vertical cssmenu-left cssmenu-with-submenu">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.long.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.long.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
					<script>jQuery("#vertical-scrolling-left").menumaker({ title: "{@cssmenu.vertical.scrolling.left}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom hidden-small-screens"></div>
			</div>

			<div class="module-mini-container cssmenu-content">
				<div class="module-mini-top hidden-small-screens">
					<div class="sub-title">{@cssmenu.vertical.img}</div>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-img" class="cssmenu cssmenu-vertical cssmenu-left">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>

						</ul>
					</nav>
					<script>jQuery("#vertical-img").menumaker({ title: "{@cssmenu.vertical.img}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom hidden-small-screens"></div>
			</div>
		</aside>

		<div id="main" class="main-with-left main-with-right" role="main">

			<div id="top-content">
				<nav id="horizontal-scrolling" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
				<script>jQuery("#horizontal-scrolling").menumaker({ title: "{@cssmenu.horizontal.scrolling}", format: "multitoggle", breakpoint: 768 }); </script>

			</div>

			<div id="main-content">

				<nav id="sandbox-actionslinks" class="cssmenu cssmenu-right cssmenu-actionslinks">
					<ul class="level-0">
						<li class="has-sub">
							<span class="cssmenu-title" href="#">{@cssmenu.actionslinks.sandbox}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox">{@cssmenu.actionslinks.index}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/admin/config">{@mini.config}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/form">{@cssmenu.actionslinks.form}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/css">{@cssmenu.actionslinks.css}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/bbcode">{@cssmenu.actionslinks.bbcode}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/menu">{@cssmenu.actionslinks.menu}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/icons">{@cssmenu.actionslinks.icons}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/table">{@cssmenu.actionslinks.table}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/mail">{@cssmenu.actionslinks.mail}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/template">{@cssmenu.actionslinks.template}</a>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.long.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
					</ul>
				</nav>
				<script>jQuery("#sandbox-actionslinks").menumaker({ title: "{@cssmenu.actionslinks}", format: "multitoggle", breakpoint: 768 }); </script>

				<nav id="breadcrumb" itemprop="breadcrumb">
					<ol itemscope itemtype="http://schema.org/BreadcrumbList">
						<li itemprop="itemListElement" itemscope itemtype="http://data-vocabulary.org/ListItem">
							<a href="{PATH_TO_ROOT}/" itemprop="item">
								<span itemprop="name">{@cssmenu.breadcrumb.index}</span>
								<meta itemprop="position" content="1" />
							</a>
						</li>
						<li itemprop="itemListElement" itemscope itemtype="http://data-vocabulary.org/ListItem">
							<a href="{PATH_TO_ROOT}/sandbox" itemprop="item">
								<span itemprop="name">{@cssmenu.breadcrumb.sandbox}</span>
								<meta itemprop="position" content="2" />
							</a>
						</li>
						<li itemprop="itemListElement" itemscope itemtype="http://data-vocabulary.org/ListItem">
							<span itemprop="title">{@cssmenu.breadcrumb.cssmenu}</span>
							<meta itemprop="position" content="3" />
						</li>
					</ol>
				</nav>

				<div class="spacer"></div>
				<section>
					<header>
						<h1>{@cssmenu.h1}</h1>
					</header>
					<div class="elements-container">
						<article>
							<header>
								<h2>{@cssmenu.h2}</h2>
							</header>
							<nav id="sandbox-group" class="cssmenu cssmenu-group">
								<ul class="level-0">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li class="current">
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
								</ul>
							</nav>
							<div class="content">
								<script>jQuery("#sandbox-group").menumaker({ title: "{@cssmenu.group}", format: "multitoggle", breakpoint: 768 }); </script>
								<div class="message-helper warning">
									{@cssmenu.warning}
								</div>
								<p>{@framework.lorem.large}</p>
								<p>{@framework.lorem.medium}</p>
							</div>
							<footer></footer>
						</article>
					</div>
					<footer></footer>
				</section>

			</div>

			<div id="bottom-content">

			</div>
		</div>

		<aside id="menu-right" class="narrow-menu-right">
			<div class="module-mini-container cssmenu-content">
				<div class="module-mini-top hidden-small-screens">
					<div class="sub-title">{@cssmenu.vertical.scrolling}</div>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-scrolling-right" class="cssmenu cssmenu-vertical cssmenu-right cssmenu-with-submenu">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
					<script>jQuery("#vertical-scrolling-right").menumaker({ title: "{@cssmenu.vertical.scrolling.right}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom hidden-small-screens"></div>
			</div>
		</aside>


	</div>

	<footer id="footer">

		<div id="top-footer">
			<nav id="sandbox-static" class="cssmenu cssmenu-static">
				<ul class="level-0">
					<li class="has-sub">
						<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li class="has-sub">
								<span class="cssmenu-title">{@cssmenu.sub.element}</span>
								<ul class="level-2">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title">{@cssmenu.sub.element}</span>
								<ul class="level-2">
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>
						</ul>
					</li>
					<li class="has-sub">
						<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
						</ul>
					</li>
					<li class="has-sub">
						<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
							</li>
						</ul>
					</li>
				</ul>
			</nav>
			<script>jQuery("#sandbox-static").menumaker({ title: "{@cssmenu.static}", format: "multitoggle", breakpoint: 768, static: true }); </script>
		</div>

		<div class="footer-content">
			<nav id="sandbox-static-footer" class="cssmenu cssmenu-static">
			<ul class="level-0">
				<li class="has-sub">
					<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
		<script>jQuery("#sandbox-static-footer").menumaker({ title: "{@cssmenu.static.footer}", format: "multitoggle", breakpoint: 768, static: true }); </script>
		</div>
	</footer>
