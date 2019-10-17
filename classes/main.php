<?php

namespace fm;


class Main {

	public static function init($config, $text) {

		Session::load();
		Config::init($config["form_mail"]);
		Text::init($text["form_mail"]);

	}
}