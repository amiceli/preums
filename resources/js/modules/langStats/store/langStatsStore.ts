import { atom, computed, type ReadableAtom } from "nanostores"
import type { LangStats } from "@/types/github"

export class LangStatsStore {
    private readonly selectedLang = atom<string>("")

    private readonly selectedType = atom<string>("")

    private readonly langStats = atom<LangStats[]>([])

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

    public get $selectedLang(): ReadableAtom<string> {
        return this.selectedLang
    }

    public get $langStats(): ReadableAtom<LangStats[]> {
        return this.langStats
    }

    public get $selectedType(): ReadableAtom<string> {
        return this.selectedType
    }

    // instances

    private static instance?: LangStatsStore

    private constructor() {}

    public static getStore(): LangStatsStore {
        if (!LangStatsStore.instance) {
            LangStatsStore.instance = new LangStatsStore()
        }

        return LangStatsStore.instance
    }
}
