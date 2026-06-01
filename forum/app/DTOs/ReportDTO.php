<?php

namespace App\DTOs;

class ReportDTO
{
    public function __construct(
        public readonly string $reportableType,
        public readonly int $reportableId,
        public readonly string $reason,
        public readonly ?string $description = null,
    ) {}
}
