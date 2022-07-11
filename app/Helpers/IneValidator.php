<?php

namespace App\Helpers;

use App\Exceptions\WrongOCRLecture;

class IneValidator
{

    public $dataExtracted;
    public $fieldsRequired = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'clave_elector',
        'curp',
        'emitido_por'
    ];

    public function __construct($dataExtracted)
    {
        $this->dataExtracted = $dataExtracted;
    }

    public function validate()
    {
        $this->allFieldsRequiredExist($this->dataExtracted, $this->fieldsRequired);
        $this->dataExtracted['clave_elector'] = $this->validateClaveElector($this->dataExtracted['clave_elector']);
        $this->dataExtracted['curp'] = $this->validateCurp($this->dataExtracted['curp']);
        return $this->dataExtracted;
    }

    public function allFieldsRequiredExist($dataExtracted, $fieldsRequired)
    {
        foreach ($fieldsRequired as $key => $required) {
            $fieldIsSet = isset($dataExtracted[$required]);
            if (!$fieldIsSet) {
                throw new WrongOCRLecture(json_encode(['field' => $required]), 406);
            }
        }
    }

    public function validateClaveElector($claveElector)
    {
        $regex = '/[A-Z]{6}[0-9]{8}[A-Z]{1}[0-9]{3}/';
        $isValid = preg_match($regex, $claveElector, $coincidences);
        if ($isValid) {
            return $coincidences[0];
        } else {
            throw new WrongOCRLecture('{"field":"clave_elector"}', 406);
        }
    }
    
    public function validateCurp($curp)
    {
        $regex = "/([A-Z][A,E,I,O,U,X][A-Z]{2})(\d{2})((01|03|05|07|08|10|12)(0[1-9]|[12]\d|3[01])|02(0[1-9]|[12]\d)|(04|06|09|11)(0[1-9]|[12]\d|30))([M,H])(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)([B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3})([0-9,A-Z][0-9])/";
        $isValid = preg_match($regex, $curp, $coincidences);
        if ($isValid) {
            return $coincidences[0];
        } else {
            throw new WrongOCRLecture('{"field":"curp"}', 406);
        }
    }
}
