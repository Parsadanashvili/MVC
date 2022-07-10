<?php

namespace Core;

use RuntimeException;

class Hash
{
    /**
     * The default cost factor.
     *
     * @var int
     */
    protected static $rounds = 10;

    /**
     * Indicates whether to perform an algorithm check.
     *
     * @var bool
     */
    protected static $verifyAlgorithm = false;

    /**
     * Get information about the given hashed value.
     *
     * @param  string  $hashedValue
     * @return array
     */
    public static function info($hashedValue)
    {
        return password_get_info($hashedValue);
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function make(string $value, array $options = [])
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, [
            'cost' => static::cost($options),
        ]);

        if ($hash === false) {
            throw new RuntimeException('Bcrypt hashing not supported.');
        }

        return $hash;
    }
    
    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     * 
     * @throws \RuntimeException
     */
    public static function check($value, $hashedValue, array $options = [])
    {
        if (static::$verifyAlgorithm && static::info($hashedValue)['algoName'] !== 'bcrypt') {
            throw new RuntimeException('This password does not use the Bcrypt algorithm.');
        }

        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    /**
     * Set the default password work factor.
     *
     * @param  int  $rounds
     * @return $this
     */
    public static function setRounds($rounds)
    {
        static::$rounds = (int) $rounds;

        return new static;
    }

    /**
     * Extract the cost value from the options array.
     *
     * @param  array  $options
     * @return int
     */
    protected static function cost(array $options = [])
    {
        return $options['rounds'] ?? static::$rounds;
    }
}