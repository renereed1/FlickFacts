<?php

namespace FlickFacts\Common\Infrastructure\Persistence\Dsql;

use Aws\Exception\AwsException;
use Aws\Signature\SignatureV4;
use Aws\Sts\StsClient;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PDO;

class Connection
{
    private ?string $cachedToken = null;
    private ?DateTimeImmutable $tokenExpiry = null;

    public function __construct()
    {
    }

    public function getConnection(
        string $host,
        string $username,
        string $database,
        string $port,
        string $region,
        string $expire = '+14 minutes'): PDO
    {
        $currentTime = new DateTimeImmutable();

        // Check if the token is cached and still valid
//        if ($this->cachedToken === null ||
//            $this->tokenExpiry === null ||
//            $this->tokenExpiry <= $currentTime) {
//
//            $this->cachedToken = $this->generateDbConnectAdminAuthToken($host,
//                $region);
//            $this->tokenExpiry = $currentTime->modify($expire);
//        }

        $dsn = sprintf("pgsql:host=%s;port=%d;dbname=%s",
            'database-1.cp8m8s2iaja3.us-west-2.rds.amazonaws.com',
            $port,
            'postgres');

        $pdo = new PDO($dsn,
            'postgres',
            //$this->cachedToken);
            'mYl4Su030109');

        $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    private function generateDbConnectAdminAuthToken(string $endpoint,
                                                     string $region): string
    {
        $startTime = microtime(true); // Start timing

        $action = 'DbConnectAdmin';

        try {
            $stsClient = new StsClient([
                'region' => $region,
                'version' => 'latest',
            ]);

            $credentials = $stsClient->getCredentials()
                ->wait();

            $updatedEndpoint = 'https://' . $endpoint;

            $uri = new Uri($updatedEndpoint)->withQuery(http_build_query(['Action' => $action]));
            $request = new Request('GET',
                $uri);

            $signer = new SignatureV4('dsql',
                $region);

            $signedRequest = $signer->presign($request,
                $credentials,
                new DateTimeImmutable('+15 minutes'));

            $signedUrl = (string)$signedRequest->getUri();

            $endTime = microtime(true); // End timing

            $executionTime = $endTime - $startTime; // Calculate execution time

            print "Token generation took {$executionTime} seconds.\n";

            return parse_url($signedUrl, PHP_URL_HOST) . parse_url($signedUrl, PHP_URL_PATH) . '?' . parse_url($signedUrl, PHP_URL_QUERY);
        } catch (AwsException $e) {
            throw new Exception('Failed to generate DB authentication token.', 0, $e);
        }
    }
}
