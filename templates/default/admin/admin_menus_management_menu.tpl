# IF C_MINI #
<div class="module_mini_container" style="{STYLE}">
    <div class="module_mini_top">
        <span id="m{IDMENU}"></span>
        <h5 class="sub_title">
            {NAME}
			# IF C_EDIT #
			    <a href="{EDIT}" title="{L_EDIT}" />
			        <img src="../templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle">
			    </a>
			# ENDIF #
			# IF C_DEL #
			    <a href="{DEL}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
			        <img src="../templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle">
			    </a>
			# ENDIF #
          </h5>
    </div><br />
    <div class="module_mini_contents">
        <p>
            <select name="{IDMENU}_enabled" onchange="document.location = {U_ONCHANGE_ENABLED}">
                <option value="1" {SELECT_ENABLED}>{L_ENABLED}</option>
                <option value="0" {SELECT_DISABLED}>{L_DISABLED}</option>
            </select>
        </p>                        
        <div style="width:100px;height:30px;margin:auto;">
            <div style="float:left">
                # IF C_UP #
                    <a href="admin_menus.php?action=up&amp;id={IDMENU}#m{IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt=""></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
                # IF C_DOWN #
                     <a href="admin_menus.php?action=down&amp;id={IDMENU}#m{IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt=""></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
            </div>
            <div style="position:relative;float:right">
                <div style="position:absolute;z-index:100;margin-top:155px;margin-left:-70px;float:left;display:none;" id="movemenu{IDMENU}">
                    <div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);">
                        <div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_HEADER}&amp;id={IDMENU}">{L_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_SUBHEADER}&amp;id={IDMENU}">{L_SUB_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_LEFT}&amp;id={IDMENU}">{L_LEFT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_TOPCENTRAL}&amp;id={IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_BOTTOMCENTRAL}&amp;id={IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_RIGHT}&amp;id={IDMENU}">{L_RIGHT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_TOPFOOTER}&amp;id={IDMENU}">{L_TOP_FOOTER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_FOOTER}&amp;id={IDMENU}">{L_FOOTER}</a></div
                    </div>
                </div>
                <a href="javascript:menu_display_block('menu{IDMENU}');" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
            </div>
        </div>
        
        <p>{CONTENTS}</p>&nbsp;
    </div>
    <div class="module_mini_bottom">
    </div>
</div>
# ELSE #
<div style="margin:15px;width:auto;{ADDITIONAL_STYLE}" class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">
        <strong><span id="m{IDMENU}"></span></strong>
        <h5 class="sub_title">
            {NAME}
            # IF C_EDIT #
                <a href="{EDIT}" title="{L_EDIT}" />
                    <img src="../templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle">
                </a>
            # ENDIF #
            # IF C_DEL #
                <a href="{DEL}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
                    <img src="../templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle">
                </a>
            # ENDIF #
          </h5>
    </div>
        
    <div class="module_contents">
        <p>
            <select name="{IDMENU}_enabled" onchange="document.location = {U_ONCHANGE_ENABLED}">
                <option value="1" {SELECT_ENABLED}>{L_ENABLED}</option>
                <option value="0" {SELECT_DISABLED}>{L_DISABLED}</option>
            </select>
        </p>
        <div style="width:100px;height:30px;">
            <div style="float:left">
                # IF C_UP #
                    <a href="admin_menus.php?action=up&amp;id={IDMENU}#m{IDMENU}"><img src="../templates/{THEME}/images/admin/up.png" alt=""></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
                # IF C_DOWN #
                     <a href="admin_menus.php?action=down&amp;id={IDMENU}#m{IDMENU}"><img src="../templates/{THEME}/images/admin/down.png" alt=""></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
            </div>
            <div style="position:relative;float:right">
                <div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{IDMENU}">
                    <div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);">
                        <div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#EE713A;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_HEADER}&amp;id={IDMENU}">{L_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_SUBHEADER}&amp;id={IDMENU}">{L_SUB_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#9B8FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_LEFT}&amp;id={IDMENU}">{L_LEFT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_TOPCENTRAL}&amp;id={IDMENU}">{L_TOP_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_BOTTOMCENTRAL}&amp;id={IDMENU}">{L_BOTTOM_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#EA6FFF;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_RIGHT}&amp;id={IDMENU}">{L_RIGHT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#61B85C;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_TOPFOOTER}&amp;id={IDMENU}">{L_TOP_FOOTER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="admin_menus.php?move={I_FOOTER}&amp;id={IDMENU}">{L_FOOTER}</a></div>
                    </div>
                </div>
            </div>
            <a href="javascript:menu_display_block('menu{IDMENU}');" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="../templates/{THEME}/images/move.png" alt="" /></a>
        </div>
        <p>{CONTENTS}</p>
        <br /><br />
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>
# ENDIF #