<?php
include_once("./header.php");
?>

<div class="btn-group"> 
    <!--More button-->
    <form action="search.php" method="POST">
    <input type="hidden" name="some name" value="same value">
    <button style="float:left" class="btn" type="submit"><i class="icon-search"></i> More</button>
    </form>
    <!--Edit button-->
    <form action="edit_product.php" method="post">
    <button class="btn btn-primary" type="submit"  name="product_number" value="some value"><i class="icon-cog icon-white"></i> Edit</button>
     </form>
     <!--delete button-->
     <button data-toggle="modal" href="#error_delete" class="btn btn-danger"><i class="icon-trash icon-white"></i> Delete</button>
</div>

<?php
include_once("footer.php");
?>
