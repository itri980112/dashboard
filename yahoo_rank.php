<?php
include_once("./header.php");
?>
<link rel="stylesheet" href="http://css-spinners.com/css/spinner/dots.css" type="text/css">
<div class="container">

    <h2>Yahoo 購物商城排行</h2>
<button type="button" class="btn btn-success tab" id="data1">孕婦裝</button>
<button type="button" class="btn btn-success tab" id="data2">0-2 Baby</button>
<button type="button" class="btn btn-success tab" id="data3">2-8 女</button>
<button type="button" class="btn btn-success tab" id="data4">2-8 男</button>
<button type="button" class="btn btn-success tab" id="data5">髮飾</button>
<button type="button" class="btn btn-success tab" id="data6">童鞋</button>
<button type="button" class="btn btn-success tab" id="data7">包包</button>
<button type="button" class="btn btn-success tab" id="data8">童帽</button>

<script>
d={};
$(".tab").click(function(){
    $("#tbody tr").remove();
    createTable(d[$(this).attr("id")]);
})
</script>
    <table class="table table-striped">
    <thead>
    <tr>
    <th>#</td>
    <th>品名</th>
    <th>商店</th>
    <th>縮圖</th>
    <th>價格</th>
    </tr>
    </thead>
    <tbody id="tbody">
    <div id="loading" style="position: fixed;top: 50%;left: 55%;"  class="text-center dots-loader">
        Loading…
    </div>
    </tbody>
    </table>
    </div>
<script>
function createTable(data){
    items = data['miditemlist'];
    table = "";
    for(var item in items){
        i = items[item];
        var t ="<tr>"
            t += "<td>" + (parseInt(item)+1) + "</td>";
        t += "<td> <a href=\""+ i['link'] +"\" </a>"+ i['title'] +"</td>";
        t += "<td> <a href=\""+ i['store_link'] +"\" </a>"+ i['store'] +"</td>";
        t += "<td> <img src=\""+ i['imgsrc'] +"\" </td>";
        t += "<td>"+ i['price'] +"</td>";
        t += "</tr>";
        table += t;
    }
    $("#tbody").append(table);
}
$.getJSON( "./yahoo_rank_data.php?id=152982780&d=3", function( data ) {
    $("#loading").hide();
    createTable(data);
    d["data1"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982890&d=4", function( data ) {
    d["data2"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982790&d=3", function( data ) {
    d["data3"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982817&d=3", function( data ) {
    d["data4"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982847&d=2", function( data ) {
    d["data5"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982836&d=2", function( data ) {
    d["data6"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152982845&d=2", function( data ) {
    d["data7"]=data;
});
$.getJSON( "./yahoo_rank_data.php?id=152995953&d=2", function( data ) {
    d["data8"]=data;
});
</script>
<?php
include_once("footer.php");
?>
