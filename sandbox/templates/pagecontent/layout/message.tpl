<div id="messages" class="sandbox-block">
    <article>
        <header>
            <h5>{@sandbox.layout.messages.and.coms}</h5>
        </header>
        <div class="content">
            <article id="comID" class="message-container cell-tile cell-modal modal-container" itemscope="itemscope" itemtype="https://schema.org/Comment">
                <header class="message-header-container">
                    <img class="message-user-avatar" src="{NO_AVATAR_URL}" alt="{@common.avatar}">
                    <div class="message-header-infos">
                        <div class="message-user-container">
                            <h3 class="message-user-name">
                                <span class="smaller" aria-label="{@user.online}/{@user.offline}">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                                <span class="administrator" itemprop="author">{@sandbox.layout.messages.login}</span>
								<span class="smaller" aria-label="{@common.see.profile.datas}" data-modal data-target="message-user-datas-ID">
									<i class="far fa-eye" aria-hidden="true"></i>
								</span>
                            </h3>
                            <div class="controls message-user-infos-preview">
                                <a href="#" class="user-group small group-ID offload"></a>
                                <span class="pinned administrator small">{@user.administrator}</span>
                            </div>
                        </div>
                        <div class="message-infos">
                            <time datetime="{TODAY_TIME}" itemprop="datePublished">{TODAY_TIME}</time>
                            <a href="#ID" class="copy-link-to-clipboard" aria-label="{@common.copy.link.to.clipboard}">\#ID</a>
                        </div>
                    </div>
                </header>
                <div id="message-user-datas-ID" class="modal modal-animation">
                    <div class="close-modal" aria-label="{@common.close}"></div>
                    <div class="content-panel cell">
                        <div class="cell-list">
                            <ul>
                                <li class="li-stretch">
                                    <span>{@sandbox.layout.messages.level}</span>
                                    <img src="{PATH_TO_ROOT}/forum/templates/images/ranks/rank_admin.png" />
                                </li>
                                <li class="li-stretch">
                                    <span>{@common.see.profile}</span>
                                    <a href="#" class="msg-link-pseudo administrator offload">admin</a>
                                </li>
                                <li class="li-stretch">
                                    <span>{@user.pm}</span>
                                    <a href="#" class="button submit smaller offload"><i class="fa fa-people-arrows" aria-hidden="true"></i></a>
                                </li>
                                <li class="li-stretch">
                                    <span>{@user.email}</span>
                                    <a href="#" class="button submit smaller offload"><i class="fa iboost fa-iboost-email" aria-hidden="true"></i></a>
                                </li>
                                <li>{@user.groups} :</li>
                                <li class="li-stretch">
                                    <a href="#">Com</a>
                                    <img src="http://data.babsoweb.com/babsodata/phpboost/graph/group-icon/com.png" alt="group picture" />
                                </li>
                                <li>
                                    <span>{@sandbox.layout.user.sign}</span>
                                </li>
                                <li>
                                    <img src="https://resources.phpboost.com/documentation/banners/banner_phpboost_01.jpg" alt="group picture" />
                                </li>
                                <li class="li-stretch">
                                    <span>{@user.punishments} : 0% </span>
                                    <span>
                                        <a href="#"><i class="fa fa-exclamation-trianglewarning"></i></a>
                                        <a href="#"><i class="fa fa-user-lock link-color"></i></a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="message-content">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi autem sequi quam ab amet culpa nobis vitae rerum laborum nulla!</p>
                </div>
            </article>
        </div>
    </article>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;article id="Id" class="message-container (message-small/message-offset) cell-tile cell-modal modal-container" itemscope="itemscope" itemtype="https://schema.org/Comment">
    &lt;header class="message-header-container">
        &lt;img class="message-user-avatar" src="/image/avatar/link" alt="{@common.avatar}">
        &lt;div class="message-header-infos">
            &lt;div class="message-user-container">
                &lt;h3 class="message-user-name">
                    &lt;span class="smaller" aria-label="{@user.online}/{@user.offline}">
                        &lt;i class="fa fa-user" aria-hidden="true">&lt;/i>
                    &lt;/span>
                    &lt;span class="administrator" itemprop="author">{@sandbox.layout.messages.login}&lt;/span>
                    &lt;span class="smaller" aria-label="{@common.see.profile.datas}" data-modal data-target="message-user-datas-ID">
                        &lt;i class="far fa-eye" aria-hidden="true">&lt;/i>
                    &lt;/span>
                &lt;/h3>
                &lt;div class="controls message-user-infos-preview">
                    &lt;a href="#" class="user-group small group-ID offload">&lt;/a>
                    &lt;span class="pinned administrator small">{@user.administrator}&lt;/span>
                &lt;/div>
            &lt;/div>
            &lt;div class="message-infos">
                &lt;time datetime="{TODAY_TIME}" itemprop="datePublished">{TODAY_TIME}&lt;/time>
                &lt;a href="#ID" class="copy-link-to-clipboard" aria-label="{@common.copy.link.to.clipboard}">\#ID&lt;/a>
            &lt;/div>
        &lt;/div>
    &lt;/header>
    &lt;div id="message-user-datas-ID" class="modal modal-animation">
        &lt;div class="close-modal" aria-label="{@common.close}">&lt;/div>
        &lt;div class="content-panel cell">
            &lt;div class="cell-list">
                &lt;ul>
                    &lt;li class="li-stretch">
                        &lt;span>{@sandbox.layout.messages.level}&lt;/span>
                        &lt;img src="/images/group/link" alt="alt name" />
                    &lt;/li>
                    &lt;li class="li-stretch">
                        &lt;span>{@common.see.profile}&lt;/span>
                        &lt;a href="#" class="msg-link-pseudo administrator offload">admin&lt;/a>
                    &lt;/li>
                    &lt;li class="li-stretch">
                        &lt;span>{@user.pm}&lt;/span>
                        &lt;a href="#" class="button submit smaller offload">&lt;i class="fa fa-people-arrows" aria-hidden="true">&lt;/i>&lt;/a>
                    &lt;/li>
                    &lt;li class="li-stretch">
                        &lt;span>{@user.email}&lt;/span>
                        &lt;a href="#" class="button submit smaller offload">&lt;i class="fa iboost fa-iboost-email" aria-hidden="true">&lt;/i>&lt;/a>
                    &lt;/li>
                    &lt;li>{@user.groups} :&lt;/li>
                    &lt;li class="li-stretch">
                        &lt;a href="#">Com&lt;/a>
                        &lt;img src="http://data.babsoweb.com/babsodata/phpboost/graph/group-icon/com.png" alt="group picture" />
                    &lt;/li>
                    &lt;li>
                        &lt;span>{@sandbox.layout.user.sign}&lt;/span>
                    &lt;/li>
                    &lt;li>
                        &lt;img src="/image/link" alt="alt name" />
                    &lt;/li>
                    &lt;li class="li-stretch">
                        &lt;span>{@user.punishments} : 0% &lt;/span>
                        &lt;span>
                            &lt;a href="#">&lt;i class="fa fa-exclamation-trianglewarning">&lt;/i>&lt;/a>
                            &lt;a href="#">&lt;i class="fa fa-user-lock link-color">&lt;/i>&lt;/a>
                        &lt;/span>
                    &lt;/li>
                &lt;/ul>
            &lt;/div>
        &lt;/div>
    &lt;/div>
    &lt;div class="message-content">
        &lt;p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi autem sequi quam ab amet culpa nobis vitae rerum laborum nulla!&lt;/p>
    &lt;/div>
&lt;/article></code></pre>
            </div>
        </div>
    </div>
</div>
