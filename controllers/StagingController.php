<?php
/**
 * StagingController.php short discription
 *
 * long discription
 *
 */
use eftec\bladeone\BladeOne;
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

		switch ($get->action) {
			case 'confirm' :
				$php_file = '/home/students/htdocs/index.php';
				$php = '<?php'. PHP_EOL;
				$php .= $post->customer_name;
				$php .= PHP_EOL. '?>'. PHP_EOL;
				file_put_contents($php_file, $php);
				break;
		}
		echo $this->get_blade()->run("staging-detail");
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
