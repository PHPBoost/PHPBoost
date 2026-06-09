<div id="admin-subheader" role="navigation">
    <nav class="admin-menu">
        <ul>
            <li class="admin-li">
                <a href="#admin-administration" class="modal-button --openmodal-administration"><i aria-hidden="true" class="fa fa-fw fa-cog"></i><span>{@menu.administration}</span></a>
            </li>
            <li class="admin-li">
                <a href="#admin-addons" class="modal-button --openmodal-addons"><i aria-hidden="true" class="fa fa-fw fa-puzzle-piece"></i><span>{@menu.addons}</span></a>
            </li>
            <li class="admin-li">
                <a href="#admin-tools" class="modal-button --openmodal-tools"><i aria-hidden="true" class="fa fa-fw fa-wrench"></i><span>{@menu.tools}</span></a>
            </li>
            <li class="admin-li">
                <a href="#admin-users" class="modal-button --openmodal-users"><i aria-hidden="true" class="fa fa-fw fa-user"></i><span>{@menu.users}</span></a>
            </li>
            <li class="admin-li">
                <a href="#admin-content" class="modal-button --openmodal-content"><i aria-hidden="true" class="far fa-fw fa-square"></i><span>{@menu.content}</span></a>
            </li>
            <li class="admin-li">
                <a href="#admin-modules" class="modal-button --openmodal-modules"><i aria-hidden="true" class="fa fa-fw fa-cube"></i><span>{@menu.modules}</span></a>
            </li>
        </ul>
    </nav>

    <div class="panel-container">
        <div id="openmodal-administration" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
                <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-modules"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.modules}</a>
                    <h5>{@menu.administration}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-addons">{@menu.addons} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.configuration}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.configuration.general}</a></li>
                            <li><a href="${relative_url(AdminConfigUrlBuilder::advanced_config())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{@menu.configuration.advanced}</a></li>
                            <li><a href="${relative_url(AdminConfigUrlBuilder::mail_config())}"><i aria-hidden="true" class="fa fa-fw iboost fa-iboost-email"></i>{@menu.configuration.email}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-download"></i>{@menu.updates}</span>
                        <ul class="level-2">
                            <li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=kernel"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.updates.kernel}</a></li>
                            <li><a href="${relative_url(AdminMaintainUrlBuilder::maintain())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.maintenance}</a></li>
                            <li><a href="{PATH_TO_ROOT}/admin/admin_alerts.php"><i aria-hidden="true" class="fa fa-fw fa-list-check"></i> {@menu.alerts}</a></li>
                        </ul>
                    </li>
                    # IF C_ADMIN_LINKS_1 #
                        # START admin_links_1 #
                            # INCLUDE admin_links_1.MODULE_MENU #
                        # END admin_links_1 #
                    # ENDIF #
                </ul>
            </div>
        </div>

        <div id="openmodal-addons" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
            <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-administration"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.administration}</a>
                    <h5>{@menu.addons}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-tools">{@menu.tools} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-cubes"></i>{@menu.modules}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminModulesUrlBuilder::add_module())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                            <!-- <li><a href="${relative_url(AdminModulesUrlBuilder::update_module())}"><i aria-hidden="true" class="fa fa-fw fa-level-up-alt"></i>{@menu.manual.updates}</a></li> -->
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-image"></i>{@menu.themes}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-language"></i>{@menu.langs}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminLangsUrlBuilder::install())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i> {@menu.add}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-download"></i>{@menu.updates}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminConfigUrlBuilder::addons_config())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.configuration.addons}</a></li>
                            <li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=module"><i aria-hidden="true" class="fa fa-fw fa-cubes"></i>{@menu.modules}</a></li>
                            <li><a href="{PATH_TO_ROOT}/admin/updates/updates.php?type=template"><i aria-hidden="true" class="fa fa-fw fa-image"></i>{@menu.themes}</a></li>
                        </ul>
                    </li>
                    # IF C_ADMIN_LINKS_2 #
                        # START admin_links_2 #
                            # INCLUDE admin_links_2.MODULE_MENU #
                        # END admin_links_2 #
                    # ENDIF #
                </ul>
            </div>
        </div>

        <div id="openmodal-tools" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
            <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-addons"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.addons}</a>
                    <h5>{@menu.tools}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-users">{@menu.users} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-sync"></i>{@menu.cache}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}"><i aria-hidden="true" class="fa fa-fw fa-sync"></i>{@menu.cache}</a></li>
                            <li><a href="${relative_url(AdminCacheUrlBuilder::clear_syndication_cache())}"><i aria-hidden="true" class="fa fa-fw fa-rss"></i>{@menu.cache.syndication}</a></li>
                            <li><a href="${relative_url(AdminCacheUrlBuilder::clear_css_cache())}"><i aria-hidden="true" class="fab fa-fw fa-css3"></i>{@menu.cache.css}</a></li>
                            <li><a href="${relative_url(AdminCacheUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{@menu.configuration}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-exclamation-triangle"></i>{@menu.errors}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminErrorsUrlBuilder::logged_errors())}"><i aria-hidden="true" class="fa fa-fw fa-exclamation-circle"></i>{@menu.errors.archived}</a></li>
                            <li><a href="${relative_url(AdminErrorsUrlBuilder::list_404_errors())}"><i aria-hidden="true" class="fa fa-fw fa-ban"></i>{@menu.errors.404}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-building"></i>{@menu.server}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminServerUrlBuilder::phpinfo())}"><i aria-hidden="true" class="fa fa-fw fa-info"></i>{@menu.server.phpinfo}</a></li>
                            <li><a href="${relative_url(AdminServerUrlBuilder::system_report())}"><i aria-hidden="true" class="fa fa-fw fa-info-circle"></i>{@menu.server.system.report}</a></li>
                        </ul>
                    </li>
                    # IF C_ADMIN_LINKS_3 #
                        # START admin_links_3 #
                            # INCLUDE admin_links_3.MODULE_MENU #
                        # END admin_links_3 #
                    # ENDIF #
                </ul>
            </div>
        </div>

        <div id="openmodal-users" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
            <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-tools"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.tools}</a>
                    <h5>{@menu.users}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-content">{@menu.content} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-user"></i>{@menu.users}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminMembersUrlBuilder::management())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminMembersUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                            <li><a href="${relative_url(AdminMembersUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{@menu.configuration}</a></li>
                            <li><a href="{PATH_TO_ROOT}/user/moderation_panel.php"><i aria-hidden="true" class="fa fa-fw fa-ban"></i>{@menu.sanctions.manager}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-users"></i>{@menu.groups}</span>
                        <ul class="level-2">
                            <li><a href="{PATH_TO_ROOT}/admin/admin_groups.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="{PATH_TO_ROOT}/admin/admin_groups.php?add=1"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-list"></i>{@menu.extended.fields}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::fields_list())}"><i aria-hidden="true" class="fa fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminExtendedFieldsUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                        </ul>
                    </li>
                    # IF C_ADMIN_LINKS_4 #
                        # START admin_links_4 #
                            # INCLUDE admin_links_4.MODULE_MENU #
                        # END admin_links_4 #
                    # ENDIF #
                </ul>
            </div>
        </div>

        <div id="openmodal-content" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
            <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-users"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.users}</a>
                    <h5>{@menu.content}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-modules">{@menu.modules} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="far fa-fw fa-square"></i>{@menu.content}</span>
                        <ul class="level-2">
                            <li><a href="{PATH_TO_ROOT}/admin/menus/menus.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.menus.management}</a></li>
                            <li><a href="${relative_url(AdminContentUrlBuilder::content_configuration())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.configuration}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="fa fa-fw fa-file-alt"></i>{@menu.files}</span>
                        <ul class="level-2">
                            <li><a href="{PATH_TO_ROOT}/admin/admin_files.php"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminFilesUrlBuilder::configuration())}"><i aria-hidden="true" class="fa fa-cogs"></i>{@menu.configuration}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="far fa-fw fa-comment"></i>{@menu.comments}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(UserUrlBuilder::comments())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="{PATH_TO_ROOT}/admin/content/?url=/comments/config/"><i aria-hidden="true" class="fa fa-fw fa-cogs"></i>{@menu.configuration}</a></li>
                        </ul>
                    </li>
                    <li>
                        <span class="modal-menu-title"><i aria-hidden="true" class="far fa-fw fa-smile"></i>{@menu.smileys}</span>
                        <ul class="level-2">
                            <li><a href="${relative_url(AdminSmileysUrlBuilder::management())}"><i aria-hidden="true" class="fa fa-fw fa-cog"></i>{@menu.management}</a></li>
                            <li><a href="${relative_url(AdminSmileysUrlBuilder::add())}"><i aria-hidden="true" class="fa fa-fw fa-plus"></i>{@menu.add}</a></li>
                        </ul>
                    </li>
                    # IF C_ADMIN_LINKS_5 #
                        # START admin_links_5 #
                            # INCLUDE admin_links_5.MODULE_MENU #
                        # END admin_links_5 #
                    # ENDIF #
                </ul>
            </div>
        </div>

        <div id="openmodal-modules" class="modal">
            <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
            <div class="modal-content">
            <span class="big error hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                <div class="next-menu">
                    <a href="#change-modal" class="modal-button --openmodal-content"><i aria-hidden="true" class="fa fa-arrow-left"></i> {@menu.content}</a>
                    <h5>{@menu.modules}</h5>
                    <a href="#change-modal" class="modal-button --openmodal-administration">{@menu.administration} <i aria-hidden="true" class="fa fa-arrow-right"></i></a>
                </div>
                <ul class="modal-menu">
                    # IF C_ADMIN_LINKS_6 #
                        # START admin_links_6 #
                            # INCLUDE admin_links_6.MODULE_MENU #
                        # END admin_links_6 #
                    # ENDIF #
                </ul>
            </div>
        </div>
    </div>

</div>
