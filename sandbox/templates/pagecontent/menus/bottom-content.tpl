<nav id="cssmenu-bottom-content" class="cssmenu cssmenu-group">
    <ul class="level-0">
        <li>
            <a class="cssmenu-title" href="#"><span>Element</span></a>
        </li>
        <li class="current">
            <a class="cssmenu-title" href="#"><span>Element</span></a>
        </li>
        <li>
            <a class="cssmenu-title" href="#"><span>Element</span></a>
        </li>
        <li>
            <a class="cssmenu-title" href="#"><span>Element</span></a>
        </li>
    </ul>
</nav>
<script>jQuery("#cssmenu-bottom-content").menumaker({ title: "cssmenu-bottom-content", format: "multitoggle", breakpoint: 768 });</script>

<a class="toggle-bottom-content pushmenu-toggle">
    <i class="fa fa-bars"></i>
    <span>Push Menu Bottom expand</span>
</a>
<nav id="pushmenu-bottom-content" class="pushnav">
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
    $('#pushmenu-bottom-content').pushmenu({
		maxWidth: false,
		customToggle: jQuery('.toggle-bottom-content'), // null
		navTitle: 'Push Menu Bottom expand', // null
		pushContent: '#push-container',
		position: 'bottom', // left, right, top, bottom
		levelOpen: 'expand', // 'overlap', 'expand', false
		levelTitles: true, // overlap only
		levelSpacing: 40, // px - overlap only
		navClass: 'pushmenu-nav-bottom-content',
		disableBody: true,
		closeOnClick: true, // if disableBody is true
		insertClose: true,
		labelClose: 'Close',
		insertBack: true,
		labelBack: 'Back'
    });
</script>
