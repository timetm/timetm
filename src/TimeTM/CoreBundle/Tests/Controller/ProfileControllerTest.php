<?php

namespace TimeTM\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase {

    public function setUp() {

		$this->client = static::createClient(array(), array(
			'PHP_AUTH_USER' => 'admin',
			'PHP_AUTH_PW'   => '1234',
		));
	}

    /**
     *  SHOW  -----------------------------------------------------------------
     */
    public function testShow() {

        print " -- PROFILE --------------------------------------------------------------------\n\n.";
        printf("%-75s", " profile view with a direct get ... ");

        $crawler = $this->client->request('GET', '/profile/');

        $this->_commonTests($crawler, 'Profile details', 'profile details');

        $this->assertTrue($crawler->filter('table:contains("admin")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("theme-black")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("default")')->count() == 1);

        print "done.\n";
    }

    /**
     *  EDIT  -----------------------------------------------------------------
     */
    public function testEdit() {

        printf("%-75s", " profile edit with a direct get ... ");

     	$crawler = $this->client->request('GET', '/profile/edit');

     	$this->_commonTests($crawler, 'Edit profile', 'edit profile');

        print "done.\n";
    }

    /**
     *  UPDATE  ---------------------------------------------------------------
     */
    public function testUpdate() {

        printf("%-75s", " task update with a direct post ... ");

        $crawler = $this->client->request('GET', '/profile/edit');

        $this->assertTrue($crawler->filter('html:contains("edit profile")')->count() == 1);

        $form = $crawler->selectButton('Update')->form();

        $form['fos_user_profile_form[email]'] = 'dummy@email.com';
        $form['fos_user_profile_form[current_password]'] = '1234';

        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        $this->_commonTests($crawler, 'Profile details', 'profile details');

        // check table content
        $this->assertTrue($crawler->filter('table:contains("dummy@email.com")')->count() == 1);

        print "done.\n\n\n";
    }

    public function testUpdateFormErrors() {

        printf("%-75s", " profile update with a direct post INVALID DATA ... ");

        $crawler = $this->client->request('GET', '/profile/edit');

        $this->assertTrue($crawler->filter('html:contains("edit profile")')->count() == 1);

        $form = $crawler->selectButton('Update')->form();

        $form['fos_user_profile_form[username]'] = '';
        $form['fos_user_profile_form[email]'] = 'a@a';
        $form['fos_user_profile_form[current_password]'] = '123';

        $crawler = $this->client->submit($form);

        $this->_commonTests($crawler, 'Edit profile', 'edit profile');

        // check errors
        $this->assertTrue($crawler->filter('table:contains("The entered password is invalid")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("The email is not valid")')->count() == 1);
        $this->assertTrue($crawler->filter('table:contains("Please enter a username")')->count() == 1);

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
}
