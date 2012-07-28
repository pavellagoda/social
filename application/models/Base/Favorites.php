<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_Favorites', 'doctrine');

/**
 * Model_Base_Favorites
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $firm_id
 * @property Model_Users $Users
 * @property Model_Firms $Firms
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Favorites extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('favorites');
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('firm_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Users as Users', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Model_Firms as Firms', array(
             'local' => 'firm_id',
             'foreign' => 'id'));
    }
}