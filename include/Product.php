<?php 
	declare(strict_types = 1);

	Class Product{
		private int $pId;
		private string $pName;
		private string $pPrice;
		private string $pImg;

		//Constructor
		public function __construct(int $id, string $name, string $price, string $img){
			$this->pId = $id;
			$this->pName = $name;
			$this->pPrice = $price;
			$this->pImg = $img;
		}

		//getters
		public function getId(){
			return $this->pId;
		}

		public function getName(){
			return $this->pName;
		}

		public function getPrice(){
			return $this->pPrice;
		}
		
		public function getImg(){
			return $this->pImg;
		}

	}
	