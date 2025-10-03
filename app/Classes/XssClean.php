<?php
namespace App\Classes;



use voku\helper\AntiXSS;
use App\Interface\SanitizerInterface;

class XssClean implements SanitizerInterface
{
    protected AntiXSS $antiXSS;
    public function __construct()
    {
        $this->antiXSS = new AntiXSS();
    }
    public function clean(string|null $value):string|null
    {
        return $this->antiXSS->xss_clean($value);
    }
    public function cleanArray(array $data): array
    {
        array_walk_recursive($data, function (&$value) {
            $value = $this->clean($value);
            $value = $this->antiXSS->isXssFound()? '': $value;
        });
        return $data;
    }
}
