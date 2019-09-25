<?php

/*if (!defined('CMSIMPLE_VERSION') || preg_match('#/database/index.php#i',$_SERVER['SCRIPT_NAME'])) 
{
    die('no direct access');
}*/


define("FORM_CONTENT_BASE", $pth["folder"]["content"]);

define("FORM_MAIL_BASE", $pth["folder"]["plugin"]);
define("FORM_MAIL_PATH", $plugin_cf["form_mail"]["form_mail_path"]);

// define("FORM_MAIL_MAIL_TARGET", $plugin_cf["form_mail"]["mail_target"]);
define("FORM_MAIL_MAIL_SENDER", $plugin_cf["form_mail"]["mail_sender"]);
define("FORM_MAIL_MAIL_ADDRESS", $plugin_cf["form_mail"]["mail_address"]);
define("FORM_MAIL_MAIL_SUBJECT", $plugin_cf["form_mail"]["mail_subject"]);

define("FORM_MAIL_SENT", $plugin_tx["form_mail"]["mail_sent"]);
define("FORM_MAIL_SENT_FAIL", $plugin_tx["form_mail"]["mail_sent_fail"]);


include_once FORM_MAIL_BASE . "lib/class.block.php";
include_once FORM_MAIL_BASE . "lib/class.sender.php";
include_once FORM_MAIL_BASE . "lib/class.selector.php";



// plugin to create a form and send the result to an email address

function form_mail($form="", $template="") {

	global $get, $admin, $action, $database, $_SESSION, $onload, $sn, $su, $f;


	// memberaccess integration
	if(class_exists("ma\Access")) {
		define("FORM_MAIL_ACCESS_SUPPORT", true);
	}
	else {
		define("FORM_MAIL_ACCESS_SUPPORT", false);
	}


	$ret = "";
	$ret_send = "";


	// return script include
	$ret .= '<script type="text/javascript" src="' . FORM_MAIL_BASE . 'script/form_mail.js"></script>';

	// add to onload
	$onload .= "form_mail_init();";


	$ret .= '<form method="post">';

	// create form definition path
	$path = FORM_CONTENT_BASE . FORM_MAIL_PATH . "/" . $form;

	if (file_exists($path . ".ini")) {

		$selector = new Form_Mail_selector($path);
		$ret .= $selector->render();

		// load form definition
		$form_ini = parse_ini_file($path . ".ini", true);

		$formid = false;

		$block_idx = 0;

		// create global block
		$global = new Mail_form_block();
		$b = new Mail_form_block();


		// iterate form lines
		foreach ($form_ini as $text => $block) {

			// load settings
			if ($text == "_settings") {
				$settings = $block;
			}

			// set block
			else {

				$b->set($text, $block);
				$ret .= $b->render();
			}

		}


		// add submit button
		$ret .= '<p><input type="submit" value="absenden"></p>';
		$ret .= '<input name="action" type="hidden" value="form_mail_send">';

		$ret .= "</form>";

	}

	else {
		$ret .= '<div class="xh_fail">Form definition not found</div>';
	}


	// EXECUTE SEND ACTION
	if (isset($_POST["action"]) && $_POST["action"] == "form_mail_send") {


		// no setting in form definition
		// use global settings
		if (!$settings) {

			$settings = [
				// "target" => "",
				"sender" => FORM_MAIL_MAIL_SENDER,
				"address" => FORM_MAIL_MAIL_ADDRESS,
				"subject" => FORM_MAIL_MAIL_SUBJECT
			];
		}

		$sender = new FormMailSender($settings["sender"], $form);
		$sender->set_key_names(["Frage","Antwort"]);
		$sender->add_data($_POST);

		$res = $sender->send($settings["address"], $settings["subject"]);


		if ($res) {

			$ret_send .= '<div class="xh_info">' . FORM_MAIL_SENT . '</div>';
		}
		else {
			$ret_send .= '<div class="xh_warning">' . FORM_MAIL_SENT_FAIL. '</div>';
		}

		// create remember string
		$remember = $_POST;
		$remember["action"] = "ma_remember";

		foreach ($remember as $key => $val) {
			$rem[] = $key . "=" . $val;
		}


		// return link
		$ret_send .= '<p><a href="?' . $su . '&' . implode("&", $rem) . '">neue Bewertung</a></p>';

		return $ret_send;
	}


	// return form
	else {
		return $ret;
	}
}


?>
