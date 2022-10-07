<?php

namespace wabisoft\spreadsheetobject\twigextensions;
use craft\elements\Asset;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use wabisoft\spreadsheetobject\services\ProcessSpreadsheet;

class SpreadsheetObjectTwigExtension extends AbstractExtension
{
    public function getName() {
        return 'SpreadSheetObject';
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('spreadsheetobject', [$this, 'spreadsheetobject']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('spreadsheetobject', [$this, 'spreadsheetobject'], ['is_safe' => ['all']]),
        ];
    }

    public function spreadsheetobject(Asset $file = null, $options = []) {

        return ProcessSpreadsheet::getArrayFromAsset($file, $options);
    }
}
