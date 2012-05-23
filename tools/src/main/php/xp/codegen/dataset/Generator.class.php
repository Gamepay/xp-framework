<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  $package= 'xp.codegen.dataset';

  uses(
    'xp.codegen.AbstractGenerator',
    'rdbms.DSN',
    'rdbms.DBTable',
    'rdbms.DriverManager',
    'rdbms.util.DBConstraintXmlGenerator',
    'rdbms.util.DBXMLNamingContext',
    'rdbms.util.DBXmlGenerator',
    'xml.DomXSLProcessor',
    'lang.XPClass'
  );

  /**
   * DataSet
   * =======
   * Generates rdbms.DataSet classes for use in the XP framework's O/R mapper.
   *
   * Usage:
   * <pre>
   *   $ cgen ... dataset {dsn} [-p {package}] [-h {host}] [-l {language}] [-n {nstrategy}] [-pv {prefix} [-pt {ptargets}] [-pe {pexclude}]]
   * </pre>
   *
   * Options
   * -------
   * <ul>
   *   <li>package: The package name, default "db"</li>
   *   <li>host: Which connection name to use, defaults to host name from DSN</li>
   *   <li>language: Language to generate, defaults to "xp5"</li>
   *   <li>prefix: Prefix to add to the class name, defaults to ""</li>
   *   <li>ptargets: List of table names to use with prefix separated by the pipe symbol "|", defaults to ""</li>
   *   <li>pexclude: Mode ptargets are treated - if pexclude is TRUE ptargets are treated as blacklist else as whitelist, defaults to FALSE</li>
   *   <li>nstrategy: strategy to name constraints, defaults to rdbms.util.DBXMLNamingStrategyDefault</li>
   * </ul>
   *
   * Languages
   * ---------
   * The following languages are supported: xp5, xp4
   *
   * @purpose  Code generator
   */
  class xp�codegen�dataset�Generator extends AbstractGenerator {
    const
      CONSTRAINT_FILE_NAME= '__Constraints';

    protected static
      $adapters = array();

    protected
      $host     = NULL,
      $prefix   = NULL,
      $ptargets = NULL,
      $pexclude = NULL,
      $adapter  = NULL,
      $processor= NULL,
      $package  = '',
      $naming   = '',
      $filename = NULL,
      $protected= TRUE;

    static function __static() {
      self::$adapters['mysql']= XPClass::forName('rdbms.mysql.MySQLDBAdapter');
      self::$adapters['sqlite']= XPClass::forName('rdbms.sqlite.SQLiteDBAdapter');
      self::$adapters['pgsql']= XPClass::forName('rdbms.pgsql.PostgreSQLDBAdapter');
      self::$adapters['sybase']= XPClass::forName('rdbms.sybase.SybaseDBAdapter');
    }

    /**
     * Constructor
     *
     * @param   util.cmd.ParamString args
     */
    public function __construct(ParamString $args) {
      $dsn= new DSN($args->value(0));
      $this->adapter= self::$adapters[$dsn->getDriver()]->newInstance(
        DriverManager::getInstance()->getConnection($dsn->dsn)
      );

      $this->package= $args->value('package', 'p', 'db');
      $this->host= $args->value('host', 'h', $dsn->getHost());

      $this->naming= $args->value('nstrategy', 'n', '');
      if ('' != $this->naming) DBXMLNamingContext::setStrategy(XPClass::forName($this->naming)->newInstance());

      $this->prefix= $args->value('prefix', 'pv', '');
      $this->ptargets= explode('|', $args->value('ptargets', 'pt', ''));
      $this->pexclude= $args->value('pexclude', 'pe', FALSE);

      $xsls= array();
      $lang= $args->value('lang', 'l', 'xp5.php');
      if ($this->getClass()->getPackage()->providesPackage(strtr($lang, '.', '_'))) {
        $resources= $this->getClass()->getPackage()->getPackage(strtr($lang, '.', '_'))->getResources();
        foreach ($resources as $resource) {
          $filename= substr($resource, strrpos($resource, DIRECTORY_SEPARATOR)+1);
          if (substr($filename, -8, 8) !== '.php.xsl') {
            continue;
          }
          
          $xsls[]= $resource;
        }
      } else {
        $packagepath= strtr($this->getClass()->getPackage()->getName(), '.', DIRECTORY_SEPARATOR);
        $xsls[]= $packagepath.DIRECTORY_SEPARATOR.$lang.'.xsl';
      }
      
      foreach ($xsls as $resource) {
        $processor= new DomXSLProcessor();
        $processor->setBase(__DIR__);
        $processor->setTemplateBuffer(ClassLoader::getDefault()->getResource($resource));
        $processor->setParam('package', $this->package);
        
        if ($this->prefix) {
          $processor->setParam('prefix', $this->prefix);
          $processor->setParam($this->pexclude ? 'exprefix' : 'incprefix', implode(',', $this->ptargets));
        }
        
        $this->processor[]= $processor;
      }
    }

    /**
     * Connect the database
     *
     */
    #[@target]
    public function connect() {
      $this->adapter->conn->connect();
    }

    /**
     * Fetch tables
     *
     */
    #[@target(depends= 'connect')]
    public function fetchTables() {
      return DBTable::getByDatabase(
        $this->adapter,
        $this->adapter->conn->dsn->getDatabase()
      );
    }

    /**
     * Fetch constraints
     *
     */
    #[@target(depends= 'connect')]
    public function fetchConstraints() {
      return DBConstraintXmlGenerator::createFromDatabase(
        $this->adapter,
        $this->adapter->conn->dsn->getDatabase()
      )->getTree();
    }

    /**
     * Generate XML from the tables
     *
     */
    #[@target(input= array('fetchTables', 'fetchConstraints', 'storage'))]
    public function generateTableXml($tables, $constraints, $storage) {
      $xml= array();
      foreach ($tables as $table) {

        // Calculate classname
        $className= ucfirst($table->name);
        if (isset($this->prefix)) {
          switch (1) {
            case (FALSE == $this->ptargets):
            case (in_array($table->name, $this->ptargets) && FALSE == $this->pexclude):
            case (!in_array($table->name, $this->ptargets) && TRUE == $this->pexclude):
              $className= $this->prefix.$className;
              break;
          }
        }

        $gen= DBXmlGenerator::createFromTable(
          $table,
          $this->host,
          $this->adapter->conn->dsn->getDatabase()
        )->getTree();

        // Add extra information
        with ($node= $gen->root->children[0]); {
          $node->setAttribute('dbtype', $this->adapter->conn->dsn->getDriver());
          $node->setAttribute('class', $className);
          $node->setAttribute('package', $this->package);
        }

        $xml[]= $storage->write($className, $gen->getSource(INDENT_DEFAULT));
      }
      $storage->write(self::CONSTRAINT_FILE_NAME, $constraints->getSource(INDENT_DEFAULT));
      return $xml;
    }

    /**
     * Apply XSLT stylesheet and generate sourcecode
     *
     */
    #[@target(input= array('generateTableXml', 'output'))]
    public function generateCode($tables, $output) {
      $dir= strtr($this->package, '.', '/').'/';

      foreach ($this->processor as $processor) {
        $processor->setParam('definitionpath', $this->storage->getUri());
        $processor->setParam('constraintfile', $this->storage->getUri().self::CONSTRAINT_FILE_NAME);
        
        $processor->registerInstance('generator', $this);
      }
      
      foreach ($tables as $stored) {
        
        foreach ($this->processor as $processor) {
          $this->filename= NULL;
          $this->protected= TRUE;
          
          $processor->setInputBuffer($stored->data());
          $processor->run();
          
          if (!isset($this->filename)) {
            continue;
          } else {
            // TODO: validate filename in some way 
          }
          
          $output->append($dir.$this->filename, $processor->output(), !$this->protected);
        }
      }
    }

    /**
     * Creates a string representation of this generator
     *
     * @return  string
     */
    public function toString() {
      return $this->getClassName().'['.$this->adapter->conn->dsn->toString().']';
    }
    
    /**
     * Allows the DomXSLProcessor to communicate a filename.
     *
     * @param string $classname
     */
    #[@xslmethod]
    public function setFilename($filename) {
      $this->filename= $filename;
    }
    
    /**
     * Allows the DomXSLProcessor to communicate if existing files are protected.
     *
     * @param string $classname
     */
    #[@xslmethod]
    public function setProtected($protected) {
      $this->protected= $protected === 'false' ? FALSE : TRUE;
    }
  }
?>