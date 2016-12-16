<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePasswordControllerTest extends WebTestCase {

    public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    /**
     *  EDIT  -----------------------------------------------------------------
     */
    public function testEdit() {

        print " -- CHANGE PASSWORD ------------------------------------------------------------\n\n.";
        printf("%-75s", " change password view with a direct get ... ");

     	$crawler = $this->client->request('GET', '/profile/change-password');

     	$this->_commonTests($crawler, 'Change password', 'change password');

        print "done.\n";
    }

    /**
     *  UPDATE  ---------------------------------------------------------------
     */
     public function testUpdate() {

         printf("%-75s", " change password update with a direct post ... ");

         $crawler = $this->_changePassword('1234', '123456');

         $this->assertTrue($this->client->getResponse()->isRedirect());

         $crawler = $this->client->followRedirect();

         $this->_commonTests($crawler, 'Profile details', 'profile details');

        //  var_dump($crawler->html()); die;

         // check table content
         $this->assertTrue($crawler->filter('table:contains("admin")')->count() == 1);

         // reset password
         $crawler = $this->_changePassword('123456', '1234');

         print "done.\n";
     }


    public function testUpdateFormErrors() {

        printf("%-75s", " change password update with a direct post INVALID DATA ... ");

        $crawler = $this->client->request('GET', '/profile/change-password');

        $this->assertTrue($crawler->filter('html:contains("change password")')->count() == 1);

        $form = $crawler->selectButton('Change password')->form();

        $form['fos_user_change_password_form[current_password]'] = '123';
        $form['fos_user_change_password_form[plainPassword][first]'] = '123456';
        $form['fos_user_change_password_form[plainPassword][second]'] = '1234567';

        $crawler = $this->client->submit($form);

        $this->_commonTests($crawler, 'Change password', 'change password');

        // check errors
        $this->assertTrue($crawler->filter('table:contains("The entered password is invalid")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("The entered passwords don\'t match")')->count() == 1);

        print "done.\n\n\n";
     }


    /**
     *  PRIVATE  --------------------------------------------------------------
     */
    private function _commonTests($crawler, $title, $content) {

        // title
        $this->assertTrue($crawler->filter("title:contains(\"$title\")")->count() == 1);

        // content
        $this->assertTrue($crawler->filter(".listContainer h1:contains(\"$content\")")->count() == 1);
    }

    private function _changePassword($old, $new) {

        $crawler = $this->client->request('GET', '/profile/change-password');

        $this->assertTrue($crawler->filter('html:contains("change password")')->count() == 1);

        $form = $crawler->selectButton('Change password')->form();

        $form['fos_user_change_password_form[current_password]'] = $old;
        $form['fos_user_change_password_form[plainPassword][first]'] = $new;
        $form['fos_user_change_password_form[plainPassword][second]'] = $new;

        $crawler = $this->client->submit($form);

        return $crawler;
    }
}
