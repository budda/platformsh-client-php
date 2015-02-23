<?php

namespace Platformsh\Client\Model;

class SshKey extends Resource
{

    /**
     * @inheritdoc
     */
    public static function check(array $data)
    {
        $errors = parent::check($data);
        if (!self::validatePublicKey($data['value'])) {
            $errors[] = "The SSH key is invalid";
        }

        return $errors;
    }

    /**
     * Validate an SSH public key.
     *
     * @param string $value
     *
     * @return bool
     */
    protected static function validatePublicKey($value)
    {
        $value = preg_replace('/\s+/', ' ', $value);
        if (!strpos($value, ' ')) {
            return false;
        }
        list($type, $key) = explode(' ', $value, 2);
        if (!in_array($type, ['ssh-rsa', 'ssh-dsa']) || base64_decode($key, true) === false) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function update(array $values)
    {
        throw new \RuntimeException('Update is not implemented for SSH keys');
    }

    /**
     * @inheritdoc
     */
    protected function getUri()
    {
        return 'ssh_keys/' . $this->data['key_id'];
    }
}
