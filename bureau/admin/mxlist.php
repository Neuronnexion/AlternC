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
 *  Returns the list of mx-hosted domains to a secondary mx 
 * 
 * @copyright AlternC-Team 2000-2017 https://alternc.com/
 */

require_once("../class/config_nochk.php");

$fields = array (
	"json"    => array ("get", "boolean", "0"),
);
getFields($fields);


// Check for the http authentication
if (!isset($_SERVER['PHP_AUTH_USER'])) {
 header('WWW-Authenticate: Basic realm="MX List Authentication"');
 header('HTTP/1.0 401 Unauthorized');
 exit;
} else {
  if ($mail->check_slave_account($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
    if (!$json) {
      $mail->echo_domain_list();
    } else {
      print_r($mail->echo_domain_list("json"));
    }
  } else {
    header('WWW-Authenticate: Basic realm="MX List Authentication"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
  }
}

?>
