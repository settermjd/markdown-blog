<?php

declare(strict_types=1);

namespace Settermjd\MarkdownBlog\Utilities\View;

use TimeConstants;
use Twig\Attribute\AsTwigFunction;

use function gmdate;
use function round;
use function str_word_count;

use const PHP_ROUND_HALF_UP;

final readonly class ReadingTimeCalculator
{
    public const int READING_SPEED_SLOW_ALOUD     = 100;
    public const int READING_SPEED_AVERAGE_ALOUD  = 130;
    public const int READING_SPEED_FAST_ALOUD     = 160;
    public const int READING_SPEED_SLOW_SILENT    = 150;
    public const int READING_SPEED_AVERAGE_SILENT = 250;
    public const int READING_SPEED_FAST_SILENT    = 400;

    /**
     * @return array{'minutes': int, 'seconds': int}
     */
    #[AsTwigFunction('get_reading_time')]
    public function getReadingTime(
        ReadingType $readingType,
        ReadingProficiency $readingProficiency,
        string $text,
    ): array {
        $readingSpeed = $this->getReadingProficiency($readingType, $readingProficiency);
        $readingTime  = round(
            (TimeConstants\MINUTE_IN_SECONDS * str_word_count($text)) / $readingSpeed,
            mode: PHP_ROUND_HALF_UP
        );

        if ($readingTime < TimeConstants\MINUTE_IN_SECONDS) {
            return [
                'minutes' => 0,
                'seconds' => (int) $readingTime,
            ];
        }

        return [
            'minutes' => (int) gmdate("i", (int) $readingTime),
            'seconds' => (int) gmdate("s", (int) $readingTime),
        ];
    }

    private function getReadingProficiency(
        ReadingType $readingType,
        ReadingProficiency $readingSpeed,
    ): int {
        if ($readingType === ReadingType::Aloud) {
            return match ($readingSpeed) {
                ReadingProficiency::Slow => self::READING_SPEED_SLOW_ALOUD,
                ReadingProficiency::Average => self::READING_SPEED_AVERAGE_ALOUD,
                ReadingProficiency::Fast => self::READING_SPEED_FAST_ALOUD,
            };
        }

        return match ($readingSpeed) {
            ReadingProficiency::Slow => self::READING_SPEED_SLOW_SILENT,
            ReadingProficiency::Average => self::READING_SPEED_AVERAGE_SILENT,
            ReadingProficiency::Fast => self::READING_SPEED_FAST_SILENT,
        };
    }
}
