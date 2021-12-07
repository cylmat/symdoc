<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\User;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class MiscManager implements ManagerInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function call()
    {
        $user = $this->deserializeUser();
        $this->properties();

        d($user);
    }

    /**
     * https://symfony.com/doc/current/components/serializer.html
     */
    private function deserializeUser(): User
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $data = '{"username":"echo d\'oc"}';
        $user = $serializer->deserialize($data, User::class, JsonEncoder::FORMAT, [
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => new User(),
                AbstractNormalizer::GROUPS => ['User']
            ],
            'ctx' => 'testing_framework'
        ]);

        return $user;
    }

    private function properties(): string
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $person = new User();
        $person->testing = 'Alpha';

        $testing = $accessor->getValue($person, 'testing');

        return $testing;
    }
}
