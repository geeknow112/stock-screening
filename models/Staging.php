<?php
/**
 * Staging.php short discription
 *
 * long discription
 *
 */
require_once(dirname(__DIR__). '/library/Ext/Model/Base.php');
/**
 * StagingClass short discription
 *
 * long discription
 *
 */
class Staging extends Ext_Model_Base {
	protected $_name = 'staging';

	/**
	 * 
	 **/
	protected $_stagings;

	/**
	 * 
	 **/
	function __construct() {
//		$this->_Stagings = $this->getPartsStagingCodes();
	}

	/**
	 * 
	 **/
	public function getStagings() {
		return $this->_stagings;
	}

	/**
	 * 
	 **/
	public function getValidElement($step_num = null) {

		$step1 = array(
			'rules' => array(
/*
				'arrival_dt'				=> 'required|max:100',
				'outgoing_warehouse'		=> 'required|max:100',

				'apply_service'				=> 'required|max:100',
				'apply_plan'				=> 'required|max:100',

				'biz_fg'					=> 'required|max:100',
				'biz_number'				=> 'required|regex:/^[0-9]{13}+$/i',
				'company_name'				=> 'required|max:100',
				'company_name_kana'			=> 'required|max:100|regex:/^[ァ-ヶｦ-ﾟー]+$/u',
				'zip'						=> 'required|max:100',
				'pref'						=> 'required|max:100',
				'addr'						=> 'required|max:100',
				'addr2'						=> 'required|max:100',
				'addr3'						=> 'max:100',
				'addr_kana'					=> 'required|max:100|regex:/^[ァ-ヶｦ-ﾟー]+$/u',
				'tel'						=> 'required|max:100',
				'fax'						=> 'max:100',
				'est_dt'					=> 'required|max:100',
				'num_employ'				=> 'required|max:100',
				'capital'					=> 'required|max:100',
				'annual_sales'				=> 'max:100',
				'goods_class'				=> 'required|max:100',
				'goods'						=> 'required|max:100',
				'delivery_company'			=> 'max:100',
				'url'						=> 'max:100',

				'name'                  => 'required|max:2',
				'email'                 => 'required|email',
				'password'              => 'required|min:6',
				'confirm_password'      => 'required|same:password',
				'avatar'                => 'required|uploaded_file:0,500K,png,jpeg',
				'skills'                => 'array',
				'skills.*.id'           => 'required|numeric',
				'skills.*.percentage'   => 'required|numeric'
*/
			), 
			'messages' => array(
				'name.required' => 'ユーザー名を入力してください',
				'name.string' => '正しい形式で入力してください',
				'name.max' => '文字数をオーバーしています。',
				'email.required' => 'メールアドレスを入力してください。',
				'email.email' => '正しい形式でメールアドレスを入力してください',
				'email.max' => '文字数をオーバーしています。',
				'email.unique' => '登録済みのユーザーです',
				'password.required' => 'パスワードを入力してください',
				'password.min' => 'パスワードは8文字以上で入力してください。',
				'password.confirmed' => 'パスワードが一致しません。',
			)
		);

		return $step1;
	}

	/**
	 * 商品情報一覧取得
	 **/
	public function getList($get = null) {
		$get = (object) $get;
		global $wpdb;
		$cur_user = wp_get_current_user();

		$sql  = "SELECT g.*, g.name AS goods_name ";
		$sql .= "FROM yc_goods AS g ";
		$sql .= "WHERE g.goods is not null ";

		if (current($cur_user->roles) != 'administrator') {
//			$sql .= "AND ap.mail = '". $cur_user->user_email. "'";
		}

		if (empty($get->action)) {
//			$sql .= "ORDER BY ap.rgdt desc";
			$sql .= ";";

		} else {
			if ($get->action == 'search') {
				if (!empty($get->s['no'])) { $sql .= sprintf("AND g.goods = '%s' ", $get->s['no']); }
				if (!empty($get->s['goods_name'])) { $sql .= sprintf("AND g.name LIKE '%s%s' ", $get->s['goods_name'], '%'); }
//				$sql .= "ORDER BY g.goods desc";
				$sql .= ";";

			} else {
//				$sql .= "AND ap.applicant = '". $prm->post. "';";
			}
		}
		$rows = $wpdb->get_results($sql);
		return $rows;
	}

