<?php

namespace Brideo\PullRequestSlack\App;

use Bitbucket\API\Repositories\PullRequests;

/**
 * Class Messages
 *
 * Helper class to get messages from BitBucket
 *
 * @package Brideo\PullRequestSlack\App
 */
class Messages
{

    const STATE = 'OPEN';

    /**
     * @var PullRequests
     */
    protected $pullRequests;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var array;
     */
    protected $messages = [];

    /**
     * Messages constructor.
     *
     * @param PullRequests $pullRequests
     * @param string       $username
     * @param string       $repository
     */
    public function __construct(
        PullRequests $pullRequests,
        $username,
        $repository
    ) {

        $this->pullRequests = $pullRequests;
        $this->repository = $repository;
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        if (!$this->messages) {
            $this->setMessages();
        }

        return $this->messages;
    }

    /**
     * @return \Buzz\Message\MessageInterface
     */
    public function getOpenPullRequests()
    {
        $response = $this->pullRequests->all($this->username, $this->repository , ['state' => static::STATE]);
        if ( ! $response ) {
            return null;
        }

        return json_decode($response->getContent());
    }

    /**
     * @return \Bitbucket\API\Http\ClientInterface
     */
    protected function getClient()
    {
        return $this->pullRequests->getClient();
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    protected function getPullRequestApiUrl($response)
    {
        return $response->links->self->href;
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    protected function getPullRequestInfo($response)
    {
        return json_decode($this->getClient()->get($this->getPullRequestApiUrl($response))->getContent());
    }

    /**
     * @return string
     */
    protected function setMessages()
    {
        if (!$this->getOpenPullRequests()) {
            return $this;
        }

        foreach ($this->getOpenPullRequests()->values as $response) {
            $pullRequest = $this->getPullRequestInfo($response);

            $reviewers = [];
            foreach ($pullRequest->reviewers as $reviewer) {
                $reviewers[$reviewer->username] = $reviewer->display_name;
            }

            $this->messages[] = sprintf(
                "*Open Pull Request*: %s
            *By*: %s
            *Assigned to*: %s
            %s",
                $pullRequest->title,
                $pullRequest->author->display_name,
                implode($reviewers, ', '),
                $pullRequest->links->html->href
            );
        }

        return $this;
    }
}
