<?php

namespace DigitalPianism\NzPostClient;

interface ParcelLabelInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function createLabel(array $data);

    /**
     * @param string $consignementId
     * @return array
     */
    public function getLabelStatus($consignementId);

    /**
     * @param string $consignementId
     * @return array
     */
    public function getRelatedLabelStatus($consignementId);

    /**
     * @param string $consignementId
     * @param string $format
     * @param int $page
     * @return array
     */
    public function downloadLabel($consignementId, $format, $page);
}