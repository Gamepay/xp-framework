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
    <xsl:value-of select="my:setFilename(concat('base/', my:camelCase(/document/table/@class), 'BaseInterface.class.php'))" />
    <xsl:value-of select="my:setProtected('false')" />

    <xsl:text>&lt;?php
/* This interface is part of the XP framework
 *
 * $Id$
 */
 
  namespace </xsl:text><xsl:value-of select="/document/table/@namespace" /><xsl:text>\base;
   
</xsl:text>
    <xsl:apply-templates/>
  <xsl:text>?></xsl:text>
  </xsl:template>
  
  <xsl:template match="table">
  
    <xsl:text>/**
   * Class wrapper for table </xsl:text><xsl:value-of select="@name"/>, database <xsl:value-of select="./@database"/><xsl:text>
   * (This interface was auto-generated, so please do not change manually)
   *
   * Please put your custom declarations into </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', my:camelCase(/document/table/@class))" /><xsl:text>Interface
   */
  interface </xsl:text><xsl:value-of select="my:camelCase(@class)"/><xsl:text>BaseInterface {
    </xsl:text>

  <!-- Create getters and setters -->
    <xsl:for-each select="attribute">
      <xsl:text>
    /**
     * Retrieves </xsl:text><xsl:value-of select="my:camelCase(@name)"/><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="@typename"/><xsl:text>
     */
    public function get</xsl:text><xsl:value-of select="my:ucfirst(my:camelCase(@name))" /><xsl:text>();&#10;</xsl:text>
    
      <xsl:text>
    /**
     * Sets </xsl:text><xsl:value-of select="my:camelCase(@name)"/><xsl:text>
     *
     * @param   </xsl:text><xsl:value-of select="concat(@typename, ' ', my:camelCase(@name))"/><xsl:text>
     * @return  </xsl:text><xsl:value-of select="@typename"/><xsl:text> the previous value
     */
    public function set</xsl:text><xsl:value-of select="my:ucfirst(my:camelCase(@name))" /><xsl:text>(</xsl:text>$<xsl:value-of select="my:camelCase(@name)"/><xsl:text>);&#10;</xsl:text>
  </xsl:for-each>

  <!-- create referenced object getters -->
  <xsl:for-each select="my:referencing($this)">
    <xsl:variable name="referencedTable" select="document(concat($definitionpath, '/', my:prefixedClassName(@table)))/document" />
    <xsl:variable name="isSingle"        select="my:constraintSingleTest(./key, $referencedTable/table/index[@unique = 'true'])" />
    <xsl:variable name="classname"       select="my:ucfirst(my:camelCase(@table))" />
    <xsl:variable name="fullclassname"   select="concat($package, '.', my:prefixedClassName(my:camelCase(@table)))" />
    <xsl:variable name="keys4apidoc">
      <xsl:for-each select="key"><xsl:value-of select="@sourceattribute" />=><xsl:value-of select="@attribute" />
      <xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>
    <xsl:choose>

      <!-- case referenced fields are unique -->
      <xsl:when test="$isSingle"><xsl:text>
    /**
     * Retrieves the </xsl:text><xsl:value-of select="$classname"/><xsl:text> entity
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface entity
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>();&#10;</xsl:text>
      </xsl:when>

      <!-- case referenced fields are not unique -->
      <xsl:otherwise><xsl:text>
    /**
     * Retrieves an array of all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface[] entities
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>List();

    /**
     * Retrieves an iterator for all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  rdbms.ResultIterator&lt;</xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface&gt;
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>Iterator();&#10;</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:for-each>
  
  <!-- create referencing object getters -->
  <xsl:for-each select="my:referenced($this)">
    <xsl:variable name="referencingTable" select="document(concat($definitionpath, '/', my:prefixedClassName(../../@name)))/document" />
    <xsl:variable name="isSingle" select="my:constraintSingleTest(./key, $referencingTable/table/index[@unique = 'true'])" />
    <xsl:variable name="classname"       select="my:ucfirst(my:camelCase(../../@name))" />
    <xsl:variable name="fullclassname"   select="concat($package, '.', my:camelCase(my:prefixedClassName(../../@name)))" />
    <xsl:variable name="keys4apidoc">
      <xsl:for-each select="key">
        <xsl:value-of select="@attribute" />=><xsl:value-of select="@sourceattribute" /><xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>

    <xsl:choose>
      <!-- case referenced fields are unique -->
      <xsl:when test="$isSingle"><xsl:text>
    /**
     * Retrieves the </xsl:text><xsl:value-of select="$classname"/><xsl:text> entity referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface entity
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>();&#10;</xsl:text>
      </xsl:when>

      <!-- case referenced fields are not unique -->
      <xsl:otherwise><xsl:text>
    /**
     * Retrieves an array of all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface[] entities
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>List();

    /**
     * Retrieves an iterator for all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  rdbms.ResultIterator&lt;</xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>Interface&gt;
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="my:camelCase(@role)" /><xsl:text>Iterator();&#10;</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:for-each>
  
    <!-- Closing curly brace -->  
    <xsl:text>  }</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>
