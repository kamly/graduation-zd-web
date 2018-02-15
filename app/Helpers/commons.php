<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/13
 * Time: 下午7:52
 */


/**
 * 解密
 */
function message_decrypt($message)
{
    if (empty($message)) {
        return [];
    }

    // 类似 des 解密
    $message = openssl_decrypt($message, 'des-cbc', config('myConfig.des_key'), 0, config('myConfig.des_iv'));

    // aes解密
    $message = explode("----", $message);
    if (count($message) == 2) {
        $message = json_decode(openssl_decrypt(base64_decode($message[0]), 'AES-256-CBC', config('myConfig.token_base'), false, base64_decode($message[1])), true);
        return $message;
    }
    return [];
}

/*
 * 加密
 */
function message_encrypt($message)
{
    if (empty($message)) {
        return '';
    }

    // 类似 aes 加密
    $iv = openssl_random_pseudo_bytes(16);
    $message = openssl_encrypt(json_encode($message), 'AES-256-CBC', config('myConfig.token_base'), false, $iv);
    $message = base64_encode($message) . "----" . base64_encode($iv);

    // des加密
    $message = openssl_encrypt($message, 'des-cbc', config('myConfig.des_key'), 0, config('myConfig.des_iv'));

    return $message;
}


/*
 * 写日志
 */
function write_log($message, $filename = 'api')
{
    $filepath = storage_path() . '/logs/' . $filename . '.log';
    if (!file_exists($filepath)) {
        $newfile = TRUE;
    }
    if (!$fp = @fopen($filepath, 'ab')) {
        return FALSE;
    }
    $message = $message . "\n";

    flock($fp, LOCK_EX);
    for ($written = 0, $length = strlen($message); $written < $length; $written += $result) {
        if (($result = fwrite($fp, substr($message, $written))) === FALSE) {
            break;
        }
    }
    flock($fp, LOCK_UN);

    fclose($fp);
    if (isset($newfile) && $newfile === TRUE) {
        chmod($filepath, 0644);
    }
}

/*
 * 写渲染日志
 */
function write_log_render($type = 'access')
{
    $response_time = intval(microtime(TRUE) - $_SERVER['REQUEST_TIME_FLOAT']);

    $user_id = null;
    if (\Auth::id()) {
        $user_id = \Auth::id();
    }

    if (request()->route()) {
        $action = request()->route()->getAction()['controller'];
    } else {
        $action = 'error_action';
    }
    
    $json = [
        'time' => date('c'),
        'response_time' => $response_time,
        'user_id' => $user_id,
        'ip_address' => request()->getClientIp(),
        'action' => $action,
        'method' => $_SERVER['REQUEST_METHOD'],
        'url' => get_protocol() . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    ];
//    write_log(json_encode($json), $type);
}

/*
 * 获取http 还是 https
 */
function get_protocol()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https' : 'http';
    } else {
        $protocol = 'http';
    }
    return $protocol;
}
