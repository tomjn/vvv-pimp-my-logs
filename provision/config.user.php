<?php
/*! pimpmylog - 1.7.14 - 025d83c29c6cf8dbb697aa966c9e9f8713ec92f1*/
/*
 * pimpmylog
 * http://pimpmylog.com
 *
 * Copyright (c) 2017 Potsky, contributors
 * Licensed under the GPLv3 license.
 */

require_once('yaml.php');

$logs = [
	"php1" => [
		"display" => "Default PHP Error Log",
		"path" => '/srv/log/php7.0_errors.log',
		"refresh" => 10,
		"max" => 50,
		"notify" => true,
		"format" => [
			"type" => "PHP",
			"regex" => "@^\\[(.*)-(.*)-(.*) (.*):(.*):(.*)( (.*))*\\] ((PHP (.*):  (.*) in (.*) on line (.*))|(.*))$@U",
			"export_title" => "Error",
			"match" => [
				"Date" => [ 2 , " " , 1 , " " , 4 , ":" , 5 , ":" , 6 , " " , 3 ],
				"Severity" => 11,
				"Error" => [ 12 , 15 ],
				"File" => 13,
				"Line" => 14
			],
			"types" => [
				"Date" => "date:H:i:s",
				"Severity" => "badge:severity",
				"File"     => "pre:\/-69",
				"Line"     => "numeral",
				"Error"    => "pre"
			],
			"exclude" => [
				"Log"=> ["\\/PHP Stack trace:\\/", "\\/PHP *[0-9]*\\. \\/"]
			]
		]
	]
];

$access_regex = <<<EOF
|^((\\\\S*) )*(\\\\S*) (\\\\S*) (\\\\S*) \\\\[(.*)\\\\] \"(\\\\S*) (.*) (\\\\S*)\" ([0-9]*) (.*)( \\"(.*)\\" \\"(.*)\\"( [0-9]*/([0-9]*))*)*\$|U
EOF;

$yaml = new Alchemy\Component\Yaml\Yaml();
$data = $yaml->load( (file_exists('/vagrant/vvv-custom.yml')) ? '/vagrant/vvv-custom.yml' : '/vagrant/vvv-config.yml' );

foreach ( $data['sites'] as $name => $site ) {
	if ( $name === 'wordpress-develop' ) {
		continue;
	}
	$logs['nginx_error_'.$name] = [
		"display" => $name." Nginx Error Log",
		"path" => '/srv/www/'.$name.'/log/error.log',
		"refresh" => 10,
		"max" => 50,
		"notify" => true,
		"format" => [
			"type" => "NGINX",
			"regex" => "@^(.*)/(.*)/(.*) (.*):(.*):(.*) \\\\[(.*)\\\\] [0-9#]*: \\\\*[0-9]+ (((.*), client: (.*), server: (.*), request: \"(.*) (.*) HTTP.*\", host: \"(.*)\"(, referrer: \"(.*)\")*)|(.*))\$@U",
			"export_title" => "Error",
			"match"       => [
				"Date"     => [1,"\/",2,"\/",3," ",4,":",5,":",6],
				"Severity" => 7,
				"Error"    => [10,18],
				"Client"   => 11,
				"Server"   => 12,
				"Method"   => 13,
				"Request"  => 14,
				"Host"     => 15,
				"Referer"  => 17
			],
			"types"    => [
				"Date"     => "date:d\/m\/Y H:i:s \/100",
				"Severity" => "badge:severity",
				"Error"    => "pre",
				"Client"   => "ip:http",
				"Server"   => "txt",
				"Method"   => "txt",
				"Request"  => "txt",
				"Host"     => "ip:http",
				"Referer"  => "link"
			]
		]
	];
	/*$logs['nginx_access_'.$name] = [
		"display" => $name." Nginx Access Log",
		"path" => '/srv/www/'.$name.'/log/access.log',
		"refresh" => 10,
		"max" => 50,
		"notify" => true,
		"format" => [
			"type" => "NCSA Extended",
			"regex" => $access_regex,
			"export_title" => "URL",
			"match"        => [
				"Date"    => 6,
				"IP"      => 3,
				"CMD"     => 7,
				"URL"     => 8,
				"Code"    => 10,
				"Size"    => 11,
				"Referer" => 13,
				"UA"      => 14,
				"User"    => 5
			],
			"types"=> [
				"Date"    => "date:H:i:s",
				"IP"      => "ip:geo",
				"URL"     => "txt",
				"Code"    => "badge:http",
				"Size"    => "numeral:0b",
				"Referer" => "link",
				"UA"      => "ua:{os.name} {os.version} | {browser.name} {browser.version}\/100"
			],
			"exclude"=> [
				"URL"=> ["\/favicon.ico\/", "\/\\\\.pml\\\\.php\\\\.*\$\/"],
				"CMD"=> ["\/OPTIONS\/"]
			]
		]
	];*/
}


