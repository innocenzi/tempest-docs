<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;

?>

<x-base>
    <x-slot name="styles">
        <style>
            body {
                background-color: #f6ffff;
            }
        </style>
    </x-slot>

    <div class="flex items-center justify-center bg-[#4f95d1] text-white slope header-gradient">
        <div class="grid gap-4 content-center place-items-center mt-[25vh] mb-[35vh] relative px-4">
            <h1 class="text-4xl font-extrabold text-center font-argon">The framework that gets out of your way.</h1>

            <h2 class="text-xl font-body px-4 md:px-0">
                <span class="tempest">Tempest</span> is the PHP framework that makes you focus on
                <span class="font-bold">your code</span>.
            </h2>

            <nav class="flex flex-wrap gap-2 mt-8">
                <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
            </nav>
        </div>
    </div>

    <div class="mt-[-25vh] grid gap-12 relative font-open-sans">
        <x-codeblock>
            <x-slot name="code">
                <?= $this->code1 ?>
            </x-slot>

            <x-slot name="text">
                Zero-config controllers and routing
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code5 ?>
            </x-slot>

            <x-slot name="text">
                An amazing new view engine
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code4 ?>
            </x-slot>

            <x-slot name="text">
                An ORM that embraces PHP
            </x-slot>
        </x-codeblock>

        <div class="header-gradient slope-2 flex justify-center text-white text-xl p-4 py-8 md:p-16">
            <div class="md:max-w-[50%]">
                Tempest has already managed to become something more than an exercise, and you seem to have the experience, mentality and passion to lead its future to much greater heights. —
                <a class=" underline hover:no-underline" href="https://www.reddit.com/r/PHP/comments/1fi2dny/introducing_tempest_the_framework_that_gets_out/lngag06/">Reddit</a>
            </div>
        </div>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code2 ?>
            </x-slot>

            <x-slot name="text">
                Frictionless console commands
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code3 ?>
            </x-slot>

            <x-slot name="text">
                Static pages out of the box
            </x-slot>
        </x-codeblock>


        <div class="header-gradient slope-2 flex justify-center text-white text-xl p-4 py-8 md:p-16">
            <div class="md:max-w-[50%]">
                Tempest is a work of art 👌 —
                <a class=" underline hover:no-underline" href="https://x.com/LukeDowning19/status/1836083961174397420">Twitter</a>
            </div>
        </div>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code6 ?>
            </x-slot>

            <x-slot name="text">
                And much more!
            </x-slot>
        </x-codeblock>
    </div>

    <div class="slope-3 md:pt-32 py-16 px-4 md:px-16 flex justify-center mt-8 bg-[#4f95d1] text-white font-bold header-gradient">
        <div class="grid gap-4 place-items-center">
            <h2 class="text-2xl">
                Get started with <span class="tempest">Tempest</span> today, now in alpha!
            </h2>

            <nav class="flex flex-wrap gap-2 mt-6 md:mt-0">
                <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
            </nav>
        </div>
    </div>
</x-base>