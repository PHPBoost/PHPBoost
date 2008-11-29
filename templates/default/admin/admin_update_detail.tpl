<div id="admin_quick_menu">
    <ul>
        <li class="title_menu">Mises � jour</li>
        <li>
            <a href="admin_updates.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="" /></a>
            <br />
            <a href="admin_updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
        </li>
        <li>
            <a href="admin_updates.php?type=kernel"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="" /></a>
            <br />
            <a href="admin_updates.php?type=kernel" class="quick_link">{L_KERNEL}</a>
        </li>
        <li>
            <a href="admin_updates.php?type=module"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="admin_updates.php?type=module" class="quick_link">{L_MODULES}</a>
        </li>
        <li>
            <a href="admin_updates.php?type=theme"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/themes.png" alt="" /></a>
            <br />
            <a href="admin_updates.php?type=theme" class="quick_link">{L_THEMES}</a>
        </li>
    </ul>
</div>

<div id="admin_contents">
    <div class="fieldset_content">
        # IF C_UNEXISTING_UPDATE #
            <br /><span class="warning" style="text-align:center;width:50%;margin:auto;">{L_UNEXISTING_UPDATE}</span>
        # ELSE #
            <h1>{L_APP_UPDATE_MESSAGE}</h1>
            <div style="width:440px;float:left;margin-left:10px;">
                <div class="block_content">
                    <div class="block_title"><span>{APP_NAME} - {APP_VERSION} ({APP_LANGUAGE})</span></div>
                    <div style="text-align:right;float:right;position:relative;top:-15px;font-size:10px;">{APP_PUBDATE}</div>
                    {APP_DESCRIPTION}
                </div>
                # IF C_NEW_FEATURES #
                    <div class="block_content">
                        <div class="block_title"><span>{L_NEW_FEATURES}</span></div>
                        <ul># START new_features #<li>{new_features.description}</li># END new_features #</ul>
                    </div>
                # END IF #
                # IF C_IMPROVMENTS #
                    <div class="block_content">
                        <div class="block_title"><span>{L_IMPROVMENTS}</span></div>
                        <ul># START improvments #<li>{improvments.description}</li># END improvments #</ul>
                    </div>
                # END IF #
                <div class="block_content">
                    <div class="block_title"><span>{L_WARNING} - {APP_WARNING_LEVEL}</span></div>
                    {APP_WARNING}
                </div>
            </div>
            
            <div style="width:300px;float:right;margin-left:10px;">
                <div class="block_content">
                    <div class="block_title"><span>{L_DOWNLOAD}</span></div>
                    <ul>
                        <li><a href="{U_APP_DOWNLOAD}">{L_DOWNLOAD_PACK}</a></li>
                        <li><a href="{U_APP_UPDATE}">{L_UPDATE_PACK}</a></li>
                    </ul>
                </div>
                <div class="block_content">
                    <div class="block_title"><span>{L_AUTHORS}</span></div>
                    <ul># START authors #<li><a href="mailto:{authors.email}">{authors.name}</a></li># END authors #</ul>
                </div>
                # IF C_BUG_CORRECTIONS #
                    <div class="block_content">
                        <div class="block_title"><span>{L_FIXED_BUGS}</span></div>
                        <ul># START bugs #<li>{bugs.description}</li># END bugs #</ul>
                    </div>
                # END IF #
                # IF C_SECURITY_IMPROVMENTS #
                    <div class="block_content">
                        <div class="block_title"><span>{L_SECURITY_IMPROVMENTS}</span></div>
                        <ul># START security #<li>{security.description}</li># END security #</ul>
                    </div>
                # END IF #
            </div>
        # END IF #
    </div>
</div>
    