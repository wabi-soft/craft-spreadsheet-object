<?php

namespace wabisoft\spreadsheetobject\services;
use Craft;
use yii\base\Exception;

class CellTemplateLoader
{

    /**
     * @throws Exception
     */
    public static function load($templates, $variables = []) {
        if(count($templates) == 0) {
            return false;
        }
        foreach($templates as $template) {
            $template = trim($template);
            if(Craft::$app->view->doesTemplateExist($template)) {
                return Craft::$app->view->renderTemplate($template, $variables);
            }
        }
        return false;
    }
}
