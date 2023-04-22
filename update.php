<?php
// Include cakeDAO file
require_once('./dao/cakeDAO.php');
 
// Define variables and initialize with empty values
$name = $description = $price = $order_date = $image = "";
$name_err = $description_err = $price_err = $order_date_err = $image_err = "";
$cakeDAO = new cakeDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value   
     $id = $_POST["id"];
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
    } elseif(!ctype_digit($input_price)){
        $price_err = "Please enter a positive integer value.";
    } else{
        $price = $input_price;
    }
    
    // Validate order date
    $input_order_date = trim($_POST["order_date"]);
    if(empty($input_order_date)){
        $order_date_err = "Please enter the order date.";
    } else{
        $order_date = $input_order_date;
    }
    
    // Check if image has been uploaded
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        // Validate uploaded image
        $allowed_exts = array("jpg", "jpeg", "png", "gif");
        $temp = explode(".", $_FILES["image"]["name"]);
        $extension = end($temp);

        if(($_FILES["image"]["type"] == "image/png" || $_FILES["image"]["type"] == "image/jpg" || $_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/gif") && ($_FILES["image"]["size"] < 2000000) && in_array($extension, $allowed_exts)){
            // Generate unique filename and move uploaded image to directory
            $filename = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "./images/" . $filename);

            $image = $filename;
        } else{
            $image_err = "Please upload a valid image file (2MB max) in one of the following formats: JPG, JPEG, PNG, GIF.";
        }
    }


    



   // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err) && empty($price_err) && empty($order_date_err) && empty($image_err)){
        $cake = new Cake($id, $name, $description, $price, $order_date, $image);
        $result = $cakeDAO->updateCake($cake);        
        header("refresh:2; url=index.php");
        echo '<br><h6 style="text-align:center">' . $result . '</h6>';
        // Close connection
        $cakeDAO->getMysqli()->close();
    }

} else{
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
}
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Cake Record</title>
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
                    <h2 class="mt-5">Update Cake Record</h2>
                    <p>Please edit the input values and submit to update the cake record.</p>
                    
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                       <div class="form-group">
        <label>Order Date</label>
        <input type="date" name="order_date" class="form-control <?php echo (!empty($order_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $order_date; ?>">
        <span class="invalid-feedback"><?php echo $order_date_err;?></span>
    </div>
    <div class="form-group">
        <label>Current Image</label>
        <?php if ($image) { ?>
            <br><img src="./images/<?php echo $image; ?>" height="200">
        <?php } else { ?>
            <br><p>No image available</p>
        <?php } ?>
    </div>
    <div class="form-group">
        <label>New Image (optional)</label>
        <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $image_err;?></span>
    </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
