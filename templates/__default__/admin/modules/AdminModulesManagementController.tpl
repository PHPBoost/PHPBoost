<!-- === /templates/__default__/admin/modules/AdminModulesManagementController.tpl === -->
# INCLUDE MESSAGE_HELPER #
# START errors #
	# INCLUDE errors.MESSAGE_HELPER #
# END errors #

<div class="text-helper">
	<span class="message-helper bgc warning">{@H|addon.modules.warning.delete}</span>
	<span class="message-helper bgc notice">{@addon.modules.warning.install}</span>
</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-modules-container">
        <header class="legend">{@addon.modules.installed}</header>
        <div class="cell-list">
            <ul class="col-v-3">
                # START installed_modules #
                    <li class="li-stretch addon-row addon# IF NOT installed_modules.C_IS_ACTIVATED # disabled-addon# ENDIF ## IF NOT installed_modules.C_COMPATIBLE # not-compatible error# ENDIF #">
                        <div class="addon-name align-left mini-checkbox" id="module-{installed_modules.MODULE_NUMBER}">
                            # IF C_SEVERAL_MODULES_INSTALLED #
                                # IF installed_modules.C_COMPATIBLE #
                                    <label class="checkbox" for="multiple-checkbox-{installed_modules.MODULE_NUMBER}">
                                        <input type="checkbox" id="multiple-checkbox-{installed_modules.MODULE_NUMBER}" name="delete-checkbox-{installed_modules.MODULE_NUMBER}"# IF NOT installed_modules.C_DELETE # disabled# ENDIF # />
                                        <span>&nbsp;</span>
                                    </label>
                                # ENDIF #
                            # ENDIF #
                            # IF installed_modules.C_COMPATIBLE #
                                # IF installed_modules.C_IS_ACTIVATED #
                                    <span class="success" aria-label="{@common.enabled}"><i class="fa fa-fw fa-check" aria-hidden="true"></i></span>
                                # ELSE #
                                    <span class="warning" aria-label="{@common.disabled}"><i class="fa fa-fw fa-times" aria-hidden="true"></i></span>
                                # ENDIF #
                            # ELSE #
                                <span class="error" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i></span>
                            # ENDIF #
                            {installed_modules.MODULE_NAME}
                        </div>
                        <div class="addon-infos addon-large">
                            <button onclick="return false;" class="button button-mini default modal-button --infos-module-{installed_modules.MODULE_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></button>
                            <div id="infos-module-{installed_modules.MODULE_NUMBER}" class="modal modal-half">
                                <div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
                                <div class="modal-content">
                                    <span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-list">
                                        <ul>
                                            <li class="li-stretch">
                                                <h2>
                                                    # IF installed_modules.C_THUMBNAIL #
                                                        <img src="{installed_modules.THUMBNAIL_URL}" alt="{installed_modules.MODULE_NAME} thumbnail" class="addon-thumbnail" />
                                                    # ELSEIF installed_modules.C_FA_ICON #
                                                        <i class="fa {installed_modules.FA_ICON}" aria-hidden="true"></i>
                                                    # ELSEIF installed_modules.C_HEXA_ICON #
                                                        <span class="hexa-icon bigger">{installed_modules.HEXA_ICON}</span>
                                                    # ELSE #
                                                        <i class="far fa-fw fa-puzzle-piece" aria-hidden="true"></i>
                                                    # ENDIF #
                                                    {installed_modules.MODULE_NAME}
                                                </h2>
                                            </li>
                                            <li>
                                                <span class="text-strong">{@common.description}</span>
                                                {installed_modules.DESCRIPTION}
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@common.author}</span>
                                                <span>
                                                    {installed_modules.AUTHOR}
                                                    # IF installed_modules.C_AUTHOR_EMAIL # <a href="mailto:{installed_modules.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
                                                    # IF installed_modules.C_AUTHOR_WEBSITE # <a href="{installed_modules.AUTHOR_WEBSITE}" target="_blank" rel="noopener" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
                                                </span>
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@category.category}</span>
                                                {installed_modules.GENRE_NAME}
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@common.version}</span>
                                                {installed_modules.VERSION}
                                            </li>
                                            <li class="li-stretch# IF NOT installed_modules.C_COMPATIBLE_VERSION # not-compatible bgc-full error# ENDIF #">
                                                <span class="text-strong">{@addon.compatibility}</span>
                                                {installed_modules.COMPATIBILITY}
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@common.creation.date}</span>
                                                {installed_modules.CREATION_DATE}
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@common.last.update}</span>
                                                {installed_modules.LAST_UPDATE}
                                            </li>
                                            <li class="li-stretch">
                                                <span class="text-strong">{@addon.modules.php.version}</span>
                                                {installed_modules.PHP_VERSION}
                                            </li>
                                            # IF NOT installed_modules.C_COMPATIBLE_ADDON #
                                                <li class="bgc-full error">{@addon.modules.not.module}</li>
                                            # ENDIF #
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <button onclick="window.open('{installed_modules.U_DOCUMENTATION}', '_blank', 'noopener'); return false;" class="button button-mini bgc # IF installed_modules.C_DOCUMENTATION #submit# ELSE #visitor# ENDIF #" aria-label="{@addon.modules.documentation}"# IF NOT installed_modules.C_DOCUMENTATION # disabled # ENDIF #><i class="fa fa-fw fa-book" aria-hidden="true"></i></button></a>
                            # IF installed_modules.C_COMPATIBLE #
                                # IF installed_modules.C_IS_ACTIVATED #
                                    <button type="submit" class="button button-mini bgc-full notice" name="disable-{installed_modules.MODULE_ID}" aria-label="{@H|addon.module.disable}" value="true"><i class="far fa-fw fa-eye-slash" aria-hidden="true"></i></button>
                                # ELSE #
                                    <button type="submit" class="button button-mini bgc-full success" name="enable-{installed_modules.MODULE_ID}" aria-label="{@common.enable}" value="true"><i class="far fa-fw fa-eye" aria-hidden="true"></i></button>
                                # ENDIF #
                            # ENDIF #
                            # IF installed_modules.C_DELETE #
                                # IF C_IS_LOCALHOST #
                                    <button type="submit" class="button button-mini bgc-full warning" name="uninstall-{installed_modules.MODULE_ID}" aria-label="{@H|addon.module.uninstall}" value="true" data-confirmation="uninstall-element"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
                                # ENDIF #
                                <button type="submit" class="button button-mini bgc-full error" name="delete-{installed_modules.MODULE_ID}" aria-label="{@H|addon.module.delete}" value="true" data-confirmation="delete-element"><i class="far fa-fw fa-trash-can" aria-hidden="true"></i></button>
                            # ENDIF #
                        </div>
                    </li>
                # END installed_modules #
            </ul>
        </div>
		<footer>
			<input type="hidden" name="token" value="{TOKEN}">
		</footer>
	</section>

	# IF C_SEVERAL_MODULES_INSTALLED #
        <div class="addon-menu-container multiple-select-menu-container">
            <a href="#" class="multiple-select-menu addon-menu-title bgc-full link-color">{@addon.multiple.select}</a>
            <ul class="addon-menu-content">
                <li class="addon-menu-checkbox mini-checkbox select-all-checkbox bgc-full link-color">
                    <label class="checkbox" for="toggle-all-checkbox">
                        <input type="checkbox" class="check-all" id="toggle-all-checkbox" name="toggle-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
                        <span aria-label="{@addon.modules.select.all}">&nbsp;</span>
                    </label>
                </li>
                <li class="addon-menu-item"><button type="submit" name="activate-selected-modules" value="true" class="button bgc-full success" id="activate-all-button">{@addon.multiple.enable}</button></li>
                <li class="addon-menu-item"><button type="submit" name="deactivate-selected-modules" value="true" class="button bgc-full notice" id="deactivate-all-button">{@addon.multiple.disable}</button></li>
                # IF C_IS_LOCALHOST #
                    <li class="addon-menu-item"><button type="submit" name="uninstall-selected-modules" value="true" class="button bgc-full warning" id="uninstall-all-button" data-confirmation="uninstall-elements">{@addon.multiple.uninstall}</button></li>
                # ENDIF #
                <li class="addon-menu-item"><button type="submit" name="delete-selected-modules" value="true" class="button bgc-full error" id="delete-all-button" data-confirmation="delete-elements">{@addon.multiple.delete}</button></li>
            </ul>
        </div>
	# ENDIF #
</form>

<script>
	opensubmenu('.addon-menu-title', {
		osmTarget: '.addon-menu-title',
		osmCloseExcept : '.addon-menu-checkbox, .addon-menu-checkbox *'
	});
</script>
