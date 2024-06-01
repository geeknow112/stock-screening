<?php
/**
 * MenuController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/library/Ext/Controller/Action.php');
/**
 * MenuControllerClass short discription
 *
 * long discription
 *
 */
class MenuController extends Ext_Controller_Action
{
	protected $_test = 'test';

	/**
	 *
	 **/
	public function listAction() {

$stocks = array(
	'1332' => 'ニッスイ', 
	'1605' => 'ＩＮＰＥＸ', 
	'1925' => '大和ハウス工業', 
	'1928' => '積水ハウス', 
	'1812' => '鹿島', 
	'1721' => 'コムシスホールディングス', 
	'1803' => '清水建設', 
	'1808' => '長谷工コーポレーション', 
	'1963' => '日揮ホールディングス', 
	'1802' => '大林組', 
	'1801' => '大成建設', 
	'2501' => 'サッポロホールディングス', 
	'2871' => 'ニチレイ', 
	'2801' => 'キッコーマン', 
	'2914' => '日本たばこ産業', 
	'2269' => '明治ホールディングス', 
	'2282' => '日本ハム', 
	'2002' => '日清製粉グループ本社', 
	'2502' => 'アサヒグループホールディングス', 
	'2503' => 'キリンホールディングス', 
	'2802' => '味の素', 
	'3401' => '帝人', 
	'3402' => '東レ', 
	'3863' => '日本製紙', 
	'3861' => '王子ホールディングス', 
	'4063' => '信越化学工業', 
	'4183' => '三井化学', 
	'4004' => 'レゾナック・ホールディングス', 
	'6988' => '日東電工', 
	'4021' => '日産化学', 
	'4452' => '花王', 
	'4631' => 'ＤＩＣ', 
	'4901' => '富士フイルムホールディングス', 
	'3405' => 'クラレ', 
	'4042' => '東ソー', 
	'4061' => 'デンカ', 
	'4043' => 'トクヤマ', 
	'4188' => '三菱ケミカルグループ', 
	'3407' => '旭化成', 
	'4208' => 'ＵＢＥ', 
	'4005' => '住友化学', 
	'4911' => '資生堂', 
	'4578' => '大塚ホールディングス', 
	'4568' => '第一三共', 
	'4523' => 'エーザイ', 
	'4519' => '中外製薬', 
	'4502' => '武田薬品工業', 
	'4151' => '協和キリン', 
	'4507' => '塩野義製薬', 
	'4503' => 'アステラス製薬', 
	'4506' => '住友ファーマ', 
	'5019' => '出光興産', 
	'5020' => 'ＥＮＥＯＳホールディングス', 
	'5101' => '横浜ゴム', 
	'5108' => 'ブリヂストン', 
	'5214' => '日本電気硝子', 
	'5201' => 'ＡＧＣ', 
	'5333' => '日本ガイシ', 
	'5233' => '太平洋セメント', 
	'5332' => 'ＴＯＴＯ', 
	'5301' => '東海カーボン', 
	'5401' => '日本製鉄', 
	'5411' => 'ＪＦＥホールディングス', 
	'5406' => '神戸製鋼所', 
	'5713' => '住友金属鉱山', 
	'5711' => '三菱マテリアル', 
	'3436' => 'ＳＵＭＣＯ', 
	'5802' => '住友電気工業', 
	'5714' => 'ＤＯＷＡホールディングス', 
	'5706' => '三井金属', 
	'5801' => '古河電気工業', 
	'5803' => 'フジクラ', 
	'6273' => 'ＳＭＣ', 
	'6367' => 'ダイキン工業', 
	'7013' => 'ＩＨＩ', 
	'6301' => 'コマツ', 
	'6302' => '住友重機械工業', 
	'5631' => '日本製鋼所', 
	'6305' => '日立建機', 
	'6103' => 'オークマ', 
	'7011' => '三菱重工業', 
	'6113' => 'アマダ', 
	'7004' => '日立造船', 
	'6326' => 'クボタ', 
	'6472' => 'ＮＴＮ', 
	'6471' => '日本精工', 
	'6473' => 'ジェイテクト', 
	'6361' => '荏原', 
	'6861' => 'キーエンス', 
	'6501' => '日立製作所', 
	'6701' => 'ＮＥＣ', 
	'6758' => 'ソニーグループ', 
	'6762' => 'ＴＤＫ', 
	'6526' => 'ソシオネクスト', 
	'6503' => '三菱電機', 
	'6674' => 'ジーエス・ユアサコーポレーション', 
	'7735' => 'ＳＣＲＥＥＮホールディングス', 
	'6479' => 'ミネベアミツミ', 
	'6976' => '太陽誘電', 
	'7751' => 'キヤノン', 
	'6504' => '富士電機', 
	'6981' => '村田製作所', 
	'6724' => 'セイコーエプソン', 
	'6506' => '安川電機', 
	'6752' => 'パナソニックホールディングス', 
	'6841' => '横河電機', 
	'6723' => 'ルネサスエレクトロニクス', 
	'6902' => 'デンソー', 
	'7752' => 'リコー', 
	'6702' => '富士通', 
	'6770' => 'アルプスアルパイン', 
	'6753' => 'シャープ', 
	'6952' => 'カシオ計算機', 
	'6971' => '京セラ', 
	'6594' => 'ニデック', 
	'6857' => 'アドバンテスト', 
	'6954' => 'ファナック', 
	'6645' => 'オムロン', 
	'6920' => 'レーザーテック', 
	'8035' => '東京エレクトロン', 
	'7012' => '川崎重工業', 
	'7203' => 'トヨタ自動車', 
	'7270' => 'ＳＵＢＡＲＵ', 
	'7261' => 'マツダ', 
	'7267' => 'ホンダ', 
	'7272' => 'ヤマハ発動機', 
	'7202' => 'いすゞ自動車', 
	'7269' => 'スズキ', 
	'7201' => '日産自動車', 
	'7211' => '三菱自動車', 
	'7205' => '日野自動車', 
	'7741' => 'ＨＯＹＡ', 
	'6146' => 'ディスコ', 
	'4543' => 'テルモ', 
	'7733' => 'オリンパス', 
	'7731' => 'ニコン', 
	'4902' => 'コニカミノルタ', 
	'7762' => 'シチズン時計', 
	'7912' => '大日本印刷', 
	'7911' => 'ＴＯＰＰＡＮホールディングス', 
	'7951' => 'ヤマハ', 
	'7832' => 'バンダイナムコホールディングス', 
	'8001' => '伊藤忠商事', 
	'8053' => '住友商事', 
	'8031' => '三井物産', 
	'2768' => '双日', 
	'8015' => '豊田通商', 
	'8002' => '丸紅', 
	'8058' => '三菱商事', 
	'9983' => 'ファーストリテイリング', 
	'9843' => 'ニトリホールディングス', 
	'3092' => 'ＺＯＺＯ', 
	'3099' => '三越伊勢丹ホールディングス', 
	'8267' => 'イオン', 
	'8233' => '高島屋', 
	'8252' => '丸井グループ', 
	'3086' => 'Ｊ・フロントリテイリング', 
	'3382' => 'セブン＆アイ・ホールディングス', 
	'8316' => '三井住友フィナンシャルグループ', 
	'8354' => 'ふくおかフィナンシャルグループ', 
	'8309' => '三井住友トラスト・ホールディングス', 
	'8411' => 'みずほフィナンシャルグループ', 
	'5831' => 'しずおかフィナンシャルグループ', 
	'8308' => 'りそなホールディングス', 
	'8331' => '千葉銀行', 
	'8306' => '三菱ＵＦＪフィナンシャル・グループ', 
	'8304' => 'あおぞら銀行', 
	'7186' => 'コンコルディア・フィナンシャルグループ', 
	'8601' => '大和証券グループ本社', 
	'8604' => '野村ホールディングス', 
	'8766' => '東京海上ホールディングス', 
	'8750' => '第一生命ホールディングス', 
	'8795' => 'Ｔ＆Ｄホールディングス', 
	'8630' => 'ＳＯＭＰＯホールディングス', 
	'8725' => 'ＭＳ＆ＡＤインシュアランスグループホールディングス', 
	'8697' => '日本取引所グループ', 
	'8591' => 'オリックス', 
	'8253' => 'クレディセゾン', 
	'8830' => '住友不動産', 
	'8802' => '三菱地所', 
	'8804' => '東京建物', 
	'3289' => '東急不動産ホールディングス', 
	'8801' => '三井不動産', 
	'9009' => '京成電鉄', 
	'9022' => 'ＪＲ東海', 
	'9001' => '東武鉄道', 
	'9008' => '京王電鉄', 
	'9021' => 'ＪＲ西日本', 
	'9005' => '東急', 
	'9007' => '小田急電鉄', 
	'9020' => 'ＪＲ東日本', 
	'9147' => 'ＮＩＰＰＯＮ　ＥＸＰＲＥＳＳホールディングス', 
	'9064' => 'ヤマトホールディングス', 
	'9104' => '商船三井', 
	'9107' => '川崎汽船', 
	'9101' => '日本郵船', 
	'9201' => '日本航空', 
	'9202' => 'ＡＮＡホールディングス', 
	'9301' => '三菱倉庫', 
	'9984' => 'ソフトバンクグループ', 
	'9613' => 'ＮＴＴデータグループ', 
	'9433' => 'ＫＤＤＩ', 
	'9434' => 'ソフトバンク', 
	'9432' => 'ＮＴＴ', 
	'9502' => '中部電力', 
	'9501' => '東京電力ホールディングス', 
	'9503' => '関西電力', 
	'9531' => '東京ガス', 
	'9532' => '大阪ガス', 
	'6098' => 'リクルートホールディングス', 
	'7974' => '任天堂', 
	'3659' => 'ネクソン', 
	'9602' => '東宝', 
	'4385' => 'メルカリ', 
	'4324' => '電通グループ', 
	'9735' => 'セコム', 
	'9766' => 'コナミグループ', 
	'4661' => 'オリエンタルランド', 
	'2432' => 'ディー・エヌ・エー', 
	'6178' => '日本郵政', 
	'2413' => 'エムスリー', 
	'4689' => 'ＬＩＮＥヤフー', 
	'4755' => '楽天グループ', 
	'4751' => 'サイバーエージェント', 
	'4704' => 'トレンドマイクロ', 
);

$stock = '1605';
$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_ts&bcode='. $stock;
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
$output = curl_exec($ch) or die ('error '. curl_error($ch));
curl_close($ch);
mb_language('Japanese');
$html_source = mb_convert_encoding($output, 'UTF-8', 'auto');
//print_r($html_source);
*/

/*
$i = 0;
foreach ($stocks as $stock => $sname) {
	$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_ts&bcode='. $stock;
	$file = dirname(__FILE__). '/../download/'. $stock. '.html';
	$cur = file_get_contents($url);
	file_put_contents($file, $cur);
}
exit;
*/

foreach ($stocks as $stock => $sname) {
	$file = dirname(__FILE__). '/../download/'. $stock. '.html';
	$html = file_get_contents($file);
	$html = preg_replace('/\t/', '', $html);
	$html = preg_replace('/\n/', '', $html);
	$html = preg_replace('/&nbsp/', '空欄', $html);

	//$this->vd($html);
	$ret = preg_match('/<table class="tb_comp_ts">.*?<\/table>/', $html, $match);

	$ret = preg_match('/<th class="em th_stock_comp">.*?<br>/', $html, $main); // main stock
	$ret = preg_match_all('/<th class="th_stock_comp">.*?<br>/', $html, $subs); // sub stock
	$ret = preg_match('/<th class="none_right th_stock_comp">.*?<br>/', $html, $end); // end stock

	$ret = preg_match_all('/<td class="em str_center">.*?<\/td>/', $html, $main_info); // main_info
	$ret = preg_match_all('/<td class="str_center">.*?<\/td>/', $html, $subs_info); // subs_info
	$ret = preg_match_all('/<td class="none_right str_center">.*?<\/td>/', $html, $end_info); // end_info

	//$this->vd(array($ret, $match, $main, $subs, $end, $main_info, $subs_info, $end_info));
	//$this->vd(array($main, $main_info[0][1]));

	$main = preg_replace('/<th class="em th_stock_comp">|<br>/', '', $main[0]);
	$end = preg_replace('/<br>/', '', $end[0]);

	$mi = preg_replace('/<br>/', '', $main_info[0][1]);
	if (preg_match('/^.*?(底値圏突入)/', $mi, $m)) {
		$result[$main] = array($main, $m[1]);
	}

//	$this->vd($subs_info);
/*
	$n = 6;
	foreach ($subs[0] as $i => $d) {
		$sub = preg_replace('/<br>/', '', $d);
		$si = preg_replace('/<br>/', '', $subs_info[0][$n]);
		if (preg_match('/^.*?(底値圏突入)/', $si, $s)) {
			$result[$sub] = $s[1];
		}
		$n++;
	}

	$ei = preg_replace('/<br>/', '', $end_info[0][1]);
	if (preg_match('/^.*?(底値圏突入)/', $ei, $e)) {
		$result[$end] = $e[1];
	}
*/
}

//$this->vd($stocks);

$json = dirname(__FILE__). '/../download/bottom.json';
file_put_contents($json, json_encode($result));

$g_json = file_get_contents($json);
$g_result = json_decode($g_json);
//$this->vd($g_result);

/*
// 配当情報取得
foreach ($g_result as $stock) {
	$code = $stock[0];
	$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_zim&bcode='. $code;
	$f = dirname(__FILE__). '/../download/haitou/'. $code. '_bottom.html';
	$cur = file_get_contents($url);
	file_put_contents($f, $cur);
	sleep(1);
}
*/

unset($html);
unset($match);
unset($result);

foreach ($g_result as $stock) {
	$code = $stock[0];
	$file = dirname(__FILE__). '/../download/haitou/'. $code. '_bottom.html';
	$html = file_get_contents($file);

	$html = preg_replace('/\t/', '', $html);
	$html = preg_replace('/\n/', '', $html);

	$ret = preg_match('/<span class="str_b">予想配当利回り<\/span>.*?\%<\/td>/', $html, $match);
//$this->vd($match);exit;
//	$ret = preg_match('/<td class="none_right em num_b">.*?</td>/', $match[0], $m);

	$haitou = preg_replace('/<table>|<tbody>|<tr>|<span class="str_b">予想配当利回り<\/span>|<th class="none_left em">.*?<\/th>|<td class="none_right em num_b">|<\/td>/', '', $match);
//$this->vd($haitou);exit;
	$result[$code] = $haitou;
}

$this->vd($result);exit;

		$get = (object) $_GET;
		$post = (object) $_POST;



//wpログイン情報関連を取得
$wp_user_info = wp_get_current_user();
//echo $user -> user_login; //ログインIDを取得
$user_login = $wp_user_info->user_login;

//DBコネクタを生成
$host = 'localhost';
$username = 'root';
$password = 'aErQl0cbmYmO';
$dbname = 'stocks';

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
	error_log($mysqli->connect_error);
	exit;
}

$sql = "select max(date) as max_date from s4042 limit 10;";

//SQL文を実行する
$data_set = $mysqli->query($sql);
//扱いやすい形に変える
$result = [];
while($row = $data_set->fetch_assoc()){
	$rows[] = $row;
}
var_dump($rows);


