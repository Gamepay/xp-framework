<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  /**
   * Interface TemplateProcessorInterface
   *
   * @purpose  Interface for custom template processor classes.
   */
  interface TemplateProcessorInterface {
  
    /**
     * Retrieve messages generate during processing.
     *
     * @return  string[]
     */
    public function getMessages();
    
    /**
     * Set a scheme handler
     *
     * @param   var callback
     * @see     php://xslt_set_scheme_handlers
     */
    public function setSchemeHandler($cb);
    
    /**
     * Set base directory
     *
     * @param   string dir
     */
    public function setBase($dir);
    
    /**
     * Get base
     *
     * @return  string
     */
    public function getBase();
    
    /**
     * Set template file.
     *
     * @param   string file file name
     */
    public function setTemplateFile($file);
    
    /**
     * Set template buffer from custom data such as an xml string or an array.
     *
     * @param   mixed
     */
    public function setTemplateBuffer($data);

    /**
     * Set data from a xml-structure tree.
     *
     * @param   xml.Tree xsl
     */
    public function setTemplateTree(Tree $xml);
    
    /**
     * Set data source file.
     *
     * @param   string file file name
     */
    public function setInputFile($file);
    
    /**
     * Set data buffer from custom data such as an xml string or an array.
     *
     * @param   mixed
     */
    public function setInputBuffer($data);

    /**
     * Set data from a xml-structure tree.
     *
     * @param   xml.Tree xml
     */
    public function setInputTree(Tree $xml);

    /**
     * Set transformation parameters
     *
     * @param   array params associative array { param_name => param_value }
     */
    public function setParams($params);
    
    /**
     * Set transformation parameter
     *
     * @param   string name
     * @param   string value
     */
    public function setParam($name, $value);
    
    /**
     * Retrieve transformation parameter
     *
     * @param   string name
     * @return  string value
     */
    public function getParam($name);
    
    /**
     * Run the transformation
     *
     * @return  bool success
     * @throws  xml.TransformerException
     */
    public function run();
    
    /**
     * Retrieve the transformation's result
     *
     * @return  string
     */
    public function output();

    /**
     * Retrieve the transformation's result's encoding
     *
     * @return  string
     */
    public function outputEncoding();
  }
?>