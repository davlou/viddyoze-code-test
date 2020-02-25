<?php

	class AcmeBasket {

		protected $cataloguePrice;
		protected $deliveryRule;
		protected $offers;
		protected $products;
		protected $deliveryCalculator;
		protected $offerCalculator;

		public function __construct($cataloguePrice, $deliveryRule, $offers, $deliveryCalculator, $offerCalculator) {
			$this->cataloguePrice = $cataloguePrice;
			$this->deliveryRule = $deliveryRule;
			$this->offers = $offers;
			$this->deliveryCalculator = $deliveryCalculator;
			$this->offerCalculator = $offerCalculator;
		}

		public function addProduct($product_code) {
			$this->products[] = $product_code;
		}

		public function getProducts() {
			return $this->products;
		}

		public function emptyBasket() {
			$this->products = array();
		}

		private function totalProductPrice() {
			$total = 0;
			foreach ($this->products as $product) {
				$total += $this->cataloguePrice[$product];
			}
			return $total;
		}

		private function totalDiscountPrice($cataloguePrice, $products, $offers) {
			return $this->offerCalculator->totalDiscount($cataloguePrice, $products, $offers);
		}

		private function totalDeliveryPrice($totalProductPrice) {
			return $this->deliveryCalculator->totalDelivery($totalProductPrice);			
		}

		public function totalPrice() {
			$totalProductPrice = $this->totalProductPrice();
			$totalProductPriceWithDiscount = $this->totalProductPrice() - $this->totalDiscountPrice($this->cataloguePrice, $this->products, $this->offers);		
			return floor( ($totalProductPriceWithDiscount + $this->totalDeliveryPrice($totalProductPriceWithDiscount)) * 100) /100;
		}

	}

	class OfferCalculator {

		private static $instance = null;
		private $calculator;

		private function __construct() {

		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new OfferCalculator();
			}
			return self::$instance;
		}

		public function totalDiscount($cataloguePrice, $products, $offers) {
			$totalDiscount = 0;
			
			foreach ($offers as $offer) {
				switch ($offer) {
					case '1RED1HALF':
						$productCode = 'R01';
						$groupCount = 2;
						$discount = 0.5;
						$totalRed = in_array($productCode, $products) ? array_count_values($products)[$productCode] : 0;
						$redPrice = $cataloguePrice[$productCode];

						$groups = floor($totalRed / $groupCount);

						$totalDiscount += $redPrice * $discount * $groups;

						break;
					
					default:
						break;
				}
			}

			return $totalDiscount;

		}

	}

	class DeliveryCalculator {

		private static $instance = null;
		private $calculator;

		private function __construct() {

		}

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new DeliveryCalculator();
			}
			return self::$instance;
		}

		public function totalDelivery($totalProductPrice) {
			$totalDelivery = 0;

			if ($totalProductPrice < 50 ) {
				$totalDelivery = 4.95;
			}
			else if ($totalProductPrice < 90) {
				$totalDelivery = 2.95;
			}

			return $totalDelivery;
		}

	}

	$catalogue = array();
	$catalogue['R01'] = array('product' => 'Red Widget', 'price' => '32.95');
	$catalogue['G01'] = array('product' => 'Green Widget', 'price' => '24.95');
	$catalogue['B01'] = array('product' => 'Blue Widget', 'price' => '7.95');

	$cataloguePrice = array();
	foreach ($catalogue as $key => $value) {
		$cataloguePrice[$key] = $catalogue[$key]['price'];
	}

	$deliveryRule = array();
	$deliveryRule[50] = 4.95;
	$deliveryRule[90] = 2.95;

	$offers = array();
	$offers[] = '1RED1HALF';

	$offerCalculator = OfferCalculator::getInstance(); 
	$deliveryCalculator = DeliveryCalculator::getInstance();

	$basket = new AcmeBasket($cataloguePrice, $deliveryRule, $offers, $deliveryCalculator, $offerCalculator);
	$basket->addProduct('B01');
	$basket->addProduct('G01');
	echo 'Products: ' . implode(', ', $basket->getProducts()) . ' Total basket 1: ' . $basket->totalPrice() . '<br />';
	$basket->emptyBasket();

	$basket->addProduct('R01');
	$basket->addProduct('R01');
	echo 'Products: ' . implode(', ', $basket->getProducts()) . ' Total basket 2: ' . $basket->totalPrice() . '<br />';
	$basket->emptyBasket();

	$basket->addProduct('R01');
	$basket->addProduct('G01');
	echo 'Products: ' . implode(', ', $basket->getProducts()) . ' Total basket 3: ' . $basket->totalPrice() . '<br />';
	$basket->emptyBasket();

	$basket->addProduct('B01');
	$basket->addProduct('B01');
	$basket->addProduct('R01');
	$basket->addProduct('R01');
	$basket->addProduct('R01');
	echo 'Products: ' . implode(', ', $basket->getProducts()) . ' Total basket 4: ' . $basket->totalPrice() . '<br />';
	$basket->emptyBasket();

?>
