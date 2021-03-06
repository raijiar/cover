<?php

namespace cover\mutex;

use cover\base\InvalidArgumentException;
use cover\base\InvalidConfigException;

/**
 * PgsqlMutex implements mutex "lock" mechanism via PgSQL locks.
 *
 * Application configuration example:
 *
 * ```
 * [
 *     'components' => [
 *         'db' => [
 *             'class' => 'cover\db\Connection',
 *             'dsn' => 'pgsql:host=127.0.0.1;dbname=demo',
 *         ]
 *         'mutex' => [
 *             'class' => 'cover\mutex\PgsqlMutex',
 *         ],
 *     ],
 * ]
 * ```
 *
 * @see Mutex
 *
 */
class PgsqlMutex extends DbMutex
{
    /**
     * Initializes PgSQL specific mutex component implementation.
     * @throws InvalidConfigException if [[db]] is not PgSQL connection.
     */
    public function init()
    {
        parent::init();
        if ($this->db->driverName !== 'pgsql') {
            throw new InvalidConfigException('In order to use PgsqlMutex connection must be configured to use PgSQL database.');
        }
    }

    /**
     * Converts a string into two 16 bit integer keys using the SHA1 hash function.
     * @param string $name
     * @return array contains two 16 bit integer keys
     */
    private function getKeysFromName($name)
    {
        return array_values(unpack('n2', sha1($name, true)));
    }

    /**
     * Acquires lock by given name.
     * @param string $name of the lock to be acquired.
     * @param int $timeout time (in seconds) to wait for lock to become released.
     * @return bool acquiring result.
     * @see http://www.postgresql.org/docs/9.0/static/functions-admin.html
     */
    protected function acquireLock($name, $timeout = 0)
    {
        if ($timeout !== 0) {
            throw new InvalidArgumentException('PgsqlMutex does not support timeout.');
        }
        list($key1, $key2) = $this->getKeysFromName($name);
        return $this->db->useMaster(function ($db) use ($key1, $key2) {
            /** @var \cover\db\Connection $db */
            return (bool) $db->createCommand(
                'SELECT pg_try_advisory_lock(:key1, :key2)',
                [':key1' => $key1, ':key2' => $key2]
            )->queryScalar();
        });
    }

    /**
     * Releases lock by given name.
     * @param string $name of the lock to be released.
     * @return bool release result.
     * @see http://www.postgresql.org/docs/9.0/static/functions-admin.html
     */
    protected function releaseLock($name)
    {
        list($key1, $key2) = $this->getKeysFromName($name);
        return $this->db->useMaster(function ($db) use ($key1, $key2) {
            /** @var \cover\db\Connection $db */
            return (bool) $db->createCommand(
                'SELECT pg_advisory_unlock(:key1, :key2)',
                [':key1' => $key1, ':key2' => $key2]
            )->queryScalar();
        });
    }
}
