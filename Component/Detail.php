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

    /**
     * 
     * @param array $collection
     * @param array $fields
     */
    public function __construct($collection, $fields = array()) {
        foreach ($fields as $key => $value) {
            $this->fields[$key] = new Field($key, $value);
            $this->columnAlign[$key] = $this->fields[$key]->getAlign();
        }
        foreach ($this->fields as $key => $value) {
            $this->footer[$key] = null;
        }
        $this->collection = $collection;

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

    public function addFieldsResult(FieldResult $fieldResult) {
        $this->fields[$fieldResult->getFieldName()] = new Field($fieldResult->getFieldName(), $fieldResult->getFieldLabel());
        $this->columnAlign[$fieldResult->getFieldName()] = $fieldResult->getAlign();
        $this->footer[$fieldResult->getFieldName()] = null;
        $this->fieldsResult[] = $fieldResult;
    }

    public function calculateData() {
        $fieldsNames = $this->getArrayFieldsName();
        foreach ($this->collection as $class) {
            $properties = $this->getProperties($class);
            $arrayData = array();
            foreach ($fieldsNames as $field) {
                foreach ($properties as $property) {
                    if ($property->getName() == $field) {
                        $property->setAccessible(true);
                        $arrayData[$property->getName()] = $property->getValue($class);
                    }
                }
            }
            $this->data[] = $arrayData;
        }
        $this->calculateFieldsResult();
    }

    private function calculateFieldsResult() {
        foreach ($this->fieldsResult as $fieldResult) {
            $fieldResult->calculate($this->data);
            $this->footer[$fieldResult->getFieldName()] = $fieldResult;
        }
    }

    private function getArrayFieldsName() {
        $fields = array();
        foreach ($this->fields as $key => $value) {
            $fields[] = $key;
        }
        return $fields;
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
            $fieldsNames = $this->getArrayFieldsName();
            foreach ($fieldsNames as $field) {
                if (!in_array($field, $arrayName)) {
                    return false;
                }
            }
        }
        return true;
    }

}
