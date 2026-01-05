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
            'AMPL' => array(
                'rawCode' => <<<'EOD'
                    set Plants;
                    set Markets;

                    # Capacity of plant p in cases
                    param Capacity{p in Plants};

                    # Demand at market m in cases
                    param Demand{m in Markets};

                    # Distance in thousands of miles
                    param Distance{Plants, Markets};

                    # Freight in dollars per case per thousand miles
                    param Freight;

                    # Transport cost in thousands of dollars per case
                    param TransportCost{p in Plants, m in Markets} :=
                        Freight * Distance[p, m] / 1000;

                    # Shipment quantities in cases
                    var shipment{Plants, Markets} >= 0;

                    # Total transportation costs in thousands of dollars
                    minimize cost:
                        sum{p in Plants, m in Markets} TransportCost[p, m] * shipment[p, m];

                    # Observe supply limit at plant p
                    s.t. supply{p in Plants}: sum{m in Markets} shipment[p, m] <= Capacity[p];

                    # Satisfy demand at market m
                    s.t. demand{m in Markets}: sum{p in Plants} shipment[p, m] >= Demand[m];

                    data;

                    set Plants := seattle san-diego;
                    set Markets := new-york chicago topeka;

                    param Capacity :=
                        seattle   350
                        san-diego 600;

                    param Demand :=
                        new-york 325
                        chicago  300
                        topeka   275;

                    param Distance : new-york chicago topeka :=
                        seattle        2.5      1.7     1.8
                        san-diego      2.5      1.8     1.4;

                    param Freight := 90;
                EOD,
            ),
            'ALGOL 60' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/ALGOL-60/fibonacci-sequence.alg',
            ),
            'ALGOL 68' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/ALGOL-68/fibonacci-sequence-1.alg',
            ),
            'ALGOL W' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/ALGOL-W/fibonacci-sequence.alg',
            ),
            'ALPHA' => array(),
            'ANS Forth' => array(
                'isHidden' => true,
            ),
            'APT' => array(
                'rawCode' => <<<'EOD'
                    PARTNO APT-1
                    CLPRNT
                    UNITS / MM
                    NOPOST

                    $$ GEOMETRY DEFINITION
                    P1 = POINT / 50, 50, 0
                    P2 = POINT / -50, -50, 0
                    L1 = LINE / P1, PARLEL, (LINE / YAXIS)
                    L2 = LINE / P2, PERPTO, L1
                    L3 = LINE / P2, PARLEL, L1
                    L4 = LINE / P1, PERPTO, L1
                    C1 = CIRCLE / XSMALL, L1, YLARGE, L2, RADIUS, 30
                    C2 = CIRCLE / XLARGE, L3, YSMALL, L4, RADIUS, 20
                    PLAN1 = PLANE / 0, 0, 1, 0
                    PLAN2 = PLANE / PARLEL, PLAN1, ZSMALL, 16

                    $$ MOTION COMMANDS
                    LOAD / TOOL, 1
                    CUTTER / 20
                    SPINDL / 3000, CLW
                    FROM / (STRTPT = POINT / 70, 70, 0)
                    RAPID
                    GO / TO, L1, TO, PLAN2, TO, L4
                    FEDRAT / 900, PERMIN
                    TLLFT, GOLFT / L1, TANTO, C1
                    GOFWD / C1, TANTO, L2
                    GOFWD / L2, PAST, L3
                    GORGT / L3, TANTO, C2
                    GOFWD / C2, TANTO, L4
                    GOFWD / L4, PAST, L1
                    RAPID
                    GOTO / STRTPT
                    FINI
                EOD,
            ),
            'ARC Assembly' => array(),
            'ARITH-MATIC' => array(),
            'Actor' => array(),
            'Address programming language' => array(),
            'Alef' => array(
                'rawCode' => <<<'EOD'
                    (int, byte*, byte)
                    func()
                    {
                        return (10, "hello", 'c');
                    }

                    void
                    main()
                    {
                        int a;
                        byte* str;
                        byte c;
                        (a, str, c) = func();
                    }
                EOD,
            ),
            'Alma-0' => array(),
            'Amiga E' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Amiga-E.amiga-e',
            ),
            'AppleScript' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/AppleScript/fibonacci-sequence-1.applescript',
            ),
            'Applesoft BASIC' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Applesoft%20BASIC',
            ),
            'Applesoft II BASIC' => array(),
            'Applesoft III' => array(),
            'Arc' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ARC.arc',
            ),
            'AspectJ' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Aspectj.aj',
            ),
            'Atlas Autocode' => array(),
            'Autocode' => array(
                'rawCode' => <<<'EOD'
                    c@VA t@IC x@½C y@RC z@NC
                    INTEGERS +5 →c           # Put 5 into c
                        →t                 # Load argument from lower accumulator
                                            # to variable t
                    +t     TESTA Z        # Put |t| into lower accumulator
                    -t
                            ENTRY Z
                    SUBROUTINE 6 →z          # Run square root subroutine on
                                            # lower accumulator value
                                            # and put the result into z
                    +tt →y →x              # Calculate t^3 and put it into x
                    +tx →y →x
                    +z+cx   CLOSE WRITE 1    # Put z + (c * x) into
                                            # lower accumulator
                                            # and return
                EOD,
            ),
            'Apple III Microsoft BASIC' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Applesoft%20BASIC',
            ),
            'Altair BASIC' => array(
                'mainRepository' => 'https://github.com/option8/Altair-BASIC?utm_source=chatgpt.com',
                'rawCode' => <<<'EOD'
                    10 PRINT "HELLO WORLD"
                    20 LET A = 5
                    30 LET B = 7
                    40 LET C = A + B
                    50 PRINT "SUM = "; C
                    60 END
                EOD,
            ),
            'B' => array(
                'mainRepository' => 'https://github.com/bext-lang/b?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/B.b',
            ),
            'Boo' => array(
                'mainRepository' => 'https://github.com/boo-lang/boo',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/Boo.boo',
            ),
            'Ada ISO 8652:1987' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Ada/fibonacci-sequence-2.ada',
            ),
            'Birkbeck Assembler' => array(),
            'Business BASIC' => array(),
            'CL' => array(
                'rawCode' => <<<'EOD'
                    <CL-command> ::= command-name [<positional-parameter-list>] [<named-parameter-list>]

                    <positional-parameter-list> ::= <parameter-value> [<positional-parameter-list>]

                    <named-parameter-list> ::= parameter-name "(" <parameter-element-list> ")" [<named-parameter-list>]

                    <parameter-element-list> ::= <parameter-value> [<parameter-element-list>]

                    <parameter-value> ::= CL-name |
                                        qualified-CL-name |
                                        "*"special-value |
                                        generic-CL-name"*" |
                                        "'"alphanumeric-value"'" |
                                        numeric-value |
                                        "X'"hexadecimal-value"'"
                    EOD,
            ),
            'ANSI Common Lisp' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Common-Lisp/fibonacci-sequence-1.lisp',
            ),
            'Common Lisp' => array(
                'mainRepository' => 'https://github.com/sbcl/sbcl',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Common%20Lisp.lisp',
            ),
            'Coq' => array(
                'mainRepository' => 'https://github.com/rocq-prover/rocq?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Coq/fibonacci-sequence.coq',
            ),
            'Draco' => array(
                'mainRepository' => 'https://github.com/Draco-lang/Compiler',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/d/Draco.d',
            ),
            'Dylan' => array(
                'mainRepository' => 'https://github.com/dylan-lang/opendylan',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/d/Dylan.dl',
                'pictureUrl' => 'https://avatars.githubusercontent.com/u/490490?s=200&v=4',
            ),
            'Idris' => array(
                'mainRepository' => 'https://github.com/idris-lang',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Idris/fibonacci-sequence-3.idris',
            ),
            'Gambas' => array(
                'rawCode' => <<<'EOD'
                    Private Sub Test(X As Float) As Float

                        Dim Mu As Float = 10.0
                        Dim Pu, Su As Float
                        Dim I, J, N As Integer
                        Dim aPoly As New Float[100]

                        N = 500000

                        For I = 0 To N - 1
                            For J = 0 To 99
                            Mu =  (Mu + 2.0) / 2.0
                            aPoly[J] = Mu
                            Next
                            Su = 0.0
                            For J = 0 To 99
                                Su = X * Su + aPoly[J]
                            Next
                            Pu += Su
                        Next

                        Return Pu

                    End

                    Public Sub Main()

                        Dim I as Integer

                        For I = 1 To 10
                            Print Test(0.2)
                        Next

                    End
                EOD,
            ),
            'Clascal' => array(),
            'Bosque' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/Bosque.bsq',
            ),
            'Clarion' => array(
                'rawCode' => <<<'EOD'
                    PROGRAM
                    MAP
                    END
                    CODE
                    MESSAGE('Hello World!','Clarion')
                    RETURN
                EOD,
            ),
            'Klammerausdrücke' => array(),
            'Limbo' => array(
                'mainRepository' => 'https://github.com/inferno-os/inferno-os?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/l/Limbo.b',
            ),
            'Lasso' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Lasso/fibonacci-sequence.lasso',
            ),
            'Little b' => array(
                'mainRepository' => 'https://github.com/aneilbaboo/littleb?utm_source=chatgpt.com',
                'rawCode' => <<<'EOD'
                    ;;; Exemple (fragment) de Little b DSL (Lisp) :
                    (load "littleb.lisp")
                    (model:define model1
                    (species A B C)
                    (reaction r1 A B -> C :rate k1)
                    (parameter k1 0.1))
                EOD,
            ),
            'FreeBASIC' => array(
                'mainRepository' => 'https://github.com/freebasic/fbc',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/FreeBASIC/fibonacci-sequence.basic',
            ),
            'Haxe' => array(
                'mainRepository' => 'https://github.com/HaxeFoundation/haxe',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Haxe/fibonacci-sequence-2.haxe',
            ),
            'Joy' => array(
                'mainRepository' => 'https://github.com/xieyuheng/joy',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Joy/fibonacci-sequence-2.joy',
            ),
            'M2001' => array(
                'link' => null,
            ),
            'Magik' => array(
                'mainRepository' => 'https://github.com/StevenLooman/magik-tools',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Smallworld%20MAGIK.magik',
            ),
            'Mercury' => array(
                'mainRepository' => 'https://github.com/Mercury-Language/mercury?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Mercury/fibonacci-sequence-1.mercury',
                'pictureUrl' => 'https://upload.wikimedia.org/wikipedia/en/2/21/Mercury_%28programming_language%29_logo.jpg',
            ),
            'SPARK' => array(
                'mainRepository' => 'https://github.com/AdaCore/spark2014?utm_source=chatgpt.com',
            ),
            'Turbo Pascal' => array(),
            'Turbo Pascal OOP' => array(),
            'Vala' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Vala/fibonacci-sequence-2.vala',
            ),
            'Oxygene' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/o/Oxygene.pas',
            ),
            'Raku' => array(
                'mainRepository' => 'https://github.com/rakudo/rakudo?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Raku/fibonacci-sequence-4.raku',
            ),
            'Simula' => array(
                'mainRepository' => 'https://github.com/sergev/simula-compiler?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Simula/fibonacci-sequence.simula',
            ),
            'Simula 67' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Simula.sim',
            ),
            'Chapel' => array(
                'mainRepository' => 'https://github.com/chapel-lang/chapel',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Chapel/fibonacci-sequence.chapel',
            ),
            'MUMPS' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/MUMPS/fibonacci-sequence.mumps',
            ),
            'Parasail' => array(
                'mainRepository' => 'https://github.com/parasail-lang/parasail?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/g/Genie.gs',
            ),
            'AWK' => array(
                'mainRepository' => 'https://github.com/onetrueawk/awk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/AWK/fibonacci-sequence.awk',
            ),
            'Agda' => array(
                'mainRepository' => 'https://github.com/agda/agda',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Agda/fibonacci-sequence.agda',
            ),
            'Eiffel' => array(
                'mainRepository' => 'https://github.com/EiffelSoftware/EiffelStudio',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Eiffel/fibonacci-sequence.e',
            ),
            'Java' => array(
                'mainRepository' => 'https://github.com/openjdk/jdk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Java/fibonacci-sequence-4.java',
            ),
            'JavaScript' => array(
                'mainRepository' => 'https://github.com/tc39/ecma262',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/JavaScript/fibonacci-sequence-1.js',
            ),
            'PHP' => array(
                'mainRepository' => 'https://github.com/php/php-src',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/PHP/fibonacci-sequence-2.php',
            ),
            'Perl' => array(
                'mainRepository' => 'https://github.com/Perl/perl5',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Perl/fibonacci-sequence-4.pl',
            ),
            'QB64' => array(
                'mainRepository' => 'https://github.com/QB64Team/qb64?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/QB64/fibonacci-sequence-2.qb64',
            ),
            'Squirrel' => array(
                'mainRepository' => 'https://github.com/albertodemichelis/squirrel?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Squirrel.nut',
            ),
            'FLOW-MATIC' => array(
                'rawCode' => <<<'EOD'
                    (0)  INPUT INVENTORY FILE-A PRICE FILE-B ; OUTPUT PRICED-INV FILE-C UNPRICED-INV
                        FILE-D ; HSP D .
                    (1)  COMPARE PRODUCT-NO (A) WITH PRODUCT-NO (B) ; IF GREATER GO TO OPERATION 10 ;
                        IF EQUAL GO TO OPERATION 5 ; OTHERWISE GO TO OPERATION 2 .
                    (2)  TRANSFER A TO D .
                    (3)  WRITE-ITEM D .
                    (4)  JUMP TO OPERATION 8 .
                    (5)  TRANSFER A TO C .
                    (6)  MOVE UNIT-PRICE (B) TO UNIT-PRICE (C) .
                    (7)  WRITE-ITEM C .
                    (8)  READ-ITEM A ; IF END OF DATA GO TO OPERATION 14 .
                    (9)  JUMP TO OPERATION 1 .
                    (10)  READ-ITEM B ; IF END OF DATA GO TO OPERATION 12 .
                    (11)  JUMP TO OPERATION 1 .
                    (12)  SET OPERATION 9 TO GO TO OPERATION 2 .
                    (13)  JUMP TO OPERATION 2 .
                    (14)  TEST PRODUCT-NO (B) AGAINST ; IF EQUAL GO TO OPERATION 16 ;
                            OTHERWISE GO TO OPERATION 15 .
                    (15)  REWIND B .
                    (16)  CLOSE-OUT FILES C ; D .
                    (17)  STOP . (END)
                EOD
            ),
            'BLISS' => array(
                'mainRepository' => 'https://github.com/madisongh/blissc?tab=readme-ov-file',
            ),
            'COBOL' => array(
                'mainRepository' => 'https://github.com/OCamlPro/gnucobol',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/COBOL/fibonacci-sequence-1.cobol',
            ),
            'D' => array(
                'mainRepository' => 'https://github.com/dlang/dmd',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/D/fibonacci-sequence-2.d',
            ),
            'Dart' => array(
                'mainRepository' => 'https://github.com/dart-lang/sdk',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Dart/fibonacci-sequence-3.dart',
            ),
            'Elixir' => array(
                'mainRepository' => 'https://github.com/elixir-lang/elixir',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Elixir/fibonacci-sequence-1.ex',
            ),
            'COWSEL' => array(
                'rawCode' => <<<'EOD'
                    function member
                    lambda x y
                    comment Is x a member of list y;
                    define      y atom then *0 end
                                y hd x equal then *1 end
                                y tl -> y repeat up
                EOD
            ),
            // TODO update / check
            'R' => array(
                'mainRepository' => 'https://github.com/wch/r-source',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/R.R',
            ),
            'Python' => array(
                'mainRepository' => 'https://github.com/python/cpython',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/Python%203.py',
            ),
            'C#' => array(
                'mainRepository' => 'https://github.com/dotnet/roslyn',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/CSharp.cs',
            ),
            'Erlang' => array(
                'mainRepository' => 'https://github.com/erlang/otp',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Erlang/fibonacci-sequence-4.erl',
            ),
            'Nim' => array(
                'mainRepository' => 'https://github.com/nim-lang/Nim',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Nim/fibonacci-sequence-4.nim',
            ),
            'Crystal' => array(
                'mainRepository' => 'https://github.com/crystal-lang/crystal',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Crystal/fibonacci-sequence-2.cr',
            ),
            'Clojure' => array(
                'mainRepository' => 'https://github.com/clojure/clojure',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Clojure/fibonacci-sequence-3.clj',
            ),
            'REFAL' => array(
                'mainRepository' => 'https://github.com/bmstu-iu9/refal-5-lambda/blob/master/README.en.md',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Refal/fibonacci-sequence.refal',
            ),
            'SNOBOL' => array(
                'mainRepository' => 'https://github.com/seanpm2001/Learn-SNOBOL?tab=readme-ov-file#Version-history',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/SNOBOL4/fibonacci-sequence-5.sno',
            ),
            'APL' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/APL/fibonacci-sequence-2.apl',
            ),
            'SNOBOL' => array(
                'mainRepository' => 'https://github.com/seanpm2001/Learn-SNOBOL?tab=readme-ov-file#Version-history',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SNOBOL',
            ),
            'FORTRAN' => array(
                'mainRepository' => 'https://github.com/fortran-lang',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Fortran/fibonacci-sequence-3.f',
            ),
            'Haskell' => array(
                'mainRepository' => 'https://github.com/ghc/ghc',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Haskell/fibonacci-sequence-1.hs',
            ),
            'Ruby' => array(
                'mainRepository' => 'https://github.com/ruby/ruby',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Ruby/fibonacci-sequence-2.rb',
            ),
            'Groovy' => array(
                'mainRepository' => 'https://github.com/apache/groovy',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Groovy/fibonacci-sequence-4.groovy',
            ),
            'Go' => array(
                'mainRepository' => 'https://github.com/golang/go',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/g/Go.go',
            ),
            'BCPL' => array(
                'mainRepository' => 'https://github.com/8l/bcpl',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/BCPL.bcl',
            ),
            'Ballerina' => array(
                'mainRepository' => 'https://github.com/ballerina-platform/ballerina-lang',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/Ballerina.bal',
            ),
            'Julia' => array(
                'mainRepository' => 'https://github.com/JuliaLang/julia',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Julia/fibonacci-sequence-1.jl',
            ),
            'ActionScript' => array(
                'mainRepository' => 'https://github.com/adobe/avmplus',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ActionScript.as',
            ),
            'Kotlin' => array(
                'mainRepository' => 'https://github.com/JetBrains/kotlin',
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Kotlin/fibonacci-sequence.kts',
                'pictureUrl' => 'https://kotlinfoundation.org/static/kotlin-logo-e561bb24367c5fce5fcdaedb726b621b.png',
            ),
            'MATLAB' => array(
                'mainRepository' => 'https://github.com/mathworks/MATLAB-Language-grammar',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/m/MATLAB.m',
            ),
            'ABC' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ABC.abc',
            ),
            'Elm' => array(
                'mainRepository' => 'https://github.com/elm/compiler',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/e/Elm.elm',
            ),
            'Lua' => array(
                'mainRepository' => 'https://github.com/lua/lua',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/l/Lua.lua',
            ),
            'Racket' => array(
                'mainRepository' => 'https://github.com/racket/racket',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Racket.rkt',
            ),
            'Swift' => array(
                'mainRepository' => 'https://github.com/apple/swift',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Swift.swift',
            ),
            'OCaml' => array(
                'mainRepository' => 'https://github.com/ocaml/ocaml',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/o/OCaml.ml',
            ),
            'Zig' => array(
                'mainRepository' => 'https://github.com/ziglang/zig',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/z/Zig.zig',
            ),
            'ABAP' => array(
                'mainRepository' => 'https://github.com/SAP/abap-cleaner',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/ABAP.abap',
            ),
            'BBC BASIC' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/b/BBC%20BASIC.bbc',
            ),
            'F#' => array(
                'mainRepository' => 'https://github.com/dotnet/fsharp',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/f/F%23.fs',
            ),
            'Rust' => array(
                'mainRepository' => 'https://github.com/rust-lang/rust',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Rust.rs',
            ),
            'TypeScript' => array(
                'mainRepository' => 'https://github.com/microsoft/TypeScript',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/t/TypeScript.ts',
            ),
            'Scheme' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Scheme.scm',
            ),
            'Hack' => array(
                'mainRepository' => 'https://github.com/facebook/hhvm',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/h/Hack.hh',
            ),
            'LISP' => array(
                'mainRepository' => null,
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/l/Lisp.lsp',
            ),
            'Scala' => array(
                'mainRepository' => 'https://github.com/scala/scala',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/Scala.scala',
            ),
            'C' => array(
                'mainRepository' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C90' => array(
                'mainRepository' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C99' => array(
                'mainRepository' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C11' => array(
                'mainRepository' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C17' => array(
                'mainRepository' => 'https://github.com/gcc-mirror/gcc?utm_source=chatgpt.com',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C.c',
            ),
            'C++03' => array(
                'mainRepository' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++11' => array(
                'mainRepository' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++14' => array(
                'mainRepository' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++17' => array(
                'mainRepository' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'C++20' => array(
                'mainRepository' => 'https://github.com/llvm/llvm-project',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/C%2B%2B.cpp',
            ),
            'AMOS BASIC' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Amos',
            ),
            'A-0' => array(),
            'ALGOL 58' => array(),
            'Scratch' => array(
                'mainRepository' => 'https://github.com/LLK/scratch-vm',
            ),
            'Smalltalk-80' => array(
                'mainRepository' => 'https://github.com/pharo-project/pharo',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SmallTalk.sm',
            ),
            'Windows PowerShell' => array(
                'mainRepository' => 'https://github.com/PowerShell/PowerShell',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/PowerShell.ps1',
            ),
            'Visual Basic .NET' => array(
                'mainRepository' => 'https://github.com/dotnet/roslyn',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/v/Visual%20Basic.vb',
            ),
            'SQL' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/s/SQL.sql',
            ),
            'Objective-C' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/o/Objective%20C.m',
            ),
            'PostScript' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/PostScript.ps',
            ),
            'Miranda' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/m/Miranda.m',
            ),
            'Red' => array(
                'mainRepository' => 'https://github.com/red/red',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Red.red',
            ),
            'Ceylon' => array(
                'mainRepository' => 'https://github.com/eclipse-archived/ceylon',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Ceylon.ceylon',
            ),
            'Genie' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/ParaSail.psi',
            ),
            'Forth' => array(
                'mainRepository' => 'https://github.com/Forth-Standard/forth-standard',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/f/Forth.fth',
            ),
            'Ada 83' => array(
                'mainRepository' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 95' => array(
                'mainRepository' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 2005' => array(
                'mainRepository' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Ada 2012' => array(
                'mainRepository' => 'https://github.com/ohenley/awesome-ada',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Ada.adb',
            ),
            'Borland Pascal' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/p/Pascal.p',
            ),
            'ALGAE' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/a/Algae.algae',
            ),
            'CORAL66' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/c/Coral%2066.cor',
            ),
            'Icon' => array(
                'rawCodeLink' => 'https://raw.githubusercontent.com/acmeism/RosettaCodeData/refs/heads/main/Task/Fibonacci-sequence/Icon/fibonacci-sequence-1.icon',
            ),
            'SETL' => array(
                'rawCode' => <<<'EOD'
                    procedure factorial(n); -- calculates the factorial n!
                    return if n = 1 then 1 else n * factorial(n - 1) end if;
                    end factorial;
                EOD
            ),
            'JOVIAL' => array(
                'rawCode' => <<<'EOD'
                    PROC RETRIEVE(CODE:VALUE);
                    BEGIN
                    ITEM CODE U;
                    ITEM VALUE F;
                    VALUE = -99999.;
                    FOR I:0 BY 1 WHILE I<1000;
                        IF CODE = TABCODE(I);
                            BEGIN
                            VALUE = TABVALUE(I);
                            EXIT;
                            END
                    END
                EOD
            ),
            // TODO empty
            'BASIC FOUR' => array(),
            'Boehm unnamed coding system' => array(),
            'CLIPPER' => array(),
            'CLU' => array(),
            'COMAL' => array(),
            'COMIT' => array(),
            'COMPOOL' => array(),
            'COMTRAN' => array(),
            'CPC Coding scheme' => array(),
            'CPL' => array(),
            'CS-4' => array(),
            'Claire' => array(),
            'Bourne Shell' => array(),
            'Brainfuck' => array(),
            'C with classes' => array(),
            'C++' => array(),
            'Caml Light' => array(),
            'Clean' => array(),
            'Boo' => array(),
            'Cobra' => array(),
            'CoffeeScript' => array(),
            'Commodore BASIC' => array(),
            'Compiler Description Language' => array(),
            'Component Pascal' => array(),
            'CorVision' => array(),
            'Curry notation system' => array(),
            'DIBOL-8' => array(),
            'DarkBasic' => array(),
            'Digital Research' => array(),
            'E' => array(),
            'ECL' => array(),
            'ECMAScript' => array(),
            'ENIAC Short Code' => array(),
            'Edinburgh IMP' => array(),
            'Editing Generator' => array(),
            'Epigram' => array(),
            'Euclid' => array(),
            'F-Script' => array(),
            'FACT' => array(),
            'FP' => array(),
            'Curl' => array(),
            'ENIAC coding system' => array(),
            'EXAPT' => array(),
            'Euphoria' => array(),
            'Factor' => array(),
            'Fantom' => array(),
            'Fortress' => array(),
            'Freiburger Code' => array(),
            'GEORGE' => array(),
            'GNU E' => array(),
            'GRASS' => array(),
            'Game Maker Language' => array(),
            'Glennie Autocode' => array(),
            'Gosu' => array(),
            'Harbour' => array(),
            'Hopscotch' => array(),
            'HyperTalk' => array(),
            'IBM BASICA' => array(),
            'IBM RPG' => array(),
            'IDL' => array(),
            'IITRAN' => array(),
            'IPL I' => array(),
            'IPL II' => array(),
            'IPL V' => array(),
            'ISLISP' => array(),
            'ISO/IEC 8652:1995/Amd 1:2007' => array(),
            'ISWIM' => array(),
            'IT' => array(),
            'Informix-4GL' => array(),
            'Integer BASIC' => array(),
            'InterLisp' => array(),
            'Irvine Dataflow' => array(),
            'GFA BASIC' => array(),
            'GW-BASIC' => array(),
            'HAL/S' => array(),
            'INTERCAL' => array(),
            'J' => array(),
            'JOSS I' => array(),
            'Join Java' => array(),
            'K' => array(),
            'KRL' => array(),
            'LIS' => array(),
            'LPC' => array(),
            'Laning and Zierler system' => array(),
            'LiveCode Transcript' => array(),
            'Logtalk' => array(),
            'MAD' => array(),
            'MAD/I' => array(),
            'MAPPER' => array(),
            'MATH-MATIC' => array(),
            'MATRIX MATH' => array(),
            'MIMIC' => array(),
            'ML' => array(),
            'Mark I Autocode' => array(),
            'Mark-IV' => array(),
            'Mesa' => array(),
            'Microsoft Power Fx' => array(),
            'Modula' => array(),
            'K&R C' => array(),
            'LOGO' => array(),
            'LOLCODE' => array(),
            'LiveScript' => array(),
            'Mathematica' => array(),
            'Modula-2' => array(),
            'Napier88' => array(),
            'NetRexx' => array(),
            'Newsqueak' => array(),
            'NewtonScript' => array(),
            'OMNIBAC Symbolic Assembler' => array(),
            'OPL' => array(),
            'Object Oberon' => array(),
            'Object Pascal' => array(),
            'OptimJ' => array(),
            'Oz' => array(),
            'PACT I' => array(),
            'PARADOX' => array(),
            'PILOT' => array(),
            'PROMAL' => array(),
            'POP-1' => array(),
            'POP-2' => array(),
            'PROSE modeling language' => array(),
            'Pascal' => array(),
            'Perl Data Language' => array(),
            'Pico' => array(),
            'Pike' => array(),
            'Plankalkül' => array(),
            'Plus' => array(),
            'Polymorphic Programming Language' => array(),
            'Processing' => array(),
            'Prolog' => array(),
            'Oberon-07' => array(),
            'Object REXX' => array(),
            'Octave' => array(),
            'Opa' => array(),
            'P' => array(),
            'P4' => array(),
            'PL/I' => array(),
            'PWCT' => array(),
            'P′′' => array(),
            'Quel (Ingres)' => array(),
            'RAPID' => array(),
            'READ/PRINT' => array(),
            'REBOL' => array(),
            'RPG II' => array(),
            'RPG III' => array(),
            'Ratfor' => array(),
            'Regional Assembly Language' => array(),
            'Rochester assembler' => array(),
            'S' => array(),
            'SAIL' => array(),
            'SAM76' => array(),
            'SAS' => array(),
            'SMALL' => array(),
            'Sather' => array(),
            'Seed7' => array(),
            'Sequentielle Formelübersetzung' => array(),
            'Shakespeare Programming Language' => array(),
            'PureScript' => array(),
            'RAPT' => array(), // nothing
            'RPL' => array(),
            'Reason' => array(
                'mainRepository' => 'https://github.com/reasonml/reason',
                'rawCodeLink' => 'https://raw.githubusercontent.com/leachim6/hello-world/refs/heads/main/r/Reason.re',
            ),
            'S-Lang' => array(),
            'STOS BASIC' => array(),
            'Short Code' => array(),
            'Smalltalk-76' => array(),
            'Sort Merge Generator' => array(),
            'Space Programming Language' => array(),
            'Speedcoding' => array(), // nothing
            'Standard ML' => array(),
            'Standard MUMPS' => array(),
            'Stanislaus' => array(),
            'StarLogo' => array(),
            'Sue' => array(),
            'Superplan' => array(),
            'TCL' => array(),
            'TELCOMP' => array(),
            'TRAC' => array(),
            'TTM' => array(),
            'TUTOR' => array(),
            'Tea' => array(),
            'VBScript' => array(),
            'VisSim' => array(),
            'Von Neumann and Goldstine graphing system' => array(),
            'Vulcan dBase-II' => array(),
            'Whiley' => array(),
            'Whirlwind assembler' => array(),
            'Smalltalk-72' => array(),
            'Speakeasy-2' => array(),
            'Speakeasy-3' => array(),
            'Squeak' => array(),
            'Standard C++' => array(),
            'True BASIC' => array(),
            'Turbo Basic' => array(),
            'VisiCalc' => array(),
            'XPL' => array(),
            'XSLT' => array(),
            'ZPL' => array(),
            '[10]' => array(),
            'A' => array(),
            'A+' => array(),
            'Ada 80' => array(),
            'AgentSheets' => array(),
            'BASIC' => array(),
            'Bash' => array(),
            'Borland Delphi' => array(),
            'Bourne shell' => array(),
            'C shell' => array(),
            'CBASIC' => array(),
            'COBOL 61' => array(),
            'ColdFusion' => array(),
            'Cuneiform' => array(),
            'Dafny' => array(),
            'EDSAC Initial Orders' => array(),
            'occam 2' => array(),
            'EuLisp' => array(),
            'Fortran 2018' => array(),
            'GDScript' => array(),
            'GPSS' => array(),
            'Hamilton C shell' => array(),
            'Io' => array(),
            'JOSS II' => array(),
            'Jacquard machine' => array(),
            'KornShell' => array(),
            'LabVIEW' => array(),
            'Modula-3' => array(),
            'Nemerle' => array(),
            'Oberon' => array(),
            'Oberon-2' => array(),
            'PL/M' => array(),
            'PowerBASIC' => array(),
            'Pure' => array(),
            'PureBasic' => array(),
            'Q' => array(),
            'QuickBASIC' => array(),
            'REXX' => array(),
            'Redcode' => array(),
            'Self' => array(),
            'Speakeasy' => array(),
            'Speakeasy-IV' => array(),
            'Subtext' => array(),
            'Turing' => array(),
            'UnrealScript' => array(),
            'Visual Basic' => array(),
            'Xojo' => array(),
            'Z Shell' => array(),
            'occam' => array(),
            'Dart' => array(),
            'Common Lisp' => array(),
            'Windows PowerShell' => array(),
        );

        DB::transaction(function () use ($langsData) {
            foreach ($langsData as $key => $value) {
                if (array_key_exists('rawCodeLink', $value)) {
                    $value['codeTitle'] = str_contains($value['rawCodeLink'], 'RosettaCodeData')
                        ? 'Fibonacci'
                        : (str_contains($value['rawCodeLink'], 'hello-world') ? 'Hello worlds' : null);
                }

                if (count($value) > 0) {
                    DB::table('pro_langs')
                        ->where('name', $key)
                        ->update($value);
                }
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
