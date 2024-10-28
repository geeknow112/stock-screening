<?php
/**
 * StagingController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
require_once(dirname(__DIR__). '/models/Staging.php');
require_once(dirname(__DIR__). '/library/Ext/Controller/Action.php');
/**
 * StagingControllerClass short discription
 *
 * long discription
 *
 */
class StagingController extends Ext_Controller_Action
{
	protected $_test = 'test';


	protected $_user_dir = '/home/students/htdocs/';

	/**
	 * 
	 **/
	public function tmp_construct() {
		$cur_user = wp_get_current_user();
//print_r($cur_user->user_login);
		switch ($cur_user->user_login) {
			default: 
				$this->_user_dir = '/home/students/m.horikoshi/htdocs/';
				break;

			case 'student' :
				$this->_user_dir = '/home/students/htdocs/';
				break;
		}
//print_r($this->_user_dir);
	}

	/**
	 *
	 **/
	public function listAction() {
		echo $this->get_blade()->run("staging-list");
	}

	/**
	 *
	 **/
	public function detailAction() {
		$get = (object) $_GET;
		$post = (object) $_POST;

		$this->setTb('Staging');
		$page = 'staging-detail';

		$this->tmp_construct();

		switch ($get->action) {
			case 'confirm' :
				$post->contents_code = $this->convCode($post->contents_code);
				$post->code_ruby = $this->convCode($post->code_ruby);
				echo $this->get_blade()->run("staging-detail", compact('get', 'post', 'rows', 'formPage', 'initForm'));
				break;

			case 'save':
				if (!empty($post)) {
					if ($post->cmd == 'save') {
						$msg = $this->getValidMsg();
						if ($msg['msg'] == 'success') {
//							$rows = $this->getTb()->regDetail($get, $post);
							$this->setCode($get, $post);
//							$rows->goods_name = $rows->name;
							$get->action = 'complete';

						} else {
							$rows = $post;
//							$rows->name = $post->goods_name;
							$rows->messages = $msg;
						}
					}
				}
				$post->contents_code = $this->convCode($post->contents_code);
				$post->code_ruby = $this->convCode($post->code_ruby);
				echo $this->get_blade()->run("staging-detail", compact('rows', 'get', 'post', 'msg'));
				break;

			case 'complete':
//				$prm = $tb->getPrm();
//				$rows = $tb->regDetail($prm);
print_r($post);
//				echo $this->get_blade()->run("shop-detail-complete", compact('rows', 'prm'));
				break;

			default:
//				$initForm = $this->getTb()->getInitForm();
//				$rows = $this->getTb()->getList();
				$code = $this->getCode();
				if ($code) {
					$post->contents_code = $code;
				}

				$code_ruby = $this->getCode('ruby');
				if ($code_ruby) {
					$post->code_ruby = $code_ruby;
				}

				echo $this->get_blade()->run("staging-detail", compact('rows', 'get', 'post', 'msg'));
				break;
		}
	}

	public function convCode($code = null) {
		if ($code) {
			$code = str_replace('\"', '"', $code);
			$code = str_replace('\\"', '"', $code);

			$code = str_replace("\'", "'", $code);
			$code = str_replace("\\'", "'", $code);
		}
		return $code;
	}

	public function getCode($kinds = null) {
		switch ($kinds) {
			default :
				$php_file = $this->_user_dir. 'index.php';
				break;

			case 'ruby' :
				$php_file = $this->_user_dir. 'index.rb';
				break;
		}

		$php = file_get_contents($php_file);

		switch ($kinds) {
			default :
				if ($php) {
					$php = str_replace('<?php', '', $php);
					$php = str_replace('?>', '', $php);
					$php = preg_replace('/\n/', '', $php);
				}

			case 'ruby' :
				break;
		}
		return $php;
	}

	public function setCode($get = null, $post = null) {
		$php_file = $this->_user_dir. 'index.php';
		$php = '<?php'. PHP_EOL;
		$php .= $this->convCode($post->contents_code);
		$php .= PHP_EOL. '?>'. PHP_EOL;
		file_put_contents($php_file, $php);

		$ruby_file = $this->_user_dir. 'index.rb';
		$ruby .= $this->convCode($post->code_ruby);
		file_put_contents($ruby_file, $ruby);
	}
}

function get_students_area($atts) {
	$cur_user = wp_get_current_user();
//print_r($cur_user->user_login);
	switch ($cur_user->user_login) {
		default: 
			$user_dir = '/home/students/m.horikoshi/htdocs/';
			break;

		case 'student' :
			$user_dir = '/home/students/htdocs/';
			break;
	}


//	$ctl = new StagingController;

/*
	$html .= '<h2>students area</h2>';
	$file = '/home/students/htdocs/index.html';
	$contents = file_get_contents($file);
	$html .= $contents;
*/

	$php_file = $user_dir. 'index.php';
	$html .= '<h2>phpの実行結果がここにでるよ</h2>';
	$php = exec('php '. $php_file);
//	require $php_file;
	$html .= '<div style="border: 1px solid black; margin: 30px; padding: 30px;">'. $php. '</div>';

/*
	$py_file = '/home/students/htdocs/index.py';
	$ret = exec('python3 '. $py_file);
	$html .= '<h4>'. $ret. '</h4>';
*/

	$html .= '<h2>rubyの実行結果がここにでるよ</h2>';
	$ruby_file = $user_dir. 'index.rb';
	$ruby = exec('ruby '. $ruby_file);
	$html .= '<div style="border: 1px solid black; margin: 30px; padding: 30px;">'. $ruby. '</div>';

//	$ctl->listAction();
	return $html;

}
add_shortcode('students_area','get_students_area');
?>
