<?php
    class Cake{        

        private $id;
        private $name;
        private $description;
        private $price;
        private $order_date;
        private $image;
                
        function __construct($id, $name, $description, $price, $order_date, $image){
            $this->setId($id);
            $this->setName($name);
            $this->setDescription($description);
            $this->setPrice($price);
            $this->setOrderDate($order_date);
            $this->setimage($image);
        }       
        
        public function getName(){
            return $this->name;
        }
        
        public function setName($name){
            $this->name = $name;
        }
        
        public function getDescription(){
            return $this->description;
        }
        
        public function setDescription($description){
            $this->description = $description;
        }

        public function getPrice(){
            return $this->price;
        }

        public function setPrice($price){
            $this->price = $price;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getId(){
            return $this->id;
        }

        public function setOrderDate($order_date){
        $this->order_date = $order_date;
        }
        public function getOrderDate(){
        return $this->order_date;
        }
        public function setImage($image){
        $this->image = $image;
        }
        public function getImage(){
            return $this->image;
        }

    }
?>

