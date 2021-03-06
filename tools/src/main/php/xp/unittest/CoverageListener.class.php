<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses(
    'unittest.TestListener',
    'io.streams.OutputStreamWriter',
    'xml.DomXSLProcessor',
    'xml.Node'
  );

  /**
   * Coverage listener - only shows details for failed tests.
   *
   * @purpose  TestListener
   */
  class CoverageListener extends Object implements TestListener {

    private
      $paths    = array(),
      $processor= NULL;

    public function registerPath($path) {
      $this->paths[]= $path;
    }

    /**
     * Constructor
     *
     * @param io.streams.OutputStreamWriter out
     */
    public function __construct() {
      $this->processor= new DomXSLProcessor();
      $this->processor->setXSLBuf($this->getClass()->getPackage()->getResource('coverage.xsl'));

      xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
    }

    /**
     * Called when a test case starts.
     *
     * @param   unittest.TestCase failure
     */
    public function testStarted(TestCase $case) {

    }

    /**
     * Called when a test fails.
     *
     * @param   unittest.TestFailure failure
     */
    public function testFailed(TestFailure $failure) {

    }

    /**
     * Called when a test errors.
     *
     * @param   unittest.TestFailure error
     */
    public function testError(TestError $error) {

    }

    /**
     * Called when a test raises warnings.
     *
     * @param   unittest.TestWarning warning
     */
    public function testWarning(TestWarning $warning) {

    }

    /**
     * Called when a test finished successfully.
     *
     * @param   unittest.TestSuccess success
     */
    public function testSucceeded(TestSuccess $success) {

    }

    /**
     * Called when a test is not run because it is skipped due to a
     * failed prerequisite.
     *
     * @param   unittest.TestSkipped skipped
     */
    public function testSkipped(TestSkipped $skipped) {

    }

    /**
     * Called when a test is not run because it has been ignored by using
     * the @ignore annotation.
     *
     * @param   unittest.TestSkipped ignore
     */
    public function testNotRun(TestSkipped $ignore) {

    }

    /**
     * Called when a test run starts.
     *
     * @param   unittest.TestSuite suite
     */
    public function testRunStarted(TestSuite $suite) {
      xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
    }

    /**
     * Called when a test run finishes.
     *
     * @param   unittest.TestSuite suite
     * @param   unittest.TestResult result
     */
    public function testRunFinished(TestSuite $suite, TestResult $result) {
      $coverage= xdebug_get_code_coverage();
      xdebug_stop_code_coverage();

      $results= array();
      foreach ($coverage as $fileName => $data) {
        foreach ($this->paths as $path) {
          if (substr($fileName, 0, strlen($path)) !== $path) {
            continue;
          }

          $results[dirname($fileName)][basename($fileName)]= $data;
          break;
        }
      }

      $pathsNode= new Node('paths');
      foreach ($results as $pathName => $files) {
        $pathNode= new Node('path');
        $pathNode->setAttribute('name', $pathName);

        foreach ($files as $fileName => $data) {
          $fileNode= new Node('file');
          $fileNode->setAttribute('name', $fileName);

          $num= 1;
          $handle= fopen($pathName.'/'.$fileName, 'r');
          while (!feof($handle)) {
            $line= stream_get_line($handle, 1000, "\n");

            $lineNode = new Node('line', new CData($line));
            if (isset($data[$num])) {
              if ($data[$num] > 0 || $data[$num] < -1) {
                $lineNode->setAttribute('checked', 'checked');
              } elseif ($data[$num] > -2) {
                $lineNode->setAttribute('unchecked', 'unchecked');
              }
            }

            $fileNode->addChild($lineNode);
            ++$num;
          }

          $pathNode->addChild($fileNode);
        }
        $pathsNode->addChild($pathNode);
      }
      $now= time();
      $pathsNode->setAttribute('time', date('Y-m-d H:i:s'));

      $this->processor->setXMLBuf($pathsNode->getSource());
      $this->processor->run();

      $reportfile= 'coverage-'.date('Y-m-d-H-i-s').'.html';
      $reportfile= 'coverage.html';
      file_put_contents($reportfile, $this->processor->output());
    }
  }
?>