<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\ByteString;

class MinerController
{
    const DIFFICULTY = 4; 

    public function mine($word): JsonResponse
    {
        $counter = 0;
        $hash = '';

        while(substr($hash, 0, self::DIFFICULTY) !== str_pad('', self::DIFFICULTY, '0')) {
            $key = ByteString::fromRandom(8);
            $hash = md5($word.$key);

            $counter++;
        }

        return new JsonResponse([
            "hash" => $hash,
            "key" => $key,
            "hashes" => $counter
        ]);
    }
}