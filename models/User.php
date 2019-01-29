<?php

namespace app\models;

use elisdn\hybrid\events\RemoveAllAssignmentsEvent;
use elisdn\hybrid\events\RemoveAllEvent;
use elisdn\hybrid\events\RemoveRoleEvent;
use elisdn\hybrid\events\RenameRoleEvent;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use elisdn\hybrid\AuthRoleModelInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, AuthRoleModelInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;


    public static function tableName()
    {
        return '{{%user}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function signup(string $username, string $email, string $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        $user->save();
        // set role (RBAC)
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRole('author');

        $authManager->assign($role, $user->getId());
        return $user;
    }

    public static function create(string $username, string $email, string $password, string $role): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->role = $role;
        $user->setPassword($password);
        $user->generateAuthKey();
        return $user;
    }

    public function edit(string $username, string $email, string $role): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->updated_at = time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public static function findAuthRoleIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findAuthIdsByRoleName($roleName)
    {
        return static::find()->where(['role' => $roleName])->select('id')->column();
    }

    public static function onRenameRole(RenameRoleEvent $event)
    {
        self::updateAll(['role' => $event->newRoleName], ['role' => $event->oldRoleName]);
    }

    public static function onRemoveRole(RemoveRoleEvent $event)
    {
        self::updateAll(['role' => null], ['role' => $event->roleName]);
    }

    public static function onRemoveAll(RemoveAllEvent $event)
    {
        self::updateAll(['role' => null]);
    }

    public static function onRemoveAllAssignments(RemoveAllAssignmentsEvent $event)
    {
        self::updateAll(['role' => null]);
    }

    // Hybrid RBAC manager methods

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getAuthRoleNames()
    {
        return (array)$this->role;
    }

    public function addAuthRoleName($roleName)
    {
        $this->updateAttributes(['role' => $this->role = $roleName]);
    }

    public function removeAuthRoleName($roleName)
    {
        $this->updateAttributes(['role' => $this->role = null]);
    }

    public function clearAuthRoleNames()
    {
        $this->updateAttributes(['role' => $this->role = null]);
    }

    public function getStatusName(){
        $list = self::getStatusList();
        return $list[$this->status];
    }

    public static function getStatusList(){
        return ['0' => 'Banned', '10' => 'Ok'];
    }
}