<?php

namespace App\Services\Export;

/**
 * Interface ParserContract
 * @package App\Services\Export
 */
interface ParserContract
{
    /**
     * Parse Entity
     */
    public function parse(): void;
}
