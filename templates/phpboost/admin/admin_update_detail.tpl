<div id="admin_quick_menu">
    <ul>
        <li class="title_menu">Mises à jour</li>
        <li>
            <a href="admin_updates.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
            <br />
            <a href="admin_updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
        </li>
        <li>
            <a href="admin_updates_kernel.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
            <br />
            <a href="admin_updates_kernel.php" class="quick_link">{L_KERNEL}</a>
        </li>
        <li>
            <a href="admin_updates_modules.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
            <br />
            <a href="admin_updates_modules.php" class="quick_link">{L_MODULES}</a>
        </li>
        <li>
            <a href="admin_updates_themes.php"><img src="../templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="admin_updates_themes.php" class="quick_link">{L_THEMES}</a>
        </li>
    </ul>
</div>

<div id="admin_contents" style="padding:20px;">
    <div>{APP_NAME} - {APP_VERSION}</div>
    <div>{APP_LANGUAGE} - {APP_PUBDATE}</div>
    <div>{L_AUTHORS}
        <ul>
            # START authors #
                <li><a href="mailto:{authors.email}">{authors.name}</a></li>
            # END authors #
        </ul>
    </div>
    
    <div>{APP_DESCRIPTION}</div>
    <div>{L_WHAT_IS_NEW}
        # IF C_NEW_FEATURES #
            <h3>{L_NEW_FEATURES}</h3>
            <ul>
                # START new_features #
                    <li>{new_features.description}</li>
                # END new_features #
            </ul>
        # END IF #
        # IF C_IMPROVMENTS #
            <h3>{L_IMPROVMENTS}</h3>
            <ul>
                # START improvments #
                    <li>{improvments.description}</li>
                # END improvments #
            </ul>
        # END IF #
        # IF C_BUG_CORRECTIONS #
            <h3>{L_FIXED_BUGS}</h3>
            <ul>
                # START bugs #
                    <li>{bugs.description}</li>
                # END bugs #
            </ul>
        # END IF #
        # IF C_SECURITY_IMPROVMENTS #
            <h3>{L_SECURITY_IMPROVMENTS}</h3>
            <ul>
                # START security #
                    <li>{security.description}</li>
                # END security #
            </ul>
        # END IF #
    </div>
    <div>{APP_WARNING_LEVEL}</div>
    <div>{APP_WARNING}</div>
</div>
    