	/**
	 * 銘柄情報詳細取得
	 **/
	public function getDetail($get = null) {
		$get = (object) $get;
		$cur_user = wp_get_current_user();

		// 商品IDで検索して商品情報を取得するSQL
		$sql = "select max(date) as max_date from s4042 limit 10;";
//		$sql  = "SELECT s.* FROM stocks.s4042 as s ";
//		$sql .= "WHERE s.id = '". $get->sales. "'";

		if (current($cur_user->roles) != 'administrator') {
//			$sql .= "AND ap.mail = '". $cur_user->user_email. "'";
		}

//		$sql .= "LIMIT 1;";

		// SQL文を実行する
		$data_set = $this->getDB()->query($sql);

		// 扱いやすい形に変える
		$result = [];
		while($row = $data_set->fetch_assoc()){
			$rows[] = $row;
		}

/*
		// 配列整形
		foreach ($rows as $i => $d) {
			$ret[str_replace('-', '_', $d->meta_key)] = $d->meta_value;
		}
*/
		return $rows[0];
	}

	/**
	 * DBコネクタを取得
	 **/
	public function getDB() {
		// DBコネクタを生成
		$host = 'localhost';
		$username = 'root';
		$password = 'aErQl0cbmYmO';
		$dbname = 'stocks';

		$mysqli = new mysqli($host, $username, $password, $dbname);
		if ($mysqli->connect_error) {
			error_log($mysqli->connect_error);
			exit;
		}
		return $mysqli;
	}

	/**
	 * 株予報情報取得
	 **/
	public function getStockReportFromWebSite() {
		$i = 0;
		$stocks = $this->getStocks();
		foreach ($stocks as $stock => $sname) {
			$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_ts&bcode='. $stock;
			$file = dirname(__FILE__). '/../download/'. $stock. '.html';
			$cur = file_get_contents($url);
			file_put_contents($file, $cur);
		}
	}

	/**
	 * 株予報情報を整形
	 **/
	public function convertStockReport() {
		$stocks = $this->getStocks();
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
		return array($html, $ret, $result);
	}

	/**
	 * 株予報情報整形結果をバックアップ
	 **/
	public function backupPriceBottomData($result = null) {
		$json = dirname(__FILE__). '/../download/bottom.json';
		file_put_contents($json, json_encode($result));
		return $json;
	}

	/**
	 * 結果を表示用に再取得
	 **/
	public function refetchPriceBottomData($json = null) {
		$g_json = file_get_contents($json);
		$g_result = json_decode($g_json);
		return $g_result;
	}

	/**
	 * 配当情報取得
	 **/
	public function getDividendInfoFromWebSite($g_result = null) {
		foreach ($g_result as $stock) {
			$code = $stock[0];
			$url = 'https://kabuyoho.ifis.co.jp/index.php?action=tp1&sa=report_zim&bcode='. $code;
			$f = dirname(__FILE__). '/../download/haitou/'. $code. '_bottom.html';
			$cur = file_get_contents($url);
			file_put_contents($f, $cur);
			sleep(1);
		}
	}

	/**
	 * 配当情報を整形
	 **/
	public function convertDevidendInfo($g_result = null) {
		$stocks = $this->getStocks();
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
			$result[$code] = str_replace('%', '', current($haitou));
		}

		// 配当率で降順
		arsort($result);
		//$this->vd($result);exit;

		foreach ($result as $stock => $haitou) {
//			echo sprintf('<table><tr><td>%s</td><td>%s</td><td>%s</td></tr></table>', $stock, $stocks[$stock], $haitou);
		}

		return $result;
	}

	/**
	 * 
	 **/
	public function getInitForm() {
		return array(
			'select' => array(
				'goods_name' => $this->getPartsGoodsName(), 
			)
		);
	}

	/**
	 * 「品名」
	 **/
	private function getPartsGoodsName() {
		global $wpdb;
		$sql  = "SELECT g.goods, g.name, g.separately_fg FROM yc_goods as g ";
		$sql .= ";";
		$rows = $wpdb->get_results($sql);

		// 配列整形
		$ret[0] = '';
		$separately = null;
		foreach ($rows as $i => $d) {
			$ret[$d->goods][0] = '';
			if ($d->separately_fg == true) { $separately = " （バラ）"; }
			$ret[$d->goods] = sprintf("%s%s", $d->name, $separately);
		}

		return $ret;
	}
}
?>
