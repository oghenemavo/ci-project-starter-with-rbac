<?php

namespace Ecosystem\Authentication\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RollOutNotifications extends BaseCommand
{
	/**
	 * The Command's Group
	 *
	 * @var string
	 */
	protected $group = 'Notifications';

	/**
	 * The Command's Name
	 *
	 * @var string
	 */
	protected $name = 'rollout:alert';

	/**
	 * The Command's Description
	 *
	 * @var string
	 */
	protected $description = 'Roll out and send pending notifications';

	/**
	 * The Command's Usage
	 *
	 * @var string
	 */
	protected $usage = 'rollout:alert';

	/**
	 * The Command's Arguments
	 *
	 * @var array
	 */
	protected $arguments = [];

	/**
	 * The Command's Options
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Actually execute a command.
	 *
	 * @param array $params
	 */
	public function run(array $params)
	{
		return service('alertlib')->dispatch_notifications();
	}
}
