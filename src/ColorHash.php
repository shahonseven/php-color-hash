<?php

/**
 * Color Hash Class
 *
 * Backported from the Javascript library color-hash
 * Source: https://github.com/zenozeng/color-hash
 * Author: Zeno Zeng (MIT License)
 *
 * @package  shahonseven/php-color-hash
 * @author   Shahril Abdullah - shahonseven
 */

namespace Shahonseven;

class ColorHash {
    
    public $L = [0.35, 0.5, 0.65];
    public $S = [0.35, 0.5, 0.65];

    /**
     * Backport of Javascript function charCodeAt()
     *
     * @access protected
     * @param  string $c
     * @return integer
     */
    protected function getCharCode($c) {
        list(, $ord) = unpack('N', mb_convert_encoding($c, 'UCS-4BE', 'UTF-8'));
        return $ord;
    }

    /**
     * BKDR Hash (modified version)
     *
     * @access protected
     * @param  string $str
     * @return integer
     */
    protected function hash($str) {
        $seed  = 131;
        $seed2 = 137;
        $hash  = 0;
        // Make hash more sensitive for short string like 'a', 'b', 'c'
        $str .= 'x';
        $max = intval(9007199254740991 / $seed2);
        for ($i = 0, $ilen = mb_strlen($str, 'UTF-8'); $i < $ilen; $i++) {
            if ($hash > $max) {
                $hash = intval($hash / $seed2);
            }
            $hash = $hash * $seed + $this->getCharCode(mb_substr($str, $i, 1, 'UTF-8'));
        }
        return $hash;
    }

    /**
     * Convert RGB Array to HEX
     *
     * @access protected
     * @param  array $rgb
     * @return string 6 digits hex starting with #
     */
    protected function RGB2HEX(array $rgb) {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * Convert HSL to RGB
     *
     * @access protected
     * @param  integer  $hue         Hue ∈ [0, 360)
     * @param  integer  $saturation  Saturation ∈ [0, 1]
     * @param  integer  $lightness   Lightness ∈ [0, 1]
     * @return array
     */
    protected function HSL2RGB($H, $S, $L) {
        $H /= 360;
        $q = $L < 0.5 ? $L * (1 + $S) : $L + $S - $L * $S;
        $p = 2 * $L - $q;

        return array_map(function ($color) use ($q, $p) {
            if ($color < 0) {
                $color++;
            }
            if ($color > 1) {
                $color--;
            }
            if ($color < 1/6) {
                $color = $p + ($q - $p) * 6 * $color;
            } else if ($color < 0.5) {
                $color = $q;
            } else if ($color < 2/3) {
                $color = $p + ($q - $p) * 6 * (2/3 - $color);
            } else {
                $color = $p;
            }
            return round($color * 255);
        }, array($H + 1/3, $H, $H - 1/3));
    }

    /**
     * Returns the hash in [h, s, l].
     * Note that H ∈ [0, 360); S ∈ [0, 1]; L ∈ [0, 1];
     *
     * @access protected
     * @param  string $str
     * @return array
     */
    public function hsl($str) {
        $hash = $this->hash($str);

        $H = $hash % 359;
        $hash = intval($hash / 360);
        $S = $this->S[$hash % count($this->S)];
        $hash = intval($hash / count($this->S));
        $L = $this->L[$hash % count($this->L)];

        return [$H, $S, $L];
    }

    /**
     * Returns the hash in [r, g, b].
     * Note that R, G, B ∈ [0, 255]
     *
     * @param string str string to hash
     * @return array [r, g, b]
     */
    public function rgb($str) {
        $hsl = $this->hsl($str);
        return $this->HSL2RGB($hsl[0], $hsl[1], $hsl[2]);
    }

    /**
     * Returns the hash in hex
     *
     * @param string str string to hash
     * @returns string hex with #
     */
    public function hex($str) {
        $rgb = self::rgb($str);
        return self::RGB2HEX($rgb);
    }
}
