<?php

namespace App\Helper\Hasher;

use Illuminate\Contracts\Hashing\Hasher;

class Md5Hasher implements Hasher
{
    public function check($value, $hashedValue, array $options = [])
    {

        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    public function make($value, array $options = [])
    {
        $value = env('SALT', '').$value;

        return md5($value);  //这里写你自定义的加密方法
    }
    public function info($hashedValue)
    {
        // TODO: Implement info() method.
    }

}
