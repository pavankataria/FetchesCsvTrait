<?php

/**
 * Author: Pavan Kataria
 * Date: 18/07/15.
 */
namespace PavanKataria;

use League\Csv\Reader;
use SplFileObject;
/**
 * Class PKEasyCsvHandlingTrait.
 */
trait FetchesCsv
{
    public function createFromPath($path);
    {
        $this->$reader = Reader::createFromPath($path)->stripBom(true);
        $this->$reader->setFlags(SplFileObject::SKIP_EMPTY);
        return $this;
    }

    public function csvContainsHeaders(&$originalHeadersReference = null)
    {
        $this->$csvContainsHeaders = true;
        if(isset($originalHeadersReference)){
            $this->$originalHeadersReference = $originalHeadersReference = $this->$reader->fetchOne($offset);
        }
        return this;
    }

    public function applyCustomerHeaders($customHeadersArray){
        $this->$customHeadersArray = $customHeadersArray;
        return $this;
    }
    public function setOffset($offsetInteger){
        $this->$offsetInteger = $offsetInteger;
        $this->$reader->setOffset($offset);
        return $this;
    }
    
    public function fetchAssoc(){
        $headersToUse = isset($this->$customHeadersArray) ? $this->$customHeadersArray : $this->$originalHeadersReference;
        $data = $reader->setOffset($offset)->fetchAssoc($headersToUse);
        return $data = $this->$reader->fetchAssoc($headersToUse);
    }



    /**
     * This method returns a referenced data object containing a named associative array
     * of the CSV inputted with the inputted custom database headers as it's keys.
     *
     * @param $pathToCSV
     * @param $csvContainsOriginalHeaders
     * @param $customHeaders
     * @return array
     */
    public function renderCSVWithCustomHeaders($pathToCSV, $csvContainsOriginalHeaders, $customHeaders)
    {
        return $this->renderCSV($pathToCSV, $csvContainsOriginalHeaders, $originalHeaders, $customHeaders);
    }


    /**
     * This method returns a referenced data object containing a named associative array
     * of the CSV inputted with the original database headers as it's keys.
     *
     * @param $pathToCSV
     * @param null $originalHeadersReference
     * @return array
     */
    public function renderCSVWithOriginalHeaders($pathToCSV, &$originalHeadersReference = null)
    {
        return $this->renderCSV($pathToCSV, true, $originalHeadersReference);
    }


    /**
     * This method returns a referenced data object containing a named associative array
     * of the CSV inputted with custom parameter options.
     *
     * The method will ignore customer headers being passed in if original headers are
     * supplied.
     *
     * @param $pathToCSV
     * @param bool $csvContainsOriginalHeaders
     * @param null $originalHeadersReference
     * @param null $customHeaders
     * @return array
     */
    public function renderCSV($pathToCSV, $csvContainsOriginalHeaders = false, &$originalHeadersReference = null, $customHeaders = null)
    {
        $reader = Reader::createFromPath($pathToCSV);

        // Set the reader's flag to remove empty lines.
        $reader->setFlags(SplFileObject::SKIP_EMPTY);

        $offset = 0;
        if ($csvContainsOriginalHeaders) {
            // retrieving the headers
            $originalHeadersReference = $reader->stripBom(true)->fetchOne($offset);
            $offset++;
        }

        // use the headers to make a named associative array
        $headersToUse = isset($customHeaders) ? $customHeaders : $originalHeadersReference;
        $data = $reader->setOffset($offset)->fetchAssoc($headersToUse);

        return $data;
    }

}
