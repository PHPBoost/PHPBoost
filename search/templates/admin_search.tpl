        <script type="text/javascript">
        <!--
            function check_form_conf()
            {
                if(!isInteger(document.getElementById('nb_results_p').value)) {
                    alert("{L_REQUIRE_INTEGER}");
                    return false;
                }
                if(!isInteger(document.getElementById('cache_time').value)) {
                    alert("{L_REQUIRE_INTEGER}");
                    return false;
                }
                if(!isInteger(document.getElementById('max_use').value)) {
                    alert("{L_REQUIRE_INTEGER}");
                    return false;
                }
                return true;
            }
        -->
        </script>
        <div id="admin_quick_menu">
            <ul>
                <li class="title_menu">{L_SEARCH_MANAGEMENT}</li>
                <li>
                    <a href="admin_search.php"><img src="search.png" alt="" /></a>
                    <br />
                    <a href="admin_search.php" class="quick_link">{L_SEARCH_CONFIG}</a>
                </li>
                <li>
                    <a href="admin_search.php?weighting=true"><img src="search.png" alt="" /></a>
                    <br />
                    <a href="admin_search.php?weighting=true" class="quick_link">{L_SEARCH_CONFIG_WEIGHTING}</a>
                </li>
            </ul>
        </div>

        <div id="admin_contents">
            # INCLUDE message_helper #
            
            # IF NOT C_WEIGHTING #
            <form action="admin_search.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
                <fieldset>
                    <legend>{L_SEARCH_CONFIG}</legend>
                    <dl>
                        <dt><label for="nb_results_p">* {L_NB_RESULTS_P}</label></dt>
                        <dd><label><input type="text" maxlength="2" size="4" id="nb_results_p" name="nb_results_p" value="{NB_RESULTS_P}" class="text" /></label></dd>
                    </dl>
                    <dl>
                        <dt><label for="authorized_modules[]">* {L_AUTHORIZED_MODULES}</label><br /><span>{L_AUTHORIZED_MODULES_EXPLAIN}</span></dt>
                        <dd><label>
                            <select id="authorized_modules[]" name="authorized_modules[]" size="5" multiple="multiple" class="list_modules">
                                # START authorized_modules #
                                <option value="{authorized_modules.MODULE}" id="{authorized_modules.MODULE}"{authorized_modules.SELECTED}>{authorized_modules.L_MODULE_NAME}</option>
                                # END authorized_modules #
                            </select>
                        </label></dd>
                    </dl>
                </fieldset>
                
                <fieldset>
                    <legend>{L_SEARCH_CACHE}</legend>
                    <dl>
                        <dt><label for="cache_time">* {L_CACHE_TIME}</label><br /><span>{L_CACHE_TIME_EXPLAIN}</span></dt>
                        <dd><label><input type="text" maxlength="4" size="4" id="cache_time" name="cache_time" value="{CACHE_TIME}" class="text" /></label></dd>
                    </dl>
                    <dl>
                        <dt><label for="max_use">* {L_MAX_USE}</label><br /><span>{L_MAX_USE_EXPLAIN}</span></dt>
                        <dd><label><input type="text" maxlength="3" size="4" id="max_use" name="max_use" value="{MAX_USE}" class="text" /></label></dd>
                    </dl>
                </fieldset>
                
                <fieldset class="fieldset_submit">
                <legend>{L_UPDATE}</legend>
                    <input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
                    &nbsp;&nbsp; 
                    <input type="reset" value="{L_RESET}" class="reset" />
                </fieldset>
            </form>
            <form action="admin_search.php?clear=1&amp;token={TOKEN}" name="form" method="post" class="fieldset_content">
                <fieldset>
                    <legend>{L_CLEAR_OUT_CACHE}</legend>
                    <p style="text-align:center;">
                        <a href="admin_search.php?clear=1"><img src="../templates/{THEME}/images/admin/refresh.png" alt="" /></a>
                        <br />
                        <a href="admin_search.php?clear=1">{L_CLEAR_OUT_CACHE}</a>
                    </p>
                </fieldset>
            </form>
            # ELSE #
            <form action="admin_search.php?weighting=true&amp;token={TOKEN}" method="post" class="fieldset_content">
                <fieldset>
                    <legend>{L_SEARCH_CONFIG_WEIGHTING}</legend>
                    <p>{L_SEARCH_CONFIG_WEIGHTING_EXPLAIN}</p>
                        <table style="margin-left:50px;border:medium none;border-spacing:0pt;">
                            <tbody>
                                <tr><th>{L_MODULES}</th><th>{L_WEIGHTS}</th></tr>
                                # START weights #
                                <tr>
                                    <td class="row2"><label for="{weights.MODULE}">{weights.L_MODULE_NAME}</label></td>
                                    <td class="row2" style="text-align:center;"><input type="text" id="{weights.MODULE}" name="{weights.MODULE}" value="{weights.WEIGHT}" size="2" maxlength="3" class="text" /></td>
                                </tr>
                                # END weights #
                            </tbody>
                        </table>
                </fieldset>
                
                <fieldset class="fieldset_submit">
                <legend>{L_UPDATE}</legend>
                    <input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
                    &nbsp;&nbsp; 
                    <input type="reset" value="{L_RESET}" class="reset" />
                </fieldset>
            </form>
            # ENDIF #
        </div>
        