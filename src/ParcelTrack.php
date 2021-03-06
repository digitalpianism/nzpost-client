<?php

namespace DigitalPianism\NzPostClient;

class ParcelTrack extends NzPostClient implements ParcelTrackInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parceltrack/3.0/parcels/';

    /**
     * Query to return tracking information for a specific tracking reference.
     *
     * @param string $trackingReference The unique parcel identifier
     * @return array Object containing the parcel and event detail
     * @throws NzPostClientAPIException
     */
    public function track($trackingReference)
    {
        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $trackingReference;

        $data = [
            ' tracking_reference ' => $trackingReference
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $responseBody = $this->sendApiRequest($request);

        return $responseBody['results'];
    }
}