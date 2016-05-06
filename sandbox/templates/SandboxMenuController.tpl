	<header id="header">
		<div id="top-header">
			<div id="site-infos">
				<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url('{HEADER_LOGO}');"# ENDIF #></div>
				<div id="site-name-container">
					<a id="site-name" href="{PATH_TO_ROOT}/">{@css.menu.site.title}</a>
					<span id="site-slogan">{@css.menu.site.slogan}</span>
				</div>
			</div>
			<div id="top-header-content">
				<nav id="horizontal_scrolling_top" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
							</ul>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#horizontal_scrolling_top").menumaker({ title: "{@css.menu.horizontal.top}", format: "multitoggle", breakpoint: 768 }); </script>
			</div>
		</div>
		<div id="sub-header">
			<div id="sub-header-content">
				<nav id="horizontal_sub_header" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin" title="{@css.menu.sub.admin}">{@css.menu.sub.admin}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#horizontal_sub_header").menumaker({ title: "{@css.menu.horizontal.sub.header}", format: "multitoggle", breakpoint: 768 }); </script>
			</div>
			<div class="spacer"></div>
		</div>
		<div class="spacer"></div>
	</header>

	<div id="global">
			
		<aside id="menu-left">				
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@css.menu.vertical.scrolling}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical_scrolling_left" class="cssmenu cssmenu-vertical cssmenu-left">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
								</ul>
							</li>						
						</ul>
					</nav>
					<script>jQuery("#vertical_scrolling_left").menumaker({ title: "{@css.menu.vertical.scrolling.left}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom"></div>					
			</div>
			
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@css.menu.vertical.img}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical_img" class="cssmenu cssmenu-vertical cssmenu-left">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@css.menu.element}" />
									{@css.menu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@css.menu.element}" />
									{@css.menu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@css.menu.element}" />
									{@css.menu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@css.menu.element}" />
									{@css.menu.element}
								</a>
							</li>
													
						</ul>
					</nav>
					<script>jQuery("#vertical_img").menumaker({ title: "{@css.menu.vertical.img}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom"></div>					
			</div>			
		</aside>
		
		<div id="main" class="main-with-left main-with-right" role="main">
				
			<div id="top-content">
				<nav id="horizontal_scrolling" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
							</ul>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#horizontal_scrolling").menumaker({ title: "{@css.menu.horizontal.scrolling}", format: "multitoggle", breakpoint: 768 }); </script>
					
			</div>
				
			<div id="main-content">
					
				<menu id="sandbox_actionslinks" class="cssmenu cssmenu-right cssmenu-actionslinks">
					<ul class="level-0">
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@css.menu.actionslinks.sandbox}">{@css.menu.actionslinks.sandbox}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox" title="{@css.menu.actionslinks.index}">{@css.menu.actionslinks.index}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/css" title="{@css.menu.actionslinks.css}">{@css.menu.actionslinks.css}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/menu" title="{@css.menu.actionslinks.menu}">{@css.menu.actionslinks.menu}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/icons" title="{@css.menu.actionslinks.icons}">{@css.menu.actionslinks.icons}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/table" title="{@css.menu.actionslinks.table}">{@css.menu.actionslinks.table}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/template" title="{@css.menu.actionslinks.template}">{@css.menu.actionslinks.template}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/mail" title="{@css.menu.actionslinks.mail}">{@css.menu.actionslinks.mail}</a>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
								</li>
							</ul>
						</li>				
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>		
					</ul>
				</menu>
				<script>jQuery("#sandbox_actionslinks").menumaker({ title: "{@css.menu.actionslinks}", format: "multitoggle", breakpoint: 768 }); </script>
				
				<nav id="breadcrumb" itemprop="breadcrumb">
					<ol>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a href="{PATH_TO_ROOT}/" title="{@css.menu.breadcrumb.index}" itemprop="url">
								<span itemprop="title">{@css.menu.breadcrumb.index}</span>
							</a>
						</li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a href="{PATH_TO_ROOT}/sandbox" title="{@css.menu.breadcrumb.sandbox}" itemprop="url">
								<span itemprop="title">{@css.menu.breadcrumb.sandbox}</span>
							</a>
						</li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<span itemprop="title">{@css.menu.breadcrumb.cssmenu}</span>
						</li>
					</ol>
				</nav>
				
					
				<br /><br />
				<div class="spacer"></div>			
				
				<header>
					<h2>{@css.menu.h2}</h2>
				</header>
				<article>
					<div class="warning">
						{@css.menu.content}
					</div>
					{@lorem.ipsum}
					<br /><br />
					{@lorem.ipsum}
				</article>	
			</div>
			<div id="bottom-content">
				<nav id="sandbox_group" class="cssmenu cssmenu-group">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#sandbox_group").menumaker({ title: "{@css.menu.group}", format: "multitoggle", breakpoint: 768 }); </script>					
			</div>
		</div>
			
		<aside id="menu-right">				
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@css.menu.vertical.scrolling}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical_scrolling_right" class="cssmenu cssmenu-vertical cssmenu-right">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
									</li>
								</ul>
							</li>						
						</ul>
					</nav>
					<script>jQuery("#vertical_scrolling_right").menumaker({ title: "{@css.menu.vertical.scrolling.right}", format: "multitoggle", breakpoint: 768 }); </script>				
				</div>
				<div class="module-mini-bottom"></div>					
			</div>				
		</aside>
		
		
	</div>
				
	<div id="top-footer">
		<nav id="sandbox_static" class="cssmenu cssmenu-static">
			<ul class="level-0">
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>						
			</ul>
		</nav>
		<script>jQuery("#sandbox_static").menumaker({ title: "{@css.menu.static}", format: "multitoggle", breakpoint: 768, static: true }); </script>				
	</div>
	
	<footer id="footer">
		<div class="footer-content">
			<nav id="sandbox_static_footer" class="cssmenu cssmenu-static">
			<ul class="level-0">
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@css.menu.sub.element}">{@css.menu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@css.menu.element}">{@css.menu.element}</a>
						</li>
					</ul>
				</li>						
			</ul>
		</nav>
		<script>jQuery("#sandbox_static_footer").menumaker({ title: "{@css.menu.static.footer}", format: "multitoggle", breakpoint: 768, static: true }); </script>	
		</div>
	</footer>