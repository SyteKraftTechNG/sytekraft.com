<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 7/16/17
 * Time: 2:04 AM
 */
class Color
{
    private $red = 0;
    private $green = 0;
    private $blue = 0;
    private $alpha = 1;

    /**
     * Color constructor.
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    public function __construct($red = 0, $green = 0, $blue = 0, $alpha = 1)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * @param $string
     * @return bool|Color
     */
    public static function fromString($string)
    {
        // rrggbb
        // #rrggbb
        // rgb(rr,gg,bb)
        // rgba(rr,gg,bb,aa)


        $string = str_replace(' ', '', $string);
        $alpha = 1;
        $red = $blue = $green = 0;
        
        if (substr($string, 0, 1) == '#') {
            $color = substr($string, 1);
            $red = hexdec(substr($color, 0, 2));
            $green = hexdec(substr($color, 2, 2));
            $blue = hexdec(substr($color, 4, 2));
        } elseif (substr($string, 0, 4) == 'rgb(' || substr($string, 0, 5) == 'rgba(') {
            $isRGBOnly = boolval(substr($string, 0, 4) == 'rgb(');
            $pos = $isRGBOnly ? 4 : 5;
            $colors = explode(',', substr($string, $pos, -1));
            $red = hexdec($colors[0]);
            $green = hexdec($colors[1]);
            $blue = hexdec($colors[2]);
            $alpha = doubleval($isRGBOnly ? 1 : $colors[3]);
        } elseif (strlen($string) == 6) {
            $red = hexdec(substr($string, 0, 2));
            $green = hexdec(substr($string, 2, 2));
            $blue = hexdec(substr($string, 4, 2));
        } else {
            return false;
        }
        
        $color = new Color;
        $color->setRed($red);
        $color->setGreen($green);
        $color->setBlue($blue);
        $color->setAlpha($alpha);
        
        return $color;
    }

    public static function cmyk2rgb($c, $m, $y, $k, $bitmapSrc='') {

        $x = round($y/5) * 21 + round($c/5);
        $y = round($k/5) * 21 + round($m/5);

        if(!$bitmapSrc) $bitmapSrc = join(DIRECTORY_SEPARATOR, [
            ROOT,
            "assets",
            "img",
            "cmyk_map_sRGB-IEC61966-21.png"
        ]);

        if(!file_exists($bitmapSrc)) {
            return false;
        } else {
            $im = ImageCreateFromPng($bitmapSrc);
            $rgb = ImageColorAt($im, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            return new Color($r, $g, $b);
        }
    }

    public static function invert(Color $color)
    {
        $color->setRed(255 - $color->getRed());
        $color->setBlue(255 - $color->getBlue());
        $color->setGreen(255 - $color->getGreen());
        
        return $color;
    }

    public function luminosity()
    {
        return 0.2126 * pow($this->getRed()/255, 2.2) + 0.7152 * pow($this->getGreen()/255, 2.2) + 0.0722 * pow($this->getBlue()/255, 2.2);
    }

    public function goodContrastWith(Color $color)
    {
        $luminosity = $this->luminosity();
        $colorLuminosity = $color->luminosity();

        $colorMoreLuminous = boolval(($colorLuminosity >= $luminosity));
        $maxLum = ($colorMoreLuminous ? $colorLuminosity : $luminosity) + 0.05;
        $minLum = ($colorMoreLuminous ? $luminosity : $colorLuminosity) + 0.05;

        return (($maxLum / $minLum) > 5) ? true : false;
    }

    /**
     * @return int
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param int $red
     */
    public function setRed($red)
    {
        $this->red = $red;
    }

    /**
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @param int $green
     */
    public function setGreen($green)
    {
        $this->green = $green;
    }

    /**
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * @param int $blue
     */
    public function setBlue($blue)
    {
        $this->blue = $blue;
    }

    /**
     * @return int
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * @param int $alpha
     */
    public function setAlpha($alpha)
    {
        $this->alpha = $alpha;
    }

    public function toHex($withHash = true) {
        $hash = $withHash ? "#" : "";
        return $hash. join('', $this->getHexColorValues());
    }

    public function toRGB() {
        return "rgb(". join(',', $this->getColorValues()). ")";
    }

    public function toRGBA() {
        $colorValues = $this->getColorValues();
        $colorValues[] = $this->getAlpha();
        return "rgba(". join(',', $colorValues). ")";
    }

    public function toCMYK() {
        return "cmyk(". join('%,', $this->getCMYKColorValues()). "%)";
    }

    public function paintPixel() {
        $txt = boolval($this->alpha == 1) ? $this->toRGB() : $this->toRGBA();
        return "<div style=\"-webkit-appearance: none;-moz-appearance: none;appearance: none;position: relative; width: 10px;height: 10px; line-height: 1px; content: ''; font-size: 5px; background-color: $txt;\">&nbsp;</div>";
    }

    /**
     * @return array
     */
    public function getColorValues()
    {
        return [
            $this->getRed(),
            $this->getGreen(),
            $this->getBlue()
        ];
    }

    /**
     * @return array
     */
    public function getHexColorValues()
    {
        return [
            dechex($this->getRed()),
            dechex($this->getGreen()),
            dechex($this->getBlue())
        ];
    }

    public function getCMYKColorValues() {
        $red = $this->red / 255;
        $green = $this->green / 255;
        $blue = $this->blue / 255;
        $k = 1 - max($red, $green, $blue);
        $cyan = round(((1 - $red - $k) / (1 - $k)), 2) * 100;
        $magenta = round(((1 - $green - $k) / (1 - $k)), 2) * 100;
        $yellow = round(((1 - $blue - $k) / (1 - $k)), 2) * 100;
        $kk = round($k, 2) * 100;

        return [
            $cyan, $magenta, $yellow, $kk
        ];
    }

}
