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
	}

	/**
	 *
	 **/
	public function detailAction() {
		echo $this->get_blade()->run("customer-detail");
	}
}

function js_typing($atts){
	//return "受け取った数字は".$atts[0]."と".$atts[1]."です";
	
	$section = $_GET["section"];
//	print_r($section);
	
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
		echo '<p class="sentence" id="'. $row->id. '" style="display : none;">'. $str. '</p>';
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
		$dic = implode(';', $ex[$row->id]);
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
  		//console.log(key);
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
	
	// console.log(ids[Q_No]);
	document.getElementById("number").innerHTML = ids[Q_No];
	//document.getElementById("sens_output").innerHTML = Q[Q_No];
	//console.log(dic[Q_No].replaceAll(';', '<br>'));
	document.getElementById("dict_output").innerHTML = dic[Q_No].replaceAll(';', '&emsp;&emsp;');
	document.getElementById("img").src = "http://hack-note.com/wp-content/uploads/duo_image/" + ids[Q_No] + ".png";
	
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
EOD;

	$html .= '<div style="height: 600px">';
	$html .= '	<h1 id="number"></h1>';
	$html .= '	<img src="http://hack-note.com/wp-content/uploads/2024/05/146.png" id="img" width="400px" />';
	$html .= '	<span id="sens_output" style="font-size: 30px; color: blue;"></span>';
	$html .= '	<br />';
	$html .= '	<span id="dict_output" style="color: blue;"></span>';
	$html .= '	<br /><br />';
	$html .= '	<center>';
	$html .= '		<h1 id="start" class="text" style="color: blue;">何かキーを押して下さい</h1>';
	$html .= '	</center>';
	$html .= '</div>';

	return $html;
}
add_shortcode('jstype','js_typing');
?>
