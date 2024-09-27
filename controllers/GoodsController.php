<?php
/**
 * GoodsController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
//require_once(dirname(__DIR__). '/models/Goods.php');
require_once(dirname(__DIR__). '/library/Ext/Controller/Action.php');
/**
 * GoodsControllerClass short discription
 *
 * long discription
 *
 */
class GoodsController extends Ext_Controller_Action
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
			$this->setPager('Goods');
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
				$formPage = 'goods-list';
//$this->vd($rows);
				echo $this->get_blade()->run("goods-list", compact('get', 'post', 'rows', 'formPage', 'initForm', 'wp_list_table'));
				break;
		}
	}

	/**
	 *
	 **/
	public function detailAction() {
		$get = (object) $_GET;
		$post = (object) $_POST;

		$this->setTb('Goods');
		$page = 'goods-detail';

		$rows = null;
		switch($get->action) {
			case 'regist':
				$tb = new Applicant;
				break;

			default:
				$initForm = $this->getTb()->getInitForm();
				$rows = $this->getTb()->getList();
				echo $this->get_blade()->run("goods-detail");
				break;

			case 'search' :
				$tb = new Applicant;
				$initForm = $tb->getInitForm();
//				$prm = (!empty($prm->post)) ? (object) $prm : $tb->getPrm();
				$rows = $tb->getList($prm);
				$formPage = 'sales-list';
				echo $this->get_blade()->run("sales-list", compact('rows', 'formPage', 'initForm'));
				break;
				
			case 'confirm':
				if (!empty($post)) {
					switch ($post->cmd) {
						default:
						case 'cmd_confirm':
							$msg = $this->getValidMsg();
							$rows = $post;
							$rows->name = $post->goods_name;
							$rows->id = $rows->goods;
							if ($rows->goods) { $rows->btn = 'update'; }

							if ($msg['msg'] !== 'success') {
								$rows->messages = $msg;
							}
						break;
					}
				}
				if($rows->messages) {
						$msg = $rows->messages;
						$get->action = 'save';
				} else {
				}

				echo $this->get_blade()->run("goods-detail", compact('rows', 'get', 'post', 'msg'));
				break;

			case 'complete':
				$prm = $tb->getPrm();
				$rows = $tb->regDetail($prm);
				echo $this->get_blade()->run("shop-detail-complete", compact('rows', 'prm'));
				break;

			case 'save':
				if (!empty($post)) {
					if ($post->cmd == 'save') {
						$msg = $this->getValidMsg();
						if ($msg['msg'] == 'success') {
							$rows = $this->getTb()->regDetail($get, $post);
							$rows->goods_name = $rows->name;
							$get->action = 'complete';

						} else {
							$rows = $post;
							$rows->name = $post->goods_name;
							$rows->messages = $msg;
						}
					}
				}
				echo $this->get_blade()->run("goods-detail", compact('rows', 'get', 'post', 'msg'));
				break;

			case 'edit-exe':
				if (!empty($post)) {
					if ($post->cmd == 'update') {
						$msg = $this->getValidMsg();
						if ($msg['msg'] == 'success') {
							$rows = $this->getTb()->updDetail($get, $post);
							$rows->goods_name = $rows->name;
							$get->action = 'complete';

						} else {
							$rows = $post;
							$rows->name = $post->goods_name;
							$rows->messages = $msg;
						}
					}
				}
				echo $this->get_blade()->run("goods-detail", compact('rows', 'get', 'post', 'msg'));
				break;

			case 'edit':
				if (!empty($get->goods)) {
					$rows = $this->getTb()->getDetailByGoodsCode($get->goods);
					$rows->goods_name = $rows->name;
					$rows->cmd = $post->cmd = 'cmd_update';

				} else {
					$msg = $this->getValidMsg();

					$rows = $post;
					$rows->name = $post->goods_name;

					if ($msg['msg'] !== 'success') {
						$rows->messages = $msg;
					}
				}
				echo $this->get_blade()->run("goods-detail", compact('rows', 'get', 'post', 'msg'));
				break;

			case 'cancel':
				$prm = (object) $_GET;
				unset($_POST);
				$tb = new Applicant;
				$rows = $tb->getDetail($prm);
				$p = $rows;
				$formPage = 'sales-list';
				echo $this->get_blade()->run("shop-detail", compact('rows', 'formPage', 'prm', 'p'));
				break;

			case 'preview':
				// 申込データプレビュー画面
				// (PDF保存形式でプレビューする)
				echo 'test preview';
				$app = new Applicant;
				$curUser = $app->getCurUser();
				if ($curUser->roles != 'administrator') {
					$applicant = htmlspecialchars($_GET['post']);
					$row = $app->getDetailByApplicantCode($applicant);

				} else {
					$row = null;
				}
				echo $this->get_blade()->run("preview", compact('row', 'formPage', 'prm', 'p'));
				break;

			case 'init-status':
				$prm = (object) $_GET;
				unset($_POST);
				$applicant = $prm->post;
				$tb = new Applicant;
				$ret = $tb->initStatus($applicant);
				$result = ($ret == true) ? 'true' : 'false';
				echo '<script>window.location.href = "'. home_url(). '/wp-admin/admin.php?page=sales-list&init-status='. $result. '";</script>';
				break;
		}
	}

	/**
	 * 商品情報の自動投稿
	 * 
	 **/
	public function postAction() {
		ini_set('mbstring.internal_encoding', 'UTF-8');
//		require_once("wp-load.php");

		$title = "amazon goods post test";
//		$content = '## 利益商品！これを安い時に買う！\n [get_amazon_code "B09G95LCFL"]';
		$content = '## profit goods'. PHP_EOL. '[get_amazon_code "B09G95LCFL"]';

		//投稿を追加
		$new_post = array(
			'post_title' => $title, 
			'post_content' => $content, 
			'post_status' => 'publish', 
			'post_date' => date('Y-m-d H:i:s'), 
			'post_author' => 1, 
			'post_name' => 'amazon-test-goods', 
			'post_type' => 'post', 
		);
var_dump($new_post);
		$post_id = wp_insert_post($new_post);
echo $post_id;
		//カテゴリーを設定
		$categioryids = array('monetize');
		wp_set_object_terms($post_id, $categioryids, 'category');

		//カスタムフィードに値を追加
//		$customfieldname = "カスタムフィールド名";
//		$customfieldvalue = "値";
//		add_post_meta($post_id, $customfieldname, $customfieldvalue, true );
	}
}

