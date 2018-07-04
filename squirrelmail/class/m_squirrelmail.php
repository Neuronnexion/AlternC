<?php
/*
  ----------------------------------------------------------------------
  AlternC - Web Hosting System
  Copyright (C) 2000-2012 by the AlternC Development Team.
  https://alternc.org/
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
  Purpose of file: Manage Squirrelmail webmail configuration
  ----------------------------------------------------------------------
*/

/**
* This class handle squirrelmail's webmail
* hook the main panel page to add a link to the webmail
*/
class m_squirrelmail {

  /* ----------------------------------------------------------------- */
  /** Hook called by the homepage or the /webmail link
   * to redirect the user to a known webmail url.
   * the variable 'webmail_redirect' tells which webmail has the priority. 
   * @return string the URL of the webmail
   */
  function hook_admin_webmail() {
    global $db;
    // Search for the domain where the panel is hosted, then search for a webmail in it.
    $i=2;
    if (!empty($_SERVER["HTTP_HOST"]))  { 
      do { // for each domain part (search panel.alternc.org then alternc.org then org, if the current panel is at www.panel.alternc.org)
	$expl=explode(".",$_SERVER["HTTP_HOST"],$i);
	if (count($expl)>=2) {
	  list($host,$dompart)=$expl;
	  // We search for a 'squirrelmail' subdomain in that domain
	  $db->query("SELECT * FROM sub_domaines s WHERE s.domaine= ? AND s.type='squirrelmail';",array($dompart));
	  if ($db->next_record()) {
	    $domain=$db->Record;
	    return "http://".$domain["sub"].(($domain["sub"])?".":"").$domain["domaine"];
	  }
	}
	$i++;
      } while (strpos($dompart,'.')!==false);
    }

    // not found: search for a webmail in the admin user account
    $db->query("SELECT * FROM sub_domaines s WHERE s.compte=2000 AND s.type='squirrelmail';");
    if ($db->next_record()) {
      $domain=$db->Record;
      return "http://".$domain["sub"].(($domain["sub"])?".":"").$domain["domaine"];
    }

  }


   /* ----------------------------------------------------------------- */
  /** Hook called when an email is REALLY deleted (by the cron, not just in the panel) 
   * @param mail_id integer the ID of the mail in the AlternC database
   * @param fullmail string the deleted mail himself in the form of john@domain.tld
   * @return boolean|null
   */
  function hook_mail_delete_for_real($mail_id, $fullmail) {
    $fullmail2 = str_replace('@','_',$fullmail); // fullname with _ instead of @ (compatibility)
    $todel = array ( 
      "$fullmail.abook", 
      "$fullmail.pref",
      "$fullmail2.abook", 
      "$fullmail2.pref");

    foreach ( $todel as $t ) {
      if (file_exists("/var/lib/squirrelmail/data/$t") ) {
        @unlink("/var/lib/squirrelmail/data/$t");
      }
    }
  } // hook_mail_delete_for_real



} /* Class Squirrelmail */

