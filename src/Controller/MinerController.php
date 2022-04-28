<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\ByteString;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
class MinerController extends AbstractController
{
    const DIFFICULTY = 4; 

    public function mine(Request $request, RateLimiterFactory $anonymousApiLimiter): JsonResponse
    {
        $limiter = $anonymousApiLimiter->create($_SERVER['HTTP_X_FORWARDED_FOR']);
        if (false === $limiter->consume(1)->isAccepted()) {
            return new JsonResponse("Too Many Attempts", JsonResponse::HTTP_TOO_MANY_REQUESTS);
        }

        $word = $request->get('word');
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