<?php
/**
 * GoodsController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/models/Goods.php');
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
		$get = (object) $_GET;
		$post = (object) $_POST;

		ini_set('mbstring.internal_encoding', 'UTF-8');
//		require_once("wp-load.php");

		$Goods = new Goods;
		$codes = $Goods->getList();

		$cnt = 0;
		foreach ($codes as $asin => $acode) {
//			if ($cnt ==2) { break; }

			$goods_url = 'https://dyn.keepa.com/r/?domain=5&asin='. $asin;
			$img_src = sprintf("https://graph.keepa.com/pricehistory.png?asin=%s&domain=co.jp", $asin);

			$content  = '## 利益商品！これを安い時に買う'. PHP_EOL;
			$content .= '[get_amazon_code "'. $asin. '"]'. PHP_EOL. PHP_EOL;
			$content .= $goods_url. PHP_EOL. PHP_EOL;
			$content .= '<img src="https://graph.keepa.com/pricehistory.png?asin='. $asin. '&domain=co.jp" value="'. $asin. '">'. PHP_EOL. PHP_EOL;

			//投稿を追加
			$new_post = array(
				'post_title' => sprintf('Amazon 商品 %s %s', $asin, $acode), 
				'post_content' => $content, 
				'post_status' => ($get->status == 'publish') ? 'publish' : 'draft', 
				'post_date' => date('Y-m-d H:i:s'), 
				'post_author' => 1, 
				'post_name' => sprintf('ag-%s-%s', $asin, $acode), 
				'post_type' => 'post', 
			);

			if ($get->status) { $post_id = wp_insert_post($new_post); }

			// 表示用に情報追加
			$goods_images = explode(';', $Goods->getImage($asin));
//			$this->vd($goods_images);
			$new_post['goods_url'] = $goods_url;
			$new_post['goods_image'] = $goods_images[0];
			$new_post['chart_image'] = $img_src;
			$new_posts[] = $new_post;
//$this->vd($post_id);
			//カテゴリーを設定
			$categioryids = array('amazon_goods', 'monetize');
			wp_set_object_terms($post_id, $categioryids, 'category');

			//カスタムフィードに値を追加
//			$customfieldname = "カスタムフィールド名";
//			$customfieldvalue = "値";
//			add_post_meta($post_id, $customfieldname, $customfieldvalue, true );

			$cnt++;
		}

//$this->vd($new_posts);
		foreach ($new_posts as $i => $p) {
			$rows[$i] = (object) $p;
		}

		echo $this->get_blade()->run("goods-post", compact('rows'));
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

	$Goods = new Goods;
	$codes = $Goods->getList();

	return $url. $codes[$asin];
}
add_shortcode('get_amazon_code','get_amazon_assosiate_code');
?>
