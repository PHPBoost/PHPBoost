# IF C_DISPLAY #
    <li class="treelinks# IF C_HAS_SUB_LINK # has-sub# ENDIF #">
        <span class="modal-menu-title offload ">
            # IF C_HAS_IMG #
                <img src="{IMG}" alt="{NAME}"/>
            # ELSEIF C_FA_ICON #
                <i class="{FA_ICON}" aria-hidden="true"></i>
            # ELSEIF C_HEXA_ICON #
                <span class="hexa-icon">{HEXA_ICON}</span>
            # ENDIF #
            {NAME}
        </span>
        # IF C_HAS_SUB_LINK #
            <ul class="level-2">
                # START element #
                    # INCLUDE element.ELEMENT #
                # END element #
            </ul>
        # ELSE #
            <ul class="level-2">
                <li>
                    <a href="{U_LINK}" class="modal-menu-title offload ">
                        <i class="fa fa-fw fa-cog" aria-hidden="true"></i>
                        ${LangLoader::get_message('menu.configuration', 'menu-lang')}
                    </a>
                </li>
            </ul>
        # ENDIF #
    </li>
# ENDIF #
