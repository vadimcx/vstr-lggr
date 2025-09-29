<?php
return [

    // SQL CONNECTION
    'host' => '<ip/url>',
    'user' => '<username>',
    'password' => '<password>',
    'database' => '<database name>',

    // ADMIN
    'allowedReferer' => 'https://your-domain.com', 
    'admin_user' => 'admin',
    'admin_pass_hash' => '<hashed password from the command below>',
    // echo password_hash('newpassword', PASSWORD_DEFAULT); to generate the hash
];
?>
