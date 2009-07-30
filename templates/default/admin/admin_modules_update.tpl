<div id="admin_quick_menu">
    <ul>
        <li class="title_menu">{L_MODULES_MANAGEMENT}</li>
        <li>
            <a href="admin_modules.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGEMENT}</a>
        </li>
        <li>
            <a href="admin_modules_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="admin_modules_add.php" class="quick_link">{L_ADD_MODULES}</a>
        </li>
        <li>
            <a href="admin_modules_update.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
            <br />
            <a href="admin_modules_update.php" class="quick_link">{L_UPDATE_MODULES}</a>
        </li>
    </ul>
</div>

<div id="admin_contents">
    
    # IF C_ERROR_HANDLER #
    <div class="error_handler_position">
        <span id="errorh"></span>
        <div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
            <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
            <br />	
        </div>
    </div>
    # ENDIF #
    
    <form action="admin_modules_update.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset_content">
        <fieldset>
            <legend>{L_UPLOAD_MODULE}</legend>
            <dl>
                <dt><label for="upload_module">{L_EXPLAIN_ARCHIVE_UPLOAD}</label></dt>
                <dd><label><input type="file" name="upload_module" id="upload_module" size="30" class="file" />
                <input type="hidden" name="max_file_size" value="2000000" /></label></dd>
            </dl>
        </fieldset>			
        <fieldset class="fieldset_submit">
            <legend>{L_UPLOAD}</legend>
            <input type="submit" value="{L_UPLOAD}" class="submit" />				
        </fieldset>	
    </form>
    
    
    # IF C_INCOMPATIBLE_PHP_VERSION #
        &nbsp;<div class="warning" style="width:300px;margin:auto;margin-top:100px;">{L_INCOMPATIBLE_PHP_VERSION}</div>
    # ELSE #
        # IF C_UPDATES #
            <div class="warning" style="width:300px;margin:auto;">{L_UPDATES_ARE_AVAILABLE}</div>
            
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
                        <p style="text-align:right;"><a href="admin_update_detail.php?identifier={apps.identifier}" title="{L_MORE_DETAILS}" class="small_link">{L_DETAILS}</a></p>
                    </td>
                    <td class="row1 {apps.priority_css_class}" >{apps.L_PRIORITY}</td>
                    <td class="row1" style="text-align:center;">
                        <a href="{apps.download_url}" title="{L_DOWNLOAD_THE_COMPLETE_PACK}">{L_DOWNLOAD_PACK}</a><br />
                        /<br />
                        <a href="{apps.update_url}" title="{L_DOWNLOAD_THE_UPDATE_PACK}">{L_UPDATE_PACK}</a>
                    </td>
                </tr>
                # END apps #
            </table>
        # ELSE #
            &nbsp;<div class="question" style="width:300px;margin:auto;margin-top:100px;">{L_NO_AVAILABLES_UPDATES}</div>
        # END IF #
    # END IF #
</div>