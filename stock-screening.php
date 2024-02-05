<?php

require(__DIR__. '/library/rakit/rakid/vendor/autoload.php');
use Rakit\Validation\Validator;
require(__DIR__. '/library/vendor/autoload.php');
use eftec\bladeone\BladeOne;
/*
require_once(dirname(__DIR__). '/stock-screening/models/model.php');
require_once(dirname(__DIR__). '/stock-screening/models/Shop.php');
require_once(dirname(__DIR__). '/stock-screening/models/Applicant.php');
require_once(dirname(__DIR__). '/stock-screening/models/Sales.php');
require_once(dirname(__DIR__). '/stock-screening/models/Goods.php');
require_once(dirname(__DIR__). '/stock-screening/models/Customer.php');
require_once(dirname(__DIR__). '/stock-screening/models/ScheduleRepeat.php');
require_once(dirname(__DIR__). '/stock-screening/models/RepeatExclude.php');
require_once(dirname(__DIR__). '/stock-screening/models/Stock.php');
require_once(dirname(__DIR__). '/stock-screening/models/StockTransfer.php');
 */

//require_once(dirname(__DIR__). '/stock-screening/controllers/GoodsController.php');
require_once(dirname(__DIR__). '/stock-screening/controllers/MenuController.php');

//require(__DIR__. '/library/vendor/vendor_phpspreadsheet/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('Asia/Tokyo');

/*
Plugin Name:Stock Screening
Plugin URI: http://www.example.com/plugin
Description: 銘柄のスクリーニング
Author: myu
Version: 0.1
Author URI: http://www.example.com
*/

class StockScreening {

	/**
	 * 
	 **/
	function __construct() {
		add_action('admin_menu', array($this, 'add_pages'));
		add_action('admin_menu', array($this, 'add_sub_menu'));
//		add_action('init', array($this, 'export_csv'));
//		add_action('init', array($this, 'export_pdf'));
	}

	/**
	 * 
	 **/
	function add_pages() {
		add_menu_page('銘柄スクリーニング','銘柄スクリーニング',  'level_8', 'stock-screening', array($this,'menu_top'), '', 26);
	}

	/**
	 * 
	 **/
	function add_sub_menu() {
		$cur_user = wp_get_current_user();

		switch ($cur_user->roles[0]) {
			case 'administrator':
			case 'editor':
				if (in_array($cur_user->user_login, array('admin', 'ceo', 'myu'))) {
					// 登録画面
//					add_submenu_page('stock-screening', '商品登録','🔷商品登録', 'read', 'goods-detail', array(&$this, 'goods_detail'));

					// 検索画面
					add_submenu_page('stock-screening', '商品検索','🔶商品検索', 'read', 'goods-list', array(&$this, 'goods_list'));

					// その他
//					add_submenu_page('stock-screening', '日別商品集計','日別商品集計', 'read', 'sum-day-goods', array(&$this, 'sum_day_goods'));

				} else {
					$this->remove_menus();
				}
				break;

			case 'subscriber' :
				if (in_array($cur_user->user_login, array('naitou'))) {
					add_submenu_page('stock-screening', '配送予定表③','配送予定表③', 'read', 'delivery-graph', array(&$this, 'delivery_graph'));
				} else {
					$this->remove_menus();
				}

			default:
				$this->remove_menus();
				//add_action( 'admin_bar_menu', 'remove_admin_bar_menus', 999 );
				break;
		}
	}

	/**
	 * メニュー
	 **/
	function menu_top() {
		echo 'stock screening git';
		$m = new MenuController();
		$m->listAction();
	}

	/**
	 * 商品詳細
	 **/
	function goods_detail() {
		$g = new GoodsController();
		$g->detailAction();
	}

	/**
	 * 顧客詳細
	 **/
	function customer_detail() {
		$c = new CustomerController();
		$c->detailAction();
	}

	/**
	 * 注文詳細
	 **/
	function sales_detail() {
		$s = new SalesController();
		$s->detailAction();
	}

	/**
	 * 在庫詳細
	 **/
	function stock_detail() {
		$s = new StockController();
		$s->detailAction();
	}

	/**
	 * 転送処理
	 **/
	function stock_transfer() {
		$s = new StockController();
		$s->transferAction();
	}

	/**
	 * 在庫ロット番号登録
	 **/
	function stock_lot_regist() {
		$s = new StockController();
		$s->lotRegistAction();
	}

	/**
	 * ロット管理
	 **/
	function lot_regist() {
		$s = new SalesController();
		$s->lotRegistAction();
	}

	/**
	 * 日別集計
	 **/
	function sum_day_goods() {
		$s = new SalesController();
		$s->sumDayGoodsAction();
	}

	/**
	 * 商品検索
	 **/
	function goods_list() {
		$g = new GoodsController();
		$g->listAction();
	}

	/**
	 * 顧客検索
	 **/
	function customer_list() {
		$c = new CustomerController();
		$c->listAction();
	}

	/**
	 * 注文検索
	 **/
	function sales_list() {
		$s = new SalesController();
		$s->listAction();
	}

	/**
	 * 在庫検索
	 **/
	function stock_list() {
		$s = new StockController();
		$s->listAction();
	}

	/**
	 * 入庫予定日検索
	 **/
	function stock_receive() {
		$s = new StockController();
		$s->receiveAction();
	}

	/**
	 * 在庫証明書
	 **/
	function stock_export() {
		$s = new StockController();
		$s->exportAction();
	}

	/**
	 * 倉出伝票
	 **/
	function stock_export_day() {
		$s = new StockController();
		$s->exportDayAction();
	}

	/**
	 * 配送表
	 **/
	function delivery_graph() {
		$s = new SalesController();
		$s->deliveryGraph();
	}

	/**
	 *
	 **/
	function remove_menus() {
		remove_menu_page('index.php'); //ダッシュボード
		remove_menu_page('profile.php'); // プロフィール
		remove_menu_page('edit.php'); //投稿メニュー
//		remove_menu_page('edit.php?post_type=memo'); //カスタム投稿タイプmemo
		remove_menu_page('upload.php'); // メディア
		remove_menu_page('edit.php?post_type=page'); //固定ページ
		remove_menu_page('edit-comments.php'); //コメント
		remove_menu_page('themes.php'); //外観
		remove_menu_page('plugins.php'); //プラグイン
//		remove_menu_page('users.php'); //ユーザー
		remove_menu_page('tools.php'); //ツールメニュー 
		remove_menu_page('options-general.php'); //設定 
	}

	/**
	 *
	 **/
	function vd($d) {
//return false;
		global $wpdb;
		$cur_user = wp_get_current_user();
		if (current($cur_user->roles) == 'administrator') {
			echo '<div class="border border-success mb-3">';
			echo '<pre>';
//			var_dump($d);
			print_r($d);
			echo '</pre>';
			echo '</div>';
		}
	}
}

/**
 * バージョンアップ通知の非表示
 **/
function update_nag_hide() {
	remove_action('admin_notices', 'update_nag', 3);
	remove_action('admin_notices', 'maintenance_nag', 10);
}
add_action('admin_init', 'update_nag_hide');

/**
 * 「WordPress のご利用ありがとうございます。」の非表示、文言の追加
 **/
function custom_admin_footer() {
	// echo '<a href="mailto:test@test.com">システム管理者へ問合せ</a>';
}
add_filter('admin_footer_text', 'custom_admin_footer');

$StockScreening = new StockScreening;
