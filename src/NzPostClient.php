<?php

namespace DigitalPianism\NzPostClient;

use DigitalPianism\NzPostClient\NzPostClientAPIException;
use Psr\SimpleCache\CacheInterface;
use JsonSchema\Validator;

/**
 * Class NzPostClient
 * @package DigitalPianism\NzPostClient
 */
class NzPostClient
{
    /** Cache key for auth token storage  */
    const TOKEN = 'NZ_POST_AUTH_TOKEN';

    /** URL to NZ Post Auth API */
    const NZPOST_AUTH_URL = 'https://oauth.nzpost.co.nz/as/token.oauth2';

    /** URL to NZ Post Prod API */
    const NZPOST_PROD_API_URL = 'https://api.nzpost.co.nz/';

    /** URL to NZ Post Test API */
    const NZPOST_TEST_API_URL = 'http://api.uat.nzpost.co.nz/';

    /** Main app request timeout */
    const REQUEST_TIMEOUT = 120;

    /**
     * Turn on/off debugging mode
     * @var bool
     */
    protected $debug = FALSE;

    /**
     * Turn on/off the production url
     * @var bool
     */
    protected $prod = FALSE;
    /**
     * Your NZ Post Client ID
     * @var string
     */
    protected $clientID;
    /**
     * Your NZ Post Client secret
     * @var string
     */
    protected $secret;
    /**
     * Auth token which we get from NZ Post Auth API
     * @var mixed
     */
    protected $token;
    /**
     * PSR-16 Cache (Optional)
     * @var NULL|CacheInterface
     */
    protected $Cache;
    /**
     * Cache TTL in seconds using only for NZ Post addresses not auth token
     * @var
     */
    protected $ttl = 86400;
    /**
     * Cache key prefix
     * @var string
     */
    protected $cachePrefix = 'nz_post_client_';

    /**
     * NzPostClient constructor. You can use yor PSR-16 cache to save money on same requests.
     * @param string $clientID
     * @param string $secret
     * @param CacheInterface|NULL $Cache
     */
    public function __construct($clientID, $secret, CacheInterface $Cache = NULL, $prod = false)
    {
        $this->clientID = $clientID;
        $this->secret = $secret;
        $this->prod = $prod;
        if (NULL !== $Cache) {
            $this->Cache = $Cache;

            if ($this->Cache->has(self::TOKEN)) {
                $this->token = $this->Cache->get(self::TOKEN);

                return;
            }
        }
        $this->auth();
    }

    /**
     * Authorize us in NZ Post Auth API to get auth token
     * @throws NzPostClientAuthException
     */
    protected function auth()
    {
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientID,
            'client_secret' => $this->secret,
        ];

        $curlSession = curl_init(self::NZPOST_AUTH_URL);

        curl_setopt($curlSession, CURLOPT_POST, 1);
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curlSession);

        $responseCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);

        if (200 !== $responseCode) {
            throw new NzPostClientAuthException($response);
        }

        $body = json_decode($response, TRUE);
        if (!isset($body['access_token'])) {
            throw new NzPostClientAuthException('Could not get auth token from NZPOST API');
        }

        $this->token = $body['access_token'];

        if (isset($body['expires_in']) && $this->cacheIsSet()) {
            $expiresAt = $body['expires_in'] - self::REQUEST_TIMEOUT;

            $this->Cache->set(self::TOKEN, $body['access_token'], $expiresAt);
        }

    }

    /**
     * Check if there is cache set
     * @return bool
     */
    public function cacheIsSet()
    {
        return is_a($this->Cache, CacheInterface::class);
    }

    /**
     * @param string $request
     * @param string $body
     * @return mixed
     * @throws NzPostClientAPIException
     */
    protected function sendApiRequest($request, $body = "")
    {
        $curlSession = curl_init($request);

        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);

        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'client_id: ' . $this->clientID
        ];

        if ($body) {
            $headers[] = 'Content-Type: application/json';
        }


        curl_setopt(
            $curlSession,
            CURLOPT_HTTPHEADER,
            $headers
        );
        
        if ($this->debug) {
            curl_setopt($curlSession, CURLOPT_VERBOSE, TRUE);
        }
        
        if ($body) {
            curl_setopt($curlSession, CURLOPT_POSTFIELDS, $body);
        }
        
        $response = curl_exec($curlSession);
        $responseCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);

        if (200 !== $responseCode) {
            throw new NzPostClientAPIException($responseCode);
        }

        $decodedJson = json_decode($response, TRUE);
        return !is_null($decodedJson)
            ? $decodedJson
            : $response;
    }

    /**
     * Get PSR-16 cache instance
     * @return NULL|CacheInterface
     */
    public function getCache()
    {
        return $this->Cache;
    }

    /**
     * Set PSR-16 cache instance
     * @param CacheInterface $cache
     * @param null|int|\DateTime $ttl
     * @return $this
     */
    public function setCache(CacheInterface $cache, $ttl = NULL)
    {
        $this->Cache = $cache;

        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @return $this
     */
    public function disableCache()
    {
        $this->Cache = NULL;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDebugOn()
    {
        $this->debug = TRUE;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDebugOff()
    {
        $this->debug = FALSE;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebugOn()
    {
        return $this->debug === TRUE;
    }

    /**
     * @return string
     */
    public function getCachePrefix()
    {
        return $this->cachePrefix;
    }

    /**
     * @param string $cachePrefix
     * @return $this
     */
    public function setCachePrefix($cachePrefix)
    {
        $this->cachePrefix = $cachePrefix;

        return $this;
    }

    /**
     * @param int|\DateTime $ttl
     * @return $this
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @return bool
     */
    public function getProd()
    {
        return $this->prod;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        if ($this->getProd()) {
            return self::NZPOST_PROD_API_URL;
        } else {
            return self::NZPOST_TEST_API_URL;
        }
    }
    
    protected function validate($data, $schemaPath)
    {
        if (!file_exists($schemaPath)) {
            throw new \Exception(sprintf("schema file '%s' not found", $schemaPath));
        }

        $schema = (object)['$ref' => 'file://' . $schemaPath];

        // Validate
        $validator = new Validator;
        $validator->check($data, $schema);

        if (!$validator->isValid()) {
            $errors = "";
            foreach ($validator->getErrors() as $error) {
                $errors .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
            
            throw new \InvalidArgumentException("JSON does not validate. Violations: \n" . $errors);
        }
    }

}