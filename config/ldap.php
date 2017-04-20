<?php
// LDAP config
$host     = '192.168.1.1';
$port     = 389;
$login    = 'domain\\administrator';
$password = 'your_password';
$use_tls  = false;
$deref_options = LDAP_DEREF_ALWAYS;
$basedn   = "dc=domain,dc=local";

//LDAP Filter
$filter   = "(&(!(userAccountControl:1.2.840.113556.1.4.803:=2))(memberof=CN=G_GLPI,OU=Grupos,DC=domain,DC=local))";
$attrs = array("samaccountname","memberof", "member", "ou", "sn", "givenname", "mail", "group", 'mobile');


$ds = ldap_connect($host, intval($port));
if ($ds) {
   ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
   ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
   ldap_set_option($ds, LDAP_OPT_DEREF, $deref_options);
   if ($use_tls) {
      if (!ldap_start_tls($ds)) {
         return false;
      }
   }
   // Auth bind
   if ($login != '') {
      $b = ldap_bind($ds, $login, $password);
   } else { // Anonymous bind
      $b = ldap_bind($ds);
   }
   if (!$b) {
    echo "Error on bind!";
    exit;
   }
}

//Make a search
$result = ldap_search($ds, $basedn, $filter,$attrs);

//Sort by samaccountname
ldap_sort($ds, $result, 'samaccountname');
$data = ldap_get_entries($ds, $result);

// Iterate over array and print data for each entry
echo "<pre>";
echo '<h2>Users report</h2>';
for ($i=0; $i<$data["count"]; $i++) {
    //echo "dn is: ". $data[$i]["dn"] ."<br />";
    echo "User: ". $data[$i]["cn"][0] ."<br />";
    if(isset($data[$i]["mail"][0])) {
        echo "Email: ". $data[$i]["mail"][0] ."<br />";
    } else {
        echo "Email: None<br />";
    }
    echo "MemberOf:" . $data[$i]["memberof"][0] ."<br /><br />";
}
// print number of entries found
echo "Number of entries found: " . ldap_count_entries($ds, $result);


// SHOW ALL DATA
echo '<h2>Dump all data</h2><pre>';
print_r($data);
echo '</pre>';


// all done? clean up
ldap_close($ds);
?>
