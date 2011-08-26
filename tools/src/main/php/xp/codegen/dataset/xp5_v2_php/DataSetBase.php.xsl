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
    <xsl:value-of select="my:setFilename(concat('base/', /document/table/@class, 'Base.class.php'))" />
    <xsl:value-of select="my:setProtected('false')" />

    <xsl:text>&lt;?php
/* This class is part of the XP framework
 *
 * $Id$
 */
 
  uses(
    'rdbms.Criteria',
    'rdbms.DataSet',
    'rdbms.FieldType',
    'rdbms.Peer',
    'util.HashmapIterator',
    '</xsl:text><xsl:value-of select="concat(/document/table/@package, '.base.', /document/table/@class)" /><xsl:text>BaseInterface'
  );&#10;</xsl:text>
    <xsl:apply-templates/>
  <xsl:text>?></xsl:text>
  </xsl:template>
  
  <xsl:template match="table">
    <xsl:variable name="primary_key_unique" select="index[@primary= 'true' and @unique= 'true']/key/text()"/>

    <xsl:text>/**
   * Class wrapper for table </xsl:text><xsl:value-of select="@name"/>, database <xsl:value-of select="./@database"/><xsl:text>
   * (This class was auto-generated, so please do not change manually)
   *
   * Please put your custom code into </xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>.
   *
   * @purpose Datasource accessor
   */
  abstract class </xsl:text><xsl:value-of select="@class"/><xsl:text>Base extends DataSet implements </xsl:text><xsl:value-of select="@class"/><xsl:text>BaseInterface {
    public&#10;</xsl:text>

  <!-- Attributes -->
  <xsl:for-each select="attribute">
    <xsl:value-of select="concat('      $', @name, substring('                                ', 0, 20 - string-length(@name)))"/>
    <xsl:choose>
      <xsl:when test="@nullable = 'true'">= NULL</xsl:when>
      <xsl:when test="@typename= 'int'">= 0</xsl:when>
      <xsl:when test="@typename= 'string'">= ''</xsl:when>
      <xsl:when test="@typename= 'float'">= 0.0</xsl:when>
      <xsl:when test="@typename= 'bool'">= FALSE</xsl:when>
      <xsl:when test="@typename= 'util.Date'">= NULL</xsl:when>
    </xsl:choose>
    <xsl:if test="position() != last()">,&#10;</xsl:if>
  </xsl:for-each>
  <xsl:text>;
  
    protected
      $cache= array(</xsl:text>
      <xsl:for-each select="my:referencing($this) | my:referenced($this)"><xsl:text>
        '</xsl:text><xsl:value-of select="@role" /><xsl:text>' => array(),</xsl:text>
      </xsl:for-each><xsl:text>
      );

    static function __static() { 
      with ($peer= self::getPeer()); {
        $peer->setTable('</xsl:text><xsl:value-of select="my:separator(@database, @name, @dbtype)"/><xsl:text>');
        $peer->setConnection('</xsl:text><xsl:value-of select="@dbhost"/><xsl:text>');</xsl:text>
        <xsl:if test="attribute[@identity= 'true']">
          <xsl:text>&#10;        $peer->setIdentity('</xsl:text><xsl:value-of select="attribute[@identity= 'true']/@name"/><xsl:text>');</xsl:text>
        </xsl:if><xsl:text>
        $peer->setPrimary(array(</xsl:text>
          <xsl:for-each select="index[@primary= 'true']/key">
            <xsl:text>'</xsl:text><xsl:value-of select="."/><xsl:text>'</xsl:text>
            <xsl:if test="position() != last()">, </xsl:if>
          </xsl:for-each>
        <xsl:text>));
        $peer->setTypes(array(&#10;</xsl:text>
        <xsl:for-each select="attribute">
          <xsl:text>          '</xsl:text>
          <xsl:value-of select="@name"/>'<xsl:value-of select="substring('                                ', 0, 20 - string-length(@name))"/>
          <xsl:text> =&gt; array('</xsl:text>
          <xsl:choose>
            <xsl:when test="@typename= 'int'">%d</xsl:when>
            <xsl:when test="@typename= 'string'">%s</xsl:when>
            <xsl:when test="@typename= 'float'">%f</xsl:when>
            <xsl:when test="@typename= 'bool'">%d</xsl:when>
            <xsl:when test="@typename= 'util.Date'">%s</xsl:when>
            <xsl:otherwise>%c</xsl:otherwise>
          </xsl:choose>
          <xsl:text>', FieldType::</xsl:text>
          <xsl:value-of select="substring-after(@type, 'DB_ATTRTYPE_')"/>
          <xsl:text>, </xsl:text>
          <xsl:value-of select="translate(@nullable, $lcletters, $ucletters)"/>
          <xsl:text>)</xsl:text>
          <xsl:if test="position() != last()">,&#10;</xsl:if>
        </xsl:for-each><xsl:text>
        ));</xsl:text>
      <xsl:if test="0 &lt; count(my:referencing($this) | my:referenced($this))"><xsl:text>
        $peer->setRelations(array(</xsl:text>
        <xsl:for-each select="my:referencing($this)"><xsl:text>
          '</xsl:text><xsl:value-of select="@role" /><xsl:text>' => array(
            'classname' => '</xsl:text><xsl:value-of select="concat($package, '.', my:prefixedClassName(@table))" /><xsl:text>',
            'key'       => array(
              </xsl:text><xsl:for-each select="key"><xsl:text>'</xsl:text><xsl:value-of select="@attribute" /><xsl:text>' => '</xsl:text><xsl:value-of select="@sourceattribute" /><xsl:text>',</xsl:text></xsl:for-each><xsl:text>
            ),
          ),</xsl:text>
        </xsl:for-each>
        <xsl:for-each select="my:referenced($this)"><xsl:text>
          '</xsl:text><xsl:value-of select="@role" /><xsl:text>' => array(
            'classname' => '</xsl:text><xsl:value-of select="concat($package, '.', my:prefixedClassName(../../@name))" /><xsl:text>',
            'key'       => array(
              </xsl:text><xsl:for-each select="key"><xsl:text>'</xsl:text><xsl:value-of select="@sourceattribute" /><xsl:text>' => '</xsl:text><xsl:value-of select="@attribute" /><xsl:text>',</xsl:text></xsl:for-each><xsl:text>
            ),
          ),</xsl:text>
        </xsl:for-each><xsl:text>
        ));</xsl:text>
      </xsl:if><xsl:text>
      }
    }  

    /**
     * Retrieve associated peer
     *
     * @return  rdbms.Peer
     */
    public static function getPeer() {
      return Peer::forName('</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>');
    }

    /**
     * column factory
     *
     * @param   string name
     * @return  rdbms.Column
     * @throws  lang.IllegalArgumentException
     */
    public static function column($name) {
      return Peer::forName('</xsl:text><xsl:value-of select="concat(/document/table/@package, '.', /document/table/@class)" /><xsl:text>')->column($name);
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
    public static function getBy</xsl:text>
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
              <xsl:text>      $r= self::getPeer()-&gt;doSelect(new Criteria(array('</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>', $</xsl:text>
              <xsl:value-of select="key"/>
              <xsl:text>, EQUAL)));&#10;      return $r ? $r[0] : NULL;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
              <xsl:text>      return self::getPeer()-&gt;doSelect(new Criteria(array('</xsl:text>
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
              <xsl:text>      $r= self::getPeer()-&gt;doSelect(new Criteria(&#10;</xsl:text>
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
              <xsl:text>      return self::getPeer()-&gt;doSelect(new Criteria(&#10;</xsl:text>
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

  <!-- Create getters and setters -->
    <xsl:for-each select="attribute">
      <xsl:text>
    /**
     * Retrieves </xsl:text><xsl:value-of select="@name"/><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="@typename"/><xsl:text>
     */
    public function get</xsl:text><xsl:value-of select="my:ucfirst(@name)" /><xsl:text>() {
      return $this-></xsl:text><xsl:value-of select="@name"/><xsl:text>;
    }
      </xsl:text>
    
      <xsl:text>
    /**
     * Sets </xsl:text><xsl:value-of select="@name"/><xsl:text>
     *
     * @param   </xsl:text><xsl:value-of select="concat(@typename, ' ', @name)"/><xsl:text>
     * @return  </xsl:text><xsl:value-of select="@typename"/><xsl:text> the previous value
     */
    public function set</xsl:text><xsl:value-of select="my:ucfirst(@name)" /><xsl:text>(</xsl:text>$<xsl:value-of select="@name"/><xsl:text>) {
      return $this->_change('</xsl:text><xsl:value-of select="@name"/><xsl:text>', $</xsl:text><xsl:value-of select="@name"/><xsl:text>);
    }&#10;</xsl:text>
  </xsl:for-each>

  <!-- create referenced object getters -->
  <xsl:for-each select="my:referencing($this)">
    <xsl:variable name="referencedTable" select="document(concat($definitionpath, '/', my:prefixedClassName(@table)))/document" />
    <xsl:variable name="isSingle"        select="my:constraintSingleTest(./key, $referencedTable/table/index[@unique = 'true'])" />
    <xsl:variable name="classname"       select="my:ucfirst(@table)" />
    <xsl:variable name="fullclassname"   select="concat($package, '.', my:prefixedClassName(@table))" />
    <xsl:variable name="keys4apidoc">
      <xsl:for-each select="key"><xsl:value-of select="@sourceattribute" />=><xsl:value-of select="@attribute" />
      <xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>
    <xsl:variable name="keys4criteria">
      <xsl:for-each select="key">
        <xsl:text>          array('</xsl:text><xsl:value-of select="@sourceattribute" /><xsl:text>', $this->get</xsl:text><xsl:value-of select="my:ucfirst(@attribute)" /><xsl:text>(), EQUAL)</xsl:text>
        <xsl:if test="position() != last()"><xsl:text>,&#10;</xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>
    <xsl:choose>

      <!-- case referenced fields are unique -->
      <xsl:when test="$isSingle"><xsl:text>
    /**
     * Retrieves the </xsl:text><xsl:value-of select="$classname"/><xsl:text> entity
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text> entity
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>() {
      $r= ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) ?
        array_values($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) :
        XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
          ->getMethod('getPeer')
          ->invoke(NULL)
          ->doSelect(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
      return $r ? $r[0] : NULL;&#10;    }&#10;</xsl:text>
      </xsl:when>

      <!-- case referenced fields are not unique -->
      <xsl:otherwise><xsl:text>
    /**
     * Retrieves an array of all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>[] entities
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>List() {
      if ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) return array_values($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']);
      return XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
        ->getMethod('getPeer')
        ->invoke(NULL)
        ->doSelect(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
    }

    /**
     * Retrieves an iterator for all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities
     * referenced by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  rdbms.ResultIterator&lt;</xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>&gt;
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>Iterator() {
      if ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) return new HashmapIterator($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']);
      return XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
        ->getMethod('getPeer')
        ->invoke(NULL)
        ->iteratorFor(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
    }&#10;</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:for-each>
  

  <!-- create referencing object getters -->
  <xsl:for-each select="my:referenced($this)">
    <xsl:variable name="referencingTable" select="document(concat($definitionpath, '/', my:prefixedClassName(../../@name)))/document" />
    <xsl:variable name="isSingle" select="my:constraintSingleTest(./key, $referencingTable/table/index[@unique = 'true'])" />
    <xsl:variable name="classname"       select="my:ucfirst(../../@name)" />
    <xsl:variable name="fullclassname"   select="concat($package, '.', my:prefixedClassName(../../@name))" />
    <xsl:variable name="keys4apidoc">
      <xsl:for-each select="key">
        <xsl:value-of select="@attribute" />=><xsl:value-of select="@sourceattribute" /><xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>
    <xsl:variable name="keys4criteria">
      <xsl:for-each select="key">
        <xsl:text>          array('</xsl:text><xsl:value-of select="@attribute" /><xsl:text>', $this->get</xsl:text><xsl:value-of select="my:ucfirst(@sourceattribute)" /><xsl:text>(), EQUAL)</xsl:text>
        <xsl:if test="position() != last()"><xsl:text>,&#10;</xsl:text></xsl:if>
      </xsl:for-each>
    </xsl:variable>

    <xsl:choose>
      <!-- case referenced fields are unique -->
      <xsl:when test="$isSingle"><xsl:text>
    /**
     * Retrieves the </xsl:text><xsl:value-of select="$classname"/><xsl:text> entity referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text> entity
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>() {
      $r= ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) ?
        array_values($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) :
        XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
          ->getMethod('getPeer')
          ->invoke(NULL)
          ->doSelect(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
      return $r ? $r[0] : NULL;&#10;    }&#10;</xsl:text>
      </xsl:when>

      <!-- case referenced fields are not unique -->
      <xsl:otherwise><xsl:text>
    /**
     * Retrieves an array of all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  </xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>[] entities
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>List() {
      if ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) return array_values($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']);
      return XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
        ->getMethod('getPeer')
        ->invoke(NULL)
        ->doSelect(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
    }

    /**
     * Retrieves an iterator for all </xsl:text><xsl:value-of select="$classname"/><xsl:text> entities referencing
     * this entity by </xsl:text><xsl:value-of select="$keys4apidoc" /><xsl:text>
     *
     * @return  rdbms.ResultIterator&lt;</xsl:text><xsl:value-of select="$fullclassname"/><xsl:text>&gt;
     * @throws  rdbms.SQLException in case an error occurs
     */
    public function get</xsl:text><xsl:value-of select="@role" /><xsl:text>Iterator() {
      if ($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']) return new HashmapIterator($this->cache['</xsl:text><xsl:value-of select="@role" /><xsl:text>']);
      return XPClass::forName('</xsl:text><xsl:value-of select="$fullclassname" /><xsl:text>')
        ->getMethod('getPeer')
        ->invoke(NULL)
        ->iteratorFor(new Criteria(&#10;</xsl:text>
          <xsl:value-of select="$keys4criteria" /><xsl:text>
      ));
    }&#10;</xsl:text>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:for-each>
  
    <!-- Closing curly brace -->  
    <xsl:text>  }</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>
