<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output   method="html"
                indent="no"
                omit-xml-declaration="yes"
                encoding="utf-8"
                doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
                doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
  
  <!-- Racine -->
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
        <head>
            <title><xsl:value-of select="//header/title"/></title>
            <xsl:for-each select="authors/author">
                <meta>
                  <xsl:attribute name="name">
                    <xsl:value-of select="author"/>
                  </xsl:attribute>
                  <xsl:attribute name="content">
                    <xsl:value-of select="name"/>, <xsl:value-of select="email"/>
                  </xsl:attribute>
                </meta>
            </xsl:for-each>
            <meta http-equiv="Content-Language" content="fr" />
            <meta name="Robots" content="index, follow, all" />
            <meta name="classification" content="tout public" />
            <link rel="stylesheet" href="documentation.css" type="text/css" media="screen, print, handheld" />
        </head>
        <body>
          <div id="document">
            <xsl:apply-templates select="//header"/>
            <xsl:apply-templates select="//body"/>
          </div>
        </body>
    </html>
  </xsl:template>
  
  <!-- header -->
  <xsl:template match="header">
    <div class="header">
      <xsl:apply-templates select="title"/>
      <xsl:apply-templates select="license"/>
      <xsl:apply-templates select="dates"/>
      <xsl:apply-templates select="authors"/>
    </div>
  </xsl:template>

  <!-- title -->
  <xsl:template match="title">
    <h1>
      <xsl:value-of select="."/>
    </h1>
  </xsl:template>
  
  <!-- authors -->
  <xsl:template match="authors">
    <div class="authors">
      <ul>
        <xsl:for-each select="author">
          <xsl:if test="position()>1">-</xsl:if>
          <li>
            <span class="name"><xsl:value-of select="@name"/></span> :
            <span class="email">
              <a>
                <xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
                <xsl:value-of select="@email"/>
              </a>
            </span>
          </li>
        </xsl:for-each>
      </ul>
    </div>
  </xsl:template>
  
  <!-- License -->
  <xsl:template match="license">
    <div class="license">
      Ce programme est distribué sous la license
      <a>
        <xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
        <xsl:value-of select="@name"/>
      </a>
    </div>
  </xsl:template>
  
  <!-- dates -->
  <xsl:template match="dates">
    <div class="dates">
      <ul>
        <li>Version <xsl:value-of select="/document/@version"/></li> -
        <li>Créé le <span class="date"><xsl:value-of select="creation"/></span></li> -
        <li>Dernière modification le <span class="date"><xsl:value-of select="last-modification"/></span></li>
      </ul>
    </div>
  </xsl:template>
  
  <!-- body -->
  <xsl:template match="body">
    <xsl:call-template name="index"/>
    <xsl:apply-templates select="thanks"/>
    <xsl:apply-templates select="content"/>
    <xsl:apply-templates select="related"/>
  </xsl:template>
  
  <!-- Index -->
  <xsl:template name="index">
    <h2>Index</h2>
    <div id="index">
      <ol>
        <xsl:for-each select="//chapters/chapter">
          <li>
            <a>
              <xsl:attribute name="href">#<xsl:value-of select="@title"/></xsl:attribute>
              <xsl:value-of select="@title"/>
            </a>
          </li>
          <xsl:if test="count ( ./chapter ) != 0">
            <xsl:call-template name="indexSubChapters"/>
          </xsl:if>
        </xsl:for-each>
      </ol>
    </div>
  </xsl:template>
  
  <!-- indexSubChapters -->
  <xsl:template name="indexSubChapters">
      <ol>
        <xsl:for-each select="chapter">
          <li>
            <a>
              <xsl:attribute name="href">#<xsl:value-of select="@title"/></xsl:attribute>
              <xsl:value-of select="@title"/>
            </a>
          </li>
          <xsl:if test="count ( ./chapter ) != 0">
            <xsl:call-template name="indexSubChapters"/>
          </xsl:if>
        </xsl:for-each>
      </ol>
  </xsl:template>
  
  <!-- thanks -->
  <xsl:template match="thanks">
    <h2>Thanks</h2>
    <div id="thanks">
      <xsl:apply-templates/>
    </div>
  </xsl:template>
  
  <!-- thanks -->
  <xsl:template match="thanks-list">
    <h4>Remerciements :</h4>
    <div class="thanks-list">
      <ul>
        <xsl:for-each select="thank">
          <li><xsl:value-of select="."/></li>
        </xsl:for-each>
      </ul>
    </div>
  </xsl:template>
  
  <!-- content -->
  <xsl:template match="content">
    <h2>Content</h2>
    <div id="content">
      <xsl:apply-templates select="preface"/>
      <xsl:apply-templates select="chapters"/>
      <xsl:apply-templates select="appendice"/>
    </div>
  </xsl:template>
  
  <!-- preface -->
  <xsl:template match="preface">
    <h3>Preface</h3>
    <div id="preface">
      <xsl:apply-templates/>
    </div>
  </xsl:template>
  
  <!-- chapters -->
  <xsl:template match="chapters">
    <h3>Chapters</h3>
    <div id="chapters">
      <xsl:for-each select="chapter">
        <xsl:apply-templates select="."/>
      </xsl:for-each>
    </div>
  </xsl:template>
  
  <xsl:template match="chapter">
    <div class="chapter">
      <xsl:attribute name="id">
        <xsl:value-of select="@title"/>
      </xsl:attribute>
      <xsl:attribute name="name">
        <xsl:value-of select="@title"/>
      </xsl:attribute>
      <xsl:attribute name="anchor">
        <xsl:value-of select="@title"/>
      </xsl:attribute>
      <xsl:choose>
        <xsl:when test="count(ancestor::chapter) = 0">
          <h4>
            <xsl:value-of select="count (preceding-sibling::chapter) + 1"/>) -
            <xsl:value-of select="@title"/>
          </h4>
        </xsl:when>
        <xsl:otherwise>
          <h5>
            <xsl:for-each select="ancestor::chapter">
              <xsl:value-of select="count (preceding-sibling::chapter) + 1"/>.</xsl:for-each><xsl:value-of select="count (preceding-sibling::chapter) + 1"/>) -
            <xsl:value-of select="@title"/>
          </h5>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:apply-templates/>
    </div>
  </xsl:template>
  
  <!-- appendice -->
  <xsl:template match="appendice">
    <h3>Appendice</h3>
    <div id="appendice">
      <xsl:apply-templates/>
    </div>
  </xsl:template>
  
  <!-- related -->
  <xsl:template match="related">
    <h2>Related</h2>
    <div id="related">
      <xsl:apply-templates/>
    </div>
  </xsl:template>
  
  <!-- link-list -->
  <xsl:template match="link-list">
    <div class="link-list">
      <ul>
        <xsl:for-each select="link">
          <li>
            <xsl:apply-templates select="."/>
          </li>
        </xsl:for-each>
      </ul>
    </div>
  </xsl:template>
  
  <!-- para -->
  <xsl:template match="para">
    <div class="text">
      <p>
        <xsl:attribute name="class">
          <xsl:value-of select="@style"/>
        </xsl:attribute>
        <xsl:value-of select="."/>
      </p>
    </div>
  </xsl:template>
  
  <!-- code -->
  <xsl:template match="code">
    <div class="code">
      <xsl:value-of select="."/>
    </div>
  </xsl:template>
  
  <!-- link -->
  <xsl:template match="link">
    <a>
      <xsl:attribute name="href">
        <xsl:value-of select="@link"/>
      </xsl:attribute>
      <xsl:value-of select="@name"/>
    </a>
  </xsl:template>
  
  <!-- img -->
  <xsl:template match="img">
    <img>
      <xsl:attribute name="name">
        <xsl:value-of select="@name"/>
      </xsl:attribute>
      <xsl:attribute name="alt">
        <xsl:value-of select="@name"/>
      </xsl:attribute>
      <xsl:attribute name="src">
        <xsl:value-of select="@path"/>
      </xsl:attribute>
    </img>
  </xsl:template>
  
</xsl:stylesheet>
