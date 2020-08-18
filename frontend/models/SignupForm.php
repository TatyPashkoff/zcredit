<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $phone;
    //public $repassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            [['phone','password'], 'required'],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ( ! $this->validate() ) {


            return false;

        }
        
        $user = new User();

        $user->username = $this->username;
        $user->name = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->role = 1;
        $user->created_at = time();
        $user->status = 1;

        $user->setPassword($this->password);
        $user->password = $this->password;
        $user->generateAuthKey();


        if(!$user->save()){
            return false;
        }
        
        return $user;

    }
}
