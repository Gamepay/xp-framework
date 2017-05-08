<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('util.Date');

  /**
   * Mapping for sun.util.calendar.ZoneInfo
   *
   * @purpose  Mapping
   */
  class ZoneInfoMapping extends XPObject {

    var
      $rawOffset= NULL,
      $rawOffsetDiff= NULL,
      $checksum= NULL,
      $dstSavings= NULL,
      $transitions= NULL,
      $offsets= NULL,
      $simpleTimeZoneParams= NULL,
      $willGMTOffsetChange= NULL,
      $ID= NULL;

    /**
     * Set rawOffset
     *
     * @param   lang.XPObject rawOffset
     */
    public function setRawOffset($rawOffset) {
      $this->rawOffset= $rawOffset;
    }

    /**
     * Get rawOffset
     *
     * @return  lang.XPObject
     */
    public function getRawOffset() {
      return $this->rawOffset;
    }

    /**
     * Set rawOffsetDiff
     *
     * @param   lang.XPObject rawOffsetDiff
     */
    public function setRawOffsetDiff($rawOffsetDiff) {
      $this->rawOffsetDiff= $rawOffsetDiff;
    }

    /**
     * Get rawOffsetDiff
     *
     * @return  lang.XPObject
     */
    public function getRawOffsetDiff() {
      return $this->rawOffsetDiff;
    }

    /**
     * Set checksum
     *
     * @param   lang.XPObject checksum
     */
    public function setChecksum($checksum) {
      $this->checksum= $checksum;
    }

    /**
     * Get checksum
     *
     * @return  lang.XPObject
     */
    public function getChecksum() {
      return $this->checksum;
    }

    /**
     * Set dstSavings
     *
     * @param   lang.XPObject dstSavings
     */
    public function setDstSavings($dstSavings) {
      $this->dstSavings= $dstSavings;
    }

    /**
     * Get dstSavings
     *
     * @return  lang.XPObject
     */
    public function getDstSavings() {
      return $this->dstSavings;
    }

    /**
     * Set transitions
     *
     * @param   lang.XPObject transitions
     */
    public function setTransitions($transitions) {
      $this->transitions= $transitions;
    }

    /**
     * Get transitions
     *
     * @return  lang.XPObject
     */
    public function getTransitions() {
      return $this->transitions;
    }

    /**
     * Set offsets
     *
     * @param   lang.XPObject offsets
     */
    public function setOffsets($offsets) {
      $this->offsets= $offsets;
    }

    /**
     * Get offsets
     *
     * @return  lang.XPObject
     */
    public function getOffsets() {
      return $this->offsets;
    }

    /**
     * Set simpleTimeZoneParams
     *
     * @param   lang.XPObject simpleTimeZoneParams
     */
    public function setSimpleTimeZoneParams($simpleTimeZoneParams) {
      $this->simpleTimeZoneParams= $simpleTimeZoneParams;
    }

    /**
     * Get simpleTimeZoneParams
     *
     * @return  lang.XPObject
     */
    public function getSimpleTimeZoneParams() {
      return $this->simpleTimeZoneParams;
    }

    /**
     * Set willGMTOffsetChange
     *
     * @param   lang.XPObject willGMTOffsetChange
     */
    public function setWillGMTOffsetChange($willGMTOffsetChange) {
      $this->willGMTOffsetChange= $willGMTOffsetChange;
    }

    /**
     * Get willGMTOffsetChange
     *
     * @return  lang.XPObject
     */
    public function getWillGMTOffsetChange() {
      return $this->willGMTOffsetChange;
    }

    /**
     * Set ID
     *
     * @param   lang.XPObject ID
     */
    public function setID($ID) {
      $this->ID= $ID;
    }

    /**
     * Get ID
     *
     * @return  lang.XPObject
     */
    public function getID() {
      return $this->ID;
    }
  } 
?>
