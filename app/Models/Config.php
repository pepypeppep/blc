<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $timestamps = false;

    // get value, return null if not found
    public static function get($key)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : null;
    }

    // set value
    public static function set($key, $value)
    {
        $config = self::where('key', $key)->first();
        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
