# Settings

Override the Plugin's default settings on a per-project basis

```php
<?php
/*
 * Copy this into the config directory with name of
 * spreadsheet-object.php to adjust project settings
 */
return [
    'storeInDb' => true,
    'cellModifierTemplates'=> '_cells',
    'defaultTemplateName' => 'default',
    'tableOptions' => [
        'thead' => false,
        'tfoot' => false,
        'autoCellDataAttribute' => true,
        'classes' => [
            'table' => false,
            'tr' => false,
            'td' => false,
            'th' => false,
            'thead' => false,
            'tfoot' => false,
            'first' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ],
            'last' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ]
        ]
    ],
];

```
