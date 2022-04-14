<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ResponseFormatter
{
    public function formattedResponse(array $rawDocuments)
    {
        $formattedResponse = array_map(function ($rawDocument) {
            $fieldsFormatted = $this->formattedFields($rawDocument['fields']);
            return $fieldsFormatted;
        }, $rawDocuments);
        return $formattedResponse;
    }

    protected function formattedFields(array $fields)
    {
        $formattedFields = [];
        foreach ($fields as $key => $field) {
            $formattedFields[$key] = $field['content'];
        }
        return $formattedFields;
    }
}
