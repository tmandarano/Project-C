<?php
require_once('src/utils/logging.php');

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
            $this->dbh = new PDO("mysql:dbname=projectc;host=localhost", "projectc", "projectc");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            debug('new connection created');
        }

        return $this->dbh;
    }
}
?>