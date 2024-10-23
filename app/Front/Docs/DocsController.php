<?php

declare(strict_types=1);

namespace App\Front\Docs;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocChildNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Tempest\Http\Get;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\StaticPage;

use function Tempest\reflect;

use Tempest\Reflection\MethodReflector;

use function Tempest\Support\arr;

use Tempest\Support\ArrayHelper;
use Tempest\Support\StringHelper;

use function Tempest\uri;
use function Tempest\view;

use Tempest\View\View;
use Tempest\View\ViewRenderer;

final readonly class DocsController
{
    public function __construct(
        private ViewRenderer $viewRenderer,
    ) {
    }

    #[Get('/framework/01-getting-started')]
    public function frameworkIndex(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'framework', slug: 'getting-started'));
    }

    #[Get('/console/01-getting-started')]
    public function consoleIndex(): Redirect
    {
        return new Redirect(uri([self::class, '__invoke'], category: 'console', slug: 'getting-started'));
    }

    #[StaticPage(DocsDataProvider::class)]
    #[Get('/docs/{category}/{slug}')]
    public function __invoke(string $category, string $slug, DocsRepository $chapterRepository): View|Response
    {
        // TODO: clean up
        $currentChapter = $chapterRepository->find($category, $slug, match($slug) {
            'utilities' => [
                'string_helper_methods' => $this->loadUtilitiesDocumentation(StringHelper::class),
                'array_helper_methods' => $this->loadUtilitiesDocumentation(ArrayHelper::class),
            ],
            default => [],
        });

        if (! $currentChapter) {
            return new NotFound();
        }

        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }

    // TODO: clean up
    private function loadUtilitiesDocumentation(string $class): string
    {
        return $this->viewRenderer->render(view(
            path: __DIR__.'/utilities.view.php',
            // TODO: implement support for iterators in `ArrayHelper`
            utilities: arr(iterator_to_array(reflect($class)->getPublicMethods()))
                ->map(function (MethodReflector $reflector) {
                    $phpDocParser = new PhpDocParser(new TypeParser(), new ConstExprParser());

                    if (in_array($reflector->getName(), ['__construct', '__toString'])) {
                        return false;
                    }

                    if ($comment = $reflector->getReflection()->getDocComment()) {
                        $comment = $phpDocParser->parse(new TokenIterator((new Lexer())->tokenize($comment)));
                        $description = arr($comment->children)
                            ->first(fn (PhpDocChildNode $node) => $node instanceof PhpDocTextNode)
                            ?->text;

                        return [
                            'name' => $reflector->getName(),
                            'description' => $description,
                        ];
                    }

                    return false;
                })
                ->filter()
        ));
    }
}
