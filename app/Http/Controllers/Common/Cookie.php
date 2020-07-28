<?php


namespace App\Http\Controllers\Common;


use League\Flysystem\Config;

class Cookie
{
    /**
     * 加密cookie
     *
     * @param  string  $plainText
     * @return string
     */
    private static function _encrypt($plainText, $key = null)
    {
        $key = $key === null ? config('secret_key') : $key;
        return openssl_encrypt($plainText, 'DES-ECB', $key, OPENSSL_RAW_DATA);
    }

    /**
     * 解密函数
     * @param $txt
     * @param  null  $key
     * @return string
     */
    public static function _decrypt($encryptedText, $key = null)
    {
        $key = $key === null ? config('secret_key') : $key;
        return openssl_decrypt($encryptedText, 'DES-ECB', $key, OPENSSL_RAW_DATA);
    }

    public static function key($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }

    /**
     * 删除cookie
     *
     * @param  array  $args
     * @return boolean
     */
    public static function del($args)
    {
        $name = $args['name'];
        $domain = isset($args['domain']) ? $args['domain'] : null;
        return isset($_COOKIE[$name]) ? setcookie($name, '', time() - 86400, '/', $domain) : true;
    }

    /**
     * 得到指定cookie的值
     *
     * @param  string  $name
     * @return string|null
     */
    public static function get($name)
    {
        return isset($_COOKIE[$name]) ? self::_decrypt($_COOKIE[$name]) : null;
    }

    /**
     * 设置cookie
     *
     * @param  array  $args
     * @return boolean
     */
    public static function set($args)
    {
        $name = $args['key'];
        $value = self::_encrypt($args['value']);
        $expire = isset($args['expire']) ? $args['expire'] : null;
        $path = isset($args['path']) ? $args['path'] : '/';
        $domain = isset($args['domain']) ? $args['domain'] : null;
        $secure = isset($args['secure']) ? $args['secure'] : 0;
        return setcookie($name, $value, $expire, $path, $domain, $secure);
    }
}