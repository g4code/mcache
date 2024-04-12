<?php

namespace G4\Mcache;

use Couchbase\Exception\DecodingFailureException;
use Couchbase\Transcoder;
use Couchbase\TranscoderFlags;

class SerializeTranscoder implements Transcoder
{
    private static ?SerializeTranscoder $instance;

    public static function getInstance(): Transcoder
    {
        if (!isset(self::$instance)) {
            self::$instance = new SerializeTranscoder();
        }
        return self::$instance;
    }

    public function encode($value): array
    {
        return [
            serialize($value),
            (new TranscoderFlags(TranscoderFlags::DATA_FORMAT_BINARY))->encode(),
        ];
    }

    public function decode(string $bytes, int $flags = 0)
    {
        $data = unserialize($bytes);
        if ($data === false) {
            throw new DecodingFailureException('Unable to unserialize bytes with SerializeTranscoder');
        }
        return $data;
    }
}
