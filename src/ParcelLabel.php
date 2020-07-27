<?php

use JsonSchema\Validator;

namespace DigitalPianism\NzPostClient;

class ParcelLabel extends NzPostClient implements ParcelLabelInterface
{
    /** NZ Post API Endpoint */
    const NZPOST_API_ENDPOINT = 'parcellabel/v3/labels/';

    /**
     * Request for creating labels. These labels are returned in either a PDF or PNG format.
     *
     * @param array $data
     * @return string Unique identifier for the consignment if the request is successful.
     * @throws NzPostClientAPIException
     */
    public function createLabel(array $data)
    {        
        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT;

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $data = (object)$data;
        
        $this->validate($data, $schemaPath);

        $responseBody = $this->sendApiRequest($request, json_encode($data));

        return $responseBody['consignment_id'];
    }

    /**
     * Returns the status of all labels within a consignment.
     * Note if webhook URL is not provided in the request for label generation, you will need to call the Status resource to get the status of the labels within a consignment.
     * 
     * @param string $consignmentId Unique id of a consignment
     * @return array
     * @throws NzPostClientAPIException
     */
    public function getLabelStatus($consignmentId)
    {
        $data = [
            'consignment_id' => $consignmentId
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');

        $this->validate($data, $schemaPath);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignmentId
            . '/status';

        $responseBody = $this->sendApiRequest($request);

        return $responseBody;
    }

    /**
     * Returns the status of all labels within a consignment and the other consignments in the same order.
     * Note - Order number is put in to the senderreference2 field in creating label request.
     * When a consignment id with that order number is queried with the 'related' resource, all consignments with the same order number specified in senderreference2 field are returned.
     *
     * @param string $consignmentId Unique id of a consignment
     * @return array An array containing all consignments relating to the specific consignment within the same order.
     * @throws NzPostClientAPIException
     */
    public function getRelatedLabelStatus($consignmentId)
    {
        $data = [
            'consignment_id' => $consignmentId
        ];

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');
        
        $this->validate($data, $schemaPath);
            
        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignmentId
            . '/related';

        $responseBody = $this->sendApiRequest($request);

        return $responseBody['consignments'];
    }

    /**
     * Download all labels generated within a consignment:
     *
     * @param string $consignmentId The unique id of a consignment
     * @param string $format The format of the labels to be downloaded. PDF if download all labels within a consignment. PNG if download a page image within a consignment
     * @param int $page The page number if download a label
     * @return array
     */
    public function downloadLabel($consignmentId, $format, $page = null)
    {
        $data = [
            'consignment_id' => $consignmentId,
            'format' => $format
        ];

        if ($page) {
            $data['page'] = $page;
        }

        $schemaPath = realpath(__DIR__ . '/schemas/' . basename(__FILE__, '.php') . '/' . __FUNCTION__ . '.json');
        
        $this->validate($data, $schemaPath);
        
        $params = http_build_query([
            'format' => $format,
            'page' => $page,
        ]);

        $request = $this->getApiUrl()
            . self::NZPOST_API_ENDPOINT
            . $consignmentId
            . '?'
            . $params;

        $responseBody = $this->sendApiRequest($request);
        return $responseBody;
    }
}