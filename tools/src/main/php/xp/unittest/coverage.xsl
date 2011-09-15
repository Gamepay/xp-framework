<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet
  version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:exslt="http://exslt.org/common"
  xmlns:func="http://exslt.org/functions"
  xmlns:string="http://exslt.org/strings"
  extension-element-prefixes="func exslt string"
>
  <xsl:strip-space elements="*"/>

  <xsl:template match="/">
    <html>
      <head>
        <title><xsl:value-of select="'Code Coverage Report'" /></title>
        <style>
        <![CDATA[
          div.code {
            border: 1px solid #CBCBCB;
            padding: 10px;
            background-color: #E2E2E2;
          }
          pre.line {
            margin:0px;
            height:1.3em;
          }
          pre.line[checked] {
            background-color: 99ff66;
          }
          pre.line[unchecked] {
            background-color: ff9999;
          }
        ]]>
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" />
        <script type="text/javascript">
        <![CDATA[
          $(document).ready(function() {
            $('.code').hide();

            $('.file > h4 > a').click(function(event) {
              event.preventDefault();
              var target= $(event.target)
              var code= target.closest('.file').find('.code').first();
              if (target.html() == 'open') {
                target.html('close');
                code.slideDown(300);
              } else {
                target.html('open');
                code.slideUp(300);
              }
            });
          });
        ]]>
        </script>
      </head>
      <body>
        <h1>
          <xsl:value-of select="'Code Coverage Report - '" />
          <xsl:value-of select="/files/@time" />
        </h1>

        <xsl:for-each select="/files/file">
          <xsl:sort select="@name" />
          <xsl:apply-templates select="." />
        </xsl:for-each>

      </body>
    </html>
  </xsl:template>

  <xsl:template match="file">
    <xsl:variable name="clocs" select="count(line[@checked])" />
    <xsl:variable name="ulocs" select="count(line[@unchecked])" />
    <xsl:variable name="alocs" select="$clocs + $ulocs" />

    <div class="file">
      <h4>
        <xsl:value-of select="@name" />
        <br/>
        <span>
          <xsl:value-of select="concat($clocs, ' of ', $alocs, ' lines checked')" />
        </span>
        <xsl:value-of select="' '" />
        <a href="#">open</a>
      </h4>
      <div class="code">
        <xsl:apply-templates select="line" />
      </div>
    </div>
  </xsl:template>

  <xsl:template match="line">
    <pre class="line">
      <xsl:if test="@checked = 'checked'">
        <xsl:attribute name="checked">
          <xsl:value-of select="'checked'" />
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="@unchecked = 'unchecked'">
        <xsl:attribute name="unchecked">
          <xsl:value-of select="'unchecked'" />
        </xsl:attribute>
      </xsl:if>
      <xsl:value-of select="." />
    </pre>
  </xsl:template>

</xsl:stylesheet>
