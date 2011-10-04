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
    <xsl:value-of select="my:setFilename(concat('base/', /document/table/@class, 'ServiceBase.class.php'))" />
    <xsl:value-of select="my:setProtected('false')" />

    <xsl:text>&lt;?php
/* This class is part of the XP framework
 *
 * $Id$
 */
 
  uses(
    'rdbms.Criteria',
    'rdbms.Peer',
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.base.', /document/table/@class)" /><xsl:text>ServiceBaseInterface',
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>',
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>Service'
  );&#10;</xsl:text>
    <xsl:apply-templates/>
  <xsl:text>?></xsl:text>
  </xsl:template>
  
  <xsl:template match="table">

    <xsl:text>/**
   * Service for table </xsl:text><xsl:value-of select="@name"/>, database <xsl:value-of select="./@database"/><xsl:text>
   * (This class was auto-generated, so please do not change manually)
   *
   * Please put your custom code into </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" />Service<xsl:text>.
   */
  abstract class </xsl:text><xsl:value-of select="@class"/><xsl:text>ServiceBase implements </xsl:text><xsl:value-of select="@class"/><xsl:text>ServiceBaseInterface {
  
    /**
     * @var </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>Service
     */
    private static $instance= NULL;
    
    /**
     * Returns the singleton instance of this service.
     *
     * @return </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>Service
     */
    public static function getInstance() {
      if (!isset(self::$instance)) {
        self::$instance= new </xsl:text><xsl:value-of select="@class"/><xsl:text>Service();
      }
      return self::$instance;
    }

    /**
     * Retrieve associated peer
     *
     * @return  rdbms.Peer
     */
    protected function getPeer() {
      return Peer::forName('</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>');
    }
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
    <xsl:text>) {&#10;</xsl:text>
      <xsl:choose>

        <xsl:when test="count(key) = 1">
          <!-- Single key -->
          <xsl:choose>
            <xsl:when test="@unique = 'true'">
              <xsl:text>      $r= $this->getPeer()-&gt;doSelect(new Criteria(array('</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>', $</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>, EQUAL)));&#10;      return $r ? $r[0] : NULL;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
              <xsl:text>      return $this->getPeer()-&gt;doSelect(new Criteria(array('</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>', $</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>, EQUAL)));</xsl:text>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:when>

        <xsl:otherwise>
        
          <!-- Multiple keys -->
          <xsl:choose>
            <xsl:when test="@unique = 'true'">
              <xsl:text>      $r= $this->getPeer()-&gt;doSelect(new Criteria(&#10;</xsl:text>
              <xsl:for-each select="key">
                <xsl:text>        array('</xsl:text>
                <xsl:value-of select="."/>
                <xsl:text>', $</xsl:text>
                <xsl:value-of select="."/>
                <xsl:text>, EQUAL)</xsl:text>
                <xsl:if test="position() != last()">,</xsl:if><xsl:text>&#10;</xsl:text>
              </xsl:for-each>
              <xsl:text>      ));&#10;      return $r ? $r[0] : NULL;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
              <xsl:text>      return $this->getPeer()-&gt;doSelect(new Criteria(&#10;</xsl:text>
              <xsl:for-each select="key">
                <xsl:text>        array('</xsl:text>
                <xsl:value-of select="."/>
                <xsl:text>', $</xsl:text>
                <xsl:value-of select="."/>
                <xsl:text>, EQUAL)</xsl:text>
                <xsl:if test="position() != last()">,</xsl:if><xsl:text>&#10;</xsl:text>
              </xsl:for-each>
              <xsl:text>      ));</xsl:text>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>
    <xsl:text>&#10;    }&#10;</xsl:text>
  </xsl:for-each>
  
    <!-- Closing curly brace -->  
    <xsl:text>  }</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>
