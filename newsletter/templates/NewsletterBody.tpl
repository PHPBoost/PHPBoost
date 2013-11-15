# IF C_CREATE_AUTH #
	<menu class="dynamic_menu right">
		<ul>
			<li><a><i class="icon-cog"></i></a>
				<ul>
					<li>
						<a href="{LINK_CREATE}" title="${i18n('add.add_newsletter')}">${i18n('add.add_newsletter')}</a>
					</li>
					# IF IS_ADMIN #
					<li>
						<a href="${relative_url(NewsletterUrlBuilder::configuration())}" title="${LangLoader::get_message('configuration', 'admin')}">${LangLoader::get_message('configuration', 'admin')}</a>
					</li>
					# ENDIF #
				</ul>
			</li>
		</ul>
	</menu>
# ENDIF #

<section>
	<header>
		<h1>
			{@newsletter}
		</h1>
	</header>
	<div class="content">
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>