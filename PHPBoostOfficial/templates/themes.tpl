# START item #
<div class="pbt-element pbt-element-templates" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
	<div class="content">
		<a href="{item.U_LINK}">
			<div class="pbt-element-img-container">
				<img src="{item.U_IMG}" alt="{item.TITLE}" class="pbt-img pbt-img-templates" itemprop="image" />
				<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon-templates" class="pbt-icon pbt-icon-templates" />
			</div>
			<div class="pbt-element-info-container">
				<p class="pbt-info-title">{item.TITLE}</p>
				<p class="pbt-info-desc">{item.DESC}</p>
				<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} {item.PSEUDO}</p>
			</div>
		</a>
	</div>
</div>
# END item #