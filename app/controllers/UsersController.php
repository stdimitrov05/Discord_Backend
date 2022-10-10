<?php

namespace App\Controllers;

use App\Exceptions\HttpExceptions\Http422Exception;
use App\Exceptions\HttpExceptions\Http500Exception;
use App\Exceptions\ServiceException;
use App\Services\AbstractService;

/**
 * Users controller
 */
class UsersController extends AbstractController
{
    /**
     * List user
     * @returns  array
     */

    public function listAction()
    {
        try {
            //  List user
            $response = $this->usersService->list();

        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * User profile
     * @returns  array
     */

    public function profileAction()
    {
        try {
            //  List user
            $response = $this->usersService->profile();

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_USER_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }

}
