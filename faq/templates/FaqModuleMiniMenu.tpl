# IF C_QUESTION #
	<a href="{U_LINK}"><img src="{PATH_TO_ROOT}/faq/faq.png" title="{@module_title}" alt="{@module_title}" height="32" width="32" itemprop="image" /></a>
	<div class="spacer"></div>
	<a href="{U_LINK}" class="small">{QUESTION}</a>
# ELSE #
${LangLoader::get_message('no_item_now', 'common')}
# ENDIF #
