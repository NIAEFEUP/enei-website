<?php

namespace App\Libs;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTLib
{
    /**
     * The JWT signing/verifying key.
     *
     * @var \Firebase\JWT\Key
     */
    protected $jwt_key;

    /**
     * The number of minutes the JWT is valid for.
     *
     * @var int
     */
    protected $valid_minutes;

    public static int $DEFAULT_VALID_MINUTES = 5;

    public function __construct(string $jwt_key)
    {
        $this->jwt_key = new Key($jwt_key, 'HS256'); // FIXME: use HMAC for now, switch to RSA later
        $this->valid_minutes = config('jwt.valid_min', static::$DEFAULT_VALID_MINUTES); // TODO: use custom configs https://stackoverflow.com/questions/38665907/how-to-add-custom-config-file-to-app-config-in-laravel-5
    }

    public function generate(array $payload): string
    {
        $issue_time = now();

        // See https://datatracker.ietf.org/doc/html/rfc7519#section-4.1.6 for definition of NumericDate type
        $convertToJWTNumericDate = fn (\Illuminate\Support\Carbon $timestamp) => $timestamp->getTimestampMs() / Carbon::MILLISECONDS_PER_SECOND;

        $claims = [
            'iss' => config('app.url'),
            'aud' => config('app.url'),
            'iat' => $convertToJWTNumericDate($issue_time),
            // 'exp' => $convertToJWTNumericDate($issue_time->addMinutes($this->valid_minutes)),
        ];

        $jwt = JWT::encode(array_merge($claims, $payload), $this->jwt_key->getKeyMaterial(), $this->jwt_key->getAlgorithm());

        return $jwt;
    }
}
