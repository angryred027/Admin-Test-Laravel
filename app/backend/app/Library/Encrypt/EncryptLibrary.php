<?php

declare(strict_types=1);

namespace App\Library\Encrypt;

class EncryptLibrary
{
    // ブロック暗号:ある特定のビット数のまとまりを一度に処理する
    // ストリーム暗号:データの流れ（ストリーム）を順次処理していく
    // モード:ブロック暗号アルゴリズムの繰り返し方法
    // AES:通信データを区切り、置き換え・並べ替えのセットを複数回繰り返すアルゴリズム。(AESは128bitの平文をまとめて暗号化し、128bitの暗号文を作成する。)
    // 128:鍵長のこと。128bit
    // ECB:ECBモード。平文ブロックを暗号化したものが暗号文ブロックとなる。(基本的には非推奨)
    // CBC(Cipher Block Chaining):CBCモード。直前の暗号文ブロックと平文ブロックのXOR(排他的論理和)の値を暗号化。初期化ベクトル(IV。暗号化の度に異なるランダム値)が必須。
    private const MAIL_ENCRYPT_ALG_ECB = 'AES-128-ECB'; // ECBモード
    private const MAIL_ENCRYPT_ALG_CBC = 'AES-128-CBC'; // CBCモード

    /**
     * encrypt value
     *
     * @param string $value value
     * @param bool $isCbc either using cbc mode
     * @return string encrypt value
     */
    public static function encrypt(string $value, bool $isCbc = true): string
    {
        // CBCモードで暗号化させる場合
        if ($isCbc) {
            [$cbcKey, $cbcIv] = self::generateCbcKeyAndIv();
            $output = openssl_encrypt($value, self::MAIL_ENCRYPT_ALG_CBC, $cbcKey, OPENSSL_RAW_DATA, $cbcIv);
            // $encode = mb_detect_encoding($output); // エンコード判定をするとSJISになった。
            // binaryから16進数にすると可読性のある文字列に変換出来る
            // $a = bin2hex($output);
            // $b = hex2bin($a);
            // return mb_convert_encoding($output, 'UTF-8');
            return bin2hex($output);
        } else {
            // プログラム上でやり取りする時があるなど、文字化けデータが含まれない様にする場合に利用する
            return openssl_encrypt($value, self::MAIL_ENCRYPT_ALG_ECB, self::getEmailEBCEncryptKey());
        }
    }

    /**
     * decrypt value
     *
     * @param string $value value
     * @param bool $isCbc either using cbc mode
     * @return string encrypt value
     */
    public static function decrypt(string $value, bool $isCbc = true): string
    {
        // CBCモードで暗号化させる場合
        if ($isCbc) {
            [$cbcKey, $cbcIv] = self::generateCbcKeyAndIv();
            // $output = openssl_decrypt($value, self::MAIL_ENCRYPT_ALG_CBC, $cbcKey, OPENSSL_RAW_DATA, $cbcIv);
            // return mb_convert_encoding($output, 'UTF-8');
            return openssl_decrypt(hex2bin($value), self::MAIL_ENCRYPT_ALG_CBC, $cbcKey, OPENSSL_RAW_DATA, $cbcIv);
        } else {
            // プログラム上でやり取りする時があるなど、文字化けデータが含まれない様にする場合に利用する
            return openssl_decrypt($value, self::MAIL_ENCRYPT_ALG_ECB, self::getEmailEBCEncryptKey());
        }
    }

    /**
     * create initialization vector
     *
     * @param string $value length of iv
     * @return string initialization vector
     */
    public static function createIv(int $value = 16): string
    {
        return openssl_random_pseudo_bytes($value);
    }

    /**
     * generate passphrase, initialization vector
     *
     * @return array key & initialization vector
     */
    public static function generateCbcKeyAndIv(): array
    {
        // salt and pass config
        $salt   = hex2bin(self::getEmailCBCEncryptKey());
        $pass   = self::getEmailEBCEncryptKey();

        // generate iv and key
        $keyHash = md5($pass . $salt);
        $IvHash = md5(hex2bin($keyHash) . $pass . $salt);
        $key   = hex2bin($keyHash);
        // IVは16文字である必要がある。
        $iv    = hex2bin($IvHash);
        return [$key, $iv];
    }

    /**
     * get chipher methods
     *
     * @return array
     */
    public static function getCipherMethods(): array
    {
        return openssl_get_cipher_methods(true);
    }

    /**
     * get ecb key.
     *
     * @return string
     */
    private static function getEmailEBCEncryptKey(): string
    {
        return config('myappEncrypt.email.ecb');
    }

    /**
     * get cbc key.
     *
     * @return string
     */
    private static function getEmailCBCEncryptKey(): string
    {
        // CBCモード用のキー(キー&IV生成用。16桁の文字列である必要がある。)
        return config('myappEncrypt.email.cbc');
    }
}
