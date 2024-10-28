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

require_once(dirname(__DIR__). '/stock-screening/controllers/GoodsController.php');
require_once(dirname(__DIR__). '/stock-screening/controllers/MenuController.php');
require_once(dirname(__DIR__). '/stock-screening/controllers/ToolsController.php');
require_once(dirname(__DIR__). '/stock-screening/controllers/StockController.php');
require_once(dirname(__DIR__). '/stock-screening/controllers/StagingController.php');

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
		add_menu_page('銘柄スクリーニング','銘柄スクリーニング',  'level_8', 'stock-screening', array($this,'stock_screening'), '', 26);
		add_menu_page('商品トラッキング','商品トラッキング',  'level_8', 'goods-tracking', array($this,'goods_tracking'), '', 26);
		add_menu_page('検証環境','検証環境',  'level_8', 'staging', array($this,'staging'), '', 26);
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
					add_submenu_page('goods-tracking', '商品投稿','🔷商品投稿', 'read', 'goods-post', array(&$this, 'goods_post'));

					// 検索画面
					add_submenu_page('stock-screening', '銘柄取得','⚡銘柄取得', 'read', 'stock-screening', array(&$this, 'stock_screening'));
					add_submenu_page('stock-screening', '銘柄検索','🔶銘柄検索', 'read', 'stock-list', array(&$this, 'stock_list'));
					add_submenu_page('goods-tracking', '商品検索','🔶商品検索', 'read', 'goods-search', array(&$this, 'goods_search'));

					// その他
