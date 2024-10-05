<?php
/**
 * MenuController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/models/Stock.php');
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
		$get = (object) $_GET;
		$post = (object) $_POST;

		try {
			// pagination
			$this->setPager('Stock');
			$wp_list_table = $this->getPager();

		} catch (Exception $e) {
			echo '<b>'. $e->getMessage(). '</b>';
		}

		global $wpdb;

		$get->action = 'search';
		switch($get->action) {
			case 'search':
			default:
				$tb = new Goods;
				$initForm = $tb->getInitForm();
				$rows = $tb->getList($get, $un_convert = true);
				$formPage = 'stock-list';
//$this->vd($rows);
				echo $this->get_blade()->run("stock-list", compact('get', 'post', 'rows', 'formPage', 'initForm', 'wp_list_table'));
				break;
		}
	}

	/**
	 *
	 **/
	public function detailAction() {
		echo $this->get_blade()->run("customer-detail");
	}

	/**
	 *
	 **/
	public function screeningAction() {

		$Stock = new Stock;
		$initForm = $Stock->getInitForm();
		$stocks = $initForm['select']['stock_codes'];
//$this->vd($stocks);exit;

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

		// 株予報情報取得
		//$Stock->getStockReportFromWebSite();

		// 株予報情報を整形
		list($html, $ret, $result) = $Stock->convertStockReport();

//$this->vd($stocks);

		// 株予報情報整形結果をバックアップ
		$json = $Stock->backupPriceBottomData($result);

		// 結果を表示用に再取得
		$g_result = $Stock->refetchPriceBottomData($json);
//$this->vd(count((array) $g_result));exit;

		// 配当情報取得
		//$Stock->getDividendInfoFromWebSite($g_result);


unset($html);
unset($match);
unset($result);

		// 配当情報を整形
		$bottomStocks = $Stock->convertDevidendInfo($g_result);

		$get = (object) $_GET;
		$post = (object) $_POST;

		// DB情報取得
		$rows = $Stock->getDetail();
$this->vd($rows);

		$get->action = 'search';
		switch($get->action) {
			case 'search':
			default:
//				$tb = new Customer;
//				$initForm = $tb->getInitForm();
//				$rows = $tb->getList($get, $un_convert = true);
				$formPage = 'menu-top';
//$this->vd($rows);
				echo $this->get_blade()->run("menu-top", compact('rows', 'formPage', 'initForm', 'stocks', 'bottomStocks'));
				break;
		}
		return $this->_test;
	}
}
?>
