<?php
class Database
{
	private static $connection = null;
	
	private static function connect()
    {
        $config = parse_ini_file(DB_CONFIG_BASE_PATH . 'db.ini', true);
		
		$driver = $config['mysql']['driver'];
		$dsn = $driver . ':';
		$isFirst = true;
		foreach($config['dsn'] as $key => $value){
			if(true == $isFirst){
				$isFirst = false;
			}
			else{
				$dsn .= ';';
			}
			
			$dsn .= $key . '=' . $value;
		}
		
		$user = $config['mysql']['user'];
		$password = $config['mysql']['password'];
		
		$connectOptions = $config['connect-options'];
        foreach($connectOptions as $key => $value){
            $key = constant($key);
            $nConnectOptions[$key] = $value;
        }
		
		self::$connection = new PDO($dsn, $user, $password, $nConnectOptions);
        
		foreach($config['attributes'] as $key => $value){
			self::$connection->setAttribute(constant($key), constant($value));
		}
		
		return self::$connection;
	}
	
	public static function __callStatic($PDOFunction, $args)
	{
		$callback = array(self::connect(), $PDOFunction);
		return call_user_func_array($callback, $args);
	}
}
?>
