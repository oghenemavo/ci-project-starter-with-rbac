<?php

namespace Ecosystem\Authentication\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    public $user_password;
    public $password_reset_token;
    public $password_reset_expires_at;
    public $activation_token;

	protected $attributes = [
		'id' => null,
		'last_name' => null,
		'first_name' => null,
		'phone_number' => null,
		'user_email' => null,
		'user_password' => null,
		'password_reset_token' => null,
		'password_reset_expires_at' => null,
		'activation_token' => null,
		'is_active' => null,
		'created_at' => null,
		'updated_at' => null,
	];

	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [
		'password_reset_token' => '?string',
		'password_reset_expires_at' => '?datetime',
		'activation_token' => '?string',
	];

	/**
	 * Hash a password
	 *
	 * @param string $password
	 * @return void
	 */
	public function setUserPassword(string $password)
    {
        $this->attributes['user_password'] = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

	public function getUserPassword()
	{
        return $this->attributes['user_password'];
    }

	public function getPasswordResetToken()
	{
        return $this->attributes['password_reset_token'];
    }

	public function getPasswordResetExpiresAt()
	{
        return $this->attributes['password_reset_expires_at'];
    }

	public function getActivationToken()
	{
        return $this->attributes['activation_token'];
    }
	
	public function full_name()
	{
        return ucfirst($this->attributes['last_name']) . ' ' . ucfirst($this->attributes['first_name']);
    }
}
