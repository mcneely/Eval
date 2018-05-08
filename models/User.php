<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{users}}';
    }

    /**
     * Gets user with provided Id.
     * @param int|string $id
     * @return User|null|\yii\web\IdentityInterface
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);

        if (!empty($user)) {
            return new static($user);
        }
        return null;
    }

    /**
     * Get User via provided token
     * @param mixed $token
     * @param null  $type
     * @return User|null|\yii\web\IdentityInterface
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = self::findOne([
                                  'authToken' => $token
                              ]);

        if (!empty($user)) {
            return new static($user);
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = self::findOne([
                                  'username' => $username
                              ]);

        if (!empty($user)) {
            return new static($user);
        }

        return null;
    }

    /**
     * Returnes Use Id.
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returnes Authkey for Identity Interface
     * @return mixed|string
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * Compared Supplied Authkey with what is set on user.
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }
}
