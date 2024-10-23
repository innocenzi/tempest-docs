<?php

declare(strict_types=1);

namespace App\Front\Docs;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

use function Tempest\Support\arr;

use Tempest\Support\ArrayHelper;

use function Tempest\Support\str;

readonly class DocsRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    public function find(string $category, string $slug, array $data = []): ?DocsChapter
    {
        $path = glob(__DIR__ . "/Content/{$category}/*{$slug}*.md")[0] ?? null;

        if (! $path) {
            return null;
        }

        $content = file_get_contents($path);

        // TODO: this is not ideal at all, but I'm not sure where to hook
        // into the contents before it being rendered as markdown.
        foreach ($data as $key => $value) {
            $content = str_replace("%{$key}%", $value, $content);
        }

        $markdown = $this->markdown->convert($content);

        $frontMatter = $markdown instanceof RenderedContentWithFrontMatter ? $markdown->getFrontMatter() : [
            'title' => $slug,
        ];

        return new DocsChapter(...[
            ...[
                'category' => $category,
                'slug' => $slug,
                'body' => $markdown->getContent(),
            ],
            ...$frontMatter,
        ]);
    }

    /**
     * @return ArrayHelper<\App\Front\Docs\DocsChapter>
     */
    public function all(string $category = '*'): ArrayHelper
    {
        return arr(glob(__DIR__ . "/Content/{$category}/*.md"))
            ->map(function (string $path) {
                $content = file_get_contents($path);

                $category = str($path)->beforeLast('/')->afterLast('/');

                preg_match('/(?<index>\d+-)?(?<slug>.*)\.md/', pathinfo($path, PATHINFO_BASENAME), $matches);

                return [
                    'slug' => $matches['slug'],
                    'index' => $matches['index'],
                    'category' => $category,
                    ...YamlFrontMatter::parse($content)->matter(),
                ];
            })
            ->mapTo(DocsChapter::class);
    }
}
