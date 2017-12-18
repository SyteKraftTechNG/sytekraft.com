<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/1/17
 * Time: 5:35 AM
 */
abstract class Model
{
    protected $id;
    protected $dataTable;
    protected $resultData;
    protected $parameters = [];
    protected $errors = [];

    function __construct($table, $identifier) {
        $dataTable = lcfirst(Word::pluralize($table));

        if (DataSource::hasTable($dataTable)) {
            $this->setDataTable($dataTable);
            if (DataSource::exists($this->dataTable, $identifier)) {
                $primaryKey = $this->primaryKey();
                $setterFunction = "set". ucfirst($primaryKey);
                $this->{$setterFunction}($identifier);
            } else {
                $this->setErrors("Record not found");
            }
        } else {
            $this->setErrors("Invalid table");
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Model
     */
    public function setId($id)
    {
        $this->id = $id;
        $table = $this->dataTable;

        if ($id > 0) {
            $row = DataSource::row($table, $id);
            if (!empty($row)) {
                $fieldNames = DataSource::fieldNames($table);
                foreach ($fieldNames as $key => $value) {
                    $set = "set". ucfirst($value);
                    if ($value != "id") $this->{$set}($row[$value]);
                }
            } else {
                $this->setErrors("Data not found.");
            }
        } else {
            $this->setErrors("No row matches this id.");
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataTable()
    {
        return $this->dataTable;
    }

    /**
     * @param mixed $dataTable
     * @return Model
     */
    public function setDataTable($dataTable)
    {
        $this->dataTable = $dataTable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultData()
    {
        return $this->resultData;
    }

    /**
     * @param mixed $resultData
     * @return Model
     */
    public function setResultData($resultData)
    {
        $this->resultData = $resultData;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return Model
     */
    public function setParameters(array $parameters = null)
    {
        if (isset($parameters)) {
            foreach($parameters as $key => $value) {
                $this->addParameter($key, $value);
            }
        } else {
            foreach($this->schemaAttributes() as $property) {
                $getterFn = 'get'. ucfirst($property);
                $value = $this->{$getterFn}();
                if (isset($value)) $this->addParameter($property, $value);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return Model
     */
    public function setErrors($errors)
    {
        if (is_array($errors)) {
            $this->errors = array_merge($this->errors, $errors);
        } else {
            $this->errors[] = $errors;
        }
        return $this;
    }

    /**
     * Adds a key-value pair to the parameters
     * @param $key
     * @param $value
     * @return $this
     */
    public function addParameter($key, $value = null) {
        if (in_array($key, $this->schemaAttributes())) {
            $getter = 'get'. ucfirst($key);
            $this->parameters = array_merge($this->parameters, [$key => (isset($value) ? $value : $this->{$getter}())]);
        }
        return $this;
    }

    /**
     * Gets the attributes of the data schema.
     * @return array
     */
    public function schemaAttributes() {
        return DataSource::fieldNames($this->getDataTable());
    }

    /**
     * Returns the full description of the data schema.
     * @return array
     */
    public function schemaDescription() {
        return (array) DataSource::fields($this->getDataTable());
    }

    /**
     * Returns the primary field for the Model.
     * @return null
     */
    public function primaryKey() {
        $primaryKey = null;
        foreach ($this->schemaDescription() as $value) {
            if (array_key_exists('Key', $value) && ($value['Key'] = 'PRI')) {
                $primaryKey = $value['Field'];
                break;
            }
        }
        return $primaryKey;
    }

    public function findSingle($loose = false) {
        $join = $loose ? " OR " : " AND ";
        $index = $search = [];
        $params = $this->getParameters();
        $primaryKey = $this->primaryKey();

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $search[] = "$key = :$key";
                $index[":$key"] = $value;
            }

            $query = "SELECT $primaryKey FROM ". $this->getDataTable(). " WHERE ". join($join, $search). " ORDER BY $primaryKey DESC LIMIT 1";

            print_r($params);

            DataSource::query($query, $index);
            $result = DataSource::getResult();
            $id = (!empty($result)) ? $result[0][$primaryKey] : 0;
            $this->setId($id);
        }
        return $this;
    }

    public function save() {
        $insertFields = $insertValues = $updateSet = $params = array();
        $insert = $update = "";
        $fieldNames = DataSource::fieldNames($this->dataTable);
        $primaryKey = $this->primaryKey();
        $identifier = $this->{$primaryKey};

        foreach ($fieldNames as $key => $value) {
            $valueFxn = "get". ucfirst($value);
            if ($value != "id" && (null !== $this->{$valueFxn}())) {
                $insertFields[] = $value;
                $insertValues[] = ":$value";
                $updateSet[] = "$value = :$value";
                $params[":$value"] = $this->{$valueFxn}();
            }
        }

        $insert = "INSERT INTO ". $this->dataTable. "(". join(", ", $insertFields). ") VALUES (". join(", ", $insertValues). ")";
        $update = "UPDATE ". $this->dataTable. " SET ". join(", ", $updateSet). " WHERE $primaryKey = :id";

        $identifier = $this->findSingle()->{$primaryKey};
        $setterFunction = "set". ucfirst($primaryKey);

        if ($identifier == 0) { // insert
            DataSource::query($insert, $params);
            $this->{$setterFunction}(DataSource::getLastId());
        } else { // update
            $params[":id"] = $identifier;
            DataSource::query($update, $params);
            $this->{$setterFunction}($identifier);
        }
        return $this;
    }

    public function delete() {
        $primaryKey = $this->{$this->primaryKey()};
        DataSource::query("DELETE FROM ". $this->dataTable. " WHERE ". $this->primaryKey(). " = :pKey", [":pKey" => $primaryKey]);
        return $this;
    }
    
}
