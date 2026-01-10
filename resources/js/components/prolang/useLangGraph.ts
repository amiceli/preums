import { useStore } from "@nanostores/vue"
import { LangGraphStore } from "./LangGraphStore"

export function useLangGraph() {
    const graphStore = LangGraphStore.getInstance()
    const graphData = useStore(graphStore.$graphData)
    const hasName = useStore(graphStore.$hasName)

    return {
        graphStore,
        graphData,
        hasName,
    }
}
