<?php
/*
 ----------------------------------------------------------------------
 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html
 ----------------------------------------------------------------------
*/

/**
 * list of domains hosted on this server
 * used by DNS slaves to mirror our configurations
 *
 * @copyright AlternC-Team 2000-2017 https://alternc.com/ 
 */

require_once("../class/config_nochk.php");

$fields = array (
	"integrity"    => array ("get", "boolean", "0"),
);
getFields($fields);

// Check for the http authentication
if (!isset($_SERVER['PHP_AUTH_USER'])) {
  header('WWW-Authenticate: Basic realm="Domain List Authentication"');
  header('HTTP/1.0 401 Unauthorized');
  exit;
} else {
  if ($dom->check_slave_account($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
    if (!$integrity) {
      $dom->echo_domain_list();
    } else {
      $dom->echo_domain_list(true);
    }
  } else {
    header('WWW-Authenticate: Basic realm="Domain List Authentication"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
  }
}

?>
