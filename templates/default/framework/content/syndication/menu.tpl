<menu class="dynamic-menu">
	<ul>
		<li><a>${LangLoader::get_message('syndication', 'common')}</a>
			<ul>
				<li>
					<a href="{U_FEED_RSS}" title="${LangLoader::get_message('syndication.rss', 'common')}">${LangLoader::get_message('syndication.rss', 'common')}</a>
				</li>
				<li>
					<a href="{U_FEED_ATOM}" title="${LangLoader::get_message('syndication.atom', 'common')}">${LangLoader::get_message('syndication.atom', 'common')}</a>
				</li>
				<li>
					<a href="http://www.netvibes.com/subscribe.php?type=rss&amp;url={U_FEED_RSS}" title="Netvibes">Netvibes</a>
				</li>
			</ul>
		</li>
	</ul>
</menu>
