<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string {
        $input = preg_replace('#[^\\pL\d]+#u', '-', $input);
        $input = trim($input, '-');
        $input = strtolower($input);
        $input = preg_replace('#[^-\w]+#', '', $input);
        return $input;
    }
}