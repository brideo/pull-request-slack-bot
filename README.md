#SlackBot for Getting PR's from BitBucket

This library will post PR's from BitBucket into the appropriate Slack channel.

I created this at the [Meanbee Hacknight](http://www.meetup.com/Meanbee-Hack-Nights/).

##Installation

    git clone git@github.com:brideo/pull-request-slack-bot.git
    composer install

There is a `config.php.sample`, copy this into your repo with the name `config.php` and fill it in with your credentials.

You will need to create a SlackBot and copy the token into your `config.php`. 

You can create a new BitBucket Api user at: https://bitbucket.org/account/user/{username or team}/api

You can setup a cron on your server to hit the `index.php` in the repo. 
