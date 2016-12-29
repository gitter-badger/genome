<?php

class Anemon extends Genome {

    protected $bucket = [];
    protected $separator = "";
    protected $i = 0;

    const NS = '.';

    // Create list of namespace step(s)
    public static function step($input, $NS = null) {
        $NS = $NS ?: self::NS;
        if (is_string($input) && strpos($input, $NS) !== false) {
            $input = explode($NS, trim($input, $NS));
            $a = array_shift($input);
            $output = [$a];
            while ($b = array_shift($input)) {
                array_unshift($output, $a . $NS . $b);
            }
            return $output;
        }
        return (array) $input;
    }

    // Prevent `$x` exceeds the value of `$min` and `$max`
    public static function edge($x, $min = null, $max = null) {
        if (isset($min) && $x < $min) return $min;
        if (isset($max) && $x > $max) return $max;
        return $x;
    }

    // Set array value recursively
    public static function set(&$input, $key, $value = null) {
        $keys = explode(self::NS, $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!array_key_exists($key, $input)) {
                $input[$key] = [];
            }
            $input =& $input[$key];
        }
        $input[array_shift($keys)] = $value;
    }

    // Get array value recursively
    public static function get(&$input, $key = null, $fail = false) {
        if (!isset($key)) return $input;
        $keys = explode(self::NS, $key);
        foreach ($keys as $value) {
            if (!is_array($input) || !array_key_exists($value, $input)) {
                return $fail;
            }
            $input =& $input[$value];
        }
        return $input;
    }

    // Remove array value recursively
    public static function reset(&$input, $key) {
        $keys = explode(self::NS, $key);
        while (count($key) > 1) {
            $key = array_shift($keys);
            if (array_key_exists($key, $input)) {
                $input =& $input[$key];
            }
        }
        if (is_array($input) && array_key_exists($value = array_shift($keys), $input)) {
            unset($input[$value]);
        }
    }

    // Extend two array
    public static function extend(&$input, ...$b) {
        $input = array_replace_recursive((array) $input, ...$b);
        return $input;
    }

    // Concat two array
    public static function concat(&$input, ...$b) {
        $input = array_merge_recursive((array) $input, ...$b);
        return $input;
    }

    public static function eat($array) {
        return new Anemon($array);
    }

    public function vomit($key = null, $fail = false) {
        return $this->get($this->bucket, $key, $fail);
    }

    // Randomize array order
    public function shake($preserve_key = true) {
        if (is_callable($preserve_key)) {
            // `$preserve_key` as `$fn`
            $this->bucket = call_user_func($preserve_key, $this->bucket);
        } else {
            // <http://php.net/manual/en/function.shuffle.php#94697>
            if ($preserve_key) {
                $k = array_keys($this->bucket);
                $v = [];
                shuffle($k);
                foreach ($k as $kk) {
                    $v[$kk] = $this->bucket[$kk];
                }
                $this->bucket = $v;
                unset($k, $v);
            } else {
                shuffle($this->bucket);
            }
        }
        return $this;
    }

    public function is($fn) {
        $this->bucket = array_filter($this->bucket, $fn, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    public function not($fn) {
        $a = array_filter($this->bucket, $fn, ARRAY_FILTER_USE_BOTH);
        $b = array_diff($this->bucket, $a);
        $this->bucket = $b;
        unset($a);
        return $this;
    }

    // Sort array value: `1` for "asc" and `-1` for "desc"
    public function sort($order = 1, $key = null, $preserve_key = false, $null = X) {
        if (isset($key)) {
            $before = $after = [];
            if (!empty($this->bucket)) {
                foreach ($this->bucket as $k => $v) {
                    $v = (array) $v;
                    if (array_key_exists($key, $v)) {
                        $before[$k] = $v[$key];
                    } else if ($null !== X) {
                        $before[$k] = $null;
                        $this->bucket[$k][$key] = $null;
                    }
                }
                $order === -1 ? arsort($before) : asort($before);
                foreach ($before as $k => $v) {
                    $after[$k] = $this->bucket[$k];
                }
            }
            $this->bucket = $after;
            unset($before, $after);
        } else {
            $this->bucket = (array) $this->bucket;
            $order === -1 ? arsort($this->bucket) : asort($this->bucket);
        }
        if (!$preserve_key) {
            $this->bucket = array_values($this->bucket);
        }
        return $this;
    }

    public static function walk($array, $fn = null, $deep = false) {
        if (is_callable($fn)) {
            $deep ? array_walk_recursive($array, $fn, $array) : array_walk($array, $fn, $array);
            return $array;
        }
        return self::eat($array);
    }

    public static function alter($input, $replace = [], $fail = null) {
        // return `$replace[$input]` value if exist
        // or `$fail` value if `$replace[$input]` does not exist
        // or `$input` value if `$fail` is `null`
        return array_key_exists($input, $replace) ? $replace[$input] : ($fail ?: $input);
    }

    // Move to next array index
    public function next($skip = 0) {
        $this->i = $this->edge($this->i + 1 + $skip, 0, $this->count() - 1);
        return $this;
    }

    // Move to previous array index
    public function previous($skip = 0) {
        $this->i = self::edge($this->i - 1 - $skip, 0, $this->count() - 1);
        return $this;
    }

    // Move to `$index` array index
    public function to($index) {
        $this->i = is_int($index) ? $index : $this->index($index, $index);
        return $this;
    }

    // Insert `$value` before current array index
    public function before($value, $key = null) {
        $key = $key ?: $this->i;
        $this->bucket = array_slice($this->bucket, 0, $this->i, true) + [$key => $value] + array_slice($this->bucket, $this->i, null, true);
        $this->i = self::edge($this->i - 1, 0, $this->count() - 1);
        return $this;
    }

    // Insert `$value` after current array index
    public function after($value, $key = null) {
        $key = $key ?: $this->i + 1;
        $this->bucket = array_slice($this->bucket, 0, $this->i + 1, true) + [$key => $value] + array_slice($this->bucket, $this->i + 1, null, true);
        $this->i = self::edge($this->i + 1, 0, $this->count() - 1);
        return $this;
    }

    // Replace current array index value with `$value`
    public function replace($value) {
        $i = 0;
        foreach ($this->bucket as $k => $v) {
            if ($i === $this->i) {
                $this->bucket[$k] = $value;
                break;
            }
            ++$i;
        }
        return $this;
    }

    // Append `$value` to array
    public function append($value, $key = null) {
        $this->i = $this->count() - 1;
        return $this->after($value, $key);
    }

    // Prepend `$value` to array
    public function prepend($value, $key = null) {
        $this->i = 0;
        return $this->before($value, $key);
    }

    // Get first array value
    public function first() {
        $this->i = 0;
        return reset($this->bucket);
    }

    // Get last array value
    public function last() {
        $this->i = $this->count() - 1;
        return end($this->bucket);
    }

    // Get current array value
    public function current($fail = false) {
        $i = 0;
        foreach ($this->bucket as $k => $v) {
            if ($i === $this->i) {
                return $this->bucket[$k];
            }
            ++$i;
        }
        return $fail;
    }

    // Get array length
    public function count($deep = false) {
        return count($this->bucket, $deep ? COUNT_RECURSIVE : COUNT_NORMAL);
    }

    // Get array key by position
    public function key($index, $fail = false) {
        $array = array_keys($this->bucket);
        return array_key_exists($index, $array) ? $array[$index] : $fail;
    }

    // Get position by array key
    public function index($key, $fail = false) {
        $search = array_search($key, array_keys($this->bucket));
        return $search ? $search : $fail;
    }

    // Generate chunk(s) of array
    public function chunk($chunk = 5, $index = null, $fail = [], $preserve_key = false) {
        $chunks = array_chunk(__is_anemon__($this->bucket) ? (array) $this->bucket : [], $chunk, $preserve_key);
        return !isset($index) ? $chunks : (array_key_exists($index, $chunks) ? $chunks[$index] : $fail);
    }

    public function swap($a, $b = null) {
        return array_column($this->bucket, $a, $b);
    }

    public function join($s = ', ') {
        return $this->__invoke($s);
    }

    public function __construct($array = [], $s = ', ') {
        $this->bucket = $array;
        $this->separator = $s;
    }

    public function __get($key) {
        return array_key_exists($key, $this->bucket) ? $this->bucket[$key] : false;
    }

    public function __set($key, $value = null) {
        $this->bucket[$key] = $value;
    }

    public function __toString() {
        return $this->__invoke($this->separator);
    }

    public function __invoke($s = ', ', $filter = true) {
        return implode($s, $filter ? $this->is(function($v, $k) {
            return strpos($k, '__') !== 0;
        })->vomit() : $this->bucket);
    }

}