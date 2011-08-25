<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:exslt="http://exslt.org/common"
  xmlns:func="http://exslt.org/functions"
  xmlns:string="http://exslt.org/strings"
  xmlns:my="http://no-sense.de/my"
  xmlns:php="http://php.net/xsl"
  extension-element-prefixes="func exslt string"
>
  <xsl:param name="definitionpath" />
  <xsl:param name="constraintfile" />
  <xsl:param name="package" />
  <xsl:param name="prefix" />
  <xsl:param name="incprefix" />
  <xsl:param name="exprefix" />
  <xsl:param name="prefixRemove" />
  
  <xsl:variable name="lcletters">abcdefghijklmnopqrstuvwxyz</xsl:variable>
  <xsl:variable name="ucletters">ABCDEFGHIJKLMNOPQRSTUVWXYZ</xsl:variable>
  <xsl:variable name="this" select="/document" />
  
  <func:function name="my:ucfirst">
    <xsl:param name="string" />
    <func:result select="concat(
      translate(substring($string, 1, 1), $lcletters, $ucletters),
      substring($string, 2)
    )"/>
  </func:function>

  <func:function name="my:constraintSingleTest">
    <xsl:param name="keyset" />
    <xsl:param name="sourceIndexSet" />
    <xsl:choose>
      <xsl:when test="count(exslt:node-set($keyset)) = 0"><func:result select="false()" /></xsl:when>
      <xsl:when test="count(exslt:node-set($sourceIndexSet)) = 0"><func:result select="false()" /></xsl:when>
      <xsl:when test="my:keysetSingleTest($keyset, exslt:node-set($sourceIndexSet)[1]/key)"><func:result select="true()" /></xsl:when>
      <xsl:otherwise><func:result select="my:constraintSingleTest($keyset, exslt:node-set($sourceIndexSet)[position() != 1])" /></xsl:otherwise>
    </xsl:choose>
  </func:function>

  <func:function name="my:keysetSingleTest">
    <xsl:param name="keyset" />
    <xsl:param name="sourceKeyset" />
    <xsl:choose>
      <xsl:when test="boolean(count(exslt:node-set($keyset)) = 0) and boolean(count(exslt:node-set($sourceKeyset)) = 0)"><func:result select="true()" /></xsl:when>
      <xsl:when test="count(exslt:node-set($keyset)) = 0"><func:result select="false()" /></xsl:when>
      <xsl:when test="count(exslt:node-set($sourceKeyset)) = 0"><func:result select="false()" /></xsl:when>
      <xsl:otherwise>
        <xsl:variable name="testkey" select="exslt:node-set($keyset)[1]/@sourceattribute" />
        <xsl:choose>
          <xsl:when test="count(exslt:node-set($keyset)[@sourceattribute = $testkey]) != count(exslt:node-set($sourceKeyset)[text() = $testkey])"><func:result select="false()" /></xsl:when>
          <xsl:otherwise>
            <func:result select="my:keysetSingleTest(exslt:node-set($keyset)[@sourceattribute != $testkey], exslt:node-set($sourceKeyset)[text() != $testkey])" />
          </xsl:otherwise>
        </xsl:choose>
      </xsl:otherwise>
    </xsl:choose>
  </func:function>

  <func:function name="my:prefixedClassName">
    <xsl:param name="tname" />
    <xsl:param name="prefix"  select="$prefix" />
    <xsl:param name="include" select="$incprefix" />
    <xsl:param name="exclude" select="$exprefix" />
    <xsl:param name="remove"  select="$prefixRemove" />
    <xsl:variable name="includeSet" select="string:tokenize($include, ',')" />
    <xsl:variable name="excludeSet" select="string:tokenize($exclude, ',')" />
    <xsl:variable name="excludetest" select="boolean(count($includeSet) = 0) and not(count($excludeSet) = 0) and not($excludeSet[text() = $tname])" />
    <xsl:variable name="includetest" select="not(count($includeSet) = 0) and boolean(count($excludeSet) = 0) and boolean($includeSet[text() = $tname])" />
    <xsl:variable name="alltest" select="boolean(count($excludeSet) = 0) and boolean(count($includeSet) = 0) and boolean($prefix)" />
    <xsl:variable name="p">
      <xsl:choose>
        <xsl:when test="$includetest or $excludetest or $alltest"><xsl:value-of select="$prefix" /></xsl:when>
        <xsl:otherwise><xsl:value-of select="''" /></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="r">
      <xsl:choose>
        <xsl:when test="$includetest or $excludetest"><xsl:value-of select="$remove" /></xsl:when>
        <xsl:otherwise><xsl:value-of select="''" /></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:choose>
      <xsl:when test="$r = substring($tname, 1, string-length($r))">
        <func:result select="concat($p, my:ucfirst(substring($tname, string-length($r) + 1)))" />
      </xsl:when>
      <xsl:otherwise>
        <func:result select="concat($p, my:ucfirst($tname))" />
      </xsl:otherwise>
    </xsl:choose>
  </func:function>
  
  <func:function name="my:separator">
    <xsl:param name="database"/>
    <xsl:param name="table"/>
    <xsl:param name="dbtype"/>
    <xsl:choose>
      <xsl:when test="$dbtype = 'mysql'"><func:result select="concat($database, '.', $table)"/></xsl:when>
      <xsl:when test="$dbtype = 'sybase'"><func:result select="concat($database, '..', $table)"/></xsl:when>
      <xsl:otherwise><func:result select="$table"/></xsl:otherwise>
    </xsl:choose>
  </func:function>
  
  <func:function name="my:distinctRole">
    <xsl:param name="nodeset" />
    <xsl:choose>
      <xsl:when test="count($nodeset[@role != $nodeset[1]/@role]) &lt; 1"><func:result select="$nodeset[1]" /></xsl:when>
      <xsl:otherwise><func:result select="$nodeset[@role = $nodeset[1]/@role][1] | my:distinctRole($nodeset[@role != $nodeset[1]/@role])" /></xsl:otherwise>
    </xsl:choose>
  </func:function>
  
  <func:function name="my:referenced">
    <xsl:param name="datasetnode" />
    <func:result select="my:distinctRole(document($constraintfile)/document/database[@database = $datasetnode/table/@database]/table/constraint/reference[@table = $datasetnode/table/@name])" />
  </func:function>
  
  <func:function name="my:referencing">
    <xsl:param name="datasetnode" />
    <func:result select="my:distinctRole($datasetnode/table/constraint/reference)" />
  </func:function>
  
  <func:function name="my:sameKeys">
    <xsl:param name="keySet1" />
    <xsl:param name="keySet2" />
    <xsl:choose>
      <xsl:when test="count($keySet1) != count($keySet2)"><func:result select="false()" /></xsl:when>
      <xsl:when test="count($keySet1) = 0"><func:result select="true()" /></xsl:when>
      <xsl:when test="$keySet1[1]/text() != $keySet2[1]/text()"><func:result select="false()" /></xsl:when>
      <xsl:otherwise><func:result select="my:sameKeys($keySet1[position() != 1], $keySet2[position() != 1])" /></xsl:otherwise>
    </xsl:choose>
  </func:function>

  <func:function name="my:distinctIndex">
    <xsl:param name="indexSet" />
    <xsl:choose>
      <xsl:when test="count( $indexSet[not(my:sameKeys(key, $indexSet[1]/key))] ) &lt; 1"><func:result select="$indexSet[1]" /></xsl:when>
      <xsl:otherwise><func:result select="$indexSet[1] | my:distinctIndex( $indexSet[not(my:sameKeys(key, $indexSet[1]/key))] )" /></xsl:otherwise>
    </xsl:choose>
  </func:function>
  
  <func:function name="my:camelCase">
    <xsl:param name="string" />
    
    <xsl:variable name="tokens" select="string:tokenize($string, '_')" />
    <xsl:variable name="ucfTokens">
      <xsl:value-of select="$tokens[1]" />
      <xsl:for-each select="$tokens[position() > 1]">
        <xsl:value-of select="my:ucfirst(.)" />
      </xsl:for-each>
    </xsl:variable>
    
    <func:result select="string:concat($ucfTokens)" />
  </func:function>
  
  <func:function name="my:setFilename">
    <xsl:param name="filename" />
    <xsl:value-of select="php:function('XSLCallback::invoke', 'generator', 'setFilename', string($filename))" />
  </func:function>
  
  <func:function name="my:setProtected">
    <xsl:param name="protected" />
    <xsl:value-of select="php:function('XSLCallback::invoke', 'generator', 'setProtected', string($protected))" />
  </func:function>
</xsl:stylesheet>
