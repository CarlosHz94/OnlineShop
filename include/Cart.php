<?php 
	declare(strict_types = 1);

	require_once 'Product.php';

	Class Cart{
		private Product $product;
		private int $qty;

		//Constructor
		public function __construct(Product $product, int $qty){
			$this->product = $product;
			$this->qty = $qty;
		}

		//getters
		public function getProduct(){
			return $this->product;
		}

		public function getQty(){
			return $this->qty;
		}

	}
	