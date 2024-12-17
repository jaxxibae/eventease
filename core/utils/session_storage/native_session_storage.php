<?php
declare(strict_types=1);


namespace Utils\Session_storage;

require __DIR__ . "/session_storage_interface.php";

use function session_start;
use function session_status;
use const PHP_SESSION_NONE;

class NativeSessionStorage implements SessionStorageInterface
{
    private array $storage;

    public function __construct(array $options = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (!session_start($options)) {
                throw new \RuntimeException('Failed to start the session.');
            }
        }

        $this->storage = &$_SESSION;
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->storage[$key] ?? $default;
    }

    public function put(string $key, $value = null): void
    {
        $this->storage[$key] = $value;
    }

    public function all(): array
    {
        return $this->storage;
    }

    public function has(string $key): bool
    {
        return isset($this->storage[$key]);
    }

    public function remove(string $key): void
    {
        unset($this->storage[$key]);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->put($offset, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->storage[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }
}