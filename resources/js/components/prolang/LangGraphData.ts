import type { Edges, Layouts, Nodes } from "v-network-graph"
import type { ProLangLanguage } from "@/types/main"

function langNameMatch(lang: ProLangLanguage, search: string) {
    return lang.name.toLowerCase().includes(search.toLowerCase())
}

export function isMemberOf(search: string, lang: ProLangLanguage): boolean {
    return (
        lang.parents.some((l) => langNameMatch(l, search)) ||
        lang.children.some((l) => langNameMatch(l, search)) ||
        langNameMatch(lang, search)
    )
}

export function getGraphData(prolangs: ProLangLanguage[]) {
    const orphan = prolangs.filter((l) => l.parents.length === 0)
    const smala = prolangs.filter((l) => l.parents.length > 0)

    const edges: Edges = {}
    const nodes: Nodes = {
        humain: { name: "Human" },
    }

    for (const l of prolangs) {
        nodes[l.name] = { name: l.name }
    }

    for (const l of orphan) {
        edges[l.name] = {
            source: "humain",
            target: l.name,
        }
    }

    for (const l of smala) {
        l.parents.forEach((p) => {
            edges[`${l.name}_${p.name}`] = {
                source: p.name,
                target: l.name,
            }
        })
    }

    return { nodes, edges }
}
