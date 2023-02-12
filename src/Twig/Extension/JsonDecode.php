<?php

namespace App\Twig\Extension;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class JsonDecode extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('decode_json', [$this, 'decode_json']),
        ];
    }

    public function decode_json(string $string_json)
    {
        return json_decode($string_json , true);
    }
}