<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  /**
   * Locale
   * 
   * Usage [retreiving default locale]
   * <code>
   *   $locale= &Locale::getDefault();
   *   var_dump($locale);
   * </code>
   *
   * Usage [setting default locale]
   * <code>
   *   Locale::setDefault(new Locale('de_DE'));
   * </code>
   *
   * @see      http://ftp.ics.uci.edu/pub/ietf/http/related/iso639.txt
   * @see      http://userpage.chemie.fu-berlin.de/diverse/doc/ISO_3166.html
   * @purpose  Represent a locale
   */
  class Locale extends Object {
    var
      $lang     = '',
      $country  = '',
      $variant  = '';
      
    /**
     * Construct
     *
     * @access  public
     * @param   string lang 2-letter abbreviation of language
     * @param   string country 2-letter abbreviation of country
     * @param   string variant default ''
     */
    function __construct() {
      switch (func_num_args()) {
        case 1: 
          sscanf(func_get_arg(0), '%2s_%2s@%s', $this->lang, $this->country, $this->variant);
          break;
          
        case 2:
          list($this->lang, $this->country)= func_get_args();
          break;
          
        case 3:
          list($this->lang, $this->country, $this->variant)= func_get_args();
          break;
      }
      parent::__construct();
    }
    
    /**
     * Get default locale
     *
     * @model   static
     * @access  public
     * @return  &util.Locale
     */
    function &getDefault() {
      return new Locale(('C' == ($locale= setlocale(LC_ALL, 0)) 
        ? 'en_US'
        : $locale
      ));
    }
    
    /**
     * Set default locale for this script
     *
     * @model   static
     * @access  public
     * @param   &util.Locale locale
     */
    function setDefault(&$locale) {
      setlocale(LC_ALL, $locale->toString());
    }
    
    /**
     * Create string representation
     *
     * Examples:
     * <pre>
     * de_DE
     * en_US
     * de_DE@euro
     * </pre>
     *
     * @access  public
     * @return  string
     */
    function toString() {
      return sprintf('%s_%s', $this->lang, $this->country).(empty($this->variant) 
        ? '' 
        : '@'.$this->variant
      );
    }
  }
?>
