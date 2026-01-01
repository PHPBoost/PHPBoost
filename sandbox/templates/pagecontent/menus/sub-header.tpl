<nav id="cssmenu-sub-header" class="cssmenu cssmenu-horizontal">
    <ul class="level-0">
        <li class="has-sub">
            <span class="cssmenu-title" href="#">SubMenu Element</span>
            <ul class="level-1">
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="cssmenu-title" href="#">Element</a>
        </li>
        <li class="has-sub">
            <span class="cssmenu-title">SubMenu Element</span>
            <ul class="level-1">
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li class="has-sub">
                    <span class="cssmenu-title" href="#">SubMenu Element</span>
                    <ul class="level-2">
                        <li>
                            <a class="cssmenu-title" href="#">Element</a>
                        </li>
                        <li class="has-sub">
                            <span class="cssmenu-title" href="#">SubMenu Element</span>
                            <ul class="level-3">
                                <li>
                                    <a class="cssmenu-title" href="#">Element</a>
                                </li>
                                <li>
                                    <a class="cssmenu-title" href="#">Element</a>
                                </li>
                                <li>
                                    <a class="cssmenu-title" href="#">Element</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<script>jQuery("#cssmenu-sub-header").menumaker({ title: "cssmenu-sub-header", format: "multitoggle", breakpoint: 768 });</script>

<a class="toggle-top pushmenu-toggle">
    <i class="fa fa-bars"></i>
    <span>Push Menu Top</span>
</a>
<nav id="pushmenu-top" class="pushnav">
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
<script>
    $('#pushmenu-top').pushmenu({
		customToggle: jQuery('.toggle-top'), // null
		navTitle: 'Push Menu Top', // null
		pushContent: '#push-container',
		position: 'top', // left, right, top, bottom
		levelOpen: 'overlap', // 'overlap', 'expand', false
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-top',
		disableBody: true,
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: 'Close',
		insertBack: true,
		labelBack: 'Back'
    });
</script>
