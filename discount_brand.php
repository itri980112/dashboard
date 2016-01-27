<?php
include_once("./header.php");
?>


<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="./assets/Chart.min.js"></script>
<br>
<div class="container">
    <div id="chart">
        <canvas id="myChart" width="1000" height="400"></canvas>
    </div>
    <div id="table">

    </div>
<script type="text/javascript">
var colors = ["79,197,199","250,110,134","244,153,48","151,236,113","222,157,214","219,249,119","244,217,91"];
function store_checked_list() {
    var store_arr = [];
    $(".store_checkbox").each(function() {
        if ($(this).prop("checked") == true) {
            store_arr.push($(this).attr("store_name"));
        }
    });
    chart_data_update(store_arr);
};

function store_checkbox_change() {
    $(".store_checkbox").change(function() {
        store_checked_list();
    });
};
function create_discount_table(){
    var table = '<table class="table table-striped" id="discount_table">\n    ';
    table += '<tr>\n    <th>品牌</th>\n     <th>折價商品數</th>\n    <th>全部商品數</th>\n    <th>折價分佈圖表</th>\n';
    table += "";
    for (var store_key in raw['data']) {
        if (raw['data'].hasOwnProperty(store_key)) {
            table += '<tr>\n';
            table += '    <td>' + store_key + "</td>\n";
            table += '    <td>' + raw['data'][store_key]['discount_product'] + "</td>\n";
            table += '    <td>' + raw['data'][store_key]['total_product'] + "</td>\n";
            table += '    <td> <input type="checkbox" class="store_checkbox" store_name="'+ store_key +'" id="' + store_key.replace(" ","_") + '"></td>\n';
            table += '</tr>\n';
        }
    }
    table += '</table>';
    $("#table").append(table);
};

function chart_data_update(store_arr){
    var data = {labels: raw['discount_ch']};
    var datasets = [];
    for(var store in store_arr){
        var store_name = store_arr[store];
        var dataset = {
            label: store_name,
                fillColor: "rgba(" + colors[store%colors.length] + ",0.2)",
                strokeColor: "rgba(" + colors[store%colors.length] + ",1)",
                pointColor: "rgba(" + colors[store%colors.length] + ",1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(" + colors[store%colors.length] + ",1)",
                data: raw['data'][store_name]['discount'],
                labelColor: 'black',
                labelFontSize: '16'
        }
        datasets.push(dataset);
    };
    data['datasets'] = datasets;
    draw_chart(data);
};
function draw_chart(data){
    $("#myChart").remove();
    $("#chart").append('<canvas id="myChart" width="1000" height="400"></canvas>');
    var options = {
        bezierCurve: false,
            multiTooltipTemplate: "<%= datasetLabel %> <%= value %>"
    };
    var ctx = document.getElementById("myChart").getContext("2d");
    var myLineChart = new Chart(ctx).Line(data, options);
};
$.get("http://220.132.97.119/dashboard/discount_json.php", function(response) {
    raw = $.parseJSON(response);
    create_discount_table();
    store_checkbox_change();
    console.log(raw);
    for(var star in raw['star']){
        console.log(raw['star'][star][1].replace(" ","_"));
        $("input[store_name='"+ raw['star'][star][1].replace(" ","_") + "']").prop('checked', true);
    }
    store_checked_list();
})
</script>


<?php
include_once("footer.php");
?>

