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
        return $formattedResponse;
    }

    protected function formattedFields(array $fields)
    {
        $formattedFields = [];
        foreach ($fields as $key => $field) {
            if(isset($field['content'])) {
                $content = $field['content'];
                $formattedFields[$key] = $content;
            }
        }
        return $formattedFields;
    }
}
