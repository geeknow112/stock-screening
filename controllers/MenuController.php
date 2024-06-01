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
$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_ts&bcode=9433';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
$output = curl_exec($ch) or die ('error '. curl_error($ch));
curl_close($ch);
mb_language('Japanese');
$html_source = mb_convert_encoding($output, 'UTF-8', 'auto');
print_r($html_source);

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
