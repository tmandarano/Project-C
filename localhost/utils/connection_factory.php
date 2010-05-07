<?php
require_once('localhost/config/config.php');
require_once('localhost/utils/logging.php');

class ConnectionFactory
{
    private static $factory;
    public static function getFactory()
    {
        if (!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    private $dbh;
    private $debug = false;

    public function getConnection($debug = false) 
    {
    	if (!$this->dbh)
        {
          try {
            $this->dbh = new PDO(PDO_DSN, PDO_USER, PDO_PASSWORD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($debug) {
              debug('new connection created');
            }
          } catch (PDOException $e) {
            debug('DB connection failed: ' . $e->getMessage());
            return False;
          }
        }

        return $this->dbh;
    }
}
?>
