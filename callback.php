<?php
    include "autoload.php";

    $tokenURL = 'https://github.com/login/oauth/access_token';
    $apiURLBase = 'https://api.github.com';

    session_start();

    if(get('code')) {
        if(!get('state') || $_SESSION['state'] != get('state')) {
            header('Location: ' .env('APP_URL'));
            die();
        }
        $token = apiRequest($tokenURL, array(
            'client_id' => env('OAUTH2_CLIENT_ID'),
            'client_secret' => env('OAUTH2_CLIENT_SECRET'),
            'state' => $_SESSION['state'],
            'User-Agent' => env('APP_NAME'),
            'code' => get('code')
        ));
        $_SESSION['access_token'] = $token->access_token;
        header('Location: ' . $_SERVER['PHP_SELF']);
    }

    function apiRequest($url, $post=FALSE, $headers=array())
    {
        $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $headers[] = 'Accept: application/json';
        if(session('access_token'))
            $headers[] = 'Authorization: Bearer ' . session('access_token');
            $headers[] = 'User-Agent:' . env('APP_NAME');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    function get($key, $default=NULL) 
    {
        return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
    }

    function session($key, $default=NULL) 
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo env('APP_NAME'); ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-5 mx-auto">
                <div id="first">
                    <div class="myform form ">
                        <div class="logo mb-3">
                            <div class="col-md-12 text-center">
                                <?php if(session('access_token')) {
                                    $response = apiRequest($apiURLBase. '/user');
                                ?>
                                <h3>Logged In</h3>
                                        <a href="<?php echo env('APP_URL'); ?>">
                                            <div class="col-md-12 text-center ">
                                                <button class=" btn btn-block mybtn btn-primary tx-tfm fa fa-home"> Home</button>
                                            </div>
                                        </a>
                                    <pre>
                                        <?php print_r($response); ?>
                                    </pre>
                                <?php } else { ?>
                                    <h3>Not logged in</h3>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>