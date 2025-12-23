<template>
    <Layout>
        <div class="road">
            <div class="road__title">
                <div>
                    <i class="hn hn-code"></i>
                </div>
                <div>
                    <h1>
                        Having ideas, choosing a framework, building, improving,
                        correcting, these are the best moments. <br />
                    </h1>
                    <h2>
                        Let me show you <b class="for--info">Preums</b> road.
                        <br />
                        <small>
                            You don't need <b class="for--error">IA</b> to build
                            a project. <br />
                        </small>
                    </h2>
                </div>
            </div>
            <br />
            <h1>Built on</h1>
            <p>
                I have already worked with several frameworks like :
                <a target="_blank" href="https://github.com/silexphp/Silex"
                    >Silex</a
                >,&nbsp;
                <a target="_blank" href="https://github.com/nestjs/nest"
                    >NestJs</a
                >,&nbsp;
                <a target="_blank" href="https://github.com/expressjs/express"
                    >Express</a
                >,&nbsp;
                <a target="_blank" href="https://github.com/mojolicious/mojo"
                    >Mojo</a
                >,&nbsp; etc.
                <br />
                But, for me, Laravel and Vue are best. <br />
                And Inertia is a beautiful fusion ❤️. <br />
                Finish with Sqlite, best solution when we need a little
                database.
            </p>
            <div class="road__repos">
                <div v-for="b in props.builtOn" :key="b.name">
                    <RepositoryCard :repository="b" show-avatar />
                </div>
            </div>
            <h1>Tools to make beautiful code</h1>
            <p>
                After lot of years with Eslint, Biome has appeared. <br />
                As long as I can get rid of the semicolons and use four spaces,
                it's perfect. <br />
                First project with Pint, perfect choice.
            </p>
            <div class="road__repos">
                <div v-for="b in props.beautifulCode" :key="b.name">
                    <RepositoryCard :repository="b" show-avatar />
                </div>
            </div>
            <h1>Make beautiful UI / UX</h1>
            <p>
                Choosing a UI framework is the most time-consuming part, but
                it's one of my favorite moments. <br />
                Web component, only CSS, Vue, it's a perfect challenge.
                <br />
                It was time to do a project with WebAwesome, the successor to
                Shoelace.
            </p>
            <div class="road__repos">
                <div v-for="b in props.UiUx" :key="b.name">
                    <RepositoryCard :repository="b" show-avatar />
                </div>
            </div>
            <h1>Keep clean repository</h1>
            <p>
                And finally, even for side projects, tests, and conventional
                commits, this is the basis!
            </p>
            <div class="road__repos">
                <div v-for="b in props.cleanRepo" :key="b.name">
                    <RepositoryCard :repository="b" show-avatar />
                </div>
            </div>
            <h1>Discoveries and surprises</h1>
            <p>
                Testing and coding on <b class="for--info">Preums</b>, I found
                cool projects :
            </p>
            <ul>
                <li>
                    <a
                        target="_blank"
                        href="https://github.com/sdkman/sdkman-cli"
                        class="for--info"
                    >
                        sdkman-cli
                    </a>
                    is most starred project with Gherkin as main language and I
                    love Gherkin !
                    <br />
                    Now is the time to advertise
                    <a
                        target="_blank"
                        href="https://github.com/amiceli/vitest-cucumber"
                    >
                        vitest-cucumber
                    </a> ❤️.
                </li>
                <li>
                    <a
                        target="_blank"
                        href="https://github.com/vwh/sqlite-online"
                        class="for--info"
                    >
                        sqlite-online
                    </a>
                    perfect to view a SQLite file
                </li>
                <li>
                    Discovered
                    <a
                        target="_blank"
                        href="https://github.com/leanprover/lean4"
                        class="ior--info"
                    >
                        Lean language </a
                    >. Wow, code is
                    <a
                        target="_blank"
                        href="https://github.com/htzh/leanproved/blob/master/migrated/algebra.lean"
                    >
                        beautiful </a
                    >.
                </li>
            </ul>
            <p>
                And something cool like VueJS was called SeedJs and Astro was
                <a
                    class="for--info"
                    target="_blank"
                    href="https://github.com/withastro/astro/commit/47d1c22449cb2c3a236170382ae9e8210a711ee0"
                >
                    magicthing !
                </a>
            </p>
            <div class="road__repos">
                <div v-for="b in props.surprises" :key="b.name">
                    <RepositoryCard :repository="b" show-avatar />
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup lang="ts">
import Layout from "@/components/Layout.vue"
import RepositoryCard from "@/components/RepositoryCard.vue"
import type { Road } from "@/types/github"

const props = defineProps<Road>()
</script>

<style lang="scss" scoped>
.road {
    &__repos {
        display: grid;
        grid-template-columns: auto auto auto;
        justify-content: space-between;
        gap: 30px;
    }

    &__title {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 40px;
        align-items: center;
        padding-top: 40px;

        .hn {
            font-size: 200px;
        }

        img {
            border-radius: 10px;
            width: 200px;
        }
    }

    h1 {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    h1 + p {
        font-size: 22px;
    }
}
</style>
