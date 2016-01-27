<?php
include_once("./header.php");
include_once("./commonlib.php");
$conn=getConnection("ibabymall");
$store_id=$_GET["store_id"];
$store_data=getStoreData($conn, $store_id);
?>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

<script src="./assets/Chart.min.js"></script>
<div class="row">
    <div class="col-md-12"><div>
        <img src="<?php echo $store_data['photo'];?>"><br><br>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h2 class="panel-title">基本資料</h2>
            </div>
            <div class="panel-body">
                <h5>商城：<?php echo $store_data['source'];?></h5>
                <h5>商店：<?php echo "(中文名) " . $store_data['chinese_name'] . " / (英文名) " . $store_data['english_name'];?></h5>
                <h5>商品總數：<?php echo $store_data['count'];?> 項</h5>
                <h5>網址：<a href="<?php echo $store_data['url'];?>"> <?php echo $store_data['url'];?></a></h5>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h2 class="panel-title">價格帶分析</h2>
            </div>
            <div class="panel-body">
                <canvas id="myChart2" width="1000" height="400"></canvas>
                <ul class="pager">
                    <li class="next"><a href="./brand_price.php">價格帶分析總表 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>    
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h2 class="panel-title">折價分析</h2>
            </div>
            <div class="panel-body">
                <canvas id="myChart" width="1000" height="400"></canvas>
                <h2><span class="label label-primary" id="total_product">Loading...</span>
                <span class="label label-success" id="discount_product">Loading...</span>
                <span class="label label-info" id="normal">Loading...</span></h2>
                <ul class="pager">
                    <li class="next"><a href="./discount_brand.php">折價分析總表 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>    
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h2 class="panel-title">商品列表</h2>
            </div>
            <div class="panel-body">
<?php
$ary = getHotProductByStoreId($conn, $_GET["store_id"]);
?>              
            <style>
            .small.photo{
                height: 50px;
                width: 50px;
            }
            </style>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>產品名稱</th>
                            <th>縮圖</th>
                            <th>狀態</th>
                            <th>建議售價</th>
                            <th>售價</th>
                            <th>外部連結</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <?php
                        for($i = 0 ; $i < sizeof($ary) ; $i++){
                            $url = $ary[$i]["url"];
                            $photo = $ary[$i]["photo"];
                            echo
                                "<tr>" .
                                "<td>" . ($i + 1) . "</a></td>" .
                                "<td>" . $ary[$i]["title"] . "</td>" .
                                "<td><img src='$photo' class='small photo'></td>" .
                                "<td>" . $ary[$i]["available"] . "</td>" .
                                "<td>" . $ary[$i]["recommend_price"] . "</td>" .
                                "<td>" . $ary[$i]["current_price"] . "</td>" .
                                "<td><a href='$url' target='_blank'>前往</a></td>" .
                                "</tr>";
                        }
                    ?>  
                    </tbody>
                </table>
                <ul class="pager">
                <li class="next"><a href="./store_personal.php?store_id=<?php echo $store_id;?>"><?php echo $store_data["chinese_name"]; ?>商品總表 <span aria-hidden="true">&rarr;</span></a></li>
                </ul>    
            </div>
        </div>
    </div>
</div>

<script>
$.get("http://220.132.97.119/dashboard/analyze_price_by_store.php?store_id=<?php echo $store_id ?>", function(response){
    raw2 = $.parseJSON(response);
    labels=[];
    data=[];
    for(var key in raw2['range']){
        labels.push(raw2['range'][key]);
        data.push(raw2['data'][raw2['range'][key]]);
    }
    var data = {
            labels: labels,
            datasets: [
            {
                label: "My First dataset",
                    fillColor: "rgba(250,110,134,0.5)",
                    strokeColor: "rgba(250,110,134,0.8)",
                    highlightFill: "rgba(250,110,134,0.75)",
                    highlightStroke: "rgba(250,110,134,1)",
                    data: data
            }
        ]
    };
    var ctx = document.getElementById("myChart2").getContext("2d");
    var myBarChart = new Chart(ctx).Bar(data);
});
$.get("http://220.132.97.119/dashboard/discount_store_json.php?store_id=<?php echo $store_id ?>", function(response) {
    raw = $.parseJSON(response);
    console.log(raw);
    var data = {
        labels: raw['discount_ch'],
            datasets:[
            {
                label: "",
                    fillColor: "rgba(79,197,199,0.2)",
                    strokeColor: "rgba(79,197,199,1)",
                    pointColor: "rgba(79,197,199,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(79,197,199,1)",
                    data: raw['data']['discount'],
                    labelColor: 'black',
                    labelFontSize: '16'
            }
        ]
    };
    var options = {
        bezierCurve: false,
            multiTooltipTemplate: "<%= datasetLabel %> <%= value %>"
    };
    var ctx = document.getElementById("myChart").getContext("2d");
    var myLineChart = new Chart(ctx).Line(data, options);
    $("#total_product").text("商品總數： "+raw['data']['total_product']);
    $("#discount_product").text("折價商品數： "+raw['data']['discount_product']);
    $("#normal").text("原價商品數： "+raw['data']['normal']);

})
    </script>

<?php
include_once("./footer.php");
?>

