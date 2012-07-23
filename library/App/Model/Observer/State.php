<?php

class App_Model_Observer_State {

    private $dirty;
    private $new;
    private $extra;

    public function __construct($dirty = true, $new = true)
    {
        $this->setDirty($dirty);
        $this->setNew($new);
    }

    public function getDirty()
    {
        return $this->dirty;
    }

    public function setDirty($dirty)
    {
        $this->dirty = (bool)$dirty;
    }

    public function getNew()
    {
        return $this->new;
    }

    public function setNew($new)
    {
        $this->new = (bool)$new;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

}
