<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Support;

use DateTimeZone;

final class TimezoneOptions
{
    /** @return array<string, string> */
    public static function all(): array
    {
        $identifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC);

        return array_combine($identifiers, $identifiers);
    }

    /** @return array<string, string> */
    public static function configured(): array
    {
        $configured = config('filament-zkteco-adms.timezone_options', []);

        if (! is_array($configured) || $configured === []) {
            return self::all();
        }

        $validIdentifiers = array_flip(DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC));
        $options = [];

        foreach ($configured as $identifier => $label) {
            $usesIdentifierAsLabel = is_int($identifier);

            if ($usesIdentifierAsLabel) {
                $identifier = $label;
            }

            if (! is_string($identifier) || ! is_string($label) || ! isset($validIdentifiers[$identifier])) {
                continue;
            }

            $options[$identifier] = $usesIdentifierAsLabel ? $identifier : $label;
        }

        return $options !== [] ? $options : self::all();
    }

    public static function default(): string
    {
        $options = self::configured();
        $configuredDefault = config('zkteco-adms.default_timezone', 'UTC');

        if (is_string($configuredDefault) && array_key_exists($configuredDefault, $options)) {
            return $configuredDefault;
        }

        if (array_key_exists('UTC', $options)) {
            return 'UTC';
        }

        return (string) array_key_first($options);
    }
}
