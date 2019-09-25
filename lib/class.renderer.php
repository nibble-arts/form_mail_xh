<?php

class Form_mail_renderer {


	// render title
	static function title($text) {

		// display block title
		return '<h4>' . $text . '</h4>';
	}


	// render string
	static function string($text) {

		return '<p>' . $text . '</p>';
	}


	// render text
	static function text($block) {

		$ret = Form_mail_renderer::title($block->text());

		$ret .= '<textarea ' . $block->name(true) . $block->mandatory(true) . $block->rows(true) . $block->cols(true) . '>';
		$ret .= '</textarea>';

		return $ret;
	}


	// render text
	static function input($block) {

		$val = "";
		$ret = '<div class="form_mail_label">' . $block->text() . '</div>';

		// insert remembered value
		if ($block->remember() and isset($_GET[$block->name()])) {
			$val = $_GET[$block->name()];
		}

		// create input field
		$ret .= '<input type="text"' . $block->name(true) . ' class="form_mail_input';


			// add mandatory class
			if ($block->mandatory()) {

				// if remembered value, set ok
				if ($val) {
					$ret .= ' form_mail_mand_ok';
				}

				else {
					$ret .= ' form_mail_mandatory';
				}
			}

			$ret .= '"';
			// end class 


			// add size and check
			$ret .= $block->size(true);
			$ret .= $block->check(true);

		// end input field
			$ret .= ' value="' . $val . '"';
		$ret .= '>';

		return $ret;
	}


	static function global_header($options) {

		$ret = "";
		
		// create global header if global options exist
		if (is_array($options) && count($options)) {

			$ret .= '<tr>';
			$ret .= '<td class="form_mail_text">&nbsp;</td>';


			// show global options header
			foreach ($options as $option) {

				$ret .= '<td class="form_mail_head">' . $option . '</td>';
			}

			$ret .= '</tr>';
			// header end
		}

		return $ret;
	}
}