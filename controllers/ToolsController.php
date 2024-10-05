<?php
/**
 * ToolsController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/library/Ext/Controller/Action.php');
/**
 * ToolsControllerClass short discription
 *
 * long discription
 *
 */
class ToolsController extends Ext_Controller_Action
{
	protected $_test = 'test';

	/**
	 *
	 **/
	public function listAction() {
			$execFile = dirname(__DIR__). '/tools/keepa_goods_search.py';
//			$execCmd = sprintf("python3 %s %s", $execFile, $idNumber);
			$execCmd = sprintf("python3 %s", $execFile);
			echo shell_exec($execCmd);


echo <<< EOD
<script>
const rp = require('request-promise');

const key = '5ug2rp1e0pmbhm8a6mlrd8eu34rh14a49559jd4ts0032c74dsnt4rts5n0erjmr';

const queryJSON = {
    "perPage": 100, // 1ページあたりの結果数
    "page": 0, // ページ番号
    "productType": 0, // 製品タイプ
    "rootCategory": "2127209051", // ルートカテゴリID
    "title": "ノートパソコン -Apple", // タイトルキーワード
    "avg90_NEW_gte": 30000, // 直近90日の新品価格の平均が30,000円以上
    "current_SALES_gte": 1000, // 現在のランキングが1,000位以上
    "current_SALES_lte": 20000, // 現在のランキングが20,000位以下
    "current_LISTPRICE_gte": 30000, // リスト価格が30,000円以上
    "isPrimeExclusive": false // Prime限定ではない商品も含む
};

const options = {
    url: 'https://api.keepa.com/query?domain=5&key=' + key,
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Connection': 'keep-alive'
    },
    body: {
        'selection': JSON.stringify(queryJSON)
    },
    json: true
};
alert(options);
rp(options)
    .then(response => {
        const asinList = response.asinList; // 条件に合ったASINの配列
        console.log('この条件にマッチするASINの合計数: ' + response.totalResults);
        console.log('ASINリスト: ', asinList);
    })
    .catch(err => {
        console.error('エラーが発生しました: ', err);
    });

</script>
EOD;
	}

	/**
	 *
	 **/
	public function detailAction() {
		echo $this->get_blade()->run("customer-detail");
	}
}

function vd($obj = null) {
	echo '<pre>'; var_dump($obj); echo '</pre>'; 
}

