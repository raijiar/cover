<?php

namespace cover\filters;

use Cover;
use cover\base\ActionFilter;
use cover\web\Request;
use cover\web\Response;
use cover\web\TooManyRequestsHttpException;

/**
 * RateLimiter implements a rate limiting algorithm based on the [leaky bucket algorithm](http://en.wikipedia.org/wiki/Leaky_bucket).
 *
 * You may use RateLimiter by attaching it as a behavior to a controller or module, like the following,
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'rateLimiter' => [
 *             'class' => \cover\filters\RateLimiter::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * When the user has exceeded his rate limit, RateLimiter will throw a [[TooManyRequestsHttpException]] exception.
 *
 * Note that RateLimiter requires [[user]] to implement the [[RateLimitInterface]]. RateLimiter will
 * do nothing if [[user]] is not set or does not implement [[RateLimitInterface]].
 *
 * @since 1.0
 */
class RateLimiter extends ActionFilter
{
    /**
     * @var bool whether to include rate limit headers in the response
     */
    public $enableRateLimitHeaders = true;
    /**
     * @var string the message to be displayed when rate limit exceeds
     */
    public $errorMessage = 'Rate limit exceeded.';
    /**
     * @var RateLimitInterface the user object that implements the RateLimitInterface.
     * If not set, it will take the value of `Cover::$app->user->getIdentity(false)`.
     */
    public $user;
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;
    /**
     * @var Response the response to be sent. If not set, the `response` application component will be used.
     */
    public $response;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->request === null) {
            $this->request = Cover::$app->getRequest();
        }
        if ($this->response === null) {
            $this->response = Cover::$app->getResponse();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($this->user === null && Cover::$app->getUser()) {
            $this->user = Cover::$app->getUser()->getIdentity(false);
        }

        if ($this->user instanceof RateLimitInterface) {
            Cover::debug('Check rate limit', __METHOD__);
            $this->checkRateLimit($this->user, $this->request, $this->response, $action);
        } elseif ($this->user) {
            Cover::info('Rate limit skipped: "user" does not implement RateLimitInterface.', __METHOD__);
        } else {
            Cover::info('Rate limit skipped: user not logged in.', __METHOD__);
        }

        return true;
    }

    /**
     * Checks whether the rate limit exceeds.
     * @param RateLimitInterface $user the current user
     * @param Request $request
     * @param Response $response
     * @param \cover\base\Action $action the action to be executed
     * @throws TooManyRequestsHttpException if rate limit exceeds
     */
    public function checkRateLimit($user, $request, $response, $action)
    {
        list($limit, $window) = $user->getRateLimit($request, $action);
        list($allowance, $timestamp) = $user->loadAllowance($request, $action);

        $current = time();

        $allowance += (int) (($current - $timestamp) * $limit / $window);
        if ($allowance > $limit) {
            $allowance = $limit;
        }

        if ($allowance < 1) {
            $user->saveAllowance($request, $action, 0, $current);
            $this->addRateLimitHeaders($response, $limit, 0, $window);
            throw new TooManyRequestsHttpException($this->errorMessage);
        }

        $user->saveAllowance($request, $action, $allowance - 1, $current);
        $this->addRateLimitHeaders($response, $limit, $allowance - 1, (int) (($limit - $allowance + 1) * $window / $limit));
    }

    /**
     * Adds the rate limit headers to the response.
     * @param Response $response
     * @param int $limit the maximum number of allowed requests during a period
     * @param int $remaining the remaining number of allowed requests within the current period
     * @param int $reset the number of seconds to wait before having maximum number of allowed requests again
     */
    public function addRateLimitHeaders($response, $limit, $remaining, $reset)
    {
        if ($this->enableRateLimitHeaders) {
            $response->getHeaders()
                ->set('X-Rate-Limit-Limit', $limit)
                ->set('X-Rate-Limit-Remaining', $remaining)
                ->set('X-Rate-Limit-Reset', $reset);
        }
    }
}
