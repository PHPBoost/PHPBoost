<div id="admin_quick_menu">
    <ul>
        <li class="title_menu">{L_WEBSITE_UPDATES}</li>
        <li>
            <a href="updates.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="" /></a>
            <br />
            <a href="updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
        </li>
        <li>
            <a href="updates.php?type=kernel"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
            <br />
            <a href="updates.php?type=kernel" class="quick_link">{L_KERNEL}</a>
        </li>
        <li>
            <a href="updates.php?type=module"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="updates.php?type=module" class="quick_link">{L_MODULES}</a>
        </li>
        <li>
            <a href="updates.php?type=theme"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/themes.png" alt="" /></a>
            <br />
            <a href="updates.php?type=theme" class="quick_link">{L_THEMES}</a>
        </li>
    </ul>
</div>

<div id="admin_contents">
	<div style="clear:right;"></div>
    # IF C_UNEXISTING_UPDATE #
        <br /><span class="warning" style="text-align:center;width:50%;margin:auto;">{L_UNEXISTING_UPDATE}</span>
    # ELSE #
        <h1>{L_APP_UPDATE_MESSAGE}</h1>
        <table>
            <tr>
	            <td style="vertical-align:top;padding-right:10px;">
		            <div class="block_container">
		                <div class="block_top"><span>{APP_NAME} - {APP_VERSION} ({APP_LANGUAGE})</span></div>
		                <div class="block_contents">
							{APP_DESCRIPTION}
							<p class="text_small" style="text-align:right;margin:0">{APP_PUBDATE}</p>
						</div>
		            </div>
		            # IF C_NEW_FEATURES #
		                <div class="block_container">
		                    <div class="block_top"><span>{L_NEW_FEATURES}</span></div>
		                    <div class="block_contents">
								<ul class="list"># START new_features #<li>{new_features.description}</li># END new_features #</ul>
							</div>
		                </div>
		            # END IF #
		            # IF C_IMPROVMENTS #
		                <div class="block_container">
		                    <div class="block_top"><span>{L_IMPROVMENTS}</span></div>
		                    <div class="block_contents">
								<ul class="list"># START improvments #<li>{improvments.description}</li># END improvments #</ul>
							</div>
		                </div>
		            # END IF #
		            <div class="block_container">
		                <div class="block_top"><span class="{PRIORITY_CSS_CLASS}">{L_WARNING} - {APP_WARNING_LEVEL}</span></div>
		                <div class="block_contents">
							{APP_WARNING}
						</div>
		            </div>
		        </td>
		        <td style="vertical-align:top;min-width:200px;">
		            <div class="block_container">
		                <div class="block_top"><span>{L_DOWNLOAD}</span></div>
		                <div class="block_contents">
							<ul class="list">
								<li><a href="{U_APP_DOWNLOAD}">{L_DOWNLOAD_PACK}</a></li>
								# IF U_APP_UPDATE #
								<li><a href="{U_APP_UPDATE}">{L_UPDATE_PACK}</a></li>
								# END IF #
							</ul>
						</div>
		            </div>
		            <div class="block_container">
		                <div class="block_top"><span>{L_AUTHORS}</span></div>
		                <div class="block_contents">
							<ul class="list"># START authors #<li><a href="mailto:{authors.email}">{authors.name}</a></li># END authors #</ul>
						</div>
		            </div>
		            # IF C_BUG_CORRECTIONS #
		                <div class="block_container">
		                    <div class="block_top"><span>{L_FIXED_BUGS}</span></div>
		                    <div class="block_contents">
								<ul class="list"># START bugs #<li>{bugs.description}</li># END bugs #</ul>
							</div>
		                </div>
		            # END IF #
		            # IF C_SECURITY_IMPROVMENTS #
		                <div class="block_container">
		                    <div class="block_top"><span>{L_SECURITY_IMPROVMENTS}</span></div>
							<div class="block_contents">
								<ul class="list"># START security #<li>{security.description}</li># END security #</ul>
							</div>
		                </div>
		            # END IF #
                </td>
            </tr>
        </table>
    # END IF #
</div>
    