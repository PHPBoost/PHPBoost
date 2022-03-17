<div class="cell-mini cell-mini-vertical cell-tile cssmenu-content">
    <div class="cell">
        <div class="cell-header hidden-small-screens">
            <h6 class="cell-name">Vertical dropdown</h6>
        </div>
        <div class="cell-body">
            <nav id="cssmenu-left" class="cssmenu cssmenu-vertical cssmenu-left cssmenu-with-submenu">
                <ul class="level-0">
                    <li>
                        <a class="cssmenu-title" href="#"><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, possimus?</span></a>
                    </li>
                    <li class="has-sub">
                        <span class="cssmenu-title"><span>SubMenu Element</span></span>
                        <ul class="level-1">
                            <li>
                                <a class="cssmenu-title" href="#">
                                    <img src="../../templates/__default__/theme/images/logo.svg" alt="Element" width="16" height="18" />
                                    <span>Lorem ipsum dolor sit amet.</span>
                                </a>
                            </li>
                            <li class="has-sub">
                                <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                                <ul class="level-2">
                                    <li>
                                        <a class="cssmenu-title" href="#"><span>Element</span></a>
                                    </li>
                                    <li class="has-sub">
                                        <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                                        <ul class="level-3">
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                        <ul class="level-1">
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script>jQuery("#cssmenu-left").menumaker({ title: "cssmenu-left", format: "multitoggle", breakpoint: 768 }); </script>
<script>
// add picture width
    jQuery('.cssmenu-title').each(function(){
        if(jQuery(this).children('img').length) {
            var imgWidth = jQuery(this).children('img').outerWidth(),
                marginWidth = jQuery(this).children('span').css('marginLeft');
            jQuery(this).css('padding-right', 'calc(' + imgWidth + 'px + ' + marginWidth + ')');
        }
    });
</script>

<div id="links-menu-left" class="cell-mini cell-mini-vertical cell-tile cssmenu-content">
    <div class="cell">
        <div class="cell-header menu-vertical-0">
            <a class="toggle-left pushmenu-toggle">
                <i class="fa fa-bars"></i>
                <span>Push Menu Left</span>
            </a>
            <nav id="pushmenu-left" class="pushnav">
                <ul class="level-0">
                    <li>
                        <a class="cssmenu-title" href="#"><span>Element</span></a>
                    </li>
                    <li class="has-sub">
                        <span class="cssmenu-title"><span>SubMenu Elemen</span>t</span>
                        <ul class="level-1">
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li class="has-sub">
                                <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                                <ul class="level-2">
                                    <li>
                                        <a class="cssmenu-title" href="#"><span>Element</span></a>
                                    </li>
                                    <li class="has-sub">
                                        <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                                        <ul class="level-3">
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                            <li>
                                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <span class="cssmenu-title" href="#"><span>SubMenu Element</span></span>
                        <ul class="level-1">
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                            <li>
                                <a class="cssmenu-title" href="#"><span>Element</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script>
    $('#pushmenu-left').pushmenu({
		customToggle: jQuery('.toggle-left'), // null
		navTitle: 'Push Menu Left', // null
		pushContent: '#push-container',
		position: 'left', // left, right, top, bottom
		levelOpen: 'overlap', // 'overlap', 'expand', false
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-left',
		disableBody: true,
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: 'Close',
		insertBack: true,
		labelBack: 'Back'
    });
</script>
