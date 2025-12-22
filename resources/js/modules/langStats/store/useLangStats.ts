import { useStore } from "@nanostores/vue"
import { LangStatsStore } from "./langStatsStore"

export function useLangStats() {
    const statsStore = LangStatsStore.getStore()
    const langStats = useStore(statsStore.$langStats)
    const selectedLang = useStore(statsStore.$selectedLang)
    const selectedType = useStore(statsStore.$selectedType)
    const typeList = useStore(statsStore.$typeList)
    const langList = useStore(statsStore.$langList)
    const oldestRepository = useStore(statsStore.$oldestRepository)
    const starredRepository = useStore(statsStore.$starredRepository)
    const recentRepository = useStore(statsStore.$recentRepository)
    const isLoading = useStore(statsStore.$isLoading)

    return {
        statsStore,

        langStats,
        selectedLang,
        selectedType,
        typeList,
        langList,
        oldestRepository,
        starredRepository,
        isLoading,
        recentRepository,
    }
}
