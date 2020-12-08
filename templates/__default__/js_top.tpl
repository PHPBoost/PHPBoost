<script>
	// PHPBoost Variables
	var PATH_TO_ROOT = "{PATH_TO_ROOT}";
	var TOKEN        = "{TOKEN}";
	var THEME        = "{THEME}";
	var LANG         = "{LANG}";

	// BBCode Variables
	var L_HIDE_MESSAGE    = ${escapejs(LangLoader::get_message('tag_hide_message', 'editor-common'))};
	var L_HIDE_HIDEBLOCK  = ${escapejs(LangLoader::get_message('tag_hide_hideblock', 'editor-common'))};
	var L_COPYTOCLIPBOARD = ${escapejs(LangLoader::get_message('tag_copytoclipboard', 'editor-common'))};

	// CookieBar Variables
	# IF C_COOKIEBAR_ENABLED #
		var COOKIEBAR_DURATION        = {COOKIEBAR_DURATION};
		var COOKIEBAR_TRACKING_MODE   = '{COOKIEBAR_TRACKING_MODE}';
		var L_COOKIEBAR_CONTENT       = {COOKIEBAR_CONTENT};
		var L_COOKIEBAR_UNDERSTAND    = ${escapejs(LangLoader::get_message('cookiebar.understand', 'user-common'))};
		var L_COOKIEBAR_ALLOWED       = ${escapejs(LangLoader::get_message('cookiebar.allowed', 'user-common'))};
		var L_COOKIEBAR_DECLINED      = ${escapejs(LangLoader::get_message('cookiebar.declined', 'user-common'))};
		var L_COOKIEBAR_MORE_TITLE    = ${escapejs(LangLoader::get_message('cookiebar.more-title', 'user-common'))};
		var L_COOKIEBAR_MORE          = ${escapejs(LangLoader::get_message('cookiebar.more', 'user-common'))};
		var L_COOKIEBAR_CHANGE_CHOICE = ${escapejs(LangLoader::get_message('cookiebar.change-choice', 'user-common'))};
		var U_COOKIEBAR_ABOUTCOOKIE   = '${relative_url(UserUrlBuilder::aboutcookie())}';
	# ENDIF #
</script>

<script src="{PATH_TO_ROOT}/kernel/lib/js/jquery/jquery.js"></script>
<script src="{PATH_TO_ROOT}/kernel/lib/js/prism/prism.js"></script>

# IF C_CSS_CACHE_ENABLED #
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/@phpboost.min.js"></script>
# ELSE #
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/@global.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/autocomplete.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/autobox.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/basictable.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/dndfiles.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/lightcase.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/linedtextarea.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/menumaker.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/multitabs.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/owl.carousel.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/pushmenu.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/selectimg.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/selectimg.multi.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/sortable.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/tooltip.js"></script>
	<script src="{PATH_TO_ROOT}/templates/__default__/plugins/wizard.js"></script>
# ENDIF #
