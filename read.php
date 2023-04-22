<?php
// Include cakeDAO file
require_once('./dao/cakeDAO.php');
$cakeDAO = new cakeDAO(); 
$image='';

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    $cake = $cakeDAO->getCake($id);
            
    if($cake){
        // Retrieve individual field value
        $name = $cake->getName();
        $description = $cake->getDescription();
        $price = $cake->getPrice();
        $order_date = $cake->getOrderDate();
        $image = $cake->getImage();
    } else{
        // URL doesn't contain valid id. Redirect to error page
        header("location: error.php");
        exit();
    }
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
} 

// Close connection
$cakeDAO->getMysqli()->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Picture</label>
                        <?php
                        $display = '<p class="mt-2"><img src="images/' . $image . '" class="img-thumbnail" alt="' . $image . '" width="300" height="300"></p>';
                        echo $image ? $display : '<p><b>NO image uploaded</b></p>';
                        ?>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $name; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p><b><?php echo $description; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <p><b><?php echo $price; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Order Date</label>
                        <p><b><?php echo $order_date; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
