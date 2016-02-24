<?php

namespace Brideo\PullRequestSlack\App;

class Config
{

    public $channelToRepoMap = [
        ''
    ];

    protected $oauthCredentials;
    protected $bitBucketSecret;
    protected $slackToken;
    protected $slackToRepoMap;

    public function __construct(array $config)
    {
        $this->oauthCredentials = $config['bitbucket'];
        $this->bitBucketSecret = $config['bitbucket']['oauth_consumer_secret'];
        $this->slackToken = $config['slack']['token'];
        $this->slackToRepoMap = $config['channelToRepoMap'];
    }


    /**
     * @return mixed
     */
    public function getSlackToken()
    {
        return $this->slackToken;
    }

    /**
     * @param mixed $slackToken
     */
    public function setSlackToken($slackToken)
    {
        $this->slackToken = $slackToken;
    }

    /**
     * @return mixed
     */
    public function getSlackToRepoMap()
    {
        return $this->slackToRepoMap;
    }

    /**
     * @param mixed $slackToRepoMap
     */
    public function setSlackToRepoMap($slackToRepoMap)
    {
        $this->slackToRepoMap = $slackToRepoMap;
    }

    /**
     * @return mixed
     */
    public function getOauthCredentials()
    {
        return $this->oauthCredentials;
    }

    /**
     * @param mixed $oauthCredentials
     */
    public function setOauthCredentials($oauthCredentials)
    {
        $this->oauthCredentials = $oauthCredentials;
    }
}
