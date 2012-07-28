<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_Firms', 'doctrine');

/**
 * Model_Base_Firms
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $main_description
 * @property string $secret_description
 * @property Doctrine_Collection $Catalogues
 * @property Doctrine_Collection $Favorites
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Firms extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('firms');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '64',
             ));
        $this->hasColumn('main_description', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('secret_description', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_Catalogues as Catalogues', array(
             'local' => 'id',
             'foreign' => 'firm_id'));

        $this->hasMany('Model_Favorites as Favorites', array(
             'local' => 'id',
             'foreign' => 'firm_id'));
    }
}