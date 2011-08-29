<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:exslt="http://exslt.org/common"
  xmlns:func="http://exslt.org/functions"
  xmlns:string="http://exslt.org/strings"
  xmlns:my="http://no-sense.de/my"
  extension-element-prefixes="func exslt string"
>
  <xsl:output method="text" omit-xml-declaration="yes"/>
  
  <xsl:include href="xp5.func.xsl"/>

  <xsl:template match="/">
    <xsl:value-of select="my:setFilename(concat('base/', /document/table/@class, 'ServiceBaseInterface.class.php'))" />
    <xsl:value-of select="my:setProtected('false')" />

    <xsl:text>&lt;?php
/* This class is part of the XP framework
 *
 * $Id$
 */
</xsl:text>
    <xsl:apply-templates/>
  <xsl:text>?></xsl:text>
  </xsl:template>
  
  <xsl:template match="table">

    <xsl:text>/**
   * Service for table </xsl:text><xsl:value-of select="@name"/>, database <xsl:value-of select="./@database"/><xsl:text>
   * (This interface was auto-generated, so please do not change manually)
   *
   * Please put your custom declarations into </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" />ServiceInterface<xsl:text>.
   */
  interface </xsl:text><xsl:value-of select="@class"/><xsl:text>ServiceBaseInterface {
</xsl:text>

  <!-- Create a static method for indexes -->
  <xsl:for-each select="my:distinctIndex(index[@name != '' and string-length (key/text()) != 0])">
    <xsl:text>
    /**
     * Gets an instance of this object by index "</xsl:text><xsl:value-of select="@name"/><xsl:text>"
     * </xsl:text><xsl:for-each select="key"><xsl:variable name="key" select="text()"/><xsl:text>
     * @param   </xsl:text><xsl:value-of select="concat(../../attribute[@name= $key]/@typename, ' ', $key)"/></xsl:for-each><xsl:text>
     * @return  </xsl:text><xsl:value-of select="concat(../@package, '.', ../@class)"/><xsl:if test="not(@unique= 'true')">[] entity objects</xsl:if><xsl:if test="@unique= 'true'"> entity object</xsl:if><xsl:text>
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function getBy</xsl:text>
    <xsl:for-each select="key"><xsl:value-of select="my:ucfirst(text())" /></xsl:for-each>
    <xsl:text>(</xsl:text>
    <xsl:for-each select="key">
      <xsl:value-of select="concat('$', text())"/>
    <xsl:if test="position() != last()">, </xsl:if>
    </xsl:for-each>
    <xsl:text>);&#10;</xsl:text></xsl:for-each>
  
    <!-- Closing curly brace -->  
    <xsl:text>  }</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>
