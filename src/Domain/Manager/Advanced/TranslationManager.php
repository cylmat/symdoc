<?php

namespace App\Domain\Manager\Advanced;

use App\Domain\Core\Interfaces\ManagerInterface;
use MessageFormatter;
use Symfony\Component\Translation\DataCollectorTranslator; //debugging
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes
 *
 * XLIFF = XML Localization Interchange File Format
 *
 * translation:update en --force
 * debug:translation fr
 * lint:yaml translations/messages.en.yaml
 */
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
        $strtrParams = ['^this^' => 'yes']; //TranslatorTrait
        return [
            'direct' => $this->translator->trans('How dare so simple', [], 'alphadomain', 'es_custom'),
            'custom_arf' => $this->translator->trans('Should be translated', $strtrParams, 'alpha', 'misc'),
            'plural' => $this->dynamicPlural(),
            // twig: {{ message|trans({'%name%': '...', '%count%': 1}, 'app') }}
            'count' => $this->translator->trans(
                "{0}%name% is no apples|{1}%name% is one apple|]1,Inf[ %name% is %count% apples",
                [
                    '%count%' => 50, // '%count%' mandatory
                    '%name%' => 'There'
                ]
            ),
            'd' => get_class($this->translator),
            // https://www.php.net/manual/en/class.messageformatter.php
            'MessageFormat' => MessageFormatter::formatMessage(
                'de',
                "{0,number,integer} planet with {1,number,integer} stars float on {2,number} universes",
                [5, 1, 30]
            ),
        ];
    }

    /*
     * ICU International Components for Unicode
     * https://icu.unicode.org/
     * https://unicode-org.github.io/icu/userguide/
     *
     * test: http://format-message.github.io/icu-message-format-for-translators/
     */
    public function dynamicPlural()
    {
        // https://unicode-org.github.io/cldr-staging/charts/latest/supplemental/language_plural_rules.html
        return
            $this->translator->trans('invitation.dot', [
                'organizer_name' => 'Evy',
                'organizer_gender' => "female"
            ], 'plural', 'pl') . ' - ' .
            $this->translator->trans('number.of', [
                'apples' => 3,
            ], 'plural', 'pl') . ' : ' .
            // IntlDateFormatter
            $this->translator->trans('published.at', [
                'publication_date' => (new \DateTime('2019-01-25 11:30:00'))->format('Y-m-d')
            ]) . ' : ' .
            // NumberFormatter
            $this->translator->trans('value', [
                'value' => 99.65
            ]);
    }
}
