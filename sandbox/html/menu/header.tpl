<nav id="cssmenu-top-header" class="cssmenu cssmenu-horizontal">
    <ul class="level-0">
        <li>
            <a class="cssmenu-title" href="#">
                <img src="sandbox_mini.png" alt="Element" width="16" height="16" />
                <span>Element</span>
            </a>
        </li>
        <li class="has-sub">
            <span class="cssmenu-title">SubMenu Element</span>
            <ul class="level-1">
                <li>
                    <a class="cssmenu-title" href="#">
                        <img src="../sandbox/templates/images/square.png" alt="Element" width="64" height="64" />
                        <span>Lorem ipsum dolor sit amet</span>
                    </a>
                </li>
                <li class="has-sub">
                    <span class="cssmenu-title" href="#">
                        <img src="../sandbox/sandbox.png" alt="Element" width="16" height="16" />
                        <span>SubMenu Element</span>
                    </span>
                    <ul class="level-2">
                        <li>
                            <a class="cssmenu-title" href="#">
                                <img src="../sandbox/sandbox_mini.png" alt="Element" width="16" height="16" />
                                <span>Element</span>
                            </a>
                        </li>
                        <li class="has-sub">
                            <span class="cssmenu-title" href="#">SubMenu Element</span>
                            <ul class="level-3">
                                <li>
                                    <a class="cssmenu-title" href="#">
                                        <img src="../sandbox/sandbox_mini.png" alt="Element" width="16" height="16" />
                                        <span>Element</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="cssmenu-title" href="#">
                                        <img src="../sandbox/sandbox_mini.png" alt="Element" width="16" height="16" />
                                        <span>Element</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="cssmenu-title" href="#">
                                        <img src="../sandbox/sandbox_mini.png" alt="Element" width="16" height="16" />
                                        <span>Element</span>
                                    </a>
                                </li>
                            </ul>
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
<script>jQuery("#cssmenu-top-header").menumaker({ title: "cssmenu-top-header", format: "multitoggle", breakpoint: 768 });</script>
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
