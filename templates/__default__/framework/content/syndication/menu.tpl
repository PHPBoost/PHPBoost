<nav class="dynamic-menu">
	<ul>
		<li><a>${LangLoader::get_message('common.syndication', 'common-lang')}</a>
			<ul>
				<li>
					<a class="offload" href="{U_FEED_RSS}">${LangLoader::get_message('common.syndication.rss', 'common-lang')}</a>
				</li>
				<li>
					<a class="offload" href="{U_FEED_ATOM}">${LangLoader::get_message('common.syndication.atom', 'common-lang')}</a>
				</li>
				<li>
					<a class="offload" href="https://www.netvibes.com/subscribe.php?type=rss&amp;url={U_FEED_RSS}">Netvibes</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