if ( realpath( __FILE__ ) === realpath( $_SERVER["SCRIPT_FILENAME"] ) ) {
	header( $_SERVER['SERVER_PROTOCOL'].' 404 Not Found' );
	die();
}
?>
{
	"globals": {
		"_remove_me_to_set_AUTH_LOG_FILE_COUNT"         : 100,
		"AUTO_UPGRADE"                : false,
		"_remove_me_to_set_CHECK_UPGRADE"               : true,
		"_remove_me_to_set_EXPORT"                      : true,
		"FILE_SELECTOR"               : "bs",
		"_remove_me_to_set_FOOTER"                      : "&copy; <a href=\"http:\/\/www.potsky.com\" target=\"doc\">Potsky<\/a> 2007-' . YEAR . ' - <a href=\"http:\/\/pimpmylog.com\" target=\"doc\">Pimp my Log<\/a>",
		"_remove_me_to_set_FORGOTTEN_YOUR_PASSWORD_URL" : "http:\/\/support.pimpmylog.com\/kb\/misc\/forgotten-your-password",
		"_remove_me_to_set_GEOIP_URL"                   : "http:\/\/www.geoiptool.com\/en\/?IP=%p",
		"_remove_me_to_set_PORT_URL"                    : "http:\/\/www.adminsub.net\/tcp-udp-port-finder\/%p",
		"_remove_me_to_set_GOOGLE_ANALYTICS"            : "UA-XXXXX-X",
		"HELP_URL"                    : "http:\/\/pimpmylog.com",
		"_remove_me_to_set_LOCALE"                      : "gb_GB",
		"_remove_me_to_set_LOGS_MAX"                    : 50,
		"_remove_me_to_set_LOGS_REFRESH"                : 0,
		"_remove_me_to_set_MAX_SEARCH_LOG_TIME"         : 5,
		"_remove_me_to_set_NAV_TITLE"                   : "",
		"NOTIFICATION"                : true,
		"NOTIFICATION_TITLE"          : "New logs [%f]",
		"_remove_me_to_set_PIMPMYLOG_ISSUE_LINK"        : "https:\/\/github.com\/potsky\/PimpMyLog\/issues\/",
		"_remove_me_to_set_PIMPMYLOG_VERSION_URL"       : "http:\/\/demo.pimpmylog.com\/version.js",
		"PULL_TO_REFRESH"             : true,
		"_remove_me_to_set_SORT_LOG_FILES"              : "default",
		"_remove_me_to_set_TAG_DISPLAY_LOG_FILES_COUNT" : true,
		"_remove_me_to_set_TAG_NOT_TAGGED_FILES_ON_TOP" : true,
		"_remove_me_to_set_TAG_SORT_TAG"                : "default | display-asc | display-insensitive | display-desc | display-insensitive-desc",
		"_remove_me_to_set_TITLE"                       : "Pimp my Log",
		"_remove_me_to_set_TITLE_FILE"                  : "Pimp my Log [%f]",
		"_remove_me_to_set_UPGRADE_MANUALLY_URL"        : "http:\/\/pimpmylog.com\/getting-started\/#update",
		"_remove_me_to_set_USER_CONFIGURATION_DIR"      : "config.user.d",
		"_remove_me_to_set_USER_TIME_ZONE"              : "Europe\/Paris"
	},

	"badges": {
		"severity": {
			"debug"       : "success",
			"info"        : "success",
			"notice"      : "default",
			"Notice"      : "info",
			"warn"        : "warning",
			"error"       : "danger",
			"crit"        : "danger",
			"alert"       : "danger",
			"emerg"       : "danger",
			"Notice"      : "info",
			"fatal error" : "danger",
			"parse error" : "danger",
			"Warning"     : "warning"
		},
		"http": {
			"1" : "info",
			"2" : "success",
			"3" : "default",
			"4" : "warning",
			"5" : "danger"
		}
	},

	"files": <?php echo json_encode( $logs ); ?>
}
