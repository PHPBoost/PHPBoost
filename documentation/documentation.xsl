<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html"/>
  <!-- Racine -->
  <xsl:template match="/">
    <html>
      <head>
        <title>
          <xsl:value-of select="//titre"/>
        </title>
      </head>
      <body bgColor="white">
        <xsl:apply-templates/>
      </body>
    </html>
  </xsl:template>
  <!-- En-Tete -->
  <xsl:template match="en-tete">
    <table align="center" cellspacing="50" style="border:0px;">
      <tr>
        <td style="border:0px;">
          <xsl:apply-templates select="couverture"/>
        </td>
        <td style="border:0px;">
          <xsl:apply-templates select="titre"/>
          <xsl:apply-templates select="auteur"/>
          <xsl:apply-templates select="info_traitements"/>
        </td>
      </tr>
    </table>
    <hr/>
    <h3>Début du texte:</h3>
  </xsl:template>
  <!-- Titre -->
  <xsl:template match="titre">
    <h1 style="text-align:center; color:blue;">
      <xsl:apply-templates/>
    </h1>
  </xsl:template>
  <!-- Couverture -->
  <xsl:template match="couverture">
    <div align="center">
      <img>
        <xsl:attribute name="src">
          <xsl:value-of select="@chemin"/>
        </xsl:attribute>
      </img>
    </div>
  </xsl:template>
  <!-- En-Tete/Auteur -->
  <xsl:template match="en-tete/auteur">
    <h2 style="text-align: center; font-style: italic;">
      <xsl:apply-templates/>
    </h2>
  </xsl:template>
  <!-- Infos Traitements -->
  <xsl:template match="info_traitements">
    <blockquote style="color:darkgreen;">But du TP du
      <xsl:value-of select="date"/>:
      <xsl:value-of select="but"/>
      <br/>Auteurs :
      <xsl:for-each select="auteurs/auteur">
        <xsl:value-of select="."/>
        <xsl:if test="position()!=last()"> et </xsl:if>
      </xsl:for-each>(
      <xsl:value-of select="auteurs/NoBinome"/>)
      <br/>Email du reponsable :
      <xsl:value-of select="email"/>
    </blockquote>
  </xsl:template>
  <!-- Paragraphe -->
  <xsl:template match="paragraphe">
    <xsl:choose>
      <xsl:when test="@type='dialogue'">
        <table align="center" width="90%">
          <tr>
            <td width="45%">
              <table border="1" cellpadding="10" width="100%">
                <xsl:for-each select="phrase[@langue!='tcheque']">
                  <tr>
                    <td width="50">
                      <xsl:choose>
                        <xsl:when test="@locuteur='Le Petit Prince'">
                          <img src="images/Le Petit Prince.png" title="Le Petit Prince"/>
                        </xsl:when>
                        <xsl:otherwise>
                          <img src="images/Narrateur.png" title="Narrateur"/>
                        </xsl:otherwise>
                      </xsl:choose>
                    </td>
                    <td>
                      <xsl:apply-templates select="."/>
                    </td>
                  </tr>
                </xsl:for-each>
              </table>
            </td>
            <td/>
            <td width="45%">
              <table border="1" cellpadding="10" width="100%">
                <xsl:for-each select="phrase[@langue='tcheque']">
                  <tr>
                    <td width="50">
                      <xsl:choose>
                        <xsl:when test="@locuteur='Le Petit Prince'">
                          <img src="images/Le Petit Prince.png" title="Le Petit Prince"/>
                        </xsl:when>
                        <xsl:otherwise>
                          <img src="images/Narrateur.png" title="Narrateur"/>
                        </xsl:otherwise>
                      </xsl:choose>
                    </td>
                    <td>
                      <xsl:apply-templates select="."/>
                    </td>
                  </tr>
                </xsl:for-each>
              </table>
            </td>
          </tr>
        </table>
      </xsl:when>
      <xsl:otherwise>
        <p>
          <xsl:for-each select="phrase">
            <xsl:apply-templates select="."/>
          </xsl:for-each>
        </p>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="position()=last()">
      <h3>Fin du texte.</h3><hr/>
    </xsl:if>
  </xsl:template>
  <!-- Phrase -->
  <xsl:template match="phrase">
    <xsl:choose>
      <xsl:when test="@langue='tcheque'">
        <span style="font-style: italic; color: brown;">
          <xsl:value-of select="."/>
        </span>
      </xsl:when>
      <xsl:otherwise>
        <xsl:choose>
          <xsl:when test="(../@type='dialogue') and (contains(text(), 'mouton'))">
            <span style="font-size: 24; font-weight: bold;">
              <xsl:value-of select="."/>
              <img src="images/moutonDessin.png" title="Mouton"/>
            </span>
          </xsl:when>
          <xsl:otherwise>
            <span style="">
              <xsl:value-of select="."/>
            </span>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="following-sibling::phrase[1]/@langue!=@langue">
      <br/>
    </xsl:if>
  </xsl:template>
  <!-- Image -->
  <xsl:template match="image">
    <div align="center">
      <img>
        <xsl:attribute name="src">
          <xsl:value-of select="@chemin"/>
        </xsl:attribute>
      </img>
    </div>
    <xsl:if test="position()=last()">
      <h3>Fin du texte.</h3><hr/>
    </xsl:if>
  </xsl:template>
</xsl:stylesheet>




