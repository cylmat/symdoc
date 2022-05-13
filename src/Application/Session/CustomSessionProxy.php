<?php

namespace App\Application\Session;

use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use Symfony\Component\Security\Core\Security;

/**
 * Handler: open, read, write, destroy, close
 * Storage: start, getId, getName, regenerate, save, clear
 * Session: has, get, set (start, id, name, save)
 *
 * $storage = new NativeSessionStorage(array(), new NativeFileSessionHandler());
 * $session = new Session($storage);
 */
class CustomSessionProxy extends SessionHandlerProxy
{
    public function __construct(\SessionHandlerInterface $handler, Security $security)
    {
        $security->getUser();
        parent::__construct($handler);
    }

    public function read($id)
    {
        return parent::read($id . 'sessionidcustom');
    }

    public function write($id, $data)
    {
        return parent::write($id, $data);
    }
}
