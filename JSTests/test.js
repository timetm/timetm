var colorizer = require('colorizer').create('Colorizer');

casper.options.clientScripts = ["../web/js/jquery-1.10.2.min.js"]

casper.test.begin('Login', 4, function(test) {

    casper.start('http://timetm');

    casper.then(function() {

        test.assertTitle('TimeTM - login', 'Login has correct title');

        // use jquery
        var submit = this.evaluate(function() {
            var submit = $("#_submit");
            return submit.val();
        });

        test.assertEquals(submit, 'Log in', "Found Log in button");

    });

    casper.then(function() {

        this.echo('ACTION  : log in with wrong credential', 'COMMENT');

        this.fill('form', {
            '_username': 'admin',
            '_password': '12345'
        }, true);
    });


    casper.waitForText('wrong email or password !', function () {

        test.assertTitle('TimeTM - login', 'Still on login page');
    });

    casper.then(function() {

        this.echo('ACTION : log in', 'COMMENT');

        this.fill('form', {
            '_username': 'admin',
            '_password': '1234'
        }, true);

    });


    casper.waitForUrl('http://timetm', function () {

        test.assertTitle('TimeTM - Dashboard', 'Landed on Dashboard');
    });

    casper.then(function() {

        this.clickLabel('calendar', 'a');

    });


    casper.then(function() {

        test.assertTitle('TimeTM - calendar', 'Landed on Dashboard');

    });

    casper.run(function(){
        test.done();
    })
});
