<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

# Fill in corresponding database information below, and rename this file as
# TKDDB.php

$hostname_TKDDB = "fill_in_db_hostname_here";
$database_TKDDB = "fill_in_db_name_here";
$username_TKDDB = "fill_in_db_username_here";
$password_TKDDB = "fill_in_db_password_here";
$TKDDB = mysql_pconnect($hostname_TKDDB, $username_TKDDB, $password_TKDDB) or trigger_error(mysql_error(),E_USER_ERROR);
?>
