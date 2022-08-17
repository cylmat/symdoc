<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\Translation\DataCollectorTranslator; //debugging
use Symfony\Contracts\Translation\TranslatorInterface;

// https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes
final class TranslationManager implements ManagerInterface
{
    private $translator;

    // Symfony\Component\Translation\DataCollectorTranslator
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        // $locale = $request->getLocale();
    }

    public function call(array $context = []): array
    {
        return [
            'direct' => $this->translator->trans('How dare so simple', [], 'alphadomain', 'es_custom'),
            'd' => get_class($this->translator)
        ];
    }
}
