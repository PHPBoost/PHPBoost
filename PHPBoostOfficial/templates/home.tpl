<!-- Download link -->
<div id="p-container">

	<img src="{PATH_TO_ROOT}/PHPBoostOfficial/templates/images/pub.png" alt="PHPBoost CMS" class="p-img" width="400" height="256"/>
	
	<div id="p-slide">
		<a href="#" class="p-slide-title">PHPBoost CMS</a>
		<p class="p-slide-content">
			{@site_slide_description}
		</p>
		<p id="p-more" class="p-left">
			<a href="{PATH_TO_ROOT}/pages/fonctionnalites-de-phpboost" class="p-more_content" title="{@phpboost_features}">{@phpboost_features.explain}</a>
		</p>
	</div>
		<div id="p-link">
			<div class="p-link-btn btn-ddl">
				<a href="{PATH_TO_ROOT}/download/" title="{@download.phpboost}">
					<i class="fa fa-download fa-2x"></i>
					<p class="p-link-title">{@download}</p>
					<p class="p-link-com ddl-com">{@version} {@download.last_major_version_number}</p>
				</a>
			</div>
			<div class="p-link-btn btn-try">
				<a href="http://demo.phpboost.com" title="{@demo}">
					<i class="fa fa-cog fa-2x"></i>
					<p class="p-link-title">{@try}</p>
					<p class="p-link-com try-com">{@demo.website}</p>
				</a>
			</div>
			<div class="spacer"></div>
		</div>
	<div class="spacer"></div>
</div>

<!-- 3 last modules and templates -->
<div id="lc-container">

	<div class="lt-container">
		<div class="lc-img lt-title-img"></div>
		<div class="lc-title title">{@last_themes}</div>
	
		<div class="lt-content">
			# INCLUDE THEMES #
			<div class="subcat-container">
					
				<div class="subcat-element subcat-templates">
					<a itemprop="about" href="{@download.last_version_themes_cat_link}" class="subcat-content">
						<span class="subcat-img-container">
							<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_themes}" class="subcat-img" />
						</span>
						<span class="subcat-title">{@discover_other_themes}</span>
					</a>
				</div>

			</div>
		</div>
	</div>
	
	<div class="lm-container">
		<div class="lc-img lm-title-img"></div>
		<div class="lc-title title">{@last_modules}</div>
		
		<div class="lm-content">
			
			<div class="subcat-container">
					
				<div class="subcat-element subcat-modules">
					<a itemprop="about" href="{@download.last_version_modules_cat_link}" class="subcat-content" title="{@modules_for_phpboost}">
						<span class="subcat-img-container">
							<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_themes}" class="subcat-img" />
						</span>
						<span class="subcat-title">{@discover_other_modules}</span>
					</a>
				</div>

			</div>
			# INCLUDE MODULES #
			<div class="spacer"></div>
		</div>
	</div>
	
</div>

<!-- Last news -->
<div id="ln-container">

	<div id="ln-top-img" class="lc-img">
		<a href="{PATH_TO_ROOT}/syndication/rss/news/" title="{@news.phpboost.rss}"></a>
	</div>
	<div id="ln-top" class="title">{@news.phpboost}</div>

	# INCLUDE LAST_NEWS #
	
	<div class="ln-list">
		<div id="ln-list-title" class="title">{@news.previous_news}</div>
		<div class="ln-list-content">
			{FEED_NEWS}
		</div>
	</div>
	
</div>