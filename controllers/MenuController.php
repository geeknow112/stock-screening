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
/*
$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_ts&bcode=9433';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
$output = curl_exec($ch) or die ('error '. curl_error($ch));
curl_close($ch);
mb_language('Japanese');
$html_source = mb_convert_encoding($output, 'UTF-8', 'auto');
print_r($html_source);
*/

//$file = dirname(__FILE__). '/../download/9433.html';
$file = dirname(__FILE__). '/../download/5406.html';
//$file = dirname(__FILE__). '/../download/3436.html';
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

$main = preg_replace('/<br>/', '', $main[0]);
$end = preg_replace('/<br>/', '', $end[0]);

$mi = preg_replace('/<br>/', '', $main_info[0][1]);
if (preg_match('/^.*?(底値圏突入)/', $mi, $m)) {
	$result[$main] = $m[1];
}

$this->vd($subs_info);
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

$this->vd($result);

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
