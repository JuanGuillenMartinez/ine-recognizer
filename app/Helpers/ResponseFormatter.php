<?php

namespace App\Helpers;

class ResponseFormatter
{
    public function formattedResponse(array $rawDocuments)
    {
        $formattedResponse = array_map(function ($rawDocument) {
            $fieldsFormatted = $this->formattedFields($rawDocument['fields']);
            return $fieldsFormatted;
        }, $rawDocuments);
        $formattedResponse = $this->clearGenderField($formattedResponse);
        return $formattedResponse;
    }

    protected function formattedFields(array $fields)
    {
        $formattedFields = [];
        foreach ($fields as $key => $field) {
            if (isset($field['content'])) {
                $content = $field['content'];
                $formattedFields[$key] = $content;
            }
        }
        return $formattedFields;
    }

    protected function clearGenderField(array $formattedResponse)
    {
        foreach ($formattedResponse as $key => $item) {
            if (isset($item['sexo'])) {
                $aux = str_replace('SEXO', '', $item['sexo']);
                $aux = trim($aux);
                $formattedResponse[$key]['sexo'] = str_replace('_', '', $aux);
            }
        }
        return $formattedResponse;
    }
}
