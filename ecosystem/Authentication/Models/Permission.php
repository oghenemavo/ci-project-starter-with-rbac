<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class Permission extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'permissions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'perm_group_id',
		'permission',
		'permission_slug',
		'enable_create',
		'enable_read',
		'enable_update',
		'enable_delete',
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

	public function getPermissionById(int $id)
	{
		return $this->where('id', $id)->first();
	}

	public function fetchPermissionByGroup(int $perm_group_id)
	{
		$this->select('permissions.*');
		$this->join('permission_groups t2', 'permissions.perm_group_id = t2.id');
		$this->where('t2.id', $perm_group_id);
		return $this->get()->getResultObject();
	}
}
