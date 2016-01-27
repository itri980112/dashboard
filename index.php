<?php
include_once("./header.php");
include_once('./commonlib.php');
$conn=getConnection('ibabymall');
$prodCNT=getTotalProducts($conn);
$prodlist=getBrandList($conn);
$brandCNT=getTotalBrands($conn);
$storeCNT=getTotalStores($conn);
$conn2=getConnection('jared');
$newsCNT=getTotalNews($conn2);
?>
<!--
<div class="alert alert-danger">正在進行資料庫更新，請稍待片刻...</div>
-->
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-4x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $prodCNT; ?></div>
                            <div>產品數量</div>
                        </div>
                    </div>
                </div>
                <a href="">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $storeCNT; ?></div>
                            <div>商店數量</div>
                        </div>
                    </div>
                </div>
                <a href="./store_list.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-bar-chart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"> <?php echo $brandCNT; ?>  </div>
                            <div>品牌數量</div>
                        </div>
                    </div>
                </div>
                <a href="./brand_list.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $newsCNT; ?></div>
                            <div>新聞數量</div>
                        </div>
                    </div>
                </div>
                <a href="./news.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    系統資料更新狀態
                </div>
                <div class="panel-body">
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
                        <div class="col-md-12 wall">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-block" href="./wall.php">Show More</a>
                        </div>
                    </div>

                    <script>
                    function add(obj){
                        content = $('.wall.template').html();
                        content = content.replace(/%title%/g, obj['title']);
                        content = content.replace(/%style%/g, obj['style']);
                        content = content.replace(/%description%/g, obj['description']);
                        content = content.replace(/%created_at%/g, obj['created_at']);
                        $(".wall:not(.template)").append(content);
                    }
                    var from=0;
                    var count=5;
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
                                $.each(msg, function(index, element){
                                    add(element);
                                });
                            }
                        });
                    }
                    load_wall();

                    </script>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>國家分布</h3>
                </div>
                <div class="panel-body">
                    <div id="countrychart" align="center" style="width: 100%; min-height: 600px"> </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 hidden">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>品牌列表</h3>
                </div>
                <div class="panel-body">
                    <div id="chart" align="center"> </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./assets/d3.min.js"></script>
<script>

var diameter = 960,
    format = d3.format(",d"),
    color = d3.scale.category20c();

var bubble = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .padding(1.5);

var svg = d3.select("#chart").append("svg")
    .attr("width", diameter)
    .attr("height", diameter)
    .attr("class", "bubble");

d3.json("./assets/bubble.json", function(error, root) {
    if (error) throw error;

    var node = svg.selectAll(".node")
        .data(bubble.nodes(classes(root))
        .filter(function(d) { return !d.children; }))
        .enter().append("g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

    node.append("title")
        .text(function(d) { return d.className + ": " + format(d.value); });
    node.append("circle")
        .attr("r", function(d) { return d.r; })
        .style("fill", function(d) { return color(d.packageName); })
        .on("click", function(d) {
            //      alert("on click" + d.value);
            self.location.href='http://220.132.97.119/dashboard/brand.php?id='+d.bid;
            //		

        });


    node.append("text")
        .attr("dy", ".3em")
        .style("text-anchor", "middle")
        .text(function(d) { return d.className.substring(0, d.r / 3); });



});

// Returns a flattened hierarchy containing all leaf nodes under the root.
function classes(root) {
    var classes = [];

    function recurse(name, node) {
        if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
        else classes.push({packageName: name, className: node.name, value: node.size,bid:node.bid});
    }

    recurse(null, root);
    return {children: classes};
}

d3.select(self.frameElement).style("height", diameter + "px");
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>
<script src="./assets/datamaps.world.min.js"></script>
<script>
$.get( "http://220.132.97.119/dashboard/index_worldmap_json.php", function( data ) {
    bubbles = [];
    var obj = JSON.parse(data);
    for (var c in obj) {
        if (obj.hasOwnProperty(c)) {
            var bubble = {};
            bubble['name'] = obj[c]['countryname'];
            bubble['product_number'] = obj[c]['cnt'];
            bubble['radius'] = Math.sqrt(parseInt(obj[c]['cnt']/10));
            bubble['country'] = obj[c]['ISOName'];
            bubble['fillKey'] = 'bubble';
            bubble['longitude'] = obj[c]['longitude'];
            bubble['latitude'] = obj[c]['latitude'];
            bubbles.push(bubble);
        }
    };
    var bubble_map = new Datamap({
    element: document.getElementById("countrychart"),
        geographyConfig: {
            popupOnHover: false,
            highlightOnHover: true
        },
        fills: {
            defaultFill: '#9FE0F6',
            havedata: '#0000CC',
            bubble: '#F29C9C'
        },
        data: {
//            DEU: {
//                fillKey: 'havedata'
//            },
//            FRA: {
//                fillKey: 'havedata'
//            }
        }
});
bubble_map.bubbles(
    bubbles, {
    popupTemplate: function(geo, data) {
        return '<div class="hoverinfo">'+data.name+' '+data.product_number
    }
});

});
</script>

<?php
include_once("footer.php");
?>
