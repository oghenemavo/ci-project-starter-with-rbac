<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class UserRole extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user_role';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'user_id',
		'role_id',
	];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	/**
	 * Fetch a user role record by user id
	 *
	 * @param string $user_id
	 * @return void
	 */
	public function fetchRoleInfo($user_id) {
        $this->select('user_role.role_id');
        $this->select('t2.role,t2.role_slug,t2.is_super_admin,t2.is_active');

        $this->join('roles t2', 'user_role.role_id = t2.id');
        return $this->where('user_role.user_id', $user_id)->first();
    }
}
