<?php

namespace App\Models;

class IneModel
{
    public $ineModel;
    public $backIneInformation;
    public $frontIneInformation;

    public function __construct($ineModel, $backIneInformation, $frontIneInformation)
    {
        $this->ineModel = $ineModel;
        $this->backIneInformation = $backIneInformation;
        $this->frontIneInformation = $frontIneInformation;
    }

    public function getBackInformation()
    {
        if (strcmp($this->ineModel, 'D') === 0) {
            return $this->modelDInformation($this->backIneInformation);
        }
        if (strcmp($this->ineModel, 'C') === 0) {
            return $this->modelCInformation($this->backIneInformation, $this->frontIneInformation);
        }
        return $this->latestModelInformation($this->backIneInformation);
    }

    protected function modelDInformation($backIneInformation)
    {
        $documentIdentifier = trim($backIneInformation['identificador_documento']);
        $citizenIdentifier = trim($backIneInformation['identificador_ciudadano']);
        $cicFormatted = $this->getCicFormatted($documentIdentifier);
        $citizenIdentifierFormatted = implode($this->clearSpecialCharsFromArray('<', str_split($citizenIdentifier)));
        return [
            'modelo' => $this->ineModel,
            'cic' => $cicFormatted,
            'ocr' => $citizenIdentifierFormatted,
        ];
    }

    protected function modelCInformation($backIneInformation, $frontInformation)
    {
        $ocr = trim($backIneInformation['ocr']);
        $emision = $this->extractEmision($frontInformation['registro']);
        return [
            'modelo' => $this->ineModel,
            'clave_elector' => $frontInformation['clave_elector'],
            'ocr' => $ocr,
            'emision' => $emision,
        ];
    }

    protected function latestModelInformation($backIneInformation)
    {
        $documentIdentifier = trim($backIneInformation['identificador_documento']);
        $citizenIdentifier = trim($backIneInformation['identificador_ciudadano']);
        $cicFormatted = $this->getCicFormatted($documentIdentifier);
        $citizenIdentifierFormatted = $this->getCitizenIdFormatted($citizenIdentifier);
        return [
            'modelo' => $this->ineModel,
            'cic' => $cicFormatted,
            'identificador_ciudadano' => $citizenIdentifierFormatted,
        ];
    }

    protected function getCicFormatted($documentIdentifier)
    {
        $cic = trim(str_replace('IDMEX', '', $documentIdentifier));
        $cicArray = str_split($cic);
        $cicArray = $this->clearSpecialCharsFromArray('<', $cicArray);
        $cicLength = count($cicArray) - 1;
        unset($cicArray[$cicLength]);
        return implode($cicArray);
    }

    protected function getCitizenIdFormatted($citizenIdentifier) {
        $citizenIdentifier = implode($this->clearSpecialCharsFromArray('<', str_split($citizenIdentifier)));
        $letterToDeleteFromString = substr($citizenIdentifier, 0, 4);
        $citizenIdentifierFormatted = str_replace($letterToDeleteFromString, '', $citizenIdentifier);
        return $citizenIdentifierFormatted;
    }

    protected function clearSpecialCharsFromArray($specialChar, $array) {
        foreach ($array as $key => $letter) {
            if ((strcmp($letter, $specialChar)) === 0) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    protected function extractEmision($registro) {
        $text = trim($registro);
        $arrayText = explode(' ', $text);
        $emision = end($arrayText);
        return $emision;
    }
}
