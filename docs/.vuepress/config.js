module.exports = {
  base: '/docs',
  title: 'Spreadsheet Object',
  head: [
    ['meta', { name: 'theme-color', content: '#000' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }],
    ['meta', { name: 'apple-mobile-web-app-status-bar-style', content: 'black' }]
  ],

  /**
   * Theme configuration, here is the default theme configuration for VuePress.
   *
   * ref：https://v1.vuepress.vuejs.org/theme/default-theme-config.html
   */
  themeConfig: {
    repo: '',
    editLinks: false,
    docsDir: '',
    editLinkText: '',
    lastUpdated: false,

    sidebar: {
      '/':  [
        {
          "title": "Get Started",
          "collapsable": false,
          "children": [
            "get-started/installation-setup",
            "get-started/requirements"
          ]
        },
        {
          "title": "Template Guides",
          "collapsable": false,
          "children": [
            "templates/simple",
            "templates/table-helper",
            "templates/advanced-overrides"
          ]
        },
        {
          "title": "Plugin Config",
          "collapsable": false,
          "children": [
            "config/settings",
          ]
        }
      ],
    }
  },

  /**
   * Apply plugins，ref：https://v1.vuepress.vuejs.org/zh/plugin/
   */
  plugins: [
    '@vuepress/plugin-back-to-top',
    '@vuepress/plugin-medium-zoom',
  ]
}
