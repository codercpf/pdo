<?php 

class pdoMySQL{
	public static $config = array();				//设置链接参数，配置信息
	public static $link = null;						//保存链接标识符，即实例化后的pdo
	public static $pconnect = false;				//是否开启长连接，默认不开启
	public static $dbVersion = null;				//保存数据库版本，默认为空
	public static $connected = false;				//判断是否链接成功
	public static $PDOStatement = null;				//保存PDOStatement对象
	public static $queryStr = null;					//最后执行的SQL语句
	public static $error = null;					//保存错误信息
	public static $lastInsertId = null;				//最后插入记录的ID号
	public static $numRows = 0;						//上一步操作受影响的记录的行数


	public function __construct($dbConfig=''){		//默认配置为空
		if (!class_exists("PDO")) {
			self::throw_exception("不支持PDO，请先开启");
		}

		//如果么有传入配置项，则采用默认配置
		if(!is_array($dbConfig)){
			//这些配置项放在配置文件中
			$dbConfig = array(
				'hostname' => DB_HOST,
				'username' => DB_USER,
				'password' => DB_PWD,
				'database' => DB_NAME,
				'hostport' => DB_PORT,
				'dbms'	   => DB_TYPE,
				'dsn'	   => DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME
			);
		}
		if (empty($dbConfig['hostname'])) {
			self::throw_exception('没有定义数据库配置，请先定义');
		}

		//调用配置信息
		self::$config = $dbConfig;
		if(empty(self::$config['params'])){		//这是链接数据库时pdo的第四个参数，设置pdo属性
			self::$config['params'] = array();
		}

		//采用单例模式，即只连接了一个，如果没有链接的话进行链接
		if (!isset(self::$link)) {
			$configs = self::$config;
			if(self::$pconnect){
				//开启长连接，添加到配置数组中
				$configs['params'][constant("PDO::ATTR_PERSISTENT")] = true;	
			}
			//链接PDO，单例模式
			try {
				self::$link = new PDO($configs['dsn'], $configs['username'], $configs['password'], $configs['params']);				
			} catch (PDOException $e) {
				self::throw_exception($e->getMessage());
			}

			//没有链接，则报错
			if(!self::$link){
				self::throw_exception('PDO链接错误');
				return false;
			}
			
			self::$link->exec('SET NAMES '.DB_CHARSET);          //设置字符集
			self::$dbVersion = self::$link->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
			self::$connected = true;

			//配置已经使用过了，释放资源
			unset($configs);
		}

	}

	/*
	* 查询所有记录
	* @param string $sql
	* @return unknown
	*/
	public static function getAll($sql = null){
		if ($sql != null) {
			self::query($sql);
		}
		$result = self::$PDOStatement->fetchAll(constant("PDO::FETCH_ASSOC"));
		return $result;
	}

	/*
	* 查询一条记录
	* @param string $sql
	* @return unknown
	*/
	public static function getRow($sql=null){
		if ($sql != null) {
			self::query($sql);
		}
		$result = self::$PDOStatement->fetch(constant("PDO::FETCH_ASSOC"));
		return $result;
	}

	/*
	* 根据主键查找记录
	* @param string $tabName
	* @param int $priId
	* @param string $fields
	* @return mixed
	*/
	public static function findById($tabName, $priId, $fields='*'){
		$sql = 'select %s from %s where id=%d';
		return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));  //格式化输入
	}
	//解析要查找的字段，防止用到特殊字符、关键字（用反引号引用
	public static function parseFields($fields){

	}







	/*
	* execute()函数，主要完成增、删、改操作
	* 返回受影响的记录的条数
	* @param string $sql
	* @return boolean|unknown
	*/
	public static function execute($sql=null){
		$link = self::$link;
		if(!$link){
			return false;
		}
		self::$queryStr = $sql;
		if(!empty(self::$PDOStatement)){
			self::free();
		}
		$result = $link->exec(self::$queryStr);		//执行SQL操作
		self::haveErrorThrowException();			//有异常，抛出异常
		if ($result) {
			self::$lastInsertId = $link->lastInsertId();	//若是插入操作，则返回最后一个ID
			self::$numRows = $result;						//若是修改、删除，则返回受影响的行数
			return self::$numRows;
		}else{
			return false;
		}		
	}



	/*
	* 释放结果集
	*/
	public static function free(){
		self::$PDOStatement = null;
	}

	public static function query($sql=''){
		$link = self::$link;
		if(!$link) {
			return false;		//没有PDO对象，则退出
		}
		//判断之前是否有结果集，如果有的话，释放结果集
		if (!empty(self::$PDOStatement)) {
			self::free();
		}
		self::$queryStr = $sql;	//保存最后执行的sql语句
		//执行操作
		self::$PDOStatement = $link->prepare(self::$queryStr);
		$res = self::$PDOStatement->execute();
		self::haveErrorThrowException();			//若有错误，则抛出异常
		return $res;
	}
	/*
	* 自定义的异常处理
	*/
	public static function haveErrorThrowException(){
		$obj = empty(self::$PDOStatement) ? self::$link : self::$PDOStatement;
		$arrError = $obj->errorInfo();
		// print_r($arrError);
		if ($arrError[0] != '00000') {
			self::$error = 'SQLSTATE: '.$arrError[0].'<br/>SQL Error: '.$arrError[2].'<br/>Error SQL: '.self::$queryStr;
			self::throw_exception(self::$error);
			return false;
		}
		//没有SQL语句
		if(self::$queryStr == ''){
			self::throw_exception('没有执行SQL语句');
			return false;
		}
	}

	/*
	* 自定义错误处理
	* @param unknown $errMsg
	*/
	public static function throw_exception($errMsg){
		echo '<div style="width:80%;background-color:#ABCDEF;color:black;font-size:20px;padding:20px 0;">'
				.$errMsg.
			 '</div>';
	}


}

require_once 'config.php';
$pdoMySQL = new pdoMySQL;
// var_dump($pdoMySQL);
/*
$sql = "select * from user1";
$res = $pdoMySQL->getAll($sql);
*/
/*
$sql = "select * from user where id=15";
$res = $pdoMySQL->getRow($sql);
*/
// $sql = "insert user(username,passwd,email) values('cpf2','cpf2','cpf2@cpf.com')";
// $sql = "delete from user where id>=15";
$sql = "update user set username='abc333' where id=14";
$res = $pdoMySQL->execute($sql);
print_r($res);
echo "<hr/>".$pdoMySQL::$lastInsertId;