		$get->action = 'search';
		switch($get->action) {
			case 'search':
			default:
//				$tb = new Customer;
//				$initForm = $tb->getInitForm();
//				$rows = $tb->getList($get, $un_convert = true);
				$formPage = 'menu-top';
//$this->vd($rows);
				echo $this->get_blade()->run("menu-top", compact('rows', 'formPage', 'initForm'));
				break;
		}
		return $this->_test;
	}

	/**
	 *
	 **/
	public function detailAction() {
		echo $this->get_blade()->run("customer-detail");
	}

	public function testSC() {
		//wpログイン情報関連を取得
		$wp_user_info = wp_get_current_user();
		//echo $user -> user_login; //ログインIDを取得
		$user_login = $wp_user_info->user_login;

		//DBコネクタを生成
		$host = 'localhost';
		$username = 'root';
		$password = 'aErQl0cbmYmO';
		$dbname = 'stocks';

		$mysqli = new mysqli($host, $username, $password, $dbname);
		if ($mysqli->connect_error) {
			error_log($mysqli->connect_error);
			exit;
		}

		$sql = "select max(date) as max_date from s4042 limit 10;";

		//SQL文を実行する
		$data_set = $mysqli->query($sql);
		//扱いやすい形に変える
		$result = [];
		while($row = $data_set->fetch_assoc()){
			$rows[] = $row;
		}
		return $rows;
	}
}
?>