/**
 * ASINコードから、紐づくAmazon アソシエイトのコードを返す
 * 
 **/
function get_amazon_assosiate_code($atts = null) {
	//$no = $atts[0];
	$url = 'https://amzn.to/';

	$asin = $atts[0];

	$codes = array(
		'B08H2DS1WX' => '47DwCaj', 
		'B08H2DS1WX' => '47DwCaj', 
		'B00I95ZWA6' => '3XSbBoR', 
		'B0BSF7V2JY' => '4gzolrQ', 
		'B07Y9MSQRL' => '4gCSfLS', 
		'B0BCVKV9V2' => '4deg4Xv', 
		'B08X4YRK54' => '4edhRgG', 
		'B07M8QB1ZC' => '3XUjYAz', 
		'B00B977RWC' => '3ZCkjJg', 
		'B0D6LLPT6F' => '4e7Vbyu', 
		'B000AY8PEY' => '3MWYvAM', 
		'B0B8SFY4K8' => '4edqEPY', 
		'B0B34BJS4F' => '3XRoYpA', 
		'B0BCG8274Y' => '3ZB3EWK', 
		'B008ZXT9JC' => '3TDNGas', 
		'B0DC5X912P' => '4dgKYhV', 
		'B01FH4H5XS' => '4gAswnd', 
		'B0BXRGSNZ5' => '4djgCLU', 
		'B09LM14VFR' => '3zpS85L', 
		'B00BQS8BME' => '4eeSM54', 
		'B07P3PT3TP' => '3NgzWyV', 
		'B0CB8L3LCS' => '3MRzMh8', 
		'B0CRGK71WN' => '3XTtTGq', 
		'B07CKQRCWW' => '3zjP7E7', 
		'B09SB6SQHZ' => '3ZDoch5', 
		'B0BVDGT18M' => '4ey4307', 
		//'B075XMYDWZ' => 'https://www.amazon.co.jp/dp/B075XMYDWZ', 
		'B0CH3BKF69' => '4gsPc90', 
		'B072MMRZSL' => '3TDGLOz', 
		'B0CPLHQNYK' => '3THsb8K', 
		'B0C3L3RZR5' => '4ete5Q0', 
		'B0BDYZ9N5K' => '3BcILXA', 
		'B0BN6BQ6MB' => '3ZuWwek', 
		'B0CF5H2QBB' => '3XUl2Ez', 
		'B01E359XQY' => '3XEhF30', 
		'B00BQS81FQ' => '3NgAiWh', 
		'B0CXJ2XR2V' => '47AuCzo', 
		'B0BYHZM521' => '3N29Lvn', 
		'B0B5QRXX93' => '3ZB4p22', 
		'B0137K161G' => '3MVVj8d', 
		'B0BHB58V3B' => '4gyPTOc', 
		'B07F3PXLJY' => '3BgAm5x', 
		'B0BKKH9VR3' => '3ZzCyPp', 
		'B0CRYC698V' => '4dxq5iV', 
		'B0CWYTWS89' => '3MWZwsA', 
		'B07VHV92YG' => '4gCQjD0', 
		'B0CPM7M6RS' => '4dcGPLT', 
		'B0CRGQM4D8' => '3XUlv9N', 
		'B00K183DUU' => '3XEi59A', 
		'B000UJ8W7Y' => '3XtupcQ', 
		'B07T9LCGY1' => '4dppcJa', 
		'B00KQ9GBMG' => '3B7TdPZ', 
		'B09RWR9FMT' => '4gx0mJZ', 
		'B0BPX891ZH' => '3XTEst6', 
		'B0CTMJPL2M' => '3ZwCsrR', 
		'B08G93W6FK' => '3XVCUyV', 
		'B0C73J8QNV' => '3ZB4YsG', 
		'B001AK1NVE' => '3XSnOde', 
		'B0053ABBNM' => '47JKIag', 
		'B0C1S47WCN' => '3MZWPpO', 
		'B0BF3TKJ2W' => '3MVo5Gg', 
		'B07Y28X1YS' => '3MTzbLE', 
		'B009VTG5QO' => '3MVnTH2', 
		'B08VFH19HJ' => '3BqXVZa', 
		'B0BH822MF8' => '3ZA2Lxn', 
		'B07ND669JY' => '3BoagNL', 
		'B09YCMXCVR' => '4dgzfjq', 
		'B0CRGSDLHD' => '4eus6Ns', 
		'B09KV84SW3' => '4dfcah5', 
		'B0B3J563FP' => '4ez69wJ', 
		//'https://dyn.keepa.com/r/?domain=5&seller=AN1VRQENFRJN5&asin=B0BN7TLDT3' => '3Zw5o3r', 
		'B08CS5DFCL' => '4dcmC9f', 
		'B014F4HYN2' => '4edBzsS', 
		'B0CP96XPPD' => '4gxs35r', 
		'B0CNPS532T' => '4dcMPnT', 
		'B0002SA66S' => '4ebxoOk', 
		'B07N1ZNH4J' => '3MVrUuX', 
		'B0CSYYXJHB' => '3BgXUqY', 
		'B09SPBM78S' => '4gCV27I', 
		'B0CM9FC3XV' => '47B7Jfi', 
		'B0CZNCQ9CD' => '4gLsskW', 
		'B09M6WD9Z5' => '47AQr22', 
		'B078YTQBY5' => '47EDe88', 
		'B08MVXMV26' => '3XyjpuN', 
		'B0BQ6YNXJF' => '3ztKPdc', 
		'B07XCSQMJD' => '3ZyorKe', 
		'B094BY5LKF' => '3MT7Idh', 
		//'B07CNWFD8R', 
		'B004VT0XLW' => '4dfEfoE', 
		'B0CWGQPF9X' => '3XWSWbS', 
		'B0B82VRLMM' => '4dfEpMM', 
		'B0D3H2CZ5V' => '3XTFkxS', 
		//'B06Y2YV1N5', 
		//'B0D8KT14PM&source=rss&path=NEW', 
		'B07R1KVZT9' => '3ZzDPGb', 
		'B0BR6FQBKR' => '3zkOB8S', 
		'B008CVF6HQ' => '47zA1qH', 
		'B096V4W7G6' => '3Bbu2MG', 
		'B073VTDYDR' => '3TFC1rs', 
		'B0868TP9JT' => '4evZWli', 
		'B0C4YLSNTS' => '4exxO1g', 
		//'B0D8KT14PM' => 'https://www.amazon.co.jp/dp/B0D8KT14PM', 
		'B0CGKNZ7X8' => '47EFbBv', 
		'B084DP9NNJ' => '3ZwDpAr', 
		'B074FM2V59' => '4etlDmB', 
		'B0BKZWJVFV' => '3TFRZ57', 
		'B07YC8CJ3Y' => '4edxDZc', 
		'B000FHXZPU' => '3TFdfIh', 
		'B003BEDCAW' => '3TAdnc0', 
		'B0CP36RHMS' => '3ZyoNk2', 
		'B08BL7ZMML' => '4gD0ckc', 
		'B009V8NS8S' => '4epwiOC', 
		'B0067R92AY' => '4ez7tzH', 
		'B005C7YC7S' => '4gD2HD2', 
		'B003OQTU0S' => '4gEd6P9', 
		'B002X5FGKO' => '3BfUqF2', 
		'B0BZ8C5QPY' => '3XDbDzB', 
		'B0BCF5RJV2' => '3ZwDOmr', 
		'B0CMXDH7YB' => '4effKZO', 
		'B019DR0EKQ' => '4dfRzt5', 
		'B0BZ7HWD2K' => '47DX2sD', 
		'B0CHMVKZW9' => '4ed8RZa', 
		'B01A50N3UI' => '47AVzDk', 
		'B00IAIDD2Q' => '3zvzlWM', 
		'B0757MX5GL' => '4euRa75', 
		'B01LWYTCPP' => '3XEpqpE', 
		'B083SS98B2' => '3Zypda6', 
		'B07NHSX5C3' => '47K1Zjt', 
		'B0BFF87WC5' => '4eB1Lh0', 
		'B09T33KG57' => '47DXpU3', 
		'B0CS2QQX38' => '3XtEpCI', 
		'B0B3173PHG' => '3BglrYW', 
		'B007LGTUKW' => '3TDOvQw', 
		'B01N2VKU0T' => '3B7YZ47', 
		'B06ZY89XMN' => '4dgB000', 
		'B00V5OQ2WA' => '3zjRo27', 
		'B0BBVRCYWC' => '4gDtCiq', 
		'B09DFL54FZ' => '3BftIfQ', 
		'B009CUG0PI' => '3ZxNpti', 
		'B0D8CKSS52' => '3Biiuan', 
		'B09Z6KBLW3' => '4gBKTIt', 
		'B00AR8SNNQ' => '3XTAyAq', 
		'B086JSF7X6' => '3MXVNe0', 
		'B0C23Z912K' => '3XSUzXJ', 
		'B0B2P1W93D' => '3MYxmNL', 
		'B0079Z72I2' => '3MTcAPB', 
		'B0B1QJC583' => '3BnXMpo', 
		'B07TDSTT9J' => '3zx4NUp', 
		'B0C4ZY3Z5J' => '3MRGBPM', 
		'B0862GT9HC' => '4djRTad', 
		'B08Z384CMK' => '4gtBJxX', 
		'B09MMTK4YN' => '3Bh3mdq', 
		'B07CK583Z2' => '3XUrqM3', 
		'B0CL7DZ7XR' => '3XTjdHN', 
		'B00KBP3BBY' => '4dn6lhv', 
		'B0D3PGV5LX' => '3Bgm70q', 
		'B0BS6B6G4B' => '4dn6oKd', 
		'B0BW46P2TV' => '3ZGEW70', 
		'B0CW5FZXMK' => '3ZxRMEI', 
		'B09G95LCFL' => '3BceqbD', 
		'B0B3159MMP' => '4eC9uvf', 
		'B008KCR4BI' => '3XRxkNT', 
		'B0B2HV3HL9' => '4ew5T1A', 
		'B06WRVSPV4' => '3XTOSsQ', 
		'B0C74BNB6W' => '3ZFqUCZ', 
		'B0CWZW2L6P' => '4eymJgb', 
		'B0779N9GZF' => '4exxz6h', 
		'B0CS5RD64R' => '47ABKMa', 
		'B0CL4LB8PY' => '4ecZmZR', 
		'B017TUPRS8' => '3zkU5jY', 
		'B00KA2ONJW' => '4dk3B4L', 
		'B00VHNB7N8' => '3Bblq8O', 

	);

	return $url. $codes[$asin];
}
add_shortcode('get_amazon_code','get_amazon_assosiate_code');
?>
