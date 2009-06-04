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
    # IF C_INCOMPATIBLE_PHP_VERSION #
        &nbsp;<div class="warning" style="width:450px;margin:auto;margin-top:100px;">{L_INCOMPATIBLE_PHP_VERSION}</div>
    # ELSE #
        # IF C_UPDATES #
            <div class="warning" style="width:450px;margin:auto;">{L_UPDATES_ARE_AVAILABLE}</div>
            
            <table class="module_table">
                <tr><th colspan="5">{L_AVAILABLES_UPDATES}</th></tr>
                <tr>
                    # IF C_ALL # <td class="row2" style="text-align:center;width:50px;">{L_TYPE}</td> # END IF #
                    <td class="row2" style="text-align:center;">{L_DESCRIPTION}</td>
                    <td class="row2" style="text-align:center;width:75px;">{L_PRIORITY}</td>
                    <td class="row2" style="text-align:center;width:75px;">{L_UPDATE_DOWNLOAD}</td>
                </tr>
                # START apps #
                <tr> 
                    # IF C_ALL # <td class="row1" style="text-align:center;">{apps.type}</td> # END IF #
                    <td class="row1">
                        {L_NAME} : <strong>{apps.name}</strong> - {L_VERSION} : <strong>{apps.version}</strong>
                        <div style="padding:5px;padding-top:10px;text-align:justify;">{apps.short_description}</div>
                        <p style="text-align:right;"><a href="detail.php?identifier={apps.identifier}" title="{L_MORE_DETAILS}" class="small_link">{L_DETAILS}</a></p>
                    </td>
                    <td class="row1 {apps.priority_css_class}" >{apps.L_PRIORITY}</td>
                    <td class="row1" style="text-align:center;">
                        <a href="{apps.download_url}" title="{L_DOWNLOAD_THE_COMPLETE_PACK}">{L_DOWNLOAD_PACK}</a><br />
                        # IF apps.update_url #
                        /<br />
                        <a href="{apps.update_url}" title="{L_DOWNLOAD_THE_UPDATE_PACK}">{L_UPDATE_PACK}</a>
                        # END IF #
                    </td>
                </tr>
                # END apps #
            </table>
        # ELSE #
            &nbsp;<div class="question" style="width:300px;margin:auto;margin-top:100px;">{L_NO_AVAILABLES_UPDATES}</div>
        # END IF #
        <p class="center" style="margin-top:100px;">
			<a href="{U_CHECK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="" /></a>
			<br />
			<a href="{U_CHECK}">{L_CHECK_FOR_UPDATES_NOW}</a>
		</p>
    # END IF #
</div>