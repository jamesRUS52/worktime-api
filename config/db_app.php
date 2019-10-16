<?php
$config = \jamesRUS52\phpfrm\Configuration::getInstance();
return [
    'connection_string' => $config->db_type.':host='.$config->db_host.';port='.$config->db_port.';dbname='.$config->db_database,
    'user'=>$config->db_user,
    'password'=>$config->db_password,
    'conn_opt' => array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            # All sessions will be saved in RDBMS while you don't kill them or restart httpd
            # Diff for localhost DB to coonect to database instance aprox 5 ms
            #,\PDO::ATTR_PERSISTENT => true
            ),
    'init_sql' => array (
        "set lc_time='ru_RU.utf8';"
    )
];

