<?php

namespace yii\web;

/**
 * Interface for classes that parse the raw request body into a parameters array.
 *
 * @since 1.0
 */
interface RequestParserInterface
{
    /**
     * Parses a HTTP request body.
     * @param string $rawBody the raw HTTP request body.
     * @param string $contentType the content type specified for the request body.
     * @return array parameters parsed from the request body
     */
    public function parse($rawBody, $contentType);
}
