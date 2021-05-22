<?php

declare(strict_types=1);

namespace App\Page\Component\Margins;

class MarginSize
{
    const NONE = 'none';
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const BIG = 'big';

    public static function columnMarginClass(string $margin): string
    {
        switch ($margin) {
            case self::SMALL:
                return 'col-lg-1';
            case self::MEDIUM:
                return 'col-lg-2';
            case self::BIG:
                return 'col-lg-3';
            default:
                return '';
        }
    }

    public static function columnContentClass(string $margin): string
    {
        switch ($margin) {
            case self::SMALL:
                return 'col-lg-10';
            case self::MEDIUM:
                return 'col-lg-8';
            case self::BIG:
                return 'col-lg-6';
            default:
                return 'col-lg-12';
        }
    }

    public static function heightMarginStyle(string $margin): string
    {
        switch ($margin) {
            case self::SMALL:
                return 'height: 30px;';
            case self::MEDIUM:
                return 'height: 55px;';
            case self::BIG:
                return 'height: 100px;';
            default:
                return '';
        }
    }
}
