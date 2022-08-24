<?php

namespace App\Domain\Manager;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validation;

/**
 * Not functional actually, only for sample !
 *    # https://symfony.com/doc/current/controller/upload_file.html
 */
final class FileUploadManager implements ManagerInterface
{
    public function call(array $context = []): array
    {
        return [
            'file' => $this->resolveUploadedFile(new UploadedFile(__FILE__, '')),
        ];
    }

    private function resolveUploadedFile(UploadedFile $file)
    {
        $key = 'attachment';

        $resolver = new OptionsResolver();
        $resolver
            ->setDefined($key)
            ->setAllowedTypes($key, UploadedFile::class)
            ->setAllowedValues($key, function (UploadedFile $file) {
                $fileConstraint = new File();
                $fileConstraint->maxSize = 500000;
                $fileConstraint->mimeTypes = $this->getMimeTypes(['jpeg', 'gif']);

                return $file->isValid()
                    && 0 === Validation::createValidator()->validate($file, $fileConstraint)->count();
            });

        #return $resolver->resolve([$key => $file])[$key];
    }

    private function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    private function getMimeTypes(array $exts): array
    {
        $types =  [];

        $mimeTypes = new MimeTypes();
        foreach ($exts as $ext) {
            $types = \array_merge($types, $mimeTypes->getMimeTypes($ext));
        }

        return $types;
    }

    /*********************************************
     * PHPUNIT TESTS - sample
     */
    private function testUploadedFile(array $data, $expect): void
    {
        $file = $this->prophesize(UploadedFile::class)->willBeConstructedWith(
            [$data['pathname'], $data['clientOriginalName']]
        );
        $file->isValid()->willReturn($data['valid']);
        $file->getPathname()->willReturn($data['pathname']);
        $file->getClientOriginalName()->willReturn($data['clientOriginalName']);
        $file->getMimeType()->willReturn($data['mimeType']);

        if ($exception = $expect['exception']) {
            $this->expectException($exception);
        }

        $request = new SampleFormServiceUploaded($file->reveal());

        $this->assertEquals($file->reveal(), $request->getUploadedFile());
    }
}
