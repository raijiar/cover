<?php

namespace cover\filters\auth;

use cover\web\IdentityInterface;
use cover\web\Request;
use cover\web\Response;
use cover\web\UnauthorizedHttpException;
use cover\web\User;

/**
 * AuthInterface is the interface that should be implemented by auth method classes.
 *
 */
interface AuthInterface
{
    /**
     * Authenticates the current user.
     * @param User $user
     * @param Request $request
     * @param Response $response
     * @return IdentityInterface the authenticated user identity. If authentication information is not provided, null will be returned.
     * @throws UnauthorizedHttpException if authentication information is provided but is invalid.
     */
    public function authenticate($user, $request, $response);

    /**
     * Generates challenges upon authentication failure.
     * For example, some appropriate HTTP headers may be generated.
     * @param Response $response
     */
    public function challenge($response);

    /**
     * Handles authentication failure.
     * The implementation should normally throw UnauthorizedHttpException to indicate authentication failure.
     * @param Response $response
     * @throws UnauthorizedHttpException
     */
    public function handleFailure($response);
}
