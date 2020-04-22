<?php

namespace DigitalPianism\NzPostClient;

class ShippingOptions extends NzPostClient implements ShippingOptionsInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'shippingoptions/2.0/';

    /**
     * @param string $trackingReference
     * @return array
     * @throws NzPostClientAPIException
     */
    public function domestic($data)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'domestic/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param string $trackingReference
     * @return array
     * @throws NzPostClientAPIException
     */
    public function international($data)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query($data);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . 'international/'
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}