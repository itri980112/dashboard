<?php


$DB_LIKES_COUNT = "doc.likes.summary.total_count";
$DB_SHARES_COUNT = "doc.shares.count";
$DB_COMMENTS_COUNT = "doc.comments.summary.total_count";
$DB_POST_TIME = "post_time";
$DB_LAST_UPDATED = 'last_updated';


function connect(){
    $host="220.132.97.119";
    $port="37017";
    $database="fb_rawdata";
    $username="mongoadmin";
    $password="db4536";
    $table="posts";
    $c="mongodb://$username:$password@$host:$port";
    $m = new MongoClient($c);
    $db = $m->$database;
    $collection = $db->$table;
    return $collection;
};

function filter($d){
    if ($d['share']>0){
        $q = array('$and' => array(
            array($DB_LIKES_COUNT => array( '$gt' => 5, '$lt' => 20 )),
            array("allSizes" => "small")
        ))

    }
}

$col=connect();
$array = [
    "comments" => 10,
    "shares" => 10,
    "likes" => 10,
    "total" => 10,
    ];
$q = array($DB_LIKES_COUNT => array( '$gt' => 5, '$lt' => 20 ));

$cursor = $col->find($q);
foreach ($cursor as $doc) {
    var_dump($doc);
}
?>
