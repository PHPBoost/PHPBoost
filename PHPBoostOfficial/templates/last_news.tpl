<div class="ln-first">
	<div class="ln-first-content">
		<a href="{U_LINK}" class="ln-title">{NAME}</a>
		<p class="ln-date"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</p>
		<div class="ln-content">{CONTENTS}</div>
	</div>
	<div class="ln-first-readmore">
		<a href="{U_LINK}" class="ln-title">[${LangLoader::get_message('read-more', 'common')}]</a>
	</div>
</div>