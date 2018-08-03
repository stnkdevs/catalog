<?php

namespace helpers;

class InstanceArrayFilter extends FilterIterator {
    private $type;
    public function __construct($iterator, $type) {
        parent::__construct($iterator);
        $this->type=$type;
    }   
    public function accept()
    {
        return  parant::current() instanceof $this->type;
    }   
}
