<?php
/**
 * Spreadsheet Object Plugin Configuration
 * 
 * Copy this file to your config directory as 'spreadsheet-object.php' to customize plugin settings.
 * 
 * @see https://github.com/wabisoft/craft-spreadsheet-object for documentation
 */

return [
    // Whether to store processed spreadsheet data in the database
    'storeInDb' => true,

    // Directory for cell modifier templates
    'cellModifierTemplates' => '_cells',

    // Default template name for cell rendering
    'defaultTemplateName' => 'default',

    // Table rendering options
    'tableOptions' => [
        // Whether to render table header
        'thead' => false,

        // Whether to render table footer
        'tfoot' => false,

        // Whether to automatically add data attributes to cells
        'autoCellDataAttribute' => true,

        // CSS classes configuration
        'classes' => [
            // Table-level classes
            'table' => false,
            'tr' => false,
            'td' => false,
            'th' => false,
            'thead' => false,
            'tfoot' => false,

            // First row/column classes
            'first' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ],

            // Last row/column classes
            'last' => [
                'tr' => false,
                'td' => false,
                'th' => false,
            ]
        ]
    ],
];
