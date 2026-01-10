import { atom, computed, type ReadableAtom } from "nanostores"
import type { ProLangLanguage } from "@/types/main"
import { getGraphData, isMemberOf } from "./LangGraphData"

export class LangGraphStore {
    private readonly prolangs = atom<ProLangLanguage[]>([])

    private readonly hasName = atom<string | null>(null)

    // computed

    private readonly $prolangs = computed([this.prolangs, this.hasName], (prolangs, hasName) => {
        return prolangs.filter((l) => (hasName ? isMemberOf(hasName, l) : true))
    })

    public readonly $graphData = computed(this.$prolangs, (langs) => {
        return getGraphData(langs)
    })

    public get $hasName(): ReadableAtom<string | null> {
        return this.hasName
    }

    // methods

    public initGraph(langs: ProLangLanguage[]) {
        this.prolangs.set(langs)
    }

    public searchByName(value: string) {
        this.hasName.set(value)
    }

    // instance

    private static instance?: LangGraphStore

    public static getInstance(): LangGraphStore {
        if (!LangGraphStore.instance) {
            LangGraphStore.instance = new LangGraphStore()
        }

        return LangGraphStore.instance
    }
}
