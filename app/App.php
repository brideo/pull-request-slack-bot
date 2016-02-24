<?php

namespace Brideo\PullRequestSlack\App;

use Bitbucket\API\Authentication\Basic;
use Bitbucket\API\Http\Listener\OAuthListener;
use Bitbucket\API\Repositories\PullRequests;
use Exception;
use PhpSlack\Slack;
use PhpSlack\Utils\RestApiClient;

class App
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PullRequests
     */
    protected $pullRequests;

    /**
     * @var Slack
     */
    protected $slack;

    /**
     * App constructor.
     *
     * @param PullRequests $pullRequests
     * @param Slack        $slack
     */
    public function __construct(
        PullRequests $pullRequests,
        Slack $slack,
        Config $config = null
    ) {
        $this->pullRequests = $pullRequests;
        $this->slack = $slack;
        if(! $config ) {
            $config =  self::getConfig();
        }

        $this->config = $config;
    }

    /**
     * Create instance of self.
     *
     * @return $this
     */
    static public function create()
    {
        $config = self::getConfig();
        $pullRequests = self::getPullRequests($config);
        $slack = self::getSlack($config);

        return new self($pullRequests, $slack, $config);
    }

    /**
     * @return mixed
     */
    public static function getConfigFilePath()
    {
        return require_once __DIR__ . '/../config.php';
    }

    /**
     * @return Config
     */
    public static function getConfig()
    {
        return new Config(self::getConfigFilePath());
    }

    /**
     * @param Config $config
     *
     * @return PullRequests
     */
    public static function getPullRequests(Config $config)
    {
        $pullRequests = new PullRequests();
        $pullRequests->getClient()->addListener(new OAuthListener($config->getOauthCredentials()));

        return $pullRequests;
    }

    /**
     * @param $config
     *
     * @return Slack
     */
    public static function getSlack(Config $config)
    {
        $token = $config->getSlackToken();
        $client = new RestApiClient($token);

        return new Slack($client);
    }

    /**
     * Send pull requests from Bitbucket to Slack.
     *
     * @return $this
     * @throws Exception
     */
    public function run()
    {
        foreach($this->config->getSlackToRepoMap() as $channel => $map) {
            $response = new Messages($this->pullRequests, $map['owner'], $map['repository']);

            foreach($response->getMessages() as $message) {
                $this->slack->sendMessage($channel, $message);
            }
        }

        return $this;
    }
}
