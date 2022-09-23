<?php

namespace App\Application\Service;

use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Complete sample of configuration
 * - Declared in config/services/enable.yml
 * - Config setted in app_my.yml
 * - Loaded in AppMyExtension::loadEnableFlagConfiguration()
 * - Processed dynamically in AppMyExtension::process()
 */
class EnableFlag
{
    /** Enable config (feature flag behavior) from enable.xml */
    private array $enableConfig;
    private iterable $optionInjectors;

    public function __construct(array $configFromExtension, iterable $optionInjectors)
    {
        $this->enableConfig = $configFromExtension;
        $this->optionInjectors = \iterator_to_array($optionInjectors->getIterator()); // twitterclientChild
    }

    public function use(string $key): bool
    {
        // d($this->enableConfig, $this->optionInjectors[0]);
        $flags = $this->enableConfig[0]['enableflags'];
        $current = $currentOptions = null;
        $names = [];
        foreach ($flags as $name => $flagOptions) {
            $names[] = $name;
            if ($key === $name) {
                $current = $flags[$name];
                $currentOptions = $flagOptions;
            }
        }

        if (null === $current) {
            throw new \Exception("Flag '$key' not found in [" . join(',', $names) . "]");
        }

        $flag = $this->getFlag($current, $currentOptions['valid'], $currentOptions['options']);
        $flag->expressionText = $currentOptions['expression'];

        $twitterClientChild = $this->optionInjectors[0];

        $isValidExpression = (new ExpressionLanguage())->evaluate(
            $flag->expressionText,
            ['translated' => $trans = $twitterClientChild->tweet($flag->options['rot13'])]
        );

        return $flag->enabled && $isValidExpression;
    }

    private function getFlag($name, $enabled, $options): object
    {
        $flag = new class ($name, $enabled, $options) {
            public function __construct($name, $enabled, $options)
            {
                $this->name = $name;
                $this->enabled = $enabled;
                $this->options = $options;
            }
            public $name;
            public $enabled;
            public $options;
            public string $expressionText; // condition depends of options
        };

        return $flag;
    }
}
