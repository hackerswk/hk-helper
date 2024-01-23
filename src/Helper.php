<?php
/**
 * Ministore helper class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkHelper;

use \PDO as PDO;

class Helper
{
    /**
     * database
     *
     * @var object
     */
    private $database;

    /**
     * initialize
     */
    public function __construct($db = null)
    {
        $this->database = $db;
    }

    /**
     * Get helpers of ministore.
     *
     * @param int $ownerId
     * @param string $type
     * @return array
     */
    public function getHelpers($ownerId, $type)
    {
        if ($type == 'website') {
            $sql = <<<EOF
            SELECT * FROM user_sites
            WHERE site_id = :site_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':site_id' => $ownerId,
            ]);
        }

        if ($type == 'blackcard') {
            $sql = <<<EOF
            SELECT * FROM crm_helpers
            WHERE crm_id = :crm_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':crm_id' => $ownerId,
            ]);
        }

        $result = [];
        if ($query->rowCount() > 0) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    /**
     * Update helper alias of user_sites and crm_helpers.
     *
     * @param string $type
     * @param int $ownerId
     * @param int $userId
     * @param string $alias
     * @return bool
     */
    public function updateHelper($type, $ownerId, $userId, $alias)
    {
        if ($type == 'website') {
            $sql = <<<EOF
            UPDATE user_sites SET helper_alias = :helper_alias
            WHERE site_id = :site_id AND user_id = :user_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':helper_alias' => $alias,
                ':site_id' => $ownerId,
                ':user_id' => $userId,
            ]);
        }

        if ($type == 'blackcard') {
            $sql = <<<EOF
            UPDATE crm_helpers SET helper_alias = :helper_alias
            WHERE crm_id = :crm_id AND user_id = :user_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':helper_alias' => $alias,
                ':crm_id' => $ownerId,
                ':user_id' => $userId,
            ]);
        }

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Establish a helper-owner relationship.
     *
     * @param string $type
     * @param int $ownerId
     * @param int $userId
     * @param int $helperId
     * @param string $alias
     * @return bool
     */
    public function link($type, $ownerId, $userId, $helperId, $alias)
    {
        if ($type == 'website') {
            $sql = <<<EOF
            INSERT INTO user_sites
            SET site_id = :site_id, user_id = :user_id, helper_id = :helper_id, helper_alias = :helper_alias
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':site_id' => $ownerId,
                ':user_id' => $userId,
                ':helper_id' => $helperId,
                ':helper_alias' => $alias,
            ]);
        }

        if ($type == 'blackcard') {
            $sql = <<<EOF
            INSERT INTO crm_helpers
            SET crm_id = :crm_id, user_id = :user_id, helper_id = :helper_id, helper_alias = :helper_alias
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':crm_id' => $ownerId,
                ':user_id' => $userId,
                ':helper_id' => $helperId,
                ':helper_alias' => $alias,
            ]);
        }

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Cancel the helper-owner relationship.
     *
     * @param string $type
     * @param int $ownerId
     * @param int $userId
     * @return bool
     */
    public function unlink($type, $ownerId, $userId)
    {
        if ($type == 'website') {
            $sql = <<<EOF
            DELETE FROM user_sites
            WHERE site_id = :site_id AND user_id = :user_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':site_id' => $ownerId,
                ':user_id' => $userId,
            ]);
        }

        if ($type == 'blackcard') {
            $sql = <<<EOF
            DELETE FROM crm_helpers
            WHERE crm_id = :crm_id AND user_id = :user_id
EOF;
            $query = $this->database->prepare($sql);
            $query->execute([
                ':crm_id' => $ownerId,
                ':user_id' => $userId,
            ]);
        }

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
