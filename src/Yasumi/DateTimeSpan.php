<?php

declare(strict_types=1);

namespace Yasumi;

use Yasumi\Exception\InvalidDateTimeSpanException;

class DateTimeSpan implements \Stringable
{
    public const YEAR_LOWER_BOUND = 1000;

    public const YEAR_UPPER_BOUND = 9999;

    private const FORMAT = 'c';

    public function __construct(private \DateTimeInterface $start, private \DateTimeInterface $end)
    {
        $this->validate();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s - %s',
            $this->start->format(self::FORMAT),
            $this->end->format(self::FORMAT),
        );
    }

    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @return array{start: string, end: string}
     */
    public function toArray(): array
    {
        return [
            'start' => $this->start->format(self::FORMAT),
            'end' => $this->end->format(self::FORMAT),
        ];
    }

    private function validate(): void
    {
        $lower = self::YEAR_LOWER_BOUND.'-01-01';
        $upper = self::YEAR_UPPER_BOUND.'-12-31';

        if ($this->start < (new \DateTimeImmutable($lower))) {
            throw new InvalidDateTimeSpanException(sprintf('start date needs to be %s or later (%s given)', $lower, $this->start->format(self::FORMAT)));
        }

        if ($this->end > (new \DateTimeImmutable($upper))) {
            throw new InvalidDateTimeSpanException(sprintf('end date needs to be %s or earlier (%s given)', $upper, $this->start->format(self::FORMAT)));
        }

        if ($this->start > $this->end) {
            throw new InvalidDateTimeSpanException('end date must be after start date');
        }
    }
}
