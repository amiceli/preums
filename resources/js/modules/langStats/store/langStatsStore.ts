import axios from "axios"
import { atom, computed, type ReadableAtom } from "nanostores"
import { searchOldestRepository, searchRecentRepository, searchStarredRepository } from "@/actions/App/Http/Controllers/GithubController"
import type { GithubRepository, LangStats } from "@/types/main"

export class LangStatsStore {
    private readonly selectedLang = atom<string>("")

    private readonly selectedType = atom<string>("")

    private readonly langStats = atom<LangStats[]>([])

    private readonly isLoading = atom<boolean>(false)

    private readonly starredRepository = atom<GithubRepository | null>(null)

    private readonly oldestRepository = atom<GithubRepository | null>(null)

    private readonly recentRepository = atom<GithubRepository | null>(null)

    public readonly $typeList = computed(this.langStats, (list) => {
        return [...new Set(list.map((t) => t.type))]
    })

    public readonly $langList = computed([this.langStats, this.selectedType], (list, selectedType) => {
        const matchLangNames = list.filter((langStat) => langStat.type === selectedType).map((langStat) => langStat.name)

        return [...new Set(matchLangNames)]
    })

    public updateLangStats(stats: LangStats[]) {
        this.langStats.set(stats)
    }

    public selectType(type: string) {
        this.selectedType.set(type)
    }

    public chooseLang(lang: string) {
        this.selectedLang.set(lang)
    }

    public async loadRepositories() {
        if (this.selectedLang.get()) {
            this.isLoading.set(true)

            await Promise.all([
                axios
                    .post<GithubRepository>(searchStarredRepository.url(), {
                        lang: this.selectedLang.get(),
                    })
                    .then(({ data }) => {
                        this.starredRepository.set(data)
                    }),
                axios
                    .post<GithubRepository>(searchRecentRepository.url(), {
                        lang: this.selectedLang.get(),
                    })
                    .then(({ data }) => {
                        this.recentRepository.set(data)
                    }),
                axios
                    .post<GithubRepository>(searchOldestRepository.url(), {
                        lang: this.selectedLang.get(),
                    })
                    .then(({ data }) => {
                        this.oldestRepository.set(data)
                    }),
            ])

            setTimeout(() => {
                this.isLoading.set(false)
            }, 750)
        }
    }

    public get $selectedLang(): ReadableAtom<string> {
        return this.selectedLang
    }

    public get $isLoading(): ReadableAtom<boolean> {
        return this.isLoading
    }

    public get $langStats(): ReadableAtom<LangStats[]> {
        return this.langStats
    }

    public get $selectedType(): ReadableAtom<string> {
        return this.selectedType
    }

    public get $starredRepository(): ReadableAtom<GithubRepository | null> {
        return this.starredRepository
    }

    public get $oldestRepository(): ReadableAtom<GithubRepository | null> {
        return this.oldestRepository
    }

    public get $recentRepository(): ReadableAtom<GithubRepository | null> {
        return this.recentRepository
    }

    // instances

    private static instance?: LangStatsStore

    private constructor() {
        this.selectedLang.listen(() => {
            this.loadRepositories()
        })
    }

    public static getStore(): LangStatsStore {
        if (!LangStatsStore.instance) {
            LangStatsStore.instance = new LangStatsStore()
        }

        return LangStatsStore.instance
    }
}
