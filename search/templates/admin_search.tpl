        <script type="text/javascript">
        <!--
            function check_form_conf()
            {
                if(document.getElementById('forum_name').value == "") {
                    alert("{L_REQUIRE_NAME}");
                    return false;
                }
                if(document.getElementById('pagination_topic').value == "") {
                    alert("{L_REQUIRE_TOPIC_P}");
                    return false;
                }
                if(document.getElementById('pagination_msg').value == "") {
                    alert("{L_REQUIRE_NBR_MSG_P}");
                    return false;
                }
                if(document.getElementById('view_time').value == "") {
                    alert("{L_REQUIRE_TIME_NEW_MSG}");
                    return false;
                }
                if(document.getElementById('topic_track').value == "") {
                    alert("{L_REQUIRE_TOPIC_TRACK_MAX}");
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
            </ul>
        </div>

        <div id="admin_contents">
            # IF C_ERROR_HANDLER #
                <span id="errorh"></span>
                <div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
                    <img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
                    <br />
                </div>
                <br />
            # ENDIF #
            <form action="admin_search.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
                <fieldset>
                    <legend>{L_SEARCH_CONFIG}</legend>
                    <dl>
                        <dt><label for="cache_time">* {L_CACHE_TIME}</label><br /><span>{L_CACHE_TIME_EXPLAIN}</span></dt>
                        <dd><label><input type="text" maxlength="4" size="4" id="cache_time" name="cache_time" value="{CACHE_TIME}" class="text" /></label></dd>
                    </dl>
                    <dl>
                        <dt><label for="nb_results_p">* {L_NB_RESULTS_P}</label></dt>
                        <dd><label><input type="text" maxlength="2" size="4" id="nb_results_p" name="nb_results_p" value="{NB_RESULTS_P}" class="text" /></label></dd>
                    </dl>
                    <dl>
                        <dt><label for="max_use">* {L_MAX_USE}</label><br /><span>{L_MAX_USE_EXPLAIN}</span></dt>
                        <dd><label><input type="text" maxlength="3" size="4" id="max_use" name="max_use" value="{MAX_USE}" class="text" /></label></dd>
                    </dl>
                </fieldset>
                
                <fieldset>
                    <legend>{L_AUTHORISED_MODULES}</legend>
                    <dl>
                        <dt><label for="authorised_modules[]">* {L_AUTHORISED_MODULES}</label><br /><span>{L_AUTHORISED_MODULES_EXPLAIN}</span></dt>
                        <dd><label>
                            <select id="authorised_modules[]" name="authorised_modules[]" size="5" multiple="multiple" class="list_modules">
                                # START authorised_modules #
                                <option value="{authorised_modules.MODULE}" id="{authorised_modules.MODULE}"{authorised_modules.SELECTED}>{authorised_modules.L_MODULE_NAME}</option>
                                # END authorised_modules #
                            </select>
                        </label></dd>
                    </dl>
                </fieldset>
                
                <fieldset class="fieldset_submit">
                <legend>{L_UPDATE}</legend>
                    <input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
                    &nbsp;&nbsp; 
                    <input type="reset" value="{L_RESET}" class="reset" />
                </fieldset>
            </form>
            <form action="admin_search.php?clear=1" name="form" method="post" class="fieldset_content">
                <fieldset>
                    <legend>{L_CLEAR_OUT_CACHE}</legend>
                    <p style="text-align:center;">
                        <a href="admin_search.php?clear=1"><img src="../templates/{THEME}/images/admin/refresh.png" alt="" /></a>
                        <br />
                        <a href="admin_search.php?clear=1">{L_CLEAR_OUT_CACHE}</a>
                    </p>
                </fieldset>
            </form>
        </div>
        