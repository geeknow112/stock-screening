<?php
/**
 * StockController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/models/Stock.php');
require_once(dirname(__DIR__). '/library/Ext/Controller/Action.php');
/**
 * StockControllerClass short discription
 *
 * long discription
 *
 */
class StockController extends Ext_Controller_Action
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

		// 自動投稿
		$this->postAction($bottomStocks);

		$get = (object) $_GET;
		$post = (object) $_POST;

		// DB情報取得
		$rows = $Stock->getDetail();
//$this->vd($rows);

		$get->action = 'search';
		switch($get->action) {
			case 'search':
			default:
//				$tb = new Customer;
//				$initForm = $tb->getInitForm();
//				$rows = $tb->getList($get, $un_convert = true);
				$formPage = 'stock-screening';
//$this->vd($rows);
				echo $this->get_blade()->run("stock-screening", compact('rows', 'formPage', 'initForm', 'stocks', 'bottomStocks'));
				break;
		}
		return $this->_test;
	}

	/**
	 * 情報の自動投稿
	 * 
	 **/
	public function postAction($bottomStocks = null) {
		$get = (object) $_GET;
		$post = (object) $_POST;

		ini_set('mbstring.internal_encoding', 'UTF-8');
//		require_once("wp-load.php");

		$Stock = new Stock;
		$initForm = $Stock->getInitForm();
		$stocks = $initForm['select']['stock_codes'];

		$content  = '## 【銘柄取得】　現在底値圏　かつ、高配当の銘柄'. PHP_EOL;
		$content  .= '| コード | 銘柄名 | 配当率 |'. PHP_EOL;
		$content  .= '| ------------ | ------------ | ------------ |'. PHP_EOL;

		foreach ($bottomStocks as $stock => $haitou) {
			// | 7201  | 日産自動車 | 6.03 |
			$content .= sprintf('| %s | %s | %s |', $stock, $stocks[$stock], $haitou). PHP_EOL;
//			$content .= '[get_amazon_code "'. $asin. '"]'. PHP_EOL. PHP_EOL;
//			$content .= 'https://dyn.keepa.com/r/?domain=5&asin='. $asin. PHP_EOL. PHP_EOL;
//			$content .= '<img src="https://graph.keepa.com/pricehistory.png?asin='. $asin. '&domain=co.jp" value="'. $asin. '">'. PHP_EOL. PHP_EOL;
		}

		//投稿を追加
		$today = date('YmdHis');
		$new_post = array(
			'post_title' => sprintf('現在底値圏　かつ、高配当の銘柄 %s', $today), 
			'post_content' => $content, 
			'post_status' => ($get->status == 'publish') ? 'publish' : 'draft', 
			'post_date' => date('Y-m-d H:i:s'), 
			'post_author' => 1, 
			'post_name' => sprintf('stocks-ifis-%s', $today), 
			'post_type' => 'post', 
		);
//$this->vd($new_post);
		if ($get->status) { $post_id = wp_insert_post($new_post); }
//$this->vd($post_id);
		//カテゴリーを設定
		$categioryids = array('ifis', 'investment');
		wp_set_object_terms($post_id, $categioryids, 'category');

		//カスタムフィードに値を追加
//		$customfieldname = "カスタムフィールド名";
//		$customfieldvalue = "値";
//		add_post_meta($post_id, $customfieldname, $customfieldvalue, true );
	}
}
?>
