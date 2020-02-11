<section>
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
					<i class="fa fa-fw fa-asterisk"></i>
					<h6 class="cell-name">
						{@title.form.builder}
					</h6>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						{@welcome.form}
					</div>
				</div>
				<div class="cell-list">
					<ul>
						# IF IS_ADMIN #
							<li class="li-stretch">
								<a href="${relative_url(SandboxUrlBuilder::form())}">{@welcome.form.front}</a>
								<a href="${relative_url(SandboxUrlBuilder::admin_form())}">{@welcome.form.admin}</a>
							</li>
						# ELSE #
							<li>
								<a href="${relative_url(SandboxUrlBuilder::form())}">{@see.render}</a>
							</li>
						# ENDIF #
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
