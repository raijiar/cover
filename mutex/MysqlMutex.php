<?php

namespace cover\mutex;

use cover\base\InvalidConfigException;

/**
 * MysqlMutex implements mutex "lock" mechanism via MySQL locks.
 *
 * Application configuration example:
 *
 * ```
 * [
 *     'components' => [
 *         'db' => [
 *             'class' => 'cover\db\Connection',
 *             'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
 *         ]
 *         'mutex' => [
 *             'class' => 'cover\mutex\MysqlMutex',
 *         ],
 *     ],
 * ]
 * ```
 *
 * @see Mutex
 *
 */
class MysqlMutex extends DbMutex
{
    /**
     * Initializes MySQL specific mutex component implementation.
     * @throws InvalidConfigException if [[db]] is not MySQL connection.
     */
    public function init()
    {
        parent::init();
        if ($this->db->driverName !== 'mysql') {
            throw new InvalidConfigException('In order to use MysqlMutex connection must be configured to use MySQL database.');
        }
    }

    /**
     * Acquires lock by given name.
     * @param string $name of the lock to be acquired.
     * @param int $timeout time (in seconds) to wait for lock to become released.
     * @return bool acquiring result.
     * @see http://dev.mysql.com/doc/refman/5.0/en/miscellaneous-functions.html#function_get-lock
     */
    protected function acquireLock($name, $timeout = 0)
    {
        return $this->db->useMaster(function ($db) use ($name, $timeout) {
            /** @var \cover\db\Connection $db */
            return (bool) $db->createCommand(
                'SELECT GET_LOCK(:name, :timeout)',
                [':name' => $name, ':timeout' => $timeout]
            )->queryScalar();
        });
    }

    /**
     * Releases lock by given name.
     * @param string $name of the lock to be released.
     * @return bool release result.
     * @see http://dev.mysql.com/doc/refman/5.0/en/miscellaneous-functions.html#function_release-lock
     */
    protected function releaseLock($name)
    {
        return $this->db->useMaster(function ($db) use ($name) {
            /** @var \cover\db\Connection $db */
            return (bool) $db->createCommand(
                'SELECT RELEASE_LOCK(:name)',
                [':name' => $name]
            )->queryScalar();
        });
    }
}
