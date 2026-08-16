<?php

return [
    /*
     * ---------------------------------------------------------------
     * Add Middleware To Roues
     * ---------------------------------------------------------------
     */
    "middlewares" => [],

    /*
     * ---------------------------------------------------------------
     * Allow Routes
     * ---------------------------------------------------------------
     */
    "allow_routes" => true,

    /*
     * ---------------------------------------------------------------
     * Panel Navigation
     * ---------------------------------------------------------------
     * By default the settings page has no sidebar entry of its own and
     * is reached through the Settings Hub. Set "register" to true to
     * also list it in the panel menu.
     *
     * "group" and "label" accept a plain string ("Admin") or a
     * translation key ("admin.menu.settings"); null keeps the package
     * default. Values containing "." or "::" go through trans().
     */
    "navigation" => [
        "register" => false,
        "group" => null,
        "label" => null,
    ],

    /*
     * ---------------------------------------------------------------
     * Settings Page Max Content Width
     * ---------------------------------------------------------------
     * null follows the panel (Filament defaults to "7xl", centered).
     * Set any value of Filament\Support\Enums\Width to override, e.g.
     * "screen-2xl" (96rem, centered) or "full" (edge to edge).
     */
    "max_content_width" => null
];
