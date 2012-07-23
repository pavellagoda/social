<?php

class Form_BaseForm extends Zend_Form {

    public function init() {
        parent::init();
    }

    protected function removeAllDecorators(Zend_Form_Element $el) {
        $el->removeDecorator('HtmlTag')
                ->removeDecorator('Label')
                ->removeDecorator('Errors');
        return $el;
    }
    
    protected function removeDecoratorsForAll() {
        $elements = $this->getElements();
        foreach ($elements as $el) {
            $el->setAttrib('class', 'form-element');
            $this->removeAllDecorators($el);
        }
    }

}