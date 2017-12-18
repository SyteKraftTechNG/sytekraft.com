<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 12/15/17
 * Time: 2:39 AM
 */
trait CommonDataAPI
{

    public function index() {
        $httpMethod = $this->getHttpMethod();
        switch ($httpMethod) {
            case "GET":
                $qualifiers = $this->getQualifiers();
                $recordsPerPage = $qualifiers['recordsPerPage'];
                $page = $qualifiers['currentPage'];

                $this->setResult($this->getModel()->getAll($recordsPerPage, $page));
                break;

            case "POST":
                $this->create();
                break;
        }
    }
    
    public function single($id) {
        switch ($this->getHttpMethod()) {
            case "GET":
                if ($id > 0) {
                    $result = $this->getModel()->setId($id)->asArray();
                    $this->setResult($result);
                }
                break;

            case "PUT":
                $this->update($id);
                break;

            case "DELETE":
                $this->delete($id);
                break;
        }
    }

    public function create() {
        $attributes = $this->getModel()->schemaAttributes();
        $data = $this->getData();

        $passwords = [
            'UsersAPI' => ['password']
        ];

        foreach ($attributes as $key => $attribute) {
            if (array_key_exists($attribute, $data) && $attribute != 'id') {
                $setterFn = 'set'. ucfirst($attribute);

                $newValue = $data[$attribute];

                // if the new value is to be a secret, then:
                if (array_key_exists(get_class(), $passwords) && in_array($attribute, $passwords[get_class()])) $newValue = md5($newValue);

                // set the new value
                $this->getModel()->{$setterFn}($newValue);
            }
        }

        // does this set of values exist on another record?
        $id = $this->getModel()->setParameters()->findSingle()->getId();

        if ($id > 0) {
            $this->setErrors('This resource may already exist.');
        } else {
            $this->getModel()->save();
            $this->getModel()->saveSearchItem();
            $this->setResult($this->getModel()->asArray());
            $this->setMessage("Resource created successfully.");
        }
    }

    public function update($id) {
        if ($id < 1) $this->setErrors('Resource ID is not valid.');

        if (!$this->hasErrors()) {
            $this->getModel()->setId($id);
            $attributes = $this->getModel()->schemaAttributes();

            foreach ($this->getData() as $key => $value) {
                if (in_array($key, $attributes)) {
                    $setterFn = 'set'. ucfirst($key);
                    $getterFn = 'get'. ucfirst($key);

                    $currentValue = $this->getModel()->{$getterFn}();
                    if ($value != $currentValue) $this->getModel()->{$setterFn}($value);
                }
            }

            $this->getModel()->save();
            $this->setResult($this->getModel()->asArray());
            $this->setMessage("Resource updated successfully.");
        }
    }

    public function delete($id) {
        $this->getModel()->setid($id);
        $this->getModel()->delete();

        if (is_null($this->getModel())) {
            $this->setMessage('Resource deleted.');
        } else {
            $this->setErrors('Delete failed');
        }
    }

    public function search($string) {
    }

    /**
     * Loads the corresponding model for this Data API.
     */
    private function loadModel($id = 0) {
        $class = get_class();
        if (is_a($this, 'API')) {
            $modelName = Word::singularize(ucfirst(substr($class, 0, -3)));
            $this->setModel(new $modelName);
            if ($id > 0) $this->getModel()->setId($id);
        }
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return DataAPI
     */
    public function setModel($model) {
        $this->model = $model;
        return $this;
    }
}
