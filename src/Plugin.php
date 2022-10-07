<?php

namespace wabisoft\spreadsheetobject;

use wabisoft\spreadsheetobject\twigextensions\SpreadsheetObjectTwigExtension;
use wabisoft\spreadsheetobject\services\StoreSpreadsheet;

use Craft;
use craft\log\MonologTarget;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LogLevel;
use yii\base\Event;
use craft\base\Element;
use craft\elements\Asset;


class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();
        /*
         * @link: https://putyourlightson.com/articles/adding-logging-to-craft-plugins-with-monolog
         */
        Craft::getLogger()->dispatcher->targets[] = new MonologTarget([
            'name' => 'spreadsheet-object',
            'categories' => ['spreadsheet-object'],
            'level' => LogLevel::INFO,
            'logContext' => false,
            'allowLineBreaks' => false,
            'formatter' => new LineFormatter(
                format: "%datetime% %message%\n",
                dateFormat: 'Y-m-d H:i:s',
            ),
        ]);
        /*
         * Add Our Twig Extensions
         */
        Craft::$app->view->registerTwigExtension(new SpreadsheetObjectTwigExtension);


        Event::on(
            Asset::class,
            Element::EVENT_AFTER_DELETE,
            function (Event $event) {
                $asset = $event->sender;
                StoreSpreadsheet::deleteByAssetId($asset->id);
            }
        );

    }


    protected function createSettingsModel() : \craft\base\Model
    {
        return new \wabisoft\spreadsheetobject\models\Settings();
    }
}
