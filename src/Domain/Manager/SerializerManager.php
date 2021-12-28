<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerManager implements ManagerInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function call(): array
    {
        return [
            'serialize' => $this->serialize(),
            'deserialize' => $this->deserializeUser(),
            'deserialize_array' => $this->deserializeUsers(),
        ];
    }

    private function serialize(): string
    {
        $user1 = (new User)->setUsername('Alpha')->setPhone(01)->setToken(new Token);
        $user2 = (new User)->setUsername('Beta')->setPhone(02)->setToken(new Token);

        $serialize = $this->serializer->serialize([$user1, $user2], 'json', ['groups' => 'registration']);

        return $serialize;
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

    private function valueObject()
    {
        $normalizer = new GetSetMethodNormalizer();
        $vo = $normalizer->denormalize(['bar' => 'symfony'], ValueObject::class);

        echo $vo->getFoo();
    }

    private function deserializeUsers(): array
    {
        $normalizers = [new GetSetMethodNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, [new JsonEncoder()]);

        $data = $this->serialize();
        $users = $serializer->deserialize($data, User::class.'[]', JsonEncoder::FORMAT, [
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => new User()
            ],
            'ctx' => 'testing_framework'
        ]);

        return $users;
    }
}