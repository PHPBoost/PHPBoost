<nav id="cssmenu-top-footer" class="cssmenu cssmenu-static">
    <ul class="level-0">
        <li class="has-sub">
            <span class="cssmenu-title" href="#">SubMenu Element</span>
            <ul class="level-1">
                <li class="has-sub">
                    <span class="cssmenu-title">SubMenu Element</span>
                    <ul class="level-2">
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
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li class="has-sub">
                    <span class="cssmenu-title">SubMenu Element</span>
                    <ul class="level-2">
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
        <li class="has-sub">
            <span class="cssmenu-title" href="#">SubMenu Element</span>
            <ul class="level-1">
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
            </ul>
        </li>
        <li class="has-sub">
            <span class="cssmenu-title" href="#">SubMenu Element</span>
            <ul class="level-1">
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
                <li>
                    <a class="cssmenu-title" href="#">Element</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<script>jQuery("#cssmenu-top-footer").menumaker({ title: "cssmenu-top-footer", format: "multitoggle", breakpoint: 768, static: true });</script>

<a class="toggle-bottom pushmenu-toggle">
    <i class="fa fa-bars"></i>
    <span>Push Menu Bottom</span>
</a>
<nav id="pushmenu-bottom" class="pushnav">
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
    $('#pushmenu-bottom').pushmenu({
		maxWidth: false,
		customToggle: jQuery('.toggle-bottom'), // null
		navTitle: 'Push Menu Bottom', // null
		pushContent: '#push-container',
		position: 'bottom', // left, right, top, bottom
		levelOpen: 'overlap', // 'overlap', 'expand', false
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-bottom',
		disableBody: true,
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: 'Close',
		insertBack: true,
		labelBack: 'Back'
    });
</script>
