<?php

include 'custom/LastNextContacts/LastNextContacts.php';

class LastNextContactsTest extends PHPUnit_Framework_TestCase {

   public function test_1() {

      $bean = BeanFactory::newBean('Contacts');
      $bean->last_name = "013";
      $bean->save();
      $this->assertEquals('', $bean->date_planned_contact);
      $call = BeanFactory::newBean('Calls');
      $call->name = "013";
      $call->date_start = '2017-04-02 12:30:00';
      $call->status = 'Planned';
      $call->parent_id = $bean->id;
      $call->parent_type = 'Contacts';
      $call->save();
      $bean = BeanFactory::getBean('Contacts', $bean->id, array( 'disable_row_level_security' => true ));
      $this->assertNotEmpty($bean->date_planned_contact);
   }

}
