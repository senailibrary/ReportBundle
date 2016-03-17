<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SenaiLibrary\ReportBundle\Component;

/**
 * Description of Field
 *
 * @author Rafael
 */
class Field {
    
    const AlignRight = 'right';
    const AlignLeft = 'left';

    private $fieldName;
    private $fieldLabel;
    private $align = Self::AlignLeft;
    
    public function __construct($fieldName, $fieldLabel) {
        $this->fieldName = $fieldName;
        $this->fieldLabel = $fieldLabel;
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function getFieldLabel() {
        return $this->fieldLabel;
    }

    public function getAlign() {
        return $this->align;
    }

    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function setFieldLabel($fieldLabel) {
        $this->fieldLabel = $fieldLabel;
        return $this;
    }

    public function setAlign($align) {
        $this->align = $align;
        return $this;
    }

}