//					add_submenu_page('stock-screening', '日別商品集計','日別商品集計', 'read', 'sum-day-goods', array(&$this, 'sum_day_goods'));

				} else {
					$this->remove_menus();
				}
				break;

			case 'subscriber' :
				if (in_array($cur_user->user_login, array('student', 'm.horikoshi'))) {
//					add_submenu_page('staging', 'コード検索','コード検索', 'read', 'staging-list', array(&$this, 'staging_list'));
					add_submenu_page('staging', 'コード投稿','コード投稿', 'read', 'staging-detail', array(&$this, 'staging_detail'));
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
		$m->screeningAction();
	}

	/**
	 * 商品投稿
	 **/
	function goods_post() {
		$g = new GoodsController();
		$g->postAction();
	}

	/**
	 * 商品詳細
	 **/
	function goods_detail() {
		$g = new GoodsController();
		$g->detailAction();
	}

	/**
	 * 在庫詳細
	 **/
	function stock_detail() {
		$s = new StockController();
		$s->detailAction();
	}

	/**
	 * 商品検索
	 **/
	function goods_list() {
		$g = new GoodsController();
		$g->listAction();
	}

	/**
	 * 商品検索(トラッキング用)
	 **/
	function goods_search() {
		$g = new ToolsController();
		$g->listAction();
	}

	/**
	 * 銘柄取得
	 **/
	function stock_screening() {
		echo 'stock screening git';
		$m = new StockController();
		$m->screeningAction();
	}

	/**
	 * 銘柄検索
	 **/
	function stock_list() {
		echo 'stock screening git';
		$m = new StockController();
		$m->listAction();
	}

	/**
	 * STG
	 **/
	function staging_list() {
		$m = new StagingController();
		$m->listAction();
	}

	/**
	 * STG 登録
	 **/
	function staging_detail() {
		$m = new StagingController();
		$m->detailAction();
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

/**
 * ショートコード作成
 **/
function set_shortcode(){
	$test = 'stock screening git';
	$m = new MenuController();
	$rows = $m->testSC();
//	return $rows[0]['max_date'];

	$test_array = array('1', '10', '100', '1000');
//	return implode(',', $test_array);

	$ret .= '<div>';
	$ret .= 'file名: <input type="text" class="input_text" id="file_md" value="" />';
	$ret .= 'title:  <input type="text" class="input_text" id="file_title" value="" />';
	$ret .= '<input type="submit" id="btn_set_memo" onclick="set_memo();" value="送信" />';
	$ret .= '<textarea id="export_area"></textarea>';
	$ret .= '<input type="submit" id="copy" onclick="copy_clipboard(\'export_area\');" value="copy" />';

	$ret .= '<textarea id="export_cmd"></textarea>';
	$ret .= '<input type="submit" id="copy_cmd" onclick="copy_clipboard(\'export_cmd\');" value="copy_cmd" />';

	$ret .= '<textarea id="bulk_set" style="height: 300px; background: gray;"></textarea>';
	$ret .= '<input type="submit" id="bulk_convert" onclick="bulk_convert();" value="bulk_convert" />';
	$ret .= '<textarea id="bulk_out" style="height: 300px;"></textarea>';

	$ret .= '<input type="submit" id="bulk_cmd" onclick="bulk_cmd();" value="bulk_cmd" />';
	$ret .= '<textarea id="bulk_cmd_out" style="height: 300px;"></textarea>';

	// chatgpt用 prompt作成
	$ret .= '<textarea id="bulk_prompt_set" style="height: 300px; background: lightskyblue;"></textarea>';
	$ret .= '<input type="submit" id="bulk_prompt_convert" onclick="bulk_prompt_convert();" value="bulk_prompt_convert" />';
	$ret .= '<textarea id="bulk_prompt_out" style="height: 300px;"></textarea>';

	$ret .= '</div>';
?>

<style>
#btn_set_memo { color: red; }

</style>

<script>
const ai_prompt = "このタイトルのブログ記事のh2見出しを5個用意して下さい。\n日本語30文字以内でお願いします。";
const cmd_1 = 'sudo chmod 777 ';
const cmd_2 = 'sudo cat /home/tmp_github/tools/tmp/code_qiita.md /home/tmp_github/tools/article.log /home/tmp_github/tools/tmp/templates/ad_2.md > ./{file} && '
			 + 'sudo sh /home/tmp_github/tools/tmp/ch_code_qiita.sh {file} "{title}" "{keyword}" && '
			 + 'sudo sh /home/tmp_github/tools/tmp/ch_code_qiita.sh {file} "{title}" "{keyword}" && '
			 + 'sudo sh /home/tmp_github/tools/tmp/tools_ch_type.sh {file}';

/**
 *
 **/
function set_memo() {
	const file_md = document.getElementById("file_md");
	const file_title = document.getElementById("file_title");
	const export_area = document.getElementById("export_area");
	const export_cmd = document.getElementById("export_cmd");

//  alert(file_md.value);
	export_area.value = file_title.value + '\n' + ai_prompt;

	export_cmd.value = 'sudo chmod 777 ' + file_md.value;
}

/**
 *
 **/
function copy_clipboard(id = null) {
	// クリップボードコピー
	const export_area_value = document.getElementById(id).value;
	navigator.clipboard.writeText(export_area_value);
}

/**
 *
 **/
function bulk_convert() {
	// 記事要素 複数変換
	const bulk_set = document.getElementById("bulk_set");
	const bulk_out = document.getElementById("bulk_out");
	const bs = bulk_set.value.split('\t').filter(Boolean); // タブ区切りして、空要素除去
//	console.log(bs);
//	console.log(ai_prompt);
//	console.log(cmd_1);

	let bo = [];
	bs.forEach((element) => 
		bo.push(element.replace(/\n/g, ""))
	);
//	console.log(bo);
	const out = bo[2] + '\n' + ai_prompt + '\n\n'
		+ bo[4] + '\n' + ai_prompt + '\n\n'
		+ bo[6] + '\n' + ai_prompt + '\n\n';

	bulk_out.value = out;
}

/**
 *
 **/
function bulk_cmd() {
	// 記事要素 複数変換
	const bulk_set = document.getElementById("bulk_set");
	const bulk_out = document.getElementById("bulk_out");
	const bs = bulk_set.value.split('\t').filter(Boolean); // タブ区切りして、空要素除去
//	console.log(bs);
//	console.log(ai_prompt);
//	console.log(cmd_1);

	let bo = [];
	bs.forEach((element) => 
		bo.push(element.replace(/\n/g, ""))
	);
//	console.log(bo);

	cmds = 	cmd_2.replace(/{file}/g, bo[1]).replace(/{title}/g, bo[2]).replace(/{keyword}/g, bo[0])
	console.log(cmds);

	const out = cmd_1 + bo[1] + '\n\n'
		+ cmd_2.replace(/{file}/g, bo[1]).replace(/{title}/g, bo[2]).replace(/{keyword}/g, bo[0]) + '\n\n'
		+ cmd_1 + bo[3] + '\n\n'
		+ cmd_2.replace(/{file}/g, bo[3]).replace(/{title}/g, bo[4]).replace(/{keyword}/g, bo[0]) + '\n\n'
		+ cmd_1 + bo[5] + '\n\n'
		+ cmd_2.replace(/{file}/g, bo[5]).replace(/{title}/g, bo[6]).replace(/{keyword}/g, bo[0]) + '\n\n';

	bulk_cmd_out.value = out;
}

/**
 *
 **/
function bulk_prompt_convert() {
	// 記事要素 複数変換
	const bset = document.getElementById("bulk_prompt_set");
	const bout = document.getElementById("bulk_prompt_out");
	const bs = bset.value.split('\t').filter(Boolean); // タブ区切りして、空要素除去
//	console.log(bs);

	let bo = [];
	bs.forEach((element) => 
		bo.push(element.replace(/\n/g, ""))
	);
	console.log(bo);
/*
	const out = bo[2] + '\n' + ai_prompt + '\n\n'
		+ bo[4] + '\n' + ai_prompt + '\n\n'
		+ bo[6] + '\n' + ai_prompt + '\n\n';
*/

	const url = bo[1];
	const title = bo[2];
	const keyword = bo[0];
	const keywords = keyword.split(',');
	
	const about = keywords[0];
	const who = '初心者エンジニア';
	var target = '　' + about + ' について' + who + 'を対象';

	var start = '「こんにちは。今回は、\n';
	start += about + 'について' + who + '\n';
	start += 'に向けて、」';

	const headers = bo[3] + '\n'
				  + bo[4] + '\n'
				  + bo[5] + '\n'
				  + bo[6] + '\n'
				  + bo[7];


	var out = url + '\n';
	out += '\n';

	out += '・下記の条件でタイトルと、ブログ記事を書いてください。\n';
	out += title + '\n';
	out += '\n';

	out += '・キーワードを下記にしてください。\n';
	out += keyword + '\n';
	out += '\n';

	out += '・対象者を下記にしてください。\n';
	out += target + '\n';
	out += '\n';
	out += '\n';

	out += '・日本語で、必ず3,000文字以上で作ってください。\n';
	out += '\n';


	out += '冒頭は、下記で作成してください。\n';
	out += start + '\n';
	out += '\n';

	out += '・参考となるブログ記事のURLを2個以上掲載してください。\n';
	out += '\n';

	out += '・見出しには下記を使ってください。(見出しにはの行頭には ## このタグを置いてください。)\n';
	out += headers + '\n';
	out += '\n';

	out += '・サンプルコードを各見出しに用意してください。\n';
	out += '\n';

	out += '・markdown表記してください。\n';
	out += '\n';

	bout.value = out;

}
</script>

<?php
	foreach ($test_array as $i => $d) {
//		$ret .= '<div style="color: blue;">'. $d. '</div>';
	}
	return $ret;

}
add_shortcode('test1','set_shortcode');

/**
 * webhook to slack
 * 
 **/
function webhook_to_slack($str = null) {
	$dt = date('Y-m-d H:i:s');
	$cmd = sprintf('curl -X POST --data \'{"text":"message from hack-note.com: %s. \n %s. "}\'', $dt, $str);
	$webhook_json = dirname(__DIR__). '/stock-screening/webhook.json';
	$webhooks = file_get_contents($webhook_json);
	$webhook = (object) json_decode($webhooks, true);
	$slack = $webhook->slack;
	exec($cmd. ' '. $slack);
	return true;
}

/**
 * webhook keepa to slack
 * 
 **/
function webhook_keepa_to_slack() {
	$dt = date('Y-m-d H:i:s');
	$cmd = sprintf('curl -X POST --data \'{"text":"test_message from hack-note.com: %s."}\'', $dt);
	$webhook_json = dirname(__DIR__). '/stock-screening/webhook.json';
	$webhooks = file_get_contents($webhook_json);
	$webhook = (object) json_decode($webhooks, true);
	$slack = $webhook->slack;
	exec($cmd. ' '. $slack);
	return true;
}
add_shortcode('webhook-kts','webhook_keepa_to_slack');

$StockScreening = new StockScreening;
