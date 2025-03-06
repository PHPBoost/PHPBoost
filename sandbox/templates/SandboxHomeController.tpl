<section id="module-sandbox-home">
	<header class="section-header">
		<h1>{@sandbox.module.title}</h1>
	</header>
	# INCLUDE SANDBOX_SUBMENU #
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				{WELCOME_MESSAGE}
			</div>
			<div class="cell-flex cell-tile cell-columns-2">
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.forms}
						</h6>
						<i class="fa fa-fw fa-asterisk"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.builder}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::builder())}"><i class="fa fa-fw fa-eye"></i> {@sandbox.welcome.see}</a>
						# IF IS_ADMIN #<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::admin_builder())}"><i class="fa fa-fw fa-cog"></i> {@sandbox.welcome.admin}</a># ENDIF #
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.components}
						</h6>
						<i class="fab fa-fw fa-css3"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.component}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::component())}"><i class="fa fa-fw fa-eye"></i> {@sandbox.components}</a>
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::icons())}"><i class="fa fa-fw fa-eye"></i> {@sandbox.icons}</a>
						# IF IS_ADMIN #<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::admin_component())}"><i class="fa fa-fw fa-cog"></i> {@sandbox.welcome.admin}</a># ENDIF #
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.bbcode}
						</h6>
						<i class="fa fa-fw fa-code"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.bbcode}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::bbcode())}"><i class="fa fa-fw fa-eye"></i> {@sandbox.welcome.see}</a>
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.layout}
						</h6>
						<i class="fab fa-fw fa-fort-awesome"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.layout}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::layout())}"><i class="fa fa-fw fa-eye"></i> {@sandbox.welcome.see}</a>
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.menus}
						</h6>
						<i class="fa fa-fw fa-list"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.menu}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::menus_nav())}"><i class="fa fa-fw fa-list"></i> {@sandbox.welcome.see.nav}</a>
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::menus_content())}"><i class="fa fa-fw fa-list-ol"></i> {@sandbox.welcome.see.content}</a>
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{@sandbox.miscellaneous}
						</h6>
						<i class="fab fa-fw fa-php"></i>
					</div>
					<div class="cell-body">
						<div class="cell-content">
							{@sandbox.welcome.misc}
						</div>
					</div>
					<div class="cell-footer">
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::lang())}"><i class="fa fa-fw fa-table"></i> {@sandbox.lang}</a>
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::table())}"><i class="fa fa-fw fa-table"></i> {@sandbox.table}</a>
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::email())}"><i class="fa fa-fw fa-at"></i> {@sandbox.email}</a>
						<a class="pinned link-color offload" href="${relative_url(SandboxUrlBuilder::template())}"><i class="fa fa-fw fa-terminal"></i> {@sandbox.template}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
