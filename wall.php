<?php
include_once("./header.php");
include_once('./commonlib.php');
?>
<div class="row wall template hidden">
    <div class="panel panel-%style%">
        <div class="panel-heading">
            %title%
        </div>
        <div class="panel-body">
            %description%
        </div>
        <div class="panel-footer">
            %created_at%
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-offset-2 col-md-8 wall">
    </div>
</div>
<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <button class="btn btn-success btn-block" onclick="load_wall();">Show More</button>
    </div>
</div>

<script>
function add(obj){
    content = $('.wall.template').html();
    content = content.replace(/%title%/g, obj['title']);
    content = content.replace(/%description%/g, obj['description']);
    content = content.replace(/%created_at%/g, obj['created_at']);
    content = content.replace(/%style%/g, obj['style']);
    $(".wall:not(.template)").append(content);
}
var from=0;
var count=10;
function load_wall(){
    $.ajax({
        url: './ajax_wall.php',
        data: {
            from: from,
            count: count
        },
        dataType: "json",
        type: "get",
        success: function(msg){
            if(msg.length){
                $.each(msg, function(index, element){
                    add(element);
                });
                from+=10;
            } else {
                alert("全部動態皆已顯示");
            }
        }
    });
}
load_wall();

</script>
<?php
include_once("footer.php");
?>
