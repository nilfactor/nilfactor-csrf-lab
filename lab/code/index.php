<?php
/**
 * @brief This is script which shows the vulnerabilties of a CSRF Attack.
 *        I debated using templates like smarty and decided to just return html
 *        I also thought about a MVC for this, but again it's just an example
 *        so I went the quickest route which makes for some embarassing code
 * @author Richard Williamson <richard@nilfactor.com>
 * @website http://nilfactor.com/
 */
session_start();

$user = [
    'user_name'  => '',
    'password'   => '',
    'first_name' => '',
    'last_name'  => '',
    'email'      => '',
    'bio'        => '',
];

if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user['user_name']) {
        $user['user_name'] = $_REQUEST['user_name'] ?: '';
    }
    $user['password'] = $_REQUEST['password'] ?: '';
    $user['first_name'] = $_REQUEST['first_name'] ?: '';
    $user['last_name'] = $_REQUEST['last_name'] ?: '';
    $user['email'] = $_REQUEST['email'] ?: '';
    $user['bio'] = $_REQUEST['bio'] ?: '';

}

$_SESSION['user'] = $user;
$disabled = !empty($user['user_name']) ? 'disabled ' : '';

/**
 * I was going to use a templating engine, but I decided for just a simple example it was over kill
 * Also note worthy as we are talking about security there is no escaping here so you could do html
 * injection with this example... becareful if reusing this for some reason for something important
 */
$html = <<<html
<!DOCTYPE html>
<html>
    <head>
        <title>CSRF Attack Lab</title>
    </head>
    <body>
        <form action="/index.php" method="post">
            Username: <input type="text" name="user_name" value="{$user['user_name']}" {$disabled}/><br />
            Password: <input type="password" name="password" value="{$user['password']}" /><br />
            First Name: <input type="text" name="first_name" value="{$user['first_name']}" /><br />
            Last Name: <input type="text" name="last_name" value="{$user['last_name']}" /><br />
            Email: <input type="text" name="email" value="{$user['email']}" /><br />
            Bio: <br />
            <textarea name="bio">{$user['bio']}</textarea><br />
            <input type="submit" value="Save" />
        </form>
    </body>
</html>
html;

echo $html;