<?php
$config = \jamesRUS52\phpfrm\Configuration::getInstance();
return 
[
    [
        "logger"=>"file",
        "level"=>"DEBUG",
        "file"=>"app.log"
        
    ],
    [
        "logger"=>"email",
        "level"=>"ERROR",
        "host"=>$config->email_smtp_host,
        "port"=>$config->email_smtp_port,
        "user"=>$config->email_smtp_user,
        "password"=>$config->email_smtp_password,
        "subject"=>'Logger system from '.$config->app_name,
        "from_email"=>$config->email_from_email,
        "from_name"=>$config->email_from_name,
        "to"=>$config->email_support_email
    ]
    
];