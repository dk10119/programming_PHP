<?php
    // this is the associative array for our shopping cart 
    $cart = [];
    $errormsg = "";
    // check if the cookie for shopping cart has been set (you can choose the name)
    // if it's been set, overwrite $cart variable with it. The easiest way to switch between array and string is to use JSON.
    // so e.g. $cart = json_decode($_COOKIE["cart"], true);
    if(isset($_COOKIE["cart"])) {
    $cart = json_decode($_COOKIE["cart"], true);
}

// check if the add form has been sent
// if yes, add the new product to the $cart array and send the updated cookie
// let's use JSON again, so e.g. $json = json_encode($cart);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    @$productName = $_POST["productname"];
    @$productAmount = $_POST["productamount"];
    @$productAmount == "" ? $errormsg = "Please choose an amount!" : $errormsg = "";
    for ($i = 0; $i < $productAmount; $i++) {
        $cart[] = $productName;
    }

    $jsonCart = json_encode($cart);
    setcookie("cart", $jsonCart);

    // check if the empty cart button has been pressed (key "emptycart" - the button's name attribute - exists in $_POST array)
    // if yes, empty the $cart array and set a new cookie that's set to expire in the past
    if (@$_POST["emptycart"]) {
        setcookie("cart", "", time() -60);
        $cart = [];
        $errormsg = "";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
</head>
<body>
    <form action="shoppingcart.php" method="post">
        <p>Choose product:</p>
        <!-- <p><label for="productname">Product name:</label><br>
            <select id="productname" name="productname">
                <option value="Book">Book</option>
                <option value="Computer">Computer</option>
                <option value="Phone">Phone</option>
            </select>
        </p> -->
        <p>
            <input type="radio" id="book" name="productname" value="Book" checked ><label for="book">Book</label>
            <input type="radio" id="computer" name="productname" value="Computer" <?php echo (@$productName == "Computer") ? 'checked' : ''; ?>><label for="computer">Computer</label>
            <input type="radio" id="phone" name="productname" value="Phone" <?php echo (@$productName == "Phone") ? 'checked' : ''; ?>><label for="phone">Phone</label>
        </p>
        <p><label for="productamount">Amount:</label><br>
            <input type="number" id="productamount" name="productamount"><br>
        <p style = "color: red; font-size: 0.8rem"><?php echo $errormsg ?></p>
        </p>
        <p><input type="submit" name="addproduct" value="Add to cart"></p>
    </form>

    <h3>Current shopping cart</h3>
    <div id="cart">
    <?php

// print the contents of the cart here (loop through the $cart array's key - value pairs)
// print e.g. 1 x Book
// Here's a fun little function: empty($cart) will tell you if the $cart array is empty
// if you check for emptiness before looping, then you could print e.g. "No items." if there's nothing in the cart
    $items = array_count_values($cart);
    if (empty($cart)) {
        echo 'Cart is empty!';
    } else {
        foreach ($items as $item => $amount) {
            echo "$amount x $item<br>";
        }
    }

    ?>

    <form action="shoppingcart.php" method="post">
     <p><input type="submit" name="emptycart" value="Empty cart"></p>
    </form>
    </div>
</body>
</html>