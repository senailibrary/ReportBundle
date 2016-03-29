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
    const FormatText = 'text';
    const FormatDecimal = 'decimal';
    const FormatInteger = 'integer';
    const FormatDate = 'date';
    const FormatDateTime = 'datetime';
    const FormatCurrency = 'currency';

    private $fieldName;
    private $fieldLabel;
    private $align = Self::AlignLeft;
    private $length = 'auto';
    private $format = null;
    private $fontWeight = 'normal';
    private $capitalize = false;

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

    public function getLength() {
        return $this->length;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setLength($length) {
        $this->length = $length;
        return $this;
    }

    public function setFormat($format) {
        $this->format = $format;
        return $this;
    }

    public function getFontWeight() {
        return $this->fontWeight;
    }

    public function setFontWeight($fontWeight) {
        $this->fontWeight = $fontWeight;
        return $this;
    }

    public function getCapitalize() {
        return $this->capitalize;
    }

    public function setCapitalize($capitalize) {
        $this->capitalize = $capitalize;
        return $this;
    }

}
