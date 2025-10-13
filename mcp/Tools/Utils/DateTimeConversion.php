<?php

namespace MintMCP\Tools\Utils;

/**
 * Utility class providing date-time conversion utilities between GMT and user's timezone.
 */
class DateTimeConversion
{
    /**
     * Converts a date string from GMT to the current user's timezone.
     * @param string $date Date string in GMT
     * @return string Date string in user's timezone
     */
    public static function toUserTZ(string $date): string
    {
        global $timedate, $current_user;

        [$dateTime, $outputFormat] = self::parseDateWithFormats(
            $date,
            $timedate,
            new \DateTimeZone('GMT')
        );

        return $timedate->tzUser($dateTime, $current_user)->format($outputFormat);
    }

    /**
     * Converts a date string from the current user's timezone to GMT.
     * @param string $date Date string in user's timezone
     * @return string Date string in GMT
     */
    public static function fromUserTZ(string $date): string
    {
        global $timedate, $current_user;

        $userTZ = $timedate::userTimezone($current_user);
        [$dateTime, $outputFormat] = self::parseDateWithFormats(
            $date,
            $timedate,
            new \DateTimeZone($userTZ)
        );

        return $timedate->tzGMT($dateTime)->format($outputFormat);
    }

    /**
     * Parses a date string using multiple formats and returns the DateTime object and the format used.
     * @param string $date Date string to parse
     * @param TimeDate $timedate TimeDate instance for format retrieval
     * @param \DateTimeZone $timezone Timezone to use for parsing
     * @return array [\DateTime $dateTime, string $formatUsed]
     */
    private static function parseDateWithFormats(string $date, $timedate, \DateTimeZone $timezone): array
    {
        $formats = [
            ['input' => $timedate->get_db_date_format(), 'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_db_date_time_format(), 'output' => $timedate->get_db_date_time_format()],
            ['input' => $timedate->get_date_format(), 'output' => $timedate->get_db_date_format()],
            ['input' => $timedate->get_date_time_format(), 'output' => $timedate->get_db_date_time_format()],
        ];

        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format['input'], $date, $timezone);
            if ($dateTime !== false) {
                return [$dateTime, $format['output']];
            }
        }

        throw new \InvalidArgumentException("Invalid date format: {$date}");
    }
}