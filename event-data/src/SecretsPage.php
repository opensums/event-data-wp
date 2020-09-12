<?php

/**
 * This file is part of the API Data plugin for WordPress™.
 *
 * @link      https://github.com/opensums/api-data-wp
 * @package   event-data-wp
 * @copyright [OpenSums](https://opensums.com/)
 * @license   MIT
 */

namespace EventData;

use EventData\WpPlugin\AdminPage;

/**
 */
class SecretsPage extends AdminPage {

    protected $menuParent = 'event-data-data';

    protected $menuLabel = 'Event Data Secrets';

    protected $pageSlug = 'secrets';

    // protected $sectionsTemplate = 'admin/settings-page-sections';
    protected $template = 'admin/secrets-page';

    protected function getSections() {

        return [
            // placeholder
            // helper to the right
            // supplemental underneath
            [
                // This is prefixed and used as the key in the wp_options table.
                'option' => 'secrets',
                // Prefixed and used as the section element's id.
                'id' => 'ticket-tailor-settings',
                'title' => __('Ticket Tailor settings', 'event-data'),
                'fields' => [
                    [
                        // This is the option key.
                        'key' => 'ticket-tailor-api-key',
                        'label' => __('API Key', 'event-data'),
                        'placeholder' => __('API Key', 'event-data'),
                        'size' => 48,
                    ],
                ],
            ],
        ];
    }
}
