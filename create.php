<?php
// Include cakeDAO file
require_once('./dao/cakeDAO.php');

// Define variables and initialize with empty values
$name = $description = $price = $order_date = $image = "";
$name_err = $description_err = $price_err = $order_date_err = $image_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        if (empty($errors) == true && isset($_FILES['image'])) {
            move_uploaded_file($file_tmp, "images/" . $_FILES['image']['name']);
            $image = $file_name;
            echo "File Successfully uploaded";
        }
    }

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter a description.";     
    } else{
        $description = $input_description;
    }
    
    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price amount.";     
    } elseif(!is_numeric($input_price)){
        $price_err = "Please enter a numeric value.";
    } else{
        $price = $input_price;
    }
    
    // Validate order date
    $input_order_date = trim($_POST["order_date"]);
    if(empty($input_order_date)){
        $order_date_err = "Please enter an order date.";     
    } else{
        $order_date = $input_order_date;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err) && empty($price_err) && empty($order_date_err)){
        $cakeDAO = new cakeDAO();    

        $cake = new Cake(0, $name, $description, $price, $order_date, $image);
        $addResult = $cakeDAO->addCake($cake);
        
        header("refresh:2; url=index.php"); 
        echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        
        // Close connection
        $cakeDAO->getMysqli()->close();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Cake</title>
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
                    <h2 class="mt-5">Create Cake</h2>
                    <p>Please fill this form and submit to add a cake record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Order Date</label>
                            <input type="date" name="order_date" class="form-control <?php echo (!empty($order_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_date; ?>">
                            <span class="invalid-feedback"><?php echo $order_date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control-file <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $image_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
            
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>
