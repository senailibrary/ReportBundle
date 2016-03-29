<?php

namespace SenaiLibrary\ReportBundle\Component;

use SenaiLibrary\ReportBundle\Component\Field;

/**
 * Description of FieldResult
 *
 * @author Rafael
 */
class FieldResult extends Field {

    const MIN_VALUE = -2147483647;
    const Sum = 'sum';
    const Count = 'count';
    const Min = 'min';
    const Max = 'max';
    const Avg = 'avg';
    const First = 'first';
    const Last = 'last';

    private $operation;
    private $data;
    private $computeNull = true;
    private $value = 0;
    private $decimal = 2;
    private $text;

    public function __construct($fieldName, $fieldLabel, $operation, $data = null) {
        $this->data = $data;
        $this->operation = $operation;
        $this->setFieldName($fieldName);
        $this->setFieldLabel($fieldLabel);
        $this->setFormat(Field::FormatDecimal);
        $this->setAlign(Field::AlignRight);
    }

    public function getValue() {
        return $this->value;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function getDecimal() {
        return $this->decimal;
    }

    public function setDecimal($decimal) {
        $this->decimal = $decimal;
        return $this;
    }

    public function getComputeNull() {
        return $this->computeNull;
    }

    public function setComputeNull($computeNull) {
        $this->computeNull = $computeNull;
        return $this;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function calculate($data) {
        $this->data = $data;
        switch ($this->operation) {
            case Self::Sum:
                $this->builtInSum();
                break;
            case Self::Count:
                $this->builtInCount();
                break;
            case Self::Min:
                $this->builtInMin();
                break;
            case Self::Max:
                $this->builtInMax();
                break;
            case Self::Avg:
                $this->builtInAvg();
                break;
            case Self::First:
                $this->builtInFirst();
                break;
            case Self::Last:
                $this->builtInLast();
                break;
        }
    }

    private function isCalculate($value) {
        return isset($value) || ($this->computeNull && !isset($value));
    }

    private function builtInSum() {
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()])) {
                $this->value += $data[$this->getFieldName()];
            }
        }
    }

    private function builtInCount() {
        $value = 0;
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()])) {
                $value ++;
            }
        }
        $this->value = $value;
    }

    private function builtInMin() {
        $value = PHP_INT_MAX;
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()]) && $value > $data[$this->getFieldName()]) {
                $value = $data[$this->getFieldName()];
            }
        }
        $this->value = $value;
    }

    private function builtInMax() {
        $value = Self::MIN_VALUE;
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()]) && $value < $data[$this->getFieldName()]) {
                $value = $data[$this->getFieldName()];
            }
        }
        $this->value = $value;
    }

    private function builtInAvg() {
        $value = 0;
        $count = 0;
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()])) {
                $value += $data[$this->getFieldName()];
                $count ++;
            }
        }
        $this->value = $value / $count;
    }

    private function builtInFirst() {
        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()])) {
                $this->value = $data[$this->getFieldName()];
                break;
            }
        }
    }

    private function builtInLast() {

        foreach ($this->data as $data) {
            if ($this->isCalculate($data[$this->getFieldName()])) {
                $arrayData[] = $data[$this->getFieldName()];
            }
        }
        $data = end($arrayData);
        $this->value = $data;
    }

}
