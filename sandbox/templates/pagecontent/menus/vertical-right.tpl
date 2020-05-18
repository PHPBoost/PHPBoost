<div class="cell-mini cell-mini-vertical cell-tile cssmenu-content">
    <div class="cell">
        <div class="cell-header hidden-small-screens">
            <h6 class="cell-name">Vertical dropdown</h6>
        </div>
        <div class="cell-body">
            <nav id="cssmenu-right" class="cssmenu cssmenu-vertical cssmenu-right cssmenu-with-submenu">
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
<script>jQuery("#cssmenu-right").menumaker({ title: "cssmenu-right", format: "multitoggle", breakpoint: 768 });</script>

<div id="links-menu-right" class="cell-mini cell-mini-vertical cell-tile cssmenu-content">
    <div class="cell">
        <div class="cell-header menu-vertical-0">
            <a class="toggle-right pushmenu-toggle">
                <i class="fa fa-bars"></i>
                <span>Push Menu Right</span>
            </a>
            <nav id="pushmenu-right" class="pushnav">
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
    $('#pushmenu-right').pushmenu({
		customToggle: jQuery('.toggle-right'), // null
		navTitle: 'Push Menu Right', // null
		pushContent: false,
		position: 'right', // left, right, top, bottom
		levelOpen: false, // 'overlap', 'expand', false
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-right',
		disableBody: false,
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: 'Close',
		insertBack: true,
		labelBack: 'Back'
    });
</script>
