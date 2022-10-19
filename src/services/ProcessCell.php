<?php

namespace wabisoft\spreadsheetobject\services;

use wabisoft\spreadsheetobject\Plugin;

class ProcessCell
{
    public static function modifyCell($cell, $properties) {
        $tr = $properties['tr'] ?? false;
        $index = $properties['td'] ?? false;
        $style = $properties['style'] ?? false;
        $paths = self::getTemplatePaths($tr, $index, $style);
        if(!$paths) {
            return $cell;
        }
        $cellUpdate = CellTemplateLoader::load($paths, ['cell' => $cell]);
        if(!$cellUpdate) {
            return $cell;
        }
        return $cellUpdate;
    }

    private static function getTemplatePaths($tr, $td, $style) {
        $templates =  Plugin::getInstance()->getSettings()->cellModifierTemplates;
        $default =  Plugin::getInstance()->getSettings()->defaultTemplateName;
        $row =  Plugin::getInstance()->getSettings()->rowTemplates;
        $column =  Plugin::getInstance()->getSettings()->columnTemplates;
        if(!$templates) {
            return false;
        }

        if(!$tr && !$td && !$style) {
            return false;
        }
        $trPath = $tr['index'] ?? '';
        $trFirst = $tr['first'] ?? '';
        $trLast = $tr['last'] ?? '';
        $tdPath = $td['index'] ?? '';
        $tdFirst = $td['first'] ?? '';
        $tdLast = $td['last'] ?? '';
        if($trFirst) {
            $trPath = 'first';
        } elseif ($trLast) {
            $trPath = 'last';
        }
        if($tdFirst) {
            $tdPath = 'first';
        } elseif ($tdLast) {
            $tdPath = 'last';
        }
        $paths = [];
        $paths[] = self::assemblePaths([$style, $row . $trPath, $column . $tdPath]);
        $paths[] = self::assemblePaths([$style, $row . $trPath]);
        $paths[] = self::assemblePaths([$style, $column . $tdPath]);
        $paths[] = self::assemblePaths([$row . $trPath, $column . $tdPath]);
        $paths[] = self::assemblePaths([$row . $trPath]);
        $paths[] = self::assemblePaths([$column . $tdPath]);
        $paths[] = self::assemblePaths([$style, $default]);
        $paths[] = self::assemblePaths([$style]);
        $paths[] = self::assemblePaths([$default]);

        return $paths;
    }


    private static function assemblePaths( $segments = [] ): string
    {
        $templates =  Plugin::getInstance()->getSettings()->cellModifierTemplates;
        return ($templates . '/' . implode('/', $segments));
    }
}
