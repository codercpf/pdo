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
		$sql = 'select %s from %s where id=%d';			//格式化输入
		return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));
	}	
	//解析要查找的字段，防止用到特殊字符、关键字（用反引号引用
	public static function parseFields($fields){
		if (is_array($fields)) {
			// $fields是数组时，调用回调函数pdoMySQL中的addSpecialChar()来处理数组中的元素
			array_walk($fields, array('pdoMySQL', 'addSpecialChar'));
			//用逗号，链接处理过的各个数组元素，如"name,age,work"
			$fieldsStr = implode(',', $fields);
		}elseif(is_string($fields) && !empty($fields)){
			if (strpos($fields, '`') === false) {	//查找引号位置，找不到即恒等于false
				//先将字符串以逗号为分隔符，分割返回数组
				$fields = explode(',', $fields);
				//回调函数，处理返回数组中的每个元素，
				array_walk($fields, array('pdoMySQL','addSpecialChar'));
				$fieldsStr = implode(',', $fields);
			}else{
				//没有反引号时，原样返回
				$fieldsStr = $fields;
			}
		}else{
			$fieldsStr = '*';
		}

		return $fieldsStr;
	}
	//通过反引号引用字段，
	//防止使用的字段和数据库关键字冲突
	public static function addSpecialChar($value){
		if ($value==='*' || strpos($value, '.')!==false || strpos($value, '`')!==false) {
			//查询全部，或字段中有点、引用等
			//不用做处理
		}elseif (strpos($value, '`') === false) {
			$value = '`'.trim($value).'`';
		}
		return $value;
	}

	/*
	* find()方法完成普通查询，带有条件、分组等
	*/
	public static function find($tables,$where=null,$fields='*',$group=null,$having=null,$order=null,$limit=null)
	{
		$sql = 'select '.self::parseFields($fields).' from '.$tables
			.self::parseWhere($where)
			.self::parseGroup($group)
			.self::parseHaving($having)
			.self::parseOrder($order)
			.self::parseLimit($limit);
		// echo $sql;exit;		
		$dataAll = self::getAll($sql);
		if (count($dataAll)==1) {
			$result = $dataAll[0];
		}else{
			$result = $dataAll;
		}
		return $result;
	}
	// 解析parseWhere()方法
	public static function parseWhere($where){
		$whereStr = '';
		if(is_string($where) && !empty($where)){
			$whereStr = $where;
		}
		return empty($whereStr) ? '' : ' where '.$whereStr;
	}
	// 解析分组group by
	public static function parseGroup($group){
		$groupStr = '';
		if(is_array($group)){
			$groupStr .= ' group by '.implode(',', $group);
		}elseif(is_string($group) && !empty($group)){
			$groupStr .= ' group by '.$group;
		}
		return empty($groupStr) ? '' : $groupStr;
	}
	// 对分组结果进行二次筛选
	public static function parseHaving($having){
		$havingStr = '';
		if (is_string($having) && !empty($having)) {
			$havingStr .= ' HAVING '.$having;
		}
		return $havingStr;
	}
	// 解析排序
	public static function parseOrder($order){
		$orderStr = '';
		if (is_array($order)) {
			$orderStr .= ' ORDER BY '.join(',', $order);	//join(',', array())将数组转为字符串
		}elseif (is_string($order) && !empty($order)) {
			$orderStr .= ' ORDER BY '.$order;
		}
		return $orderStr;
	}
	// 解析parseLimit，限制显示条数
	// limit 3；	limit(1,3)
	public static function parseLimit($limit){
		$limitStr='';
		//当传入的值为数组，且元素数目大于1时，取前两个值
		if (is_array($limit)) {
			if (count($limit) > 1) {
				$limitStr .= ' LIMIT '.$limit[0].','.$limit[1];
			}else{
				$limitStr .= ' LIMIT '.$limit[0];
			}
		}elseif (is_string($limit) && !empty($limit)) {	
		//此处不能用is_numeric，因可能传2个参数；故要用is_string()，传一个参数时加''
			$limitStr .= ' LIMIT '.$limit;
		}
		return $limitStr;
	}

	/*
	* 添加记录的操作
	* @param array $data
	* @param string $table
	*
	* array(
		'username'=>'imooc',
		'passwd'  =>'imooc',
		'email'   =>'123@123.com'
	  )
	  insert user(username,passwd,email) values('aaa','bbb',ccc');
	*/
	public static function add($data, $table){
		//数组中的键名是字段名，故先取出数组中的键名，以逗号链接；组成要插入的字段的语句
		$keys = array_keys($data);	//获取数组中的key，组成新数组
		array_walk($keys, array('pdoMySQL', 'addSpecialChar'));		//处理字段名中的关键词
		$fieldsStr = join(',', $keys);	//组成语句
		//数组中的值是要插入的内容，以 ',' 链接；两边各加单引号
		$values = "'".join("','", array_values($data))."'";
		//组成SQL语句
		$sql = "insert {$table}({$fieldsStr}) values({$values})";		
			// echo $sql;
		return self::execute($sql);
	}

	/*
	* 更新记录的操作；更新时$limit只能传一个参数，传两个会报错
	* @param array $data
	* @param string $table
	* @param string $where
	* @param string $order
	* @param string $limit
	*
	* array(
		'username'=>'imooc111',
		'passwd'  =>'imooc222',
		'email'   =>'im444@123.com'
	  )
	  update user set username='imooc111', password='imooc222' ... where id=14 order by id asc limit 2;
	*/
	public static function update($data, $table, $where=null, $order=null, $limit=0){
		$sets = '';
		//数组中的键名是字段名、值是更新的数据，故先组成更新语句
		foreach ($data as $key => $val) {
			$sets .= $key ."='".$val."',";
		}
		$sets = rtrim($sets, ',');
		$sql = "update {$table} set {$sets} ".self::parseWhere($where)
								  .self::parseOrder($order)
								  .self::parseLimit($limit);
		// echo $sql;
		return self::execute($sql);
	}

	/*
	* 删除记录的操作	
	* @param string $table
	* @param string $where
	* @param string $order
	* @param string $limit
	*
	*/
	public static function delete($table, $where=null, $order=null, $limit=0){
		$sql = "delete from {$table} " .self::parseWhere($where)
									   .self::parseOrder($order)
									   .self::parseLimit($limit);
		return self::execute($sql);
	}

	/*
	* 得到最后执行的SQL语句
	*/
	public static function getLastSql(){
		$link = self::$link;
		if (!$link) {
			return false;
		}
		return self::$queryStr;
	}

	/*
	* 得到上一步插入操作产生的ID值，即auto_increment
	*/
	public static function getLastInsertId(){
		$link = self::$link;
		if (!$link) {
			return false;
		}
		return self::$lastInsertId;
	}

	/*
	* 得到数据库版本
	*/
	public static function getDbVersion(){
		$link = self::$link;
		if (!$link) {
			return false;
		}
		return self::$dbVersion;
	}

	/*
	* 得到数据库的所有数据表
	*/
	public static function showTables(){
		$tables = array();
		if (self::query("SHOW TABLES")) {
			$result = self::getAll();
			// print_r($result);exit;
			foreach ($result as $key => $value) {
				$tables[$key] = current($value);
			}
		}
		return $tables;
	}

	/*
	* 销毁链接对象，关闭数据库
	*/
	public static function close(){
		self::$link = null;
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

/*
require_once 'config.php';
$pdoMySQL = new pdoMySQL;
*/

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
/*
$sql = "update user set username='abc333' where id=14";
$res = $pdoMySQL->execute($sql);
print_r($res);
echo "<hr/>".$pdoMySQL::$lastInsertId;
*/
/*
$tabName = 'user';
$priId = '8';
// $fields = 'username,email';
// $fields = array('username','passwd');
$fields = '*';
$res = $pdoMySQL->findById($tabName,$priId,$fields);
print_r($res);
*/

/*
$tables = 'user';
$where = 'id>=6';
*/

// $res = $pdoMySQL->find($tables,$where);
// $res = $pdoMySQL->find($tables, $where, 'username,email');
// $res = $pdoMySQL->find($tables, $where, '*','username');
// $res = $pdoMySQL->find($tables, $where, '*,count(*) as total','username','count(*)>=1');
// $res = $pdoMySQL->find($tables, $where, '*', null,null,'id asc');
// $res = $pdoMySQL->find($tables,null,'*',null,null,null,'2');
// $res = $pdoMySQL->find($tables,null,'*',null,null,null,'2,2');
// $res = $pdoMySQL->find($tables,null,'*',null,null,null,array(2));
// $res = $pdoMySQL->find($tables,null,'*',null,null,null,array(2,3));
// print_r($res);
/*
$data = array(
		'username'=>'imooc',
		'passwd'  =>'imooc',
		'email'   =>'123@123.com'
	);
var_dump($pdoMySQL->add($data,$tables));
*/

/*
$data = array(
		'username'=>'imooc111',
		'passwd'  =>'imooc222',
		'email'   =>'im444@123.com'
	  );
var_dump($pdoMySQL->update($data,$tables,'id=12'));
*/

// var_dump($pdoMySQL->delete($tables,'id>=10','id asc','1'));

// print_r($pdoMySQL->showTables());