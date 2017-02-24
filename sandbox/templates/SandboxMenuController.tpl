	<header id="header">
		<div id="top-header">
			<div id="site-infos">
				<div id="site-logo" # IF C_HEADER_LOGO #style="background-image: url('{HEADER_LOGO}');"# ENDIF #></div>
				<div id="site-name-container">
					<a id="site-name" href="{PATH_TO_ROOT}/">{@cssmenu.site.title}</a>
					<span id="site-slogan">{@cssmenu.site.slogan}</span>
				</div>
			</div>
			<div id="top-header-content">
				<nav id="horizontal-scrolling-top" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
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
							<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="cssmenu-title" href="{PATH_TO_ROOT}/admin" title="{@cssmenu.sub.admin}">{@cssmenu.sub.admin}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
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
			
		<aside id="menu-left">				
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@cssmenu.vertical.scrolling}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-scrolling-left" class="cssmenu cssmenu-vertical cssmenu-left cssmenu-with-submenu">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>						
						</ul>
					</nav>
					<script>jQuery("#vertical-scrolling-left").menumaker({ title: "{@cssmenu.vertical.scrolling.left}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom"></div>					
			</div>
			
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@cssmenu.vertical.img}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-img" class="cssmenu cssmenu-vertical cssmenu-left">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">
									<img src="{PATH_TO_ROOT}/sandbox/sandbox_mini.png" alt="{@cssmenu.element}" />
									{@cssmenu.element}
								</a>
							</li>
													
						</ul>
					</nav>
					<script>jQuery("#vertical-img").menumaker({ title: "{@cssmenu.vertical.img}", format: "multitoggle", breakpoint: 768 }); </script>
				</div>
				<div class="module-mini-bottom"></div>					
			</div>			
		</aside>
		
		<div id="main" class="main-with-left main-with-right" role="main">
				
			<div id="top-content">
				<nav id="horizontal-scrolling" class="cssmenu cssmenu-horizontal">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li class="has-sub">
									<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
									<ul class="level-2">
										<li>
											<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
										</li>
										<li class="has-sub">
											<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
											<ul class="level-3">
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
												<li>
													<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#horizontal-scrolling").menumaker({ title: "{@cssmenu.horizontal.scrolling}", format: "multitoggle", breakpoint: 768 }); </script>
					
			</div>
				
			<div id="main-content">
					
				<menu id="sandbox-actionslinks" class="cssmenu cssmenu-right cssmenu-actionslinks">
					<ul class="level-0">
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@cssmenu.actionslinks.sandbox}">{@cssmenu.actionslinks.sandbox}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox" title="{@cssmenu.actionslinks.index}">{@cssmenu.actionslinks.index}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/css" title="{@cssmenu.actionslinks.css}">{@cssmenu.actionslinks.css}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/bbcode" title="{@cssmenu.actionslinks.css}">{@cssmenu.actionslinks.bbcode}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/menu" title="{@cssmenu.actionslinks.menu}">{@cssmenu.actionslinks.menu}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/icons" title="{@cssmenu.actionslinks.icons}">{@cssmenu.actionslinks.icons}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/table" title="{@cssmenu.actionslinks.table}">{@cssmenu.actionslinks.table}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/mail" title="{@cssmenu.actionslinks.mail}">{@cssmenu.actionslinks.mail}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="{PATH_TO_ROOT}/sandbox/template" title="{@cssmenu.actionslinks.template}">{@cssmenu.actionslinks.template}</a>
								</li>
							</ul>
						</li>
						<li class="has-sub">
							<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
							<ul class="level-1">
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
								<li>
									<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
								</li>
							</ul>
						</li>				
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>		
					</ul>
				</menu>
				<script>jQuery("#sandbox-actionslinks").menumaker({ title: "{@cssmenu.actionslinks}", format: "multitoggle", breakpoint: 768 }); </script>
				
				<nav id="breadcrumb" itemprop="breadcrumb">
					<ol>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a href="{PATH_TO_ROOT}/" title="{@cssmenu.breadcrumb.index}" itemprop="url">
								<span itemprop="title">{@cssmenu.breadcrumb.index}</span>
							</a>
						</li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a href="{PATH_TO_ROOT}/sandbox" title="{@cssmenu.breadcrumb.sandbox}" itemprop="url">
								<span itemprop="title">{@cssmenu.breadcrumb.sandbox}</span>
							</a>
						</li>
						<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<span itemprop="title">{@cssmenu.breadcrumb.cssmenu}</span>
						</li>
					</ol>
				</nav>
				
					
				<br /><br />
				<div class="spacer"></div>			
				
				<header>
					<h2>{@cssmenu.h2}</h2>
				</header>
				<article>
					<div class="warning">
						{@cssmenu.warning}
					</div>
					<p>{@framework.lorem.large}</p>
					<p>{@framework.lorem.medium}</p>
				</article>	
			</div>
			<div id="bottom-content">
				<nav id="sandbox-group" class="cssmenu cssmenu-group">
					<ul class="level-0">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li class="current">
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>						
					</ul>
				</nav>
				<script>jQuery("#sandbox-group").menumaker({ title: "{@cssmenu.group}", format: "multitoggle", breakpoint: 768 }); </script>					
			</div>
		</div>
			
		<aside id="menu-right">				
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h3>{@cssmenu.vertical.scrolling}</h3>
				</div>
				<div class="module-mini-contents">
					<nav id="vertical-scrolling-right" class="cssmenu cssmenu-vertical cssmenu-right cssmenu-with-submenu">
						<ul class="level-0">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li class="has-sub">
										<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
										<ul class="level-2">
											<li>
												<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
											</li>
											<li class="has-sub">
												<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
												<ul class="level-3">
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
													<li>
														<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<li class="has-sub">
								<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
								<ul class="level-1">
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
									<li>
										<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
									</li>
								</ul>
							</li>						
						</ul>
					</nav>
					<script>jQuery("#vertical-scrolling-right").menumaker({ title: "{@cssmenu.vertical.scrolling.right}", format: "multitoggle", breakpoint: 768 }); </script>				
				</div>
				<div class="module-mini-bottom"></div>					
			</div>				
		</aside>
		
		
	</div>
					
	<footer id="footer">

		<div id="top-footer">
			<nav id="sandbox-static" class="cssmenu cssmenu-static">
				<ul class="level-0">
					<li class="has-sub">
						<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
						</ul>
					</li>
					<li class="has-sub">
						<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
						</ul>
					</li>
					<li class="has-sub">
						<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
						<ul class="level-1">
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
							</li>
							<li>
								<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
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
					<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<span class="cssmenu-title" href="#" title="{@cssmenu.sub.element}">{@cssmenu.sub.element}</span>
					<ul class="level-1">
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
						<li>
							<a class="cssmenu-title" href="#" title="{@cssmenu.element}">{@cssmenu.element}</a>
						</li>
					</ul>
				</li>						
			</ul>
		</nav>
		<script>jQuery("#sandbox-static-footer").menumaker({ title: "{@cssmenu.static.footer}", format: "multitoggle", breakpoint: 768, static: true }); </script>	
		</div>
	</footer>