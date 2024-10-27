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

		switch ($get->action) {
			case 'confirm' :
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
				echo $this->get_blade()->run("staging-detail", compact('rows', 'get', 'post', 'msg'));
				break;
		}
	}

	public function getCode() {
		$php_file = '/home/students/htdocs/index.php';
		$php = file_get_contents($php_file);
		if ($php) {
			$php = str_replace('<?php', '', $php);
			$php = str_replace('?>', '', $php);
			$php = preg_replace('/\n/', '', $php);
		}
		return $php;
	}

	public function setCode($get = null, $post = null) {
		$php_file = '/home/students/htdocs/index.php';
		$php = '<?php'. PHP_EOL;
		$php .= $post->contents_code;
		$php .= PHP_EOL. '?>'. PHP_EOL;
		file_put_contents($php_file, $php);
	}
}

function get_students_area($atts) {
//	$ctl = new StagingController;

	$html .= '<h2>students area</h2>';
	$file = '/home/students/htdocs/index.html';
	$contents = file_get_contents($file);
	$html .= $contents;

	
	$php_file = '/home/students/htdocs/index.php';
	$php = exec('php '. $php_file);
//	require $php_file;
	$html .= '<h4>'. $php. '</h4>';

	$py_file = '/home/students/htdocs/index.py';
	$ret = exec('python3 '. $py_file);
	$html .= '<h4>'. $ret. '</h4>';

	$ruby_file = '/home/students/htdocs/index.rb';
	$ruby = exec('ruby '. $ruby_file);
	$html .= $ruby;

//	$ctl->listAction();
	return $html;

}
add_shortcode('students_area','get_students_area');
?>
