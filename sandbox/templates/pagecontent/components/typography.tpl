<div id="component-typography">
    <h2>{@sandbox.typography}</h2>

    <article id="component-titles" class="sandbox-block">
        <header>
            <h5>{@sandbox.component.titles} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <h1>h1. {@sandbox.component.title} 1</h1>
        <h2>h2. {@sandbox.component.title} 2</h2>
        <h3>h3. {@sandbox.component.title} 3</h3>
        <h4>h4. {@sandbox.component.title} 4</h4>
        <h5>h5. {@sandbox.component.title} 5</h5>
        <h6>h6. {@sandbox.component.title} 6</h6>
    </article>

    <article id="component-sizes" class="sandbox-block">
        <header>
            <h5>{@sandbox.sizes} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <p class="smallest">{@sandbox.component.item.smallest}</p>
        <p class="smaller">{@sandbox.component.item.smaller}</p>
        <p class="small">{@sandbox.component.item.small}</p>
        <p class="normal">{@sandbox.component.item}</p>
        <p class="big">{@sandbox.component.item.big}</p>
        <p class="bigger">{@sandbox.component.item.bigger}</p>
        <p class="biggest">{@sandbox.component.item.biggest}</p>
        <!-- Source code -->
        <div class="formatter-container formatter-hide no-js">
            <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
            <div class="formatter-content formatter-code">
                <div class="no-style">
<pre class="precode"><code>&lt;span class="smallest">{@sandbox.component.item.smallest}&lt;/span>
&lt;span class="smaller">{@sandbox.component.item.smaller}&lt;/span>
&lt;span class="small">{@sandbox.component.item.small}&lt;/span>
&lt;span class="normal">{@sandbox.component.item}&lt;/span>
&lt;span class="big">{@sandbox.component.item.big}&lt;/span>
&lt;span class="bigger">{@sandbox.component.item.bigger}&lt;/span>
&lt;span class="biggest">{@sandbox.component.item.biggest}&lt;/span></code></pre>
                </div>
            </div>
        </div>
    </article>
    <article id="component-styles" class="sandbox-block">
        <header>
            <h5>{@sandbox.component.styles} {@H|sandbox.pinned.bbcode}</h5>
        </header>
        <p class="text-strong">{@sandbox.component.item.bold}</p>
        <p class="text-italic">{@sandbox.component.item.italic}</p>
        <p class="text-underline">{@sandbox.component.item.underline}</p>
        <p class="text-strike">{@sandbox.component.item.strike}</p>
        <p><a href="#">{@sandbox.component.link}</a></p>
        <p class="ellipsis">{@sandbox.component.item.ellipsis} - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure voluptatum incidunt illum consectetur officia id unde accusantium eaque est, ex!</p>
        <p class="align-left">{@sandbox.component.item.left}</p>
        <p class="align-center">{@sandbox.component.item.center}</p>
        <p class="align-right">{@sandbox.component.item.right}</p>
        <p class="float-left">{@sandbox.component.item.float.left}</p>
        <p class="float-right">{@sandbox.component.item.float.right}</p>
        <div class="spacer"></div>
        <p class="flex-between"><span>{@sandbox.component.items}</span><span>{@sandbox.component.item.stretch.center}</span><span>{@sandbox.component.item.stretch.right}</span></p>
        <p class="stacked"><span>{@sandbox.component.item}</span><span class="stack-event stack-right stack-sup stack-circle bgc member">{@sandbox.component.item.stack.index}</span></p>
        <div class="spacer"></div>
        <p class="pinned bgc moderator">{@sandbox.component.item.pinned}</p>
        <!-- Source code -->
        <div class="formatter-container formatter-hide no-js">
            <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
            <div class="formatter-content formatter-code">
                <div class="no-style">
<pre class="precode"><code>&lt;p class="text-strong">{@sandbox.component.item.bold}&lt;/p>
&lt;p class="text-italic">{@sandbox.component.item.italic}&lt;/p>
&lt;p class="text-underline">{@sandbox.component.item.underline}&lt;/p>
&lt;p class="text-strike">{@sandbox.component.item.strike}&lt;/p>
&lt;p>&lt;a href="#">{@sandbox.component.link}&lt;/a>&lt;/p>
&lt;p class="ellipsis">{@sandbox.component.item.ellipsis} - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure voluptatum incidunt illum consectetur officia id unde accusantium eaque est, ex!&lt;/p>
&lt;p class="align-left">{@sandbox.component.item.left}&lt;/p>
&lt;p class="align-center">{@sandbox.component.item.center}&lt;/p>
&lt;p class="align-right">{@sandbox.component.item.right}&lt;/p>
&lt;p class="float-left">{@sandbox.component.item.float.left}&lt;/p>
&lt;p class="float-right">{@sandbox.component.item.float.right}&lt;/p>
&lt;p class="flex-between">&lt;span>{@sandbox.component.items}&lt;/span>&lt;span>{@sandbox.component.item.stretch.center}&lt;/span>&lt;span>{@sandbox.component.item.stretch.right}&lt;/span>&lt;/p>
&lt;p class="stacked">&lt;span>{@sandbox.component.item}&lt;/span>&lt;span class="stack-event stack-right stack-sup stack-circle bgc member">{@sandbox.component.item.stack.index}&lt;/span>&lt;/p>
&lt;p class="pinned bgc moderator">{@sandbox.component.item.pinned}&lt;/p></code></pre>
                </div>
            </div>
        </div>
    </article>
</div>