function js_typing($atts){
	//return "受け取った数字は".$atts[0]."と".$atts[1]."です";
	
	$section = $_GET["section"];
//	print_r($section);
	webhook_to_slack('start section : '. $section);
	
	global $wpdb;
	//$no = $atts[0];
	$no = $section;
	$sql = "select * from hack_duo where section = ". $no. ";";
	$rows = $wpdb->get_results($sql);
//	var_dump(current($rows));
//	print_r(current($rows)->sentence);

	//$html = "<div>". current($rows)->sentence. "</div>";
	foreach ($rows as $i => $row) {
		$str = str_replace("’", "_", $row->sentence);
		$str = str_replace("'", "_", $str);
		$str = str_replace("/", "_", $str);
		$str = str_replace("‘", "_", $str);
		$str = preg_replace("/“/", " ", $str);
		$str = preg_replace("/”/", " ", $str);
		$row->sentence = $str;
	}

	foreach ($rows as $i => $row) {
		echo '<p class="sentence" id="'. $row->id. '" style="display : none;">'. $row->sentence. '</p>';
	}
	
	/** 
	 * dictionaryの作成
	 **/
	$sql_dict = "select word, japanese from hack_duo_dict;";
	$dict = $wpdb->get_results($sql_dict);
	foreach ($dict as $i => $d) {
		$dc[$d->word] = $d->japanese;
	}
//print_r($dc);

	foreach ($rows as $i => $row) {
		$sens = explode(' ', $row->sentence);
		foreach ($sens as $j => $word) {
			if (empty($dc[$word])) { continue; }
			$ex[$row->id][] = $word. ':'. $dc[$word];
		}

		if ($row->id == '543' || $row->id == '106') { // TODO: これだけsysteがdownする
			//if ($i == 12) { print_r(array($i, $row->id, vd($ex)));exit; }
			$dic = null;
		} else {
			$dic = (!empty($ex)) ? implode(';', $ex[$row->id]) : null;
		}

		$means = json_decode($row->keyword1);
		foreach ($means as $en => $jp) {
			$ex2[$row->id][] = $en. ': '. $jp;
		}

		$dic = null;
		if ($ex2[$row->id]) {
			$dic2 = (!empty($ex2)) ? implode(';', $ex2[$row->id]) : null;
			$dic = $dic2;
		}

		echo '<p class="dictionary" id="'. $row->id. '" style="display : none;">'. $dic. '</p>';
	}

echo <<< EOD
<script>
	var test = document.getElementsByClassName("sentence");
	//console.log(test[0].innerText);
	
	var dict = document.getElementsByClassName("dictionary");
	//console.log(dict[0].innerText);

	// 問題文
	let Q = [];
	let ids = [];
	let dic = [];
	// objectのforeach
	Object.keys(test).forEach(function (key) {
//			console.log(test[key].id);
		Q.push(test[key].innerText);
		ids.push(test[key].id);
		dic.push(dict[key].innerText);
	});

	//let Q = [test[0].innerText, test[1].innerText, test[2].innerText, test[3].innerText, test[4].innerText, test[5].innerText];
//		"I_ll stand up for what I believe in and won_t yield to any threats."];

	// 問題をランダムで出題する
	//let Q_No = Math.floor( Math.random() * Q.length);
	let Q_No = 0;

	// 回答初期値・現在単語どこまで合っているか判定している文字番号
	let Q_i = 0;

	// 計算用の文字の長さ
	let Q_l = Q[Q_No].length;


window.addEventListener("keydown", push_Keydown);

function push_Keydown(event) {
	let keyCode = event.key;
	
	console.log(ids[Q_No]);
	if (ids[Q_No] === undefined) {
//		window.location.href = '/webhook-keepa-to-slack/';
		location.reload();

	}

	document.getElementById("number").innerHTML = ids[Q_No];
	//document.getElementById("sens_output").innerHTML = Q[Q_No];
	//console.log(dic[Q_No].replaceAll(';', '<br>'));
//	document.getElementById("dict_output").innerHTML = dic[Q_No].replaceAll(';', '<br>');
	document.getElementById("sidebar").innerHTML = dic[Q_No].replaceAll(';', '<br>');
	document.getElementById("footer").innerHTML = dic[Q_No].replaceAll(';', '<span style="color: red;"> | </span>');
	document.getElementById("img").src = "http://hack-note.com/wp-content/uploads/duo_image/" + ids[Q_No].padStart(2,"0") + ".png";
	
	if (Q_l == Q_l-Q_i) {
		// 問題を書き出す
		document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);
	}


	// 押したキーが合っていたら
	if (Q[Q_No].charAt(Q_i) == keyCode) {
		// 判定する文章に１足す
		Q_i++;

		// 問題を書き出す
		document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);

		// 全部正解したら
		if (Q_l-Q_i === 0) {
			// 問題をランダムで出題する
			console.log("Q_No : " + Q_No);
			Q_No += 1;
			console.log("Q_No : " + Q_No);
			//Q_No = Math.floor( Math.random() * Q.length);

			// 回答初期値・現在どこまで合っているか判定している文字番号
			Q_i = 0;

			// 計算用の文字の長さ
			Q_l = Q[Q_No].length;

			// 新たな問題を書き出す
			document.getElementById("start").innerHTML = Q[Q_No].substring(Q_i, Q_l);
		}
	}
}

</script>
<style>
	#dict_output::after{
		content:"\A";
		white-space:pre;
	}

	#header-in, #menu-menu-1{
		display: none;
	}

	#wrap_img {
#		width: 700px;
		float: left;
		margin-right: 40px;
	}

	#img {
		width: 800px;
		height: 350px;
		object-fit: cover;
		object-position: right top;
	}

	#dict_output {
		margin-left: 10px;
		padding: 20px;
		background: black;
		font-weight: bold;
		color: white;
#		height: 100px;
	}

	#sidebar {
		margin-left: 10px;
		padding: 20px;
		background: black;
		font-weight: bold;
		color: white;
#		height: 100px;
	}

	.clear {
		clear: both;
	}

	#sens_output {
		font-size: 1px;
		color: blue;
	}

	#start {
		font-size: 30px;
		#font-family: serif;
		font-family: "Gill Sans", sans-serif;
		#font-family: system-ui;
		font-weight: bold;
		color: blue;
	}
</style>
EOD;

	$html .= '<div style="height: 350px">';
	$html .= '	<h4 id="number"></h4>';
	$html .= '	<div>';
	$html .= '		<div id="wrap_img"><img src="http://hack-note.com/wp-content/uploads/2024/05/146.png" id="img" /></div>';
//	$html .= '		<div id="dict_output"></div>';
	$html .= '	</div>';
	$html .= '	<p id="sens_output" class="clear"></p>';
//	$html .= '	<br />';
//	$html .= '	<center>';
	$html .= '		<p id="start" class="text" style="color: blue;">何かキーを押して下さい</p>';
//	$html .= '	</center>';
	$html .= '</div>';
	$html .= '<br><br>';

	return $html;
}
add_shortcode('jstype','js_typing');
?>
