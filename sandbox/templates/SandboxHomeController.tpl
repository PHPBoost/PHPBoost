<section>
	# INCLUDE SANDBOX_SUB_MENU #
	<header>
		<h1>{@sandbox.module.title}</h1>
	</header>
	<div class="content">
		<article>
		{WELCOME_MESSAGE}
		</article>
		<div class="cell-flex cell-tile cell-columns-2">
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.builder}
					</h6>
					<i class="fa fa-fw fa-asterisk"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.builder}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::builder())}"><i class="fa fa-fw fa-eye"></i> {@welcome.see}</a>
					# IF IS_ADMIN #<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::admin_builder())}"><i class="fa fa-fw fa-cog"></i> {@welcome.admin}</a># ENDIF #
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.fwkboost}
					</h6>
					<i class="fab fa-fw fa-css3"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.fwkboost}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::component())}"><i class="fa fa-fw fa-eye"></i> {@title.component}</a>
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::layout())}"><i class="fa fa-fw fa-eye"></i> {@title.layout}</a>
					# IF IS_ADMIN #<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::admin_fwkboost())}"><i class="fa fa-fw fa-cog"></i> {@welcome.admin}</a># ENDIF #
				</div>
			</div>
		</div>
		<div class="cell-flex cell-tile cell-columns-2">
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.bbcode}
					</h6>
					<i class="fa fa-fw fa-code"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.bbcode}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::bbcode())}"><i class="fa fa-fw fa-eye"></i> {@welcome.see}</a>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.menu}
					</h6>
					<i class="fa fa-fw fa-list"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.menu}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::menus())}"><i class="fa fa-fw fa-eye"></i> {@welcome.see}</a>
				</div>
			</div>
		</div>
		<div class="cell-flex cell-tile cell-columns-2">
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.icons}
					</h6>
					<i class="fab fa-fw fa-fort-awesome"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.icons}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::icons())}"><i class="fa fa-fw fa-eye"></i> {@welcome.see}</a>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">
						{@title.miscellaneous}
					</h6>
					<i class="fab fa-fw fa-php"></i>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.misc}
					</div>
				</div>
				<div class="cell-footer">
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::table())}"><i class="fa fa-fw fa-table"></i> {@title.table}</a>
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::email())}"><i class="fa fa-fw fa-at"></i> {@title.email}</a>
					<a class="pinned link-color" href="${relative_url(SandboxUrlBuilder::template())}"><i class="fa fa-fw fa-terminal"></i> {@title.template}</a>
				</div>
			</div>
		</div>
	</div>
</section>
