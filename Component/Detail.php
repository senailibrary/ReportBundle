<?php

namespace SenaiLibrary\ReportBundle\Component;

use Symfony\Component\Config\Definition\Exception\Exception;
use SenaiLibrary\ReportBundle\Component\FieldResult;
use SenaiLibrary\ReportBundle\Component\Field;

/**
 * Description of Detail
 *
 * @author Rafael
 */
class Detail {

    /**
     *
     * @var array 
     */
    private $collection;
    private $data = array();
    private $fieldsResult = array();
    private $fields = array();
    private $footer = array();
    private $columnAlign = array();
    private $columnFormat = array();
    private $title;

    /**
     * 
     * @param array $collection
     * @param array $fields
     */
    public function __construct($collection, $fields = array()) {
        $this->collection = $collection;

        if ($fields) {
            foreach ($fields as $key => $value) {
                $this->fields[$key] = new Field($key, $value);
            }
        } else {
            foreach ($this->getProperties($this->collection[0]) as $field) {
                $this->fields[$field->getName()] = new Field($field->getName(), $field->getName());
            }
        }


        foreach ($this->fields as $key => $value) {
            $this->footer[$key] = null;
        }

        if (!$this->collection) {
            throw new Exception('Collection must have a value.');
        }

        if (!$this->validateDetail($this->collection[0])) {
            throw new Exception('There are no columns in the collection.');
        }
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getCollection() {
        return $this->collection;
    }

    /**
     * 
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    public function getData() {
        return $this->data;
    }

    public function getFooter() {
        return $this->footer;
    }

    public function getColumnAlign() {
        return $this->columnAlign;
    }

    public function getColumnFormat() {
        return $this->columnFormat;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function addFields(Field $field) {
        $this->fields[$fieldResult->getFieldName()] = $field;
    }

    public function getFieldByName($fieldName) {
        foreach ($this->fields as $field) {
            if ($field->getFieldName() == $fieldName) {
                return $field;
            }
        }
    }

    public function addFieldsResult(FieldResult $fieldResult) {
        $this->fields[$fieldResult->getFieldName()] = $fieldResult;
        $this->footer[$fieldResult->getFieldName()] = null;
        $this->fieldsResult[] = $fieldResult;

        return $fieldResult;
    }

    public function calculateData() {
        foreach ($this->collection as $class) {
            $properties = $this->getProperties($class);
            $arrayData = array();
            foreach ($this->fields as $field) {
                foreach ($properties as $property) {
                    if ($property->getName() == $field->getFieldName()) {
                        $property->setAccessible(true);
                        $arrayData[$field->getFieldName()] = $property->getValue($class);
                        $this->columnAlign[$field->getFieldName()] = $field->getAlign();
                        $this->columnFormat[$field->getFieldName()] = $field->getFormat() ? $field->getFormat() : $this->getType($arrayData[$field->getFieldName()]);
                        $this->columnCaptalize[$field->getFieldName()] = $field->getCapitalize();
                    }
                }
            }
            $this->data[] = $arrayData;
        }
        $this->calculateFieldsResult();
    }

    private function getType($value) {
        switch (gettype($value)) {
            case "integer":
                return Field::FormatInteger;
            case "double":
                return Field::FormatDecimal;
            case "string":
                return Field::FormatText;
        }
        return is_object($value) && get_class($value) == "DateTime" ? Field::FormatDateTime : Field::FormatText;
    }

    private function calculateFieldsResult() {
        foreach ($this->fieldsResult as $fieldResult) {
            $fieldResult->calculate($this->data);
            $this->footer[$fieldResult->getFieldName()] = $fieldResult;
        }
    }

    private function getProperties($class) {
        $reflect = new \ReflectionClass($class);
        return $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE);
    }

    private function getPropertyNameToArray($class) {
        $properties = $this->getProperties($class);
        $arrayName = array();
        foreach ($properties as $property) {
            $arrayName[$property->getName()] = $property->getName();
        }
        return $arrayName;
    }

    private function validateDetail($class) {
        if ($class) {
            $arrayName = $this->getPropertyNameToArray($class);
            foreach ($this->fields as $field) {
                if (!in_array($field->getFieldName(), $arrayName)) {
                    return false;
                }
            }
        }
        return true;
    }

}
