<?php
/**
 * This file is part of the OpenSums WpPlugin framework.
 *
 * @package    event-data-wp-plugin
 * @copyright  Copyright 2020 OpenSums https://opensums.com/
 * @license    MIT
 */
declare(strict_types=1);

namespace EventData\WpPlugin;

/**
 * Base class for a WordPress plugin.
 */
abstract class Plugin {
    // --- YOU MUST OVERRIDE THESE IN THE PLUGIN CLASS -------------------------
    /** @var string Name of the admin class (optional). */
    protected $adminClass;

    /** @var string Plugin human name. */
    protected $name;

    /** @var string Plugin slug (aka text domain). */
    protected $slug;

    /** @var string Plugin version. */
    protected $version;

    // -------------------------------------------------------------------------

    /** @var string Path to the plugin's assets - prefixed by the constructor. */
    protected $assetsUrl = '/assets';

    /** @var string Path to the plugin - set by the constructor. */
    protected $pluginDir;

    /** @var string URL to the plugin - set by the constructor. */
    protected $pluginUrl;

    /** @var string Path to the plugin's templates - prefixed by the constructor. */
    protected $templateDir = '/templates';

    /** @var mixed[] Global variables for templates. */
    protected $templateGlobals = [];

    // -------------------------------------------------------------------------

    final public function __construct(string $pluginFile) {
        $this->pluginDir = dirname($pluginFile);
        $this->pluginUrl = plugin_dir_url($pluginFile);
        $this->assetsUrl = $this->pluginUrl . $this->assetsUrl;

        $this->templateDir = realpath($this->pluginDir . $this->templateDir);

        $this->templateGlobals = [
            'plugin' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'version' => $this->version,
            ],
        ];
    }

    /**
     * Add entries to the Admin menu.
     *
     * Invoked as a callback (when?).
     */
    public function addAdminMenuEntries(): void {
        foreach ($this->adminPages as $page) {
            (new $page($this));
        }
    }

    public function getAssetsUrl($file = null): string {
        return $this->assetsUrl . "/$file";
    }

    public function getName(): string {
        return $this->name;
    }

    public function getVersion(): string {
        return $this->version;
    }

    // Refactor after here -----------------------------------------------------

    /**
     * Load the plugin.
     */
    final public function load() {
        $this->childLoad();

        add_action('admin_menu', [$this, 'addAdminMenuEntries']);
    }

    public function render($template, $vars) {
        extract($this->templateGlobals);
        extract($vars);
        require("$this->templateDir/$template.tpl.php");
    }

    /**
     * Get a prefixed slug.
     *
     * @param string $slug The slug to be prefixed.
     * @param string $separator A separator to use instead of `-`.
     * @return string The slug with an added prefix.
     */
    final public function slugify(string $slug = null, $separator = null): string {
        // Add the prefix.
        $ret = $slug === null ? $this->slug : "{$this->slug}-{$slug}";
        // Return the kebab-cased slug...
        if ($separator === null) return $ret;
        // ...or replace with another separator (usually an _)/
        return str_replace('-', $separator, $ret);
    }

    /**
     * Called by load(), override in the child class.
     */
    protected function childLoad() {}
}
