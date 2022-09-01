<?php

namespace App\Domain\Manager\Utilities;

use App\Application\Expression\ExpressionProvider;
use App\Domain\Core\Interfaces\ManagerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

# @phpcs:disable
\define('AL', 'most');
# @phpcs:enable

// require symfony/expression-language
// https://symfony.com/doc/current/components/expression_language.html
final class ExpressionManager implements ManagerInterface
{
    public function call(array $context = []): array
    {
        return $this->expression();
    }

    /**
     * "PHP sandboxed..."
     *
     * evaluation: the expression is evaluated without being compiled to PHP;
     * compile: the expression is compiled to PHP, so it can be cached and evaluated.
     */
    private function expression(): array
    {
        $language = new ExpressionLanguage(null, [new ExpressionProvider()]);
        $object = new class ()
        {
            public $num = 5;
            public $num2 = 51;
            public $txt = 'alpha';
            public $txt2 = 'beta';
            public $func = "echo 'test'";
            public $func2 = "echo 'test2';";
            public function __toString()
            {
                return '6';
            }
        };

        /*var_dump($language->evaluate(
            'life < universe or life < everything',
            array(
                'life' => 10,
                'universe' => 10,
                'everything' => 22,
            )
        ));*/

        # https://symfony.com/doc/current/components/expression_language/syntax.html
        /*
            - user.getId() in ['123', '456']
            - article.count > 10 and article.price not in ["dot"]
            - someArray[3].someMethod('bar')
        */

        /**
         * Other variables in
         * - security expressions (user, roles, object, token, trust_resolver) and is_auth*
         * - service container expressions
         * - routing expressions (context, request)
         */
        $language->register(
            'myown',
            function ($arguments, $compiledValue) {
                d($arguments, $compiledValue);
                // compiled version
                return sprintf('(is_string(%1$s) ? strtolower(%1$s) : %1$s)', $compiledValue);
            },
            function ($arguments, string $str) {
                return strtolower($str);
            }
        );

        // or register Provider with function
        $language->registerProvider(new ExpressionProvider());

        return [
            'evaluate 12obj and' => $language->evaluate(
                '1 + 2 + myobj.num and user in 5..9',
                [
                    'myobj' => $object,
                    'user' => 8
                ]
            ),
            'const' => $language->evaluate('constant("AL")', []),

            // compile('a.b', array('a'));
            // expression is compiled to PHP for later evaluation
            'compile' => $compiled = $language->compile(
                'object.b + 987 + set[0] || myobj',
                [
                    'object', 'set', 'myobj' // must only define allow values
                ]
            ), // compiled into php

            'registered' => $language->evaluate('myown("DOT"~(1 + 2))~fromprovider("UP")', ['obj']),

            'parse' => $language->parse('1 + 2', []), // ! || &&
            'match' => $language->evaluate('not ("foo" matches "/bar/")'), // not or and
            'concat' => $language->evaluate('is~"more"', ['is' => 'ok']),
        ];
    }
}
