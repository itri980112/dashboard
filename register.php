<?php
require_once('./header.php');
require_once('./commonlib.php');

$conn=getConnection('ibabymall');
$user_list = user_list($conn);
?>
<form class="form-inline" id="new_account">
    <label class="control-label">新增帳號</label>
    <div class="form-group">
        <label class="control-label">帳號:</label>
        <input class="form-control" name="account" type="text">
    </div>
    <div class="form-group">
        <label class="control-label">密碼:</label>
        <input class="form-control" name="password" type="password">
    </div>
    <button class="btn btn-success">新增</button>
</form>
<script>
$(document).ready(function(){
    $("#new_account").submit(function(){
        form = $(this);
        $.ajax({
            url: 'register_ajax.php',
            type: 'post',
            data: form.serialize(),
            success: function(msg){
                form[0].reset();
                location.reload(false);
            }
        });
        return false;
    });
});
</script>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>帳號</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            for($i = 0 ; $i < sizeof($user_list) ; $i++){
                echo 
                    "<tr>" .
                    "<td>" . $user_list[$i]["id"] . "</td>" .
                    "<td>" . $user_list[$i]["account"] . "</td>" .
                    "</tr>";
            }
        ?>
    </tbody>
</table>

<?php
require_once('./footer.php');
?>
