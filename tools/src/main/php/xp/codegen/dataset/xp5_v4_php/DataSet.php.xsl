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
    <xsl:value-of select="my:setFilename(concat(my:camelCase(/document/table/@class), '.class.php'))" />
    <xsl:value-of select="my:setProtected('true')" />

    <xsl:text>&lt;?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace </xsl:text><xsl:value-of select="/document/table/@namespace" /><xsl:text>;  
  
  uses(
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.base.', my:camelCase(/document/table/@class))" /><xsl:text>Base',
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', my:camelCase(/document/table/@class))" /><xsl:text>Interface'
  );&#10;
  
  use </xsl:text><xsl:value-of select="concat(/document/table/@namespace, '\base\', my:camelCase(/document/table/@class), 'Base', ' as ', my:camelCase(/document/table/@class), 'Base', ';')"/><xsl:text>
  use </xsl:text><xsl:value-of select="concat(/document/table/@namespace, '\', my:camelCase(/document/table/@class), 'Interface', ' as ', my:camelCase(/document/table/@class), 'Interface', ';')"/><xsl:text>&#10;</xsl:text>
  
    <xsl:apply-templates/>
  <xsl:text>?></xsl:text>
  </xsl:template>
  
  <xsl:template match="table">
    <xsl:variable name="primary_key_unique" select="index[@primary= 'true' and @unique= 'true']/key/text()"/>

    <xsl:text>/**
   * Class wrapper for table </xsl:text><xsl:value-of select="@name"/>, database <xsl:value-of select="./@database"/><xsl:text>
   */
  class </xsl:text><xsl:value-of select="my:camelCase(@class)"/><xsl:text> extends </xsl:text><xsl:value-of select="my:camelCase(@class)"/><xsl:text>Base implements </xsl:text><xsl:value-of select="my:camelCase(@class)"/><xsl:text>Interface {
  }</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>