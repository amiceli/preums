<?php

namespace App\Console\Commands;

use App\Models\LangAuthor;
use App\Models\ProLang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProLangAssets extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pro-lang-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prolang and author picture';

    private function getWikiImageSrc(string $authorLink) {
        $title = urldecode(basename(parse_url($authorLink, PHP_URL_PATH)));
        $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/'.rawurlencode($title);

        $res = Http::withHeaders(array(
            'User-Agent' => 'MyApp/1.0 (contact@example.com)',
            'Accept' => 'application/json',
        ))->get($url);

        if (! $res->ok()) {
            return null;
        }

        return $res->json('thumbnail.source');
    }

    private function updateAssets() {
        LangAuthor::whereNotNull('link')->each(
            function ($author) {
                $img = $this->getWikiImageSrc($author->link);

                $author->update(
                    array('pictureUrl' => $img)
                );
            }
        );

        ProLang::whereNotNull('link')->each(
            function ($lang) {
                $img = $this->getWikiImageSrc($lang->link);

                $lang->update(
                    array('pictureUrl' => $img)
                );
            }
        );
    }

    private function updateLinks() {
        $langsData = array(
            'B' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/B.b',
            ),
            'Boo' => array(
                'maintainRepo' => 'https://github.com/boo-lang/boo',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/Boo.boo',
            ),
            'Eiffel' => array(
                'maintainRepo' => 'https://github.com/EiffelSoftware/EiffelStudio',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/e/Eiffel.eiff',
            ),
            'Java' => array(
                'maintainRepo' => 'https://github.com/openjdk/jdk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/j/Java.java',
            ),
            'JavaScript' => array(
                'maintainRepo' => 'https://github.com/tc39/ecma262',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/j/JavaScript.js',
            ),
            'PHP' => array(
                'maintainRepo' => 'https://github.com/php/php-src',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/PHP.php',
            ),
            'R' => array(
                'maintainRepo' => 'https://github.com/wch/r-source',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/R.R',
            ),
            'Python' => array(
                'maintainRepo' => 'https://github.com/python/cpython',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/Python%203.py',
            ),
            'AWK' => array(
                'maintainRepo' => 'https://github.com/onetrueawk/awk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/AWK.awk',
            ),
            'C#' => array(
                'maintainRepo' => 'https://github.com/dotnet/roslyn',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/CSharp.cs',
            ),
            'Agda' => array(
                'maintainRepo' => 'https://github.com/agda/agda',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Agda.agda',
            ),
            'COBOL' => array(
                'maintainRepo' => 'https://github.com/OCamlPro/gnucobol',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/COBOL.cbl',
            ),
            'BLISS' => array(
                'maintainRepo' => 'https://github.com/madisongh/blissc?tab=readme-ov-file',
                'rawCodeLink' => null,
            ),
            'D' => array(
                'maintainRepo' => 'https://github.com/dlang/dmd',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/d/D.d',
            ),
            'Dart' => array(
                'maintainRepo' => 'https://github.com/dart-lang/sdk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/d/Dart.dart',
            ),
            'Elixir' => array(
                'maintainRepo' => 'https://github.com/elixir-lang/elixir',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/e/Elixir.ex',
            ),
            'Erlang' => array(
                'maintainRepo' => 'https://github.com/erlang/otp',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/e/Erlang.erl',
            ),
            'Nim' => array(
                'maintainRepo' => 'https://github.com/nim-lang/Nim',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/n/Nim.nim',
            ),
            'Crystal' => array(
                'maintainRepo' => 'https://github.com/crystal-lang/crystal',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Crystal.cr',
            ),
            'Clojure' => array(
                'maintainRepo' => 'https://github.com/clojure/clojure',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Clojure.clj',
            ),
            'REFAL' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Refal.ref',
            ),
            'SNOBOL' => array(
                'maintainRepo' => 'https://github.com/seanpm2001/Learn-SNOBOL?tab=readme-ov-file#Version-history',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SNOBOL',
            ),
            'APL' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/APL.apl',
            ),
            'SNOBOL' => array(
                'maintainRepo' => 'https://github.com/seanpm2001/Learn-SNOBOL?tab=readme-ov-file#Version-history',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SNOBOL',
            ),
            'FORTRAN' => array(
                'maintainRepo' => 'https://github.com/fortran-lang',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/f/Fortran.f90',
            ),
            'Haskell' => array(
                'maintainRepo' => 'https://github.com/ghc/ghc',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/h/Haskell.hs',
            ),
            'Ruby' => array(
                'maintainRepo' => 'https://github.com/ruby/ruby',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Ruby.rb',
            ),
            'Groovy' => array(
                'maintainRepo' => 'https://github.com/apache/groovy',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/g/Groovy.groovy',
            ),
            'Common Lisp' => array(
                'maintainRepo' => 'https://github.com/sbcl/sbcl',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Common%20Lisp.lisp',
            ),
            'Go' => array(
                'maintainRepo' => 'https://github.com/golang/go',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/g/Go.go',
            ),
            'BCPL' => array(
                'maintainRepo' => 'https://github.com/8l/bcpl',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/BCPL.bcl',
            ),
            'Ballerina' => array(
                'maintainRepo' => 'https://github.com/ballerina-platform/ballerina-lang',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/Ballerina.bal',
            ),
            'Julia' => array(
                'maintainRepo' => 'https://github.com/JuliaLang/julia',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/j/Julia.jl',
            ),
            'ActionScript' => array(
                'maintainRepo' => 'https://github.com/adobe/avmplus',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ActionScript.as',
            ),
            'Kotlin' => array(
                'maintainRepo' => 'https://github.com/JetBrains/kotlin',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/k/Kotlin.kt',
            ),
            'MATLAB' => array(
                'maintainRepo' => 'https://github.com/mathworks/MATLAB-Language-grammar',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/m/MATLAB.m',
            ),
            'ABC' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ABC.abc',
            ),
            'Elm' => array(
                'maintainRepo' => 'https://github.com/elm/compiler',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/e/Elm.elm',
            ),
            'Lua' => array(
                'maintainRepo' => 'https://github.com/lua/lua',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/l/Lua.lua',
            ),
            'Racket' => array(
                'maintainRepo' => 'https://github.com/racket/racket',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Racket.rkt',
            ),
            'Swift' => array(
                'maintainRepo' => 'https://github.com/apple/swift',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Swift.swift',
            ),
            'OCaml' => array(
                'maintainRepo' => 'https://github.com/ocaml/ocaml',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/o/OCaml.ml',
            ),
            'Zig' => array(
                'maintainRepo' => 'https://github.com/ziglang/zig',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/z/Zig.zig',
            ),
            'ABAP' => array(
                'maintainRepo' => 'https://github.com/SAP/abap-cleaner',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ABAP.abap',
            ),
            'ALGOL 60' => array(
                'maintainRepo' => 'https://github.com/JvanKatwijk/algol-60-compiler',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ALGOL%2060.algol60',
            ),
            'ALGOL 68' => array(
                'maintainRepo' => 'https://github.com/algol68g/algol68g',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ALGOL%2068.algol68',
            ),
            'BBC BASIC' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/BBC%20BASIC.bbc',
            ),
            'F#' => array(
                'maintainRepo' => 'https://github.com/dotnet/fsharp',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/f/F%23.fs',
            ),
            'Rust' => array(
                'maintainRepo' => 'https://github.com/rust-lang/rust',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Rust.rs',
            ),
            'TypeScript' => array(
                'maintainRepo' => 'https://github.com/microsoft/TypeScript',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/t/TypeScript.ts',
            ),
            'Scheme' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Scheme.scm',
            ),
            'Hack' => array(
                'maintainRepo' => 'https://github.com/facebook/hhvm',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/h/Hack.hh',
            ),
            'LISP' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/l/Lisp.lsp',
            ),
            'Scala' => array(
                'maintainRepo' => 'https://github.com/scala/scala',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Scala.scala',
            ),
            'C' => array(
                'maintainRepo' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C90' => array(
                'maintainRepo' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C99' => array(
                'maintainRepo' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C11' => array(
                'maintainRepo' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C17' => array(
                'maintainRepo' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C++03' => array(
                'maintainRepo' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++11' => array(
                'maintainRepo' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++14' => array(
                'maintainRepo' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++17' => array(
                'maintainRepo' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++20' => array(
                'maintainRepo' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'AMOS BASIC' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Amos',
            ),
            'A-0' => array(
                'maintainRepo' => null,
                'rawCodeLink' => null,
            ),
            'ALGOL 58' => array(
                'maintainRepo' => null,
                'rawCodeLink' => null,
            ),
            'Scratch' => array(
                'maintainRepo' => 'https://github.com/LLK/scratch-vm',
                'rawCodeLink' => null,
            ),
            'Smalltalk-80' => array(
                'maintainRepo' => 'https://github.com/pharo-project/pharo',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SmallTalk.sm',
            ),
            'Windows PowerShell' => array(
                'maintainRepo' => 'https://github.com/PowerShell/PowerShell',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/PowerShell.ps1',
            ),
            'Visual Basic .NET' => array(
                'maintainRepo' => 'https://github.com/dotnet/roslyn',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/v/Visual%20Basic.vb',
            ),
            'SQL' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SQL.sql',
            ),
            'Objective-C' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/o/Objective%20C.m',
            ),
            'PostScript' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/PostScript.ps',
            ),
            'Miranda' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/m/Miranda.m',
            ),
            'Red' => array(
                'maintainRepo' => 'https://github.com/red/red',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Red.red',
            ),
            'Ceylon' => array(
                'maintainRepo' => 'https://github.com/eclipse-archived/ceylon',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Ceylon.ceylon',
            ),
            'Genie' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/ParaSail.psi',
            ),
            'Parasail' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/g/Genie.gs',
            ),
            'Raku' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Raku.raku',
            ),
            'Forth' => array(
                'maintainRepo' => 'https://github.com/Forth-Standard/forth-standard',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/f/Forth.fth',
            ),
            'Ada 83' => array(
                'maintainRepo' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 95' => array(
                'maintainRepo' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 2005' => array(
                'maintainRepo' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 2012' => array(
                'maintainRepo' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Borland Pascal' => array(
                'maintainRepo' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/Pascal.p',
            ),
        );

        DB::transaction(function () use ($langsData) {
            foreach ($langsData as $key => $value) {
                DB::table('pro_langs')
                    ->where('name', $key)
                    ->update($value);
            }
        });
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        Log::info('action=prolang_assets_command, status=started');
        $this->updateAssets();
        Log::info('action=prolang_assets_command, status=finished');

        $this->updateLinks();
    }
}
