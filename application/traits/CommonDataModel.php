<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/17/17
 * Time: 7:45 AM
 */
trait CommonDataModel
{

    public function asArray() {
        $array = DataSource::row($this->getDataTable(), $this->getId());
        if (array_key_exists('password', $array)) unset($array['password']);
        return $array;
    }

    public function getAll($rowsPerPage = 10, $page = 1) {
        $result = [];
        $query = "SELECT id FROM ". $this->getDataTable();
        $recordSet = DataSource::recordSet($query, null, $rowsPerPage, $page);

        foreach ($recordSet['data'] as $record) {
            if ($record['id'] > 0) {
                $object = clone $this;
                $object->setId($record['id']);
                $result[] = $object->asArray();
            }
        }
        return $result;
    }

    public function saveSearchItem() {
        $object = new Object();
        $object->setSchema($this->getDataTable());
        $object->setRecordId($this->getId());
        $object->setReference();
        $object->setCreated();

        // set the search string
        $object->setSearchString(join(" ", $this->asArray()));

        $object->save();

        return $this;
    }
}
