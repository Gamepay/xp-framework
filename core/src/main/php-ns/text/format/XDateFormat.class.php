<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace text\format;
 use text\format\IFormat;
  
  /**
   * Date formatter
   *
   * @purpose  Provide a Format wrapper for date/time
   * @see      php://strftime
   * @see      xp://text.format.IFormat
   */
  class XDateFormat extends IFormat {
  
    /**
     * Get an instance
     *
     * @return  text.format.DateFormat
     */
    public function getInstance() {
      return parent::getInstance('DateFormat');
    }  

    /**
     * Apply format to argument
     *
     * @param   var fmt
     * @param   var argument
     * @return  string
     * @throws  lang.FormatException
     */
    public function apply($fmt, $argument) {
      switch (gettype($argument)) {
        case 'string':
          if (-1 == ($u= strtotime($argument))) {
            throw new \lang\FormatException('Argument "'.$argument.'" cannot be converted to a date');
          }
          break;
          
        case 'integer':
        case 'float':
          $u= (int)$argument;
          break;
          
        case 'object':
          if ($argument instanceof Date) {
            $u= $argument->getTime();
            break;
          }
          // Break missing intentionally
          
        default:
          throw new \lang\FormatException('Argument of type "'.gettype($argument).'" cannot be converted to a date');
      }
      
      return strftime($fmt, $u);
    }
  }
?>
