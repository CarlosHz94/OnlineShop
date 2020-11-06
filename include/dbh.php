<?php

	require_once '../config/config.php';

	class Dbh {
		private $host = DB_HOST;
		private $user = DB_USER;
		private $pwd = DB_PWD;
		private $dbName = DB_NAME;
		private $pdo = null;
		private $stmt = null;

		public function __construct(){
			$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
			$options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
			try{
				$this->pdo = new PDO($dsn, $this->user, $this->pwd, $options);
			}catch(PDOException $e){
				throw new Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		public function execute($query, array $params = []){
			try{
				if($this->pdo !== null){
					if(($this->stmt = $this->pdo->prepare($query)) !== false){
						return $this->stmt->execute($params);
					}
				}
			}catch(PDOException $e){
				throw new Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		public function fetch(){
			return $this->stmt->fetch();
		}

		public function fetchAll(){
			return $this->stmt->fetchAll();
		}
	}