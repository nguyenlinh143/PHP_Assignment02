<?php require_once('abstractDAO.php');
require_once('./model/cake.php');

class cakeDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }  
    
    public function getCake($cakeId){
        $query = 'SELECT * FROM cakes WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $cakeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $cake = new cake($temp['id'], $temp['name'], $temp['description'], $temp['price'], $temp['order_date'], $temp['image']);
            $result->free();
            return $cake;
        }
        $result->free();
        return false;
    }

    public function getCakes(){
        $result = $this->mysqli->query('SELECT * FROM cakes');
        $cakes = Array();
        
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){
                $cake = new cake($row['id'], $row['name'], $row['description'], $row['price'], $row['order_date'], $row['image']);
                $cakes[] = $cake;
            }
            $result->free();
            return $cakes;
        }
        $result->free();
        return false;
    }   
    
    public function addCake($cake){
        
        if(!$this->mysqli->connect_errno){
            $query = 'INSERT INTO cakes (name, description, price, order_date, image) VALUES (?,?,?,?,?)';
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                $name = $cake->getName();
                $description = $cake->getDescription();
                $price = $cake->getPrice();
                $order_date = $cake->getOrderDate();
                $image = $cake->getImage();
                  
                $stmt->bind_param('ssdss', 
                    $name,
                    $description,
                    $price,
                    $order_date,
                    $image
                );    
                $stmt->execute();         
                    
                if($stmt->error){
                    return $stmt->error;
                } else {
                    return $cake->getName() . ' added successfully!';
                } 
            } else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        } else {
            return 'Could not connect to Database.';
        }
    }  
    public function updateCake($cake){
        
        if(!$this->mysqli->connect_errno){
            $query = "UPDATE cakes SET name=?, price=?, description=?, order_date=?, image=? WHERE id=?";
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                $id = $cake->getId();
                $name = $cake->getName();
                $price = $cake->getPrice();
                $description = $cake->getDescription();
                $order_date = $cake->getOrderDate();
                $image = $cake->getImage();
                  
                $stmt->bind_param('sdsssi', 
                    $name,
                    $price,
                    $description,
                    $order_date,
                    $image,
                    $id
                );    
                $stmt->execute();         
                    
                if($stmt->error){
                    return $stmt->error;
                } else {
                    return $cake->getName() . ' updated successfully!';
                } 
            } else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        } else {
            return 'Could not connect to Database.';
        }
    }    

    public function deleteCake($cakeId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM cakes WHERE id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $cakeId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}

    