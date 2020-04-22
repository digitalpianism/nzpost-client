<?php

namespace DigitalPianism\NzPostClient;

class ParcelLabel extends NzPostClient implements ParcelLabelInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parcellabel/v3/labels/';

    /**
     * @param array $data
     * @return array
     * @throws NzPostClientAPIException
     */
    public function createDomestic($data)
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
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param array $data
     * @return array
     * @throws NzPostClientAPIException
     */
    public function createInternational($data)
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
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param int $consignementId
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getLabelStatus($consignementId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignementId
            . '/status';

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param int $consignementId
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getRelatedLabelStatus($consignementId)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignementId
            . '/related';

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }

    /**
     * @param int $consignementId
     * @param string $format
     * @param int $page
     * @return array
     */
    public function downloadLabel($consignementId, $format, $page)
    {
        if ($this->cacheIsSet()) {
            $cacheKey = $this->cachePrefix . md5($query . $type . strval($max));

            if ($this->Cache->has($cacheKey)) {
                return $this->Cache->get($cacheKey);
            }
        }

        $params = http_build_query([
            'format' => $format,
            'page' => $page,
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignementId
            . $params;

        $responseBody = $this->sendApiRequest($request);

        if ($this->cacheIsSet()) {
            $this->Cache->set($cacheKey, $responseBody, $this->ttl);
        }

        return $responseBody;
    }
}