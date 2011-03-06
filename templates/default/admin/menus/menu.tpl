# IF C_MINI # 
<div class="module_mini_container" style="{STYLE}">
    <div class="module_mini_top">
        <span id="m{IDMENU}"></span>
        <h5 class="sub_title">
            {NAME}
			# IF C_EDIT #
			    <a href="{U_EDIT}" title="{L_EDIT}">
			        <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle" />
			    </a>
			# ENDIF #
			# IF C_DEL #
			    <a href="{U_DELETE}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
			        <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle" />
			    </a>
			# ENDIF #
          </h5>
    </div><br />
    <div class="module_mini_contents">
        <p>
            # IF C_MENU_ACTIVATED #
            <select name="{IDMENU}_enabled" onchange="document.location = {U_ONCHANGE_ENABLED}">
                <option value="1" {SELECT_ENABLED}>{L_ENABLED}</option>
                <option value="0" {SELECT_DISABLED}>{L_DISABLED}</option>
            </select>
            # ENDIF #
        </p>                        
        <div style="width:100px;height:30px;margin:auto;">
            <div style="float:left">
                # IF C_UP #
                    <a href="{U_UP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/up.png" alt="" /></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
                # IF C_DOWN #
                     <a href="{U_DOWN}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/down.png" alt="" /></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
            </div>
            <div style="position:relative;float:right">
                <div style="position:absolute;z-index:99;margin-top:155px;margin-left:-70px;float:left;display:none;" id="movemenu{IDMENU}">
                    <div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);">
                        <div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#cee6cd;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_HEADER}">{L_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_SUBHEADER}">{L_SUB_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#afafaf;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_LEFT}">{L_LEFT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_TOPCENTRAL}">{L_TOP_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_BOTTOMCENTRAL}">{L_BOTTOM_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#bdaeca;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_RIGHT}">{L_RIGHT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#90ab8e;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_TOPFOOTER}">{L_TOP_FOOTER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_FOOTER}">{L_FOOTER}</a></div>
                    </div>
                </div>
                <a href="javascript:menu_display_block('menu{IDMENU}');" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/move.png" alt="" /></a>
            </div>
        </div>
        <div style="margin-top:10px;">
			{CONTENTS}
		</div>
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
                <a href="{U_EDIT}" title="{L_EDIT}">
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle" />
                </a>
            # ENDIF #
            # IF C_DEL #
                <a href="{U_DELETE}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle" />
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
                    <a href="{U_UP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/up.png" alt="" /></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
                # IF C_DOWN #
                     <a href="{U_DOWN}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/down.png" alt="" /></a>
                # ELSE #
                    <div style="float:left;width:32px;"> </div>
                # ENDIF #
            </div>
            <div style="position:relative;float:right">
                <div style="position:absolute;z-index:100;margin-top:155px;margin-left:-100px;float:left;display:none;" id="movemenu{IDMENU}">
                    <div class="bbcode_block" style="width:170px;overflow:auto;" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);">
                        <div style="margin-bottom:4px;" class="text_small"><strong>{L_MOVETO}</strong>:</div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#cee6cd;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_HEADER}">{L_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#CCFF99;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_SUBHEADER}">{L_SUB_HEADER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#afafaf;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_LEFT}">{L_LEFT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FFE25F;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_TOPCENTRAL}">{L_TOP_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#FF5F5F;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_BOTTOMCENTRAL}">{L_BOTTOM_CENTRAL_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#bdaeca;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_RIGHT}">{L_RIGHT_MENU}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#90ab8e;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_TOPFOOTER}">{L_TOP_FOOTER}</a></div>
                        <div style="float:left;margin-left:5px;height:10px;width:10px;background:#A8D1CB;border:1px solid black"></div> <div style="clear:right"><a href="{U_MOVE}&amp;move={I_FOOTER}">{L_FOOTER}</a></div>
                    </div>
                </div>
            </div>
            <a href="javascript:menu_display_block('menu{IDMENU}');" onmouseover="menu_hide_block('menu{IDMENU}', 1);" onmouseout="menu_hide_block('menu{IDMENU}', 0);" class="bbcode_hover" title="{L_MOVETO}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/move.png" alt="" /></a>
        </div>
        <div style="margin:10px 0px;">
			{CONTENTS}
		</div>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>
# ENDIF #
