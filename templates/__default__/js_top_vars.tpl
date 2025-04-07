<script>
	// PHPBoost Variables
	var PATH_TO_ROOT = "{PATH_TO_ROOT}";
	var TOKEN        = "{TOKEN}";
	var THEME        = "{THEME}";
	var LANG         = "{LANG}";

    // To clipboard
    var L_COPIED_TO_CLIPBOARD = ${escapejs(@common.copied.to.clipboard)};

	// BBCode Variables
	var L_HIDE_MESSAGE    = ${escapejs(@common.hidden.text)};
	var L_HIDE_HIDEBLOCK  = ${escapejs(@common.hide.text)};
	var L_COPY_TO_CLIPBOARD = ${escapejs(@common.copy.to.clipboard)};

    // Media
	var L_PREV  = ${escapejs(@common.previous)};
	var L_NEXT  = ${escapejs(@common.next)};
    var L_PLAY  = ${escapejs(@common.play)};
    var L_PAUSE = ${escapejs(@common.pause)};

    // Password
    var HIDE_PASSWORD = ${escapejs(@user.password.hide)};
    var REVEAL_PASSWORD = ${escapejs(@user.password.see)};

	// CookieBar Variables
	# IF C_COOKIEBAR_ENABLED #
		var COOKIEBAR_DURATION        = {COOKIEBAR_DURATION};
		var COOKIEBAR_TRACKING_MODE   = '{COOKIEBAR_TRACKING_MODE}';
		var L_COOKIEBAR_CONTENT       = {COOKIEBAR_CONTENT};
		var L_COOKIEBAR_UNDERSTAND    = ${escapejs(@user.cookiebar.understand)};
		var L_COOKIEBAR_ALLOWED       = ${escapejs(@user.cookiebar.allowed)};
		var L_COOKIEBAR_DECLINED      = ${escapejs(@user.cookiebar.declined)};
		var L_COOKIEBAR_MORE_TITLE    = ${escapejs(@user.cookiebar.more.title)};
		var L_COOKIEBAR_MORE          = ${escapejs(@user.cookiebar.more)};
		var L_COOKIEBAR_CHANGE_CHOICE = ${escapejs(@user.cookiebar.change.choice)};
		var U_COOKIEBAR_ABOUTCOOKIE   = '${relative_url(UserUrlBuilder::aboutcookie())}';
	# ENDIF #
</script>

{JS_TOP}