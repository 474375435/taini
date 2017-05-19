<?php
	
	//开发一个工具类，完成对mysql的各种操作

	//开发类的步骤
	//1. 类名  DAOMySQLi
	//2. 定属性...
	//3..确定方法.

	class DAOMySQLi{
		
		//确定属性
		//有一种编程风格，就是把属性的名字方式为
		// 访问修饰符 $_属性名; //老外.
		private $_host;
		private $_user;
		private $_pwd;
		private $_database;
		private $_port;
		private $_charset;

		// 还一个表示该对象实例的静态属性
		private static $_instance;
		// 我们还需要定义一个表示 php 和 mysql的连接, 他完成对数据库的各种操作
		private $_mySQLi;


		//构造方法.

		private function __construct($arr){
			
			//1. 先初始化我们的成员属性
//			echo '__construct<pre> ';
//			var_dump($arr);
			//$arr====>成员属性
			$this->_host = isset($arr['host'])?$arr['host'] : '';
			$this->_user = isset($arr['user'])?$arr['user'] : '';
			$this->_pwd = isset($arr['pwd'])?$arr['pwd'] : '';
			$this->_database = isset($arr['database'])?$arr['database'] : '';
			$this->_port = isset($arr['port'])?$arr['port'] : '';
			$this->_charset = isset($arr['charset'])?$arr['charset'] : '';

			//对数据进行校验
			if($this->_host == '' || $this->_user == '' || $this->_pwd == '' || $this->_database == '' || $this->_port == '' || $this->_charset==''){
				echo '<br> 你输入的连接数据库的信息有误';
				exit;
			}

			//初识化我们的 $_mySQLi 属性
			$this->_mySQLi = new MySQLi($this->_host, $this->_user, $this->_pwd, $this->_database, $this->_port);
			$this->_mySQLi->set_charset($this->_charset);
			//判断是否连接成功.
			if($this->_mySQLi->connect_errno){
				
				die('连接失败,错误信息' . $this->_mySQLi->connect_error);
			}

			//测试
			//echo '<pre>';
			//var_dump($this->_mySQLi);
			
		}

		//得到一个对象实例
		public static function getSingleton($arr){
			
			

			if(!self::$_instance instanceof self){
				//创建一个对象 
				self::$_instance = new self($arr);
			}
			//返回对象实例
			return self::$_instance;
			
		}

		//禁止使用克隆
		private function __clone(){}

		//提供一个方法，可以执行查询语句sql...
		public function fetchAll($sql){
			
			//定义一个返回的数组.
			$arr = array();

			if($res = $this->_mySQLi->query($sql)){
				
				//1. 将$res 的记录取出
				//2. 封装到数组中
				while($row = $res->fetch_assoc()){
					$arr[] = $row;
				}
				//3. 是否结果集
				$res->free();
				//4. 返回数组
				return $arr;
			}else{
				
				echo '<br> 执行错误 sql = ' . $sql;
				echo '<br> 错误的信息 ' . $this->_mySQLi->error;
				exit;
			}
			
		}
		public function fetchOne($sql){
			
			if($res = $this->_mySQLi->query($sql)){
				$row = $res->fetch_assoc();
				$res->free();
			}else{
				echo '<br> sql 执行失败';
				die('错误信息' . $this->_mySQLi->error);
			}
			return $row;
		}


		//提供一个执行dml操作的函数
		public function query($sql){
			
			if($this->_mySQLi->query($sql)){
				
				return true;
			}else{
				
				echo '<br> 执行错误 sql = ' . $sql;
				echo '<br> 错误的信息 ' . $this->_mySQLi->error;
				exit;

			}
		}

		//


	}
