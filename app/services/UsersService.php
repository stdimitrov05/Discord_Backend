<?php

namespace App\Services;

use App\Lib\Helper;
use App\Models\EmailConfirmations;
use App\Models\Users;
use App\Exceptions\ServiceException;

/**
 * Business-logic for users
 *
 * Class UsersService
 */
class UsersService extends AbstractService
{

    /**
     * Creating a new user
     *
     * @param array $data
     * @return array
     */

    public function createUser(array $data)
    {
        try {
            $this->db->begin();

            $user = new Users();
            $user->assign($data);
            $result = $user->create();

            if (!$result) {
                throw new ServiceException(
                    'Unable to create user',
                    self::ERROR_UNABLE_CREATE_USER
                );
            }

            $ipAddress = $this->request->getClientAddress();
            $userAgent = $this->request->getUserAgent();
            $token = Helper::generateToken();
            // Send email with confirmation link
            $emailConfirmation = new EmailConfirmations();
            $emailConfirmation->user_id = $user->id;
            $emailConfirmation->token = $token;
            if ($ipAddress) $emailConfirmation->ip_address = $ipAddress;
            if ($userAgent) $emailConfirmation->user_agent = $userAgent;
            $emailConfirmation->save();


            $this->mailer->signUpToken($user->email, $user->username, $token);

            $this->db->commit();

//            $this->logger->info('User created');

        } catch (\PDOException $e) {
            var_dump($e->getMessage());
            die;
            $this->db->rollback();
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return [
            'userId' => $user->id
        ];

    }

    /**
     * User list
     * @returns  array
     */
    public function list()
    {
        $sql = "SELECT * FROM users ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    /**
     *  User profile
     * @returns  array
     */

    public function profile()
    {
        $userId = $this->authService->getIdentity();
        $sql = "
        SELECT 
           us.username,
           us.email
        FROM users us WHERE us.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        if (!$data) {
            throw new ServiceException(
                "User not found",
                self::ERROR_USER_NOT_FOUND
            );

        }

        return [
            "Users Details" => $data
        ];

    }

}
