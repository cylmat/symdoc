<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use App\Domain\Entity\Token;
use App\Domain\Entity\User;
use App\Domain\Serializer\EmojiEncoder;
use App\Domain\Serializer\EmojiNameConverter;
use App\Domain\Serializer\EmojiNormalizer;
use Symfony\Component\Serializer\Encoder\ChainEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

// composer require symfony/serializer-pack
final class SerializerManager implements ManagerInterface
{
    // OBJECT
    // Normalizers read data to array or string
    // <- ARRAY ->
    // Encoders turn arrays into formats and vice versa
    // FORMAT ("json"...)
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        // Constructed with SerializerPass
        $this->serializer = $serializer;
    }

    // annotations : composer require sensio/framework-extra-bundle
    public function call(array $context = []): array
    {
        /* bin(ă):" . bin2hex("ă")
        . " - hex(c483):" . hex2bin("c483")
        . " - \\xc4\\x83: \xc4\x83"
        . " - emo \\u{1F601}:\u{1F601}" . " */

        return [
            'service' => $this->serializer,
            'serialize' => $this->serialize(),
            'deserialize' => $this->deserializeUser(),
            'deserialize_array' => $this->deserializeUsers(),
            'valueObject' => $this->valueObject(),
            'emoji' => $this->emoji(),
            'chained' => (new Serializer(
                [new EmojiNormalizer()],
                [new ChainEncoder([ // chain : look if one handle the format
                    new JsonEncoder(),
                    new YamlEncoder()
                ])]
            ))->serialize("\\x05\\xd4" . " chained", 'json')
        ];
    }

    private function emoji(): array
    {
        $string = 'alpha';
        $emojied = $this->serializer->serialize($string, 'emoji', ['smiley' => true]);
        $deserialized = $this->serializer->deserialize($emojied, 'string', 'emoji', ['unicode' => '9']);

        return [
            $emojied,
            $deserialized,
        ];
    }

    private function serialize(): array
    {
        $user1 = (new User())->setUsername('Alpha')->setPhone(01)->setToken(new Token());
        $user2 = (new User())->setUsername('Beta')->setPhone(02)->setToken(new Token());

        $json = $this->serializer->serialize([$user1, $user2], 'json', [
            AbstractNormalizer::GROUPS => 'serializ_group',
            [
                'callback' => function ($value) {
                }
            ]
        ]);
        $xml = $this->serializer->serialize([$user1, $user2], 'xml', [
            AbstractNormalizer::GROUPS => ['serializ_group', 'groupxml']
        ]);

        return [
            (string) $json,
            (string) $xml
        ];
    }

    /**
     * https://symfony.com/doc/current/components/serializer.html
     */
    private function deserializeUser(): User
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, new EmojiNameConverter())];
        $serializer = new Serializer($normalizers, $encoders);

        $data = '{"username":"echo d\'oc"}';
        $user = $serializer->deserialize($data, User::class, JsonEncoder::FORMAT, [
            [
                ObjectNormalizer::OBJECT_TO_POPULATE => new User(),
                AbstractNormalizer::GROUPS => ['User']
            ],
            'ctx' => 'testing_framework'
        ]);

        return $user;
    }

    private function deserializeUsers(): array
    {
        $normalizers = [new GetSetMethodNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, [new JsonEncoder()]);

        $data = '{"username":"echo d\'oc"}';
        $users = $serializer->deserialize($data, User::class . '[]', JsonEncoder::FORMAT, [
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => new User()
            ],
            'ctx' => 'testing_framework'
        ]);

        return $users;
    }

    private function valueObject()
    {
        $normalizer = new GetSetMethodNormalizer();

        $vobject = $normalizer->denormalize(
            ['bar' => 'symfony'],
            ValueObject::class,
            'array'
        );

        $array = $normalizer->normalize(
            (new ValueObject())->setBar('tadam')
        );

        return [
            $vobject,
            $array,
        ];
    }
}

# @phpcs:disable
class ValueObject
{
    private $foo;
    private string $bar;

    public function setBar(string $value): self
    {
        $this->bar = $value;
        return $this;
    }

    public function getBar(): string
    {
        return $this->bar;
    }
}
# @phpcs:enable
