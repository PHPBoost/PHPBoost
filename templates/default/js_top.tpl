<script>
<!--
	//Variables PHPBoost
	var PATH_TO_ROOT = "{PATH_TO_ROOT}";
	var TOKEN = "{TOKEN}";
	var THEME = "{THEME}";
	var LANG = "{LANG}";

	//Variables BBCode
	var L_HIDE_MESSAGE = ${escapejs(LangLoader::get_message('tag_hide', 'editor-common'))};
	var L_COPYTOCLIPBOARD = ${escapejs(LangLoader::get_message('tag_copytoclipboard', 'editor-common'))};

	//Variables Cookiebar  (En cours) Il faut englober avec un IF COOKIEBAR enable et remplacer le U_tracking par le choix de l'admin
	var U_TRACKING = true;
	var L_COOKIEBAR_NOTRACKING =  ${escapejs(LangLoader::get_message('cookiebar-message.notracking', 'user-common'))};
	var L_COOKIEBAR_TRACKING =  ${escapejs(LangLoader::get_message('cookiebar-message.tracking', 'user-common'))};
	var L_COOKIEBAR_UNDERSTAND = ${escapejs(LangLoader::get_message('cookiebar.understand', 'user-common'))};
	var L_COOKIEBAR_ALLOWED = ${escapejs(LangLoader::get_message('cookiebar.allowed', 'user-common'))};
	var L_COOKIEBAR_DECLINED = ${escapejs(LangLoader::get_message('cookiebar.declined', 'user-common'))};
	var L_COOKIEBAR_MORE = ${escapejs(LangLoader::get_message('cookiebar.more', 'user-common'))};
	var L_COOKIEBAR_MORE_LINK = "{PATH_TO_ROOT}" + "/user/aboutcookie";
-->
</script>

<script src="{PATH_TO_ROOT}/kernel/lib/js/global.js"></script>
